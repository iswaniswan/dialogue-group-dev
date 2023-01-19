<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function data($iperiode)
    {
        return $this->db->query("   SELECT
                                        a.i_product AS ic_product,
                                        b.e_product_name AS ic_product_name,
                                        a.i_product_motif AS ic_product_motif,
                                        a.i_product_grade AS ic_product_grade,
                                        a.f_ic_convertion,
                                        sum(a.n_ic_convertion) AS ic_n_convertion,
                                        b.i_product AS item_product,
                                        b.e_product_name AS item_product_name,
                                        b.i_product_motif AS item_product_motif,
                                        b.i_product_grade AS item_product_grade,
                                        sum(b.n_ic_convertion) AS item_n_convertion
                                    FROM
                                        tm_ic_convertion a,
                                        tm_ic_convertionitem b
                                    WHERE
                                        to_char(d_ic_convertion::timestamp with time zone, 'yyyymm'::text)= '$iperiode'
                                        AND a.i_ic_convertion = b.i_ic_convertion
                                    GROUP BY
                                        a.i_product,
                                        b.e_product_name,
                                        a.i_product_motif,
                                        a.i_product_grade,
                                        a.f_ic_convertion,
                                        b.i_product,
                                        b.e_product_name,
                                        b.i_product_motif,
                                        b.i_product_grade
                                    ORDER BY
                                        a.i_product ASC");
    }
    
    public function getAll($iperiode){
        return $this->db->query("SELECT
                                    a.i_product AS ic_product,
                                    b.e_product_name AS ic_product_name,
                                    a.i_product_motif AS ic_product_motif,
                                    a.i_product_grade AS ic_product_grade,
                                    a.f_ic_convertion,
                                    sum(a.n_ic_convertion) AS ic_n_convertion,
                                    b.i_product AS item_product,
                                    b.e_product_name AS item_product_name,
                                    b.i_product_motif AS item_product_motif,
                                    b.i_product_grade AS item_product_grade,
                                    sum(b.n_ic_convertion) AS item_n_convertion
                                FROM
                                    tm_ic_convertion a,
                                    tm_ic_convertionitem b
                                WHERE
                                    to_char(d_ic_convertion::timestamp with time zone, 'yyyymm'::text)= '$iperiode'
                                    AND a.i_ic_convertion = b.i_ic_convertion
                                GROUP BY
                                    a.i_product,
                                    b.e_product_name,
                                    a.i_product_motif,
                                    a.i_product_grade,
                                    a.f_ic_convertion,
                                    b.i_product,
                                    b.e_product_name,
                                    b.i_product_motif,
                                    b.i_product_grade
                                ORDER BY
                                    a.i_product ASC ", FALSE);
    }
}

/* End of file Mmaster.php */
