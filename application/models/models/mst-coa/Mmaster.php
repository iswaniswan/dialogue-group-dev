<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query("
                            SELECT 
                                0 as no, 
                                a.i_coa, 
                                a.e_coa_name, 
                                b.e_coa_ledger_name, 
                                c.e_coa_type_name,
                                a.id_company,
                                CASE
                                    WHEN a.f_status = 't' 
                                    THEN 'Aktif'
                                    ELSE 'Tidak Aktif'
                                END AS status, 
                                '$i_menu' as i_menu, 
                                '$folder' as folder 
                            FROM 
                                tr_coa a
                                LEFT JOIN 
                                    tr_coa_ledger b ON (a.i_coa_ledger = b.i_coa_ledger)
                                LEFT JOIN 
                                    tr_coa_type c ON (a.id_coa_type = c.id)
                            /*WHERE a.id_company = '$idcompany'*/
                            ORDER BY
                                a.i_coa
                        ", FALSE);
        $datatables->edit(
            'status', 
            function ($data) {
                $id         = trim($data['i_coa']);
                $folder     = $data['folder'];
                $id_menu    = $data['i_menu'];
                $status     = $data['status'];
                if ($status=='Aktif') {
                    $warna = 'success';
                }else{
                    $warna = 'danger';
                }
                $data    = '';
                if(check_role($id_menu, 3)){
                    //$data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

		$datatables->add('action', function ($data) {
            $icoa   = trim($data['i_coa']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$icoa/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$icoa/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');

        return $datatables->generate();
    }
    
    public function cekkode($kode){
        return $this->db->query("SELECT i_coa FROM tr_coa WHERE i_coa ='$kode'", FALSE);
    }

	public function cek_data($id){
        return $this->db->query("
                                SELECT 
                                    a.*,
                                    b.e_coa_ledger_name,
                                    c.e_coa_type_name 
                                FROM 
                                    tr_coa a
                                    LEFT JOIN 
                                        tr_coa_ledger b ON (a.i_coa_ledger = b.i_coa_ledger)
                                    LEFT JOIN 
                                        tr_coa_type c ON (a.id_coa_type = c.id AND b.id_coa_type = c.id)
                                WHERE 
                                    i_coa='$id'
                                ", FALSE);
    }
    
    function get_coa(){
        $this->db->select('*');
        $this->db->from('tr_coa');
    return $this->db->get();
    }

	public function insert($icoa, $ecoaname, $icoagroup, $icoatype){  
        $idcompany  = $this->session->userdata('id_company');   
        $data = array(
              'i_coa'         => $icoa,
              'e_coa_name'    => $ecoaname,
              'i_coa_ledger'  => $icoagroup,
              'id_coa_type'   => $icoatype,
              'id_company'    => $idcompany,               
    );
    $this->db->insert('tr_coa', $data);
  }

    public function update($id, $icoa, $ecoaname, $icoagroup, $icoatype){       
        $data = array(
            'i_coa'         => $icoa,
            'e_coa_name'    => $ecoaname,
            'i_coa_ledger'  => $icoagroup,
            'id_coa_type'   => $icoatype  
    );

    $this->db->where('id', $id);
    $this->db->update('tr_coa', $data);
    }


    public function groupcoa($cari) {
        return $this->db->query("
                                SELECT 
                                    i_coa_ledger, 
                                    e_coa_ledger_name 
                                FROM 
                                    tr_coa_ledger 
                                WHERE 
                                    f_status = 't' 
                                    AND (i_coa_ledger like '%$cari%' or e_coa_ledger_name like '%$cari%') 
                                ORDER BY
                                    i_coa_ledger
                                ", FALSE);
    }

    public function gettype($id){
        return $this->db->query(" 
                                SELECT 
                                    a.id_coa_type, 
                                    b.e_coa_type_name
                                FROM 
                                    tr_coa_ledger a
                                    LEFT JOIN 
                                        tr_coa_type b ON (a.id_coa_type = b.id)
                                WHERE
                                    b.f_status = 't' 
                                    AND a.i_coa_ledger = '$id'
                                ", FALSE);
    }

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_coa');
        $this->db->where('i_coa', $id);
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
        $this->db->where('i_coa', $id);
        $this->db->update('tr_coa', $data);
    }

}

/* End of file Mmaster.php */