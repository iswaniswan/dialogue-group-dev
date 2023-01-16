<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  Baca Bagian Berdasarkan Type  ----------*/    

    public function bagian() 
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->where('a.i_type', '11');    
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  Proses Closing Gudang  ----------*/
    
    public function closingsaldo($ibagian,$periodeclosing,$periodenext) 
    {
        $x     = $periodeclosing.'01';
        $awal  = date('Y-m-d', strtotime($x));
        $akhir = date('Y-m-t', strtotime($x));
        $this->db->query("
            DELETE FROM
                tm_mutasi_saldoawal_base_jadi
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            INSERT INTO 
                tm_mutasi_saldoawal_base_jadi
                (id_company, i_bagian, e_mutasi_periode, i_product_base, i_color, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_product_base, 
                    i_color,
                    saldo_akhir 
                FROM f_mutasi_saldoawal_qc ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ", FALSE);

        /*$this->db->query("
            INSERT INTO 
                tm_mutasi_saldoawal_pinjaman_material
                (id_company, i_bagian, e_mutasi_periode, i_material, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_material, 
                    saldo_akhir 
                FROM f_mutasi_saldoawal_bbpinjaman ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
                ON CONFLICT (id_company, i_bagian, e_mutasi_periode, i_material) DO UPDATE SET n_saldo_awal = excluded.n_saldo_awal
        ", FALSE);*/
    }
}
/* End of file Mmaster.php */