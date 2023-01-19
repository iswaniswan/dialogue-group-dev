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
            $thbl=$thnow.$bl;
            $dfromprev=$hr."-".$bl."-".$th;

            $tmp=explode("-",$dto);
            $hr=$tmp[0];
            $bl=$tmp[1];
            $th=$tmp[2]-1;
            $thnya=$tmp[2];
            $thblto=$thnya.$bl;
            if((intval($th)%4!=0)&&($bl=='02')&&($hr=='29')) $hr='28';
            $dtoprev=$hr."-".$bl."-".$th;

            if($group=='NA'){
                  $sql=" a.e_area_island ,a.e_provinsi ,sum(a.vnota)  as vnota, sum(qnota) as qnota , sum(a.ob) as ob ,sum(a.oa) as oa , sum(a.prevvnota)  as prevvnota , sum(a.prevqnota) as prevqnota , sum(a.prevoa) as prevoa from (
                  select a.e_area_island ,a.e_provinsi ,sum(a.vnota)  as vnota, sum(qnota) as qnota , sum(a.ob) as ob ,sum(a.oa) as oa , sum(a.prevvnota)  as prevvnota , sum(a.prevqnota) as prevqnota , sum(a.prevoa) as prevoa from (
                  /*============================== Start This Year============================================*/
                  /* Hitung OB Group*/
                  select a.e_area_island,a.e_provinsi,
                  0 as vnota , 0 as qnota, count(ob) as ob, 0 as oa, 0 as prevvnota , 0 as prevqnota, 0 as prevoa from (
                  select distinct a.i_customer as ob, b.e_area_island, b.e_provinsi , a.e_periode,a.i_salesman
                  from tr_customer_salesman a,tr_area b
                  where e_periode ='$thblto' 
                  and a.i_area=b.i_area
                  and b.f_area_real='t'
                  ) as a
                  group by a.e_area_island, a.ob, a.e_provinsi
                  union all

                  /*Hitung Rp.Nota*/
                  select b.e_area_island, b.e_provinsi,
                  sum(a.v_nota_netto)  as vnota, 0 as qnota , 0 as ob,0 as oa , 0 as prevvnota , 0 as prevqnota , 0 as prevoa
                  from tm_nota a, tr_area b
                  where (a.d_nota>=to_date('$dfrom','dd-mm-yyyy') and a.d_nota <=to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and b.f_area_real='t'
                  group by b.e_area_island,b.e_provinsi
                  union all

                  /*//Hitung Qty */
                  select b.e_area_island, b.e_provinsi,
                  0  as vnota, sum(c.n_deliver) as qnota,0 as ob,0 as oa , 0 as prevvnota , 0 as prevqnota, 0 as prevoa
                  from tm_nota a, tr_area b, tm_nota_item c
                  where (a.d_nota>=to_date('$dfrom','dd-mm-yyyy') and a.d_nota <=to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and a.i_sj=c.i_sj and a.i_area=c.i_area
                  and b.f_area_real='t'
                  group by b.e_area_island, b.e_provinsi
                  union all

                  /*//Hitung OA*/
                  select a.e_area_island ,a.e_provinsi,
                  0 as vnota , 0 as qnota ,0 as ob, count(a.oa) as oa , 0 as prevvnota , 0 as prevqnota, 0 as prevoa from (
                  select distinct on (to_char(a.d_nota,'yyyymm'),a.i_customer) a.i_customer as oa , b.e_area_island,b.e_provinsi
                  from tm_nota a , tr_area b where (a.d_nota>=to_date('$dfrom','dd-mm-yyyy') and a.d_nota <=to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='false'
                  and not a.i_nota isnull and a.i_area=b.i_area 
                  and b.f_area_real='t'
                  ) as a
                  group by a.e_area_island,a.e_provinsi
                  union all

                  /*=============================================End This Year=============================================*/
                  /*=============================================Start Prev Year===========================================*/

                  /*//Hitung Rp.Nota*/
                  select b.e_area_island, b.e_provinsi,
                  0  as vnota, 0 as qnota,0 as ob, 0 as oa , sum(a.v_nota_netto) as prevvnota , 0 as prevqnota, 0 as prevoa
                  from tm_nota a, tr_area b
                  where (a.d_nota>=to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <=to_date('$dtoprev','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and b.f_area_real='t'
                  group by b.e_area_island, b.e_provinsi
                  union all

                  /*//Hitung Qty */
                  select b.e_area_island,  b.e_provinsi,
                  0 as vnota, 0 as qnota,0 as ob, 0 as oa , 0 as prevvnota ,sum(c.n_deliver) as prevqnota, 0 as prevoa
                  from tm_nota a, tr_area b, tm_nota_item c
                  where (a.d_nota>=to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <=to_date('$dtoprev','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and a.i_sj=c.i_sj and a.i_area=c.i_area
                  and b.f_area_real='t'
                  group by b.e_area_island, b.e_provinsi
                  union all

                  /*//Hitung OA*/
                  select a.e_area_island ,a.e_provinsi,
                  0 as vnota , 0 as qnota ,0 as ob, 0 as oa , 0 as prevvnota , 0 as prevqnota, count(oa) as prevoa from (
                  select distinct on (to_char(a.d_nota,'yyyymm'),a.i_customer) a.i_customer as oa , b.e_area_island ,b.e_provinsi
                  from tm_nota a , tr_area b where (a.d_nota>=to_date('$dfromprev','dd-mm-yyyy') and a.d_nota <=to_date('$dtoprev','dd-mm-yyyy')) and a.f_nota_cancel='false'
                  and not a.i_nota isnull and a.i_area=b.i_area 
                  and b.f_area_real='t'
                  ) as a
                  group by  a.e_area_island,a.e_provinsi


                  ) as a
                  group by a.e_area_island,a.e_provinsi
                  ) as a      
                  group by a.e_area_island,a.e_provinsi
                  order by a.e_area_island,a.e_provinsi";
            }else{
                  $sql =" a.e_area_island ,sum(a.vnota)  as vnota, sum(qnota) as qnota , sum(a.oa) as oa , sum(a.prevvnota)  as prevvnota , sum(a.prevqnota) as prevqnota , sum(a.prevoa) as prevoa , a.e_product_groupname ,a.i_product_group from (
                  select a.i_periode, a.e_area_island, sum(a.vnota)  as vnota, sum(qnota) as qnota , sum(a.oa) as oa , sum(a.prevvnota)  as prevvnota , sum(a.prevqnota) as prevqnota , sum(a.prevoa) as prevoa, a.e_product_groupname ,a.i_product_group from (
                  /*============================== Start This Year============================================*/
                  /*//Hitung Rp.Nota*/
                  select to_char(a.d_nota,'yyyy') as i_periode, b.e_area_island, 
                  sum(a.v_nota_netto)  as vnota, 0 as qnota , 0 as oa , 0 as prevvnota , 0 as prevqnota , 0 as prevoa , d.e_product_groupname , c.i_product_group
                  from tm_nota a, tr_area b , tm_spb c , tr_product_group d
                  where to_char(a.d_nota,'yyyy') = '$tahun' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and a.i_area=c.i_area and a.i_nota=c.i_nota and c.f_spb_cancel='false' and c.i_product_group=d.i_product_group
                  group by to_char(a.d_nota,'yyyy'), b.e_area_island,d.e_product_groupname , c.i_product_group
                  union all

                  /*//Hitung Qty */
                  select to_char(a.d_nota,'yyyy') as i_periode, b.e_area_island, 
                  0  as vnota, sum(c.n_deliver) as qnota, 0 as oa , 0 as prevvnota , 0 as prevqnota, 0 as prevoa , e.e_product_groupname , d.i_product_group 
                  from tm_nota a, tr_area b, tm_nota_item c , tm_spb d , tr_product_group e
                  where to_char(a.d_nota,'yyyy') = '$tahun' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and a.i_sj=c.i_sj and a.i_area=c.i_area and a.i_area=d.i_area and a.i_nota=d.i_nota and d.f_spb_cancel='false'
                  and d.i_product_group=e.i_product_group
                  group by to_char(a.d_nota,'yyyy'), b.e_area_island, e.e_product_groupname , d.i_product_group 
                  union all

                  /*//Hitung OA*/
                  select a.i_periode , a.e_area_island ,
                  0 as vnota , 0 as qnota , count(a.oa) as oa , 0 as prevvnota , 0 as prevqnota, 0 as prevoa , a.e_product_groupname , a.i_product_group from (
                  select distinct on (to_char(a.d_nota,'yyyy'),a.i_customer) a.i_customer as oa , to_char(a.d_nota,'yyyy') as i_periode , b.e_area_island, d.e_product_groupname , c.i_product_group
                  from tm_nota a , tr_area b , tm_spb c , tr_product_group d where to_char(a.d_nota,'yyyy')='$tahun' and a.f_nota_cancel='false'
                  and not a.i_nota isnull and a.i_area=b.i_area  and a.i_area=c.i_area and a.i_nota=c.i_nota and c.f_spb_cancel='false'
                  and c.i_product_group=d.i_product_group
                  ) as a
                  group by a.i_periode , a.e_area_island, a.e_product_groupname , a.i_product_group 
                  union all
                  /*=============================================End This Year=============================================*/
                  /*=============================================Start Prev Year===========================================*/

                  /*Hitung Rp.Nota*/
                  select to_char(a.d_nota,'yyyy') as i_periode, b.e_area_island,
                  0  as vnota, 0 as qnota, 0 as oa , sum(a.v_nota_netto) as prevvnota , 0 as prevqnota, 0 as prevoa, d.e_product_groupname , c.i_product_group
                  from tm_nota a, tr_area b , tm_spb c , tr_product_group d
                  where to_char(a.d_nota,'yyyy') = '$prevth' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and a.i_area=c.i_area and a.i_nota=c.i_nota and c.f_spb_cancel='false' and c.i_product_group=d.i_product_group
                  group by to_char(a.d_nota,'yyyy'), b.e_area_island,d.e_product_groupname , c.i_product_group
                  union all

                  /*Hitung Qty */
                  select to_char(a.d_nota,'yyyy') as i_periode, b.e_area_island, 
                  0 as vnota, 0 as qnota, 0 as oa , 0 as prevvnota ,sum(c.n_deliver) as prevqnota, 0 as prevoa, e.e_product_groupname , d.i_product_group 
                  from tm_nota a, tr_area b, tm_nota_item c , tm_spb d , tr_product_group e
                  where to_char(a.d_nota,'yyyy') = '$prevth' and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                  and a.i_sj=c.i_sj and a.i_area=c.i_area and a.i_area=d.i_area and a.i_nota=d.i_nota and d.f_spb_cancel='false'
                  and d.i_product_group=e.i_product_group
                  group by to_char(a.d_nota,'yyyy'), b.e_area_island, e.e_product_groupname , d.i_product_group 
                  union all

                  /*Hitung OA*/
                  select a.i_periode , a.e_area_island , 
                  0 as vnota , 0 as qnota , 0 as oa , 0 as prevvnota , 0 as prevqnota, count(oa) as prevoa , a.e_product_groupname , a.i_product_group from (
                  select distinct on (to_char(a.d_nota,'yyyy'),a.i_customer) a.i_customer as oa , to_char(a.d_nota,'yyyy') as i_periode , b.e_area_island, d.e_product_groupname , c.i_product_group
                  from tm_nota a , tr_area b , tm_spb c , tr_product_group d where to_char(a.d_nota,'yyyy')='$prevth' and a.f_nota_cancel='false'
                  and not a.i_nota isnull and a.i_area=b.i_area  and a.i_area=c.i_area and a.i_nota=c.i_nota and c.f_spb_cancel='false'
                  and c.i_product_group=d.i_product_group
                  ) as a
                  group by a.i_periode , a.e_area_island, a.e_product_groupname , a.i_product_group


                  ) as a
                  group by a.i_periode, a.e_area_island, a.e_product_groupname ,a.i_product_group
                  ) as a      
                  where a.i_product_group='$group'
                  group by a.e_area_island, a.e_product_groupname ,a.i_product_group
                  order by a.e_area_island";

            }
            $this->db->select($sql,FALSE);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                  return $query->result();
            }
      }
}
