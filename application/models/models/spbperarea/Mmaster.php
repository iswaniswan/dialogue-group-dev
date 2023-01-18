<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom, $dto, $folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                a.i_area||' - '||e_area_name AS area,
                c.v_target,
                SUM(a.v_spb) AS v_spb,
                SUM(a.v_spb_discounttotal) AS v_spb_discounttotal,
                SUM(a.v_spb)-SUM(a.v_spb_discounttotal) AS bersih,
                '$folder' AS folder
            FROM
                tm_spb a,
                tr_area b,
                tm_target c
            WHERE
                a.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy')
                AND a.i_area = b.i_area
                AND a.i_area = c.i_area
                AND a.f_spb_cancel = 'f'
                AND TO_CHAR(a.d_spb, 'yyyymm')= c.i_periode
            GROUP BY
                c.v_target,
                a.i_area,
                b.e_area_name
            ORDER BY
                a.i_area,
                b.e_area_name,
                c.v_target"
        , FALSE);
        $datatables->edit('v_target', function ($data) {
            return number_format($data['v_target']);
        });
        $datatables->edit('v_spb', function ($data) {
            return number_format($data['v_spb']);
        });
        $datatables->edit('v_spb_discounttotal', function ($data) {
            return number_format($data['v_spb_discounttotal']);
        });
        $datatables->edit('bersih', function ($data) {
            return number_format($data['bersih']);
        });
        $datatables->hide('folder');
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
