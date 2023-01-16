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
        $this->db->where('a.i_type', '03');    
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
                tm_mutasi_saldoawal_base_material
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            INSERT INTO 
                tm_mutasi_saldoawal_base_material
                (id_company, i_bagian, e_mutasi_periode, i_material, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_material, 
                    saldo_akhir 
                FROM f_mutasi_saldoawal_bp ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ", FALSE);
        $this->db->query("
            DELETE FROM
                tm_mutasi_saldoawal_pinjaman_material
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            INSERT INTO 
                tm_mutasi_saldoawal_pinjaman_material
                (id_company, i_bagian, e_mutasi_periode, i_material, id_partner, i_partner, type_partner, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_material,                     
                    id_partner,
                    i_partner,
                    type_partner,
                    saldo_akhir 
                FROM f_mutasi_saldoawal_bppinjaman ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ", FALSE);
    }
}
/* End of file Mmaster.php */