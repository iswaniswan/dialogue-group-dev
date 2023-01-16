<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
        $user = $this->session->userdata('username');
        
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" select tr_customer_area.i_area, tr_customer_area.e_area_name, tr_customer_area.i_customer, tr_customer.e_customer_name, '$i_menu' as i_menu
        from tr_customer_area
		inner join tr_customer on (tr_customer_area.i_customer=tr_customer.i_customer)
		inner join tr_area on (tr_customer_area.i_area=tr_area.i_area)
		where
		tr_customer_area.i_area in(select i_area from tm_user_area where i_user = '$user')
        ");
        
		$datatables->add('action', function ($data) {
			$i_area     = trim($data['i_area']);
            $i_customer = trim($data['i_customer']);
            $i_menu     = $data['i_menu'];
            $data = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerarea/cform/view/$i_area/$i_customer\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerarea/cform/edit/$i_area/$i_customer\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_area');
        $datatables->hide('i_customer');
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($iarea, $icustomer){
		$this->db->select('*');
        $this->db->from('tr_customer_area a');
        $this->db->join('tr_customer b','a.i_customer = b.i_customer');
        $this->db->where('a.i_customer', $icustomer);
        return $this->db->get();
	}
    
    function get_area(){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->order_by('i_area');
    return $this->db->get();
    }

	public function insert($icustomer,$iarea, $eareaname){
        $data = array(
            'i_customer' 	=> $icustomer,
            'i_area'        => $iarea,
            'e_area_name'   => $eareaname,
    );
    	$this->db->insert('tr_price_group', $data);
    }
    
    public function update($icustomer, $iarea, $eareaname){
        $data = array(
            'i_customer'    => $icustomer,
            'i_area'        => $iarea,
            'e_area_name'   => $eareaname,
    );

    $this->db->where('i_customer', $icustomer);
    $this->db->update('tr_customer_area', $data);
    }
}
?>
