<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom,$dto){
        $dfrom = date('Y-m-d', strtotime($dfrom));
        $dto   = date('Y-m-d', strtotime($dto));        
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_area,
                a.e_area_name,
                b.e_product_groupname,
                a.i_product_group,
                SUM(a.jumlah) AS jumlah,
                SUM(a.netto) AS netto,
                SUM(a.n_spb) AS n_spb
            FROM
                (
                SELECT
                    a.i_area,
                    b.e_area_name,
                    d.i_product_group,
                    0 AS jumlah,
                    SUM(a.v_spb-a.v_spb_discounttotal) AS netto,
                    0 AS n_spb
                FROM
                    tr_area b,
                    tr_product_group d,
                    tm_spb a
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 'f'
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                    AND a.i_product_group = d.i_product_group
                GROUP BY
                    a.i_area,
                    d.i_product_group,
                    b.e_area_name
            UNION ALL
                SELECT
                    a.i_area,
                    b.e_area_name,
                    d.i_product_group,
                    SUM(c.v_unit_price*c.n_order) AS jumlah,
                    0 AS netto,
                    SUM(c.n_order) AS n_spb
                FROM
                    tr_area b,
                    tr_product_group d,
                    tm_spb a,
                    tm_spb_item c
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 'f'
                    AND a.i_spb = c.i_spb
                    AND a.i_area = c.i_area
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                    AND a.i_product_group = d.i_product_group
                GROUP BY
                    a.i_area,
                    d.i_product_group,
                    b.e_area_name
            UNION ALL
                SELECT
                    a.i_area,
                    b.e_area_name,
                    'PB' AS i_product_group,
                    0 AS jumlah,
                    SUM(a.v_spb-a.v_spb_discounttotal) AS netto,
                    0 AS n_spb
                FROM
                    tr_area b,
                    tm_spb a
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 't'
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                GROUP BY
                    a.i_area,
                    b.e_area_name
            UNION ALL
                SELECT
                    a.i_area,
                    b.e_area_name,
                    'PB' AS i_product_group,
                    SUM(c.v_unit_price*c.n_order) AS jumlah,
                    0 AS netto,
                    SUM(c.n_order) AS n_spb
                FROM
                    tr_area b,
                    tm_spb a,
                    tm_spb_item c
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 't'
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                    AND a.i_spb = c.i_spb
                    AND a.i_area = c.i_area
                GROUP BY
                    a.i_area,
                    b.e_area_name ) AS a
            INNER JOIN tr_product_group b ON (b.i_product_group = a.i_product_group)
            GROUP BY
                a.i_area,
                a.e_area_name,
                a.i_product_group,
                b.e_product_groupname
            ORDER BY
                a.i_product_group,
                a.i_area,
                a.e_area_name"
        , FALSE);
        $datatables->edit('jumlah', function ($data) {
            return number_format($data['jumlah']);
        });
        $datatables->edit('netto', function ($data) {
            return number_format($data['netto']);
        });
        $datatables->edit('n_spb', function ($data) {
            return number_format($data['n_spb']);
        });
        $datatables->hide('i_product_group');
        return $datatables->generate();
    }

    public function total($dfrom, $dto){
        $dfrom = date('Y-m-d', strtotime($dfrom));
        $dto   = date('Y-m-d', strtotime($dto));        
        return $this->db->query("
            SELECT
                SUM(a.jumlah) AS jumlah,
                SUM(a.netto) AS netto,
                SUM(a.n_spb) AS n_spb
            FROM
                (
                SELECT
                    a.i_area,
                    b.e_area_name,
                    d.i_product_group,
                    0 AS jumlah,
                    SUM(a.v_spb-a.v_spb_discounttotal) AS netto,
                    0 AS n_spb
                FROM
                    tr_area b,
                    tr_product_group d,
                    tm_spb a
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 'f'
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                    AND a.i_product_group = d.i_product_group
                GROUP BY
                    a.i_area,
                    d.i_product_group,
                    b.e_area_name
            UNION ALL
                SELECT
                    a.i_area,
                    b.e_area_name,
                    d.i_product_group,
                    SUM(c.v_unit_price*c.n_order) AS jumlah,
                    0 AS netto,
                    SUM(c.n_order) AS n_spb
                FROM
                    tr_area b,
                    tr_product_group d,
                    tm_spb a,
                    tm_spb_item c
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 'f'
                    AND a.i_spb = c.i_spb
                    AND a.i_area = c.i_area
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                    AND a.i_product_group = d.i_product_group
                GROUP BY
                    a.i_area,
                    d.i_product_group,
                    b.e_area_name
            UNION ALL
                SELECT
                    a.i_area,
                    b.e_area_name,
                    'PB' AS i_product_group,
                    0 AS jumlah,
                    SUM(a.v_spb-a.v_spb_discounttotal) AS netto,
                    0 AS n_spb
                FROM
                    tr_area b,
                    tm_spb a
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 't'
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                GROUP BY
                    a.i_area,
                    b.e_area_name
            UNION ALL
                SELECT
                    a.i_area,
                    b.e_area_name,
                    'PB' AS i_product_group,
                    SUM(c.v_unit_price*c.n_order) AS jumlah,
                    0 AS netto,
                    SUM(c.n_order) AS n_spb
                FROM
                    tr_area b,
                    tm_spb a,
                    tm_spb_item c
                WHERE
                    a.f_spb_cancel = 'f'
                    AND a.i_area = b.i_area
                    AND a.f_spb_consigment = 't'
                    AND a.d_spb >= '$dfrom'
                    AND a.d_spb <= '$dto'
                    AND a.i_spb = c.i_spb
                    AND a.i_area = c.i_area
                GROUP BY
                    a.i_area,
                    b.e_area_name 
            ) AS a"
        , FALSE);
    }
}

/* End of file Mmaster.php */
