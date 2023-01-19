<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT c.e_area_name, b.e_customer_name, a.e_salesman_name, d.e_product_groupname, 
                            a.i_area, a.i_customer, a.i_salesman, a.i_product_group, a.e_periode, '$i_menu' AS i_menu
                            FROM tr_customer_salesman a, tr_customer b, tr_area c, tr_product_group d
                            WHERE a.i_customer = b.i_customer
                            AND a.i_area = c.i_area
                            AND a.i_area=b.i_area
                            AND a.i_product_group = d.i_product_group
        ");
        
		$datatables->add('action', function ($data) {
			$i_area          = trim($data['i_area']);
            $i_customer      = trim($data['i_customer']);
            $i_salesman      = trim($data['i_salesman']);
            $i_product_group = trim($data['i_product_group']);
            $e_periode       = trim($data['e_periode']);
            $i_menu          = $data['i_menu'];
            $data            = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customersalesman/cform/view/$i_area/$i_customer/$i_salesman/$i_product_group/$e_periode\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customersalesman/cform/edit/$i_area/$i_customer/$i_salesman/$i_product_group/$e_periode\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_area');
        $datatables->hide('i_customer');
        $datatables->hide('i_salesman');
        $datatables->hide('i_product_group');
        $datatables->hide('e_periode');
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($iarea, $icustomer, $iproductgroup){
		#$this->db->select('c.e_area_name, b.e_customer_name, a.e_salesman_name, d.e_product_groupname, a.i_area, a.i_customer, a.i_salesman, a.i_product_group, a.e_periode');
		$this->db->select('*');
        $this->db->from('tr_customer_salesman a');
        $this->db->join('tr_customer b','a.i_customer = b.i_customer AND a.i_area = b.i_area');
        $this->db->join('tr_area c','a.i_area = c.i_area');
        $this->db->join('tr_product_group d','a.i_product_group = d.i_product_group');
        $this->db->where('a.i_area', $iarea);
        $this->db->where('a.i_customer', $icustomer);
        $this->db->where('a.i_product_group', $iproductgroup);
        return $this->db->get();
	}
    
    function get_area(){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->order_by('i_area');
    return $this->db->get();
    }

    function get_jenis(){
        $this->db->select('*');
        $this->db->from('tr_product_group');
        $this->db->order_by('i_product_group');
    return $this->db->get();
    }

    function get_salesman(){
        $this->db->select('*');
        $this->db->from('tr_salesman');
        $this->db->where('f_salesman_aktif = TRUE ');
        $this->db->order_by('i_salesman');
    return $this->db->get();
    }

	public function insert($icustomer, $isalesman, $iarea, $esalesman, $iproductgroup, $iperiode){
        $data = array(
            'i_customer' 	    => $icustomer,
            'i_salesman'        => $isalesman,
            'i_area'            => $iarea,
            'e_salesman_name'   => $esalesman,
            'i_product_group'   => $iproductgroup,
            'e_periode'         => $iperiode,
    );
    	$this->db->insert('tr_customer_salesman', $data);
    }
    
    public function update($icustomer, $isalesman, $iarea, $esalesman, $iproductgroup, $iperiode){
        $data = array(
            'i_customer' 	    => $icustomer,
            'i_salesman'        => $isalesman,
            'i_area'            => $iarea,
            'e_salesman_name'   => $esalesman,
            'i_product_group'   => $iproductgroup,
            'e_periode'         => $iperiode,
    );

    $this->db->where('i_customer', $icustomer);
    $this->db->where('i_area', $iarea);
    $this->db->where('i_product_group', $iproductgroup);
    $this->db->update('tr_customer_salesman', $data);
    }
}
?>
