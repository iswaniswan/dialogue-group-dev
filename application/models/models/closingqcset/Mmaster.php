<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('b.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.i_type', '08');
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

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
                FROM f_mutasi_saldoawal_qcset ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ", FALSE);
    }
}
/* End of file Mmaster.php */