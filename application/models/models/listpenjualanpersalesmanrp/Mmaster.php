<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacaperiode($iperiode){
			$user   = $this->session->userdata('username');

      return $this->db->query("
                              SELECT
                                * , 
                                CAST(CASE WHEN v_nota=0 THEN 0 ELSE v_retur/v_nota*100 END AS numeric(10,2)) AS persenretur,
                                CAST(CASE WHEN v_nota_netto=0 THEN 0 ELSE v_retur_gross/v_nota_netto*100 END AS numeric(10,2)) AS persenreturgross  
                              FROM
                                (SELECT 
                                  i_area, 
                                  i_salesman, 
                                  e_area_name, 
                                  e_salesman_name, 
                                  SUM(v_target) AS v_target, 
                                  SUM(v_spb) AS v_spb, 
                                  SUM(v_spb_netto) AS v_spb_netto, 
                                  SUM(v_nota) AS v_nota, 
                                  SUM(v_nota_netto) AS v_nota_netto, 
                                  SUM(v_retur) AS v_retur, 
                                  SUM(v_retur_gross) AS v_retur_gross 
                                  FROM(
                                    SELECT 
                                      a.i_area, 
                                      a.i_salesman, 
                                      b.e_area_name, 
                                      c.e_salesman_name, 
                                      a.v_target, 
                                      0 AS v_spb, 
                                      0 AS v_spb_netto,
                                      0 AS v_nota, 
                                      0 AS v_nota_netto, 
                                      0 AS v_retur, 
                                      0 AS v_retur_gross
                                      FROM 
                                        tm_target_itemsls a, 
                                        tr_area b, 
                                        tr_salesman c 
                                      WHERE 
                                        a.i_periode = '$iperiode' 
                                        AND a.i_area=b.i_area 
                                        AND a.i_salesman=c.i_salesman
                                        AND b.f_area_real='t'
                                        AND a.i_area in ( select i_area from public.tm_user_area where username='$user')
                                      UNION ALL
                                      SELECT 
                                        a.i_area, 
                                        a.i_salesman, 
                                        b.e_area_name, 
                                        c.e_salesman_name, 
                                        0 AS v_target, 
                                        sum(v_spb) AS v_spb,
                                        sum(v_spb-v_spb_discounttotal) AS v_spb_netto, 
                                        0 AS v_nota, 
                                        0 AS v_nota_netto, 
                                        0 AS v_retur, 
                                        0 AS v_retur_gross
                                      FROM 
                                        tm_spb a, 
                                        tr_area b, 
                                        tr_salesman c 
                                      WHERE 
                                        to_char(d_spb,'yyyymm') = '$iperiode' 
                                        AND f_spb_cancel='f'
                                        AND a.i_area=b.i_area 
                                        AND a.i_salesman=c.i_salesman
                                        AND b.f_area_real='t'
                                        AND a.i_area in ( select i_area from public.tm_user_area where username='$user')
                                      GROUP BY a.i_area, a.i_salesman, b.e_area_name, c.e_salesman_name
                                      UNION ALL
                                        SELECT 
                                          a.i_area, 
                                          a.i_salesman, 
                                          b.e_area_name, 
                                          c.e_salesman_name, 
                                          0 AS v_target, 
                                          0 AS v_spb, 
                                          0 AS v_spb_netto, 
                                          SUM(v_nota_gross) AS v_nota, 
                                          SUM(v_nota_netto) AS v_nota_netto, 
                                          0 AS v_retur, 
                                          0 AS v_retur_gross
                                        FROM 
                                          tm_nota a, 
                                          tr_area b, 
                                          tr_salesman c 
                                        WHERE 
                                          to_char(d_nota,'yyyymm') = '$iperiode' 
                                          AND f_nota_cancel='f'
                                          AND a.i_area=b.i_area 
                                          AND a.i_salesman=c.i_salesman
                                          AND b.f_area_real='t'
                                          AND a.i_area in ( select i_area from public.tm_user_area where username='$user')
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
                                          0 AS v_target, 
                                          0 AS v_spb, 
                                          0 AS v_spb_netto, 
                                          0 AS v_nota, 
                                          0 AS v_nota_netto, 
                                          SUM(a.v_netto) AS v_retur,
                                          SUM(a.v_gross) AS v_retur_gross
                                        FROM 
                                          tm_kn a, 
                                          tr_area b, 
                                          tr_salesman c 
                                        WHERE 
                                          to_char(d_kn,'yyyymm') = '$iperiode' 
                                          AND f_kn_cancel='f'
                                          AND a.i_area=b.i_area 
                                          AND a.i_salesman=c.i_salesman
                                          AND b.f_area_real='t' 
                                          AND a.i_kn_type='01'
                                          AND a.i_area in ( select i_area from public.tm_user_area where username='$user')
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
                                      x.i_salesman) 
                                      AS data  ",false);
    }
}

/* End of file Mmaster.php */
