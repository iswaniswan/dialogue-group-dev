<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT i_customer_producttype, e_customer_producttypename, $i_menu as i_menu FROM tr_customer_producttype");

		$datatables->add('action', function ($data) {
            $i_customer_producttype = trim($data['i_customer_producttype']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerproducttype/cform/view/$i_customer_producttype/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerproducttype/cform/edit/$i_customer_producttype/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_customer_producttype');
    $this->db->where('i_customer_producttype', $id);
    return $this->db->get();

	}


	public function insert($icustomerproducttype, $icustomerproducttypename){
        $data = array(
              'i_customer_producttype'     			=> $icustomerproducttype,
              'e_customer_producttypename'       	    => $icustomerproducttypename
    );
    $this->db->insert('tr_customer_producttype', $data);
    }

    public function update($icustomerproducttype, $icustomerproducttypename){

        $data = array(
             'e_customer_producttypename'       	     => $icustomerproducttypename
    );

    $this->db->where('i_customer_producttype', $icustomerproducttype);
    $this->db->update('tr_customer_producttype', $data);
    }

}

/* End of file Mmaster.php */
