<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT i_customer_salestype, e_customer_salestypename, $i_menu as i_menu FROM tr_customer_salestype");

		$datatables->add('action', function ($data) {
            $i_customer_salestype = trim($data['i_customer_salestype']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customersalestype/cform/view/$i_customer_salestype/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customersalestype/cform/edit/$i_customer_salestype/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_customer_salestype');
    $this->db->where('i_customer_salestype', $id);
    return $this->db->get();

	}


	public function insert($icustomersalestype, $icustomersalestypename){
        $data = array(
              'i_customer_salestype'     			=> $icustomersalestype,
              'e_customer_salestypename'       	    => $icustomersalestypename
    );
    $this->db->insert('tr_customer_salestype', $data);
    }

    public function update($icustomersalestype, $icustomersalestypename){

        $data = array(
             'e_customer_salestypename'       	     => $icustomersalestypename
    );

    $this->db->where('i_customer_salestype', $icustomersalestype);
    $this->db->update('tr_customer_salestype', $data);
    }

}

/* End of file Mmaster.php */
