<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

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

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacaareabyid($area){
        return $this->db->where('i_area',$area)->get('tr_area')->row();
    }
    public function bacaspb($area){
        if($area == '00'){
            return $this->db->select('a.d_spb, a.i_spb, c.e_area_name, c.i_area, b.e_customer_name')
            ->join('tr_customer b','b.i_customer = a.i_customer')
            ->join('tr_area c','c.i_area = a.i_area')
            ->where('b.i_area',$area)
            ->where('a.i_nota','isnull')
            ->where('a.i_store !=','isnull')
            ->where('a.f_spb_cancel','f')
            ->where('a.i_approve1 !=','isnull')
            ->where('a.i_approve2 !=','isnull')
            ->where('a.f_spb_siapnotagudang','t')
            ->where('a.f_spb_siapnotasales','t')
            ->where("((a.f_spb_stockdaerah = 'f' and a.f_spb_consigment = 'f') or 
                (a.f_spb_stockdaerah = 't' and a.f_spb_consigment = 't'))")
            ->order_by('a.i_spb','DESC')->get('tm_spb a')->result();
        }else{
            return $this->db->select('a.d_spb, a.i_spb, c.e_area_name, c.i_area, b.e_customer_name')
            ->join('tr_customer b','b.i_customer = a.i_customer')
            ->join('tr_area c','c.i_area = a.i_area')
            ->where('b.i_area',$area)
            ->where('a.i_nota','isnull')
            ->where('a.i_store !=','isnull')
            ->where('a.f_spb_cancel','f')
            ->where('a.i_approve1 !=','isnull')
            ->where('a.i_approve2 !=','isnull')
            ->where('a.f_spb_stockdaerah','t')
            ->where('a.i_sj','isnull')
            ->order_by('a.i_spb','DESC')->get('tm_spb a')->result();
        }
    }


	
}

/* End of file Mmaster.php */
