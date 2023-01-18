<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query("
                            SELECT 
                                0 as no, 
                                i_bank, 
                                e_bank_name, 
                                e_jenis_name, 
                                id_company,
                                case when f_status = TRUE then 'Aktif' else 'Tidak' end as status, 
                                $i_menu as i_menu 
                            FROM 
                                tr_bank a 
                                LEFT JOIN 
                                    tr_jenis_bank b ON (a.i_jenis = b.i_jenis)
                            WHERE
                                id_company = '$idcompany'
                            ORDER BY
                                id");

        $datatables->edit(
                'status', 
                function ($data) {
                    $id         = trim($data['i_bank']);
                    //$folder     = $data['folder'];
                    $id_menu    = $data['i_menu'];
                    $status     = $data['status'];
                    if ($status=='Aktif') {
                        $warna = 'success';
                    }else{
                        $warna = 'danger';
                    }
                    $data    = '';
                    if(check_role($id_menu, 3)){
                        $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"mst-bank\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }else{
                        $data   .= "<span class=\"label label-$warna\">$status</span>";
                    }
                    return $data;
                }
        );

		$datatables->add('action', function ($data) {
            $ibank = trim($data['i_bank']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-bank/cform/view/$ibank/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-bank/cform/edit/$ibank/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('id_company');

        return $datatables->generate();
    }

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_bank');
        $this->db->where('i_bank', $id);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row    = $query->row();
            $status = $row->f_status;
            if ($status=='t') {
                $stat = 'f';
            }else{
                $stat = 't';
            }
        }
        $data = array(
            'f_status' => $stat 
        );
        $this->db->where('i_bank', $id);
        $this->db->update('tr_bank', $data);
}

    public function cek_data($id){
        return $this->db->query("SELECT a.id, a.i_bank, a.e_bank_name, a.i_jenis, b.e_jenis_name FROM tr_bank a LEFT JOIN tr_jenis_bank b ON (a.i_jenis = b.i_jenis) WHERE a.i_bank = '$id'", FALSE);
    }
    
    public function get_jenisbank($cari){
        return $this->db->query("SELECT * FROM tr_jenis_bank WHERE (i_jenis like '%$cari%' or e_jenis_name like '%$cari%') ORDER BY i_jenis", FALSE);
    }

    public function cekkode($kode){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT i_bank FROM tr_bank WHERE i_bank ='$kode' AND id_company = '$idcompany'", FALSE);
    }

    public function insert($ibank, $ebankname, $jenis){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'i_bank'      => $ibank,
            'e_bank_name' => $ebankname, 
            'i_jenis'     => $jenis,     
            'id_company'  => $idcompany,           
        );
        $this->db->insert('tr_bank', $data);
    }

    public function update($id,$ibank,$ebankname,$jenis){       
        $data = array(
            'i_bank'      => $ibank,
            'e_bank_name' => $ebankname, 
            'i_jenis'     => $jenis,       
        );

        $this->db->where('id', $id);
        $this->db->update('tr_bank', $data);
    }

}

/* End of file Mmaster.php */