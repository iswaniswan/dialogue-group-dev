<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
        $qperiode = $this->db->query("select i_periode from tm_periode")->row();
        $periodesekarang = $qperiode->i_periode;
        $periodekemarin = ($periodesekarang - 1);
        $periodebesok = ($periodesekarang + 1);

		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("select a.i_notapb, a.d_notapb, a.i_customer, a.i_product, a.e_product_name, a.v_product_retail, a.kuduna, a.i_cek, '$i_menu' as i_menu from (
        select a.d_notapb, (a.v_unit_price*  ROUND(((100 -f.n_margin) /100 ), 2)) as kuduna, d.v_product_retail, (a.v_unit_price*  ROUND(((100 -f.n_margin) /100 ), 2) )-d.v_product_retail as selisih, a.i_product, a.i_customer,
        a.i_price_groupco, a.e_product_name, a.i_notapb, ROUND(((100 -f.n_margin) /100 ), 2), b.i_cek
        from tm_notapb_item a, tm_notapb b, tr_product_priceco c, tr_product_price d, tr_customer_consigment e, tr_price_groupco f
        where a.i_notapb=b.i_notapb and a.i_area=b.i_area and a.i_customer=b.i_customer 
        and (to_char(a.d_notapb,'yyyymm')='$periodekemarin' or to_char(a.d_notapb,'yyyymm')='$periodesekarang' or to_char(a.d_notapb,'yyyymm')='$periodebesok')
        and a.i_product=c.i_product and a.i_price_groupco=c.i_price_group
        and a.i_product=d.i_product and a.i_price_groupco=d.i_price_group
        and a.i_customer = e.i_customer
        and b.i_customer = e.i_customer
        and e.i_price_groupco = f.i_price_groupco
        and b.i_spb isnull
        order by a.i_price_groupco, a.i_product
        ) as a
        where selisih<>0");
        $datatables->edit('i_cek', function ($data) {
            $i_cek = $data['i_cek'];
            if($i_cek == ''){
              return "Belum Cek";
            }elseif ($i_cek == null) {
              return "Belum Cek";
            }else{
              return "Sudah Cek";
            }
         });

		$datatables->add('action', function ($data) {
            $i_notapb = trim($data['i_notapb']);
            $i_customer = trim($data['i_customer']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 4)){
                $data .= "<a href=\"#\" onclick='show(\"hargaketimpakons/cform/delete/$i_customer/$i_notapb/\",\"#main\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}
	
}

/* End of file Mmaster.php */
