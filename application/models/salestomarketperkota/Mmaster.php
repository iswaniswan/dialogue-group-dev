<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $iarea, $icity){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT distinct
                                a.i_product,
                                a.e_product_name,
                                SUM(a.n_deliver) AS total,
                                a.v_unit_price, 
                                SUM(a.n_deliver*a.v_unit_price) AS njual, 
                                a.i_area, 
                                b.e_city_name
                            FROM 
                                tm_nota_item a, 
                                tr_city b, 
                                tm_nota c, 
                                tr_customer d
                            WHERE 
                                a.i_area=b.i_area 
                                AND b.i_city=d.i_city
                                AND a.i_nota=c.i_nota 
                                AND c.i_customer=d.i_customer
                                AND a.i_area='$iarea' 
                                AND b.i_city='$icity'
                                AND a.d_nota >= to_date('$dfrom','dd-mm-yyyy')
                                AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
                            GROUP BY 
                                a.i_product, 
                                a.e_product_name, 
                                a.v_unit_price,
                                a.i_area, 
                                b.e_city_name
                            ORDER BY 
                                a.i_product"
                        ,false);

        $datatables->edit('v_unit_price', function ($data) {
            $v_unit_price = $data['v_unit_price'];
            return number_format($v_unit_price);
        });

        $datatables->edit('njual', function ($data) {
            $njual = $data['njual'];
            return number_format($njual);
        });

        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
