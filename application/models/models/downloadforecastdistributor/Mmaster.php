<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu, $folder, $dfrom, $dto)
	{
		$id_company = $this->session->userdata('id_company');
		$cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_forecast_distributor
            WHERE
                i_status <> '5'
                and to_date(periode,'YYYYmm') between to_date('$dfrom','01-mm-yyyy') and to_date('$dto','01-mm-yyyy') and  id_company = '$id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        /*AND i_level = '" . $this->session->userdata('i_level') . "'*/
                        AND username = '" . $this->session->userdata('username') . "'
                        AND id_company = '$id_company')

        ", FALSE);
		if ($this->session->userdata('i_departement') == '1') {
			$bagian = "";
		} else {
			if ($cek->num_rows() > 0) {
				$i_bagian = $cek->row()->i_bagian;
				$bagian = "AND a.i_bagian = '$i_bagian' ";
			} else {
				$bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        /*AND i_level = '" . $this->session->userdata('i_level') . "'*/
                        AND username = '" . $this->session->userdata('username') . "'
                        AND id_company = '$id_company')";
			}
		}

		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("

         		WITH cte AS (
         			SELECT 0 AS NO, 
		            a.id, a.id_customer, c.e_bagian_name, b.e_customer_name, bulan(to_date(a.periode, 'YYYYmm')) || ' ' || substring(a.periode,1,4) as periode,  
		            substring(a.periode,1,4) as tahun, substring(a.periode,5,6) as bulan,a.i_status, e_status_name, label_color,  a.i_bagian, '$i_menu' as i_menu, '$folder' as folder, '$dfrom' AS dfrom, '$dto' AS dto, e.i_level, l.e_level_name, to_char(a.d_entry, 'YYYY-MM-DD HH24:MI:SS') AS d_entry, to_char(a.d_update, 'YYYY-MM-DD HH24:MI:SS') AS d_update
					from
					tm_forecast_distributor a
					inner join tr_customer b on (a.id_customer = b.id)
					inner join tr_bagian c on (a.i_bagian = c.i_bagian and a.id_company = c.id_company)
					inner join tr_status_document d on (a.i_status = d.i_status)
					left join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
					left join public.tr_level l on (e.i_level = l.i_level)
					where to_date(a.periode,'YYYYmm') between to_date('$dfrom','01-mm-yyyy') and to_date('$dto','01-mm-yyyy') and a.i_status <> '5' and a.id_company = '$id_company' $bagian
         		)
         		select NO, id, id_customer, e_bagian_name, e_customer_name, periode, tahun, bulan, coalesce(total, 0) as total, d_entry, d_update, i_status, e_status_name, label_color, i_bagian, i_menu, folder, dfrom, dto, i_level, e_level_name from cte
         		left join (
         			select id_forecast, sum(n_quantity) as total from tm_forecast_distributor_item where id_company = '$id_company' and id_forecast in (select id from cte) group by 1
         		) a on (cte.id = a.id_forecast)
         		order by a.total desc NULLS LAST
				
			
			
        ", FALSE);

		$datatables->edit('e_status_name', function ($data) {
			$i_status = $data['i_status'];
			if ($i_status == '2') {
				$data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
			}
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

		// $datatables->edit('i_reff', function ($data) {
		//     return '<span>'.str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['i_reff']))).'</span>';
		// });

		$datatables->add('action', function ($data) {
			$id       = $data['id'];
			$i_menu   = $data['i_menu'];
			$folder   = $data['folder'];
			$dfrom    = $data['dfrom'];
			$dto      = $data['dto'];
			$i_status = $data['i_status'];
			$i_bagian     = $data['i_bagian'];
			$id_customer  = $data['id_customer'];
			$tahun        = $data['tahun'];
			$bulan        = $data['bulan'];
			$i_level      = $data['i_level'];
			$data     = '';

			if (check_role($i_menu, 2)) {
				$data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$i_bagian/$id_customer/$tahun/$bulan/$dfrom/$dto/$id/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
			}

			if (check_role($i_menu, 3) /*&& ($tahun . $bulan) > date('Ym')*/) {
				if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
					$data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$i_bagian/$id_customer/$tahun/$bulan/$dfrom/$dto/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
				}
			}

			if (check_role($i_menu, 7) && $i_status == '2') {
			    if (($i_level == $this->i_level || $this->i_level == 1) ) {
			        $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$i_bagian/$id_customer/$tahun/$bulan/$dfrom/$dto/$id\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
			    }
			}   

			if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            }

			return $data;
		});
		$datatables->hide('id');
		$datatables->hide('i_menu');
		$datatables->hide('folder');
		$datatables->hide('dfrom');
		$datatables->hide('dto');
		$datatables->hide('id_customer');
		$datatables->hide('i_bagian');
		$datatables->hide('tahun');
		$datatables->hide('bulan');
		$datatables->hide('i_status');
		$datatables->hide('label_color');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
		return $datatables->generate();
	}

    public function get_bagian($ibagian, $idcompany)
	{
		$this->db->select(" i_bagian, e_bagian_name from tr_bagian where i_bagian = '$ibagian' and id_company = '$idcompany' ", false);
		return $this->db->get();
	}

	public function get_customer($idcustomer, $idcompany, $tahun, $bulan)
	{
		$this->db->select(" id, e_customer_name , '$tahun' as tahun, '$bulan' as ibulan, bulan( ('$tahun'||'-'||'$bulan'||'-01')::date) as bulan from tr_customer where id = '$idcustomer'", false);
		return $this->db->get();
	}

	public function dataheader($idcompany, $idcustomer, $periode, $id)
	{
		$this->db->select("id, i_status from tm_forecast_distributor where id_company = '$idcompany' and id_customer = '$idcustomer' and periode = '$periode' AND id = '$id' ", false);
		return $this->db->get();
	}

	public function datadetail($idcompany, $idcustomer, $periode, $id, $iclass)
	{
		$year  = substr($periode,0,4);
		$month = substr($periode,4,2);
		$i_periode1 = date('Ym', strtotime('-1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode2 = date('Ym', strtotime('-2 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode3 = date('Ym', strtotime('-3 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$and = ($id != '' || $id != null) ? "and a.id = '$id'" : "";
		$or = ($iclass != 'all') ? "and c.id_class_product = '$iclass'" : "";
		return $this->db->query("
			with cte as (
				SELECT
					id_product,
					sum(n_quantity) AS n_quantity,
					round(sum(n_quantity) / sum(count_op),2) AS n_rata2
				FROM
					(
					SELECT
						id_product,
						sum(n_quantity) AS n_quantity,
						CASE
							WHEN sum(n_quantity) > 0 THEN 1
							ELSE 0
						END AS count_op
					FROM
						tm_spb_distributor_item a
					INNER JOIN tm_spb_distributor b ON
						(b.id = a.id_document)
					WHERE
						b.i_status = '6'
						AND to_char(d_document, 'YYYYMM') = '$i_periode1'
						AND b.id_company = '$this->id_company '
					GROUP BY
						1
				UNION ALL
					SELECT
						id_product,
						sum(n_quantity) AS n_quantity,
						CASE
							WHEN sum(n_quantity) > 0 THEN 1
							ELSE 0
						END AS count_op
					FROM
						tm_spb_distributor_item a
					INNER JOIN tm_spb_distributor b ON
						(b.id = a.id_document)
					WHERE
						b.i_status = '6'
						AND to_char(d_document, 'YYYYMM') = '$i_periode2'
						AND b.id_company = '$this->id_company '
					GROUP BY
						1
				UNION ALL
					SELECT
						id_product,
						sum(n_quantity) AS n_quantity,
						CASE
							WHEN sum(n_quantity) > 0 THEN 1
							ELSE 0
						END AS count_op
					FROM
						tm_spb_distributor_item a
					INNER JOIN tm_spb_distributor b ON
						(b.id = a.id_document)
					WHERE
						b.i_status = '6'
						AND to_char(d_document, 'YYYYMM') = '$i_periode3'
						AND b.id_company = '$this->id_company '
					GROUP BY
						1
					) AS x
				GROUP BY
					1
			)
			select b.id_product as id_product_base, i_product_base, e_product_basename, periode, cc.e_customer_name, DATE(a.d_entry) as d_entry, b.v_harga, e_class_name,
			b.n_quantity, coalesce(g.n_rata2,0) AS n_rata2, b.n_quantity_sisa, b.e_remark, d.e_color_name from tm_forecast_distributor a
			inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
			right join tr_product_base c on (b.id_product = c .id)
			right join tr_color d on (c.i_color = d.i_color and c.id_company = d.id_company)
            INNER JOIN tr_customer cc ON
            (cc.id = a.id_customer)
			INNER JOIN tr_class_product e ON
			(e.id = c.id_class_product)	
			LEFT JOIN cte g ON (g.id_product = b.id_product)
			where a.id_company = '$idcompany' and a.id_customer = '$idcustomer' and a.periode = '$periode' $and $or 
			order by c.id_class_product
        ", FALSE);
	}

    public function dataexportdetail($idcompany, $periode)
    {
        $year  = substr($periode,0,4);
		$month = substr($periode,4,2);
		$i_periode1 = date('Ym', strtotime('-1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode2 = date('Ym', strtotime('-2 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode3 = date('Ym', strtotime('-3 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		return $this->db->query("
			with cte as (
				SELECT
					id_product,
					sum(n_quantity) AS n_quantity,
					round(sum(n_quantity) / sum(count_op),2) AS n_rata2
				FROM
					(
					SELECT
						id_product,
						sum(n_quantity) AS n_quantity,
						CASE
							WHEN sum(n_quantity) > 0 THEN 1
							ELSE 0
						END AS count_op
					FROM
						tm_spb_distributor_item a
					INNER JOIN tm_spb_distributor b ON
						(b.id = a.id_document)
					WHERE
						b.i_status = '6'
						AND to_char(d_document, 'YYYYMM') = '$i_periode1'
						AND b.id_company = '$this->id_company '
					GROUP BY
						1
				UNION ALL
					SELECT
						id_product,
						sum(n_quantity) AS n_quantity,
						CASE
							WHEN sum(n_quantity) > 0 THEN 1
							ELSE 0
						END AS count_op
					FROM
						tm_spb_distributor_item a
					INNER JOIN tm_spb_distributor b ON
						(b.id = a.id_document)
					WHERE
						b.i_status = '6'
						AND to_char(d_document, 'YYYYMM') = '$i_periode2'
						AND b.id_company = '$this->id_company '
					GROUP BY
						1
				UNION ALL
					SELECT
						id_product,
						sum(n_quantity) AS n_quantity,
						CASE
							WHEN sum(n_quantity) > 0 THEN 1
							ELSE 0
						END AS count_op
					FROM
						tm_spb_distributor_item a
					INNER JOIN tm_spb_distributor b ON
						(b.id = a.id_document)
					WHERE
						b.i_status = '6'
						AND to_char(d_document, 'YYYYMM') = '$i_periode3'
						AND b.id_company = '$this->id_company '
					GROUP BY
						1
					) AS x
				GROUP BY
					1
			)
			select b.id_product as id_product_base, i_product_base, e_product_basename, periode, cc.e_customer_name, DATE(a.d_entry) as d_entry, b.v_harga, e_class_name,
			b.n_quantity, coalesce(g.n_rata2,0) AS n_rata2, b.n_quantity_sisa, b.e_remark, d.e_color_name from tm_forecast_distributor a
			inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
			right join tr_product_base c on (b.id_product = c .id)
			right join tr_color d on (c.i_color = d.i_color and c.id_company = d.id_company)
            INNER JOIN tr_customer cc ON
            (cc.id = a.id_customer)
			INNER JOIN tr_class_product e ON
			(e.id = c.id_class_product)	
			LEFT JOIN cte g ON (g.id_product = b.id_product)
			where a.id_company = '$idcompany' and a.periode = '$periode' and a.i_status = '6'
			order by cc.e_customer_name, id_product_base, e_product_basename, d.e_color_name, c.id_class_product
        ", FALSE);

    }

}