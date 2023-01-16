<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT i_customer_group, e_customer_groupname, '$i_menu' AS i_menu FROM tr_customer_group");
        
		$datatables->add('action', function ($data) {
			$i_customer_group   = trim($data['i_customer_group']);
            $i_menu             = $data['i_menu'];
            $data               = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customergroup/cform/view/$i_customer_group/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customergroup/cform/edit/$i_customer_group/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            
            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomergroup){
		$this->db->select('*');
        $this->db->from('tr_customer_group');
        $this->db->where('i_customer_group', $icustomergroup);
        return $this->db->get();
	}

	public function insert($icustomergroup,$ecustomergroupname){
        $data = array(
            'i_customer_group' 	     => $icustomergroup,
            'e_customer_groupname'   => $ecustomergroupname,
    );
    	$this->db->insert('tr_customer_group', $data);
    }
    
    public function update($icustomergroup,$ecustomergroupname){
        $data = array(
            'e_customer_groupname'   => $ecustomergroupname
    );

    $this->db->where('i_customer_group', $icustomergroup);
    $this->db->update('tr_customer_group', $data);
    }
}
?>
