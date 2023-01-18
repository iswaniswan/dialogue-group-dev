<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    
    public function bacaperiode($dfrom,$dto){
        $dfrom    = substr($dfrom,6,4).'-'.substr($dfrom,3,2).'-'.substr($dfrom,0,2);
        $dto      = substr($dto,6,4).'-'.substr($dto,3,2).'-'.substr($dto,0,2);
        $iperiode = substr($dto,6,4).substr($dto,3,2);
        $query = $this->db->query("
            SELECT
                a.i_area,
                a.e_area_name,
                SUM(a.v_netto) AS v_nota_netto,
                SUM(a.v_gross) AS v_nota_gross,
                SUM(a.v_discount) AS v_nota_discounttotal,
                SUM(a.v_spb) AS v_spb,
                SUM(a.v_spbdiscount) AS v_spbdiscount,
                SUM(a.v_kn) AS v_kn
            FROM
                (
                SELECT
                    b.i_area,
                    c.e_area_name,
                    0 AS v_netto,
                    0 AS v_gross,
                    0 AS v_discount,
                    b.v_spb,
                    b.v_spb_discounttotal AS v_spbdiscount,
                    0 AS v_kn
                FROM
                    tm_spb b ,
                    tr_area c
                WHERE
                    b.f_spb_cancel = FALSE
                    AND b.d_spb >= '$dfrom'
                    AND b.d_spb <= '$dto'
                    AND b.i_area = c.i_area
            UNION ALL
                SELECT
                    a.i_area,
                    c.e_area_name,
                    a.v_nota_netto AS v_netto,
                    a.v_nota_gross AS v_gross,
                    a.v_nota_discounttotal AS v_discount,
                    0 AS v_spb,
                    0 AS v_spbdiscount,
                    0 AS v_kn
                FROM
                    tm_nota a,
                    tm_spb b,
                    tr_area c
                WHERE
                    NOT a.i_nota IS NULL
                    AND a.f_nota_cancel = FALSE
                    AND a.i_spb = b.i_spb
                    AND a.i_area = b.i_area
                    AND a.d_nota >= '$dfrom'
                    AND a.d_nota <= '$dto'
                    AND a.i_area = c.i_area
            UNION ALL
                SELECT
                    d.i_area,
                    c.e_area_name,
                    0 AS v_netto,
                    0 AS v_gross,
                    0 AS v_discount,
                    0 AS v_spb,
                    0 AS v_spbdiscount,
                    d.v_netto AS v_kn
                FROM
                    tm_kn d,
                    tr_area c
                WHERE
                    d.f_kn_cancel = FALSE
                    AND d.i_kn_type = '01'
                    AND d.d_kn >= '$dfrom'
                    AND d.d_kn <= '$dto'
                    AND d.i_area = c.i_area ) AS a
            GROUP BY
                i_area,
                e_area_name
            ORDER BY
                i_area
            ", FALSE);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
