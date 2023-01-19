<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function baca($dfrom,$dto,$dfromsebelumnya,$dtosebelumnya){
      $this->db->select("a.e_provinsi, a.i_area, a.e_area_name, a.e_customer_name, a.i_customer, a.e_customer_classname, count(ob) as ob, sum(a.vnota) as vnota, sum(a.qnota) as qnota, sum(a.oa) as oa, sum(a.prevvnota) as prevvnota, sum(a.prevqnota) as prevqnota, sum(a.prevoa) as prevoa from (

        SELECT f.e_provinsi, a.i_area, f.e_area_name, c.e_customer_name, a.i_customer, z.e_customer_classname, count(a.i_customer) as ob, sum(v_nota_netto) as vnota, 0 as qnota, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa 
         from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
         where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
         and f.i_area=a.i_area and a.i_customer=c.i_customer
         and c.i_customer_class = z.i_customer_class
         group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, f.e_provinsi, z.e_customer_classname
        union all
        SELECT f.e_provinsi, a.i_area, f.e_area_name, c.e_customer_name, a.i_customer, z.e_customer_classname, 0 as ob, 0 as vnota, sum(b.n_deliver) as qnota, 0 as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa 
         from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z
         where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
         and a.i_sj=b.i_sj and a.i_area=b.i_area and f.i_area=a.i_area and f.i_area=b.i_area and a.i_customer=c.i_customer
         and c.i_customer_class = z.i_customer_class
         group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, f.e_provinsi, z.e_customer_classname
        union all
        select b.e_provinsi, b.i_area, b.e_area_name, b.e_customer_name, b.i_customer, b.e_customer_classname, 0 as ob, 0 as vnota, 0 as qnota, count(b.i_customer) as oa, 0 as prevvnota, 0 as prevqnota, 0 as prevoa from (
        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_name, f.e_provinsi, c.e_customer_name
         from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
         where (a.d_nota>=to_date('$dfrom', 'dd-mm-yyyy') and a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
         and f.i_area=a.i_area and a.i_customer=c.i_customer and not a.i_nota isnull
         and c.i_customer_class = z.i_customer_class
         group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname
         ) as b
         group by b.e_customer_name, b.i_customer, b.i_area, b.e_area_name, b.e_provinsi, b.e_customer_classname
        /*--nah ini thnsebelumnya dibawah*/
        union all
        SELECT f.e_provinsi, a.i_area, f.e_area_name, c.e_customer_name, a.i_customer, z.e_customer_classname, count(a.i_customer) as ob, 0 as vnota, 0 as qnota, 0 as oa, sum(v_nota_netto) as prevvnota, 0 as prevqnota, 0 as prevoa 
         from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
         where (a.d_nota>=to_date('$dfromsebelumnya', 'dd-mm-yyyy') and a.d_nota <= to_date('$dtosebelumnya', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
         and f.i_area=a.i_area and a.i_customer=c.i_customer
         and c.i_customer_class = z.i_customer_class
         group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, f.e_provinsi, z.e_customer_classname
        union all
        SELECT f.e_provinsi, a.i_area, f.e_area_name, c.e_customer_name, a.i_customer, z.e_customer_classname, 0 as ob, 0 as vnota, 0 as qnota, 0 as oa, 0 as prevvnota, sum(b.n_deliver) as prevqnota, 0 as prevoa 
         from tm_nota a, tm_nota_item b, tr_customer c, tr_area f, tr_customer_class z
         where (a.d_nota>=to_date('$dfromsebelumnya', 'dd-mm-yyyy') and a.d_nota <= to_date('$dtosebelumnya', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
         and a.i_sj=b.i_sj and a.i_area=b.i_area and f.i_area=a.i_area and f.i_area=b.i_area and a.i_customer=c.i_customer
         and c.i_customer_class = z.i_customer_class
         group by to_char(a.d_nota, 'yyyy'), c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, f.e_provinsi, z.e_customer_classname
        union all
        select b.e_provinsi, b.i_area, b.e_area_name, b.e_customer_name, b.i_customer, b.e_customer_classname, 0 as ob, 0 as vnota, 0 as qnota, 0 as oa, 0 as prevvnota, 0 as prevqnota, count(b.i_customer) as prevoa from (
        select distinct on (to_char(a.d_nota, 'yyyymm'), a.i_customer) a.i_customer, z.e_customer_classname, a.i_area, f.e_area_name, f.e_provinsi, c.e_customer_name
         from tm_nota a, tr_customer c, tr_area f, tr_customer_class z
         where (a.d_nota>=to_date('$dfromsebelumnya', 'dd-mm-yyyy') and a.d_nota <= to_date('$dtosebelumnya', 'dd-mm-yyyy')) and a.f_nota_cancel='f'
         and f.i_area=a.i_area and a.i_customer=c.i_customer and not a.i_nota isnull
         and c.i_customer_class = z.i_customer_class
         group by c.e_customer_name, a.i_customer, a.i_area, f.e_area_name, a.d_nota, f.e_provinsi, z.e_customer_classname
         ) as b
         group by b.e_customer_name, b.i_customer, b.i_area, b.e_area_name, b.e_provinsi, b.e_customer_classname
        ) as a
        group by a.i_area, a.e_area_name, a.e_customer_name, a.i_customer, a.e_provinsi, a.e_customer_classname
        order by a.i_area, a.i_customer
        ",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		  return $query->result();
		}
    }
}
