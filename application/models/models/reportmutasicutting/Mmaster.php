<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
	public function getbagian($search)
	{
		$this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
		$this->db->from('tr_bagian a');
		$this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian', 'inner');
		$this->db->where('i_departement', $this->session->userdata('i_departement'));
		$this->db->where('i_level', $this->session->userdata('i_level'));
		$this->db->where('username', $this->session->userdata('username'));
		$this->db->where('b.id_company', $this->session->userdata('id_company'));
		$this->db->where('a.id_company', $this->session->userdata('id_company'));
		// $this->db->where('a.i_type', '01');
		$this->db->like('lower(e_bagian_name)', strtolower($search), 'both');
		$this->db->order_by('e_bagian_name');
		return $this->db->get();
	}

	public function getcompany($search)
	{
		$this->db->select('a.id, a.name')->distinct();
		$this->db->from('public.company a');
		$this->db->where('a.i_apps', $this->session->userdata('i_apps'));
		$this->db->where('a.f_status', 't');
		$this->db->like('lower(a.name)', strtolower($search), 'both');
		$this->db->order_by('a.name');
		return $this->db->get();
	}

	public function getkategori($search)
	{
		$id_company = $this->session->userdata('id_company');
		return $this->db->query("SELECT DISTINCT
            	a.i_kode_kelompok,
            	b.e_nama_kelompok
            FROM 
                produksi.tr_material a
            INNER JOIN 
            	produksi.tr_kelompok_barang b ON (a.i_kode_kelompok = b.i_kode_kelompok)
            WHERE
				a.id_company = '$id_company'
				AND b.e_nama_kelompok ILIKE '%$search%'
			ORDER BY 2");
	}

	public function getjenis($ikelompok,$search)
	{
		$id_company = $this->session->userdata('id_company');
		$where = '';
		if ($ikelompok == "null") {
			$where = " AND a.i_kode_kelompok IN (Select i_kode_kelompok from tr_material where id_company = '$id_company')";
		} else {
			$where = " AND a.i_kode_kelompok = '$ikelompok' ";
		}

		$this->db->select("a.i_type_code, a.e_type_name from tr_item_type a where a.id_company = '$id_company' and a.i_kode_group_barang = 'GRB0003' and e_type_name ilike '%$search%' $where ", false);
		return $this->db->get();
	}

	public function bacabagian($ibagian)
	{
		$this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
		$this->db->from('tr_bagian a');
		$this->db->where('a.id_company', $this->session->userdata('id_company'));
		$this->db->where('a.i_bagian', $ibagian);
		$this->db->order_by('e_bagian_name');
		return $this->db->get();
	}

	public function kategoribarang($ikelompok, $id_company)
	{

		if ($ikelompok != 'KTB') {
			$this->db->select("e_nama_kelompok from tr_kelompok_barang where i_kode_kelompok = '$ikelompok' and id_company = '$id_company' ", false);
		} else {
			$this->db->select("'Semua Kategori Barang' as e_nama_kelompok", false);
		}
		return $this->db->get();
	}

	public function jenisbarang($jnsbarang, $id_company)
	{
		if ($jnsbarang != 'JNB') {
			$this->db->select("e_type_name from tr_item_type where i_type_code = '$jnsbarang' and id_company = '$id_company' ", false);
		} else {
			$this->db->select("'Semua Barang' as e_type_name", false);
		}
		return $this->db->get();
	}

	function cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang)
	{
		$where = '';
		if ($jnsbarang != 'null' && $jnsbarang != '' ) {
			$where .= "AND a.i_type_code = '$jnsbarang'";
		}

		$where2 = '';
		if ($ikelompok != 'null' && $ikelompok != '') {
			$where2 .= "AND (a.i_kode_kelompok = '$ikelompok')";
		}

		return $this->db->query("SELECT x.*, a.i_material, upper(a.e_material_name) AS e_material_name, 
			case when e_jenis_bagian isnull then initcap(e_bagian_name) else initcap(e_bagian_name||' - '||coalesce(e_jenis_bagian,'')) end as e_bagian_name, b.i_satuan_code, b.e_satuan_name
			from f_mutasi_cutting('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
			INNER JOIN tr_material a ON
				(/* a.id_company = x.id_company
				AND  */a.id = x.id_material)
			INNER JOIN tr_satuan b ON
				(a.i_satuan_code = b.i_satuan_code AND b.id_company = a.id_company)
			LEFT JOIN tr_bagian c ON (c.i_bagian = '$ibagian' AND x.id_company=c.id_company)
			WHERE x.id_company is not null
			$where $where2
			ORDER BY a.i_material, e_material_name
			", FALSE);
		// return $this->db->get();
	}

	public function get_saldo($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $bagian)
	{
		return $this->db->query(
			"SELECT COALESCE(round(sum(n_qty*v_price)),0) AS saldo 
			FROM f_mutasi_material_rp_hitung 
			('$id_company','$i_periode','$d_jangka_awal','$d_jangka_akhir','$dfrom','$dto','$bagian')
		");
	}
}
/* End of file Mmaster.php */