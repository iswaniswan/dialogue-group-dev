<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getspmbfrom($cari,$dfrom,$dto,$iarea){
      if ($iarea == '') {
        $sql = '';
      }else{
        $sql = "and tm_spmb.i_area = '$iarea'";
      }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_spmb, i_area
            from dgu.tm_spmb
            where i_spmb like '%$cari%' 
            and d_spmb >= to_date('$dfrom','dd-mm-yyyy') and d_spmb <= to_date('$dto','dd-mm-yyyy')
            $sql
            order by i_spmb",
        FALSE);
    }

    public function getspmbto($cari,$dfrom,$dto,$iarea,$spmbfrom){
      if ($iarea == '') {
        $sql = '';
      }else{
        $sql = "and tm_spmb.i_area = '$iarea'";
      }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_spmb, i_area
            from dgu.tm_spmb
            where i_spmb like '%$cari%'
            and d_spmb >= to_date('$dfrom','dd-mm-yyyy') and d_spmb <= to_date('$dto','dd-mm-yyyy')
            and i_spmb >= '$spmbfrom'
            $sql
            order by i_spmb",
        FALSE);
    }
    function bacamaster($area,$spmbfrom,$spmbto)
    {
      if ($area == '') {
        $sql = '';
      }else{
        $sql = "and tm_spmb.i_area = '$area'";
      }
/*
    $this->db->select(" * from tm_spmb 
              inner join tr_area on (tm_spmb.i_area=tr_area.i_area)
              inner join tr_customer on (tm_spmb.i_customer=tr_customer.i_customer)
              inner join tr_salesman on (tm_spmb.i_salesman=tr_salesman.i_salesman)
              where tm_spmb.i_spmb >= '$spmbfrom' and tm_spmb.i_spmb <= '$spmbto' and tm_spmb.i_area = '$area' order by tm_spmb.i_spmb",false);
*/
    $this->db->select(" * from dgu.tm_spmb 
              inner join dgu.tr_area on (tm_spmb.i_area=tr_area.i_area)
              where tm_spmb.i_spmb >= '$spmbfrom' and tm_spmb.i_spmb <= '$spmbto' $sql order by tm_spmb.i_spmb",false);
    $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }
    function bacadetail($area,$spmb)
    {
      $this->db->select(" * from dgu.tm_spmb_item 
                inner join dgu.tr_product_motif on (tm_spmb_item.i_product_motif=tr_product_motif.i_product_motif
                and tm_spmb_item.i_product=tr_product_motif.i_product)
                where tm_spmb_item.i_spmb = '$spmb' order by tm_spmb_item.i_product asc",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }
    public function company($id_company){
        return $this->db->query("
            SELECT
                *
            FROM
                public.company
            WHERE
                id = '$id_company'
        ", FALSE);
    }

}

/* End of file Mmaster.php */
