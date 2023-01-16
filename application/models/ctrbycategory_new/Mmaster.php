<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function baca($dfrom,$dto)
  {
    $pecah1       = explode('-', $dfrom);
      $tgl1       = $pecah1[0];
      $bln1       = $pecah1[1];
      $tahun1     = $pecah1[2];
      $tahunprev1 = intval($tahun1) - 1;

    $pecah2       = explode('-', $dto);
      $tgl2       = $pecah2[0];
      $bln2       = $pecah2[1];
      $tahun2     = $pecah2[2];
      $tahunprev2 = intval($tahun2) - 1;

      if((intval($tahunprev2)%4!=0)&&($bln2=='02')&&($tgl2=='29')) $tgl2='28';

    $gabung1 = $tgl1.'-'.$bln1.'-'.$tahunprev1;
    $gabung2 = $tgl2.'-'.$bln2.'-'.$tahunprev2;

                            $this->db->select(" sum(a.oa) as oa, sum(a.oaprev) as oaprev, sum(a.ob) as ob, sum(a.vnota) as vnota, sum(a.vnotaprev) as vnotaprev, sum(a.qty) as qty, sum(a.qtyprev) as qtyprev, a.e_product_classname from(
                                           select sum(z.oa) as oa, 0 as oaprev, sum(z.ob) as ob, sum(z.vnota) as vnota, 0 as vnotaprev, sum(z.qty) as qty, 0 as qtyprev, z.e_product_classname from(
                                           select 0 as oa, 0 as ob, sum(a.vnota) as vnota, sum(a.qty) as qty, a.e_product_classname from (
                                           select c.i_product_class, c.e_product_classname, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as vnota, sum(a.n_deliver) as qty
                                           from tm_nota_item a, tm_nota b, tr_product_class c, tr_product d
                                           where b.f_nota_cancel = 'f'
                                           and a.i_nota=b.i_nota and a.i_area=b.i_area
                                           and a.i_product = d.i_product
                                           and d.i_product_class=c.i_product_class
                                           and not b.i_nota isnull
                                           and b.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') and b.d_nota <= to_date('$dto', 'dd-mm-yyyy')
                                           group by c.i_product_class, c.e_product_classname
                                           ) as a
                                           group by a.e_product_classname
                                           
                                           union all

                                           select count(a.oa) as oa, 0 as ob, 0 as vnota, 0 as qty, a.e_product_classname from (
                                           select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer as oa, e.e_product_classname, e.i_product_class
                                           from tm_nota a, tm_nota_item b, tr_customer c, tr_product d, tr_product_class e, tr_customer f
                                           where (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                                           and a.f_nota_cancel='false'
                                           and a.i_nota = b.i_nota and a.i_area = b.i_area
                                           and a.i_customer = c.i_customer
                                           and b.i_product = d.i_product
                                           and d.i_product_class=e.i_product_class
                                           and not a.i_nota isnull
                                           and a.i_customer=f.i_customer and a.i_area=f.i_area
                                           group by e.i_product_class, e.e_product_classname, a.i_customer, to_char(a.d_nota, 'yyyymm')
                                           ) as a
                                           group by a.e_product_classname
                                           ) as z
                                           group by z.e_product_classname
                                           
                                           union all

                                           /*------------------------------------------- batas tahun lalu -----------------------------------------*/
                                           select 0 as oa, sum(z.oaprev) as oaprev, 0 as ob, 0 as vnota, sum(z.vnotaprev) as vnotaprev, 0 as qty, sum(z.qtyprev) as qtyprev, z.e_product_classname from(
                                           select 0 as oaprev, sum(a.vnota) as vnotaprev, sum(a.qty) as qtyprev, a.e_product_classname from (
                                           select c.i_product_class, c.e_product_classname, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as vnota, sum(a.n_deliver) as qty
                                           from tm_nota_item a, tm_nota b, tr_product_class c, tr_product d
                                           where b.f_nota_cancel = 'f'
                                           and a.i_nota=b.i_nota and a.i_area=b.i_area
                                           and a.i_product = d.i_product
                                           and d.i_product_class=c.i_product_class
                                           and not b.i_nota isnull
                                           and b.d_nota >= to_date('$gabung1', 'dd-mm-yyyy') and b.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')
                                           group by c.i_product_class, c.e_product_classname
                                           ) as a
                                           group by a.e_product_classname
                                           
                                           union all

                                           select count(a.oa) as oaprev, 0 as vnotaprev, 0 as qtyprev, a.e_product_classname from (
                                           select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer as oa, e.e_product_classname, e.i_product_class
                                           from tm_nota a, tm_nota_item b, tr_customer c, tr_product d, tr_product_class e, tr_customer f
                                           where (a.d_nota >= to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy'))
                                           and a.f_nota_cancel='false'
                                           and a.i_nota = b.i_nota and a.i_area = b.i_area
                                           and a.i_customer = c.i_customer
                                           and b.i_product = d.i_product
                                           and d.i_product_class=e.i_product_class
                                           and not a.i_nota isnull
                                           and a.i_customer=f.i_customer and a.i_area=f.i_area
                                           group by e.i_product_class, e.e_product_classname, a.i_customer, to_char(a.d_nota, 'yyyymm')
                                           ) as a
                                           group by a.e_product_classname

                                           ) as z
                                           group by z.e_product_classname
                                           ) as a 
                                           group by a.e_product_classname
                                           order by a.e_product_classname ",false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      return $query->result();
    }
  }

  function bacaob($dfrom,$dto)
  { 
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