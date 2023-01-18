<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT i_customer, e_customer_ownername, e_customer_owneraddress, e_customer_setor,
                             e_customer_ownerphone, '$i_menu' AS i_menu
                             FROM tr_customer_owner ");
        
		$datatables->add('action', function ($data) {
            $i_customer = trim($data['i_customer']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerowner/cform/view/$i_customer/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerowner/cform/edit/$i_customer/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomer){
		$this->db->select('i_customer, e_customer_ownername, e_customer_owneraddress, e_customer_ownerphone, e_customer_setor');
        $this->db->from('tr_customer_owner');
        $this->db->where('i_customer', $icustomer);
        return $this->db->get();
	}
    
    function get_area(){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->order_by('i_area');
    return $this->db->get();
    }

	public function insert($icustomer, $ecustomerownername, $ecustomerowneraddress,$ecustomerownerphone,$ecustomersetor){
        $data = array(
            'i_customer' 	  	      => $icustomer,
            'e_customer_ownername' 	  => $ecustomerownername,
			'e_customer_owneraddress' => $ecustomerowneraddress,
			'e_customer_ownerphone'	  => $ecustomerownerphone,
			'e_customer_setor'	      => $ecustomersetor
    );
    	$this->db->insert('tr_customer_owner', $data);
    }
    
    public function update($icustomer, $ecustomerownername, $ecustomerowneraddress, $ecustomerownerphone, $ecustomersetor){
        $data = array(
            'i_customer' 	  	      => $icustomer,
            'e_customer_ownername' 	  => $ecustomerownername,
			'e_customer_owneraddress' => $ecustomerowneraddress,
			'e_customer_ownerphone'	  => $ecustomerownerphone,
			'e_customer_setor'	      => $ecustomersetor
    );

    $this->db->where('i_customer', $icustomer);
    $this->db->update('tr_customer_owner', $data);

        $this->db->select("i_customer from tr_customer_tmp where i_customer='$icustomer'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $data = array(
                        'i_customer' 		       => $icustomer,
                        'e_customer_owner'         => $ecustomerownername,
                        'e_customer_owneraddress'  => $ecustomerowneraddress,
                        'e_customer_ownerphone'    => $ecustomerownerphone
                    );
            $this->db->where('i_customer', $icustomer);
            $this->db->update('tr_customer_tmp', $data);
        }else{
            $this->db->select("i_customer from tr_customer_tmpnonspb where i_customer='$icustomer'", false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
            $data = array(
                        'i_customer' 		       => $icustomer,
                        'e_customer_owner'         => $ecustomerownername,
                        'e_customer_owneraddress'  => $ecustomerowneraddress,
                        'e_customer_ownerphone'    => $ecustomerownerphone
                    );
            $this->db->where('i_customer', $icustomer);
            $this->db->update('tr_customer_tmpnonspb', $data);
            }
        }
    }
}
?>
