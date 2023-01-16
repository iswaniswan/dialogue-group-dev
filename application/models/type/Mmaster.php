<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select a.i_product_type, a.e_product_typename, b.e_product_groupname, a.e_product_typenameprint1, a.e_product_typenameprint2, '$i_menu' as i_menu 
        from tr_product_type a, tr_product_group b
		where a.i_product_group = b.i_product_group");

        
        $datatables->add('action', function ($data) {
            $i_product_type = trim($data['i_product_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"type/cform/view/$i_product_type/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"type/cform/edit/$i_product_type/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('a.*, b.e_product_groupname');
        $this->db->from('tr_product_type a');
        $this->db->join( 'tr_product_group b','a.i_product_group=b.i_product_group');
        $this->db->where('a.i_product_type', $id);
        return $this->db->get();

	}

	public function insert($iproducttype,$eproducttypename,$eproducttypenameprint1,$eproducttypenameprint2){
        $data = array(
            'i_product_type'            => $iproducttype,
            'e_product_typename'        => $eproducttypename,
            'e_product_typenameprint1'  => $eproducttypenameprint1,
            'e_product_typenameprint2'  => $eproducttypenameprint2
    );
    
    $this->db->insert('tr_product_type', $data);
    }
    public function bacagroup(){
        return $this->db->order_by('i_product_group','ASC')->get('tr_product_group')->result();
    }
    public function bacaseri(){
        return $this->db->order_by('i_product_seri','ASC')->get('tr_product_seri')->result();
    }


    public function update($iproducttype,$eproducttypename,$eproducttypenameprint1,$eproducttypenameprint2){
        $data = array(
            'i_product_type'            => $iproducttype,
            'e_product_typename'        => $eproducttypename,
            'e_product_typenameprint1'  => $eproducttypenameprint1,
            'e_product_typenameprint2'  => $eproducttypenameprint2
    );

    $this->db->where('i_product_type', $iproducttype);
    $this->db->update('tr_product_type', $data);
    }



	
}

/* End of file Mmaster.php */
