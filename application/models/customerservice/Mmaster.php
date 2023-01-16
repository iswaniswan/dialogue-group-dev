<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT i_customer_service, e_customer_servicename, $i_menu as i_menu FROM tr_customer_service");

		$datatables->add('action', function ($data) {
            $i_customer_service = trim($data['i_customer_service']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerservice/cform/view/$i_customer_service/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerservice/cform/edit/$i_customer_service/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_customer_service');
    $this->db->where('i_customer_service', $id);
    return $this->db->get();

	}


	public function insert($icustomerservice, $icustomerservicename){
        $data = array(
              'i_customer_service'     			=> $icustomerservice,
              'e_customer_servicename'       	=> $icustomerservicename
    );
    $this->db->insert('tr_customer_service', $data);
    }

    public function update($icustomerservice, $icustomerservicename){

        $data = array(
              'e_customer_servicename'       	=> $icustomerservicename
    );

    $this->db->where('i_customer_service', $icustomerservice);
    $this->db->update('tr_customer_service', $data);
    }

}

/* End of file Mmaster.php */
