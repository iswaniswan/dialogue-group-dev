<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT i_customer, e_customer_bankname, e_customer_bankaccount, '$i_menu' AS i_menu FROM tr_customer_bank ");
        
		$datatables->add('action', function ($data) {
			$i_customer             = trim($data['i_customer']);
			$e_customer_bankname    = trim($data['e_customer_bankname']);
            $i_menu                 = $data['i_menu'];
            $data                   = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerbank/cform/view/$i_customer/$e_customer_bankname/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerbank/cform/edit/$i_customer/$e_customer_bankname/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            
            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomer, $ecustomerbankname){
        $ecustomerbankname = str_replace('%20',' ',trim($ecustomerbankname));

        $this->db->select('i_customer, e_customer_bankname, e_customer_bankaccount');
        $this->db->from('tr_customer_bank');
        $this->db->where('i_customer', $icustomer);
        $this->db->where('e_customer_bankname', $ecustomerbankname);
        return $this->db->get();
	}

	public function insert($icustomer, $ecustomerbankname, $ecustomerbankaccount){
            $data = array(
                'i_customer' 	            => $icustomer,
                'e_customer_bankname'       => $ecustomerbankname,
                'e_customer_bankaccount'    => $ecustomerbankaccount,
        );
            $this->db->insert('tr_customer_bank', $data);
    }
    
    public function update($icustomer, $ecustomerbankname, $ecustomerbankaccount){
            $data = array(
                'i_customer' 	            => $icustomer,
                'e_customer_bankname'       => $ecustomerbankname,
                'e_customer_bankaccount'    => $ecustomerbankaccount,
        );

        $this->db->where('i_customer', $icustomer);
        $this->db->update('tr_customer_bank', $data);
    }
}
?>
