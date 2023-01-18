<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query(" select i_product_seri, e_product_seriname, '$i_menu' as i_menu from tr_product_seri order by i_product_seri");
        $datatables->add('action', function ($data) {
            $i_product_seri = trim($data['i_product_seri']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"seri/cform/view/$i_product_seri/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"seri/cform/edit/$i_product_seri/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_product_seri');
        $this->db->where('i_product_seri', $id);
        return $this->db->get();

	}

	public function insert($iproductseri,$eproductseriname){
        $data = array(
            'i_product_seri' => $iproductseri,
            'e_product_seriname' => $eproductseriname,
            
    );
    
    $this->db->insert('tr_product_seri', $data);
    }

    public function update($iproductseri,$eproductseriname){
        $data = array(

            'i_product_seri' => $iproductseri,
            'e_product_seriname' => $eproductseriname
    );

    $this->db->where('i_product_seri', $iproductseri);
    $this->db->update('tr_product_seri', $data);
    }



	
}

/* End of file Mmaster.php */
