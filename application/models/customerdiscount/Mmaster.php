<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT a.i_customer, b.e_customer_name, a.n_customer_discount1, a.n_customer_discount2, 
                             a.n_customer_discount3, '$i_menu' AS i_menu
                             FROM tr_customer_discount a, tr_customer b 
                             WHERE a.i_customer=b.i_customer ");
        
		$datatables->add('action', function ($data) {
            $i_customer = trim($data['i_customer']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerdiscount/cform/view/$i_customer/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerdiscount/cform/edit/$i_customer/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomer){
		$this->db->select('a.i_customer, a.n_customer_discount1, a.n_customer_discount2, a.n_customer_discount3, b.e_customer_name');
        $this->db->from('tr_customer_discount a, tr_customer b ');
        $this->db->where('a.i_customer=b.i_customer');
        $this->db->where('a.i_customer', $icustomer);
        return $this->db->get();
	}
    
    function get_area(){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->order_by('i_area');
    return $this->db->get();
    }

	public function insert($icustomer, $ncustomerdiscount1, $ncustomerdiscount2,$ncustomerdiscount3){
        if($ncustomerdiscount1=='')
			$ncustomerdiscount1=0.00;
		if($ncustomerdiscount2=='')
			$ncustomerdiscount2=0.00;
		if($ncustomerdiscount3=='')
            $ncustomerdiscount3=0.00;
            
        $data = array(
            'i_customer' 	  	     => $icustomer,
            'n_customer_discount1' 	 => $ncustomerdiscount1,
			'n_customer_discount2'   => $ncustomerdiscount2,
			'n_customer_discount3'	 => $ncustomerdiscount3
    );
    	$this->db->insert('tr_customer_discount', $data);
    }
    
    public function update($icustomer, $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3){
        if($ncustomerdiscount1=='')
			$ncustomerdiscount1=0.00;
		if($ncustomerdiscount2=='')
			$ncustomerdiscount2=0.00;
		if($ncustomerdiscount3=='')
            $ncustomerdiscount3=0.00;
            
        $data = array(
            'i_customer' 	  	     => $icustomer,
            'n_customer_discount1' 	 => $ncustomerdiscount1,
			'n_customer_discount2'   => $ncustomerdiscount2,
			'n_customer_discount3'	 => $ncustomerdiscount3
        );

        $this->db->where('i_customer =', $icustomer);
		$this->db->update('tr_customer_discount', $data);
    }
}
?>
