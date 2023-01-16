<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select a.i_product_type, a.e_product_typename, b.e_product_groupname, a.e_product_typenameprint1, a.e_product_typenameprint2, '$i_menu' as i_menu from tr_product_type a, tr_product_group b
		where a.i_product_group = b.i_product_group");

        
        $datatables->add('action', function ($data) {
            $i_product_type = trim($data['i_product_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"class/cform/view/$i_product_type/\",\"#main\")'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"class/cform/edit/$i_product_type/\",\"#main\")'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_supplier_group');
        $this->db->where('i_supplier_group', $id);
        return $this->db->get();

    }
    
    function bacakode()
    {
		  $this->db->select(" * from tr_price_group order by i_price_group", false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }

	public function insert($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill,$vproductretail){
        $query = $this->db->query("SELECT current_timestamp as c");
		$row   = $query->row();
		$dentry= $row->c;
        $data = array(
            'i_product'         => $iproduct,
            'e_product_name'    => $eproductname,
            'i_product_grade'   => $iproductgrade,
            'i_price_group'     => $ipricegroup,
            'v_product_retail'  => $vproductretail,
            'v_product_mill'             => $vproductmill,
            'd_product_priceentry'       => $dentry
    );
    $this->db->insert('tr_product_price', $data);
    }

    public function update($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill,$vproductretail){
        $query = $this->db->query("SELECT current_timestamp as c");
        $row   = $query->row();
        $dupdate= $row->c;
        $data = array(
            'i_product'         => $iproduct,
            'e_product_name'    => $eproductname,
            'i_product_grade'   => $iproductgrade,
            'i_price_group'     => $ipricegroup,
            'v_product_retail'  => $vproductretail,
            'v_product_mill'    => $vproductmill,
            'd_product_priceentry'           => $dupdate
    );

    $this->db->where('i_product', $iproduct);
    $this->db->update('tr_product_price', $data);
    }
    public function bacaproduct(){
        return $this->db->order_by('i_product','ASC')->get('tr_product')->result();
    }
    public function bacagrade(){
        return $this->db->order_by('i_product_grade','ASC')->get('tr_product_grade')->result();
    }


	
}

/* End of file Mmaster.php */
