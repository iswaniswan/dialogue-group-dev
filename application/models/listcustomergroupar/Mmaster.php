<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data(){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                  SELECT  
                     a.i_customer, 
                     a.i_customer_groupar as i_group
                  FROM 
                     tr_customer_groupar a
                  ORDER BY 
                     a.i_customer"
                , FALSE);
         return $datatables->generate();
   }
}

/* End of file Mmaster.php */
