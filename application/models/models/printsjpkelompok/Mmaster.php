<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getsjpfrom($cari,$dfrom,$dto,$iarea){
      if ($iarea == '') {
        $sql = '';
      }else{
        $sql = "and tm_sjp.i_area = '$iarea'";
      }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_sjp, i_area 
            from dgu.tm_sjp 
            where i_sjp like '%$cari%' 
            and d_sjp >= to_date('$dfrom','dd-mm-yyyy') and d_sjp <= to_date('$dto','dd-mm-yyyy')
            $sql
            order by i_sjp",
        FALSE);
    }

    public function getsjpto($cari,$dfrom,$dto,$iarea,$sjpfrom){
      if ($iarea == '') {
        $sql = '';
      }else{
        $sql = "and tm_sjp.i_area = '$iarea'";
      }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_sjp, i_area 
            from dgu.tm_sjp 
            where i_sjp like '%$cari%'
            and d_sjp >= to_date('$dfrom','dd-mm-yyyy') and d_sjp <= to_date('$dto','dd-mm-yyyy')
            and i_sjp >= '$sjpfrom'
            $sql
            order by i_sjp",
        FALSE);
    }
    function bacamaster($sjpfrom,$sjpto,$area)
    {
      if ($area == '') {
        $sql = '';
      }else{
        $sql = "and tr_area.i_area ='$area' ";
      }
      $this->db->select(" tm_sjp.*, tr_area.e_area_name, tm_spmb.i_spmb  from dgu.tm_sjp
                          inner join dgu.tr_area on (tm_sjp.i_area=tr_area.i_area)
                          left join dgu.tm_spmb on (tm_spmb.i_spmb=tm_sjp.i_spmb and tm_spmb.i_area=tm_sjp.i_area)
                          where tm_sjp.i_sjp >= '$sjpfrom' and tm_sjp.i_sjp <= '$sjpto'
                          $sql
                          order by tm_sjp.i_sjp",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }
    function bacadetail($sj,$area)
    {
      if ($area == '') {
        $sql = '';
      }else{
        $sql = "and tm_sjp_item.i_area ='$area' ";
      }
      $this->db->select(" * from dgu.tm_sjp_item 
                          inner join dgu.tr_product_motif on (tm_sjp_item.i_product_motif=tr_product_motif.i_product_motif
                          and tm_sjp_item.i_product=tr_product_motif.i_product)
                          where tm_sjp_item.i_sjp = '$sj' 
                          $sql
                          order by tm_sjp_item.i_sjp",false);
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
