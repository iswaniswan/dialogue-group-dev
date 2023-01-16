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

    public function getsjfrom($cari, $dfrom, $dto, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_sj, i_area
            from tm_nota
            where i_area='$iarea ' and (n_print=0 or n_print isnull)
            and d_sj >= to_date('$dfrom','dd-mm-yyyy') and d_sj <= to_date('$dto','dd-mm-yyyy')
            AND i_sj LIKE '%$cari%'
            order by i_sj",
        FALSE);
    }

    public function getsjto($cari, $dfrom, $dto, $iarea, $sjfrom){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT i_sj, i_area
            from tm_nota
            where i_area='$iarea ' and (n_print=0 or n_print isnull)
            and d_sj >= to_date('$dfrom','dd-mm-yyyy') and d_sj <= to_date('$dto','dd-mm-yyyy')
            AND i_sj LIKE '%$cari%'
            and i_sj >= '$sjfrom'
            order by i_sj",
        FALSE);
    }
    function bacamaster($sjfrom,$sjto)
    {
      $this->db->select(" 
tm_nota.i_sj, tm_nota.i_area, tm_nota.i_customer,tm_nota.d_sj,
tm_nota.i_spb,
tm_nota.n_nota_discount1,
tm_nota.n_nota_discount2,
tm_nota.n_nota_discount3,
tm_nota.n_nota_discount4,
tm_nota.v_nota_discounttotal,
c.e_customer_name, c.e_customer_address, c.e_customer_city, c.f_customer_plusppn as f_plus_ppn, c.e_customer_phone, 
tr_area.e_area_name, tm_spb.i_spb_po, tm_spb.f_spb_consigment, tr_area.e_area_phone, 
d.e_customer_ownername, e.e_customer_pkpname, 
c.f_customer_pkp 
from dgu.tm_nota
inner join dgu.tr_customer c on (tm_nota.i_customer=c.i_customer) 
inner join dgu.tr_customer_owner d on (tm_nota.i_customer=d.i_customer) 
inner join dgu.tr_customer_pkp e on (tm_nota.i_customer=e.i_customer) 
inner join dgu.tr_area on (substring(tm_nota.i_sj, 9, 2)=tr_area.i_area) 
left join dgu.tm_spb on (tm_spb.i_spb=tm_nota.i_spb and tm_spb.i_area=tm_nota.i_area)
where tm_nota.i_sj >= '$sjfrom' 
and tm_nota.i_sj <= '$sjto' 
order by tm_nota.i_sj",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }
    function bacadetail($sj,$area)
    {
      $cust='';
      $tes=$this->db->query("select i_customer from dgu.tm_nota where i_sj = '$sj' and substring(i_sj,9,2)='$area'",false);
      if ($tes->num_rows() > 0){
        foreach($tes->result() as $xx){
          $cust=$xx->i_customer;
        }
      }
      $group='';
      $que  = $this->db->query(" select i_customer_plugroup from dgu.tr_customer_plugroup where i_customer='$cust'",false);
      if($que->num_rows()>0){
        foreach($que->result() as $hmm){
          $group=$hmm->i_customer_plugroup;
        }
      }
      if($group==''){
        $this->db->select(" i_sj, i_nota, tm_nota_item.i_product, i_product_grade, tm_nota_item.i_product_motif, n_deliver, v_unit_price,
                            e_product_name, i_area, d_nota, n_item_no from dgu.tm_nota_item 
                            inner join dgu.tr_product_motif on (tm_nota_item.i_product_motif=tr_product_motif.i_product_motif
                            and tm_nota_item.i_product=tr_product_motif.i_product)
                            where tm_nota_item.i_sj = '$sj'
                            order by tm_nota_item.n_item_no",false);
  #and substring(tm_nota_item.i_sj,9,2)='$area'
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          return $query->result();
        }
      }else{
        $this->db->select(" a.i_sj, a.i_nota, a.i_product as product, a.i_product_grade, a.i_product_motif, a.n_deliver, a.v_unit_price,
                            a.e_product_name, a.i_area, a.d_nota, a.n_item_no, c.i_customer_plu, c.i_product from dgu.tm_nota_item a
                            inner join dgu.tr_product_motif b on (a.i_product_motif=b.i_product_motif and a.i_product=b.i_product)
                            left join dgu.tr_customer_plu c on (c.i_customer_plugroup='$group' and a.i_product=c.i_product) 
                            where i_sj = '$sj' order by n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          return $query->result();
        }
      }
    }
    function updatesj($isj, $area)
    {
      $query  = $this->db->query("SELECT current_timestamp as c");
      $row    = $query->row();
      $dprint = $row->c;
      $this->db->set(
          array(
        'd_sj_print'      => $dprint
          )
        );
      $this->db->where('i_sj', $isj);
      $this->db->where('i_area', $area);
      $this->db->update('dgu.tm_nota'); 
    }

}

/* End of file Mmaster.php */
