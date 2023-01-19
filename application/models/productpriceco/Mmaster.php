<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	public function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("select a.i_product, b.e_product_name, a.i_price_group, d.e_price_groupconame, a.i_product_grade, a.v_product_retail,'$i_menu' as i_menu,'$folder' as folder
            from tr_product b, tr_product_grade c, tr_product_priceco a, tr_price_groupco d 
            where a.i_product=b.i_product and a.i_product_grade=c.i_product_grade and a.i_price_groupco=d.i_price_groupco");
        $datatables->add('action', function ($data) {
            $i_product = trim($data['i_product']);
            $i_price_group = trim($data['i_price_group']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_product/$i_price_group/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function data_groupco(){
        return $this->db->get('tr_price_groupco');
    }

    public function getharga($igroup){
        $this->db->select("i_price_groupco, e_price_groupconame, n_margin"); 
        $this->db->from("tr_price_groupco");
        $this->db->where("i_price_groupco", $igroup);
        return $this->db->get();
    }

    public function cekco($iproduct,$ipricegroup,$iproductgrade){  
        $ada=true;  
        $query=$this->db->query(" 
            SELECT
                i_product
            FROM
                tr_product_priceco
            WHERE
                i_product = '$iproduct'
            AND i_product_grade = '$iproductgrade'
            AND i_price_group ='$ipricegroup'",false);  
        if ($query->num_rows() > 0){
            foreach($query->result() as $qq){
                $ada=false;
            }
        }
        return $ada;
    }

    public function insert($iproduct,$ipricegroup,$iproductgrade,$vproductretail,$nmargin,$ipricegroupco){       
        $dentry= current_datetime();
        $data = array(
            'i_product'         => $iproduct,
            'i_product_grade'   => $iproductgrade,
            'i_price_group'     => $ipricegroup,
            'v_product_retail'  => $vproductretail,
            'n_margin'          => $nmargin,
            'i_price_groupco'   => $ipricegroupco,
            'd_entry'           => $dentry
        );
        $this->db->insert('tr_product_priceco', $data);
    }

    public function update($iproduct,$eproductname,$iproductgrade,$ipricegroup,$vproductretail,$nmargin){
        $dupdate = current_datetime();
        $data = array(
            'v_product_retail' => $vproductretail,
            'n_margin'         => $nmargin,
            'd_update'         => $dupdate
        );
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_price_group', $ipricegroup);
        $this->db->update('tr_product_priceco', $data);
    }

    public function cekharga($iproduct,$ipricegroup,$iproductgrade){          
        $ada=true;
        $this->db->select('i_product');
        $this->db->from('tr_product_price');
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_price_group', $ipricegroup);            
        $query=$this->db->get();            
        if ($query->num_rows() > 0){                
            foreach($query->result() as $qq){                    
                $ada=false;                
            }
        }
        return $ada;
    }

    public function insertnet($iproduct,$ipricegroup,$iproductgrade,$eproductname,$vproductretail,$nmargin){  
        $this->db->select('v_product_mill');
        $this->db->from('tr_product_price');
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_price_group', '00');
        $que = $this->db->get();   
        if ($que->num_rows() > 0){              
            foreach($que->result() as $qq){          
                $hrg=$qq->v_product_mill;        
            }
            $dentry= current_datetime();
            $data = array(
                'i_product'             => $iproduct,
                'i_product_grade'       => $iproductgrade,
                'i_price_group'         => $ipricegroup,
                'e_product_name'        => $eproductname,
                'v_product_retail'      => $vproductretail,
                'v_product_mill'        => $hrg,
                'd_product_priceentry'  => $dentry,
                'n_product_margin'      => $nmargin
            );
            $this->db->insert('tr_product_price', $data);
        }
    }

    public function updatenet($iproduct,$ipricegroup,$iproductgrade,$eproductname,$vproductretail,$nmargin){
        $dupdate = current_datetime(); 
        $data = array(
            'v_product_retail'      => $vproductretail,
            'd_product_priceupdate' => $dupdate 
        );
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_price_group', $ipricegroup);
        $this->db->update('tr_product_price', $data);
    }

    public function baca($iproduct,$ipricegroup){
        $this->db->select('a.*, b.e_product_name, c.e_product_gradename');
        $this->db->from('tr_product_priceco a');
        $this->db->join('tr_product b','b.i_product = a.i_product');
        $this->db->join('tr_product_grade c','c.i_product_grade = a.i_product_grade');
        $this->db->where('a.i_product', $iproduct);
        $this->db->where('a.i_price_group', $ipricegroup);
        $query = $this->db->get();        
        if ($query->num_rows() > 0){          
            return $query->row();      
        }
    }
}

/* End of file Mmaster.php */
