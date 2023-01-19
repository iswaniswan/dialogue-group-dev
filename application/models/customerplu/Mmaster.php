<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT i_customer_plugroup,i_customer_plu, i_product, f_customer_pluaktif, '$i_menu' AS i_menu FROM tr_customer_plu ");
        
		$datatables->add('action', function ($data) {
			$i_customer_plugroup    = trim($data['i_customer_plugroup']);
			$i_customer_plu         = trim($data['i_customer_plu']);
            $i_menu                 = $data['i_menu'];
            $data                   = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customerplu/cform/view/$i_customer_plugroup/$i_customer_plu/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerplu/cform/edit/$i_customer_plugroup/$i_customer_plu/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            
            return $data;
        });
        
        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($icustomerplu, $icustomerplugroup){
        $this->db->select('i_customer_plu, i_product, i_customer_plugroup, f_customer_pluaktif');
        $this->db->from('tr_customer_plu');
        $this->db->where('i_customer_plu', $icustomerplu);
        $this->db->where('i_customer_plugroup', $icustomerplugroup);
        return $this->db->get();
	}

	public function insert($icustomerplugroup, $icustomerplu, $iproduct){
            $data = array(
                'i_customer_plugroup' 	=> $icustomerplugroup,
    			'i_customer_plu'        => $icustomerplu,
                'i_product'             => $iproduct,
                'f_customer_pluaktif'   => 'TRUE'
        );
            $this->db->insert('tr_customer_plu', $data);
    }
    
    public function update($icustomerplugroup, $icustomerplu, $iproduct, $fcustomerpluaktif){
        if($fcustomerpluaktif=='on'){
			$fcustomerpluaktif='TRUE';
		}else{
			$fcustomerpluaktif='FALSE';
		}    
        
        $data = array(
            'i_customer_plugroup'   => $icustomerplugroup,
            'i_customer_plu'        => $icustomerplu,
            'i_product'             => $iproduct,
            'f_customer_pluaktif'   => $fcustomerpluaktif
        );

        $this->db->where('i_customer_plu', $icustomerplu);
		$this->db->where('i_customer_plugroup', $icustomerplugroup);
		$this->db->update('tr_customer_plu', $data); 
    }

    function get_plu(){
        $this->db->select('*');
        $this->db->from('tr_customer_plugroup');
        $this->db->order_by('i_customer_plugroup');
    return $this->db->get();
    }
}
?>
