<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
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
         		order by d_entry desc, d_update desc NULLS LAST, a.total desc NULLS LAST
				
			
			
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
            
			if(check_role($i_menu, 2)){
                $data .= "<a href=\"".base_url($folder.'/cform/export_data/'.$i_bagian.'/'.$id_customer.'/'.$tahun.'/'.$bulan.'/'.$dfrom.'/'.$dto.'/'.$id)."\" title='Export'><i class='ti-download text-success'></i></a>&nbsp;&nbsp;";
            }

			if (check_role($i_menu, 7) && $i_status == '2') {
			    if (($i_level == $this->i_level || $this->i_level == 1) ) {
			        $data 		  .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$i_bagian/$id_customer/$tahun/$bulan/$dfrom/$dto/$id\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
			    }
			}   

			if (check_role($i_menu, 4) && ($i_status=='1')) {
                	$data 		  .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
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

	public function getpartner()
	{
		$this->db->select("i_unit_jahit, e_unitjahit_name from tr_unit_jahit order by e_unitjahit_name", false);
		return $this->db->get()->result();
	}

	public function cek_produk($idproduct, $i_product)
	{
		return $this->db->query("select id from tr_product_base where id = '$idproduct' and i_product_base = '$i_product' and id_company = '" . $this->session->userdata('id_company') . "'");
	}

	public function cek_produk_warna($i_product)
	{
		return $this->db->query("SELECT a.id, i_product_base, e_product_basename, b.e_color_name from tr_product_base a, tr_color b where a.i_color = b.i_color and b.id_company = a.id_company and i_product_base = '$i_product' and a.id_company = '$this->id_company' ORDER BY e_color_name ASC");
	}

	public function getpartnerbyid($partner)
	{
		$this->db->select("i_unit_jahit, e_unitjahit_name from tr_unit_jahit where i_unit_jahit = '$partner'", false);
		return $this->db->get()->row();
	}

	public function getbarang($ikodemaster)
	{
		$this->db->select("a.i_material, a.e_material_name
                        from tr_material a
                        join tm_kelompok_barang b on a.i_kode_kelompok = b.i_kode_kelompok
                        where 
                        a.i_kode_kelompok='KTB0004' or a.i_kode_kelompok='KTB0005'
                        order by a.i_material", false);
		return $this->db->get();
	}

	function cek_datadet($idcompany, $year, $month)
	{
		$i_periode1 = date('Ym', strtotime('-1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode2 = date('Ym', strtotime('-2 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 2 Bulan */
		$i_periode3 = date('Ym', strtotime('-3 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 3 Bulan */
		return $this->db->query("
			WITH cte AS (
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
			SELECT DISTINCT
				c.id AS id_product_base,
				i_product_base,
				e_product_basename,
				d.e_color_name,
				e.e_nama_kelompok,
				f.e_type_name ,
				'' AS v_harga,
				0 AS n_quantity,
				0 AS n_quantity_sisa,
				'' AS e_remark,
				g.e_class_name,
				coalesce(n_rata2,0) AS n_rata2,
				bb.e_brand_name,
				cc.e_style_name,
				c.id_class_product,
				h.e_nama_divisi
			FROM
				tr_product_base c
			INNER JOIN tr_color d ON
				(c.i_color = d.i_color
					AND c.id_company = d.id_company)
			INNER JOIN tr_kelompok_barang e ON
				(e.i_kode_kelompok = c.i_kode_kelompok
					AND c.id_company = e.id_company)
			INNER JOIN tr_class_product g ON 
				(g.id = c.id_class_product)
			INNER JOIN tr_brand bb ON
				(c.i_brand = bb.i_brand AND bb.id_company = c.id_company)
			INNER JOIN tr_style cc ON
				(cc.i_style = c.i_style AND c.id_company = cc.id_company)
			LEFT JOIN tr_item_type f ON
				(f.i_type_code = c.i_type_code
					AND c.id_company = f.id_company)
			LEFT JOIN cte k ON (k.id_product = c.id)
			LEFT JOIN tr_divisi_new h ON (h.id = e.id_divisi)
			WHERE
				c.id_company = '$idcompany' and c. f_status = true
			ORDER BY c.id_class_product asc
        ", FALSE);
	}

	function cek_datadetail_warna($idcompany, $year, $month)
	{
		$i_periode1 = date('Ym', strtotime('-1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode2 = date('Ym', strtotime('-2 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 2 Bulan */
		$i_periode3 = date('Ym', strtotime('-3 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 3 Bulan */
		return $this->db->query("WITH cte AS (
			SELECT
				i_product_base,
				y.id_company,
				sum(n_quantity) AS n_quantity,
				round(sum(n_quantity) / sum(count_op), 2) AS n_rata2
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
					AND b.id_company = '$idcompany'
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
					AND b.id_company = '$idcompany'
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
					AND b.id_company = '$idcompany'
				GROUP BY
					1 ) AS x
			INNER JOIN tr_product_base y ON (y.id = x.id_product)
			GROUP BY
				1,2 ) 
			SELECT
				DISTINCT c.i_product_base,
				e_product_basename,
				e.e_nama_kelompok,
				f.e_type_name ,
				'' AS v_harga,
				0 AS n_quantity,
				0 AS n_quantity_sisa,
				'' AS e_remark,
				g.e_class_name,
				COALESCE(n_rata2, 0) AS n_rata2,
				bb.e_brand_name,
				cc.e_style_name,
				c.id_class_product,
				h.e_nama_divisi
			FROM
				tr_product_base c
			INNER JOIN tr_color d ON
				(c.i_color = d.i_color
					AND c.id_company = d.id_company)
			INNER JOIN tr_kelompok_barang e ON
				(e.i_kode_kelompok = c.i_kode_kelompok
					AND c.id_company = e.id_company)
			INNER JOIN tr_class_product g ON
				(g.id = c.id_class_product)
			INNER JOIN tr_brand bb ON
				(c.i_brand = bb.i_brand
					AND bb.id_company = c.id_company)
			INNER JOIN tr_style cc ON
				(cc.i_style = c.i_style
					AND c.id_company = cc.id_company)
			LEFT JOIN tr_item_type f ON
				(f.i_type_code = c.i_type_code
					AND c.id_company = f.id_company)
			LEFT JOIN cte k ON
				(k.i_product_base = c.i_product_base AND c.id_company = k.id_company)
			LEFT JOIN tr_divisi_new h ON
				(h.id = e.id_divisi)
			WHERE
				c.id_company = '$idcompany'
				AND c. f_status = TRUE
			ORDER BY
				c.id_class_product ASC
        ");
	}

	function dataexport($idcompany, $idcustomer, $periode, $id, $iclass)
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
			select b.id_product as id_product_base, i_product_base, e_product_basename, b.v_harga, e_class_name,
			b.n_quantity, coalesce(g.n_rata2,0) AS n_rata2, b.n_quantity_sisa, b.e_remark, d.e_color_name, j.e_nama_kelompok, k.e_type_name, l.e_nama_divisi from tm_forecast_distributor a
			inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
			right join tr_product_base c on (b.id_product = c .id)
			right join tr_color d on (c.i_color = d.i_color and c.id_company = d.id_company)
			INNER JOIN tr_class_product e ON
			(e.id = c.id_class_product)	
			LEFT JOIN cte g ON (g.id_product = b.id_product)
			LEFT JOIN tr_kelompok_barang j ON (j.i_kode_kelompok = c.i_kode_kelompok AND j.id_company = '$this->id_company')
			LEFT JOIN tr_item_type k ON (k.i_type_code = c.i_type_code AND k.id_company = '$this->id_company')
			LEFT JOIN tr_divisi_new l ON (l.id = j.id_divisi)
			where a.id_company = '$idcompany' and a.id_customer = '$idcustomer' and a.periode = '$periode' $and $or 
			order by c.id_class_product, i_product_base , d.e_color_name asc
        ", FALSE);
	}

	function runningnumber($yearmonth, $partner, $lokasi)
	{
		$bl = substr($yearmonth, 4, 2);
		$th = substr($yearmonth, 0, 4);
		$thn = substr($yearmonth, 2, 2);
		$area = 'MJ';
		// var_dump($bl);
		//var_dump($area);
		//$asal=$yearmonth;
		$asal = substr($yearmonth, 0, 4);
		$yearmonth = substr($yearmonth, 0, 4);
		//$yearmonth=substr($yearmonth,2,2).substr($yearmonth,4,2);
		// var_dump($yearmonth);
		// die;
		$this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='SO'
                            and i_area='$area'
                            and e_periode='$asal' 
                            and substring(e_periode,1,4)='$th' for update", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$terakhir = $row->max;
			}
			$nopp  = $terakhir + 1;
			$this->db->query("update tm_dgu_no 
                            set n_modul_no=$nopp
                            where i_modul='SO'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
			settype($nopp, "string");
			$a = strlen($nopp);

			//u/ 0
			while ($a < 5) {
				$nopp = "0" . $nopp;
				$a = strlen($nopp);
			}
			$nopp  = "SO-" . $lokasi . "-" . $thn . $bl . "-" . $nopp;
			return $nopp;
		} else {
			$nopp  = "00001";
			$nopp  = "SO-" . $lokasi . "-" . $thn . $bl . "-" . $nopp;
			$this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SO','$area','$asal',1)");
			return $nopp;
		}
	}

	function cek_datadet_upload($dso, $kodewip, $icolor, $partner)
	{
		$interval = new DateInterval('P1M');

		$dateTime = new DateTime($dso);
		$bulan = $dateTime->format('m');
		$tahun = $dateTime->format('Y');
		$bulanlalu = $dateTime->sub($interval)->format('m');
		$tahunlalu = $dateTime->sub($interval)->format('Y');

		$datefrom = "01-" . $bulan . "-" . $tahun;
		$dateto = new DateTime($dso);
		$dateto = $dateto->format('t-m-Y');

		$dawal = $datefrom;
		$dakhir = $datefrom;
		$partner2 = 'xx';

		//var_dump($bulanlalu,$tahunlalu,$datefrom,$dateto,$bulan,$tahun,$partner, $dawal, $dakhir, $partner2);
		//die();

		$query = $this->db->query("
          select saldoawal, saldoakhir from f_mutasi_makloonjahit('$bulanlalu','$tahunlalu','$datefrom','$dateto','$bulan','$tahun','$partner', '$dawal', '$dakhir', '$partner2') 
          where kodewip='$kodewip' and icolor = '$icolor' order by kodewip, icolor;
        ", false);

		return $query;
	}

	public function simpan($id, $idcompany, $ibagian, $icustomer, $periode)
	{
		$dentry = current_datetime();
		$data = array(
		    'id'           => $id,
		    'id_company'   => $idcompany,
		    'i_bagian'     => $ibagian,
		    'id_customer'  => $icustomer,
		    'periode'      => $periode,
		    'd_entry'      => $dentry,
		);
		$this->db->insert('tm_forecast_distributor', $data);

		/* $query = $this->db->query("
          insert into tm_forecast_distributor(id, id_company, i_bagian, id_customer, periode, d_entry) 
          VALUES ('$id', '$idcompany', '$ibagian', '$icustomer', '$periode', '$dentry')
          ON CONFLICT (id) DO UPDATE 
            SET i_bagian = excluded.i_bagian, 
                d_entry = excluded.d_entry;
        ", false); */
	}

	public function simpandetail($idcompany, $id, $id_product, $v_harga, $qty, $e_remark, $rata)
	{
		$data = array(
			'id_company'      => $idcompany,
			'id_forecast'     => $id,
			'id_product'      => $id_product,
			'v_harga'         => $v_harga,
			'n_quantity'      => $qty,
			'n_quantity_sisa' => $qty,
			'e_remark'        => $e_remark,
			'rata2'           => $rata,
		);
		$this->db->insert('tm_forecast_distributor_item', $data);
	}

	public function deletedatadetail($id)
	{
		$this->db->query("DELETE FROM tm_forecast_distributor_item WHERE id_forecast = '$id'");
	}

	public function deletedetail($istokopname)
	{
		$this->db->query("DELETE FROM tt_stok_opname_makloonjahit_detail WHERE i_stok_opname_makloonjahit='$istokopname'");
	}

	public function insertdetail($istokopname, $iwip, $icolor, $saldoawal, $saldoakhir, $stokopname, $nitemno, $partner)
	{
		$data = array(
			'i_stok_opname_makloonjahit' => $istokopname,
			'i_product'           => $iwip,
			'i_color'             => $icolor,
			'grade'               => 'A',
			'v_stok_opname'       => $stokopname,
			'v_stok_awal'         => $saldoawal,
			'v_saldo_akhir'       => $saldoakhir,
			'f_status_approve'    => 'f',
			'n_item_no'           => $nitemno,
			'partner'             => $partner,
		);
		$this->db->insert('tt_stok_opname_makloonjahit_detail', $data);
	}

	public function updatedataheader($id, $ibagian, $icustomer, $periode){
		$dupdate = current_datetime(); 
		$data = array(
			'i_bagian' 		=> $ibagian,
			'id_customer'	=> $icustomer,
			'periode' 		=> $periode,
			'd_update'		=> $dupdate,
			'i_status'		=> '1'
		);
		$this->db->where('id', $id);
		$this->db->where('id_company', $this->id_company);
		$this->db->update('tm_forecast_distributor', $data);
	}

	public function updatedatadetail($idproduct, $vprice, $nqty, $eremark, $id)
	{
		$data = array(
			'v_harga'			=> $vprice,
			'n_quantity'		=> $nqty,
			'n_quantity_sisa'	=> $nqty,
			'e_remark' 			=> $eremark,
		);
		$this->db->where('id_company', $this->id_company);
		$this->db->where('id_forecast', $id);
		$this->db->where('id_product', $idproduct);
		$this->db->update('tm_forecast_distributor_item', $data);
	}

	public function updateheader($ikodeso, $periode, $partner)
	{

		$data = array(
			'f_status_approve' => 't',
		);
		$this->db->where('i_stok_opname_makloonjahit', $ikodeso);
		$this->db->where('i_periode', $periode);
		$this->db->where('partner', $partner);
		$this->db->update('tt_stok_opname_makloonjahit', $data);
	}

	public function updatedetail($ikodeso, $partner)
	{

		$data = array(
			'f_status_approve'                 => 't',
		);
		$this->db->where('i_stok_opname_makloonjahit', $ikodeso);
		$this->db->where('partner', $partner);
		$this->db->update('tt_stok_opname_makloonjahit_detail', $data);
	}




	public function cek_datadetail($iso, $partner)
	{
		$this->db->select("b.i_product as kodewip, wi.e_namabrg as barangwip, co.e_color_name as ecolor,
    b.i_color as icolor, b.v_stok_awal as saldoawal, b.v_saldo_akhir as saldoakhir, b.v_stok_opname as so
    from tt_stok_opname_makloonjahit a
    JOIN tt_stok_opname_makloonjahit_detail b ON a.i_stok_opname_makloonjahit = b.i_stok_opname_makloonjahit   
    JOIN tm_barang_wip wi ON b.i_product = wi.i_kodebrg
    JOIN tr_color co ON b.i_color = co.i_color
    where a.i_stok_opname_makloonjahit = '$iso' and a.partner = '$partner'", false);
		return $this->db->get();
	}





	public function bagian()
	{
		// $this->db->select('aa.id, a.i_bagian, e_bagian_name')->distinct();
		// $this->db->from('tr_bagian a');
		// $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
		// $this->db->where('a.f_status', 't');
		// $this->db->where('i_departement', $this->session->userdata('i_departement'));
		// //$this->db->where('i_level', $this->session->userdata('i_level'));
		// $this->db->where('username', $this->session->userdata('username'));
		// $this->db->where('a.id_company', $this->session->userdata('id_company'));
		// //$this->db->where('a.i_type', '17');    
		// $this->db->order_by('e_bagian_name');
		// return $this->db->get();
		return $this->db->query("
				SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
	}

	public function customer($idcompany)
	{
		// $this->db->select(" id, e_customer_name from tr_customer where i_type_industry||id_company IN (
  //     select b.i_type_industry||b.id_company from tr_type_spb a
  //     inner join tr_type_industry b on (b.id = a.id_type_industry)
  //     where a.e_type_name = 'Distributor' and a.id_company = '$idcompany' ) AND id_company = '$idcompany' order by e_customer_name", false);
		// return $this->db->get();
		return $this->db->query("
				SELECT id, e_customer_name from tr_customer a where i_supplier_group = 'KTG04' and id_company = '$idcompany' and f_status = 't'
        ", false);
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
			select b.id_product as id_product_base, i_product_base, e_product_basename, b.v_harga, e_class_name,
			b.n_quantity, coalesce(g.n_rata2,0) AS n_rata2, b.n_quantity_sisa, b.e_remark, d.e_color_name from tm_forecast_distributor a
			inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
			right join tr_product_base c on (b.id_product = c .id)
			right join tr_color d on (c.i_color = d.i_color and c.id_company = d.id_company)
			INNER JOIN tr_class_product e ON
			(e.id = c.id_class_product)	
			LEFT JOIN cte g ON (g.id_product = b.id_product)
			where a.id_company = '$idcompany' and a.id_customer = '$idcustomer' and a.periode = '$periode' $and $or 
			order by c.id_class_product
        ", FALSE);
	}

	/*----------  CARI BARANG  ----------*/

	public function product($cari)
	{
		return $this->db->query("            
            SELECT
                a.id,
                i_product_base,
                e_product_basename,
                e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color
                AND a.id_company = b.id_company)
            WHERE
                a.f_status = 't'
                AND (i_product_base ILIKE '%$cari%' 
                OR e_product_basename ILIKE '%$cari%')
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
            ORDER BY
                2 ASC
        ", FALSE);
	}

	public function runningid()
	{
		$this->db->select('max(id) AS id');
		$this->db->from('tm_forecast_distributor');
		return $this->db->get()->row()->id + 1;
	}

	public function hapusdetail($idcompany, $id)
	{
		return $this->db->query(" 
          delete from tm_forecast_distributor_item where id_forecast = '$id'
        ", FALSE);
	}


	public function changestatus($id,$istatus)
    {
    	$now = date('Y-m-d');
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("
            	SELECT b.i_menu , a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut from tm_forecast_distributor a
				inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
				where a.id = '$id'
				group by 1,2", FALSE)->row();

            if ($istatus == '3') {
            	if ($awal->i_approve_urutan - 1 == 0 ) {
            		$data = array(
	                    'i_status'  => $istatus,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
            	}
            	$this->db->query("delete from tm_menu_approve where i_menu = '$this->i_menu' and i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
            		$data = array(
	                    'i_status'  => $istatus,
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
            	$this->db->query("
            		INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					 ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_forecast_distributor');", FALSE);
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_forecast_distributor', $data);
    }
    
    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
}
/* End of file Mmaster.php */