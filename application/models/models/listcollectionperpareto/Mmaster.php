<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

function bacaperiode($dfrom,$dto,$interval)
    {
      $perfrom=substr($dfrom,6,4).substr($dfrom,3,2);
      $perto  =substr($dto,6,4).substr($dto,3,2);
      $sql =" a.i_customer, a.e_customer_name, a.bln, sum(a.total) as total, sum(a.realisasi) as realisasi from( 
              select a.i_customer, a.e_customer_name, substring(b.e_periode, 5, 2) as bln, sum(b.v_target_tagihan) as total, 
              sum(b.v_realisasi_tagihan) as realisasi 
              from tm_collection c, tm_collection_item b, tr_customer a
              where a.i_customer=b.i_customer and a.f_pareto='t' and b.e_periode=c.e_periode
              and b.e_periode>='$perfrom' and b.e_periode<='$perto' 
              group by a.i_customer, a.e_customer_name, substring(b.e_periode, 5, 2) ) as a 
              group by a.i_customer, a.e_customer_name, a.bln 
              order by a.i_customer, a.e_customer_name, a.bln";

          $this->db->select($sql,false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              return $query->result();
          }
    }

    function sumperiode($dfrom,$dto,$interval)
    {
      $perfrom=substr($dfrom,6,4).substr($dfrom,3,2);
      $perto  =substr($dto,6,4).substr($dto,3,2);
      $sql =" a.bln, sum(a.total) as total from( 
              select a.i_customer, a.e_customer_name, substring(b.e_periode, 5, 2) as bln, sum(b.v_target_tagihan) as total, 
              sum(b.v_realisasi_tagihan) as realisasi 
              from tm_collection c, tm_collection_item b, tr_customer a
              where a.i_customer=b.i_customer and a.f_pareto='t' and b.e_periode=c.e_periode
              and b.e_periode>='$perfrom' and b.e_periode<='$perto' 
              group by a.i_customer, a.e_customer_name, substring(b.e_periode, 5, 2)) as a 
              group by a.bln ";

          $this->db->select($sql,false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              return $query->result();
          }
    }
    function interval($dfrom,$dto)
    {
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
