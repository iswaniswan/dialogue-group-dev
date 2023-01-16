<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select a.i_city_group, a.e_city_groupname, b.e_area_name, to_char(a.d_city_groupentry,'dd-mm-yyyy'), '$i_menu' as i_menu from tr_city_group a JOIN tr_area b ON b.i_area = a.i_area");
		$datatables->add('action', function ($data) {
            $i_city_group = trim($data['i_city_group']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"groupkota/cform/view/$i_city_group/\",\"#main\")'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"groupkota/cform/edit/$i_city_group/\",\"#main\")'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_city_group a');
        $this->db->join('tr_area b','b.i_area = a.i_area');
        $this->db->where('i_city_group', $id);
        return $this->db->get();

	}

	public function insert($icitygroup,$ecitygroupname,$iarea){
        $dentry = $this->db->query('SELECT current_timestamp AS')->c;
        $data = array(
            'i_city_group' => $icitygroup,
            'e_city_groupname' => $ecitygroupname,
            'i_area' => $iarea,
            'd_city_groupentry' => $dentry,
    );
    
    $this->db->insert('tr_city_group', $data);
    }

    public function update($icitygroup,$ecitygroupname,$iarea){
        $dentry = $this->db->query('SELECT current_timestamp AS')->c;
        $data = array(
            'e_city_groupname' => $ecitygroupname,
            'i_area' => $iarea,
            'd_city_groupupdate' => $dentry,
    );

    $this->db->where('i_city_group', $icitygroup);
    $this->db->update('tr_city_group', $data);
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

	
}

/* End of file Mmaster.php */
