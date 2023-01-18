<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT a.i_customer_transfer, a.i_customer, b.e_customer_name,  $i_menu as i_menu 
            FROM tr_customer_transfer a 
            JOIN tr_customer b on a.i_customer = b.i_customer");

		$datatables->add('action', function ($data) {
            $icustomertransfer = trim($data['i_customer_transfer']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kodetransfer/cform/view/$icustomertransfer/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kodetransfer/cform/edit/$icustomertransfer/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_customer');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_customer_transfer');
    $this->db->join('tr_customer', 'tr_customer_transfer.i_customer = tr_customer.i_customer');
    $this->db->where('i_customer_transfer', $id);

    return $this->db->get();
    }
    
    function get_kodetransfer(){
        $this->db->select('*');
        $this->db->from('tr_customer_transfer');
    return $this->db->get();
    }

    function get_pelanggan(){
        $this->db->select('*');
        $this->db->from('tr_customer');
        $this->db->order_by('i_customer');
    return $this->db->get();
    }

	public function insert($icustomertransfer, $icustomer){
        $dentry = date("Y-m-d H:i:s"); 
        // $qcolor = $this->db->query("SELECT i_reff_transfer FROM tr_customer_transfer ORDER BY i_reff_transfer DESC LIMIT 1");
        // if ($qcolor->num_rows() > 0) {
        //     $row_color = $qcolor->row();
        //     $irefftransfer= $row_color->i_reff_transfer+1;
        // }
        // else
        //     $irefftransfer = 1;       
        $data = array(
              'i_customer_transfer' => $icustomertransfer,
              'i_customer'          => $icustomer,
              'd_entry'             => $dentry,                
              
    );
    
    $this->db->insert('tr_customer_transfer', $data);
    }

    public function update($icustomertransfer, $icustomer){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
              'i_customer_transfer' => $icustomertransfer,
              'i_customer'          => $icustomer,
              'd_update'            => $dupdate,               
    );

    $this->db->where('i_customer_transfer', $icustomertransfer);
    $this->db->update('tr_customer_transfer', $data);
    }

}

/* End of file Mmaster.php */