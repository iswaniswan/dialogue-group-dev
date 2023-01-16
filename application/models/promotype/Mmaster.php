<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select i_promo_type, e_promo_typename,'$i_menu' as i_menu  from tr_promo_type order by i_promo_type");

        
        $datatables->add('action', function ($data) {
            $i_promo_type = trim($data['i_promo_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"promotype/cform/view/$i_promo_type/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"promotype/cform/edit/$i_promo_type/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_promo_type');
        $this->db->where('i_promo_type', $id);
        return $this->db->get();

	}

	public function insert($ipromotype,$epromotypename){
        $data = array(
            'i_promo_type' => $ipromotype,
            'e_promo_typename' => $epromotypename
            
    );
    
    $this->db->insert('tr_promo_type', $data);
    }

    public function update($ipromotype,$epromotypename){
        $data = array(
            'i_promo_type' => $ipromotype,
            'e_promo_typename' => $epromotypename
    );

    $this->db->where('i_promo_type', $ipromotype);
    $this->db->update('tr_promo_type', $data);
    }



	
}

/* End of file Mmaster.php */
