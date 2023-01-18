<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
	function data($i_menu, $folder, $dfrom, $dto)
	{

		$datefrom = date('Ym', strtotime($dfrom));
        $dateto = date('Ym', strtotime($dto));
		$id_company = $this->session->userdata('id_company');
		$cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_mutasi_saldoawal_base_jadi
            WHERE
                e_mutasi_periode between '$datefrom' and '$dateto' and  id_company = '$id_company'
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
		
        $iperiode = date('Ym');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                0 AS no,
				a.id_company,
				a.i_bagian,
				b.e_bagian_name,
				a.e_mutasi_periode,
				'$folder' AS folder,
				'$i_menu' AS i_menu
            FROM tm_mutasi_saldoawal_base_jadi a
			LEFT JOIN tr_bagian b
			ON (b.i_bagian = a.i_bagian)
			WHERE a.id_company = '$id_company' 
            AND a.e_mutasi_periode between '$datefrom' and '$dateto'
			$bagian
            GROUP BY 5,4,3,2
			ORDER BY 5 DESC
		
        ");

		$datatables->add('action', function ($data) {
			$i_menu   	= $data['i_menu'];
			$folder   	= $data['folder'];
			$periode    = $data['e_mutasi_periode'];
			$i_bagian 	= $data['i_bagian'];
			$id_company = $data['id_company'];
			$download	= site_url($folder.'/cform/exportperiode/').$id_company.'/'.$i_bagian.'/'.$periode.'/';
			$data     	= '';

			if (check_role($i_menu, 2)) {
					$data  		   	.= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id_company/$i_bagian/$periode/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
			}

			if (check_role($i_menu, 2)) {
					$data  		   	.= "<a href='$download'><i class='ti-download text-light mr-3'></i></a>";
			}

			// if (check_role($i_menu, 3)) {
			// 		$data       	.= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id_company/$i_bagian/$periode/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
			// }

			// if (check_role($i_menu, 4)) {
            //     	$data 			.= "<a href=\"#\" title='Batal' onclick='show(\"$folder/cform/delete/$id_company/$i_bagian/$periode/\",\"#main\"); return false;'><i class='ti-close text-danger'></i></a>";
            // }

			return $data;
		});
		$datatables->hide('id_company');
		$datatables->hide('i_bagian');
		$datatables->hide('folder');
		$datatables->hide('i_menu');
		return $datatables->generate();
	}

	public function getpartner()
	{
		$this->db->select("i_unit_jahit, e_unitjahit_name from tr_unit_jahit order by e_unitjahit_name", false);
		return $this->db->get()->result();
	}

	public function cek_produk($idproduct, $i_product)
	{
		return $this->db->query("select 
		a.id,
		a.i_product_base,
		a.e_product_basename,
		a.i_color,
		(SELECT e_color_name FROM tr_color WHERE i_color = a.i_color AND id_company = '" . $this->session->userdata('id_company') . "') as e_color_name
		from tr_product_base a
		where a.id = '$idproduct' 
		and a.i_product_base = '$i_product' 
		and a.id_company = '" . $this->session->userdata('id_company') . "' ");
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

	function cek_datadet($ibagian, $year, $month)
	{
		$periode = $year.$month; 
		$id_company = $this->session->userdata('id_company');
		$cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_mutasi_saldoawal_base_jadi
            WHERE
                e_mutasi_periode = '$periode' and  id_company = '$id_company'
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

		$i_bagian = $cek->row()->i_bagian;

		return $this->db->query("
		SELECT DISTINCT
		a.id_company,
		a.id,
		a.i_product_base,
		a.i_color,
		a.e_product_basename,
		c.e_color_name, 
		b.n_saldo_awal
		FROM tr_product_base a
		INNER JOIN
		tm_mutasi_saldoawal_base_jadi b 
		ON (a.id = b.id_product_base AND a.i_product_base = b.i_product_base AND b.id_company = '$this->id_company'
		AND b.e_mutasi_periode = '$periode' AND b.i_bagian = '$i_bagian')
		INNER JOIN tr_color c 
		ON (c.i_color = a.i_color AND c.i_color = b.i_color AND c.id_company = '$id_company')
		WHERE 
		a.id_company = '$id_company' 
		AND a.f_status = 'true'
		GROUP BY 
        2,6,7
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

	public function simpan($idcompany, $ibagian, $periode, $iproduct, $icolor, $nsaldoawal, $idproduct)
	{
		$data = array(
			'id_company'	        => $idcompany,
			'i_bagian'              => $ibagian,
            'e_mutasi_periode'      => $periode,
            'i_product_base'        => $iproduct,
            'i_color'               => $icolor,
            'n_saldo_awal'          => $nsaldoawal,
            'id_product_base'       => $idproduct
		);
		
		$dataupdate = array(
			'n_saldo_awal'          => $nsaldoawal,
		);
			
			$this->db->insert('tm_mutasi_saldoawal_base_jadi',$data);

	}

	public function deletedata($idcompany, $ibagian, $periode){
			$this->db->where('id_company', $idcompany);
			$this->db->where('i_bagian', $ibagian);
			$this->db->where('e_mutasi_periode', $periode);
			$this->db->delete('tm_mutasi_saldoawal_base_jadi');
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

	public function updatedetail($id, $idcompany, $ibagian, $periode, $iproduct, $icolor, $nsaldoawal, $idproduct)
	{

		$this->db->where('id', $id);
		$this->db->delete('tm_mutasi_saldoawal_base_jadi');

		$data = array(
			'id_company'	        => $idcompany,
			'i_bagian'              => $ibagian,
            'e_mutasi_periode'      => $periode,
            'i_product_base'        => $iproduct,
            'i_color'               => $icolor,
            'n_saldo_awal'          => $nsaldoawal,
            'id_product_base'       => $idproduct
		);

		$this->db->insert('tm_mutasi_saldoawal_base_jadi',$data);
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
				SELECT id, e_customer_name from tr_customer a where i_supplier_group = 'KTG04' and id_company = '$idcompany'
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

	public function datadetail($idcompany, $ibagian, $periode)
	{
		return $this->db->query("SELECT 
		a.id,
		a.id_company,
		a.i_bagian,
		a.e_mutasi_periode,
		a.i_product_base,
		b.e_product_basename,
		a.i_color,
		c.e_color_name,
		a.n_saldo_awal,
		a.id_product_base
		FROM tm_mutasi_saldoawal_base_jadi a
		LEFT JOIN tr_product_base b ON
		(b.id = a.id_product_base)
		LEFT JOIN tr_color c ON
		(c.i_color = a.i_color and a.id_company = c.id_company)
		WHERE 
		a.id_company = '$idcompany'
		AND a.i_bagian = '$ibagian'
		AND a.e_mutasi_periode = '$periode'
		ORDER BY 5
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

	public function productcolor($cari)
	{
		return $this->db->query("            
            SELECT
                a.i_product_base,
                b.i_color,
				b.e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color
                AND a.id_company = b.id_company)
            WHERE
                a.f_status = 't'
                AND a.id = '$cari'
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

	function exportperiode($ibagian, $periode)
	{
		$id_company = $this->session->userdata('id_company');

		return $this->db->query("
		SELECT DISTINCT
		a.id_company,
		a.id,
		a.i_product_base,
		a.i_color,
		a.e_product_basename,
		c.e_color_name, 
		b.n_saldo_awal
		FROM tr_product_base a
		INNER JOIN
		tm_mutasi_saldoawal_base_jadi b 
		ON (a.id = b.id_product_base AND a.i_product_base = b.i_product_base AND b.id_company = '$this->id_company'
		AND b.e_mutasi_periode = '$periode' AND b.i_bagian = '$ibagian')
		INNER JOIN tr_color c 
		ON (c.i_color = a.i_color AND c.i_color = b.i_color AND c.id_company = '$id_company')
		WHERE 
		a.id_company = '$id_company' 
		AND a.f_status = 'true'
		GROUP BY 
        2,6,7
        ", FALSE);
	}
}
/* End of file Mmaster.php */