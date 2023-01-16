<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getopfrom($cari,$dfrom,$dto,$iarea){
      if ($iarea == '') {
        $sql = '';
      }else{
        $sql = "and tm_op.i_area = '$iarea'";
      }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_op, i_area 
            from dgu.tm_op 
            where n_print < 1
            and i_op like '%$cari%'
            $sql
            and d_op >= to_date('$dfrom','dd-mm-yyyy') and d_op <= to_date('$dto','dd-mm-yyyy')
            $sql
            order by i_op",
        FALSE);
    }

    public function getopto($cari,$dfrom,$dto,$iarea,$opfrom){
      if ($iarea == '') {
        $sql = '';
      }else{
        $sql = "and tm_op.i_area = '$iarea'";
      }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_op, i_area 
            from dgu.tm_op 
            where n_print < 1
            and i_op like '%$cari%'
            and d_op >= to_date('$dfrom','dd-mm-yyyy') and d_op <= to_date('$dto','dd-mm-yyyy')
            and i_op >= '$opfrom'
            $sql
            order by i_op",
        FALSE);
    }
    function bacamaster($opfrom,$opto)
    {
/*
    $this->db->select(" * from tm_spmb 
              inner join tr_area on (tm_spmb.i_area=tr_area.i_area)
              inner join tr_customer on (tm_spmb.i_customer=tr_customer.i_customer)
              inner join tr_salesman on (tm_spmb.i_salesman=tr_salesman.i_salesman)
              where tm_spmb.i_spmb >= '$opfrom' and tm_spmb.i_spmb <= '$spmbto' and tm_spmb.i_area = '$area' order by tm_spmb.i_spmb",false);
*/
    $this->db->select(" * from dgu.tm_op 
              inner join dgu.tr_supplier on (tm_op.i_supplier=tr_supplier.i_supplier)
              inner join dgu.tr_op_status on (tm_op.i_op_status=tr_op_status.i_op_status)
              inner join dgu.tr_area on (tm_op.i_area=tr_area.i_area)
              where tm_op.i_op >= '$opfrom' and tm_op.i_op <= '$opto' and tm_op.n_print<1 order by tm_op.i_op",false);
    $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }
    function bacadetail($iop)
    {$reff='';
      $this->db->select(" i_reff from dgu.tm_op where tm_op.i_op = '$iop'",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $tes){
          $reff=$tes->i_reff;
        }
      }
      if(substr($reff,0,3)=='SPB'){
        $this->db->select("a.*, b.e_remark, d.e_product_motifname from dgu.tm_op_item a, dgu.tm_spb_item b, dgu.tm_op c, dgu.tr_product_motif d where a.i_op='$iop'
                           and a.i_op=c.i_op and a.i_product=d.i_product and a.i_product_motif=d.i_product_motif
                           and a.i_op=b.i_op and c.i_reff=b.i_spb and c.i_area=b.i_area 
                           and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                           and a.i_product_grade=b.i_product_grade order by a.i_product asc",false);
      }else{
        $this->db->select("a.*, b.e_remark, d.e_product_motifname from dgu.tm_op_item a, dgu.tm_spmb_item b, dgu.tm_op c, dgu.tr_product_motif d where a.i_op='$iop'
                           and a.i_op=c.i_op and a.i_product=d.i_product and a.i_product_motif=d.i_product_motif
                           and a.i_op=b.i_op and c.i_reff=b.i_spmb and c.i_area=b.i_area 
                           and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                           and a.i_product_grade=b.i_product_grade order by a.i_product asc",false);
      }
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
