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
            from dgu.tm_opfc 
            where i_op like '%$cari%' 
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
            from dgu.tm_opfc 
            where i_op like '%$cari%'
            and d_op >= to_date('$dfrom','dd-mm-yyyy') and d_op <= to_date('$dto','dd-mm-yyyy')
            and i_op >= '$opfrom'
            $sql
            order by i_op",
        FALSE);
    }
    function bacamaster($opfrom,$opto)
    {
    $this->db->select(" * 
      from dgu.tm_opfc 
      inner join dgu.tr_supplier on (tm_opfc.i_supplier=tr_supplier.i_supplier)
      inner join dgu.tr_op_status on (tm_opfc.i_op_status=tr_op_status.i_op_status)
      inner join dgu.tr_area on (tm_opfc.i_area=tr_area.i_area)
      where tm_opfc.i_op >= '$opfrom' and tm_opfc.i_op <= '$opto' order by tm_opfc.i_op",false);
    $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }
    function bacadetail($iop)
    {
      $reff='';
      $this->db->select(" i_reff from dgu.tm_opfc where tm_opfc.i_op = '$iop'",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $tes){
          $reff=$tes->i_reff;
        }
      }
      if(substr($reff,0,3)=='SPB'){
        $this->db->select("a.*, d.e_product_motifname from dgu.tm_opfc_item a, dgu.tm_opfc c, dgu.tr_product_motif d where a.i_op='$iop'
                           and a.i_op=c.i_op and a.i_product=d.i_product and a.i_product_motif=d.i_product_motif
                           order by a.n_item_no",false);
      }else{
        $this->db->select("a.*, d.e_product_motifname from dgu.tm_opfc_item a, dgu.tm_opfc c, dgu.tr_product_motif d where a.i_op='$iop'
                           and a.i_op=c.i_op and a.i_product=d.i_product and a.i_product_motif=d.i_product_motif
                           order by a.n_item_no",false);
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
