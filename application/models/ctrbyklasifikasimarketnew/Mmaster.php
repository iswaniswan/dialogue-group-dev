<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

        function baca($dfrom,$dto,$group){
            $tmp=explode("-",$dfrom);
            $hr=$tmp[0];
            $bl=$tmp[1];
            $th=$tmp[2]-1;
            $thnow=$tmp[2];
            $thbl=$thnow.$bl;
            $dfromprev=$hr."-".$bl."-".$th;
          
            $tehabeel=$thnow."-".$bl;
            $tsasih = date('Y-m', strtotime('-24 month', strtotime($tehabeel))); //tambah tanggal sebanyak 6 bulan
            if($tsasih!=''){
                $smn = explode("-", $tsasih);
                $thn = $smn[0];
                $bln = $smn[1];
            }
            $taunsasih = $hr."-".$bln."-".$thn;
            $tmp=explode("-",$dto);
            $hr=$tmp[0];
            $bl=$tmp[1];
            $th=$tmp[2]-1;
            $thnya=$tmp[2];
            $thblto=$thnya.$bl;
            if((intval($th)%4!=0)&&($bl=='02')&&($hr=='29')) $hr='28';
                $dtoprev=$hr."-".$bl."-".$th;
    
            if ($group=="NA") {
                $this->db->select(" x.i_customer_class, x.e_customer_classname, SUM(x.ob) as ob, SUM(x.oa) as oa, SUM(x.vnota) as vnota, SUM(x.qnota) as qnota, 
                    SUM(x.oaprev) as oaprev, SUM(x.vnotaprev) as vnotaprev, SUM(x.qnotaprev) as qnotaprev from(
                    SELECT a.i_customer_class, a.e_customer_classname, SUM(ob) AS ob, 0 AS oa, SUM(a.vnota) AS vnota, SUM(qnota) AS qnota, 
                    0 AS oaprev, SUM(vnotaprev) AS vnotaprev, SUM(qnotaprev) AS qnotaprev 
                    FROM (
                            SELECT * FROM f_sales_report_klasifikasi_new('$dfrom', '$dto', '$dfromprev', '$dtoprev', '$taunsasih') 
                            GROUP BY grup, i_customer_class,
                            e_customer_classname, i_product_group, ob, oa, vnota, qnota, oaprev, qnotaprev, vnotaprev ORDER BY grup
                        ) AS a 
                        GROUP BY a.i_customer_class, a.e_customer_classname 
                    
                    UNION ALL
                    
                    SELECT b.i_customer_class, b.e_customer_classname, 0 as ob, count(b.i_customer) as oa, 0 as vnota, 0 as qnota, 0 as prevoa, 0 as vnotaprev, 0 as qnotaprev from (
                        SELECT DISTINCT ON (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer,c.i_customer_class, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                        WHERE (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) AND a.f_nota_cancel='f'
                        AND f.i_area=a.i_area AND a.i_customer=c.i_customer
                        AND c.i_customer_class = z.i_customer_class
                        AND a.i_nota = x.i_nota
                        AND a.i_spb = x.i_spb
                        AND a.i_area = x.i_area
                        GROUP BY c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island, c.i_customer_class
                    ) as b
                    GROUP BY b.e_customer_classname, b.i_customer_class
                    
                    UNION ALL
                    
                    SELECT b.i_customer_class, b.e_customer_classname, 0 AS ob, 0 AS oa, 0 AS vnota, 0 AS qnota, count(b.i_customer) AS prevoa, 0 AS vnotaprev, 0 as qnotaprev from (
                        SELECT distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer,c.i_customer_class, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                        FROM tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                        WHERE (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) AND a.f_nota_cancel='f'
                        AND f.i_area=a.i_area AND a.i_customer=c.i_customer
                        AND c.i_customer_class = z.i_customer_class
                        AND a.i_nota = x.i_nota
                        AND a.i_spb = x.i_spb
                        AND a.i_area = x.i_area
                        GROUP BY c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island, c.i_customer_class
                    ) as b
                    GROUP BY b.e_customer_classname, b.i_customer_class
                ) as x
                GROUP BY x.e_customer_classname, x.i_customer_class",false);            
            }else{
                    $this->db->select(" x.i_customer_class, x.e_customer_classname, sum(x.ob) as ob, sum(x.oa) as oa, sum(x.vnota) as vnota, sum(x.qnota) as qnota, 
                sum(x.oaprev) as oaprev, sum(x.vnotaprev) as vnotaprev, sum(x.qnotaprev) as qnotaprev, '$group' as i_product_group  from(
                SELECT a.i_customer_class, a.e_customer_classname, sum(ob) as ob, 0 as oa, sum(a.vnota) as vnota, sum(qnota) as qnota, 
                0 as oaprev, sum(vnotaprev) as vnotaprev, sum(qnotaprev) as qnotaprev 
                from (
                select * from f_sales_report_klasifikasi_new('$dfrom','$dto','$dfromprev','$dtoprev','$taunsasih','$group') where i_product_group = '$group' order by e_customer_classname
                ) as a group by a.i_customer_class, 
                 a.e_customer_classname 
                union all
                select b.i_customer_class, b.e_customer_classname, 0 as ob, count(b.i_customer) as oa, 0 as vnota, 0 as qnota, 0 as prevoa, 0 as vnotaprev, 0 as qnotaprev from (
                select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer,c.i_customer_class, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                and f.i_area=a.i_area and a.i_customer=c.i_customer
                and c.i_customer_class = z.i_customer_class
                and a.i_nota = x.i_nota
                and a.i_spb = x.i_spb
                and a.i_area = x.i_area
                and x.i_product_group = '$group'
                and x.f_spb_consigment = 'f'
                group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island, c.i_customer_class
                ) as b
                group by b.e_customer_classname, b.i_customer_class
                union all
                select b.i_customer_class, b.e_customer_classname, 0 as ob, 0 as oa, 0 as vnota, 0 as qnota, count(b.i_customer) as prevoa, 0 as vnotaprev, 0 as qnotaprev from (
                select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer,c.i_customer_class, z.e_customer_classname, a.i_area, f.e_area_island, f.e_area_name, f.e_provinsi, c.e_customer_name
                from tm_nota a, tr_customer c, tr_area f, tr_customer_class z, tm_spb x
                where (a.d_nota>=to_date('$dfromprev', 'dd-mm-yyyy') and a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
                and f.i_area=a.i_area and a.i_customer=c.i_customer
                and c.i_customer_class = z.i_customer_class
                and a.i_nota = x.i_nota
                and a.i_spb = x.i_spb
                and a.i_area = x.i_area
                and x.i_product_group = '$group'
                and x.f_spb_consigment = 'f'
                group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname, f.e_area_island, c.i_customer_class
                ) as b
                group by b.e_customer_classname, b.i_customer_class
                ) as x
                group by x.e_customer_classname, x.i_customer_class",false);
            }
            $query = $this->db->get();
            if ($query->num_rows() > 0){
              return $query->result();
            }
    
        }
        function bacaob($dfrom,$dto,$group)
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
                  return $query->result();
              }
        }
}