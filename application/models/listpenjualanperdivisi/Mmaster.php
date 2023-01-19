<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom,$dto){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                    SELECT
                        x.i_area,
                        x.e_area_name,
                        x.e_product_groupname,
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
                    x 
                    GROUP BY
                    x.i_area,
                    x.e_area_name,
                    x.i_product_group,
                    x.e_product_groupname 
                    order by
                    x.i_area,
                    x.e_product_groupname"
                , FALSE);

        /*$datatables->edit('jumlah', function ($data) {
            return number_format($data['jumlah'],2);
        });
        $datatables->edit('jml_netto', function ($data) {
            return number_format($data['jml_netto'],2);
        });*/
        return $datatables->generate();
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
}

/* End of file Mmaster.php */
