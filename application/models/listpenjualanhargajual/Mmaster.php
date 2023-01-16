<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($tahun,$bulan){
        $th = date('y', strtotime($tahun));
        $thbl = $th.$bulan ;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                ROW_NUMBER() OVER(
            ORDER BY
                a.i_product) AS i,
                a.i_product,
                a.e_product_name,
                SUM(a.n_deliver) AS n_deliver,
                b.i_price_group,
                a.v_unit_price,
                (a.v_unit_price * SUM(a.n_deliver)) AS total_harga_jual
            FROM
                tm_nota_item a,
                tr_customer b,
                tm_nota c,
                tr_product_price d
            WHERE
                a.i_nota LIKE 'FP-$thbl%'
                AND a.i_nota = c.i_nota
                AND a.i_area = c.i_area
                AND b.i_customer = c.i_customer
                AND a.i_product = d.i_product
                AND a.i_product_grade = d.i_product_grade
                AND b.i_price_group = d.i_price_group
            GROUP BY
                a.i_product,
                a.e_product_name,
                a.i_product_motif,
                a.i_product_grade,
                b.i_price_group,
                a.v_unit_price
            ORDER BY
                a.i_product"
        , FALSE);
        $datatables->edit('n_deliver', function ($data) {
            return number_format($data['n_deliver']);
        });
        $datatables->edit('v_unit_price', function ($data) {
            return number_format($data['v_unit_price']);
        });
        $datatables->edit('total_harga_jual', function ($data) {
            return number_format($data['total_harga_jual']);
        });
        return $datatables->generate();
    }

    public function total($tahun, $bulan){  
        $th = date('y', strtotime($tahun));
        $thbl = $th.$bulan ;    
        return $this->db->query("
            SELECT
                SUM(totalqty) AS totalqty,
                SUM(totalnilai) AS totalnilai
            FROM
                (
                SELECT
                    SUM(a.n_deliver) AS totalqty,
                    (a.v_unit_price * SUM(a.n_deliver)) AS totalnilai
                FROM
                    tm_nota_item a,
                    tr_customer b,
                    tm_nota c,
                    tr_product_price d
                WHERE
                    a.i_nota LIKE 'FP-$thbl%'
                    AND a.i_nota = c.i_nota
                    AND a.i_area = c.i_area
                    AND b.i_customer = c.i_customer
                    AND a.i_product = d.i_product
                    AND a.i_product_grade = d.i_product_grade
                    AND b.i_price_group = d.i_price_group
                GROUP BY
                    a.v_unit_price) AS x"
        , FALSE);
    }
}

/* End of file Mmaster.php */
