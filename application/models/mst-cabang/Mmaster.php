<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT a.i_branch, a.i_customer, b.e_customer_name, a.e_initial, a.e_branch_name, 
            a.e_branch_city, c.n_customer_discount1, 
            c.n_customer_discount2, c.n_customer_discount3, a.d_update, $i_menu as i_menu 
            FROM tr_branch a
            JOIN tr_customer b on a.i_customer=b.i_customer
            LEFT JOIN tr_customer_discount c on a.i_customer = c.i_customer and b.i_customer = c.i_customer", false);
        
		$datatables->add('action', function ($data) {
            $ibranch = trim($data['i_branch']);
            $i_menu = $data['i_menu'];
            $data = '';
            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"mst-cabang/cform/view/$ibranch/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-cabang/cform/edit/$ibranch/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_customer');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_branch');
    $this->db->join('tr_customer', 'tr_customer.i_customer = tr_branch.i_customer');
    $this->db->join('tr_customer_discount', 'tr_customer.i_customer = tr_branch.i_customer and tr_customer_discount.i_customer = tr_branch.i_customer','LEFT');
    $this->db->where('i_branch', $id);

    return $this->db->get();
    }
    
    function get_cabang(){
        $this->db->select('*');
        $this->db->from('tr_branch');
    return $this->db->get();
    }

    function get_pelanggan(){
        $this->db->select('*');
        $this->db->from('tr_customer');
        $this->db->order_by('i_customer', 'ASC');
    return $this->db->get();
    }

	public function insert($ibranch, $icustomer, $einitial, $ebranchname, $ecity, $ecodearea, $ebranchaddress){
        $dentry = date("Y-m-d H:i:s");
        
        $data = array(
              'i_branch'            => $ibranch,
              'i_customer'          => $icustomer,  
              'e_initial'           => $einitial,
              'e_branch_name'       => $ebranchname,
              'e_branch_city'       => $ecity,
              'i_code'              => $ecodearea,
              'e_branch_address'    => $ebranchaddress,
              'd_entry'             => $dentry,        
    );
    
    $this->db->insert('tr_branch', $data);
    }

    public function insertdiscount($icustomer, $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3){
        $dentry = date("Y-m-d H:i:s");
        
        $data = array(
            'i_customer'            => $icustomer,
            'n_customer_discount1'  => $ncustomerdiscount1,
            'n_customer_discount2'  => $ncustomerdiscount2, 
            'n_customer_discount3'  => $ncustomerdiscount3,
    );
    
    $this->db->insert('tr_customer_discount', $data);
    }

    public function update($ibranch, $icustomer, $einitial, $ebranchname, $ecity, $ecodearea, $ebranchaddress){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_branch'            => $ibranch,
            'i_customer'          => $icustomer,  
            'e_initial'           => $einitial,
            'e_branch_name'       => $ebranchname,
            'e_branch_city'       => $ecity,
            'i_code'              => $ecodearea,
            'e_branch_address'    => $ebranchaddress,             
            'd_update'            => $dupdate, 

    );

    $this->db->where('i_branch', $ibranch);
    $this->db->update('tr_branch', $data);
    }

    public function updatediscount($icustomer, $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_customer'            => $icustomer,
            'n_customer_discount1'  => $ncustomerdiscount1,
            'n_customer_discount2'  => $ncustomerdiscount2, 
            'n_customer_discount3'  => $ncustomerdiscount3,
    );

    $this->db->where('i_customer', $icustomer);
    $this->db->update('tr_customer_discount', $data);
    }

}

/* End of file Mmaster.php */