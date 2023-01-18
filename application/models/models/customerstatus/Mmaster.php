<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT i_customer_status, e_customer_statusname, n_customer_statusdown, n_customer_statusup, 
                             n_customer_statusindex, '$i_menu' AS i_menu
                             FROM tr_customer_status ");
        
		$datatables->add('action', function ($data) {
            $i_customer_status  = trim($data['i_customer_status']);
            $i_menu             = $data['i_menu'];
            $data               = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerstatus/cform/view/$i_customer_status/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerstatus/cform/edit/$i_customer_status/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomerstatus){
		$this->db->select('i_customer_status, e_customer_statusname, n_customer_statusdown, n_customer_statusup, n_customer_statusindex');
        $this->db->from('tr_customer_status');
        $this->db->where('i_customer_status', $icustomerstatus);
        return $this->db->get();
	}
    
    function get_area(){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->order_by('i_area');
    return $this->db->get();
    }

	public function insert($icustomerstatus, $ecustomerstatusname, $ncustomerstatusdown,$ncustomerstatusup,$ncustomerstatusindex){
        $data = array(
            'i_customer_status'  	=> $icustomerstatus,
    		'e_customer_statusname'	=> $ecustomerstatusname,
			'n_customer_statusdown'	=> $ncustomerstatusdown,
			'n_customer_statusup'	=> $ncustomerstatusup,
			'n_customer_statusindex'=> $ncustomerstatusindex
    );
    	$this->db->insert('tr_customer_status', $data);
    }
    
    public function update($icustomerstatus, $ecustomerstatusname, $ncustomerstatusdown, $ncustomerstatusup, $ncustomerstatusindex){
        $data = array(
            'i_customer_status'  	=> $icustomerstatus,
    		'e_customer_statusname'	=> $ecustomerstatusname,
			'n_customer_statusdown'	=> $ncustomerstatusdown,
			'n_customer_statusup'	=> $ncustomerstatusup,
			'n_customer_statusindex'=> $ncustomerstatusindex
        );

        $this->db->where('i_customer_status =', $icustomerstatus);
		$this->db->update('tr_customer_status', $data);
    }
}
?>
