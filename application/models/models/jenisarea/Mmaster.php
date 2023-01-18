<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_area_type, e_area_typename, '$i_menu' AS i_menu from tr_area_type");
		$datatables->add('action', function ($data) {
            $i_area_type = trim($data['i_area_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"jenisarea/cform/view/$i_area_type/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"jenisarea/cform/edit/$i_area_type/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_area_type');
        $this->db->where('i_area_type', $id);
        return $this->db->get();

	}

	public function insert($iareatype,$eareatypename){
        $data = array(
            'i_area_type' => $iareatype,
            'e_area_typename' => $eareatypename,
    );
    
    $this->db->insert('tr_area_type', $data);
    }

    public function update($iareatype,$eareatypename){
        $data = array(
            'e_area_typename' => $eareatypename,
    );

    $this->db->where('i_area_type', $iareatype);
    $this->db->update('tr_area_type', $data);
    }



	
}

/* End of file Mmaster.php */
