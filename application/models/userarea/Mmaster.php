<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select a.i_user, b.e_area_name, '$i_menu' as i_menu from tm_user_area a JOIN tr_area b ON b.i_area = a.i_area");
		$datatables->add('action', function ($data) {
            $i_user = trim($data['i_user']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"userarea/cform/view/$i_user/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"userarea/cform/edit/$i_user/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tm_user_area');
        $this->db->where('i_user', $id);
        return $this->db->get();

	}

	public function insert($iuser, $iarea){
        $data = array(
            'i_user' => $iuser,
            'i_area' => $iarea,
    );
    $this->db->insert('tm_user_area', $data);
    }

    public function bacauser(){
        return $this->db->order_by('i_user','ASC')->get('tm_user')->result();
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }
}

/* End of file Mmaster.php */
