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

    public function getspb($cari, $dfrom, $dto, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT a.i_spb, a.i_area, a.i_customer, b.e_customer_name 
            from tm_spb a, tr_customer b
            where a.i_customer=b.i_customer
            and a.i_area='$iarea ' and (a.n_print=0 or a.n_print isnull)
            and a.d_spb >= to_date('$dfrom','dd-mm-yyyy') and a.d_spb <= to_date('$dto','dd-mm-yyyy')
            AND (a.i_spb LIKE '%$cari%'
            OR b.e_customer_name LIKE '%$cari%')
            order by a.i_spb",
        FALSE);
    }

    public function getspbto($cari, $dfrom, $dto, $iarea, $spbfrom){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT a.i_spb, a.i_area, a.i_customer, b.e_customer_name 
            from tm_spb a, tr_customer b
            where a.i_customer=b.i_customer
            and a.i_area='$iarea ' and (a.n_print=0 or a.n_print isnull)
            and a.d_spb >= to_date('$dfrom','dd-mm-yyyy') and a.d_spb <= to_date('$dto','dd-mm-yyyy')
            AND (a.i_spb LIKE '%$cari%' OR b.e_customer_name LIKE '%$cari%')
            and a.i_spb >= '$spbfrom'
            order by a.i_spb",
        FALSE);
    }
    function bacamaster($area,$spbfrom,$spbto)
    {
      $area1    = $this->session->userdata('i_area');
      if($area1=='00'){
        $this->db->select("   a.i_spb,a.i_area,a.i_customer,a.i_salesman,a.i_price_group,a.v_spb,a.n_spb_discount1,a.n_spb_discount2,a.n_spb_discount3,a.n_spb_discount4,a.n_spb_toplength,a.d_spb,a.v_spb_discounttotal,a.e_remark1,a.e_approve1,a.e_approve2, b.e_customer_name,b.e_customer_address,b.e_customer_city, b.f_customer_pkp, b.d_signin,c.e_salesman_name, d.e_customer_classname,f.v_flapond,f.n_ratatelat, g.e_customer_ownername, h.e_customer_pkpname, h.e_customer_pkpnpwp
                       from dgu.tm_spb a, 
                       dgu.tr_customer b, 
                       dgu.tr_salesman c, 
                       dgu.tr_customer_class d, 
                       dgu.tr_price_group e, 
                       dgu.tr_customer_groupar f, 
                       dgu.tr_customer_owner g
                       , dgu.tr_customer_pkp h
                       where a.i_spb >= '$spbfrom' and a.i_spb <= '$spbto' and a.i_area = '$area'
                       and a.i_customer=b.i_customer and (a.n_print=0 or a.n_print isnull)
                       and a.i_salesman=c.i_salesman and a.i_customer=f.i_customer
                       and a.i_customer=g.i_customer
                       and a.i_customer=h.i_customer
                       and (e.n_line=b.i_price_group or e.i_price_group=b.i_price_group)
                       and b.i_customer_class=d.i_customer_class 
                       and (((a.f_spb_stockdaerah='t' and not a.i_approve1 isnull) or b.i_customer_status='4' or a.f_spb_stockdaerah='f'))
                       order by a.i_spb",false);
      }else{
        $this->db->select("  a.i_spb,a.i_area,a.i_customer,a.i_salesman,a.i_price_group,a.v_spb,a.n_spb_discount1,a.n_spb_discount2,a.n_spb_discount3,a.n_spb_discount4,a.n_spb_toplength,a.d_spb,a.v_spb_discounttotal,a.e_remark1,a.e_approve1,a.e_approve2, b.e_customer_name,b.e_customer_address,b.e_customer_city, b.f_customer_pkp, b.d_signin,c.e_salesman_name, d.e_customer_classname,f.v_flapond,f.n_ratatelat, g.e_customer_ownername, h.e_customer_pkpnpwp
                       , h.e_customer_pkpname
                       from dgu.tm_spb a, dgu.tr_customer b, dgu.tr_salesman c, dgu.tr_customer_class d, dgu.tr_price_group e, dgu.tr_customer_groupar f, dgu.tr_customer_owner g
                       , dgu.tr_customer_pkp h
                       where a.i_spb >= '$spbfrom' and a.i_spb <= '$spbto' and a.i_area = '$area'
                       and a.i_customer=b.i_customer and (a.n_print=0 or a.n_print isnull)
                       and a.i_salesman=c.i_salesman and a.i_customer=f.i_customer
                       and a.i_customer=g.i_customer
                       and a.i_customer=h.i_customer
                       and (e.n_line=b.i_price_group or e.i_price_group=b.i_price_group)
                       and b.i_customer_class=d.i_customer_class order by a.i_spb",false);
      }
#and a.f_spb_stockdaerah='t'
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }
    function bacadetail($area,$spb)
    {
      $this->db->select(" a.i_spb,
                          a.i_product,
                          a.i_product_grade,
                          a.i_product_motif,
                          a.n_order,
                          a.n_deliver,
                          a.n_stock,
                          a.v_unit_price,
                          substr(a.e_product_name,1,46) as e_product_name,
                          a.i_op,
                          a.i_area,
                          a.e_remark,
                          a.n_item_no,
                          tr_product.i_product_status from dgu.tm_spb_item a
                         inner join dgu.tr_product on (a.i_product=tr_product.i_product)
                         inner join dgu.tr_product_motif on (a.i_product_motif=tr_product_motif.i_product_motif
                         and a.i_product=tr_product_motif.i_product)
                         where a.i_spb = '$spb' and a.i_area='$area' order by a.n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
           return $query->result();
        }
    }
    function bacapiutang($ispb,$area)
    {
      $this->db->select(" i_customer from dgu.tm_spb where i_spb = '$ispb' and i_area='$area'",false);
      $quer = $this->db->get();
      $cust='';      
      $saldo=0;
      if ($quer->num_rows() > 0){
        foreach($quer->result() as $rowi){
          $cust=$rowi->i_customer;
        }
        $this->db->select(" sum(v_sisa) as sisa from dgu.tm_nota where i_customer = '$cust' and f_nota_cancel='f' and not i_nota isnull",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $saldo=$row->sisa;
          }
        }
      }
      return $saldo;
    }
    function bacanotapiutang($ispb,$area)
    {
      $this->db->select(" i_customer from dgu.tm_spb where i_spb = '$ispb' and i_area='$area'",false);
      $quer = $this->db->get();
      $cust='';      
      $nota=0;
      if ($quer->num_rows() > 0){
        foreach($quer->result() as $rowi){
          $cust=$rowi->i_customer;
        }
        $this->db->select(" i_nota from dgu.tm_nota where i_customer = '$cust' and f_nota_cancel='f' and not i_nota isnull",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $nota=$row->i_nota;
          }
        }
      }
      return $nota;
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
