<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data(){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                    SELECT 
                      a.i_product, 
                      a.e_product_name, 
                      a.v_product_retail, 
                      a.d_product_priceentry, 
                      a.d_product_priceupdate
                    FROM 
                      tr_product_price a, 
                      tr_product b, 
                      tr_price_group c
                    WHERE 
                      a.i_product=b.i_product 
                      AND a.i_price_group=c.i_price_group
                      AND b.f_product_pricelist='t'
                    ORDER BY 
                      a.e_product_name, 
                      a.i_price_group"
                    , FALSE);

        $datatables->edit('v_product_retail', function ($data) {
            return number_format($data['v_product_retail']);
        });

        $datatables->edit('d_product_priceentry', function ($data) {
          $d_product_priceentry = $data['d_product_priceentry'];
          if($d_product_priceentry == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_product_priceentry) );
          }
        });

        $datatables->edit('d_product_priceupdate', function ($data) {
          $d_product_priceupdate = $data['d_product_priceupdate'];
          if($d_product_priceupdate == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_product_priceupdate) );
          }
        });

        return $datatables->generate();
    }
}

/* End of file Mmaster.php */