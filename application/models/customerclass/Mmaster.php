<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT i_customer_class, e_customer_classname, $i_menu as i_menu FROM tr_customer_class");

		$datatables->add('action', function ($data) {
            $i_customer_class = trim($data['i_customer_class']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerclass/cform/view/$i_customer_class/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerclass/cform/edit/$i_customer_class/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_customer_class');
    $this->db->where('i_customer_class', $id);
    return $this->db->get();

	}


	public function insert($icustomerclass, $icustomerclassname){
        $data = array(
              'i_customer_class'     			=> $icustomerclass,
              'e_customer_classname'       	    => $icustomerclassname,
              'n_urut'                          => $icustomerclass
    );
    $this->db->insert('tr_customer_class', $data);
    }

    public function update($icustomerclass, $icustomerclassname){

        $data = array(
              'e_customer_classname'            => $icustomerclassname
    );

    $this->db->where('i_customer_class', $icustomerclass);
    $this->db->update('tr_customer_class', $data);
    }

}

/* End of file Mmaster.php */
