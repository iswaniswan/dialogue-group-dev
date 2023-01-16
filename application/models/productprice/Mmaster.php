<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("Select i_product, e_product_name, i_price_group, i_product_grade, v_product_retail, 
        n_product_margin, d_product_priceentry, d_product_priceupdate,'$i_menu' as i_menu
        from tr_product_price");

        
        $datatables->add('action', function ($data) {
            $i_product = trim($data['i_product']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"productprice/cform/view/$i_product/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"productprice/cform/edit/$i_product/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->edit('d_product_priceentry', function ($data) {
            $d_product_priceentry = $data['d_product_priceentry'];
            if($d_product_priceentry == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_product_priceentry) );
            }
        });

        $datatables->edit('d_product_priceupdate', function ($data) {
            $d_product_priceupdate = $data['d_product_priceupdate'];
            if($d_product_priceupdate == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_product_priceupdate) );
            }
        });

        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_product_price');
        $this->db->where('i_product', $id);
        return $this->db->get();

	}
    function get_productgrade(){
        $this->db->select('*');
        $this->db->from('tr_product_grade');
        return $this->db->get();
    }
	public function insert($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill){
        $data = array(
            'iproduct'               => $iproduct,
            'eproductname'           => $eproductname,
            'iproductgrade'          => $iproductgrade,
            'nproductmargin'         => $nproductmargin,
            'vproductmill'           => $vproductmill
    );
    
    $this->db->insert('tr_product_price', $data);
    }

    public function update($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill){
        $data = array(
            'e_product_name'          => $eproductname,
            'i_product_grade'         => $iproductgrade,
            'n_product_margin'        => $nproductmargin,
            'v_product_mill'          => $vproductmill
    );

    $this->db->where('i_product', $iproduct);
    $this->db->update('tr_product_price', $data);
    }



	
}

/* End of file Mmaster.php */
