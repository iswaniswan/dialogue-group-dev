<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function baca($dfrom,$dto){
      if($dfrom!=''){
          $tmp = explode("-", $dfrom);
          $hr = $tmp[0];
          $bl = $tmp[1];
          $th = $tmp[2];
          $thprev = $tmp[2]-1;
          $dfrom = $th."-".$bl."-".$hr;
          $dfromprev = $thprev."-".$bl."-".$hr;
      }
      if($dto){
          $tem = explode("-", $dto);
          $hri = $tem[0];
          $bln = $tem[1];
          $thn = $tem[2];
          $thnprev = $tem[2]-1;
          $dto = $thn."-".$bln."-".$hri;
          if((intval($thnprev)%4!=0)&&($bln=='02')&&($hri=='29')) $hri='28';
          $dtoprev = $thnprev."-".$bln."-".$hri;
      }
      $this->db->select(" a.iseri, a.seriname, sum(a.oa) as oa, sum(a.oaprev) as oaprev ,sum(slsqty) as slsqty, sum(slsqtyprev) as slsqtyprev, sum(netsls) as netsls, sum(netslsprev) as netslsprev
                         from(

                         /*--//Hitung SALES QTY*/
                         select a.iseri, a.seriname,0 as oa, 0 as oaprev , sum(a.vol) as slsqty, 0 as slsqtyprev, 0 as netsls, 0 as netslsprev 
                         from
                         (
                         select a.i_product_seri as iseri, b.e_product_seriname as seriname, sum(c.n_deliver) as vol
                         from tr_product a, tr_product_seri b, tm_nota_item c, tm_nota d
                         where b.i_product_seri=a.i_product_seri and c.i_product=a.i_product
                         and (d.d_nota >='$dfrom' and d.d_nota <='$dto') and d.f_nota_cancel='f'
                         and d.i_nota=c.i_nota and d.i_area=c.i_area and not d.i_nota isnull
                         group by a.i_product_seri, b.e_product_seriname
                         ) as a
                         group by a.iseri, a.seriname
                         
                         union all
                         
                         select a.iseri, a.seriname,0 as oa, 0 as oaprev , 0 as slsqty, sum(a.vol) as slsqtyprev , 0 as netsls, 0 as netslsprev 
                         from
                         (
                         select a.i_product_seri as iseri, b.e_product_seriname as seriname, sum(c.n_deliver) as vol
                         from tr_product a, tr_product_seri b, tm_nota_item c, tm_nota d
                         where b.i_product_seri=a.i_product_seri and c.i_product=a.i_product
                         and (d.d_nota >='$dfromprev' and d.d_nota <='$dtoprev') and d.f_nota_cancel='f'
                         and d.i_nota=c.i_nota and d.i_area=c.i_area and not d.i_nota isnull
                         group by a.i_product_seri, b.e_product_seriname
                         ) as a
                         group by a.iseri, a.seriname

                         /*--// End Hitung Sales Qty*/
                         
                         union all
                         /*--// Hitung Net Sales*/
                         select a.iseri, a.seriname,0 as oa, 0 as oaprev , 0 as slsqty, 0 as slsqtyprev, sum(a.vnota) as netsls, 0 as netslsprev 
                         from
                         (
                         select a.i_product_seri as iseri, b.e_product_seriname as seriname,
                         round(sum((c.n_deliver*c.v_unit_price-(((c.n_deliver*c.v_unit_price)/d.v_nota_gross)*d.v_nota_discounttotal)))) as vnota
                         from tr_product a, tr_product_seri b, tm_nota_item c, tm_nota d
                         where b.i_product_seri=a.i_product_seri and c.i_product=a.i_product 
                         and (d.d_nota >='$dfrom' and d.d_nota <='$dto') and d.f_nota_cancel='f'
                         and d.i_nota=c.i_nota and d.i_area=c.i_area and not d.i_nota isnull
                         group by a.i_product_seri, b.e_product_seriname
                         ) as a
                         group by a.iseri, a.seriname
                         
                         union all
                         
                         select a.iseri, a.seriname,0 as oa, 0 as oaprev , 0 as slsqty, 0 as slsqtyprev, 0 as netsls, sum(a.vnota) as netslsprev 
                         from
                         (
                         select a.i_product_seri as iseri, b.e_product_seriname as seriname,
                         round(sum((c.n_deliver*c.v_unit_price-(((c.n_deliver*c.v_unit_price)/d.v_nota_gross)*d.v_nota_discounttotal)))) as vnota
                         from tr_product a, tr_product_seri b, tm_nota_item c, tm_nota d
                         where b.i_product_seri=a.i_product_seri and c.i_product=a.i_product 
                         and (d.d_nota >='$dfromprev' and d.d_nota <='$dtoprev') and d.f_nota_cancel='f'
                         and d.i_nota=c.i_nota and d.i_area=c.i_area and not d.i_nota isnull
                         group by a.i_product_seri, b.e_product_seriname
                         ) as a
                         group by a.iseri, a.seriname
                         /*--// End Hitung Net sls*/
                         union all

                         select a.i_product_seri as iseri,a.e_product_seriname as seriname,count(a.oa) as oa, 0 as oaprev ,0 as slsqty, 0 as slsqtyprev, 0 as netsls, 0 as netslsprev from (
                           select distinct on (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer as oa, e.e_product_seriname, e.i_product_seri
                           from tm_nota a, tm_nota_item b, tr_product d, tr_product_seri e,tr_customer f 
                           where e.i_product_seri=d.i_product_seri and b.i_product=d.i_product 
                           and (a.d_nota >='$dfrom' and a.d_nota <='$dto') and a.f_nota_cancel='f'
                           and a.i_nota=b.i_nota and a.i_area=b.i_area and not a.i_nota isnull
                           and a.i_customer=f.i_customer and a.i_area=f.i_area
                           group by e.i_product_seri, e.e_product_seriname,a.i_customer, to_char(a.d_nota,'yyyymm')
                        ) as a
                        group by a.i_product_seri,a.e_product_seriname
                        union all
                        select a.i_product_seri as iseri,a.e_product_seriname as seriname,0 as oa, count(a.oa) as oaprev ,0 as slsqty, 0 as slsqtyprev, 0 as netsls, 0 as netslsprev from (
                           select distinct on (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer as oa, e.e_product_seriname, e.i_product_seri
                           from tm_nota a, tm_nota_item b, tr_product d, tr_product_seri e ,tr_customer f
                           where e.i_product_seri=d.i_product_seri and b.i_product=d.i_product 
                           and (a.d_nota >='$dfromprev' and a.d_nota <='$dtoprev') and a.f_nota_cancel='f'
                           and a.i_nota=b.i_nota and a.i_area=b.i_area and not a.i_nota isnull
                           and a.i_customer=f.i_customer and a.i_area=f.i_area
                           group by e.i_product_seri, e.e_product_seriname,a.i_customer, to_char(a.d_nota,'yyyymm')
                        ) as a
                        group by a.i_product_seri,a.e_product_seriname
                                     
            )as a
            group by a.iseri, a.seriname
            order by a.iseri",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }

    function bacaob($dfrom,$dto){ 
      $tmp=explode("-",$dfrom);
      $hr=$tmp[0];
      $bl=$tmp[1];
      $th=$tmp[2]-1;
      $thnow=$tmp[2];
      $thbl=$thnow."-".$bl;
      $dfromprev=$hr."-".$bl."-".$th;
      $tsasih = date('Y-m', strtotime('-24 month', strtotime($thbl))); //tambah tanggal sebanyak 6 bulan
      if($tsasih!=''){
      $smn = explode("-", $tsasih);
      $thn = $smn[0];
      $bln = $smn[1];
      }
      $taunsasih = $thn.$bln;
      $tmp=explode("-",$dto);
      $hr=$tmp[0];
      $bl=$tmp[1];
      $th=$tmp[2]-1;
      $thnya=$tmp[2];
      $thblto=$thnya.$bl;
      $dtoprev=$hr."-".$bl."-".$th;

      $this->db->select(" count(a.ob) as ob from (
                                  select distinct on (x.ob) x.ob as ob from(
                                  select a.i_customer as ob
                                  from tm_nota a , tr_area c
                                  where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
                                  and a.f_nota_cancel='false' 
                                  and not a.i_nota isnull
                                  and not a.i_spb isnull
                                  and a.i_area=c.i_area 
                                  and c.f_area_real='t' 
                                  and not a.i_nota isnull
                                  union all
                                  select b.i_customer as ob
                                  from tr_area c, tr_customer b 
                                  where b.i_customer_status<>'4' 
                                  and b.f_customer_aktif='true' 
                                  and b.i_area=c.i_area 
                                  and c.f_area_real='t'
                                  )
                                  as x
                                  order by x.ob
                                  ) as a",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
          return $query->result();
      }
    }
}
