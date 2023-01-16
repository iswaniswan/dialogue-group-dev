<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("
            SELECT
                0 AS no,
                i_departement, 
                e_departement_name, 
                CASE 
                    WHEN f_status = TRUE 
                    THEN 'Aktif' 
                    ELSE 'Tidak Aktif' 
                END AS status, 
                $i_menu AS i_menu, 
                '$folder' AS folder 
            FROM public.tr_departement",
            FALSE);
        $datatables->edit('status',
            function ($data) {
                $id         = trim($data['i_departement']);
                $folder     = $data['folder'];
                $id_menu    = $data['i_menu'];
                $status     = $data['status'];
                if ($status=='Aktif') {
                    $warna = 'success';
                }else{
                    $warna = 'danger';
                }
                $data      = '';
                if(check_role($id_menu, 3)){
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

		$datatables->add('action', function ($data) {
            $isept  = trim($data['i_departement']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data   = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isept/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');

        return $datatables->generate();
	}

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('public.tr_departement');
        $this->db->where('i_departement', $id);
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
        $this->db->where('i_departement', $id);
        $this->db->update('public.tr_departement', $data);
    }

	public function cek_data($idept){
        $this->db->select('*');
        $this->db->from('public.tr_departement');
        $this->db->where('i_departement', $idept);

        return $this->db->get();
    }
    
    public function get_user(){
        $this->db->select('*');
        $this->db->from('tm_user');
        return $this->db->get();
    }

    public function insert($idept, $edept){
        $data = array(
            'i_departement'       => $idept,
            'e_departement_name'  => $edept,                  
        );

        $this->db->insert('public.tr_departement', $data);
    }

    public function update($idept, $edept){
        $data = array(
            'i_departement'       => $idept,
            'e_departement_name'  => $edept,
        );

        $this->db->where('i_departement', $idept);
        $this->db->update('public.tr_departement', $data);
    }
}
/* End of file Mmaster.php */