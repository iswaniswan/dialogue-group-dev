<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($igroup){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT 
                                a.i_product,
                                b.e_product_name, 
                                a.i_price_group,
                                d.e_price_groupconame,
                                c.i_product_grade,
                                b.v_product_retail
                            FROM 
                                tr_product b, 
                                tr_product_grade c, 
                                tr_product_priceco a, 
                                tr_price_groupco d 
                            WHERE 
                                a.i_product=b.i_product 
                                and a.i_product_grade=c.i_product_grade 
                                and a.i_price_groupco=d.i_price_groupco 
                                and d.i_price_groupco ='$igroup'
                            ORDER BY 
                                b.e_product_name, 
                                a.i_price_group"
                        ,false);
        $datatables->edit('v_product_retail', function ($data) {
            $v_product_retail = $data['v_product_retail'];
            return number_format($v_product_retail);
        });
                
        return $datatables->generate();
    }

    function bacabarang($igroup){
        return $this->db->query(" select a.*, b.e_product_name, c.e_product_gradename, d.e_price_groupconame
		                from tr_product b, tr_product_grade c, tr_product_priceco a, tr_price_groupco d 
		                where a.i_product=b.i_product and a.i_product_grade=c.i_product_grade and 
		                a.i_price_groupco=d.i_price_groupco and d.i_price_groupco ='$igroup'
		                order by b.e_product_name, a.i_price_group", false);
    }
}

/* End of file Mmaster.php */
