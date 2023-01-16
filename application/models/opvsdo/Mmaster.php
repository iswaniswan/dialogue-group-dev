<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacasupplier($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_supplier,
                e_supplier_name
            FROM
                tr_supplier
            WHERE
                (UPPER(e_supplier_name) LIKE '%$cari%'
                OR UPPER(i_supplier) LIKE '%$cari%')
            ORDER BY
                e_supplier_name
        ", FALSE);
    }

	public function data($dfrom,$dto,$isupplier){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                x.e_supplier_name,
                x.i_op,
                to_char(x.d_op, 'dd-mm-yyyy') AS d_op,
                x.i_product,
                x.n_order,
                x.e_area_name,
                x.v_product_mill,
                d.i_do,
                to_char(d.d_do, 'dd-mm-yyyy') AS d_do,
                f.i_product AS proddo,
                f.n_deliver,
                (cast(d.d_do AS date))-(cast(x.d_op AS date)) AS selisih
            FROM
                (
                SELECT
                    e.i_op, a.d_op, a.i_supplier, a.i_area, a.i_op_old, b.e_supplier_name, c.e_area_name, e.i_product, e.n_order, e.v_product_mill
                FROM
                    tm_op a, tr_supplier b, tr_area c, tm_op_item e
                WHERE
                    a.i_op = e.i_op
                    AND a.i_supplier = b.i_supplier
                    AND a.i_area = c.i_area
                    AND a.i_supplier = '$isupplier'
                    AND a.d_op >= to_date('$dfrom', 'dd-mm-yyyy')
                    AND a.d_op <= to_date('$dto', 'dd-mm-yyyy') ) AS x
            LEFT JOIN tm_do d ON
                (x.i_op = d.i_op
                AND x.i_area = d.i_area)
            LEFT JOIN tm_do_item f ON
                (x.i_op = f.i_op
                AND d.i_do = f.i_do
                AND d.i_op = f.i_op
                AND x.i_product = f.i_product)
            ORDER BY
                x.i_op"
        , FALSE);
        $datatables->edit('selisih', function ($data) {
            $selisih = $data['selisih'];
            if($selisih == '' || $selisih == null){
                return '';
            }else{
                return $selisih." Hari";
            }
        });
        return $datatables->generate();
	}
}

/* End of file Mmaster.php */
