<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	
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
