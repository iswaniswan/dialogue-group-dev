<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacaperiode($iperiode,$iarea){
        return $this->db->query(" 
                                SELECT
                                    a.i_customer, 
                                    b.e_customer_name, 
                                    b.e_customer_address, 
                                    c.e_city_name, 
                                    a.i_salesman, 
                                    sum(a.v_nota_gross) as nota,
                                    sum(a.v_nota_netto) as bersih
                                FROM 
                                    tm_nota a, 
                                    tr_customer b, 
                                    tr_city c
                                WHERE 
                                    a.f_nota_cancel='f'
                                    AND to_char(a.d_nota,'yyyymm')='$iperiode' 
                                    AND NOT a.i_nota isnull and a.i_area='$iarea'
                                    AND a.i_customer=b.i_customer and b.i_city=c.i_city and b.i_area=c.i_area
                                GROUP BY 
                                    a.i_customer, 
                                    b.e_customer_name, 
                                    b.e_customer_address, 
                                    c.e_city_name, 
                                    a.i_salesman
                                ORDER BY 
                                    c.e_city_name ");
    }
   
}

/* End of file Mmaster.php */
