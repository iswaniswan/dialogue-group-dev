<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getarea($username, $idcompany){
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

    public function bacacash($iperiode, $iarea, $username, $idcompany){
        if ($iarea=='00') {
            return $this->db->query("
                SELECT
                    i_area,
                    e_area_name,
                    i_salesman,
                    e_salesman_name,
                    SUM(total) AS total,
                    SUM(realisasi) AS realisasi,
                    SUM(totalnon) AS totalnon,
                    SUM(realisasinon) AS realisasinon
                FROM
                    (
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name,
                        SUM(b.bayar + b.sisa) AS total,
                        SUM(b.bayar) AS realisasi,
                        0 AS totalnon,
                        0 AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_cash b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 't'
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name
                UNION ALL
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name,
                        0 AS total,
                        0 AS realisasi,
                        SUM(b.bayar + b.sisa) AS totalnon,
                        SUM(b.bayar) AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_cash b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 'f'
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name ) AS a
                GROUP BY
                    i_area,
                    e_area_name,
                    i_salesman,
                    e_salesman_name
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    i_area,
                    e_area_name,
                    i_salesman,
                    e_salesman_name,
                    SUM(total) AS total,
                    SUM(realisasi) AS realisasi,
                    SUM(totalnon) AS totalnon,
                    SUM(realisasinon) AS realisasinon
                FROM
                    (
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name,
                        SUM(b.bayar + b.sisa) AS total,
                        SUM(b.bayar) AS realisasi,
                        0 AS totalnon,
                        0 AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_cash b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 't'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name
                UNION ALL
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name,
                        0 AS total,
                        0 AS realisasi,
                        SUM(b.bayar + b.sisa) AS totalnon,
                        SUM(b.bayar) AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_cash b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 'f'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name ) AS a
                GROUP BY
                    i_area,
                    e_area_name,
                    i_salesman,
                    e_salesman_name
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ", FALSE);
        }
    }

    public function bacacredit($iperiode, $iarea, $username, $idcompany){
        if ($iarea=='00') {
            return $this->db->query("
                SELECT
                    i_area,
                    e_area_name,
                    a.i_salesman,
                    e_salesman_name,
                    SUM(total) AS total,
                    SUM(realisasi) AS realisasi,
                    SUM(totalnon) AS totalnon,
                    SUM(realisasinon) AS realisasinon
                FROM
                    (
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e.e_salesman_name,
                        SUM(b.sisa + b.bayar) AS total,
                        SUM(b.bayar) AS realisasi,
                        0 AS totalnon,
                        0 AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_credit b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 't'
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name
                UNION ALL
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e.e_salesman_name,
                        0 AS total,
                        0 AS realisasi,
                        SUM(b.sisa + b.bayar) AS totalnon,
                        SUM(b.bayar) AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_credit b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 'f'
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name ) AS a
                GROUP BY
                    i_area,
                    e_area_name,
                    a.i_salesman,
                    e_salesman_name
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    i_area,
                    e_area_name,
                    a.i_salesman,
                    e_salesman_name,
                    SUM(total) AS total,
                    SUM(realisasi) AS realisasi,
                    SUM(totalnon) AS totalnon,
                    SUM(realisasinon) AS realisasinon
                FROM
                    (
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e.e_salesman_name,
                        SUM(b.sisa + b.bayar) AS total,
                        SUM(b.bayar) AS realisasi,
                        0 AS totalnon,
                        0 AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_credit b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 't'
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name
                UNION ALL
                    SELECT
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e.e_salesman_name,
                        0 AS total,
                        0 AS realisasi,
                        SUM(b.sisa + b.bayar) AS totalnon,
                        SUM(b.bayar) AS realisasinon
                    FROM
                        tr_area a
                    LEFT JOIN tm_collection_credit b ON
                        (a.i_area = b.i_area)
                    LEFT JOIN tr_salesman e ON
                        (b.i_salesman = e.i_salesman)
                    WHERE
                        a.f_area_real = 't'
                        AND b.e_periode = '$iperiode'
                        AND b.f_insentif = 'f'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
                    GROUP BY
                        a.i_area,
                        a.e_area_name,
                        e.i_salesman,
                        e_salesman_name ) AS a
                GROUP BY
                    i_area,
                    e_area_name,
                    a.i_salesman,
                    e_salesman_name
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ", FALSE);
        }
    }
    
    public function bacapenjualan($iperiode, $iarea, $username, $idcompany){
        if ($iarea=='00') {
            return $this->db->query("
                SELECT
                    i_area,
                    i_salesman,
                    e_area_name,
                    e_salesman_name,
                    SUM(v_target) AS v_target,
                    SUM(v_spb) AS v_spb,
                    SUM(v_nota) AS v_nota,
                    SUM(v_retur) AS v_retur
                FROM
                    (
                    SELECT
                        a.i_area,
                        a.i_salesman,
                        b.e_area_name,
                        c.e_salesman_name,
                        a.v_target,
                        0 AS v_spb,
                        0 AS v_nota,
                        0 AS v_retur
                    FROM
                        tm_target_itemsls a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        a.i_periode = '$iperiode'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
                UNION ALL
                    SELECT
                        a.i_area,
                        a.i_salesman,
                        b.e_area_name,
                        c.e_salesman_name,
                        0 AS v_target,
                        SUM(v_spb) AS v_spb,
                        0 AS v_nota,
                        0 AS v_retur
                    FROM
                        tm_spb a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        TO_CHAR(d_spb, 'yyyymm') = '$iperiode'
                        AND f_spb_cancel = 'f'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
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
                        SUM(v_nota_gross) AS v_nota,
                        0 AS v_retur
                    FROM
                        tm_nota a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        TO_CHAR(d_nota, 'yyyymm') = '$iperiode'
                        AND f_nota_cancel = 'f'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
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
                        0 AS v_nota,
                        SUM(a.v_netto) AS v_retur
                    FROM
                        tm_kn a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        TO_CHAR(d_kn, 'yyyymm') = '$iperiode'
                        AND f_kn_cancel = 'f'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
                    GROUP BY
                        a.i_area,
                        a.i_salesman,
                        b.e_area_name,
                        c.e_salesman_name ) AS x
                GROUP BY
                    x.i_area,
                    x.i_salesman,
                    x.e_area_name,
                    x.e_salesman_name
                ORDER BY
                    x.i_area,
                    x.i_salesman
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    i_area,
                    i_salesman,
                    e_area_name,
                    e_salesman_name,
                    SUM(v_target) AS v_target,
                    SUM(v_spb) AS v_spb,
                    SUM(v_nota) AS v_nota,
                    SUM(v_retur) AS v_retur
                FROM
                    (
                    SELECT
                        a.i_area,
                        a.i_salesman,
                        b.e_area_name,
                        c.e_salesman_name,
                        a.v_target,
                        0 AS v_spb,
                        0 AS v_nota,
                        0 AS v_retur
                    FROM
                        tm_target_itemsls a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        a.i_periode = '$iperiode'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
                UNION ALL
                    SELECT
                        a.i_area,
                        a.i_salesman,
                        b.e_area_name,
                        c.e_salesman_name,
                        0 AS v_target,
                        SUM(v_spb) AS v_spb,
                        0 AS v_nota,
                        0 AS v_retur
                    FROM
                        tm_spb a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        TO_CHAR(d_spb, 'yyyymm') = '$iperiode'
                        AND f_spb_cancel = 'f'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
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
                        SUM(v_nota_gross) AS v_nota,
                        0 AS v_retur
                    FROM
                        tm_nota a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        TO_CHAR(d_nota, 'yyyymm') = '$iperiode'
                        AND f_nota_cancel = 'f'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
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
                        0 AS v_nota,
                        SUM(a.v_netto) AS v_retur
                    FROM
                        tm_kn a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        TO_CHAR(d_kn, 'yyyymm') = '$iperiode'
                        AND f_kn_cancel = 'f'
                        AND a.i_area = b.i_area
                        AND a.i_area = c.i_area
                        AND a.i_salesman = c.i_salesman
                        AND b.f_area_real = 't'
                        AND a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$idcompany')
                    GROUP BY
                        a.i_area,
                        a.i_salesman,
                        b.e_area_name,
                        c.e_salesman_name ) AS x
                GROUP BY
                    x.i_area,
                    x.i_salesman,
                    x.e_area_name,
                    x.e_salesman_name
                ORDER BY
                    x.i_area,
                    x.i_salesman
            ", FALSE);
        }
    }
}

/* End of file Mmaster.php */
