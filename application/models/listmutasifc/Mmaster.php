<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($periode){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select row_number() over(order by x.i_product) as i, x.i_product, x.e_product_name, sum(x.n_saldo_awal) as saldo_awal, sum(x.n_deliver) as saldo_masuk, (sum(x.n_saldo_awal) - sum(x.n_deliver)) as saldo_akhir from(
							select a.i_product, b.e_product_name, a.n_saldo_awal, 0 as n_deliver
							from tm_saldoawal_fc a, tr_product b
							where a.i_product=b.i_product
							and a.e_periode='$periode'
							union all
							select a.i_product, a.e_product_name, 0 as n_saldo_awal, sum(a.n_deliver) as n_deliver
							from tm_dofc_item a, tm_dofc b
							where a.i_do = b.i_do and a.i_supplier = b.i_supplier
							and a.i_op = b.i_op
							and to_char(a.d_do,'YYYYMM')='$periode'
							and b.f_do_cancel = 'f'
							group by a.i_product, a.e_product_name
							) as x
							group by x.i_product, x.e_product_name
							order by x.i_product asc");
		$datatables->add('action', function ($data) {
			$iproduct    = trim($data['i_product']);
			$i   		 = trim($data['i']);
			$data = '';
			return $data;
		});
        return $datatables->generate();
	}
}

/* End of file Mmaster.php */