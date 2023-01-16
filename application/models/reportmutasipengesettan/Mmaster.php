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
		$this->db->where('a.i_type', '08');
		$this->db->like('lower(e_bagian_name)', strtolower($search), 'both');
		$this->db->order_by('e_bagian_name');
		return $this->db->get();
	}

	public function getkategori($search)
	{
		$id_company = $this->session->userdata('id_company');
		return $this->db->query("SELECT DISTINCT
            	a.i_kode_kelompok,
            	b.e_nama_kelompok
            FROM 
                tr_product_base a
            INNER JOIN 
            	tr_kelompok_barang b ON (a.i_kode_kelompok = b.i_kode_kelompok)
            WHERE
				a.id_company = '$id_company'
				AND b.e_nama_kelompok ILIKE '%$search%'
			ORDER BY 2");
	}

	public function getkategori_old()
	{
		$id_company = $this->session->userdata('id_company');
		return $this->db->query("
                            SELECT DISTINCT
                              a.i_kode_kelompok,
                              b.e_nama_kelompok
                            FROM 
                              tr_product_wip a
                              INNER JOIN 
                                tr_kelompok_barang b ON (a.i_kode_kelompok = b.i_kode_kelompok)
                            WHERE
				                        a.id_company = '$id_company'
                            ");
	}

	public function getjenis($ikelompok,$search)
	{
		$id_company = $this->session->userdata('id_company');
		$where = '';
		if ($ikelompok == "null") {
			$where = " AND a.i_kode_kelompok IN (Select i_kode_kelompok from tr_product_base where id_company = '$id_company')";
		} else {
			$where = " AND a.i_kode_kelompok = '$ikelompok' ";
		}

		$this->db->select("a.i_type_code, a.e_type_name from tr_item_type a where a.id_company = '$id_company' and a.i_kode_group_barang = 'GRB0003' and e_type_name ilike '%$search%' $where ", false);
		return $this->db->get();
	}

	public function getjenis_old($ikelompok, $ibagian)
	{
		$id_company = $this->session->userdata('id_company');
		$where = '';
		if ($ikelompok == "KTB") {
			$where = " AND a.i_kode_kelompok IN (Select i_kode_kelompok from tr_product_wip where id_company = '$id_company')";
		} else {
			$where = " AND a.i_kode_kelompok = '$ikelompok' ";
		}

		$this->db->select("
      a.i_type_code, a.e_type_name from tr_item_type a where a.id_company = '$id_company' and a.i_kode_group_barang = 'GRB0002' $where

    ", false);
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
		if ($jnsbarang !== 'null') {
			$where .= "AND a.i_type_code = '$jnsbarang'";
		}

		$where2 = '';
		if ($ikelompok !== 'null') {
			$where2 .= "AND (a.i_kode_kelompok = '$ikelompok')";
		}

		$this->db->select("
      x.*, a.i_product_wip, upper(a.e_product_wipname) AS e_product_basename, cc.e_class_name,
      case when e_jenis_bagian isnull then upper(e_bagian_name) else upper(e_bagian_name||' - '||coalesce(e_jenis_bagian,'')) end as e_bagian_name, 
      upper(b.e_color_name) AS e_color_name,
	    upper(d.e_nama_kelompok) AS e_nama_kelompok,
	    upper(xx.e_brand_name) AS e_brand_name,
	    upper(e.e_type_name) AS e_type_name,
		g.i_panel , g.bagian , g.id_material, g.n_qty_penyusun, h.i_material , h.e_material_name
      FROM f_mutasi_saldoawal_pengesettan('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
      INNER JOIN tr_product_wip a ON
        (a.id_company = x.id_company
          AND a.id = x.id_product_wip)
	  INNER JOIN tr_product_base be ON 
	    (a.i_product_wip = be.i_product_wip 
		  AND a.i_color = be.i_color 
		  AND a.id_company = be.id_company)	  
      INNER JOIN tr_color b ON
        (a.id_company = b.id_company
          AND a.i_color = b.i_color)
	  INNER JOIN tm_panel_item g ON 
	    (g.id = x.id_panel_item)
	  INNER JOIN tr_material h ON 
	    (g.id_material = h.id)
      INNER JOIN tr_kelompok_barang d ON
        (d.i_kode_kelompok = a.i_kode_kelompok
          AND a.id_company = d.id_company)
      INNER JOIN tr_item_type e ON
        (e.i_type_code = a.i_type_code
          AND a.id_company = e.id_company)
      INNER JOIN tr_brand xx ON (xx.i_brand = a.i_brand AND a.id_company = xx.id_company)
	  INNER JOIN tr_class_product cc ON (cc.id = be.id_class_product)
      LEFT JOIN tr_bagian c ON (c.i_bagian = x.i_bagian AND x.id_company=c.id_company)
      WHERE x.id_company is not null
      $where $where2
      ORDER BY cc.e_class_name, a.i_product_wip, b.e_color_name, x.id_panel_item
    ", FALSE);
		return $this->db->get();
	}
}
/* End of file Mmaster.php */
// INNER JOIN tm_masuk_makloonqcset_item mm ON
// 			(mm.id_panel_item = x.id_panel_item
// 			AND mm.id_company = x.id_company)
// 		INNER JOIN tm_keluar_makloon_qcset_item_new km ON
// 			(km.id_panel_item = x.id_panel_item
// 			AND km.id_company = x.id_company)
// 		INNER JOIN tm_keluar_makloon_qcset_new kmh ON
// 			(mm.id_document_reff = kmh.id AND km.id_document = kmh.id)