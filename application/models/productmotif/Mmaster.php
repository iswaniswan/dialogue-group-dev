<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("Select a.i_product, b.e_product_name, a.i_product_motif, a.e_product_motifname,'$i_menu' as i_menu  
        from tr_product_motif a, tr_product b 
        where  a.i_product=b.i_product
        order by a.i_product,a.i_product_motif");

        
        $datatables->add('action', function ($data) {
            $i_product_motif = trim($data['i_product_motif']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"productmotif/cform/view/$i_product_motif/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"productmotif/cform/edit/$i_product_motif/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_product_motif');
        $this->db->where('i_product_motif', $id);
        return $this->db->get();

	}

	public function insert($iproduct,$iproductmotif,$eproductmotifname){
        $data = array(
            'i_product'          => $iproduct,
            'i_product_motif'     => $iproductmotif,
            'e_product_motifname' => $eproductmotifname
            
    );
    
    $this->db->insert('tr_product_motif', $data);
    }
    public function bacaproduct(){
        return $this->db->order_by('i_product','ASC')->get('tr_product')->result();
    }
    public function update($iproduct,$iproductmotif,$eproductmotifname){
        $data = array(
            'i_product'          => $iproduct,
            'i_product_motif'     => $iproductmotif,
            'e_product_motifname' => $eproductmotifname
    );

    
    $this->db->where('i_product', $iproduct);
    $this->db->update('tr_product_motif', $data);
    }



	
}

/* End of file Mmaster.php */
