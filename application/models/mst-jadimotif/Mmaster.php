<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_product_motif.i_product, tr_product_motif.i_product_motif, tr_product_motif.e_product_motifname, $i_menu as i_menu FROM tr_product_motif");

		$datatables->add('action', function ($data) {
            $iproductmotif = trim($data['i_product_motif']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jadimotif/cform/view/$iproductmotif/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jadimotif/cform/edit/$iproductmotif/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
        $this->db->select('*');
        $this->db->from('tr_product_motif');
        $this->db->join('tr_product_base', 'tr_product_motif.i_product = tr_product_base.i_product_base');
        $this->db->join('tr_color', 'tr_product_motif.i_color = tr_color.i_color');
        $this->db->where('i_product_motif', $id);

    return $this->db->get();
    }
    
    function get_product(){
        $this->db->select('*');
        $this->db->from('tr_product_base');
    return $this->db->get();
    }

    function get_warna(){
        $this->db->select('*');
        $this->db->from('tr_color');
        $this->db->order_by('i_color', 'ASC');
    return $this->db->get();
    }

	public function insert($iproductmotif, $iproduct, $eproductmotifname, $nquantity, $icolor){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_product_motif'         => $iproductmotif,
              'i_product'               => $iproduct,
              'e_product_motifname'     => $eproductmotifname, 
              'n_quantity'              => $nquantity,
              'i_color'                 => $icolor, 
              'd_entry'                 => $dentry,        
        );    
    
        if($eproductmotifname != ""){            
            $this->db->insert('tr_product_motif', $data);

            $qryprice       = $this->db->query(" SELECT * FROM tr_product_price ORDER BY i_product_price DESC LIMIT 1 ");
            if($qryprice->num_rows()>0) {
                $row = $qryprice->row();
                $icodeprice0        = $row->i_product_price; // H00001
                $icodeprice_angka   = substr($icodeprice0,1,strlen($icodeprice0)-1); // 00001
                $icodeprice_jml = $icodeprice_angka+1;

                switch(strlen($icodeprice_jml)) {
                    case 1:
                        $icode  = 'H'.'0000'.$icodeprice_jml;
                    break;
                    case 2:
                        $icode  = 'H'.'000'.$icodeprice_jml;
                    break;
                    case 3:
                        $icode  = 'H'.'00'.$icodeprice_jml;
                    break;
                    case 4:
                        $icode  = 'H'.'0'.$icodeprice_jml;
                    break;
                    default:
                        $icode  = 'H'.$icodeprice_jml;
            }
            $icodeprice     = $icode;
                    
            }else{
                $icodeprice     = "H00001";
            }
            $qimotif    = $this->db->query("SELECT * FROM tr_product_base WHERE i_product_base='$iproduct' LIMIT 1 ");
                
            if($qimotif->num_rows()>0) {
                $rimotif    = $qimotif->row();
                $this->db->query("INSERT INTO tr_product_price (i_product_price, i_customer, i_product, i_product_motif, v_price, d_entry, d_update) 
                VALUES('$icodeprice','0','$iproduct','$iproductmotif','$rimotif->v_unitprice','$dentry','$dentry')");
            }
        }
    }

    public function update($iproductmotif, $iproduct, $eproductmotifname, $nquantity, $icolor){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
              'i_product_motif'         => $iproductmotif,
              'i_product'               => $iproduct,
              'e_product_motifname'     => $eproductmotifname, 
              'n_quantity'              => $nquantity,
              'i_color'                 => $icolor,  
              'd_update'                => $dupdate, 

    );

    $this->db->where('i_product_motif', $iproductmotif);
    $this->db->update('tr_product_motif', $data);
    }

}

/* End of file Mmaster.php */