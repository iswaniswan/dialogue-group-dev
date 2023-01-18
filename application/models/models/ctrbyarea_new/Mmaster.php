<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($dfrom,$dto,$iproductgroup){
        $pecah1     = explode('-', $dfrom);
        $tgl1       = $pecah1[0];
        $bln1       = $pecah1[1];
        $tahun1     = $pecah1[2];
        $thbl       = $tahun1.$bln1;
        $tahunprev1 = intval($tahun1) - 1;
        $thbln      = $tahun1."-".$bln1;
        $tsasih     = date('Y-m', strtotime('-24 month', strtotime($thbln))); //tambah tanggal sebanyak 6 bulan
        if($tsasih!=''){
            $smn = explode("-", $tsasih);
            $thn = $smn[0];
            $bln = $smn[1];
        }
        $taunsasih = $thn.$bln;

        $pecah2     = explode('-', $dto);
        $tgl2       = $pecah2[0];
        $bln2       = $pecah2[1];
        $tahun2     = $pecah2[2];
        $thblto     = $tahun2.$bln2;
        $tahunprev2 = intval($tahun2) - 1;

        if((intval($tahunprev2)%4!=0)&&($bln2=='02')&&($tgl2=='29')) $tgl2='28';
        $gabung1 = $tgl1.'-'.$bln1.'-'.$tahunprev1;
        $gabung2 = $tgl2.'-'.$bln2.'-'.$tahunprev2;

        if($iproductgroup == "NA"){
            $query = "SELECT SUM(c.oa) AS oa,SUM(c.oaprev) AS oaprev, SUM(c.ob) AS ob, SUM(c.vnota) AS vnota, 
            SUM(c.vnotaprev) AS vnotaprev, SUM(c.qty) AS qty, SUM(c.qtyprev) AS qtyprev, c.i_area, c.e_area_name, 
            c.e_area_island , c.e_provinsi FROM(
            /* HITUNG OA THN BERJALAN */
            SELECT SUM(b.oa) AS oa, 0 AS oaprev,SUM(b.ob) AS ob, SUM(b.vnota) AS vnota, 0 AS vnotaprev, SUM(b.qty) AS qty, 0 AS qtyprev, b.i_area,b.e_area_name, b.e_area_island, b.e_product_groupname , b.e_provinsi
            FROM (
            SELECT COUNT(a.oa) AS oa, 0 AS ob, 0 AS vnota, 0 AS qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname ,a.e_provinsi FROM (
            SELECT DISTINCT ON (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer AS oa, c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname, d.e_provinsi
            FROM tm_nota a, tr_customer c, tr_area d, tm_spb e, tr_product_group f
            WHERE (a.d_nota >= to_date('$dfrom','dd-mm-yyyy') AND a.d_nota <= to_date('$dto','dd-mm-yyyy'))
            /* nota */
            AND a.f_nota_cancel='f' AND NOT a.i_nota ISNULL AND NOT a.i_spb ISNULL
            /* nota ke customer */
            AND a.i_customer = c.i_customer AND a.i_area = c.i_area
            /* nota ke area */
            AND a.i_area = d.i_area
            /* nota ke spb */
            AND a.i_nota = e.i_nota AND a.i_area = e.i_area AND a.i_customer = e.i_customer
            /* spb */
            AND NOT e.i_spb ISNULL AND NOT e.i_nota ISNULL AND e.f_spb_cancel = 'f'
            /* spb ke product group */
            AND e.i_product_group = f.i_product_group
            /* spb ke customer */
            AND e.i_customer = c.i_customer AND e.i_area = c.i_area
            GROUP BY c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,to_char(a.d_nota,'yyyymm'),d.e_provinsi
            ) AS a
            GROUP BY a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi

            UNION ALL

            /* HITUNG PENJUALAN THN BERJALAN */
            SELECT 0 AS oa, 0 AS ob, SUM(a.vnota) AS vnota, SUM(a.qty) AS qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname ,a.e_provinsi FROM (
            SELECT SUM(b.n_deliver*b.v_unit_price)-(a.v_nota_discount*(SUM(b.n_deliver*b.v_unit_price)/ a.v_nota_gross)) AS vnota, SUM(b.n_deliver) AS qty,
            c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname , d.e_provinsi
            FROM tm_nota a, tm_nota_item b, tr_customer c, tr_area d, tm_spb e, tr_product_group f
            WHERE a.d_nota >= to_date('$dfrom','dd-mm-yyyy') AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area
            AND a.f_nota_cancel='false'
            AND a.i_customer = c.i_customer
            and c.i_area = d.i_area
            AND a.i_spb = e.i_spb
            AND e.i_product_group = f.i_product_group
            AND NOT a.i_nota ISNULL
            AND a.i_area = e.i_area
            GROUP BY a.i_area, a.v_nota_discount,a.v_nota_gross,c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,to_char (a.d_nota,'yyyy'),d.e_provinsi
            ) AS a
            GROUP BY a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi

            UNION ALL

            /* HITUNG OB */
            SELECT 0 AS oa, COUNT(a.ob) AS ob, 0 AS vnota, 0 AS qty, a.i_area,a.e_area_name, a.e_area_island, '' AS e_product_groupname,a.e_provinsi FROM (
            SELECT DISTINCT ON (x.ob) x.ob AS ob, x.i_area, x.e_area_name ,x.e_area_island , x.e_provinsi FROM(
            SELECT a.i_customer AS ob, a.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
            FROM tm_nota a , tr_area c
            WHERE to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            AND a.f_nota_cancel='false' 
            AND NOT a.i_nota ISNULL
            AND NOT a.i_spb ISNULL
            AND a.i_area=c.i_area 
            and c.f_area_real='t' 
            AND NOT a.i_nota ISNULL

            UNION ALL

            SELECT b.i_customer AS ob, b.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
            FROM tr_area c, tr_customer b 
            WHERE b.i_customer_status<>'4' 
            and b.f_customer_aktif='true' 
            and b.i_area=c.i_area 
            and c.f_area_real='t'
            and b.i_customer not in(
            SELECT a.i_customer
            FROM tm_nota a , tr_area c
            WHERE to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            AND a.f_nota_cancel='false' 
            AND NOT a.i_nota ISNULL
            AND NOT a.i_spb ISNULL
            AND a.i_area=c.i_area 
            and c.f_area_real='t' 
            AND NOT a.i_nota ISNULL
            )
            )AS x
            ORDER BY x.ob
            ) AS a
            GROUP BY a.i_area,a.e_area_name,a.e_area_island,a.e_provinsi
            /* HITUNG OB SAMPAI SINI */
            ) AS b
            GROUP BY b.i_area,b.e_area_name,b.e_area_island, b.e_product_groupname , b.e_provinsi
            /****************************************** tahun lalu *******************************************/
            UNION ALL 
            /****************************************** tahun lalu *******************************************/
            SELECT 0 AS oa, SUM(b.oa) AS oaprev, 0 AS ob, 0 AS vnota, SUM(b.vnota) AS vnotaprev , 0 AS qty, SUM(b.qty) AS qtyprev,  
            b.i_area , b.e_area_name , b.e_area_island,b.e_product_groupname,b.e_provinsi FROM (
            /* HITUNG OA TAHUN SEBELUMNYA */
            SELECT COUNT(a.oa) AS oa, 0 AS vnota, 0 AS qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname,a.e_provinsi FROM (
            SELECT DISTINCT ON (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer AS oa, c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname,d.e_provinsi
            FROM tm_nota a, tr_customer c, tr_area d, tm_spb e, tr_product_group f
            WHERE (a.d_nota >= to_date('$gabung1','dd-mm-yyyy') AND a.d_nota <= to_date('$gabung2','dd-mm-yyyy'))
            /* nota */
            AND a.f_nota_cancel='f' AND NOT a.i_nota ISNULL AND NOT a.i_spb ISNULL
            /* nota ke customer */
            AND a.i_customer = c.i_customer AND a.i_area = c.i_area
            /* nota ke area */
            AND a.i_area = d.i_area
            /* nota ke spb */
            AND a.i_nota = e.i_nota AND a.i_area = e.i_area AND a.i_customer = e.i_customer
            /* spb */
            AND NOT e.i_spb ISNULL AND NOT e.i_nota ISNULL AND e.f_spb_cancel = 'f'
            /* spb ke product group */
            AND e.i_product_group = f.i_product_group
            /* spb ke customer */
            AND e.i_customer = c.i_customer AND e.i_area = c.i_area
            GROUP BY c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,to_char (a.d_nota,'yyyymm'),d.e_provinsi
            ) AS a
            GROUP BY a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi

            UNION ALL

            SELECT 0 AS oaprev, SUM(a.vnota) AS vnotaprev, SUM(a.qty) AS qtyprev, a.i_area ,a.e_area_name , a.e_area_island, a.e_product_groupname,a.e_provinsi FROM (
            SELECT SUM(b.n_deliver*b.v_unit_price)-(a.v_nota_discount*(SUM(b.n_deliver*b.v_unit_price)/ a.v_nota_gross)) AS vnota, 
            SUM(b.n_deliver) AS qty, c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname,d.e_provinsi
            FROM tm_nota a, tm_nota_item b, tr_customer c, tr_area d, tm_spb e, tr_product_group f
            WHERE (a.d_nota >= to_date('$gabung1','dd-mm-yyyy') AND a.d_nota <= to_date('$gabung2','dd-mm-yyyy'))
            AND a.f_nota_cancel='false'           and a.i_sj=b.i_sj 
            and a.i_area=b.i_area AND a.i_customer = c.i_customer
            and c.i_area = d.i_area AND a.i_spb = e.i_spb AND e.i_product_group = f.i_product_group
            AND NOT a.i_nota ISNULL AND a.i_area = e.i_area
            GROUP BY a.i_area, a.v_nota_discount,a.v_nota_gross,c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,d.e_provinsi
            ) AS a
            GROUP BY a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi
            ) AS b
            GROUP BY b.i_area,b.e_area_name,b.e_area_island, b.e_product_groupname,b.e_provinsi
            /* END TAHUN SEBELUMNYA */
            ) AS c
            GROUP BY c.e_provinsi ,c.i_area,c.e_area_name,c.e_area_island
            ORDER BY c.e_area_island,c.e_provinsi ,c.i_area, c.e_area_name";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }elseif($iproductgroup == "MO"){
            $query = "SELECT sum(c.oa) as oa,sum(c.oaprev) as oaprev, sum(c.ob) as ob, sum(c.vnota) as vnota, 
            sum(c.vnotaprev) as vnotaprev, sum(c.qty) as qty, sum(c.qtyprev) as qtyprev, c.i_area, c.e_area_name, 
            c.e_area_island , c.e_provinsi
            from(
            SELECT sum(b.oa) as oa, 0 as oaprev,sum(b.ob) as ob, sum(b.vnota) as vnota, 0 as vnotaprev, sum(b.qty) as qty, 0 as qtyprev, b.i_area,b.e_area_name, b.e_area_island, b.e_product_groupname , b.e_provinsi
            from (
            SELECT count(a.oa) as oa, 0 as ob, 0 as vnota, 0 as qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname ,a.e_provinsi from (
            SELECT distinct on (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer as oa, c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname, d.e_provinsi
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
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi

            union all

            SELECT 0 as oa, 0 as ob, sum(a.vnota) as vnota, sum(a.qty) as qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname ,a.e_provinsi from (
            SELECT sum(b.n_deliver*b.v_unit_price)-(a.v_nota_discount*(sum(b.n_deliver*b.v_unit_price)/ a.v_nota_gross)) as vnota, sum(b.n_deliver) as qty,
            c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname , d.e_provinsi
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area d, tm_spb e, tr_product_group f
            where a.d_nota >= to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area
            and a.f_nota_cancel='false'
            and a.i_customer = c.i_customer
            and c.i_area = d.i_area
            and a.i_spb = e.i_spb
            and e.f_spb_consigment = 't'
            and e.i_product_group = f.i_product_group
            and not a.i_nota isnull
            and a.i_area = e.i_area
            group by a.i_area, a.v_nota_discount,a.v_nota_gross,c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,to_char (a.d_nota,'yyyy'),d.e_provinsi

            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi
            union all

            SELECT 0 as oa, count(a.ob) as ob, 0 as vnota, 0 as qty, a.i_area,a.e_area_name, a.e_area_island, '' as e_product_groupname,a.e_provinsi from (
            SELECT DISTINCT ON (x.ob) x.ob AS ob, x.i_area, x.e_area_name ,x.e_area_island , x.e_provinsi FROM(
            SELECT a.i_customer AS ob, a.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
            FROM tm_nota a , tr_area c, tm_spb x
            WHERE to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            AND a.f_nota_cancel='false' 
            AND NOT a.i_nota ISNULL
            AND NOT a.i_spb ISNULL
            AND a.i_area=c.i_area 
            and c.f_area_real='t' 
            and a.i_spb = x.i_spb 
            and a.i_area = x.i_area
            and a.i_nota = x.i_nota
            and x.f_spb_consigment = 't'
            AND NOT a.i_nota ISNULL

            UNION ALL

            SELECT b.i_customer AS ob, b.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi 
            FROM tr_area c, tr_customer b 
            WHERE b.i_customer_status<>'4' 
            and b.f_customer_aktif='true' 
            and b.i_area=c.i_area 
            and c.f_area_real='t'
            and b.i_customer not in(
            SELECT a.i_customer
            FROM tm_nota a , tr_area c, tm_spb x
            WHERE to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            AND a.f_nota_cancel='false' 
            AND NOT a.i_nota ISNULL
            AND NOT a.i_spb ISNULL
            AND a.i_area=c.i_area 
            and c.f_area_real='t' 
            and a.i_spb = x.i_spb 
            and a.i_area = x.i_area
            and a.i_nota = x.i_nota
            and x.f_spb_consigment = 't'
            AND NOT a.i_nota ISNULL
            )
            )AS x
            ORDER BY x.ob
            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island,a.e_provinsi

            ) as b
            group by b.i_area,b.e_area_name,b.e_area_island, b.e_product_groupname , b.e_provinsi
            /*------------------------------------------- tahun lalu -----------------------------------------------*/
            union all 
            /*------------------------------------------- tahun lalu -----------------------------------------------*/
            SELECT 0 as oa, sum(b.oa) as oaprev, 0 as ob, 0 as vnota, sum(b.vnota) as vnotaprev , 0 as qty, sum(b.qty) as qtyprev,  b.i_area , b.e_area_name , b.e_area_island,b.e_product_groupname,b.e_provinsi from (
            SELECT count(a.oa) as oa, 0 as vnota, 0 as qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname,a.e_provinsi from (
            SELECT distinct on (to_char(a.d_nota,'yyyymm') , a.i_customer)  a.i_customer as oa, c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname,d.e_provinsi
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
            group by c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,to_char (a.d_nota,'yyyymm'),d.e_provinsi
            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi

            union all

            SELECT 0 as oaprev, sum(a.vnota) as vnotaprev, sum(a.qty) as qtyprev, a.i_area ,a.e_area_name , a.e_area_island, a.e_product_groupname,a.e_provinsi from (
            SELECT sum(b.n_deliver*b.v_unit_price)-(a.v_nota_discount*(sum(b.n_deliver*b.v_unit_price)/ a.v_nota_gross)) as vnota, sum(b.n_deliver) as qty,
            c.i_area , d.e_area_name, d.e_area_island, f.e_product_groupname,d.e_provinsi
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area d, tm_spb e, tr_product_group f
            where (a.d_nota >= to_date('$gabung1','dd-mm-yyyy') and a.d_nota <= to_date('$gabung2','dd-mm-yyyy'))
            and a.f_nota_cancel='false'
            and a.i_sj=b.i_sj 
            and a.i_area=b.i_area
            and a.i_customer = c.i_customer
            and c.i_area = d.i_area
            and a.i_spb = e.i_spb
            and e.f_spb_consigment = 't'
            and e.i_product_group = f.i_product_group
            and not a.i_nota isnull
            and a.i_area = e.i_area
            group by a.i_area, a.v_nota_discount,a.v_nota_gross,c.i_area, a.i_customer,d.e_area_name,d.e_area_island, f.e_product_groupname,d.e_provinsi
            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi

            ) as b
            group by b.i_area,b.e_area_name,b.e_area_island, b.e_product_groupname,b.e_provinsi

            ) as c
            group by c.e_provinsi ,c.i_area,c.e_area_name,c.e_area_island
            order by c.e_area_island,c.e_provinsi ,c.i_area, c.e_area_name";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0){
                return $query->result();
            }

        }else{
            $query = "SELECT sum(c.oa) as oa,sum(c.oaprev) as oaprev, sum(c.ob) as ob, sum(c.vnota) as vnota, 
            sum(c.vnotaprev) as vnotaprev, sum(c.qty) as qty, sum(c.qtyprev) as qtyprev, c.i_area, c.e_area_name, 
            c.e_area_island , c.e_provinsi, c.i_product_group, c.e_product_groupname 
            from(
            SELECT sum(b.oa) as oa, 0 as oaprev,sum(b.ob) as ob, sum(b.vnota) as vnota, 0 as vnotaprev, sum(b.qty) as qty, 0 as qtyprev, b.i_area,b.e_area_name, b.e_area_island, b.e_product_groupname , b.e_provinsi, b.i_product_group
            from (
            SELECT count(b.i_customer) as oa, 0 as ob, 0 as vnota, 0 as qty, b.i_area,b.e_area_name, b.e_area_island, b.e_product_groupname ,b.e_provinsi, b.i_product_group from (
            SELECT distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_name, f.e_provinsi, c.e_customer_name, x.i_product_group, f.e_area_island, y.e_product_groupname
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x, tr_product_group y
            where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
            and f.i_area=a.i_area and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and x.i_product_group = y.i_product_group
            and x.i_product_group= '$iproductgroup'
            and x.f_spb_consigment = 'f'
            group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island, y.e_product_groupname, x.i_product_group
            ) as b
            group by b.i_area,b.e_area_name, b.e_area_island, b.e_product_groupname ,b.e_provinsi, b.i_product_group

            union all

            SELECT 0 as oa, 0 as ob, sum(a.vnota) as vnota, sum(a.qty) as qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname ,a.e_provinsi, a.i_product_group from (
            SELECT sum(x.vnota) as vnota, sum(x.qty) as qty, x.i_area, x.e_area_name, x.e_area_island, x.e_product_groupname, x.e_provinsi, x.i_product_group from(
            SELECT 0 as vnota, sum(b.n_deliver) as qty, c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z, tm_spb x, tr_product_group y
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
            and x.i_product_group = y.i_product_group
            and x.i_product_group = '$iproductgroup'
            group by c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group

            union all
            SELECT sum(a.v_nota_netto) as vnota, 0 as qty, c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x, tr_product_group y
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
            and x.i_product_group = y.i_product_group
            and x.i_product_group = '$iproductgroup'
            group by c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group
            ) as x
            group by x.i_area, x.e_area_name, x.e_area_island, x.e_product_groupname, x.e_provinsi, x.i_product_group

            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi,a.i_product_group
            union all

            SELECT 0 as oa, count(a.ob) as ob, 0 as vnota, 0 as qty, a.i_area,a.e_area_name, a.e_area_island, a.e_product_groupname as e_product_groupname,a.e_provinsi, a.i_product_group from (
            SELECT DISTINCT ON (x.ob) x.ob AS ob, x.i_area, x.e_area_name ,x.e_area_island , x.e_provinsi, x.e_product_groupname, x.i_product_group FROM(
            SELECT a.i_customer AS ob, a.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi , x.i_product_group, y.e_product_groupname
            FROM tm_nota a , tr_area c, tm_spb x, tr_product_group y
            WHERE to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            AND a.f_nota_cancel='false' 
            AND NOT a.i_nota ISNULL
            AND NOT a.i_spb ISNULL
            AND a.i_area=c.i_area 
            and c.f_area_real='t' 
            and a.i_spb = x.i_spb 
            and a.i_area = x.i_area
            and a.i_nota = x.i_nota
            and x.f_spb_consigment = 'f'
            AND NOT a.i_nota ISNULL
            and x.i_product_group = y.i_product_group
            and x.i_product_group = '$iproductgroup'

            UNION ALL

            SELECT b.i_customer AS ob, b.i_area, c.e_area_name ,c.e_area_island , c.e_provinsi , '$iproductgroup' as i_product_group, NULL as e_product_groupname
            FROM tr_area c, tr_customer b 
            WHERE b.i_customer_status<>'4' 
            and b.f_customer_aktif='true' 
            and b.i_area=c.i_area 
            and c.f_area_real='t'
            and b.i_customer not in(
            SELECT a.i_customer
            FROM tm_nota a , tr_area c, tm_spb x
            WHERE to_char(a.d_nota,'yyyymm')>='$taunsasih' and to_char(a.d_nota,'yyyymm') <='$thblto' 
            AND a.f_nota_cancel='false' 
            AND NOT a.i_nota ISNULL
            AND NOT a.i_spb ISNULL
            AND a.i_area=c.i_area 
            and c.f_area_real='t' 
            and a.i_spb = x.i_spb 
            and a.i_area = x.i_area
            and a.i_nota = x.i_nota
            and x.f_spb_consigment = 'f'
            AND NOT a.i_nota ISNULL
            and x.i_product_group = '$iproductgroup'
            )
            )AS x
            ORDER BY x.ob
            ) as a
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname, a.e_provinsi, a.i_product_group


            ) as b
            group by b.i_area,b.e_area_name,b.e_area_island, b.e_product_groupname , b.e_provinsi, b.i_product_group
            /*------------------------------------------- tahun lalu -----------------------------------------------*/
            union all 
            /*------------------------------------------- tahun lalu -----------------------------------------------*/
            SELECT 0 as oa, sum(b.oa) as oaprev, 0 as ob, 0 as vnota, sum(b.vnota) as vnotaprev , 0 as qty, sum(b.qty) as qtyprev,  b.i_area , b.e_area_name , b.e_area_island,b.e_product_groupname,b.e_provinsi, b.i_product_group from (
            SELECT count(b.i_customer) as oa, 0 as vnota, 0 as qty, b.i_area,b.e_area_name, b.e_area_island, b.e_product_groupname ,b.e_provinsi, b.i_product_group from (
            SELECT distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_name, f.e_provinsi, c.e_customer_name, x.i_product_group, f.e_area_island, y.e_product_groupname
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x, tr_product_group y
            where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
            and f.i_area=a.i_area and a.i_customer=c.i_customer
            and c.i_customer_class = z.i_customer_class
            and a.i_nota = x.i_nota
            and a.i_spb = x.i_spb
            and a.i_area = x.i_area
            and x.i_product_group = y.i_product_group
            and x.i_product_group= '$iproductgroup'
            and x.f_spb_consigment = 'f'
            group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island, y.e_product_groupname, x.i_product_group
            ) as b
            group by b.i_area,b.e_area_name, b.e_area_island, b.e_product_groupname ,b.e_provinsi, b.i_product_group

            union all

            SELECT 0 as oaprev, sum(a.vnota) as vnotaprev, sum(a.qty) as qtyprev, a.i_area ,a.e_area_name , a.e_area_island, a.e_product_groupname,a.e_provinsi, a.i_product_group from (
            SELECT sum(x.vnota) as vnota, sum(x.qty) as qty, x.i_area, x.e_area_name, x.e_area_island, x.e_product_groupname, x.e_provinsi, x.i_product_group from(
            SELECT 0 as vnota, sum(b.n_deliver) as qty, c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group
            from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z, tm_spb x, tr_product_group y
            where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')) 
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
            and x.i_product_group = y.i_product_group
            and x.i_product_group = '$iproductgroup'
            group by c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group

            union all
            SELECT sum(a.v_nota_netto) as vnota, 0 as qty, c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group
            from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x, tr_product_group y
            where (a.d_nota>=to_date('$gabung1', 'dd-mm-yyyy') 
            and a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')) 
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
            and x.i_product_group = y.i_product_group
            and x.i_product_group = '$iproductgroup'
            group by c.i_area, f.e_area_name, f.e_area_island, y.e_product_groupname, f.e_provinsi, x.i_product_group
            ) as x
            group by x.i_area, x.e_area_name, x.e_area_island, x.e_product_groupname, x.e_provinsi, x.i_product_group                                ) as a
            group by a.i_area,a.e_area_name,a.e_area_island, a.e_product_groupname,a.e_provinsi,a.i_product_group

            ) as b
            group by b.i_area,b.e_area_name,b.e_area_island, b.e_product_groupname,b.e_provinsi,b.i_product_group

            ) as c where c.i_product_group = '$iproductgroup'
            group by c.e_provinsi ,c.i_area,c.e_area_name,c.e_area_island, c.i_product_group, c.e_product_groupname 
            order by c.e_area_island,c.e_provinsi ,c.i_area, c.e_area_name";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }
        $this->load->database();
    }
}
