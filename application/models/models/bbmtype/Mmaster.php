<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        //$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("Select i_bbm_type, e_bbm_typename, '$i_menu' as i_menu from tr_bbm_type");
		$datatables->add('action', function ($data) {
            $i_bbm_type = trim($data['i_bbm_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"bbmtype/cform/view/$i_bbm_type/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bbmtype/cform/edit/$i_bbm_type/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_bbm_type');
        $this->db->where('i_bbm_type', $id);
        return $this->db->get();

	}

	public function insert($ibbmtype,$ebbmtypename){
        $dbbmtypeentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_bbm_type' => $ibbmtype,
            'e_bbm_typename' => $ebbmtypename,
            'd_bbm_typeentry' => $dbbmtypeentry,
    );
    
    $this->db->insert('tr_bbm_type', $data);
    }

    public function update($ibbmtype,$ebbmtypename){
        $dbbmtypeupdate = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'e_bbm_typename' => $ebbmtypename,
            'd_bbm_typeupdate' => $dbbmtypeupdate
    );

    $this->db->where('i_bbm_type', $ibbmtype);
    $this->db->update('tr_bbm_type', $data);
    }



	
}

/* End of file Mmaster.php */
