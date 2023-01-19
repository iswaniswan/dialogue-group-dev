<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("Select i_product_group, e_product_groupname, '$i_menu' as i_menu from tr_product_group");
        $datatables->add('action', function ($data) {
            $i_product_group = trim($data['i_product_group']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"group/cform/view/$i_product_group/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"group/cform/edit/$i_product_group/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_product_group');
        $this->db->where('i_product_group', $id);
        return $this->db->get();

	}

	public function insert($i_product_group,$e_product_groupname){
        $data = array(
            'i_product_group' => $i_product_group,
            'e_product_groupname' => $e_product_groupname,
    );
    
    $this->db->insert('tr_product_group', $data);
    }

    public function update($i_product_group,$e_product_groupname){
        $data = array(
            'i_product_group' => $i_product_group,
            'e_product_groupname' => $e_product_groupname,
            
    );

    
    $this->db->where('i_product_group', $i_product_group);
    $this->db->update('tr_product_group', $data);
    }



	
}

/* End of file Mmaster.php */
