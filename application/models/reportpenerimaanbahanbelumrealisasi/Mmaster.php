<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
	public function get_bagian($id=null)
	{
		$this->db->where('id', $id);
		return $this->db->get('tr_bagian');
	}

    public function get_list_bagian()
    {
        $i_level = $this->session->userdata('i_level');
        $id_company = $this->session->userdata('id_company');
        $username = $this->session->userdata('username');
        $TYPE_JAHIT = '07';

        $sql = "SELECT DISTINCT a.id,
                    a.i_bagian,
                    e_bagian_name
                FROM tr_bagian a
                INNER JOIN tr_departement_cover tdc ON tdc.i_bagian = a.i_bagian and tdc.id_company = a.id_company
                WHERE i_departement = '1'
                    AND i_level = '$i_level'
                    AND username = '$username'
                    AND a.id_company = '$id_company'
                    AND a.i_type = '$TYPE_JAHIT'
                    AND lower(e_bagian_name) LIKE '%%' ESCAPE '!'
                ORDER BY
                    e_bagian_name";
        
        // var_dump($sql);

        return $this->db->query($sql);
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

	public function getjenis($ikelompok, $search)
	{
		$id_company = $this->session->userdata('id_company');
		$where = '';
		if ($ikelompok == "null" || $ikelompok == "") {
			$where = " AND a.i_kode_kelompok IN (Select i_kode_kelompok from tr_product_base where id_company = '$id_company')";
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

	function cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang, $i_product)
	{
		$where = '';
		if ($jnsbarang != 'null' && $jnsbarang != '') {
			$where = "AND a.i_type_code = '$jnsbarang'";
		}

		$where2 = '';
		if ($ikelompok != 'null' && $ikelompok != '') {
			$where2 = "AND (a.i_kode_kelompok = '$ikelompok')";
		}

		$where3 = '';
		if ($i_product != 'null' && $i_product != '') {
			$where3 = "AND (a.id = '$i_product')";
		}

		$sql = "x.*, a.i_product_wip, upper(a.e_product_basename) AS e_product_basename, e_class_name, case when e_jenis_bagian isnull then initcap(e_bagian_name) else initcap(e_bagian_name||' - '||coalesce(e_jenis_bagian,'')) end as e_bagian_name, upper(b.e_color_name) AS e_color_name 
				from f_mutasi_wip('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
				INNER JOIN tr_product_base a ON
					(a.id_company = x.id_company
					AND a.id = x.id_product_base)
				INNER JOIN tr_color b ON
					(a.id_company = b.id_company
					AND a.i_color = b.i_color)
				INNER JOIN tr_class_product cc ON (cc.id = a.id_class_product)
				LEFT JOIN tr_bagian c ON (c.i_bagian = x.i_bagian AND x.id_company=c.id_company)
				WHERE x.id_company is not null
				$where $where2 $where3
				ORDER BY e_class_name, a.i_product_wip, e_product_basename,e_color_name";

		// var_dump($sql); die();

		$this->db->select($sql, FALSE);
		return $this->db->get();
	}

    public function get_export_data($id_company, $id_bagian=null, $dfrom, $dto)
    {
        // var_dump($this->session->userdata()); die();
        /** filter bagian */
        $where_bagian = '';
        if ($id_bagian != null) {
            $sql_bagian = "SELECT i_bagian FROM tr_bagian WHERE id='$id_bagian'";
            $where_bagian = " AND (tmmc.i_bagian = ($sql_bagian) AND tmmc.id_company = '$id_company')";
        }

        $sql_realisasi = "SELECT tsci.id_referensi 
                            FROM tm_schedule_cutting_item tsci
                            WHERE tsci.d_schedule_realisasi IS NOT NULL ";

        /** main table */
        $cte_penerimaan = "SELECT 
                                STRING_AGG(tmmc.id::VARCHAR, ',') AS id, 
                                STRING_AGG(tmmci.id::VARCHAR, ',') AS id_item, 
                                tmmci.id_material, 
                                STRING_AGG(tmmc.id_document_referensi::VARCHAR, ',') AS id_document_referensi, 
                                SUM(tmmci.n_quantity) AS n_quantity
                            FROM tm_masuk_material_cutting_item tmmci
                            INNER JOIN tm_masuk_material_cutting tmmc ON tmmc.id = tmmci.id_document 
                            WHERE tmmc.i_status = '6'
                            AND (tmmc.d_document >= '$dfrom' AND tmmc.d_document <= '$dto')
                            AND tmmci.id NOT IN ($sql_realisasi) 
                            $where_bagian
                            GROUP BY tmmci.id_material";

        $cte_stb = "SELECT tsmc.id, tsmci.id_material, tsmci.n_quantity, tsmci.n_quantity_sisa 
                    FROM tm_stb_material_cutting_item tsmci
                    INNER JOIN tm_stb_material_cutting tsmc ON tsmc.id = tsmci.id_document";

        /** main query */
        $sql = "WITH CTE_PENERIMAAN AS ($cte_penerimaan) 
                SELECT CTE_PENERIMAAN.id_material, 
                        tm.i_material, 
                        tm.e_material_name, 
                        ts.e_satuan_name,
                        CTE_PENERIMAAN.n_quantity 
                FROM CTE_PENERIMAAN
                LEFT JOIN ($cte_stb) AS CTE_STB ON CTE_STB.id::VARCHAR IN (CTE_PENERIMAAN.id_document_referensi)
                LEFT JOIN tr_material tm ON tm.id = CTE_PENERIMAAN.id_Material
                LEFT JOIN tr_satuan ts ON (ts.i_satuan_code = tm.i_satuan_code AND ts.id_company = tm.id_company)"
                ;

        // var_dump($sql); die();

        return $this->db->query($sql);
    }

	public function get_product($i_kategori, $i_sub_kategori, $search)
	{
		$kategori = '';
		if ($i_kategori !== 'null' && $i_kategori !== '') {
			$kategori = "AND (a.i_kode_kelompok = '$i_kategori')";
		}
		$sub_kategori = '';
		if ($i_sub_kategori !== 'null' && $i_sub_kategori !== '') {
			$sub_kategori = "AND (a.i_type_code = '$i_sub_kategori')";
		}

		return $this->db->query("SELECT a.id, a.i_product_base i_product, a.e_product_basename||' - '||e_color_name e_product_name
            FROM 
                tr_product_base a
			INNER JOIN tr_color c ON (
				c.i_color = a.i_color AND a.id_company = c.id_company
			)
            WHERE
				a.id_company = '$this->id_company'
				AND (
					a.e_product_basename ILIKE '%$search%' OR 
					i_product_base ILIKE '%$search%' OR 
					e_color_name ILIKE '%$search%'
				)
				$kategori
				$sub_kategori
			ORDER BY 2, 3");
	}
}
/* End of file Mmaster.php */