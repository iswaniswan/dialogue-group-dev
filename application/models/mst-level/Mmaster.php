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
                i_level, 
                e_level_name, 
                CASE 
                    WHEN f_status = TRUE 
                    THEN 'Aktif' 
                    ELSE 'Tidak Aktif' 
                END AS status, 
                $i_menu as i_menu, 
                '$folder' as folder 
            FROM public.tr_level", 
        FALSE);        
        $datatables->edit('status', 
            function ($data) {
                $id         = trim($data['i_level']);
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
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

		$datatables->add('action', function ($data) {
            $ilevel     = trim($data['i_level']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $data       = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ilevel/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');

        return $datatables->generate();
	}

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('public.tr_level');
        $this->db->where('i_level', $id);
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
        $this->db->where('trim(i_level)', trim($id));
        $this->db->update('public.tr_level', $data);
    }

	public function cek_data($ilevel){
        $this->db->select('*');
        $this->db->from('public.tr_level');
        $this->db->where('i_level', $ilevel);

        return $this->db->get();
    }
    
    public function get_user(){
        $this->db->select('*');
        $this->db->from('public.tr_level');
        return $this->db->get();
    }

	public function insert($ilevel, $elevel){
        $data = array(
            'i_level'       => $ilevel,
            'e_level_name'  => $elevel,
        );

        $this->db->insert('public.tr_level', $data);
    }

    public function update($ilevel, $elevel){
        $data = array(
            'e_level_name'  => $elevel,              
        );

        $this->db->where('i_level', $ilevel);
        $this->db->update('public.tr_level', $data);
    }
}
/* End of file Mmaster.php */