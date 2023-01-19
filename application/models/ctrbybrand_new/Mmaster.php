<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($dfrom,$dto){ 
        $tmp=explode("-",$dfrom);
        $hr=$tmp[0];
        $bl=$tmp[1];
        $tahun=$tmp[2];
        $th=$tmp[2]-1;
        $dfromprev=$hr."-".$bl."-".$th;

        $tmp=explode("-",$dto);
        $hr=$tmp[0];
        $bl=$tmp[1];
        $th=$tmp[2]-1;
        $thnow=$tmp[2];

        if((intval($th)%4!=0)&&($bl=='02')&&($hr=='29')) $hr='28';

        $dtoprev=$hr."-".$bl."-".$th;

        $this->db->select("
        a.group ,sum(a.totsales) as totsales, sum(ob) as ob, sum(oa) as oa , sum(a.vnota)  as vnota, sum(qnota) as qnota ,sum(oaprev) as oaprev, sum(vnotaprev) as vnotaprev , sum(qnotaprev) as qnotaprev from (
            select a.i_periode,sum(a.totsales) as totsales , a.group,sum(ob) as ob, sum(oa) as oa ,sum(a.vnota)  as vnota, sum(qnota) as qnota , sum(oaprev) as oaprev ,sum(vnotaprev) as vnotaprev , sum(qnotaprev) as qnotaprev from ( 

            /*--=================================START FIRST YEAR=================================================*/

            /*--hitung totsales*/
            select '' as i_periode ,a.e_product_groupname as group, count(a.i_salesman) as totsales ,0 as ob ,0 as oa ,0  as vnota, 0 as qnota ,0 as oaprev , 0 as vnotaprev , 0 as qnotaprev from (
            select distinct a.i_salesman , c.e_product_groupname
            from tm_nota a , tm_spb b , tr_product_group c where (a.d_nota >=to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='false'
            and not a.i_nota isnull and a.i_area=b.i_area and a.i_nota=b.i_nota and b.f_spb_cancel='false'
            and b.i_product_group=c.i_product_group
            ) as a
            group by a.e_product_groupname

            union all

            /*-- Hitung Jum Rp.Nota Per Group*/
            select to_char(a.d_nota,'yyyymm') as i_periode, c.e_product_groupname as group,0 as totsales,0 as ob , 0 as oa ,sum(a.v_nota_netto)  as vnota, 0 as qnota ,0 as oaprev , 0 as vnotaprev , 0 as qnotaprev 
            from tm_nota a, tm_spb b, tr_product_group c
            where (a.d_nota >=to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.i_product_group=c.i_product_group
            group by to_char(a.d_nota,'yyyymm'), c.e_product_groupname
            Union all

            /*--hitung qty group*/
            select to_char(a.d_nota,'yyyymm') as i_periode, c.e_product_groupname as group,0 as totsales,0 as ob ,0 as oa , 0  as vnota, sum(d.n_deliver) as qnota ,0 as oaprev , 0 as vnotaprev , 0 as qnotaprev
            from tm_nota a, tm_spb b, tr_product_group c, tm_nota_item d
            where (a.d_nota >=to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.i_product_group=c.i_product_group  
            and a.i_sj=d.i_sj and a.i_area=d.i_area
            group by to_char(a.d_nota,'yyyymm'), c.e_product_groupname
            union all

            /*--hitung oa */
            select a.i_periode , a.e_product_groupname as group ,0 as totsales,0 as ob , count(a.oa) as oa ,0  as vnota, 0 as qnota ,0 as oaprev , 0 as vnotaprev , 0 as qnotaprev from (
            select distinct on (to_char(a.d_nota,'yyyymm'),a.i_customer) a.i_customer as oa , to_char(a.d_nota,'yyyymm') as i_periode , c.e_product_groupname
            from tm_nota a , tm_spb b , tr_product_group c where (a.d_nota >=to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='false'
            and not a.i_nota isnull and a.i_area=b.i_area and a.i_nota=b.i_nota and b.f_spb_cancel='false' and b.i_product_group=c.i_product_group
            /*--and a.i_customer in ('01155','01010')*/
            /*--order by a.i_customer*/
            ) as a
            group by a.i_periode , a.e_product_groupname
            union all

            /*-- Hitung OB Group*/
            select a.i_periode , a.e_product_groupname as group ,0 as totsales,count(ob) as ob, 0 as oa ,0  as vnota, 0 as qnota ,0 as oaprev , 0 as vnotaprev , 0 as qnotaprev from (
            select distinct on (a.i_customer)  a.i_customer as ob, to_char (a.d_nota,'yyyymm') as i_periode , c.e_product_groupname
            from tm_nota a , tm_spb b, tr_product_group c
            where a.d_nota <= to_date('$dto','dd-mm-yyyy') and a.f_nota_cancel='false'
            and not a.i_nota isnull and a.i_area=b.i_area and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.i_product_group=c.i_product_group 
            order by a.i_customer 
            ) as a
            group by a.i_periode , a.e_product_groupname
            union all

            /*--========================================START OF PREV YEAR==================================================*/


            select to_char(a.d_nota,'yyyymm') as i_periode, c.e_product_groupname as group,0 as totsales,0 as ob ,0 as oa , 0 as vnota, 0 as qnota ,0 as oaprev , sum(a.v_nota_netto) as vnotaprev , 0 as qnotaprev 
            from tm_nota a, tm_spb b, tr_product_group c
            where (a.d_nota >=to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <= to_date('$dtoprev','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.i_product_group=c.i_product_group
            group by to_char(a.d_nota,'yyyymm'), c.e_product_groupname
            Union all

            select to_char(a.d_nota,'yyyymm') as i_periode, c.e_product_groupname as group,0 as totsales,0 as ob , 0 as oa ,0  as vnota, 0 as qnota ,0 as oaprev , 0 as vnotaprev , sum(d.n_deliver) as qnotaprev
            from tm_nota a, tm_spb b, tr_product_group c, tm_nota_item d
            where (a.d_nota >=to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <= to_date('$dtoprev','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.i_product_group=c.i_product_group  
            and a.i_sj=d.i_sj and a.i_area=d.i_area
            group by to_char(a.d_nota,'yyyymm'), c.e_product_groupname
            union all 


            /*--hitung oa tahun sebelumnya*/
            select a.i_periode , a.e_product_groupname as group ,0 as totsales,0 as ob , 0 as oa ,0  as vnota, 0 as qnota , count(a.oa) as oaprev ,0 as vnotaprev , 0 as qnotaprev from (
            select distinct on (to_char(a.d_nota,'yyyymm'),a.i_customer) a.i_customer as oa , to_char(a.d_nota,'yyyymm') as i_periode , c.e_product_groupname
            from tm_nota a , tm_spb b , tr_product_group c where (a.d_nota >=to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <= to_date('$dtoprev','dd-mm-yyyy')) and a.f_nota_cancel='false'
            and not a.i_nota isnull and a.i_area=b.i_area and a.i_nota=b.i_nota and b.f_spb_cancel='false'
            and b.i_product_group=c.i_product_group
            ) as a
            group by a.i_periode , a.e_product_groupname
            /*============================================END======================================*/

            ) as a
            group by a.i_periode, a.group

            ) as a
            group by a.group
            ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacagroup($tahun){
        $this->db->select(" a.group from( 
            SELECT to_char(a.d_nota,'yyyy') as i_periode, 'Modern Outlet' as group, sum(a.v_nota_netto)  as vnota, 0 as qnota
            from tm_nota a, tm_spb b
            where to_char(a.d_nota,'yyyy') = '$tahun' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.f_spb_consigment='t'
            group by to_char(a.d_nota,'yyyy')
            union all
            select to_char(a.d_nota,'yyyy') as i_periode, c.e_product_groupname as group, sum(a.v_nota_netto)  as vnota, 
            0 as qnota from tm_nota a, tm_spb b, tr_product_group c
            where to_char(a.d_nota,'yyyy') = '$tahun' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.f_spb_consigment='f' and b.i_product_group=c.i_product_group
            group by to_char(a.d_nota,'yyyy'), c.e_product_groupname
            Union all
            SELECT to_char(a.d_nota,'yyyy') as i_periode, 'Modern Outlet' as group, 0  as vnota, sum(c.n_deliver) as qnota
            from tm_nota a, tm_spb b, tm_nota_item c
            where to_char(a.d_nota,'yyyy') = '$tahun' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.f_spb_consigment='t'  and a.i_sj=c.i_sj and a.i_area=c.i_area
            group by to_char(a.d_nota,'yyyy')
            union all
            select to_char(a.d_nota,'yyyy') as i_periode, c.e_product_groupname as group, 0  as vnota, 
            sum(d.n_deliver) as qnota from tm_nota a, tm_spb b, tr_product_group c, tm_nota_item d
            where to_char(a.d_nota,'yyyy') = '$tahun' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area 
            and a.i_nota=b.i_nota and b.f_spb_cancel='f' and b.f_spb_consigment='f' and b.i_product_group=c.i_product_group  
            and a.i_sj=d.i_sj and a.i_area=d.i_area
            group by to_char(a.d_nota,'yyyy'), c.e_product_groupname
            ) as a
            group by a.i_periode, a.group
            order by a.i_periode, a.group",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacaob($dfrom,$dto){ 
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

        $this->db->select(" count(ob) as ob from (
            select distinct on (a.ob) a.ob as ob, a.i_area, a.e_area_name ,a.e_area_island , a.e_provinsi from (
            select a.i_customer as ob, a.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
            from tm_nota a , tr_area c
            where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull

            union all

            select b.i_customer as ob, b.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
            from tr_customer b, tr_area c
            where b.i_customer_status<>'4' and b.f_customer_aktif='true' and b.i_area=c.i_area and c.f_area_real='t'
            ) as a 
            ) as a
            ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }
}
