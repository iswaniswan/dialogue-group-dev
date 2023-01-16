<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT a.i_type, a.e_type_name, a.i_supplier_group, b.e_supplier_groupname, a.d_update, $i_menu as i_menu FROM tr_supplier_type a JOIN tr_supplier_group b on a.i_supplier_group=b.i_supplier_group");

		$datatables->add('action', function ($data) {
            $isuppliertype = trim($data['i_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jeniskategorisupplier/cform/view/$isuppliertype/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jeniskategorisupplier/cform/edit/$isuppliertype/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_supplier_group');
        
        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('a.i_type, a.e_type_name, a.i_supplier_group, b.e_supplier_groupname');
    $this->db->from('tr_supplier_type a');
    $this->db->join('tr_supplier_group b','b.i_supplier_group=a.i_supplier_group');
    $this->db->where('i_type', $id);

    return $this->db->get();
    }
    
    function get_supplier_group(){
        $this->db->select('*');
        $this->db->from('tr_supplier_group');
    return $this->db->get();
    }

    function get_supplier_type(){
        $this->db->select('*');
        $this->db->from('tr_supplier_type');
    return $this->db->get();
    }

	public function insert($isuppliertype, $isuppliertypename, $ikategorisupplier){
        $dentry = date("d F Y H:i:s");
        //$dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
              'i_type'         => $isuppliertype,
              'e_type_name'    => $isuppliertypename,   
              'i_supplier_group'     => $ikategorisupplier,
              'd_entry'        => $dentry,    
    );
    
    $this->db->insert('tr_supplier_type', $data);
    }

    public function update($isuppliertype, $isuppliertypename, $ikategorisupplier){
        $dupdate = date("d F Y H:i:s");
        $data = array(
              'i_type'        => $isuppliertype,
              'e_type_name'   => $isuppliertypename, 
              'i_supplier_group'    => $ikategorisupplier,
              'd_update'      => $dupdate,   
    );

    $this->db->where('i_type', $isuppliertype);
    $this->db->update('tr_supplier_type', $data);
    }

}

/* End of file Mmaster.php */