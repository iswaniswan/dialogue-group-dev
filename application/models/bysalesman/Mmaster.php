<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($dfrom,$dto,$th,$prevth,$bl,$bulan,$tahun,$iuser,$akhir,$prevakhir,$last){
        $thbl=$th.$bl;
        $thblto=$tahun.$bulan;

        $tmp=explode("-",$dfrom);
        $hr=$tmp[0];
        $bl=$tmp[1];
        $thun=$tmp[2]-1;
        $thnow=$tmp[2];
        $thblfrom = $thnow."-".$bl;
        $dfromprev=$hr."-".$bl."-".$thun;

        $tsasih = date('Y-m', strtotime('-24 month', strtotime($thblfrom)));
        if($tsasih!=''){
            $smn = explode("-", $tsasih);
            $yer = $smn[0];
            $mon = $smn[1];
        }
        $taunsasih = $yer.$mon;

        $tmp=explode("-",$dto);
        $hri=$tmp[0];
        $bln=$tmp[1];
        $thun=$tmp[2]-1;
        $thn = $tmp[2];
        $thblto = $thn.$bln;
        if((intval($thun)%4!=0)&&($bln=='02')&&($hri=='29')) $hri='28';
        $dtoprev=$hri."-".$bln."-".$thun;
        $sql=" a.i_area , b.e_area_name ,a.i_salesman , c.e_salesman_name ,sum(a.vtargetcoll) as vtargetcoll , sum(a.vrealisasicoll) as vrealisasicoll ,
        sum(a.vtargetsls) as vtargetsls, sum(a.ob) as ob, sum(a.oa) as oa , sum(a.qty) as qty , sum(a.netsales) as netsales, 
        sum(a.oaprev) as oaprev , sum(a.qtyprev) as qtyprev , sum(a.netsalesprev) as netsalesprev from (
        ";
        $start = $month = strtotime($dfrom);
        $end = strtotime($dto);
        $i=0;
        while($month < $end){   
            $oke=date('m', $month);
            $month = strtotime("+1 month", $month);
            $date = date ("Y-m-d", strtotime("+1 month", strtotime($month)));
            $periode=$th.$oke;

            if($periode!=''){
                $th=substr($periode,0,4);
                $bl=substr($periode,4,2);
                $prevth  =$th-1;
                $prevdate=$prevth.$bl;
            }       
            if($bl <9 ){
                $bln = $bl+1;
                $bln = '0'.$bln;
                $akhir = $th.'-'.$bln.'-01';
                $prevakhir = $prevth.'-'.$bln.'-01';
            }elseif($bl >=9 && $bl <12){
                $bln = $bl+1;
                $akhir = $th.'-'.$bln.'-01';
                $prevakhir = $prevth.'-'.$bln.'-01';
            }elseif($bl ==12){
                $thn = $th+1;
                $akhir = $thn.'-01-01';
                $prevakhir = $prevth.'-01-01';
            }
            $sql .="  select i_area , i_salesman , sum(v_target_tagihan) as vtargetcoll , sum(v_realisasi_tagihan) as vrealisasicoll ,
            0 as vtargetsls, 0 as ob, 0 as oa , 0 as qty , 0 as netsales,
            0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , 0 as qtyprev , 0 as netsalesprev 
            from  f_target_collection_rekapkodealokasi('$periode','$akhir')
            group by i_area, i_salesman
            UNION ALL";
        }    
        $sql .="

        select i_area , i_salesman , 0 as vtargetcoll , 0 as vtargetcoll , sum(v_target) as vtargetsls, 0 as ob, 0 as oa , 0 as qty , 0 as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , 0 as qtyprev , 0 as netsalesprev
        from tm_target_itemsls
        where  i_periode >='$thbl' and i_periode <='$thblto' 
        group by i_area , i_salesman

        UNION ALL

        select i_area , i_salesman,0 as vtargetcoll , 0 as vtargetcoll ,0 as vtargetsls, count(ob) as ob, 0 as oa , 0 as qty , 0 as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , 0 as qtyprev , 0 as netsalesprev from (
        select distinct on (a.ob) a.ob as ob , a.i_salesman , a.i_area from (
        select  a.i_customer as ob ,a.i_salesman,a.i_area
        from tm_nota a 
        where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
        and a.f_nota_cancel='false'
        and not a.i_nota isnull
        union all
        select a.i_customer as ob ,b.i_salesman , a.i_area from tr_customer a 
        left join tr_customer_salesman b on(a.i_customer=b.i_customer and a.i_area=b.i_area) 
        where a.i_customer_status <> '4' and a.f_customer_aktif='true'
        ) as a
        order by a.ob

        ) as a
        group by i_area , i_salesman 

        UNION ALL

        select i_area , i_salesman,0 as vtargetcoll,0 as vtargetcoll ,0 as vtargetsls, 0 as ob,count(oa) as oa , 0 as qty , 0 as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , 0 as qtyprev , 0 as netsalesprev from(
        SELECT  distinct on (to_char(a.d_nota,'yyyymm'),a.i_customer,a.i_salesman) a.i_customer as oa, a.i_salesman , a.i_area from  tm_nota a ,tr_customer b
        where (a.d_nota >= to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy'))
        and a.f_nota_cancel='false' and not a.i_nota isnull
        and a.i_customer=b.i_customer and a.i_area=b.i_area
        ) as a
        group by i_salesman , i_area

        UNION ALL

        select a.i_area , b.i_salesman,0 as vtargetcoll,0 as vtargetcoll ,0 as vtargetsls, 0 as ob,0 as oa ,sum(a.n_deliver) as qty , 0 as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , 0 as qtyprev , 0 as netsalesprev
        from tm_nota_item a , tm_nota b
        where b.f_nota_cancel='false'
        and (b.d_nota >= to_date('$dfrom','dd-mm-yyyy') and b.d_nota <= to_date('$dto','dd-mm-yyyy'))
        and a.i_nota=b.i_nota and a.i_area=b.i_area and not b.i_nota isnull
        group by b.i_salesman , a.i_area
        UNION ALL
        select a.i_area, a.i_salesman,0 as vtargetcoll,0 as vtargetcoll ,0 as vtargetsls, 0 as ob,0 as oa ,0 as qty ,sum(v_nota_netto) as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , 0 as qtyprev , 0 as netsalesprev
        from tm_nota a
        where (d_nota >= to_date('$dfrom','dd-mm-yyyy') and d_nota <= to_date('$dto','dd-mm-yyyy'))
        and f_nota_cancel='f' and not i_nota isnull
        group by a.i_area, a.i_salesman
        UNION ALL
        /*================================================ PRev Year ==============================================*/
        select i_area , i_salesman,0 as vtargetcoll,0 as vtargetcoll ,0 as vtargetsls, 0 as ob,0 as oa , 0 as qty , 0 as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, count(oa) as oaprev , 0 as qtyprev , 0 as netsalesprev from(
        SELECT  distinct on (to_char(a.d_nota,'yyyymm'),a.i_customer,a.i_salesman) a.i_customer as oa, a.i_salesman , a.i_area from  tm_nota a ,tr_customer b
        where (a.d_nota >= to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <= to_date('$dtoprev','dd-mm-yyyy'))
        and a.f_nota_cancel='false' and not a.i_nota isnull
        and a.i_customer=b.i_customer and a.i_area=b.i_area
        ) as a
        group by i_salesman , i_area
        UNION ALL
        select a.i_area , b.i_salesman,0 as vtargetcoll,0 as vtargetcoll ,0 as vtargetsls, 0 as ob,0 as oa ,0 as qty , 0 as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , sum(a.n_deliver) as qtyprev , 0 as netsalesprev
        from tm_nota_item a , tm_nota b
        where b.f_nota_cancel='false'
        and (b.d_nota >= to_date('$dfromprev','dd-mm-yyyy') and b.d_nota <= to_date('$dtoprev','dd-mm-yyyy')) 
        and a.i_nota=b.i_nota and a.i_area=b.i_area and not b.i_nota isnull
        group by b.i_salesman , a.i_area
        UNION ALL
        select a.i_area, a.i_salesman,0 as vtargetcoll,0 as vtargetcoll ,0 as vtargetsls, 0 as ob,0 as oa ,0 as qty ,0 as netsales,
        0 as vtargetcollprev, 0 as vrealisasicollprev , 0 as vtargetslsprev,0 as obprev, 0 as oaprev , 0 as qtyprev , sum(v_nota_netto) as netsalesprev
        from tm_nota a
        where (a.d_nota >= to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <= to_date('$dtoprev','dd-mm-yyyy'))
        and f_nota_cancel='f' and not i_nota isnull
        group by a.i_area, a.i_salesman

        ) as a
        left join tr_area b on (a.i_area=b.i_area and b.f_area_real='t' )
        left join tr_salesman c on (a.i_salesman=c.i_salesman)
        group by a.i_area , a.i_salesman,b.e_area_name ,c.e_salesman_name
        order by i_area , i_salesman ";
        $this->db->select($sql,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}
