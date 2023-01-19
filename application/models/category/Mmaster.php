<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("Select a.i_product_category, a.e_product_categoryname, b.e_product_classname, a.d_product_categoryentry,'$i_menu' as i_menu 
        from tr_product_category a, tr_product_class b where a.i_product_class=b.i_product_class");
        $datatables->add('action', function ($data) {
            $i_product_category = trim($data['i_product_category']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"category/cform/view/$i_product_category/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"category/cform/edit/$i_product_category/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        $datatables->edit('d_product_categoryentry', function ($data) {
            $d_product_categoryentry = $data['d_product_categoryentry'];
            if($d_product_categoryentry == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_product_categoryentry) );
            }
        });

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_product_category');
        $this->db->where('i_product_category', $id);
        return $this->db->get();

	}

	public function insert($iproductcategory,$eproductcategoryname,$iproductclass){
        $data = array(
            'i_product_category' => $iproductcategory,
            'e_product_categoryname' => $eproductcategoryname,
            'i_product_class' => $iproductclass
  
    );
    $this->db->insert('tr_product_category', $data);
    }
    public function bacaclass(){
        return $this->db->order_by('i_product_class','ASC')->get('tr_product_class')->result();
    }

    public function update($iproductcategory,$eproductcategoryname,$iproductclass){
        $data = array(
            'i_product_category' => $iproductcategory,
            'e_product_categoryname' => $eproductcategoryname,
            'i_product_class' => $iproductclass,
    );

    $this->db->where('i_product_category', $iproductcategory);
    $this->db->update('tr_product_category', $data);
    }



	
}

/* End of file Mmaster.php */
