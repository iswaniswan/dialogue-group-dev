<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("select a.i_customer, b.e_customer_name, b.e_customer_address, b.e_customer_phone
		from tr_customer_consigment a, tr_customer b
		where a.i_customer=b.i_customer");

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_customer_consigment');
        $this->db->where('i_customer', $id);
        return $this->db->get();
	}

	public function insert($i_customer,$e_customer_name){
        $data = array(
            'i_customer' => $i_customer,
    );
    
    $this->db->insert('tr_customer_consigment', $data);
    }

	
}

/* End of file Mmaster.php */
