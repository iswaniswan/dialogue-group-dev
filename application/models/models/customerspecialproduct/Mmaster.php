<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT i_customer_specialproduct, e_customer_specialproductname, $i_menu as i_menu FROM tr_customer_specialproduct");

		$datatables->add('action', function ($data) {
            $i_customer_specialproduct= trim($data['i_customer_specialproduct']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerspecialproduct/cform/view/$i_customer_specialproduct/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerspecialproduct/cform/edit/$i_customer_specialproduct/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_customer_specialproduct');
    $this->db->where('i_customer_specialproduct', $id);
    return $this->db->get();

	}


	public function insert($icustomerspecialproduct, $icustomerspecialproductname){
        $data = array(
              'i_customer_specialproduct'     			       => $icustomerspecialproduct,
              'e_customer_specialproductname'       	       => $icustomerspecialproductname
    );
    $this->db->insert('tr_customer_specialproduct', $data);
    }

    public function update($icustomerspecialproduct, $icustomerspecialproductname){

        $data = array(
             'e_customer_specialproductname'       	     => $icustomerspecialproductname
    );

    $this->db->where('i_customer_specialproduct', $icustomerspecialproduct);
    $this->db->update('tr_customer_specialproduct', $data);
    }

}

/* End of file Mmaster.php */
