<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("select a.i_spg, a.e_spg_name, a.i_customer, b.e_customer_name, c.e_area_name, '$i_menu' as i_menu
        from tr_spg a, tr_customer b, tr_area c
        where a.i_customer=b.i_customer and a.i_area=c.i_area");

        return $datatables->generate();
	}
	
}

/* End of file Mmaster.php */
