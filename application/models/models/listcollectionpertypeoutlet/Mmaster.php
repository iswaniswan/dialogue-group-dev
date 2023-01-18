<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function bacaperiode($dfrom,$dto,$interval){
        $perfrom=substr($dfrom,6,4).substr($dfrom,3,2);
        $perto  =substr($dto,6,4).substr($dto,3,2);

        $query = $this->db->query("
                                SELECT 
                                    a.i_customer_class, 
                                    a.e_customer_classname, 
                                    a.bln, 
                                    sum(a.total) as total, 
                                    sum(a.realisasi) as realisasi 
                                FROM( 
                                    SELECT 
                                        a.i_customer_class, 
                                        c.e_customer_classname, 
                                        substring(b.e_periode,5,2) as bln, 
                                        sum(b.v_target_tagihan) as total, 
                                        sum(b.v_realisasi_tagihan) as realisasi 
                                    FROM 
                                        tm_collection d, 
                                        tm_collection_item b, 
                                        tr_customer a, 
                                        tr_customer_class c 
                                    WHERE 
                                        a.i_customer=b.i_customer 
                                        and b.e_periode>='$perfrom' 
                                        and b.e_periode<='$perto' 
                                        and a.i_customer_class=c.i_customer_class
                                        and b.e_periode=d.e_periode
                                    GROUP BY 
                                        a.i_customer_class, 
                                        c.e_customer_classname, 
                                        substring(b.e_periode,5,2)) as a 
                                GROUP BY 
                                    a.i_customer_class, 
                                    a.e_customer_classname, 
                                    a.bln 
                                ORDER BY 
                                    a.i_customer_class, 
                                    a.e_customer_classname, 
                                    a.bln"
                                ,false);

        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    function sumperiode($dfrom,$dto,$interval){
      $perfrom=substr($dfrom,6,4).substr($dfrom,3,2);
      $perto  =substr($dto,6,4).substr($dto,3,2);
      $query = $this->db->query("
                                SELECT 
                                    a.bln, 
                                    sum(a.total) as total 
                                FROM( 
                                    SELECT 
                                    a.i_customer_class, 
                                    c.e_customer_classname, 
                                    substring(b.e_periode,5,2) as bln, 
                                    sum(b.v_target_tagihan) as total, 
                                    sum(b.v_realisasi_tagihan) as realisasi 
                                    FROM 
                                    tm_collection_item b, 
                                    tr_customer a, 
                                    tr_customer_class c 
                                    WHERE 
                                    a.i_customer=b.i_customer and 
                                    b.e_periode>='$perfrom' and 
                                    b.e_periode<='$perto' and 
                                    a.i_customer_class=c.i_customer_class 
                                    GROUP BY 
                                    a.i_customer_class, 
                                    c.e_customer_classname, 
                                    substring(b.e_periode,5,2)) as a 
                                GROUP BY 
                                    a.bln"
                                ,FALSE);
		if ($query->num_rows() > 0){
		    return $query->result();
		}
    }

    function interval($dfrom,$dto){
        if($dfrom!=''){
			$tmp=explode("-",$dfrom);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dfrom=$th."-".$bl."-".$hr;
        }
        
        if($dto!=''){
			$tmp=explode("-",$dto);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dto=$th."-".$bl."-".$hr;
		}
		$this->db->select("(DATE_PART('year', '$dto'::date) - DATE_PART('year', '$dfrom'::date)) * 12 +
                            (DATE_PART('month', '$dto'::date) - DATE_PART('month', '$dfrom'::date)) as inter ",false);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$tmp=$query->row();
            return $tmp->inter+1;
		}
    }
}

/* End of file Mmaster.php */
