<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function baca($dfrom,$dto,$group){
		$pecah1       = explode('-', $dfrom);
		$tgl1       = $pecah1[0];
		$bln1       = $pecah1[1];
		$tahun1     = $pecah1[2];
		$thbl		= $tahun1.$bln1;
		$tahunprev1 = intval($tahun1) - 1;
		$thbln = $tahun1."-".$bln1;
                $tsasih = date('Y-m', strtotime('-24 month', strtotime($thbln))); //tambah tanggal sebanyak 6 bulan
                if($tsasih!=''){
                	$smn = explode("-", $tsasih);
                	$thn = $smn[0];
                	$bln = $smn[1];
                }
                $taunsasih = $thn.$bln;

                $pecah2       = explode('-', $dto);
                $tgl2       = $pecah2[0];
                $bln2       = $pecah2[1];
                $tahun2     = $pecah2[2];
                $thblto 	= $tahun2.$bln2;
                $tahunprev2 = intval($tahun2) - 1;

                if((intval($tahunprev2)%4!=0)&&($bln2=='02')&&($tgl2=='29')) $tgl2='28';

                $gabung1 = $tgl1.'-'.$bln1.'-'.$tahunprev1;
                $gabung2 = $tgl2.'-'.$bln2.'-'.$tahunprev2;
                $this->load->database('101');
                if($group=='NA'){
                	$sql=" a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa from (
                	select a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa from (
                	/*============================== Start This Year============================================*/
                	/*-- Hitung OB Group*/
                	select a.e_area_island, 0 as vnota, 0 as qnota, count(ob) as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa from (
                	select distinct on (x.ob) x.ob as ob, x.i_area, x.e_area_name ,x.e_area_island , x.e_provinsi from(
                	select a.i_customer as ob, a.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
                	from tm_nota a , tr_area c
                	where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
                	and a.f_nota_cancel='false' 
                	and not a.i_nota isnull
                	and not a.i_spb isnull
                	and a.i_area=c.i_area 
                	and c.f_area_real='t' 
                	and not a.i_nota isnull
                	union all
                	select b.i_customer as ob, b.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
                	from tr_area c, tr_customer b 
                	where b.i_customer_status<>'4' 
                	and b.f_customer_aktif='true' 
                	and b.i_area=c.i_area 
                	and c.f_area_real='t'
                	)
                	as x
                	order by x.ob
                	) as a
                	group by a.e_area_island, a.ob
                	union all

                	/*--//Hitung Rp.Nota*/
                	select b.e_area_island, sum(a.v_nota_netto) as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area b
                	where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                	group by b.e_area_island
                	union all

                	/*--//Hitung Qty */
                	select b.e_area_island, 0 as vnota, sum(c.n_deliver) as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area b, tm_nota_item c
                	where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                	and a.i_sj=c.i_sj and a.i_area=c.i_area
                	group by b.e_area_island
                	union all

                	/*--//Hitung OA*/
                        select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, count(b.i_customer) as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa from (
                        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
                        where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                        and f.i_area=a.i_area and a.i_customer=c.i_customer and not a.i_nota isnull
                        and c.i_customer_class = z.i_customer_class
                        group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island
                        ) as b
                        group by b.e_area_island
                	union all

                	/*--=============================================End This Year=============================================*/
                	/*--=============================================Start Prev Year===========================================*/

                	/*--//Hitung Rp.Nota*/
                	select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, sum(a.v_nota_netto) as prevvnota, 0 as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area b
                	where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                	group by b.e_area_island
                	union all

                	/*--//Hitung Qty */
                	select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, sum(c.n_deliver) as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area b, tm_nota_item c
                	where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
                	and a.i_sj=c.i_sj and a.i_area=c.i_area
                	group by b.e_area_island
                	union all

                	/*--//Hitung OA*/
                        select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, count(b.i_customer) as prevoa from (
                        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
                        where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                        and f.i_area=a.i_area and a.i_customer=c.i_customer
                        and c.i_customer_class = z.i_customer_class and not a.i_nota isnull
                        group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island
                        ) as b
                        group by b.e_area_island


                	) as a
                	group by a.e_area_island
                	) as a 
                	group by a.e_area_island
                	order by a.e_area_island";
                }elseif($group=='MO'){
                	$sql =" a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa from (
                	select a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa from (
                                /*--============================== Start This Year============================================*/
                	/*-- Hitung OB Group*/
                	select a.e_area_island, 0 as vnota, 0 as qnota, count(ob) as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa from (
                	select distinct on (x.ob) x.ob as ob, x.i_area, x.e_area_name ,x.e_area_island , x.e_provinsi from(
                	select a.i_customer as ob, a.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
                	from tm_nota a , tr_area c, tm_spb d
                	where to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
                	and a.f_nota_cancel='false' 
                	and a.i_area=c.i_area 
                	and c.f_area_real='t' 
                	and not a.i_nota isnull
                	and a.i_spb = d.i_spb 
                	and a.i_area = 'PB'
                	and a.i_area = d.i_area
                	and a.i_customer = d.i_customer
                	and not d.i_spb isnull
                	and not d.i_nota isnull
                	and d.f_spb_consigment = 't'
                	)
                	as x
                	order by x.ob
                	) as a
                	group by a.e_area_island, a.ob
                	union all

                	/*--//Hitung Rp.Nota*/
                	select c.e_area_island, sum(a.v_nota_netto) as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area c, tm_spb d
                	where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
                	and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
                	and a.f_nota_cancel='false' 
                	and a.i_area=c.i_area 
                	and c.f_area_real='t' 
                	and not a.i_nota isnull
                	and a.i_spb = d.i_spb 
                	and a.i_area = 'PB'
                	and a.i_area = d.i_area
                	and a.i_customer = d.i_customer
                	and not d.i_spb isnull
                	and not d.i_nota isnull
                	and d.f_spb_consigment = 't'
                	group by c.e_area_island
                	union all

                	/*--//Hitung Qty */
                	select c.e_area_island, 0 as vnota, sum(b.n_deliver) as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area c, tm_nota_item b, tm_spb d
                	where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
                	and a.f_nota_cancel='false' 
                	and a.i_area=c.i_area 
                	and c.f_area_real='t' 
                	and not a.i_nota isnull
                	and a.i_spb = d.i_spb 
                	and a.i_area = 'PB'
                	and a.i_area = d.i_area
                	and a.i_customer = d.i_customer
                	and not d.i_spb isnull
                	and not d.i_nota isnull
                	and d.f_spb_consigment = 't'
                	and a.i_sj = b.i_sj
                	and a.i_area = b.i_area
                	group by c.e_area_island
                	union all

                	/*--//Hitung OA*/
                	select a.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, count(a.oa) as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa from (
                	select distinct on (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer as oa, c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname, d.e_provinsi
                	from tm_nota a, tr_customer c, tr_area d, tm_spb e, tr_product_group f
                	where (a.d_nota >= to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy'))
                	/*--nota*/
                	and a.f_nota_cancel='f'
                	and not a.i_nota isnull
                	and not a.i_spb isnull
                	/*--nota ke customer*/
                	and a.i_customer = c.i_customer
                	and a.i_area = c.i_area
                	/*--nota ke area*/
                	and a.i_area = d.i_area
                	/*--nota ke spb*/
                	and a.i_nota = e.i_nota
                	and a.i_area = e.i_area
                	and a.i_customer = e.i_customer
                	/*--spb*/
                	and not e.i_spb isnull
                	and not e.i_nota isnull
                	and e.f_spb_cancel = 'f'
                	and e.f_spb_consigment = 't'
                	/*--spb ke product group*/
                	and e.i_product_group = f.i_product_group
                	/*--customer*/
                	and c.i_customer_status<>'4'
                	and c.f_customer_aktif='t'
                	/*--spb ke customer*/
                	and e.i_customer = c.i_customer
                	and e.i_area = c.i_area
                	group by c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,to_char(a.d_nota,'yyyymm'),d.e_provinsi
                	) as a
                	group by a.e_area_island
                	union all

                	/*--=============================================End This Year=============================================*/
                	/*--=============================================Start Prev Year===========================================*/

                	/*--//Hitung Rp.Nota*/
                	select c.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, sum(a.v_nota_netto) as prevvnota, 0 as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area c, tm_spb d
                	where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) 
                	and a.f_nota_cancel='false' 
                	and a.i_area=c.i_area 
                	and c.f_area_real='t' 
                	and not a.i_nota isnull
                	and a.i_spb = d.i_spb 
                	and a.i_area = 'PB'
                	and a.i_area = d.i_area
                	and a.i_customer = d.i_customer
                	and not d.i_spb isnull
                	and not d.i_nota isnull
                	and d.f_spb_consigment = 't'
                	group by c.e_area_island
                	union all

                	/*--//Hitung Qty */
                	select c.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, sum(b.n_deliver) as prevqnota, 0 as prevoa
                	from tm_nota a, tr_area c, tm_nota_item b, tm_spb d
                	where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) 
                	and a.f_nota_cancel='false' 
                	and a.i_area=c.i_area 
                	and c.f_area_real='t' 
                	and not a.i_nota isnull
                	and a.i_spb = d.i_spb 
                	and a.i_area = 'PB'
                	and a.i_area = d.i_area
                	and a.i_customer = d.i_customer
                	and not d.i_spb isnull
                	and not d.i_nota isnull
                	and d.f_spb_consigment = 't'
                	and a.i_sj = b.i_sj
                	and a.i_area = b.i_area
                	group by c.e_area_island
                	union all

                	/*--//Hitung OA*/
                	select a.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, count(oa) as prevoa from (
                	select distinct on (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer as oa, c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname,d.e_provinsi
                	from tm_nota a, tr_customer c, tr_area d, tm_spb e, tr_product_group f
                	where (a.d_nota >= to_date('$gabung1','dd-mm-yyyy') and a.d_nota <= to_date('$gabung2','dd-mm-yyyy'))
                	/*--nota*/
                	and a.f_nota_cancel='f'
                	and not a.i_nota isnull
                	and not a.i_spb isnull
                	/*--nota ke customer*/
                	and a.i_customer = c.i_customer
                	and a.i_area = c.i_area
                	/*--nota ke area*/
                	and a.i_area = d.i_area
                	/*--nota ke spb*/
                	and a.i_nota = e.i_nota
                	and a.i_area = e.i_area
                	and a.i_customer = e.i_customer
                	/*--spb*/
                	and not e.i_spb isnull
                	and not e.i_nota isnull
                	and e.f_spb_cancel = 'f'
                	and e.f_spb_consigment = 't'
                	/*--spb ke product group*/
                	and e.i_product_group = f.i_product_group
                	/*--customer*/
                	and c.i_customer_status<>'4'
                	and c.f_customer_aktif='t'
                	/*--spb ke customer*/
                	and e.i_customer = c.i_customer
                	and e.i_area = c.i_area
                	group by c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,to_char(a.d_nota,'yyyymm'),d.e_provinsi
                	) as a
                	group by a.e_area_island


                	) as a
                	group by a.e_area_island
                	) as a 
                	group by a.e_area_island
                	order by a.e_area_island";

                }elseif($group=='01'){
                	$sql =" a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa, a.i_product_group from (
                        select a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa, a.i_product_group from (
                        /*--============================== Start This Year============================================*/
                        /*-- Hitung OB Group*/
                        select a.e_area_island, 0 as vnota, 0 as qnota, count(ob) as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, a.i_product_group from (
                        select distinct on (x.ob) x.ob as ob, x.i_area, x.e_area_name, x.e_area_island, x.e_provinsi, x.i_product_group from(
                        select a.i_customer as ob, a.i_area, c.e_area_name, c.e_area_island, c.e_provinsi, d.i_product_group
                        from tm_nota a, tr_area c, tm_spb d, tr_product_group e
                        where to_char(a.d_nota, 'yyyymm')>='$taunsasih' and to_char(a.d_nota, 'yyyymm') <='$thblto' 
                        and a.f_nota_cancel='false' and a.i_area=c.i_area and c.f_area_real='t' and not a.i_nota isnull
                        and a.i_spb = d.i_spb and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull and d.i_product_group='01'
                        and d.f_spb_consigment = 'f' and d.i_product_group=e.i_product_group
                        union all
                        select b.i_customer as ob, b.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi , '01' as i_product_group
                        from tr_area c, tr_customer b, tr_product_group d 
                        where b.i_customer_status<>'4' and b.f_customer_aktif='true' and b.i_area=c.i_area and c.f_area_real='t'
                        and d.i_product_group='01' and not b.i_customer like 'PB%'
                        )
                        as x
                        order by x.ob
                        ) as a
                        group by a.e_area_island, a.ob,a.i_product_group
                        union all

                        /*--//Hitung Rp.Nota*/
                        select c.e_area_island, sum(a.v_nota_netto) as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_spb d
                        where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
                        and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung Qty */
                        select c.e_area_island, 0 as vnota, sum(b.n_deliver) as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_nota_item b, tm_spb d
                        where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        and a.i_sj = b.i_sj
                        and a.i_area = b.i_area
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung OA*/
                        select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, count(b.i_customer) as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, '01' as i_product_group from (
                        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                        where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                        and f.i_area=a.i_area and a.i_customer=c.i_customer
                        and c.i_customer_class = z.i_customer_class
                        and a.i_nota = x.i_nota
                        and a.i_spb = x.i_spb
                        and a.i_area = x.i_area
                        and x.i_product_group= '01'
                        and x.f_spb_consigment = 'f'
                        group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island
                        ) as b
                        group by b.e_area_island
                        union all

                        /*--=============================================End This Year=============================================*/
                        /*--=============================================Start Prev Year===========================================*/

                        /*--//Hitung Rp.Nota*/
                        select c.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, sum(a.v_nota_netto) as prevvnota, 0 as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_spb d
                        where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung Qty */
                        select c.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, sum(b.n_deliver) as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_nota_item b, tm_spb d
                        where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        and a.i_sj = b.i_sj
                        and a.i_area = b.i_area
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung OA*/
                        select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, count(b.i_customer) as prevoa, '01' as i_product_group from (
                        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                        where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                        and f.i_area=a.i_area and a.i_customer=c.i_customer
                        and c.i_customer_class = z.i_customer_class
                        and a.i_nota = x.i_nota
                        and a.i_spb = x.i_spb
                        and a.i_area = x.i_area
                        and x.i_product_group= '01'
                        and x.f_spb_consigment = 'f'
                        group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island
                        ) as b
                        group by b.e_area_island


                        ) as a
                        group by a.e_area_island, a.i_product_group
                        ) as a
                        where a.i_product_group = '01'
                        group by a.e_area_island, a.i_product_group
                        order by a.e_area_island
                        ";
                }else{
                        $sql = " a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa, a.i_product_group from (
                        select a.e_area_island, sum(a.vnota) as vnota, sum(qnota) as qnota, sum(a.ob) as ob, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa, a.i_product_group from (
                        /*--============================== Start This Year============================================*/
                        /*-- Hitung OB Group*/
                        select a.e_area_island, 0 as vnota, 0 as qnota, count(ob) as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, a.i_product_group from (
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
                        group by a.e_area_island, a.ob,a.i_product_group
                        union all

                        /*--//Hitung Rp.Nota*/
                        select c.e_area_island, sum(a.v_nota_netto) as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_spb d
                        where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') 
                        and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung Qty */
                        select c.e_area_island, 0 as vnota, sum(b.n_deliver) as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_nota_item b, tm_spb d
                        where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <=to_date('$dto', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        and a.i_sj = b.i_sj
                        and a.i_area = b.i_area
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung OA*/
                        select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, count(b.i_customer) as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa, '$group' as i_product_group from (
                        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                        where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                        and f.i_area=a.i_area and a.i_customer=c.i_customer
                        and c.i_customer_class = z.i_customer_class
                        and a.i_nota = x.i_nota
                        and a.i_spb = x.i_spb
                        and a.i_area = x.i_area
                        and x.i_product_group= '$group'
                        and x.f_spb_consigment = 'f'
                        group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island
                        ) as b
                        group by b.e_area_island
                        union all

                        /*--=============================================End This Year=============================================*/
                        /*--=============================================Start Prev Year===========================================*/

                        /*--//Hitung Rp.Nota*/
                        select c.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, sum(a.v_nota_netto) as prevvnota, 0 as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_spb d
                        where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung Qty */
                        select c.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, sum(b.n_deliver) as prevqnota, 0 as prevoa, d.i_product_group
                        from tm_nota a, tr_area c, tm_nota_item b, tm_spb d
                        where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <=to_date('$gabung2', 'dd-mm-yyyy')) 
                        and a.f_nota_cancel='false' 
                        and a.i_area=c.i_area 
                        and c.f_area_real='t' 
                        and not a.i_nota isnull
                        and a.i_spb = d.i_spb 
                        and a.i_area <> 'PB'
                        and a.i_area = d.i_area
                        and a.i_customer = d.i_customer
                        and not d.i_spb isnull
                        and not d.i_nota isnull
                        and d.f_spb_consigment = 'f'
                        and a.i_sj = b.i_sj
                        and a.i_area = b.i_area
                        group by c.e_area_island, d.i_product_group
                        union all

                        /*--//Hitung OA*/
                        select b.e_area_island, 0 as vnota, 0 as qnota, 0 as ob, 0 as oa, 0 as prevvnota, 0 as prevqnota, count(b.i_customer) as prevoa, '$group' as i_product_group from (
                        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                        where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                        and f.i_area=a.i_area and a.i_customer=c.i_customer
                        and c.i_customer_class = z.i_customer_class
                        and a.i_nota = x.i_nota
                        and a.i_spb = x.i_spb
                        and a.i_area = x.i_area
                        and x.i_product_group= '$group'
                        and x.f_spb_consigment = 'f'
                        group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island
                        ) as b
                        group by b.e_area_island


                        ) as a
                        group by a.e_area_island, a.i_product_group
                        ) as a
                        where a.i_product_group = '$group'
                        group by a.e_area_island, a.i_product_group
                        order by a.e_area_island
                        ";
                }
        $this->db->select($sql,FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
        	return $query->result();
        }
        $this->load->database();
    }

    function bacaisland($dfrom,$dto){
             $tmp=explode("-",$dfrom);
             $hr=$tmp[0];
             $bl=$tmp[1];
             $th=$tmp[2]-1;
             $thnow=$tmp[2];
             $dfromprev=$hr."-".$bl."-".$th;

             $tmp=explode("-",$dto);
             $hr=$tmp[0];
             $bl=$tmp[1];
             $th=$tmp[2]-1;
             $dtoprev=$hr."-".$bl."-".$th;

             $this->db->select(" a.e_area_island from (
              select b.e_area_island from tm_nota a, tr_area b
              where (a.d_nota <=to_date('$dto','dd-mm-yyyy')) and a.f_nota_cancel='f' and not a.i_nota isnull and a.i_area=b.i_area
              group by b.e_area_island
              ) as a
              group by a.e_area_island
              order by a.e_area_island
              ",false);
             $query = $this->db->get();
             if ($query->num_rows() > 0){
              return $query->result();
        }
    }
}