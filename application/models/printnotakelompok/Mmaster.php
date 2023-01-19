<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getarea($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                tr_area.i_area,
                tr_area.e_area_name
            FROM
                dgu.tr_area
            WHERE
                tr_area.i_area LIKE '%$cari%'
                OR tr_area.e_area_name LIKE '%$cari%'
            ORDER BY
                tr_area.i_area", 
        FALSE);
    }

    public function getnotafrom($cari, $dfrom, $dto, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_nota, i_area 
            from dgu.tm_nota 
            where i_nota like '%$cari%' and f_nota_cancel='f'
            and i_area='$iarea' 
            and d_nota >= to_date('$dfrom','dd-mm-yyyy') 
            AND d_nota <= to_date('$dto','dd-mm-yyyy') 
            and n_print=0
            order by i_nota",
        FALSE);
    }

    public function getnotato($cari, $dfrom, $dto, $iarea, $notafrom){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_nota, i_area 
            from dgu.tm_nota 
            where i_nota like '%$cari%' and f_nota_cancel='f'
            and i_area='$iarea' 
            and d_nota >= to_date('$dfrom','dd-mm-yyyy') 
            AND d_nota <= to_date('$dto','dd-mm-yyyy') 
            and n_print=0
            and i_nota >= '$notafrom'
            order by i_nota",
        FALSE);
    }
    function bacamaster($area,$notafrom,$notato)
    {
      $area1    = $this->session->userdata('i_area');
      if($area1=='00'){
        $this->db->select("   * from dgu.tm_nota 
          inner join dgu.tm_spb on (tm_nota.i_spb=tm_spb.i_spb and dgu.tm_nota.i_area=tm_spb.i_area)
          inner join dgu.tr_customer on (tm_nota.i_customer=tr_customer.i_customer)
          inner join dgu.tr_customer_owner on (tm_nota.i_customer=tr_customer_owner.i_customer)
          inner join dgu.tr_salesman on (tm_nota.i_salesman=tr_salesman.i_salesman)
          left join dgu.tr_customer_pkp on (tm_nota.i_customer=tr_customer_pkp.i_customer)
          left join dgu.tr_customer_va on (tm_nota.i_customer=tr_customer_va.i_customer)
          where dgu.tm_nota.i_nota >= '$notafrom' 
          and dgu.tm_nota.i_nota <= '$notato' 
          and dgu.tm_nota.i_area = '$area'
          and (dgu.tm_nota.n_print=0 or dgu.tm_nota.n_print isnull)
          order by tm_nota.i_nota",false);
      }else{
        $this->db->select("  * from dgu.tm_nota 
          inner join dgu.tm_spb on (tm_nota.i_spb=tm_spb.i_spb and tm_nota.i_area=tm_spb.i_area)
          inner join dgu.tr_customer on (tm_nota.i_customer=tr_customer.i_customer)
          inner join dgu.tr_customer_owner on (tm_nota.i_customer=tr_customer_owner.i_customer)
          inner join dgu.tr_salesman on (tm_nota.i_salesman=tr_salesman.i_salesman)
          left join dgu.tr_customer_pkp on (tm_nota.i_customer=tr_customer_pkp.i_customer)
          left join dgu.tr_customer_va on (tm_nota.i_customer=tr_customer_va.i_customer)
          where tm_nota.i_nota >= '$notafrom' 
          and tm_nota.i_nota <= '$notato' 
          and tm_nota.i_area = '$area'
          and (tm_nota.n_print=0 or tm_nota.n_print isnull)
          order by tm_nota.i_nota",false);
      }
#and a.f_spb_stockdaerah='t'
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }
    function bacadetail($nota)
    {
      $this->db->select(" * from dgu.tm_nota_item 
          inner join dgu.tr_product_motif on (tm_nota_item.i_product_motif=tr_product_motif.i_product_motif
          and tm_nota_item.i_product=tr_product_motif.i_product)
          where tm_nota_item.i_nota = '$nota' order by tm_nota_item.n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
           return $query->result();
        }
    }
    function updatenota($inota)
    {
      $query  = $this->db->query("SELECT current_timestamp as c");
      $row    = $query->row();
      $dprint = $row->c;
      $this->db->query("update dgu.tm_nota set d_nota_print='$dprint', n_print=n_print+1 where i_nota='$inota'");
/*
      $this->db->set(
          array(
        'd_nota_print'      => $dprint
          )
        );
      $this->db->where('i_nota', $inota);
      $this->db->update('tm_nota'); 
*/
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
