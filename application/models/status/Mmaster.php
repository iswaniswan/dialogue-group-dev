<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query(" select i_product_status, e_product_statusname, '$i_menu' as i_menu from tr_product_status order by i_product_status");
        $datatables->add('action', function ($data) {
            $i_product_status = trim($data['i_product_status']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"status/cform/view/$i_product_status/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"status/cform/edit/$i_product_status/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_product_status');
        $this->db->where('i_product_status', $id);
        return $this->db->get();

	}

	public function insert($iproductstatus,$eproductstatusname){
        $data = array(
            'i_product_status' => $iproductstatus,
            'e_product_statusname' => $eproductstatusname,
    );
    
    $this->db->insert('tr_product_status', $data);
    }

    public function update($iproductstatus,$eproductstatusname){
        $data = array(
            'i_product_status' => $iproductstatus,
            'e_product_statusname' => $eproductstatusname,
    );

    $this->db->where('i_product_status', $iproductstatus);
    $this->db->update('tr_product_status', $data);
    }



	
}

/* End of file Mmaster.php */
