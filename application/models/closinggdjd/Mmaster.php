<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmaster extends CI_Model
{
    public function closingpembelianbarangjadi($id_company, $ibagian, $periodeclosing, $periodenext)
    {
        $x     = $periodeclosing . '01';
        $awal  = date('Y-m-d', strtotime($x));
        $akhir = date('Y-m-t', strtotime($x));

        /*----------  Mutasi Gudang Jadi  ----------*/
        $this->db->query("DELETE FROM tm_mutasi_saldoawal_base_jadi WHERE id_company = $id_company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext'");
        $this->db->query("INSERT INTO tm_mutasi_saldoawal_base_jadi (id_company, i_bagian, e_mutasi_periode, i_product_base, i_color, n_saldo_awal, id_product_base, n_saldo_awal_repair, n_saldo_awal_gradeb)
            SELECT a.id_company, '$ibagian', '$periodenext', b.i_product_base, b.i_color, a.n_saldo_akhir, a.id_product_base, a.n_saldo_akhir_repair, a.n_saldo_akhir_gradeb 
            FROM f_mutasi_gudang_jadi ($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian') a
            INNER JOIN tr_product_base b ON (b.id = a.id_product_base)
        ");

        /*----------  Mutasi Material  ----------*/
        $this->db->query("DELETE FROM tm_mutasi_saldoawal_base_material WHERE id_company = $id_company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext'");
        $this->db->query("INSERT INTO tm_mutasi_saldoawal_base_material (id_company, i_bagian, e_mutasi_periode, i_material, n_saldo_awal, id_material)
            SELECT a.id_company, '$ibagian', '$periodenext', b.i_material, a.n_saldo_akhir, a.id_material
            FROM f_mutasi_material ($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian') a
            INNER JOIN tr_material b ON (b.id = a.id_material)
        ");

        /*----------  Update Harga Saldo Awal Material  ----------*/
        $this->db->query("UPDATE tm_mutasi_saldoawal_base_material AS query
            SET v_price = subquery.v_price
            FROM (SELECT id_material, v_price
                FROM f_mutasi_material_rp_hitung($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian') 
            ) AS subquery
            WHERE query.id_material = subquery.id_material AND query.e_mutasi_periode = '$periodenext' AND query.i_bagian = '$ibagian' ;
        ");

        /*----------  Mutasi Pengadaan  ----------*/
        $this->db->query("DELETE FROM tm_mutasi_saldoawal_base_pengadaan WHERE id_company = $id_company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext'");
        $this->db->query("INSERT INTO tm_mutasi_saldoawal_base_pengadaan (id_company, i_bagian, e_mutasi_periode, i_product_wip, i_color, n_saldo_awal, id_product_wip)
            SELECT a.id_company, '$ibagian', '$periodenext', b.i_product_wip, b.i_color, a.n_saldo_akhir, a.id_product_wip
            FROM f_mutasi_saldoawal_pengadaan_newbie ($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian') a
            INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)"
        );        

        /*----------  Mutasi Pengesettan  ----------*/
        $this->db->query("DELETE FROM tm_mutasi_saldoawal_base_pengesettan WHERE id_company = $id_company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext'");
        $this->db->query("INSERT INTO produksi.tm_mutasi_saldoawal_base_pengesettan
            (id_company, i_bagian, e_mutasi_periode, i_product_wip, i_color, id_panel_item, n_saldo_awal)
            SELECT a.id_company, '$ibagian' AS i_bagian, '$periodenext' AS e_mutasi_periode, b.i_product_wip, b.i_color, id_panel_item, n_saldo_akhir 
            FROM f_mutasi_saldoawal_pengesettan($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian') a
            INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)");

        /*----------  Mutasi Unit Jahit  ----------*/
        $this->db->query("DELETE FROM tm_mutasi_saldoawal_base_unitjahit WHERE id_company = $id_company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext'");
        $this->db->query("INSERT INTO tm_mutasi_saldoawal_base_unitjahit (id_company, i_bagian, e_mutasi_periode, i_product_wip, i_color, n_saldo_awal, id_product_wip, n_saldo_awal_repair)
            SELECT a.id_company, '$ibagian',  '$periodenext',  b.i_product_wip,  b.i_color, a.saldo_akhir, b.id, a.saldo_akhir_repair
            FROM f_mutasi_unitjahit ($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian') a
            INNER JOIN tr_product_base ab ON (ab.id = a.id_product_base)
            INNER JOIN tr_product_wip b ON (
                b.i_product_wip = ab.i_product_wip AND ab.i_color = b.i_color AND b.id_company = ab.id_company
            )
        ");

        /*----------  Mutasi WIP  ----------*/
        $this->db->query("DELETE FROM tm_mutasi_saldoawal_base_wip WHERE id_company = $id_company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext'");
        $this->db->query("INSERT INTO tm_mutasi_saldoawal_base_wip (id_company, i_bagian, e_mutasi_periode, i_product_wip, i_color, n_saldo_awal, id_product_wip, n_saldo_awal_repair)
            SELECT a.id_company, '$ibagian',  '$periodenext',  b.i_product_wip,  b.i_color, a.n_saldo_akhir, b.id, a.n_saldo_akhir_repair
            FROM f_mutasi_wip ($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian') a
            INNER JOIN tr_product_base ab ON (ab.id = a.id_product_base)
            INNER JOIN tr_product_wip b ON (
                b.i_product_wip = ab.i_product_wip AND ab.i_color = b.i_color AND b.id_company = ab.id_company
            )
        ");

        /*----------  Mutasi Packing  ----------*/
        $this->db->query("DELETE FROM tm_mutasi_saldoawal_base_unitpacking WHERE id_company = $id_company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext'");
        $this->db->query("INSERT INTO tm_mutasi_saldoawal_base_unitpacking (id_company, i_bagian, e_mutasi_periode, n_saldo_awal, id_product_base)
            SELECT id_company, '$ibagian',  '$periodenext',  n_saldo_akhir,  id_product_base
            FROM f_mutasi_packing ($id_company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ");
    }

    public function bagian()
    {
        $i_type = array('01', '02', '03', '04', '08', '09', '10', '12', '23');
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->company);
        $this->db->where_in('a.i_type', $i_type);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }
}
/* End of file Mmaster.php */