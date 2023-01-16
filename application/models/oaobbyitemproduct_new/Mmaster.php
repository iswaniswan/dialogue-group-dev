<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($dfrom,$dto,$group){
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


        if($group == 'NA'){
            $querry = "z.i_product, sum(z.oa) as oa , sum(z.oaprev) as oaprev, sum(z.ob) as ob, z.e_product_name, sum(z.jml) as jml ,
            sum(z.jmlprev) as jmlprev, sum(z.netitem) as netitem, sum(z.netitemprev) as netitemprev ,
            (sum(z.netitem)/(select sum(v_nota_netto) as v_nota from tm_nota where d_nota >= to_date('$dfrom','dd-mm-yyyy') and d_nota <= to_date('$dto','dd-mm-yyyy') 
            and not i_nota isnull and f_nota_cancel='false')*100) as ctrnetsales from (

            select b.i_product, sum(b.oa) as oa , 0 as oaprev, sum(b.ob) as ob, b.e_product_name, sum(b.jml) as jml , 0 as jmlprev, sum(b.netitem) as netitem, 0 as netitemprev from (
            select  a.i_product, count(a.i_customer) as oa,0 as oaprev, 0 as ob, a.e_product_name, sum(a.jml) as jml , 0 as netitem from (
            select distinct on (to_char(b.d_nota,'yyyymm'),b.i_customer) a.i_product, b.i_customer, c.e_product_name, 0 as jml
            from tm_nota_item a, tm_nota b, tr_product c,tr_customer d
            where a.i_nota=b.i_nota and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$dfrom','dd-mm-yyyy') and b.d_nota <= to_date('$dto','dd-mm-yyyy'))
            and not b.i_nota isnull
            and b.i_customer=d.i_customer and b.i_area=d.i_area 
            group by a.i_product, b.i_customer, c.e_product_name, b.i_nota,b.d_nota
            )as a
            group by a.i_product,a.e_product_name 

            union all 

            select  a.i_product, 0 as oa, 0 as oaprev, 0 as ob, a.e_product_name, sum(a.jml) as jml, sum(a.netitem) as netitem from (
            select a.i_product, c.e_product_name, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as netitem,sum(a.n_deliver) as jml
            from tm_nota_item a, tm_nota b , tr_product c
            where a.i_nota=b.i_nota and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$dfrom','dd-mm-yyyy') and b.d_nota <= to_date('$dto','dd-mm-yyyy'))
            and not b.i_nota isnull
            group by a.i_product, c.e_product_name
            )as a
            group by a.i_product, a.e_product_name
            ) as b
            group by b.i_product, b.e_product_name
            /*-------------------------------------------tahun lalu-----------------------------------------------------------*/
            union all 
            /*-------------------------------------------tahun lalu ----------------------------------------------------------*/
            select b.i_product,0 as oa, sum(b.oaprev) as oaprev , 0 as ob, b.e_product_name, 0 as jml, sum(b.jmlprev) as jmlprev , 0 as netitem, sum(b.netitemprev) as netitemprev from (
            select  a.i_product, count(a.i_customer) as oaprev, a.e_product_name, 0 as jmlprev , 0 as netitemprev from (
            select distinct on (to_char(b.d_nota,'yyyymm'),b.i_customer) a.i_product, b.i_customer, c.e_product_name, 0 as jmlprev
            from tm_nota_item a, tm_nota b, tr_product c,tr_customer d
            where a.i_nota=b.i_nota and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$gabung1','dd-mm-yyyy') and b.d_nota <= to_date('$gabung2','dd-mm-yyyy'))
            and not b.i_nota isnull
            and b.i_customer=d.i_customer and b.i_area=d.i_area 
            group by a.i_product, b.i_customer, c.e_product_name, b.i_nota,b.d_nota
            )as a
            group by a.i_product,a.e_product_name 

            union all 

            select  a.i_product, 0 as oaprev, a.e_product_name, sum(a.jmlprev) as jmlprev, sum(a.netitemprev) as netitemprev from (
            select a.i_product, c.e_product_name, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as netitemprev, sum(a.n_deliver) as jmlprev
            from tm_nota_item a, tm_nota b , tr_product c
            where a.i_nota=b.i_nota and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$gabung1','dd-mm-yyyy') and b.d_nota <= to_date('$gabung2','dd-mm-yyyy'))
            and not b.i_nota isnull
            group by a.i_product, c.e_product_name
            )as a
            group by a.i_product, a.e_product_name

            ) as b
            group by b.i_product, b.e_product_name

            ) as z
            group by z.i_product, z.e_product_name
            order by ctrnetsales desc ,z.i_product";
        }elseif($group == 'MO'){
            $querry = "z.i_product, sum(z.oa) as oa, sum(z.oaprev) as oaprev, sum(z.ob) as ob, z.e_product_name, sum(z.jml) as jml, sum(z.jmlprev) as jmlprev, 
            sum(z.netitem) as netitem, sum(z.netitemprev) as netitemprev, (sum(z.netitem)/(select sum(v_nota_netto) as v_nota from tm_nota, tm_spb as z
            where tm_nota.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') 
            and tm_nota.d_nota <= to_date('$dto', 'dd-mm-yyyy') 
            and not tm_nota.i_nota isnull 
            and f_nota_cancel='false'
            and tm_nota.i_nota = z.i_nota
            and tm_nota.i_spb = z.i_spb
            and tm_nota.i_customer  = z.i_customer
            and tm_nota.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'
            and z.f_spb_consigment = 't'
            )*100) as ctrnetsales, z.i_product_group from (
            select b.i_product, sum(b.oa) as oa, 0 as oaprev, sum(b.ob) as ob, b.e_product_name, sum(b.jml) as jml, 0 as jmlprev, sum(b.netitem) as netitem, 
            0 as netitemprev, b.i_product_group from (
            select a.i_product, count(a.i_customer) as oa, 0 as oaprev, 0 as ob, a.e_product_name, sum(a.jml) as jml, 0 as netitem, a.i_product_group from (
            select distinct on (to_char(b.d_nota, 'yyyymm'), b.i_customer) a.i_product, b.i_customer, c.e_product_name, 0 as jml, z.i_product_group
            from tm_nota_item a, tm_nota b, tr_product c, tr_customer d, tm_spb as z
            where a.i_nota=b.i_nota and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') 
            and b.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
            and not b.i_nota isnull
            and b.i_customer=d.i_customer 
            and b.i_area=d.i_area 
            and d.i_customer_status<>'4' 
            and d.f_customer_aktif='true'

            and b.i_nota = z.i_nota
            and b.i_spb = z.i_spb
            and b.i_customer  = z.i_customer
            and b.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'

            and z.f_spb_consigment = 't'
            group by a.i_product, b.i_customer, c.e_product_name, b.i_nota, b.d_nota,z.i_product_group
            )as a
            group by a.i_product, a.e_product_name , a.i_product_group

            union all 

            select a.i_product, 0 as oa, 0 as oaprev, 0 as ob, a.e_product_name, sum(a.jml) as jml, sum(a.netitem) as netitem, a.i_product_group from (
            select a.i_product, c.e_product_name, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as netitem, 
            sum(a.n_deliver) as jml, z.i_product_group
            from tm_nota_item a, tm_nota b, tr_product c, tm_spb as z
            where a.i_nota=b.i_nota 
            and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') 
            and b.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
            and not b.i_nota isnull

            and b.i_nota = z.i_nota
            and b.i_spb = z.i_spb
            and b.i_customer  = z.i_customer
            and b.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'

            and z.f_spb_consigment = 't'
            group by a.i_product, c.e_product_name,z.i_product_group
            )as a
            group by a.i_product, a.e_product_name, a.i_product_group
            ) as b
            group by b.i_product, b.e_product_name, b.i_product_group
            /*-------------------------------------------tahun lalu-----------------------------------------------------------*/
            union all 
            /*-------------------------------------------tahun lalu ----------------------------------------------------------*/
            select b.i_product, 0 as oa, sum(b.oaprev) as oaprev, 0 as ob, b.e_product_name, 0 as jml, sum(b.jmlprev) as jmlprev, 0 as netitem, 
            sum(b.netitemprev) as netitemprev, b.i_product_group from (
            select a.i_product, count(a.i_customer) as oaprev, a.e_product_name, 0 as jmlprev, 0 as netitemprev, a.i_product_group from (
            select distinct on (to_char(b.d_nota, 'yyyymm'), b.i_customer) a.i_product, b.i_customer, c.e_product_name, 0 as jmlprev, z.i_product_group
            from tm_nota_item a, tm_nota b, tr_product c, tr_customer d, tm_spb as z
            where a.i_nota=b.i_nota 
            and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$gabung1', 'dd-mm-yyyy') 
            and b.d_nota <= to_date('$gabung2', 'dd-mm-yyyy'))
            and not b.i_nota isnull
            and b.i_customer=d.i_customer 
            and b.i_area=d.i_area 
            and d.i_customer_status<>'4' 
            and d.f_customer_aktif='true'
            and b.i_nota = z.i_nota
            and b.i_spb = z.i_spb
            and b.i_customer  = z.i_customer
            and b.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'

            and z.f_spb_consigment = 't'
            group by a.i_product, b.i_customer, c.e_product_name, b.i_nota, b.d_nota,z.i_product_group
            )as a
            group by a.i_product, a.e_product_name , a.i_product_group

            union all 

            select a.i_product, 0 as oaprev, a.e_product_name, sum(a.jmlprev) as jmlprev, sum(a.netitemprev) as netitemprev, a.i_product_group from (
            select a.i_product, c.e_product_name, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as netitemprev, 
            sum(a.n_deliver) as jmlprev, z.i_product_group
            from tm_nota_item a, tm_nota b, tr_product c, tm_spb as z
            where a.i_nota=b.i_nota 
            and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$gabung1', 'dd-mm-yyyy') 
            and b.d_nota <= to_date('$gabung2', 'dd-mm-yyyy'))
            and not b.i_nota isnull
            and b.i_nota = z.i_nota
            and b.i_spb = z.i_spb
            and b.i_customer  = z.i_customer
            and b.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'

            and z.f_spb_consigment = 't'
            group by a.i_product, c.e_product_name, z.i_product_group
            )as a
            group by a.i_product, a.e_product_name, a.i_product_group

            ) as b
            group by b.i_product, b.e_product_name, b.i_product_group

            ) as z
            group by z.i_product, z.e_product_name, z.i_product_group
            order by ctrnetsales desc, z.i_product
            ";
        }else{
            $querry = "z.i_product, sum(z.oa) as oa, sum(z.oaprev) as oaprev, sum(z.ob) as ob, z.e_product_name, sum(z.jml) as jml, sum(z.jmlprev) as jmlprev, 
            sum(z.netitem) as netitem, sum(z.netitemprev) as netitemprev, (sum(z.netitem)/(select sum(v_nota_netto) as v_nota from tm_nota, tm_spb as z
            where tm_nota.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') 
            and tm_nota.d_nota <= to_date('$dto', 'dd-mm-yyyy') 
            and not tm_nota.i_nota isnull 
            and f_nota_cancel='false'
            and tm_nota.i_nota = z.i_nota
            and tm_nota.i_spb = z.i_spb
            and tm_nota.i_customer  = z.i_customer
            and tm_nota.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'
            and z.f_spb_consigment = 'f'
            and z.i_product_group = '$group')*100) as ctrnetsales, z.i_product_group from (
            select b.i_product, sum(b.oa) as oa, 0 as oaprev, sum(b.ob) as ob, b.e_product_name, sum(b.jml) as jml, 0 as jmlprev, sum(b.netitem) as netitem, 
            0 as netitemprev, b.i_product_group from (
            select b.i_product, count(b.i_customer) as oa, 0 as oaprev, 0 as ob, b.e_product_name, 0 as jml, 0 as netitem, b.i_product_group from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, b.i_product, b.e_product_name, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_nota_item b, tm_spb x
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
            and f.i_area=a.i_area and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_sj = b.i_sj
            and a.i_nota = b.i_nota
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and x.i_product_group= '$group'
            and x.f_spb_consigment = 'f'
            ) as b
            group by b.i_product, b.e_product_name, b.i_product_group


            union all 

            select a.i_product, 0 as oa, 0 as oaprev, 0 as ob, a.e_product_name, sum(a.jml) as jml, sum(a.netitem) as netitem, a.i_product_group from (
            select a.i_product, c.e_product_name, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as netitem, 
            sum(a.n_deliver) as jml, z.i_product_group
            from tm_nota_item a, tm_nota b, tr_product c, tm_spb as z
            where a.i_nota=b.i_nota 
            and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') 
            and b.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
            and not b.i_nota isnull

            and b.i_nota = z.i_nota
            and b.i_spb = z.i_spb
            and b.i_customer  = z.i_customer
            and b.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'
            and z.i_product_group = '$group'
            and z.f_spb_consigment = 'f'
            group by a.i_product, c.e_product_name,z.i_product_group
            )as a
            group by a.i_product, a.e_product_name, a.i_product_group
            ) as b
            group by b.i_product, b.e_product_name, b.i_product_group
            /*-------------------------------------------tahun lalu-----------------------------------------------------------*/
            union all 
            /*-------------------------------------------tahun lalu ----------------------------------------------------------*/
            select b.i_product, 0 as oa, sum(b.oaprev) as oaprev, 0 as ob, b.e_product_name, 0 as jml, sum(b.jmlprev) as jmlprev, 0 as netitem, 
            sum(b.netitemprev) as netitemprev, b.i_product_group from (
            select b.i_product, count(b.i_customer) as oaprev, b.e_product_name, 0 as jmlprev, 0 as netitemprev, b.i_product_group from (
            select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, b.i_product, b.e_product_name, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_nota_item b, tm_spb x
            where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
            and f.i_area=a.i_area and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_sj = b.i_sj
            and a.i_nota = b.i_nota
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and x.i_product_group= '$group'
            and x.f_spb_consigment = 'f'
            ) as b
            group by b.i_product, b.e_product_name, b.i_product_group

            union all 

            select a.i_product, 0 as oaprev, a.e_product_name, sum(a.jmlprev) as jmlprev, sum(a.netitemprev) as netitemprev, a.i_product_group from (
            select a.i_product, c.e_product_name, sum((a.n_deliver*a.v_unit_price-(((a.n_deliver*a.v_unit_price)/b.v_nota_gross)*b.v_nota_discounttotal))) as netitemprev, 
            sum(a.n_deliver) as jmlprev, z.i_product_group
            from tm_nota_item a, tm_nota b, tr_product c, tm_spb as z
            where a.i_nota=b.i_nota 
            and a.i_product = c.i_product
            and b.f_nota_cancel = 'false'
            and (b.d_nota >= to_date('$gabung1', 'dd-mm-yyyy') 
            and b.d_nota <= to_date('$gabung2', 'dd-mm-yyyy'))
            and not b.i_nota isnull
            and b.i_nota = z.i_nota
            and b.i_spb = z.i_spb
            and b.i_customer  = z.i_customer
            and b.i_area = z.i_area
            and not z.i_nota isnull
            and not z.i_spb isnull
            and z.f_spb_cancel = 'false'
            and z.i_product_group = '$group'
            and z.f_spb_consigment = 'f'
            group by a.i_product, c.e_product_name, z.i_product_group
            )as a
            group by a.i_product, a.e_product_name, a.i_product_group

            ) as b
            group by b.i_product, b.e_product_name, b.i_product_group

            ) as z
            where z.i_product_group = '$group'
            group by z.i_product, z.e_product_name, z.i_product_group
            order by ctrnetsales desc, z.i_product
            ";
        }

        $this->db->select($querry,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacaob($dfrom,$dto,$group){ 
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
        $hr=$tmp[0];
        $bl=$tmp[1];
        $th=$tmp[2]-1;
        $thnya=$tmp[2];
        $thblto=$thnya.$bl;
        $dtoprev=$hr."-".$bl."-".$th;

        if($group == 'NA'){
            $querry = "count(a.ob) as ob from (
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
            order by x.ob) as a";
        }elseif($group == '01'){
            $querry = "count(a.ob) as ob from (
            select distinct on (x.ob) x.ob as ob, x.i_area, x.e_area_name ,x.e_area_island, x.e_product_groupname , x.e_provinsi, x.i_product_group  from(
            select a.i_customer as ob, a.i_area, c.e_area_name ,c.e_area_island, e.e_product_groupname, c.e_provinsi, d.i_product_group  
            from tm_nota a , tr_area c, tm_spb d, tr_product_group e
            where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull
            and a.i_spb = d.i_spb and a.i_area <> 'PB'
            and a.i_area = d.i_area
            and a.i_customer = d.i_customer
            and not d.i_spb isnull
            and not d.i_nota isnull and d.i_product_group='$group'
            and d.f_spb_consigment = 'f' and d.i_product_group=e.i_product_group
            union all
            select b.i_customer as ob, b.i_area, c.e_area_name ,c.e_area_island , d.e_product_groupname, c.e_provinsi, '$group' as i_product_group
            from tr_area c, tr_customer b, tr_product_group d 
            where b.i_customer_status<>'4' and b.f_customer_aktif='true' and b.i_area=c.i_area and c.f_area_real='t'
            and d.i_product_group='$group' and not b.i_customer like 'PB%'
            )
            as x) as a";
        }elseif($group == 'MO'){
            $querry = "0 as oa, count(a.ob) as ob, 0 as vnota, 0 as qty, a.i_area,a.e_area_name, a.e_area_island, '' as e_product_groupname,a.e_provinsi from (
            select distinct on (x.ob) x.ob as ob, x.i_area, x.e_area_name ,x.e_area_island , x.e_provinsi from(
            select a.i_customer as ob, a.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
            from tm_nota a , tr_area c, tm_spb d
            where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull
            and a.i_spb = d.i_spb and a.i_area = 'PB'
            and a.i_area = d.i_area
            and a.i_customer = d.i_customer
            and not d.i_spb isnull
            and not d.i_nota isnull
            and d.f_spb_consigment = 't'
            )
            as x
            order by x.ob
            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island,a.e_provinsi";
        }else{
            $querry = " sum(z.ob) as ob, z.i_product_group from(
            select count(a.ob) as ob, a.i_product_group from (
            select distinct on (x.ob) x.ob as ob, x.i_area, x.e_area_name ,x.e_area_island, x.e_product_groupname , x.e_provinsi, x.i_product_group  from(
            select a.i_customer as ob, a.i_area, c.e_area_name ,c.e_area_island, e.e_product_groupname, c.e_provinsi, d.i_product_group  
            from tm_nota a , tr_area c, tm_spb d, tr_product_group e
            where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull
            and a.i_spb = d.i_spb and a.i_area <> 'PB'
            and a.i_area = d.i_area
            and a.i_customer = d.i_customer
            and not d.i_spb isnull
            and not d.i_nota isnull
            and d.f_spb_consigment = 'f' and d.i_product_group=e.i_product_group
            )
            as x
            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname, a.e_provinsi, a.i_product_group
            ) as z
            where z.i_product_group = '$group'
            group by i_product_group
            ";
        }
        $this->db->select($querry,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }
}
