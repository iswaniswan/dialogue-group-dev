<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return '00';
        }else{
            return 'xx';
        }
    }

    public function bacaarea($username, $idcompany, $iarea){
        if ($iarea=='00') {
            return $this->db->query("SELECT * FROM tr_area", FALSE)->result();
        }else{
            return $this->db->query("
                SELECT
                    *
                FROM
                    tr_area
                WHERE
                    i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
        ", FALSE)->result();
        }
    }
    
    public function bacaperiode($iperiode,$iarea){
        if($iarea=='NA'){
            $query = $this->db->query("
                SELECT
                    c.e_area_name,
                    SUM(v_target) AS v_target,
                    b.i_area,
                    SUM(b.v_nota_netto) AS v_nota_netto,
                    SUM(b.v_nota_gross) AS v_nota_gross,
                    SUM(b.v_nota_grossinsentif) AS v_nota_grossinsentif,
                    SUM(b.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                    SUM(b.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                    SUM(b.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                    SUM(b.v_nota_reguler) AS v_nota_reguler,
                    SUM(b.v_nota_baby) AS v_nota_baby,
                    SUM(b.v_nota_babyinsentif) AS v_nota_babyinsentif,
                    SUM(b.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                    SUM(b.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                    SUM(b.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                    SUM(b.v_spb_gross) AS v_spb_gross,
                    SUM(b.v_spb_netto) AS v_spb_netto,
                    SUM(b.v_retur_insentif) AS v_retur_insentif,
                    SUM(b.v_retur_noninsentif) AS v_retur_noninsentif
                FROM
                    (
                    SELECT
                        v_target,
                        i_area,
                        0 AS v_nota_netto,
                        0 AS v_nota_gross,
                        0 AS v_nota_grossinsentif,
                        0 AS v_nota_nettoinsentif,
                        0 AS v_nota_grossnoninsentif,
                        0 AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        0 AS v_spb_gross,
                        0 AS v_spb_netto,
                        0 AS v_retur_insentif,
                        0 AS v_retur_noninsentif
                    FROM
                        tm_target
                    WHERE
                        i_periode = '$iperiode'
                UNION ALL
                    SELECT
                        0 AS v_target,
                        i_area,
                        SUM(a.v_nota_netto) AS v_nota_netto,
                        SUM(a.v_nota_gross) AS v_nota_gross,
                        SUM(a.v_nota_grossinsentif) AS v_nota_grossinsentif,
                        SUM(a.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                        SUM(a.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                        SUM(a.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                        SUM(a.v_nota_reguler) AS v_nota_reguler,
                        SUM(a.v_nota_baby) AS v_nota_baby,
                        SUM(a.v_nota_babyinsentif) AS v_nota_babyinsentif,
                        SUM(a.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                        SUM(a.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                        SUM(a.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                        SUM(a.v_spb_gross) AS v_spb_gross,
                        SUM(a.v_spb_netto) AS v_spb_netto,
                        SUM(a.v_retur_insentif) AS v_retur_insentif,
                        SUM(a.v_retur_noninsentif) AS v_retur_noninsentif
                    FROM
                        (
                        SELECT
                            i_area,
                            SUM(v_netto) AS v_nota_netto,
                            SUM(v_gross) AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            SUM(v_spb) AS v_spb_gross,
                            SUM(v_spb)-SUM(v_spbdiscount) AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            TO_CHAR(d_docspb, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 't'
                            AND TO_CHAR(d_docspb, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            SUM(v_kn) AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 't'
                            AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            SUM(v_gross) AS v_nota_grossnoninsentif,
                            SUM(v_netto) AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 'f'
                            AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            SUM(v_kn) AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 'f'
                            AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area ) AS a
                    GROUP BY
                        i_area ) AS b,
                    tr_area c
                WHERE
                    b.i_area = c.i_area
                GROUP BY
                    b.i_area,
                    c.e_area_name
                ORDER BY
                    b.i_area
                ", FALSE);
        }else{
            $query = $this->db->query("
                SELECT
                    c.e_area_name,
                    SUM(v_target) AS v_target,
                    b.i_area,
                    SUM(b.v_nota_netto) AS v_nota_netto,
                    SUM(b.v_nota_gross) AS v_nota_gross,
                    SUM(b.v_nota_grossinsentif) AS v_nota_grossinsentif,
                    SUM(b.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                    SUM(b.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                    SUM(b.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                    SUM(b.v_nota_reguler) AS v_nota_reguler,
                    SUM(b.v_nota_baby) AS v_nota_baby,
                    SUM(b.v_nota_babyinsentif) AS v_nota_babyinsentif,
                    SUM(b.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                    SUM(b.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                    SUM(b.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                    SUM(b.v_spb_gross) AS v_spb_gross,
                    SUM(b.v_spb_netto) AS v_spb_netto,
                    SUM(b.v_retur_insentif) AS v_retur_insentif,
                    SUM(b.v_retur_noninsentif) AS v_retur_noninsentif
                FROM
                    (
                    SELECT
                        v_target,
                        i_area,
                        0 AS v_nota_netto,
                        0 AS v_nota_gross,
                        0 AS v_nota_grossinsentif,
                        0 AS v_nota_nettoinsentif,
                        0 AS v_nota_grossnoninsentif,
                        0 AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        0 AS v_spb_gross,
                        0 AS v_spb_netto,
                        0 AS v_retur_insentif,
                        0 AS v_retur_noninsentif
                    FROM
                        tm_target
                    WHERE
                        i_periode = '$iperiode'
                        AND i_area = '$iarea'
                UNION ALL
                    SELECT
                        0 AS v_target,
                        i_area,
                        SUM(a.v_nota_netto) AS v_nota_netto,
                        SUM(a.v_nota_gross) AS v_nota_gross,
                        SUM(a.v_nota_grossinsentif) AS v_nota_grossinsentif,
                        SUM(a.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                        SUM(a.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                        SUM(a.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                        SUM(a.v_nota_reguler) AS v_nota_reguler,
                        SUM(a.v_nota_baby) AS v_nota_baby,
                        SUM(a.v_nota_babyinsentif) AS v_nota_babyinsentif,
                        SUM(a.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                        SUM(a.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                        SUM(a.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                        SUM(a.v_spb_gross) AS v_spb_gross,
                        SUM(a.v_spb_netto) AS v_spb_netto,
                        SUM(a.v_retur_insentif) AS v_retur_insentif,
                        SUM(a.v_retur_noninsentif) AS v_retur_noninsentif
                    FROM
                        (
                        SELECT
                            i_area,
                            SUM(v_netto) AS v_nota_netto,
                            SUM(v_gross) AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            i_area = '$iarea'
                            AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            SUM(v_spb) AS v_spb_gross,
                            SUM(v_spb)-SUM(v_spbdiscount) AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            i_area = '$iarea'
                            AND TO_CHAR(d_docspb, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 't'
                            AND i_area = '$iarea'
                            AND TO_CHAR(d_docspb, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            SUM(v_kn) AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 't'
                            AND i_area = '$iarea'
                            AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            SUM(v_gross) AS v_nota_grossnoninsentif,
                            SUM(v_netto) AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            0 AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 'f'
                            AND i_area = '$iarea'
                            AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area
                    UNION ALL
                        SELECT
                            i_area,
                            0 AS v_nota_netto,
                            0 AS v_nota_gross,
                            0 AS v_nota_grossinsentif,
                            0 AS v_nota_nettoinsentif,
                            0 AS v_nota_grossnoninsentif,
                            0 AS v_nota_nettononinsentif,
                            0 AS v_nota_reguler,
                            0 AS v_nota_baby,
                            0 AS v_nota_babyinsentif,
                            0 AS v_nota_babynoninsentif,
                            0 AS v_nota_regulerinsentif,
                            0 AS v_nota_regulernoninsentif,
                            0 AS v_spb_gross,
                            0 AS v_spb_netto,
                            0 AS v_retur_insentif,
                            SUM(v_kn) AS v_retur_noninsentif
                        FROM
                            vpenjualan
                        WHERE
                            f_insentif = 'f'
                            AND i_area = '$iarea'
                            AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                        GROUP BY
                            i_area ) AS a
                    GROUP BY
                        i_area ) AS b,
                    tr_area c
                WHERE
                    b.i_area = c.i_area
                GROUP BY
                    b.i_area,
                    c.e_area_name
                ORDER BY
                    b.i_area
                ", FALSE);
        }
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacapersales($iperiode,$iarea){
        $query = $this->db->query("
            SELECT
                c.e_salesman_name,
                d.e_area_name,
                b.i_area,
                b.i_salesman,
                SUM(v_target) AS v_target,
                SUM(b.v_nota_netto) AS v_nota_netto,
                SUM(b.v_nota_gross) AS v_nota_gross,
                SUM(b.v_nota_grossinsentif) AS v_nota_grossinsentif,
                SUM(b.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                SUM(b.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                SUM(b.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                SUM(b.v_nota_reguler) AS v_nota_reguler,
                SUM(b.v_nota_baby) AS v_nota_baby,
                SUM(b.v_nota_babyinsentif) AS v_nota_babyinsentif,
                SUM(b.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                SUM(b.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                SUM(b.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                SUM(b.v_spb_gross) AS v_spb_gross,
                SUM(b.v_spb_netto) AS v_spb_netto,
                SUM(b.v_retur_insentif) AS v_retur_insentif,
                SUM(b.v_retur_noninsentif) AS v_retur_noninsentif
            FROM
                (
                SELECT
                    v_target,
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tm_target_itemsls
                WHERE
                    i_periode = '$iperiode'
                    AND i_area = '$iarea'
            UNION ALL
                SELECT
                    0 AS v_target,
                    i_area,
                    i_salesman,
                    SUM(a.v_nota_netto) AS v_nota_netto,
                    SUM(a.v_nota_gross) AS v_nota_gross,
                    SUM(a.v_nota_grossinsentif) AS v_nota_grossinsentif,
                    SUM(a.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                    SUM(a.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                    SUM(a.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                    SUM(a.v_nota_reguler) AS v_nota_reguler,
                    SUM(a.v_nota_baby) AS v_nota_baby,
                    SUM(a.v_nota_babyinsentif) AS v_nota_babyinsentif,
                    SUM(a.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                    SUM(a.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                    SUM(a.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                    SUM(a.v_spb_gross) AS v_spb_gross,
                    SUM(a.v_spb_netto) AS v_spb_netto,
                    SUM(a.v_retur_insentif) AS v_retur_insentif,
                    SUM(a.v_retur_noninsentif) AS v_retur_noninsentif
                FROM
                    (
                    SELECT
                        i_area,
                        i_salesman,
                        0 AS v_nota_netto,
                        0 AS v_nota_gross,
                        0 AS v_nota_grossinsentif,
                        0 AS v_nota_nettoinsentif,
                        0 AS v_nota_grossnoninsentif,
                        0 AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        SUM(v_spb) AS v_spb_gross,
                        SUM(v_spb)-SUM(v_spbdiscount) AS v_spb_netto,
                        0 AS v_retur_insentif,
                        0 AS v_retur_noninsentif
                    FROM
                        vpenjualan
                    WHERE
                        i_area = '$iarea'
                        AND TO_CHAR(d_docspb, 'yyyymm')= '$iperiode'
                    GROUP BY
                        i_area,
                        i_salesman
                UNION ALL
                    SELECT
                        i_area,
                        i_salesman,
                        SUM(v_netto) AS v_nota_netto,
                        SUM(v_gross) AS v_nota_gross,
                        0 AS v_nota_grossinsentif,
                        0 AS v_nota_nettoinsentif,
                        0 AS v_nota_grossnoninsentif,
                        0 AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        0 AS v_spb_gross,
                        0 AS v_spb_netto,
                        0 AS v_retur_insentif,
                        0 AS v_retur_noninsentif
                    FROM
                        vpenjualan
                    WHERE
                        i_area = '$iarea'
                        AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                    GROUP BY
                        i_area,
                        i_salesman
                UNION ALL
                    SELECT
                        i_area,
                        i_salesman,
                        0 AS v_nota_netto,
                        0 AS v_nota_gross,
                        SUM(v_gross) AS v_nota_grossinsentif,
                        SUM(v_netto) AS v_nota_nettoinsentif,
                        0 AS v_nota_grossnoninsentif,
                        0 AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        0 AS v_spb_gross,
                        0 AS v_spb_netto,
                        0 AS v_retur_insentif,
                        0 AS v_retur_noninsentif
                    FROM
                        vpenjualan
                    WHERE
                        f_insentif = 't'
                        AND i_area = '$iarea'
                        AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                    GROUP BY
                        i_area,
                        i_salesman
                UNION ALL
                    SELECT
                        i_area,
                        i_salesman,
                        0 AS v_nota_netto,
                        0 AS v_nota_gross,
                        0 AS v_nota_grossinsentif,
                        0 AS v_nota_nettoinsentif,
                        0 AS v_nota_grossnoninsentif,
                        0 AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        0 AS v_spb_gross,
                        0 AS v_spb_netto,
                        SUM(v_kn) AS v_retur_insentif,
                        0 AS v_retur_noninsentif
                    FROM
                        vpenjualan
                    WHERE
                        f_insentif = 't'
                        AND i_area = '$iarea'
                        AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                    GROUP BY
                        i_area,
                        i_salesman
                UNION ALL
                    SELECT
                        i_area,
                        i_salesman,
                        0 AS v_nota_netto,
                        0 AS v_nota_gross,
                        0 AS v_nota_grossinsentif,
                        0 AS v_nota_nettoinsentif,
                        SUM(v_gross) AS v_nota_grossnoninsentif,
                        SUM(v_netto) AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        0 AS v_spb_gross,
                        0 AS v_spb_netto,
                        0 AS v_retur_insentif,
                        0 AS v_retur_noninsentif
                    FROM
                        vpenjualan
                    WHERE
                        f_insentif = 'f'
                        AND i_area = '$iarea'
                        AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                    GROUP BY
                        i_area,
                        i_salesman
                UNION ALL
                    SELECT
                        i_area,
                        i_salesman,
                        0 AS v_nota_netto,
                        0 AS v_nota_gross,
                        0 AS v_nota_grossinsentif,
                        0 AS v_nota_nettoinsentif,
                        0 AS v_nota_grossnoninsentif,
                        0 AS v_nota_nettononinsentif,
                        0 AS v_nota_reguler,
                        0 AS v_nota_baby,
                        0 AS v_nota_babyinsentif,
                        0 AS v_nota_babynoninsentif,
                        0 AS v_nota_regulerinsentif,
                        0 AS v_nota_regulernoninsentif,
                        0 AS v_spb_gross,
                        0 AS v_spb_netto,
                        0 AS v_retur_insentif,
                        SUM(v_kn) AS v_retur_noninsentif
                    FROM
                        vpenjualan
                    WHERE
                        f_insentif = 'f'
                        AND i_area = '$iarea'
                        AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                    GROUP BY
                        i_area,
                        i_salesman ) AS a
                GROUP BY
                    i_area,
                    i_salesman ) AS b,
                tr_salesman c,
                tr_area d
            WHERE
                b.i_salesman = c.i_salesman
                AND b.i_area = d.i_area
            GROUP BY
                b.i_area,
                b.i_salesman,
                c.e_salesman_name,
                d.e_area_name
            ORDER BY
                b.i_salesman
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
