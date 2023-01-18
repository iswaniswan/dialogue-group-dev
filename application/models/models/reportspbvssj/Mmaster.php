<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    // herdin 24 Maret 2020
    public function bacasupplier(){
        return $this->db->order_by('i_supplier','ASC')->get('tr_supplier')->result();
    }

    // public function total($dfrom,$dto) {
    //   return $this->db->query("
    //             select sum(jml.qty_op) as op ,sum(jml.qty_btb) as btb,sum(jml.sisa) as sisa,count(*) as size 
    //               from (
    //                 select final.*, final.qty_op - final.qty_btb as sisa from (
    //                   select y.i_supplier, y.i_op, y.d_op, y.qty_op, case when x.qty_btb is null then 0 else x.qty_btb end as qty_btb from (
    //                     select o.i_supplier, spd.i_op, sum (spd.n_qty) as qty_btb from tm_sj_pembelian sp
    //                     inner join tm_sj_pembelian_detail spd on (sp.i_btb = spd.i_btb)
    //                     inner join tm_opbb o on  (o.i_supplier = sp.i_supplier and o.i_op = spd.i_op)
    //                     where o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f' and f_sj_cancel = 'f'
    //                     group by o.i_supplier, spd.i_op 
    //                     ) as x
    //                     full join (
    //                       select o.i_supplier, o.i_op, o.d_op, sum(n_quantity) as qty_op from tm_opbb_item oi
    //                       inner join tm_opbb o on (o.i_op = oi.i_op)
    //                       where o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f'
    //                       group by o.i_supplier, o.i_op, o.d_op
    //                     ) as y
    //                     on (x.i_supplier = y.i_supplier and y.i_op = x.i_op)
    //                 ) as final
    //                 where final.qty_op - final.qty_btb > 0
    //               ) as jml
    //         "
    //     , FALSE);
    // }

    public function data($i_menu, $dfrom,$dto){
        // $th = date('y', strtotime($tahun));
        // $thbl = $th.$bulan ;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT ROW_NUMBER() OVER(ORDER BY a.i_op) as i, a.i_op_code, b.i_do, c.d_op, d.d_do, a.i_product, e.e_product_basename,
        a.n_count as qtyop, b.n_deliver as qtydo, a.v_price,
        (a.n_count * a.v_price) as totalop, (b.n_deliver * a.v_price) as totaldo,
        ((b.n_deliver / a.n_count) * 100) ::numeric(5,2) as persentase,
        case when a.n_count = b.n_deliver then 'Complete' else 'Belum Complete' end as status, '$i_menu' as i_menu 
        from tm_op_item a
        left join tm_do_item b on (a.i_op_code = b.i_op and a.i_product = b.i_product and a.i_color = b.i_color)
        inner join tm_op c on a.i_op_code = c.i_op_code
        inner join tm_do d on b.i_do = d.i_do
        inner join tr_product_base e on a.i_product = e.i_product_base
        where c.d_op >= '$dfrom' and c.d_op <= '$dto'"
        , FALSE);
        // $datatables->edit('n_deliver', function ($data) {
        //     return number_format($data['n_deliver']);
        // });
        // $datatables->edit('v_unit_price', function ($data) {
        //     return number_format($data['v_unit_price']);
        // });
        // $datatables->edit('total_harga_jual', function ($data) {
        //     return number_format($data['total_harga_jual']);
        // });
        $datatables->hide('v_price');
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
