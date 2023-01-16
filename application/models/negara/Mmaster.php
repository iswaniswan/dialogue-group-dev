<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_country, e_country_name, '$i_menu' as i_menu from tr_country");
		$datatables->add('action', function ($data) {
            $i_country = trim($data['i_country']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"negara/cform/view/$i_country/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"negara/cform/edit/$i_country/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_country');
        $this->db->where('i_country', $id);
        return $this->db->get();

	}

	public function insert($icountry,$ecountryname){
        $data = array(
            'i_country' => $icountry,
            'e_country_name' => $ecountryname,
            'd_country_entry' => $this->db->query('SELECT current_timestamp AS c')->row()->c,
    );
    
    $this->db->insert('tr_country', $data);
    }

    public function update($icountry,$ecountryname){
        $data = array(
            'e_country_name' => $ecountryname,
            'd_country_update' => $this->db->query('SELECT current_timestamp AS c')->row()->c,
    );

    $this->db->where('i_country', $icountry);
    $this->db->update('tr_country', $data);
    }



	
}

/* End of file Mmaster.php */
