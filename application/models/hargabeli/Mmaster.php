<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select i_product, e_product_name, i_price_group, i_product_grade, v_product_mill, d_product_priceentry, 
        d_product_priceupdate,'$i_menu' as i_menu from tr_harga_beli");

        
        $datatables->add('action', function ($data) {
            $i_product = trim($data['i_product']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"hargabeli/cform/view/$i_product/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"hargabeli/cform/edit/$i_product/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->edit('d_product_priceentry', function ($data) {
            $d_product_priceentry = $data['d_product_priceentry'];
            if($d_product_priceentry == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_product_priceentry) );
            }
        });
        
        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_harga_beli');
        $this->db->where('i_product', $id);
        return $this->db->get();

    }
    public function bacaproduct(){
        return $this->db->order_by('i_product','ASC')->get('tr_product')->result();
    }
    public function bacagrade(){
        return $this->db->order_by('i_product_grade','ASC')->get('tr_product_grade')->result();
    }

	public function insert($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill){
        $data = array(
            'i_product'           => $iproduct,
            'i_price_group'       => $ipricegroup,
            'e_product_name' => $eproductname,
            'i_product_grade' => $iproductgrade,
            'v_product_mill' => $vproductmill
    );
    
    $this->db->insert('tr_harga_beli', $data);
    }

    public function update($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill){
        $data = array(
            'i_price_group'       => $ipricegroup,
            'e_product_name' => $eproductname,
            'i_product_grade' => $iproductgrade,
            'v_product_mill' => $vproductmill
    );

    $this->db->where('i_product', $iproduct);
    $this->db->update('tr_harga_beli', $data);
    }



	
}

/* End of file Mmaster.php */
