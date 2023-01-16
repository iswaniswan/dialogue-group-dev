<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT i_customer, i_area, e_customer_setorname, e_customer_setorrekening, '$i_menu' AS i_menu
                             FROM tr_customer_setor WHERE f_show='t' ");
        
		$datatables->add('action', function ($data) {
            $i_customer = trim($data['i_customer']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customersetor/cform/view/$i_customer/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customersetor/cform/edit/$i_customer/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomer){
		$this->db->select('i_setor, i_customer, a.i_area, e_customer_setorname, e_customer_setoraddress, e_customer_setorphone, e_customer_setorrekening, e_area_name');
        $this->db->from('tr_customer_setor a');
        $this->db->join('tr_area b','a.i_area = b.i_area');
        $this->db->where('i_customer', $icustomer);
        return $this->db->get();
	}
    
    function get_area(){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->order_by('i_area');
    return $this->db->get();
    }

	public function insert($iarea,$icustomer,$ecustomersetor,$ecustomerrekening){
        $seq_tm	= $this->db->query(" SELECT i_setor FROM tr_customer_setor ORDER BY i_setor DESC LIMIT 1 ");
		if($seq_tm->num_rows() > 0 ) {
			$seqrow	= $seq_tm->row();
			$isetor	= $seqrow->i_setor+1;
		} else {
			$isetor	= 1;				
		}
        
        $data = array(
            'i_setor'                   => $isetor,
    		'i_customer'                => $icustomer,
			'i_area'                    => $iarea,
			'e_customer_setorname'      => $ecustomersetor,
			'e_customer_setorrekening'  => $ecustomerrekening,
			'f_show'                    => TRUE,
			'd_entry'                   => current_datetime()
    );
    	$this->db->insert('tr_customer_setor', $data);
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
