<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
		$datatables->add('action', function ($data) {
            $i_supplier_group = trim($data['i_supplier_group']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"suppliergroup/cform/view/$i_supplier_group/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"suppliergroup/cform/edit/$i_supplier_group/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_supplier_group');
        $this->db->where('i_supplier_group', $id);
        return $this->db->get();

	}

	public function insert($isuppliergroup,$esuppliergroupname,$esuppliergroupnameprint1,$esuppliergroupnameprint2){
        $data = array(
            'i_supplier_group' => $isuppliergroup,
            'e_supplier_groupname' => $esuppliergroupname,
            'e_supplier_groupnameprint1' => $esuppliergroupnameprint1,
            'e_supplier_groupnameprint2' => $esuppliergroupnameprint2
    );
    
    $this->db->insert('tr_supplier_group', $data);
    }

    public function update($isuppliergroup,$esuppliergroupname,$esuppliergroupnameprint1,$esuppliergroupnameprint2){
        $data = array(
            'e_supplier_groupname' => $esuppliergroupname,
            'e_supplier_groupnameprint1' => $esuppliergroupnameprint1,
            'e_supplier_groupnameprint2' => $esuppliergroupnameprint2
    );

    $this->db->where('i_supplier_group', $isuppliergroup);
    $this->db->update('tr_supplier_group', $data);
    }



	
}

/* End of file Mmaster.php */
