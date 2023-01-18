<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT a.i_customer, b.e_customer_name, a.e_customer_pkpname, a.e_customer_pkpaddress,
                             a.e_customer_pkpnpwp, '$i_menu' AS i_menu
                             FROM tr_customer_pkp a, tr_customer b
                             WHERE a.i_customer=b.i_customer ");
        
		$datatables->add('action', function ($data) {
            $i_customer = trim($data['i_customer']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerpkp/cform/view/$i_customer/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerpkp/cform/edit/$i_customer/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomer){
		$this->db->select('a.i_customer, b.e_customer_name, a.e_customer_pkpname, a.e_customer_pkpaddress, a.e_customer_pkpnpwp');
        $this->db->from('tr_customer_pkp a, tr_customer b');
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

	public function insert($icustomer, $ecustomerpkpname, $ecustomerpkpaddress,$ecustomerpkpnpwp){
        $data = array(
            'i_customer' 	  	      => $icustomer,
            'e_customer_pkpname' 	  => $ecustomerpkpname,
			'e_customer_pkpaddress'   => $ecustomerpkpaddress,
			'e_customer_pkpnpwp'	  => $ecustomerpkpnpwp
    );
    	$this->db->insert('tr_customer_pkp', $data);
    }
    
    public function update($icustomer, $ecustomerpkpname, $ecustomerpkpaddress, $ecustomerpkpnpwp){
        $data = array(
            'i_customer' 		      => $icustomer,
            'e_customer_pkpname' 	  => $ecustomerpkpname,
            'e_customer_pkpaddress'   => $ecustomerpkpaddress,
            'e_customer_pkpnpwp' 	  => $ecustomerpkpnpwp
        );

        $this->db->where('i_customer =', $icustomer);
        $this->db->update('tr_customer_pkp', $data);

        $this->db->select("i_customer from tr_customer_tmp where i_customer='$icustomer'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $data = array(
                    'i_customer' 		        => $icustomer,
                    'e_customer_npwpname' 	  => $ecustomerpkpname,
                        'e_customer_npwpaddress' => $ecustomerpkpaddress,
                        'e_customer_pkpnpwp' 	  => $ecustomerpkpnpwp
                    );
            $this->db->where('i_customer', $icustomer);
            $this->db->update('tr_customer_tmp', $data);
        }else{
            $this->db->select("i_customer from tr_customer_tmpnonspb where i_customer='$icustomer'", false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                $data = array(
                    'i_customer' 		        => $icustomer,
                    'e_customer_npwpname' 	  => $ecustomerpkpname,
                        'e_customer_npwpaddress' => $ecustomerpkpaddress,
                        'e_customer_pkpnpwp' 	  => $ecustomerpkpnpwp
                        );
                $this->db->where('i_customer', $icustomer);
                $this->db->update('tr_customer_tmpnonspb', $data);
            }
        }
    }
}
?>
