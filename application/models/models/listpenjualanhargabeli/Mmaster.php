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
                tm_nota_item.i_product) AS i,
                tm_nota_item.i_product,
                tm_nota_item.e_product_name,
                SUM(tm_nota_item.n_deliver) AS n_deliver,
                tr_harga_beli.v_product_mill,
                (tr_harga_beli.v_product_mill* SUM(tm_nota_item.n_deliver)) AS total_harga_beli
            FROM
                tm_nota,
                tm_nota_item,
                tr_harga_beli,
                tr_product_type,
                tr_product
            WHERE
                tm_nota.i_sj = tm_nota_item.i_sj
                AND tm_nota.i_area = tm_nota_item.i_area
                AND tr_harga_beli.i_product = tm_nota_item.i_product
                AND tr_harga_beli.i_product_grade = tm_nota_item.i_product_grade
                AND tm_nota.f_nota_cancel = FALSE
                AND tr_harga_beli.i_price_group = '00'
                AND tm_nota.i_nota IS NOT NULL
                AND tm_nota.i_nota LIKE 'FP-$thbl%'
                AND tr_product.i_product_type = tr_product_type.i_product_type
                AND tr_product.i_product = tm_nota_item.i_product
            GROUP BY
                tm_nota_item.i_product,
                tm_nota_item.e_product_name,
                tm_nota_item.i_product_motif,
                tm_nota_item.i_product_grade,
                tr_product_type.e_product_typename,
                tr_harga_beli.v_product_mill
            ORDER BY
                tm_nota_item.i_product"
        , FALSE);
        $datatables->edit('n_deliver', function ($data) {
            return number_format($data['n_deliver']);
        });
        $datatables->edit('v_product_mill', function ($data) {
            return number_format($data['v_product_mill']);
        });
        $datatables->edit('total_harga_beli', function ($data) {
            return number_format($data['total_harga_beli']);
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
                    SUM(tm_nota_item.n_deliver) AS totalqty,
                    (tr_harga_beli.v_product_mill* SUM(tm_nota_item.n_deliver)) AS totalnilai
                FROM
                    tm_nota,
                    tm_nota_item,
                    tr_harga_beli,
                    tr_product_type,
                    tr_product
                WHERE
                    tm_nota.i_sj = tm_nota_item.i_sj
                    AND tm_nota.i_area = tm_nota_item.i_area
                    AND tr_harga_beli.i_product = tm_nota_item.i_product
                    AND tr_harga_beli.i_product_grade = tm_nota_item.i_product_grade
                    AND tm_nota.f_nota_cancel = FALSE
                    AND tr_harga_beli.i_price_group = '00'
                    AND tm_nota.i_nota IS NOT NULL
                    AND tm_nota.i_nota LIKE 'FP-$thbl%'
                    AND tr_product.i_product_type = tr_product_type.i_product_type
                    AND tr_product.i_product = tm_nota_item.i_product
                GROUP BY
                    tr_harga_beli.v_product_mill) AS x"
        , FALSE);
    }
}

/* End of file Mmaster.php */
