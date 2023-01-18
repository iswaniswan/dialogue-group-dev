<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom,$dto){
       return $this->db->query("select area, grup, sum(atu) as atu, sum(siji) as siji, sum(ua) as ua, sum(loro) as loro, sum(iga) as iga, 
                                 sum(telu) as telu, sum(ampa) as ampa, sum(papat) as papat, sum(ima) as ima, sum(limo) as limo, sum(anam) as anam, 
                                 sum(anem) as anem, sum(uju) as uju, sum(pitu) as pitu, sum(apan) as apan, sum(lapan) as lapan, sum(alan) as alan, 
                                 sum(sanga) as sanga from(
                                 select area, grup, atu, 0 as siji, ua, 0 as loro, iga, 0 as telu, ampa, 0 as papat, ima, 0 as limo, anam, 0 as anem, uju, 
                                 0 as pitu, apan, 0 as lapan, alan, 0 as sanga from(
                                 select area, grup, sum(atu) as atu, sum(ua) as ua, sum(iga) as iga, sum(ampa) as ampa, sum(ima) as ima, sum(anam) as anam, 
                                 sum(uju) as uju, sum(apan) as apan, sum(alan) as alan from crosstab (
                                 'SELECT a.i_area,f.i_product_group,c.i_customer_class, sum(d.n_deliver*d.v_unit_price)  AS v_gross
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d, tr_product e, tr_product_type f
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND 
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND 
                                 b.f_spb_consigment = false and a.i_sj=d.i_sj and a.i_area=d.i_area and d.i_product=e.i_product 
                                 AND e.i_product_type=f.i_product_type AND f.i_product_group = ''00''
                                 GROUP BY a.i_area, f.i_product_group, c.i_customer_class
                                 UNION ALL 
                                 SELECT a.i_area,f.i_product_group,c.i_customer_class, sum(d.n_deliver*d.v_unit_price)  AS v_gross
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d, tr_product e, tr_product_type f
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND 
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND 
                                 b.f_spb_consigment = false and a.i_sj=d.i_sj and a.i_area=d.i_area and d.i_product=e.i_product 
                                 AND e.i_product_type=f.i_product_type AND f.i_product_group = ''01''
                                 GROUP BY a.i_area, f.i_product_group, c.i_customer_class
                                 UNION ALL 
                                 SELECT a.i_area,f.i_product_group,c.i_customer_class, sum(d.n_deliver*d.v_unit_price)  AS v_gross
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d, tr_product e, tr_product_type f
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND 
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND 
                                 b.f_spb_consigment = false and a.i_sj=d.i_sj and a.i_area=d.i_area and d.i_product=e.i_product 
                                 AND e.i_product_type=f.i_product_type AND f.i_product_group = ''02''
                                 GROUP BY a.i_area, f.i_product_group, c.i_customer_class
                                 UNION ALL 
                                 SELECT a.i_area,''PB'' as i_product_group,c.i_customer_class, sum(d.n_deliver*d.v_unit_price)  AS v_gross
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND 
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND b.f_spb_consigment = true
                                 and a.i_sj=d.i_sj and a.i_area=d.i_area
                                 GROUP BY a.i_area, i_product_group, c.i_customer_class','select * from generate_series(1,9)')
                                 as
                                 (area text, grup text, atu bigint, ua bigint, iga bigint, ampa bigint, ima bigint, anam bigint,
                                 uju bigint, apan bigint, alan bigint)
                                 group by area, grup
                                 ) as a
                                 union all
                                 
                                 
                                 select area, grup, 0 as atu, atu as siji, 0 as ua, ua as loro, 0 as iga, iga as telu, 0 as ampa, ampa as papat, 0 as ima, 
                                 ima as limo, 0 as anam, anam as anem, 0 as uju, uju as pitu, 0 as apan, apan as lapan, 0 as alan, alan as sanga from(
                                 select area, grup, sum(atu) as atu, sum(ua) as ua, sum(iga) as iga, sum(ampa) as ampa, sum(ima) as ima, sum(anam) as anam, 
                                 sum(uju) as uju, sum(apan) as apan, sum(alan) as alan from crosstab (
                                 'SELECT x.i_area,x.i_product_group,x.i_customer_class, count(x.i_customer) as jml from(
                                 SELECT distinct(c.i_customer) as i_customer, a.i_area,f.i_product_group,c.i_customer_class
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d, tr_product e, tr_product_type f
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND 
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND 
                                 b.f_spb_consigment = false and a.i_sj=d.i_sj and a.i_area=d.i_area and d.i_product=e.i_product 
                                 AND e.i_product_type=f.i_product_type AND f.i_product_group = ''00''
                                 GROUP BY a.i_area, f.i_product_group, c.i_customer_class, c.i_customer
                                 ) as x
                                 GROUP BY x.i_area, x.i_product_group, x.i_customer_class
                                 UNION ALL 
                                 SELECT x.i_area,x.i_product_group,x.i_customer_class, count(x.i_customer) as jml from(
                                 SELECT distinct(c.i_customer) as i_customer, a.i_area,f.i_product_group,c.i_customer_class
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d, tr_product e, tr_product_type f
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND 
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND 
                                 b.f_spb_consigment = false and a.i_sj=d.i_sj and a.i_area=d.i_area and d.i_product=e.i_product 
                                 AND e.i_product_type=f.i_product_type AND f.i_product_group = ''01''
                                 GROUP BY a.i_area, f.i_product_group, c.i_customer_class, c.i_customer
                                 ) as x
                                 GROUP BY x.i_area, x.i_product_group, x.i_customer_class
                                 UNION ALL
                                 SELECT x.i_area,x.i_product_group,x.i_customer_class, count(x.i_customer) as jml from(
                                 SELECT distinct(c.i_customer) as i_customer, a.i_area,f.i_product_group,c.i_customer_class
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d, tr_product e, tr_product_type f
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND
                                 b.f_spb_consigment = false and a.i_sj=d.i_sj and a.i_area=d.i_area and d.i_product=e.i_product
                                 AND e.i_product_type=f.i_product_type AND f.i_product_group = ''02''
                                 GROUP BY a.i_area, f.i_product_group, c.i_customer_class, c.i_customer
                                 ) as x
                                 GROUP BY x.i_area, x.i_product_group, x.i_customer_class
                                 UNION ALL
                                 SELECT x.i_area,''PB'' as i_product_group,x.i_customer_class, count(x.i_customer) as jml from(
                                 SELECT distinct(c.i_customer) as i_customer, a.i_area, ''PB'' as i_product_group,c.i_customer_class
                                 FROM tm_nota a, tm_spb b, tr_customer c, tm_nota_item d
                                 WHERE a.d_nota>=''$dfrom'' AND a.d_nota<=''$dto'' AND a.i_customer=c.i_customer AND NOT a.i_nota IS NULL AND 
                                 a.f_nota_cancel = false AND a.i_spb = b.i_spb AND a.i_area = b.i_area AND b.f_spb_consigment = true
                                 and a.i_sj=d.i_sj and a.i_area=d.i_area
                                 GROUP BY a.i_area, i_product_group, c.i_customer_class, c.i_customer
                                 ) as x
                                 GROUP BY x.i_area, x.i_product_group, x.i_customer_class','select * from generate_series(1,9)')
                                 as
                                 (area text, grup text, atu bigint, ua bigint, iga bigint, ampa bigint, ima bigint, anam bigint,
                                 uju bigint, apan bigint, alan bigint)
                                 group by area, grup
                                 ) as a
                                 )as a
                                 group by area, grup
                                 order by area, grup",false);
      }

    public function total($dfrom, $dto){   
        return $this->db->query("
            SELECT
                SUM(x.jumlah) AS jumlah,
                SUM(x.jml_netto) AS jml_netto,
                SUM(n_nota) AS n_nota,
                SUM(x.n_spb) AS n_spb 
            FROM
            (
               SELECT
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb,
                  SUM(a.n_nota) AS n_nota,
                  SUM(a.jumlah) AS jumlah,
                  SUM(a.jumlah - a.v_nota_discounttotal) AS jml_netto 
               FROM
                  (
                     SELECT
                        a.i_area,
                        a.i_nota,
                        b.e_area_name,
                        cast('PB' as text) AS i_product_group,
                        cast('Modern Outlet' AS text) AS e_product_groupname,
                        a.v_nota_discounttotal,
                        SUM(e.n_deliver) AS n_nota,
                        0 AS n_spb,
                        SUM(e.n_deliver*e.v_unit_price) AS jumlah 
                     FROM
                        tm_nota a,
                        tr_area b,
                        tm_spb c,
                        tm_nota_item e 
                     WHERE
                        c.f_spb_cancel = 'f' 
                        AND c.i_spb = a.i_spb 
                        AND c.i_area = a.i_area 
                        AND a.i_area = b.i_area 
                        AND a.d_nota >= '$dfrom' 
                        AND a.d_nota <= '$dto' 
                        AND not a.i_nota is null 
                        AND a.f_nota_cancel = 'f' 
                        AND c.f_spb_consigment = 't' 
                        AND a.i_sj = e.i_sj 
                        AND a.i_area = e.i_area 
                     GROUP BY
                        a.i_area,
                        e_product_groupname,
                        b.e_area_name,
                        a.v_nota_discounttotal,
                        a.i_nota 
                  )
                  AS a 
               GROUP BY
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb 
               UNION ALL
               SELECT
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb,
                  SUM(a.n_nota) AS n_nota,
                  SUM(a.jumlah) AS jumlah,
                  SUM(a.jml_netto) AS jml_netto 
               FROM
                  (
                     SELECT
                        a.i_area,
                        b.e_area_name,
                        cast('PB' as text) AS i_product_group,
                        cast('Modern Outlet' AS text) AS e_product_groupname,
                        0 AS n_nota,
                        sum(f.n_order) AS n_spb,
                        0 AS jumlah,
                        0 AS jml_netto 
                     FROM
                        tm_nota a,
                        tr_area b,
                        tm_spb c,
                        tm_spb_item f 
                     WHERE
                        c.f_spb_cancel = 'f' 
                        AND c.i_spb = a.i_spb 
                        AND c.i_area = a.i_area 
                        AND a.i_area = b.i_area 
                        AND a.d_nota >= '$dfrom' 
                        AND a.d_nota <= '$dto' 
                        AND not a.i_nota is null 
                        AND a.f_nota_cancel = 'f' 
                        AND c.f_spb_consigment = 't' 
                        AND c.i_spb = f.i_spb 
                        AND c.i_area = f.i_area 
                     GROUP BY
                        a.i_area,
                        e_product_groupname,
                        b.e_area_name 
                  )
                  AS a 
               GROUP BY
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb 
               UNION ALL
               SELECT
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb,
                  SUM(a.n_nota) AS n_nota,
                  SUM(a.jumlah) AS jumlah,
                  SUM(a.jumlah - a.v_nota_discounttotal) AS jml_netto 
               FROM
                  (
                     SELECT
                        a.i_area,
                        b.e_area_name,
                        d.i_product_group,
                        d.e_product_groupname,
                        a.v_nota_discounttotal,
                        SUM(e.n_deliver) AS n_nota,
                        0 as n_spb,
                        SUM(e.n_deliver*e.v_unit_price) AS jumlah 
                     FROM
                        tm_nota a,
                        tr_area b,
                        tr_product_group d,
                        tm_spb c,
                        tm_nota_item e,
                        tr_product g,
                        tr_product_type h 
                     WHERE
                        c.f_spb_cancel = 'f' 
                        AND c.i_spb = a.i_spb 
                        AND c.i_area = a.i_area 
                        AND a.i_area = b.i_area 
                        AND a.d_nota >= '$dfrom' 
                        AND a.d_nota <= '$dto' 
                        AND not a.i_nota is null 
                        AND h.i_product_group = d.i_product_group 
                        AND a.f_nota_cancel = 'f' 
                        AND c.f_spb_consigment = 'f' 
                        AND e.i_product = g.i_product 
                        AND g.i_product_type = h.i_product_type 
                        AND a.i_sj = e.i_sj 
                        AND a.i_area = e.i_area 
                     GROUP BY
                        a.i_area,
                        d.i_product_group,
                        d.e_product_groupname,
                        b.e_area_name,
                        a.v_nota_discounttotal 
                  )
                  AS a 
               GROUP BY
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb 
               UNION ALL
               SELECT
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb,
                  SUM(a.n_nota) AS n_nota,
                  SUM(a.jumlah) AS jumlah,
                  SUM(a.jml_netto) AS jml_netto 
               FROM
                  (
                     SELECT
                        a.i_area,
                        b.e_area_name,
                        d.i_product_group,
                        d.e_product_groupname,
                        0 AS n_nota,
                        SUM(f.n_order) AS n_spb,
                        0 AS jumlah,
                        0 AS jml_netto 
                     FROM
                        tm_nota a,
                        tr_area b,
                        tr_product_group d,
                        tm_spb c,
                        tm_spb_item f,
                        tr_product g,
                        tr_product_type h 
                     WHERE
                        c.f_spb_cancel = 'f' 
                        AND c.i_spb = a.i_spb 
                        AND c.i_area = a.i_area 
                        AND a.i_area = b.i_area 
                        AND a.d_nota >= '$dfrom' 
                        AND a.d_nota <= '$dto' 
                        AND not a.i_nota is null 
                        AND a.f_nota_cancel = 'f' 
                        AND c.f_spb_consigment = 'f' 
                        AND c.i_spb = f.i_spb 
                        AND c.i_area = f.i_area 
                        AND h.i_product_group = d.i_product_group 
                        AND f.i_product = g.i_product 
                        AND g.i_product_type = h.i_product_type 
                     GROUP BY
                        a.i_area,
                        d.i_product_group,
                        d.e_product_groupname,
                        b.e_area_name 
                  )
                  AS a 
               GROUP BY
                  a.i_area,
                  a.e_area_name,
                  a.i_product_group,
                  a.e_product_groupname,
                  a.n_spb 
            )
            x"
        , FALSE);
   }

   public function bacakelas(){
		$query = $this->db->query(" select * from tr_customer_class order by i_customer_class",false);
		if ($query->num_rows() > 0){
			return $query->result();
		}
   }

   function bacaarea($dfrom,$dto){
		$this->db->select(" distinct a.i_area, b.e_area_name
                        from vpenjualanperdivisikelas a, tr_area b
                        where a.i_area=b.i_area and a.d_doc>='$dfrom' and a.d_doc<='$dto'
                        order by a.i_area",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
  }

   public function bacaprod($dfrom,$dto){
      $query = $this->db->query(" 
                        SELECT 
                           a.* 
                        FROM (
                           SELECT DISTINCT 
                              a.i_product_group, 
                              b.e_product_groupname
                           FROM 
                              vpenjualanperdivisikelas a 
                           INNER JOIN 
                              tr_product_group b on (a.i_product_group=b.i_product_group)
                           WHERE 
                              a.d_doc>='$dfrom' 
                              AND a.d_doc<='$dto'
                           UNION ALL
                           SELECT DISTINCT 
                              a.i_product_group, 
                              'Modern Outlet' AS e_product_groupname
                           FROM 
                              vpenjualanperdivisikelas a 
                           WHERE 
                              a.d_doc>='$dfrom' 
                              AND a.d_doc<='$dto'
                              AND a.i_product_group not in (select i_product_group from tr_product_group)
                           ) AS a
                        ORDER BY 
                           a.e_product_groupname"
                        ,false);
		if ($query->num_rows() > 0){
			return $query->result();
		}
  }
}

/* End of file Mmaster.php */
