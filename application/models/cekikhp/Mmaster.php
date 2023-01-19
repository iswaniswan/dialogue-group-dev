<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function area($username, $idcompany){
        return  $this->db->query("
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
        ", FALSE);
    }

    public function bacasaldo($iarea, $dfrom, $xfrom){
        return $this->db->query("
            SELECT
                (SUM(saldo_awalt)+ SUM(v_terima_tunai)) - (SUM(v_keluar_tunai)) AS saldotunai,
                (SUM(saldo_awalg)+ SUM(v_terima_giro))-SUM(v_keluar_giro) AS saldogiro
            FROM
                (
                SELECT
                    v_saldo_akhirtunai AS saldo_awalt,
                    v_saldo_akhirgiro AS saldo_awalg,
                    0 AS v_terima_ku,
                    0 AS v_terima_tunai,
                    0 AS v_terima_giro,
                    0 AS v_keluar_ku,
                    0 AS v_keluar_tunai,
                    0 AS v_keluar_giro
                FROM
                    tm_ikhp_saldo
                WHERE
                    i_area = '$iarea'
                    AND d_bukti = TO_DATE('$xfrom', 'dd-mm-yyyy')
            UNION ALL
                SELECT
                    0 AS saldo_awalt,
                    0 AS saldo_awalg,
                    0 AS v_terima_ku,
                    a.v_terima_tunai,
                    a.v_terima_giro,
                    0 AS v_keluar_ku,
                    a.v_keluar_tunai,
                    a.v_keluar_giro
                FROM
                    tm_ikhp a,
                    tr_ikhp_type b,
                    tr_area c
                WHERE
                    a.i_ikhp_type = b.i_ikhp_type
                    AND a.i_area = '$iarea'
                    AND a.i_area = c.i_area
                    AND d_bukti > TO_DATE('$xfrom', 'dd-mm-yyyy')
                    AND d_bukti < TO_DATE('$dfrom', 'dd-mm-yyyy')
            UNION ALL
                SELECT
                    0 AS saldo_awalt,
                    0 AS saldo_awalg,
                    0 AS v_terima_ku,
                    a.v_jumlah AS v_terima_tunai,
                    0 AS v_terima_giro,
                    0 AS v_keluar_ku,
                    0 AS v_keluar_tunai,
                    0 AS v_keluar_giro
                FROM
                    tm_tunai a,
                    tr_area d
                WHERE
                    a.i_area = d.i_area
                    AND a.f_tunai_cancel = 'f'
                    AND (a.d_tunai > TO_DATE('$xfrom', 'dd-mm-yyyy')
                    AND a.d_tunai < TO_DATE('$dfrom', 'dd-mm-yyyy'))
                    AND a.i_area = '$iarea'
            UNION ALL
                SELECT
                    0 AS saldo_awalt,
                    0 AS saldo_awalg,
                    0 AS v_terima_ku,
                    0 AS v_terima_tunai,
                    0 AS v_terima_giro,
                    0 AS v_keluar_ku,
                    b.v_jumlah AS v_keluar_tunai,
                    0 AS v_keluar_giro
                FROM
                    tm_tunai a,
                    tm_rtunai_item b,
                    tm_rtunai c,
                    tr_area d,
                    tr_bank e
                WHERE
                    a.i_tunai = b.i_tunai
                    AND a.i_area = b.i_area_tunai
                    AND b.i_rtunai = c.i_rtunai
                    AND b.i_area = c.i_area
                    AND a.i_area_rtunai = c.i_area
                    AND c.i_bank = e.i_bank
                    AND a.f_tunai_cancel = 'f'
                    AND c.f_rtunai_cancel = 'f'
                    AND a.i_area = d.i_area
                    AND (c.d_rtunai > TO_DATE('$xfrom', 'dd-mm-yyyy')
                    AND c.d_rtunai < TO_DATE('$dfrom', 'dd-mm-yyyy'))
                    AND a.i_area = '$iarea'
            UNION ALL
                SELECT
                    0 AS saldo_awalt,
                    0 AS saldo_awalg,
                    0 AS v_terima_ku,
                    a.v_jumlah AS v_terima_tunai,
                    0 AS v_terima_giro,
                    0 AS v_keluar_ku,
                    0 AS v_keluar_tunai,
                    0 AS v_keluar_giro
                FROM
                    tm_pelunasan a,
                    tr_area c
                WHERE
                    a.i_jenis_bayar = '02'
                    AND a.i_area = '$iarea'
                    AND a.i_area = c.i_area
                    AND a.f_pelunasan_cancel = 'f'
                    AND a.d_bukti > TO_DATE('$xfrom', 'dd-mm-yyyy')
                    AND a.d_bukti < TO_DATE('$dfrom', 'dd-mm-yyyy')
            UNION ALL
                SELECT
                    0 AS saldo_awalt,
                    0 AS saldo_awalg,
                    a.v_jumlah AS v_terima_ku,
                    0 AS v_terima_tunai,
                    0 AS v_terima_giro,
                    0 AS v_keluar_ku,
                    0 AS ‹v_keluar_tunai,
                    0 AS v_keluar_giro
                FROM
                    tm_kum a,
                    tr_area b
                WHERE
                    (a.d_kum > TO_DATE('$xfrom', 'dd-mm-yyyy')
                    AND a.d_kum < TO_DATE('$dfrom', 'dd-mm-yyyy'))
                    AND a.f_kum_cancel = 'f'
                    AND a.i_area = '$iarea'
                    AND a.i_area = b.i_area
            UNION ALL
                SELECT
                    0 AS saldo_awalt,
                    0 AS saldo_awalg,
                    0 AS v_terima_ku,
                    0 AS v_terima_tunai,
                    a.v_jumlah AS v_terima_giro,
                    0 AS v_keluar_ku,
                    0 AS v_keluar_tunai,
                    0 AS v_keluar_giro
                FROM
                    tr_area c,
                    tm_giro a
                LEFT JOIN tm_dt b ON
                    (a.i_dt = b.i_dt
                    AND a.i_area = b.i_area)
                WHERE
                    a.i_area = '$iarea'
                    AND a.i_area = c.i_area
                    AND a.d_giro_terima > TO_DATE('$xfrom', 'dd-mm-yyyy')
                    AND a.d_giro_terima < TO_DATE('$dfrom', 'dd-mm-yyyy')
                    AND a.f_giro_batal_input = '0' ) AS x
        ", FALSE);
    }

    public function data($dfrom, $dto, $iarea, $folder, $saldoawalt, $saldoawalg, $xfrom){
        $PiutangDagang = PiutangDagang;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                xy.id,
                xy.d_bukti,
                xy.i_bukti,
                xy.e_ikhp_typename,
                xy.i_coa,
                xy.v_terima_tunai,
                xy.v_terima_giro,
                xy.v_keluar_tunai,
                xy.v_keluar_giro,
                xy.saldotunai,
                xy.saldogiro,
                xy.dcek
            FROM
                (
                SELECT
                    x.id,
                    x.d_bukti,
                    x.i_bukti,
                    x.e_ikhp_typename,
                    x.i_coa,
                    x.v_terima_tunai,
                    x.v_terima_giro,
                    x.v_keluar_tunai,
                    x.v_keluar_giro,
                    SUM(x.saldotunai) OVER (
                ORDER BY
                    x.id) AS saldotunai,
                    SUM(x.saldogiro) OVER (
                ORDER BY
                    x.id) AS saldogiro,
                    x.dcek
                FROM
                    (
                    SELECT
                        id,
                        d_bukti,
                        i_bukti,
                        e_ikhp_typename,
                        i_coa,
                        v_terima_tunai,
                        v_terima_giro,
                        v_keluar_tunai,
                        v_keluar_giro,
                        v_terima_tunai-v_keluar_tunai AS saldotunai,
                        v_terima_giro-v_keluar_giro AS saldogiro,
                        dcek
                    FROM
                        (
                        SELECT
                            0 AS id,
                            NULL AS d_bukti,
                            0 AS urut,
                            NULL AS i_bukti,
                            NULL AS e_ikhp_typename,
                            NULL AS i_coa,
                            '$saldoawalt' AS v_terima_tunai,
                            '$saldoawalg' AS v_terima_giro,
                            0 AS v_keluar_tunai,
                            0 AS v_keluar_giro,
                            0 AS saldotunai,
                            0 AS saldogiro,
                            NULL AS dcek
                        FROM
                            tm_ikhp_saldo
                        WHERE
                            i_area = '$iarea'
                            AND d_bukti = TO_DATE('$xfrom', 'dd-mm-yyyy')
                    UNION ALL
                        SELECT
                            ROW_NUMBER() OVER(
                        ORDER BY
                            x.d_bukti ASC,
                            x.urut,
                            substr(x.i_bukti,
                            1,
                            7)) AS id,
                            TO_CHAR(d_bukti, 'dd-mm-yyyy') AS d_bukti,
                            urut,
                            i_bukti,
                            e_ikhp_typename,
                            i_coa,
                            SUM(v_terima_ku) + SUM(v_terima_tunai) AS v_terima_tunai,
                            SUM(v_terima_giro) AS v_terima_giro,
                            SUM(v_keluar_ku) + SUM(v_keluar_tunai) AS v_keluar_tunai,
                            SUM(v_keluar_giro) AS v_keluar_giro,
                            (SUM(v_terima_ku) + SUM(v_terima_tunai)) - (SUM(v_keluar_ku) + SUM(v_keluar_tunai)) AS saldotunai,
                            SUM(v_terima_giro) - SUM(v_keluar_giro) AS saldogiro,
                            TO_CHAR(d_cek_ikhp::DATE, 'dd-mm-yyyy') AS dcek
                        FROM
                            (
                            SELECT
                                0 AS urut,
                                a.d_bukti AS d_dt,
                                a.d_bukti,
                                SUBSTRING(a.i_bukti, 1, 7) AS i_bukti,
                                b.e_ikhp_typename,
                                a.i_coa,
                                0 AS v_terima_ku,
                                a.v_terima_tunai,
                                a.v_terima_giro,
                                0 AS v_keluar_ku,
                                a.v_keluar_tunai,
                                a.v_keluar_giro,
                                c.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_ikhp a,
                                tr_ikhp_type b,
                                tr_area c
                            WHERE
                                a.i_ikhp_type = b.i_ikhp_type
                                AND a.i_area = '$iarea'
                                AND a.i_area = c.i_area
                                AND d_bukti >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND d_bukti <= TO_DATE('$dto', 'dd-mm-yyyy')
                        UNION ALL
                            SELECT
                                0 AS urut,
                                a.d_tunai AS d_dt,
                                a.d_tunai AS d_bukti,
                                SUBSTRING(a.i_tunai, 1, 2)|| SUBSTRING(a.i_tunai, 10, 5) AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                a.v_jumlah AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                d.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_tunai a,
                                tr_area d
                            WHERE
                                a.i_area = d.i_area
                                AND a.f_tunai_cancel = 'f'
                                AND (a.d_tunai >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_tunai <= TO_DATE('$dto', 'dd-mm-yyyy'))
                                AND a.i_area = '$iarea'
                        UNION ALL
                            SELECT
                                1 AS urut,
                                c.d_rtunai AS d_dt,
                                c.d_rtunai AS d_bukti,
                                SUBSTRING(b.i_rtunai, 1, 1)|| SUBSTRING(b.i_rtunai, 3, 1)|| SUBSTRING(b.i_rtunai, 10, 5) AS i_bukti,
                                'Setoran Bank ' || e.e_bank_name AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                0 AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                b.v_jumlah AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                d.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_tunai a,
                                tm_rtunai_item b,
                                tm_rtunai c,
                                tr_area d,
                                tr_bank e
                            WHERE
                                a.i_tunai = b.i_tunai
                                AND a.i_area = b.i_area_tunai
                                AND b.i_rtunai = c.i_rtunai
                                AND b.i_area = c.i_area
                                AND a.i_area_rtunai = c.i_area
                                AND c.i_bank = e.i_bank
                                AND a.f_tunai_cancel = 'f'
                                AND c.f_rtunai_cancel = 'f'
                                AND a.i_area = d.i_area
                                AND (c.d_rtunai >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND c.d_rtunai <= TO_DATE('$dto', 'dd-mm-yyyy'))
                                AND a.i_area = '$iarea'
                        UNION ALL
                            SELECT
                                0 AS urut,
                                a.d_dt,
                                a.d_bukti,
                                SUBSTRING(a.i_pelunasan, 1, 7) AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                a.v_jumlah AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                c.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_pelunasan a,
                                tr_area c
                            WHERE
                                a.i_jenis_bayar = '02'
                                AND a.i_area = '$iarea'
                                AND a.i_area = c.i_area
                                AND a.f_pelunasan_cancel = 'f'
                                AND a.d_bukti >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_bukti <= TO_DATE('$dto', 'dd-mm-yyyy')
                        UNION ALL
                            SELECT
                                0 AS urut,
                                a.d_kum AS d_dt,
                                a.d_kum AS d_bukti,
                                SUBSTRING(a.i_kum, 1, 7) AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                a.v_jumlah AS v_terima_ku,
                                0 AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS ‹v_keluar_tunai,
                                0 AS v_keluar_giro,
                                b.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_kum a,
                                tr_area b
                            WHERE
                                (a.d_kum >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_kum <= TO_DATE('$dto', 'dd-mm-yyyy'))
                                AND a.f_kum_cancel = 'f'
                                AND a.i_area = '$iarea'
                                AND a.i_area = b.i_area
                        UNION ALL
                            SELECT
                                0 AS urut,
                                b.d_dt AS d_dt,
                                a.d_giro_terima AS d_bukti,
                                a.i_dt AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                0 AS v_terima_tunai,
                                a.v_jumlah AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                c.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tr_area c,
                                tm_giro a
                            LEFT JOIN tm_dt b ON
                                (a.i_dt = b.i_dt
                                AND a.i_area = b.i_area)
                            WHERE
                                a.i_area = '$iarea'
                                AND a.i_area = c.i_area
                                AND a.d_giro_terima >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_giro_terima <= TO_DATE('$dto', 'dd-mm-yyyy')
                                AND a.f_giro_batal_input = '0' ) AS x
                        GROUP BY
                            d_dt,
                            d_bukti,
                            urut,
                            i_bukti,
                            e_ikhp_typename,
                            i_coa,
                            e_area_name,
                            d_cek_ikhp ) AS y) AS x
                ORDER BY
                    x.id) AS xy
            WHERE
                xy.id > 0
            ORDER BY
                xy.id"
        , FALSE);
        $datatables->edit('v_terima_tunai', function ($data) {
            return number_format($data['v_terima_tunai']);
        });
        $datatables->edit('v_terima_giro', function ($data) {
            return number_format($data['v_terima_giro']);
        });
        $datatables->edit('v_keluar_giro', function ($data) {
            return number_format($data['v_keluar_giro']);
        });
        $datatables->edit('v_keluar_tunai', function ($data) {
            return number_format($data['v_keluar_tunai']);
        });
        $datatables->edit('saldotunai', function ($data) {
            return number_format($data['saldotunai']);
        });
        $datatables->edit('saldogiro', function ($data) {
            return number_format($data['saldogiro']);
        });
        return $datatables->generate();
    }

    public function saldoakhir($dfrom, $dto, $iarea, $saldoawalt, $saldoawalg, $xfrom){
        $PiutangDagang = PiutangDagang;
        return $this->db->query("
            SELECT
                xy.id,
                xy.d_bukti,
                xy.i_bukti,
                xy.e_ikhp_typename,
                xy.i_coa,
                xy.v_terima_tunai,
                xy.v_terima_giro,
                xy.v_keluar_tunai,
                xy.v_keluar_giro,
                xy.saldotunai,
                xy.saldogiro,
                xy.dcek
            FROM
                (
                SELECT
                    x.id,
                    x.d_bukti,
                    x.i_bukti,
                    x.e_ikhp_typename,
                    x.i_coa,
                    x.v_terima_tunai,
                    x.v_terima_giro,
                    x.v_keluar_tunai,
                    x.v_keluar_giro,
                    SUM(x.saldotunai) OVER (
                ORDER BY
                    x.id) AS saldotunai,
                    SUM(x.saldogiro) OVER (
                ORDER BY
                    x.id) AS saldogiro,
                    x.dcek
                FROM
                    (
                    SELECT
                        id,
                        d_bukti,
                        i_bukti,
                        e_ikhp_typename,
                        i_coa,
                        v_terima_tunai,
                        v_terima_giro,
                        v_keluar_tunai,
                        v_keluar_giro,
                        v_terima_tunai-v_keluar_tunai AS saldotunai,
                        v_terima_giro-v_keluar_giro AS saldogiro,
                        dcek
                    FROM
                        (
                        SELECT
                            0 AS id,
                            NULL AS d_bukti,
                            0 AS urut,
                            NULL AS i_bukti,
                            NULL AS e_ikhp_typename,
                            NULL AS i_coa,
                            '$saldoawalt' AS v_terima_tunai,
                            '$saldoawalg' AS v_terima_giro,
                            0 AS v_keluar_tunai,
                            0 AS v_keluar_giro,
                            0 AS saldotunai,
                            0 AS saldogiro,
                            NULL AS dcek
                        FROM
                            tm_ikhp_saldo
                        WHERE
                            i_area = '$iarea'
                            AND d_bukti = TO_DATE('$xfrom', 'dd-mm-yyyy')
                    UNION ALL
                        SELECT
                            ROW_NUMBER() OVER(
                        ORDER BY
                            x.d_bukti ASC,
                            x.urut,
                            substr(x.i_bukti,
                            1,
                            7)) AS id,
                            TO_CHAR(d_bukti, 'dd-mm-yyyy') AS d_bukti,
                            urut,
                            i_bukti,
                            e_ikhp_typename,
                            i_coa,
                            SUM(v_terima_ku) + SUM(v_terima_tunai) AS v_terima_tunai,
                            SUM(v_terima_giro) AS v_terima_giro,
                            SUM(v_keluar_ku) + SUM(v_keluar_tunai) AS v_keluar_tunai,
                            SUM(v_keluar_giro) AS v_keluar_giro,
                            (SUM(v_terima_ku) + SUM(v_terima_tunai)) - (SUM(v_keluar_ku) + SUM(v_keluar_tunai)) AS saldotunai,
                            SUM(v_terima_giro) - SUM(v_keluar_giro) AS saldogiro,
                            TO_CHAR(d_cek_ikhp::DATE, 'dd-mm-yyyy') AS dcek
                        FROM
                            (
                            SELECT
                                0 AS urut,
                                a.d_bukti AS d_dt,
                                a.d_bukti,
                                SUBSTRING(a.i_bukti, 1, 7) AS i_bukti,
                                b.e_ikhp_typename,
                                a.i_coa,
                                0 AS v_terima_ku,
                                a.v_terima_tunai,
                                a.v_terima_giro,
                                0 AS v_keluar_ku,
                                a.v_keluar_tunai,
                                a.v_keluar_giro,
                                c.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_ikhp a,
                                tr_ikhp_type b,
                                tr_area c
                            WHERE
                                a.i_ikhp_type = b.i_ikhp_type
                                AND a.i_area = '$iarea'
                                AND a.i_area = c.i_area
                                AND d_bukti >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND d_bukti <= TO_DATE('$dto', 'dd-mm-yyyy')
                        UNION ALL
                            SELECT
                                0 AS urut,
                                a.d_tunai AS d_dt,
                                a.d_tunai AS d_bukti,
                                SUBSTRING(a.i_tunai, 1, 2)|| SUBSTRING(a.i_tunai, 10, 5) AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                a.v_jumlah AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                d.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_tunai a,
                                tr_area d
                            WHERE
                                a.i_area = d.i_area
                                AND a.f_tunai_cancel = 'f'
                                AND (a.d_tunai >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_tunai <= TO_DATE('$dto', 'dd-mm-yyyy'))
                                AND a.i_area = '$iarea'
                        UNION ALL
                            SELECT
                                1 AS urut,
                                c.d_rtunai AS d_dt,
                                c.d_rtunai AS d_bukti,
                                SUBSTRING(b.i_rtunai, 1, 1)|| SUBSTRING(b.i_rtunai, 3, 1)|| SUBSTRING(b.i_rtunai, 10, 5) AS i_bukti,
                                'Setoran Bank ' || e.e_bank_name AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                0 AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                b.v_jumlah AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                d.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_tunai a,
                                tm_rtunai_item b,
                                tm_rtunai c,
                                tr_area d,
                                tr_bank e
                            WHERE
                                a.i_tunai = b.i_tunai
                                AND a.i_area = b.i_area_tunai
                                AND b.i_rtunai = c.i_rtunai
                                AND b.i_area = c.i_area
                                AND a.i_area_rtunai = c.i_area
                                AND c.i_bank = e.i_bank
                                AND a.f_tunai_cancel = 'f'
                                AND c.f_rtunai_cancel = 'f'
                                AND a.i_area = d.i_area
                                AND (c.d_rtunai >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND c.d_rtunai <= TO_DATE('$dto', 'dd-mm-yyyy'))
                                AND a.i_area = '$iarea'
                        UNION ALL
                            SELECT
                                0 AS urut,
                                a.d_dt,
                                a.d_bukti,
                                SUBSTRING(a.i_pelunasan, 1, 7) AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                a.v_jumlah AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                c.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_pelunasan a,
                                tr_area c
                            WHERE
                                a.i_jenis_bayar = '02'
                                AND a.i_area = '$iarea'
                                AND a.i_area = c.i_area
                                AND a.f_pelunasan_cancel = 'f'
                                AND a.d_bukti >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_bukti <= TO_DATE('$dto', 'dd-mm-yyyy')
                        UNION ALL
                            SELECT
                                0 AS urut,
                                a.d_kum AS d_dt,
                                a.d_kum AS d_bukti,
                                SUBSTRING(a.i_kum, 1, 7) AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                a.v_jumlah AS v_terima_ku,
                                0 AS v_terima_tunai,
                                0 AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS ‹v_keluar_tunai,
                                0 AS v_keluar_giro,
                                b.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tm_kum a,
                                tr_area b
                            WHERE
                                (a.d_kum >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_kum <= TO_DATE('$dto', 'dd-mm-yyyy'))
                                AND a.f_kum_cancel = 'f'
                                AND a.i_area = '$iarea'
                                AND a.i_area = b.i_area
                        UNION ALL
                            SELECT
                                0 AS urut,
                                b.d_dt AS d_dt,
                                a.d_giro_terima AS d_bukti,
                                a.i_dt AS i_bukti,
                                'Hasil Tagihan' AS e_ikhp_typename,
                                '$PiutangDagang' AS i_coa,
                                0 AS v_terima_ku,
                                0 AS v_terima_tunai,
                                a.v_jumlah AS v_terima_giro,
                                0 AS v_keluar_ku,
                                0 AS v_keluar_tunai,
                                0 AS v_keluar_giro,
                                c.e_area_name,
                                TO_CHAR(a.d_cek_ikhp, 'yyyy-mm-dd') AS d_cek_ikhp
                            FROM
                                tr_area c,
                                tm_giro a
                            LEFT JOIN tm_dt b ON
                                (a.i_dt = b.i_dt
                                AND a.i_area = b.i_area)
                            WHERE
                                a.i_area = '$iarea'
                                AND a.i_area = c.i_area
                                AND a.d_giro_terima >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                                AND a.d_giro_terima <= TO_DATE('$dto', 'dd-mm-yyyy')
                                AND a.f_giro_batal_input = '0' ) AS x
                        GROUP BY
                            d_dt,
                            d_bukti,
                            urut,
                            i_bukti,
                            e_ikhp_typename,
                            i_coa,
                            e_area_name,
                            d_cek_ikhp ) AS y) AS x
                ORDER BY
                    x.id) AS xy
            WHERE
                xy.id > 0
            ORDER BY
                xy.id DESC LIMIT 1"
        , FALSE);
    }

    public function ceksaldo($dto, $saldoakhirt, $saldoakhirg, $iarea){
        $dtox = date('Y-m-d', strtotime($dto));
        $this->db->select("* from tm_ikhp_saldo where i_area='$iarea' and d_bukti = to_date('$dto','dd-mm-yyyy')",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $this->db->query("
                UPDATE
                    tm_ikhp_saldo
                SET
                    v_saldo_akhirtunai = ".$saldoakhirt.",
                    v_saldo_akhirgiro = ".$saldoakhirg."
                WHERE
                    i_area = '$iarea'
                    AND d_bukti = TO_DATE('$dto', 'dd-mm-yyyy')
            ",false);
        }else{
            $this->db->query("
                INSERT
                    INTO
                    tm_ikhp_saldo
                VALUES('$iarea',
                '$dtox',
                '$saldoakhirt',
                '$saldoakhirg')
            ",false);
        }
    }

    public function updateikhp($iarea,$dfrom,$dto,$user){
        $dentry = current_datetime();
        $this->db->query(" UPDATE tm_ikhp SET i_cek_ikhp='$user', d_cek_ikhp='$dentry' WHERE
                i_area='$iarea' AND
                d_cek_ikhp isnull 
                AND 
                d_bukti >= to_date('$dfrom','dd-mm-yyyy') AND
                d_bukti <= to_date('$dto','dd-mm-yyyy') ");

        $this->db->query(" UPDATE tm_pelunasan SET i_cek_ikhp='$user', d_cek_ikhp='$dentry' WHERE  
                i_area='$iarea' AND
                d_cek_ikhp isnull 
                AND 
                d_bukti >= to_date('$dfrom','dd-mm-yyyy') AND
                d_bukti <= to_date('$dto','dd-mm-yyyy') ");

        $this->db->query(" UPDATE tm_giro SET i_cek_ikhp='$user', d_cek_ikhp='$dentry' WHERE  
                i_area='$iarea' AND
                d_cek_ikhp isnull 
                AND 
                d_giro_terima >= to_date('$dfrom','dd-mm-yyyy') AND
                d_giro_terima <= to_date('$dto','dd-mm-yyyy') ");

        $this->db->query(" UPDATE tm_tunai SET i_cek_ikhp='$user', d_cek_ikhp='$dentry' WHERE  
                i_area='$iarea' AND
                d_cek_ikhp isnull 
                AND 
                d_tunai >= to_date('$dfrom','dd-mm-yyyy') AND
                d_tunai <= to_date('$dto','dd-mm-yyyy') ");

        $this->db->query(" UPDATE tm_kum SET i_cek_ikhp='$user', d_cek_ikhp='$dentry' WHERE  
                i_area='$iarea' AND
                d_cek_ikhp isnull 
                AND 
                d_kum >= to_date('$dfrom','dd-mm-yyyy') AND
                d_kum <= to_date('$dto','dd-mm-yyyy') ");
    }
}

/* End of file Mmaster.php */
