<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($dfrom,$dto,$group){ 
        $tmp=explode("-",$dfrom);
        $hr=$tmp[0];
        $bl=$tmp[1];
        $th=$tmp[2]-1;
        $thnow=$tmp[2];
        $thbl=$thnow."-".$bl;
        $dfromprev=$hr."-".$bl."-".$th;
        $tsasih = date('Y-m', strtotime('-24 month', strtotime($thbl)));
        if($tsasih!=''){
            $smn = explode("-", $tsasih);
            $thn = $smn[0];
            $bln = $smn[1];
        }
        $taunsasih = $thn.$bln;

        $tmp=explode("-",$dto);
        $hri=$tmp[0];
        $bln=$tmp[1];
        $th=$tmp[2]-1;
        $thn = $tmp[2];
        $thblto = $thn.$bln;
        if((intval($th)%4!=0)&&($bln=='02')&&($hri=='29')) $hri='28';
        $dtoprev=$hri."-".$bln."-".$th;

        $tsasih = date('Y-m-d', strtotime('-24 month', strtotime($dto)));
        $dtos = date('Y-m-d',strtotime($dto));

        if($group=='NA'){
            $sql=" a.i_periode, sum(ob) as ob, sum(oa) as oa, sum(qty) as qty, sum(vnota) as vnota, sum(oaprev)as oaprev, sum(qtyprev) as qtyprev, sum(vnotaprev) as vnotaprev from(
            /* Hitung OB */
            select a.i_periode, count(ob) as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev from (
            select distinct on(a.ob)  a.ob as ob, to_char(a.d_nota, 'mm') as i_periode from (
            select a.i_customer as ob, a.i_area , a.d_nota
            from tm_nota a , tr_area c
            where 
            to_char(a.d_nota, 'yyyymm')>='$taunsasih' and to_char(a.d_nota, 'yyyymm') <='$thblto' 
            /*/*--a.d_nota >= '$tsasih' and a.d_nota <= '$dtos' --(a.d_nota>=to_date('$taunsasih', 'dd-mm-yyyy') and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) */*/
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull

            union all

            select b.i_customer as ob, b.i_area, NULL as d_nota
            from tr_customer b, tr_area c
            where b.i_customer_status<>'4' and b.f_customer_aktif='true' and b.i_area=c.i_area and c.f_area_real='t'
            and b.i_customer not in(
            select a.i_customer
            from tm_nota a , tr_area c
            where to_char(a.d_nota, 'yyyymm')>='$taunsasih' and to_char(a.d_nota, 'yyyymm') <='$thblto' 
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull
            )
            ) as a 
            )as a
            group by a.i_periode

            union all

            /* Hitung OA */
            select a.i_periode, 0 as ob, count(oa) as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer as oa, to_char(a.d_nota, 'mm') as i_periode
            from tm_nota a, tr_customer b, tm_spb z where 
            (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='false'
            and not a.i_nota isnull 
            and a.i_customer=b.i_customer 
            and a.i_area=b.i_area 
            and a.i_nota = z.i_nota
            and a.i_spb = z.i_spb
            and a.i_area = z.i_area
            and a.i_customer = z.i_customer
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'f'
            ) as a
            group by a.i_periode

            union all

            /*/*--Hitung Qty */*/
            SELECT to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, sum(b.n_deliver) as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area 
            and f.i_area=a.i_area 
            and f.i_area=b.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            group by to_char(a.d_nota, 'mm')
            union all
            /*--Hitung Nota*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, sum(a.v_nota_netto) as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and f.i_area=a.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            group by to_char(a.d_nota, 'mm')
            union all
            /*--Hitung OA Prevth*/
            select a.i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, count(oa) as oaprev, 0 as qtyprev, 0 as vnotaprev from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer as oa, to_char(a.d_nota, 'mm') as i_periode
            from tm_nota a, tr_customer b, tm_spb z where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <=to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='false' 
            and a.i_customer=b.i_customer 
            and a.i_area=b.i_area 
            and not a.i_nota isnull
            and a.i_nota = z.i_nota
            and a.i_spb = z.i_spb
            and a.i_area = z.i_area
            and a.i_customer = z.i_customer
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'f'
            ) as a
            group by a.i_periode
            union all
            /*--Hitung Qty Prevth*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, sum(b.n_deliver) as qtyprev, 0 as vnotaprev
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z
            where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area 
            and f.i_area=a.i_area 
            and f.i_area=b.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            group by to_char(a.d_nota, 'mm')
            union all
            /*--Hitung Nota Prevth*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, sum(a.v_nota_netto) as vnotaprev
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
            where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and f.i_area=a.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            group by to_char(a.d_nota, 'mm')

            ) as a 
            group by a.i_periode 
            order by a.i_periode";
        }elseif($group=='MO'){
            $sql = " a.i_periode, sum(ob) as ob, sum(oa) as oa, sum(qty) as qty, sum(vnota) as vnota, sum(oaprev)as oaprev, sum(qtyprev) as qtyprev, sum(vnotaprev) as vnotaprev from(
            /* Hitung OB */
            select a.i_periode, count(ob) as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev from (
            select distinct on(a.ob)  a.ob as ob, to_char(a.d_nota, 'mm') as i_periode, a.i_product_group from (
            select a.i_customer as ob, a.i_area , a.d_nota, x.i_product_group
            from tm_nota a , tr_area c, tm_spb x
            where 
            to_char(a.d_nota, 'yyyymm')>='$taunsasih' and to_char(a.d_nota, 'yyyymm') <='$thblto' 
            and a.i_spb = x.i_spb and a.i_area = x.i_area
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull and x.f_spb_consigment = 't'

            union all

            select b.i_customer as ob, b.i_area, NULL as d_nota, NULL as i_product_group
            from tr_customer b, tr_area c
            where b.i_customer_status<>'4' and b.f_customer_aktif='true' and b.i_area=c.i_area and c.f_area_real='t'
            and b.i_customer not in(
            select a.i_customer
            from tm_nota a , tr_area c, tm_spb x
            where 
            to_char(a.d_nota, 'yyyymm')>='$taunsasih' and to_char(a.d_nota, 'yyyymm') <='$thblto' 
            and a.i_spb = x.i_spb and a.i_area = x.i_area
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull and x.f_spb_consigment = 't'
            )
            ) as a 
            )as a
            group by a.i_periode

            union all

            /*--Hitung OA*/
            select a.i_periode, 0 as ob, count(oa) as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer as oa, to_char(a.d_nota, 'mm') as i_periode
            from tm_nota a, tr_customer b, tm_spb z where 
            (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='false'
            and not a.i_nota isnull 
            and a.i_customer=b.i_customer 
            and a.i_area=b.i_area 
            and b.i_customer_status<>'4' 
            and b.f_customer_aktif='true'
            and a.i_nota = z.i_nota
            and a.i_spb = z.i_spb
            and a.i_area = z.i_area
            and a.i_customer = z.i_customer
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'f'
            and f_spb_consigment = 't'
            ) as a
            group by a.i_periode
            union all
            /*--Hitung Qty */
            SELECT to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, sum(b.n_deliver) as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area 
            and f.i_area=a.i_area 
            and f.i_area=b.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and f_spb_consigment = 't'
            group by to_char(a.d_nota, 'mm')
            union all
            /*--Hitung Nota*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, sum(a.v_nota_netto) as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and f.i_area=a.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and f_spb_consigment = 't'
            group by to_char(a.d_nota, 'mm')
            union all
            /*--Hitung OA Prevth*/
            select a.i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, count(oa) as oaprev, 0 as qtyprev, 0 as vnotaprev from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer as oa, to_char(a.d_nota, 'mm') as i_periode
            from tm_nota a, tr_customer b, tm_spb z where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <=to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='false' 
            and a.i_customer=b.i_customer 
            and a.i_area=b.i_area 
            and b.i_customer_status<>'4' 
            and b.f_customer_aktif='true'
            and not a.i_nota isnull
            and a.i_nota = z.i_nota
            and a.i_spb = z.i_spb
            and a.i_area = z.i_area
            and a.i_customer = z.i_customer
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'f'
            and f_spb_consigment = 't'
            ) as a
            group by a.i_periode
            union all
            /*--Hitung Qty Prevth*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, sum(b.n_deliver) as qtyprev, 0 as vnotaprev
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area 
            and f.i_area=a.i_area 
            and f.i_area=b.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and f_spb_consigment = 't'
            group by to_char(a.d_nota, 'mm')
            union all
            /*--Hitung Nota Prevth*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, sum(a.v_nota_netto) as vnotaprev
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and f.i_area=a.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and f_spb_consigment = 't'
            group by to_char(a.d_nota, 'mm')

            ) as a 
            group by a.i_periode 
            order by a.i_periode
            ";
        }else{
            $sql ="a.i_periode, sum(ob) as ob, sum(oa) as oa, sum(qty) as qty, sum(vnota) as vnota, sum(oaprev)as oaprev, sum(qtyprev) as qtyprev, sum(vnotaprev) as vnotaprev, a.i_product_group from(
            /* Hitung OB */
            select a.i_periode, count(ob) as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev, a.i_product_group from (
            select distinct on(a.ob)  a.ob as ob, to_char(a.d_nota, 'mm') as i_periode, a.i_product_group from (
            select a.i_customer as ob, a.i_area , a.d_nota, x.i_product_group
            from tm_nota a , tr_area c, tm_spb x
            where 
            to_char(a.d_nota, 'yyyymm')>='$taunsasih' and to_char(a.d_nota, 'yyyymm') <='$thblto' 
            and a.i_spb = x.i_spb and a.i_area = x.i_area
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull and x.f_spb_consigment = 'f'
            and x.i_product_group = '$group'

            union all

            select b.i_customer as ob, b.i_area, NULL as d_nota, '$group' as i_product_group
            from tr_customer b, tr_area c
            where b.i_customer_status<>'4' and b.f_customer_aktif='true' and b.i_area=c.i_area and c.f_area_real='t'
            and b.i_customer not in(
            select a.i_customer as ob
            from tm_nota a , tr_area c, tm_spb x
            where 
            to_char(a.d_nota, 'yyyymm')>='$taunsasih' and to_char(a.d_nota, 'yyyymm') <='$thblto' 
            and a.i_spb = x.i_spb and a.i_area = x.i_area
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull and x.f_spb_consigment = 'f'
            and x.i_product_group = '$group'
            )
            ) as a 
            )as a
            group by a.i_periode, i_product_group

            union all

            /*--Hitung OA*/
            select b.i_periode, 0 as ob, count(b.i_customer) as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev, b.i_product_group from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, to_char(a.d_nota, 'mm') as i_periode, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
            and f.i_area=a.i_area and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and x.i_product_group= '$group'
            and x.f_spb_consigment = 'f'
            ) as b
            group by b.i_periode, b.i_product_group
            union all
            /*--Hitung Qty */
            SELECT to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, sum(b.n_deliver) as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev, x.i_product_group
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area 
            and f.i_area=a.i_area 
            and f.i_area=b.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and x.f_spb_consigment = 'f'
            and x.i_product_group = '$group'
            group by to_char(a.d_nota, 'mm'), x.i_product_group
            union all
            /*--Hitung Nota*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, sum(a.v_nota_netto) as vnota, 0 as oaprev, 0 as qtyprev, 0 as vnotaprev, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and f.i_area=a.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and f_spb_consigment = 'f'
            and x.i_product_group = '$group'
            group by to_char(a.d_nota, 'mm'), x.i_product_group
            union all
            /*--Hitung OA Prevth*/
            select b.i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, count(b.i_customer) as oaprev, 0 as qtyprev, 0 as vnotaprev, b.i_product_group from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, to_char(a.d_nota, 'mm') as i_periode, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
            and f.i_area=a.i_area and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and x.i_product_group= '$group'
            and x.f_spb_consigment = 'f'
            ) as b
            group by b.i_periode, b.i_product_group
            union all
            /*--Hitung Qty Prevth*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, sum(b.n_deliver) as qtyprev, 0 as vnotaprev, x.i_product_group
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area 
            and f.i_area=a.i_area 
            and f.i_area=b.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and x.f_spb_consigment = 'f'
            and x.i_product_group = '$group'
            group by to_char(a.d_nota, 'mm'), x.i_product_group
            union all
            /*--Hitung Nota Prevth*/
            select to_char(a.d_nota, 'mm') as i_periode, 0 as ob, 0 as oa, 0 as qty, 0 as vnota, 0 as oaprev, 0 as qtyprev, sum(a.v_nota_netto) as vnotaprev, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
            where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) 
            and a.f_nota_cancel='f'
            and f.i_area=a.i_area 
            and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and a.i_customer = x.i_customer
            and not x.i_nota isnull
            and not x.i_spb isnull
            and x.f_spb_cancel = 'f'
            and f_spb_consigment = 'f'
            and x.i_product_group = '$group'
            group by to_char(a.d_nota, 'mm'), x.i_product_group

            ) as a where a.i_product_group = '$group'
            group by a.i_periode, a.i_product_group
            order by a.i_periode
            ";

        }
        $this->db->select($sql,FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}
