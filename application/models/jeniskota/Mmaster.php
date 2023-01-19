<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_city_type, e_city_typename, '$i_menu' as i_menu from tr_city_type");
		$datatables->add('action', function ($data) {
            $i_city_type = trim($data['i_city_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"jeniskota/cform/view/$i_city_type/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"jeniskota/cform/edit/$i_city_type/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_city_type');
        $this->db->where('i_city_type', $id);
        return $this->db->get();

	}

	public function insert($icitytype,$ecitytypename){
        $data = array(
            'i_city_type' => $icitytype,
            'e_city_typename' => $ecitytypename,
            'd_city_typeentry' => $this->db->query('SELECT current_timestamp AS c')->row()->c,
        );
        $this->db->insert('tr_city_type', $data);
    }

    public function update($icitytype,$ecitytypename){
        $data = array(
            'e_city_typename' => $ecitytypename,
            'd_city_typeupdate' => $this->db->query('SELECT current_timestamp AS c')->row()->c,
    );

    $this->db->where('i_city_type',$icitytype);
    $this->db->update('tr_city_type', $data);
    }



	
}

/* End of file Mmaster.php */
