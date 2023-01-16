<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacaperiode($iperiode){
			$user   = $this->session->userdata('username');

      return $this->db->query("
                              SELECT
                                *, 
                                CAST(CASE WHEN n_nota=0 THEN 0 ELSE n_retur/n_nota*100 END AS numeric(10, 2)) AS persenretur 
                              FROM
                                (SELECT 
                                  i_area, 
                                  i_salesman,
                                  e_area_name, 
                                  e_salesman_name, 
                                  SUM(n_target) AS n_target, 
                                  SUM(n_spb) AS n_spb, 
                                  SUM(n_nota) AS n_nota, 
                                  SUM(n_retur) AS n_retur 
                                  FROM(
                                    SELECT 
                                      a.i_area, 
                                      a.i_salesman, 
                                      b.e_area_name, 
                                      c.e_salesman_name, 
                                      a.n_target, 
                                      0 AS n_spb, 
                                      0 AS n_nota, 
                                      0 AS n_retur
                                    FROM 
                                      tm_target_itemsls a, 
                                      tr_area b, 
                                      tr_salesman c 
                                    WHERE 
                                      a.i_periode = '$iperiode' 
                                      AND a.i_area=b.i_area 
                                      AND a.i_salesman=c.i_salesman 
                                      AND b.f_area_real='t'
                                      AND a.i_area in (select i_area from public.tm_user_area where username='$user')
                                    UNION ALL
                                    SELECT
                                      a.i_area, 
                                      a.i_salesman, 
                                      b.e_area_name,
                                      c.e_salesman_name, 
                                      0 AS n_target,
                                      sum(n_order) AS n_spb, 
                                      0 AS n_nota, 
                                      0 AS n_retur
                                    FROM 
                                      tm_spb a, 
                                      tr_area b, 
                                      tr_salesman c, 
                                      tm_spb_item d
                                    WHERE 
                                      to_char(d_spb, 'yyyymm') = '$iperiode' 
                                      AND f_spb_cancel='f' 
                                      AND a.i_area=b.i_area 
                                      AND a.i_salesman=c.i_salesman 
                                      AND b.f_area_real='t' 
                                      AND a.i_spb=d.i_spb 
                                      AND a.i_area=d.i_area
                                      AND a.i_area in(select i_area from public.tm_user_area where username='$user')
                                    GROUP BY 
                                      a.i_area, 
                                      a.i_salesman, 
                                      b.e_area_name, 
                                      c.e_salesman_name
                                    UNION ALL
                                    SELECT 
                                      a.i_area, 
                                      a.i_salesman, 
                                      b.e_area_name, 
                                      c.e_salesman_name, 
                                      0 AS n_target, 
                                      0 AS n_spb, 
                                      sum(n_deliver) AS n_nota, 
                                      0 AS n_retur
                                    FROM 
                                      tm_nota a, 
                                      tr_area b, 
                                      tr_salesman c, 
                                      tm_nota_item d
                                    WHERE 
                                      to_char(a.d_nota, 'yyyymm') = '$iperiode' 
                                      AND f_nota_cancel='f' 
                                      AND a.i_area=b.i_area 
                                      AND a.i_salesman=c.i_salesman 
                                      AND b.f_area_real='t' 
                                      AND a.i_sj=d.i_sj
                                      AND a.i_area=d.i_area
                                      AND a.i_area in(select i_area from public.tm_user_area where username='$user')
                                    GROUP BY 
                                      a.i_area, 
                                      a.i_salesman, 
                                      b.e_area_name, 
                                      c.e_salesman_name
                                  UNION ALL
                                  SELECT 
                                    a.i_area, 
                                    a.i_salesman, 
                                    b.e_area_name, 
                                    c.e_salesman_name, 
                                    0 AS n_target, 
                                    0 AS n_spb, 
                                    0 AS n_nota, 
                                    sum(d.n_quantity) AS v_retur
                                  FROM 
                                    tm_kn a, 
                                    tr_area b, 
                                    tr_salesman c, 
                                    tm_bbm_item d
                                  WHERE 
                                    to_char(d_kn, 'yyyymm') = '$iperiode' 
                                    AND f_kn_cancel='f'
                                    AND a.i_area=b.i_area 
                                    AND a.i_salesman=c.i_salesman 
                                    AND b.f_area_real='t' 
                                    AND a.i_refference=d.i_bbm
                                    AND a.i_area in(select i_area from public.tm_user_area where username='$user')
                                  GROUP BY 
                                    a.i_area, 
                                    a.i_salesman, 
                                    b.e_area_name, 
                                    c.e_salesman_name
                                  ) AS x
                                GROUP BY 
                                  x.i_area, 
                                  x.i_salesman, 
                                  x.e_area_name, 
                                  x.e_salesman_name
                              ORDER BY 
                                x.i_area, 
                                x.i_salesman) AS data ",false);
    }
}

/* End of file Mmaster.php */
