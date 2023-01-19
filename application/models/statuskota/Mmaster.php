<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_city_status, e_city_statusname, '$i_menu' as i_menu from tr_city_status");
		$datatables->add('action', function ($data) {
            $i_city_status = trim($data['i_city_status']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"statuskota/cform/view/$i_city_status/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"statuskota/cform/edit/$i_city_status/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_city_status');
        $this->db->where('i_city_status', $id);
        return $this->db->get();

	}

	public function insert($icitystatus,$ecitystatusname){
        $data = array(
            'i_city_status' => $isuppliergroup,
            'e_city_statusname' => $esuppliergroupname,
    );
    
    $this->db->insert('tr_city_status', $data);
    }

    public function update($icitystatus,$ecitystatusname){
        $data = array(
            'e_city_statusname' => $esuppliergroupname,
    );

    $this->db->where('i_city_status', $icitystatus);
    $this->db->update('tr_city_status', $data);
    }	
}

/* End of file Mmaster.php */
