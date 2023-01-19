<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function bacaperiode($dfrom,$dto,$interval)
    {
      $perfrom=substr($dfrom,6,4).substr($dfrom,3,2);
      $perto  =substr($dto,6,4).substr($dto,3,2);
      $sql =" a.i_area, a.e_area_name, a.i_salesman, a.e_salesman_name, a.bln, sum(a.totalcash) as totalcash, 
              sum(a.realisasicash) as realisasicash, sum(a.totalcredit) as totalcredit, sum(a.realisasicredit) as realisasicredit from( 
              select a.i_area, c.e_area_name, a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2) as bln, 
              sum(b.bayar+b.sisa) as totalcash, sum(b.bayar) as realisasicash, 0 as totalcredit, 0 as realisasicredit
              from tm_collection_cash b, tr_salesman a, tr_area c
              where a.i_salesman=b.i_salesman and a.i_area=c.i_area and b.e_periode>='$perfrom' and b.e_periode<='$perto'
              group by a.i_area, c.e_area_name, a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2) 
              union all 
              select a.i_area, c.e_area_name, a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2) as bln, 0 as totalcash, 
              0 as realisasicash, sum(b.sisa+b.bayar) as totalcredit, sum(b.bayar) as realisasicredit 
              from tm_collection_credit b, tr_salesman a, tr_area c
              where a.i_salesman=b.i_salesman and a.i_area=c.i_area and b.e_periode>='$perfrom' and b.e_periode<='$perto' 
              group by a.i_area, c.e_area_name, a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2)
              ) as a 
              group by a.i_area, a.e_area_name, a.i_salesman, a.e_salesman_name, a.bln 
              order by a.i_area, a.i_salesman, a.bln";
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
      $sql =" a.bln, sum(a.totalcash) as totalcash, sum(a.totalcredit) as totalcredit from( 
              select a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2) as bln, sum(b.bayar+b.sisa) as totalcash, 
              sum(b.bayar) as realisasicash, 0 as totalcredit, 0 as realisasicredit
              from tm_collection_cash b, tr_salesman a, tr_area c 
              where a.i_salesman=b.i_salesman and a.i_area=c.i_area and b.e_periode>='$perfrom' and b.e_periode<='$perto'
              group by a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2) 
              union all 
              select a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2) as bln, 0 as totalcash, 0 as realisasicash, 
              sum(b.sisa+b.bayar) as totalcredit, sum(b.bayar) as realisasicredit 
              from tm_collection_credit b, tr_salesman a, tr_area c 
              where a.i_salesman=b.i_salesman and a.i_area=c.i_area and b.e_periode>='$perfrom' and b.e_periode<='$perto' 
              group by a.i_salesman, a.e_salesman_name, substring(b.e_periode, 5, 2)
              ) as a 
              group by a.i_salesman, a.e_salesman_name, a.bln ";
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
