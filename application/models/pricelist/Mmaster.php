<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function bacasemua(){
		$query = $this->db->query(" select * from crosstab(
                                    'select a.i_product, b.i_product_type, c.e_product_typename, d.i_product_class,
                                     b.e_product_name,c.i_product_group, x.e_product_groupname, d.e_product_classname, 
                                     e.i_product_category, e.e_product_categoryname, b.f_product_pricelist, b.d_product_register, g.v_product_mill, 
                                     b.i_product_status, y.e_product_statusname, b.i_supplier, f.e_supplier_name, 
                                     a.i_price_group, sum(a.v_product_retail)
                                     from tr_product_price a, tr_product b
                                     left join tr_harga_beli g on (b.i_product=g.i_product and g.i_price_group=''00'')
                                     left join tr_product_type c on (b.i_product_type=c.i_product_type)
                                     left join tr_product_class d on (b.i_product_class=d.i_product_class)
                                     left join tr_product_group x on (x.i_product_group=c.i_product_group)
                                     left join tr_product_status y on (y.i_product_status=b.i_product_status)
                                     left join tr_product_category e on (b.i_product_category=e.i_product_category 
                                          and b.i_product_class=e.i_product_class), tr_supplier f
                                     where a.i_product=b.i_product and b.i_supplier=f.i_supplier and b.f_product_pricelist=''t''
                                     and a.i_product_grade=''A''
                                     group by a.i_product, a.i_price_group, b.i_product_type, c.e_product_typename, d.i_product_class, 
                                     b.e_product_name,c.i_product_group, x.e_product_groupname,
                                     d.e_product_classname, e.i_product_category, e.e_product_categoryname, b.f_product_pricelist,
                                     b.d_product_register, g.v_product_mill, b.i_product_status, y.e_product_statusname, b.i_supplier, 
                                     f.e_supplier_name
                                     order by a.i_product, a.i_price_group','select i_price_group from tr_price_group order by i_price_group ')
                                     as
                                    (i_product text, i_product_type text, e_product_typename text, i_product_class text, e_product_name text,
                                     i_product_group text, e_product_groupname text, 
                                     e_product_classname text, i_product_category text, e_product_categoryname text, f_product_pricelist boolean,
                                     d_product_register date, v_product_mill numeric, i_product_status text, e_product_statusname text, 
                                     i_supplier text, e_supplier_name text, 
                                     h00 numeric, h01 numeric, h02 numeric, h03 numeric, h04 numeric, 
                                     h05 numeric, h06 numeric , hG0 numeric, hG2 numeric, hG3 numeric, hG5 numeric)",false); //and b.f_product_pricelist=''t''
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
}

/* End of file Mmaster.php */
