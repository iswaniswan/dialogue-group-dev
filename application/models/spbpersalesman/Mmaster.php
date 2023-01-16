<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom, $dto, $folder){
        $iperiode   = date('Ym', strtotime($dfrom));
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                area,
                salesman,
                SUM(y.v_target) AS target,
                SUM(x.v_spb) AS vspb,
                SUM(x.v_spb_bersih) AS vspbbersih,
                CASE WHEN SUM(v_target) = 0 THEN 0
                ELSE ((SUM(v_spb)-SUM(v_spb_discounttotal))/ SUM(v_target))* 100 END AS vs,
                '$folder' AS folder,
                x.i_salesman,
                e_salesman_name
            FROM
                (
                SELECT
                    a.i_area,
                    a.i_salesman,
                    e_salesman_name,
                    SUM(a.v_spb)-SUM(a.v_spb_discounttotal) AS v_spb_bersih,
                    a.i_area || ' - ' || e_area_name AS area,
                    a.i_salesman || ' - ' || c.e_salesman_name AS salesman,
                    SUM(a.v_spb) AS v_spb,
                    SUM(a.v_spb)-SUM(a.v_spb_discounttotal) AS bersih,
                    SUM(a.v_spb_discounttotal) AS v_spb_discounttotal
                FROM
                    tm_spb a,
                    tr_area b,
                    tr_salesman c
                WHERE
                    a.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                    AND a.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy')
                    AND a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.i_salesman = c.i_salesman
                GROUP BY
                    a.i_area,
                    a.i_salesman,
                    e_area_name,
                    e_salesman_name ) AS x
            LEFT JOIN tm_target_itemsls y ON
                (y.i_salesman = x.i_salesman
                AND x.i_area = y.i_area
                AND y.i_periode = '$iperiode')
            GROUP BY
                AREA,
                salesman,
                x.i_salesman,
                e_salesman_name
            ORDER BY
                AREA,
                salesman"
        , FALSE);
        $datatables->edit('target', function ($data) {
            return number_format($data['target']);
        });
        $datatables->edit('vspb', function ($data) {
            return number_format($data['vspb']);
        });
        $datatables->edit('vspbbersih', function ($data) {
            return number_format($data['vspbbersih']);
        });
        $datatables->edit('vs', function ($data) {
            return number_format($data['vs'],2)." %";
        });
        $datatables->add('action', function ($data) {
            $isales     = trim($data['i_salesman']);
            $folder     = $data['folder'];
            $esales     = $data['e_salesman_name'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/detail/$isales/$esales/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-check-square-o'></i></a>";
            return $data;
        });
        $datatables->hide('i_salesman');
        $datatables->hide('e_salesman_name');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function getnilai($dfrom, $dto){
        $iperiode   = date('Ym', strtotime($dfrom));
        return $this->db->query("
            SELECT 
                SUM(target) AS target,
                SUM(vspbbersih) AS bersih,
                SUM(vspb) AS kotor
            FROM(
                SELECT
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    area,
                    salesman,
                    SUM(y.v_target) AS target,
                    SUM(x.v_spb) AS vspb,
                    SUM(x.v_spb_bersih) AS vspbbersih,
                    CASE WHEN SUM(v_target) = 0 THEN 0
                    ELSE ((SUM(v_spb)-SUM(v_spb_discounttotal))/ SUM(v_target))* 100 END AS vs
                FROM
                    (
                    SELECT
                        a.i_area,
                        a.i_salesman,
                        SUM(a.v_spb)-SUM(a.v_spb_discounttotal) AS v_spb_bersih,
                        a.i_area || ' - ' || e_area_name AS area,
                        a.i_salesman || ' - ' || c.e_salesman_name AS salesman,
                        SUM(a.v_spb) AS v_spb,
                        SUM(a.v_spb)-SUM(a.v_spb_discounttotal) AS bersih,
                        SUM(a.v_spb_discounttotal) AS v_spb_discounttotal
                    FROM
                        tm_spb a,
                        tr_area b,
                        tr_salesman c
                    WHERE
                        a.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                        AND a.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy')
                        AND a.f_spb_cancel = 'f'
                        AND a.i_area = b.i_area
                        AND a.i_salesman = c.i_salesman
                    GROUP BY
                        a.i_area,
                        a.i_salesman,
                        e_area_name,
                        e_salesman_name ) AS x
                LEFT JOIN tm_target_itemsls y ON
                    (y.i_salesman = x.i_salesman
                    AND x.i_area = y.i_area
                    AND y.i_periode = '$iperiode')
                GROUP BY
                    AREA,
                    salesman
                ORDER BY
                    AREA,
                    salesman
            ) AS x
        ", FALSE);
    }

    public function getdetail($dfrom, $dto, $isalesman){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                d.i_product,
                d.e_product_name,
                SUM(d.n_order) AS n_order
            FROM
                tm_spb a,
                tr_salesman c,
                tm_spb_item d
            WHERE
                a.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy')
                AND a.f_spb_cancel = 'f'
                AND a.i_salesman = '$isalesman'
                AND a.i_salesman = c.i_salesman
                AND a.i_spb = d.i_spb
                AND a.i_area = d.i_area
            GROUP BY
                d.i_product,
                d.e_product_name
            ORDER BY
                d.i_product,
                d.e_product_name"
        , FALSE);
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
