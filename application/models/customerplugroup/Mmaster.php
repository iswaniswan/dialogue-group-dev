<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT i_customer, i_customer_plugroup, e_customer_plugroupname, '$i_menu' AS i_menu FROM tr_customer_plugroup ");
        
		$datatables->add('action', function ($data) {
			$i_customer             = trim($data['i_customer']);
			$i_customer_plugroup    = trim($data['i_customer_plugroup']);
            $i_menu                 = $data['i_menu'];
            $data                   = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerplugroup/cform/view/$i_customer/$i_customer_plugroup/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerplugroup/cform/edit/$i_customer/$i_customer_plugroup/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            
            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomer, $icustomerplugroup){
        $this->db->select('i_customer, e_customer_plugroupname, i_customer_plugroup');
        $this->db->from('tr_customer_plugroup');
        $this->db->where('i_customer', $icustomer);
        $this->db->where('i_customer_plugroup', $icustomerplugroup);
        return $this->db->get();
	}

	public function insert($icustomerplugroup, $ecustomerplugroupname, $icustomer){
            $data = array(
                'i_customer_plugroup'       => $icustomerplugroup,
    			'e_customer_plugroupname'   => $ecustomerplugroupname,
				'i_customer'                => $icustomer,
        );
            $this->db->insert('tr_customer_plugroup', $data);
    }
    
    public function update($icustomerplugroup, $ecustomerplugroupname, $icustomer){
            $data = array(
                'i_customer_plugroup'       => $icustomerplugroup,
    			'e_customer_plugroupname'   => $ecustomerplugroupname,
				'i_customer'                => $icustomer,
        );

        $this->db->where('i_customer', $icustomer);
        $this->db->where('i_customer_plugroup', $icustomerplugroup);
        $this->db->update('tr_customer_plugroup', $data);
    }
}
?>
