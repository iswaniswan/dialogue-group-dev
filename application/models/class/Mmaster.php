<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("Select i_product_class, e_product_classname, d_product_classregister, '$i_menu' as i_menu from tr_product_class");
        $datatables->add('action', function ($data) {
            $i_product_class = trim($data['i_product_class']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"class/cform/view/$i_product_class/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"class/cform/edit/$i_product_class/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->edit('d_product_classregister', function ($data) {
            $d_product_classregister = $data['d_product_classregister'];
            if($d_product_classregister == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_product_classregister) );
            }
            });

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_product_class');
        $this->db->where('i_product_class', $id);
        return $this->db->get();

	}

	public function insert($iproductclass,$eproductclassname,$dproductclassregister){
        $data = array(
            'i_product_class' => $iproductclass,
            'e_product_classname' => $eproductclassname,
            'd_product_classregister' => $dproductclassregister,
    );
    
    $this->db->insert('tr_product_class', $data);
    }

    public function update($iproductclass,$eproductclassname,$dproductclassregister){
        $data = array(
            'i_product_class' => $iproductclass,
            'e_product_classname' => $eproductclassname,
            'd_product_classregister' => $dproductclassregister,
    );

    $this->db->where('i_product_class', $iproductclass);
    $this->db->update('tr_product_class', $data);
    }



	
}

/* End of file Mmaster.php */
