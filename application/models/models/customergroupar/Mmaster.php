<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT a.i_customer, a.i_customer_groupar, '$i_menu' AS i_menu 
                             FROM tr_customer_groupar a
                             JOIN tr_customer b ON(a.i_customer = b.i_customer) ");
        
		$datatables->add('action', function ($data) {
			$i_customer         = trim($data['i_customer']);
            $i_menu             = $data['i_menu'];
            $data               = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customergroupar/cform/view/$i_customer/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customergroupar/cform/edit/$i_customer/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            
            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomer){
		$this->db->select('a.i_customer, e_customer_name, i_customer_groupar as i_group');
        $this->db->from('tr_customer_groupar a');
        $this->db->join('tr_customer b','a.i_customer = b.i_customer');
        $this->db->where('a.i_customer', $icustomer);
        return $this->db->get();
	}

	public function insert($icustomer, $igroup){
        /*********INSERT KE GROUPAR*********/    
            $data = array(
                'i_customer' 	        => $icustomer,
                'i_customer_groupar'    => $igroup,
        );
            $this->db->insert('tr_customer_groupar', $data);
    }
    
    public function update($icustomer, $igroup){
       /*********UPDATE GROUPAR*********/
        $data = array(
            'i_customer' 	        => $icustomer,
            'i_customer_groupar'    => $igroup,
        );

        $this->db->where('i_customer', $icustomer);
        $this->db->update('tr_customer_groupar', $data);
    }
}
?>
