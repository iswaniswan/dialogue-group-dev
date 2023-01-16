<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    // herdin 24 Maret 2020
    public function bacasupplier(){
        return $this->db->order_by('e_supplier_name','ASC')->get('tr_supplier')->result();
    }

    // public function total($supplier,$dfrom,$dto) {
    //   return $this->db->query("
    //             select sum(jml.qty_op) as op ,sum(jml.qty_btb) as btb,sum(jml.sisa) as sisa,count(*) as size 
    //               from (
    //                 select final.*, final.qty_op - final.qty_btb as sisa from (
    //                   select y.i_supplier, y.i_op, y.d_op, y.qty_op, case when x.qty_btb is null then 0 else x.qty_btb end as qty_btb from (
    //                     select o.i_supplier, spd.i_op, sum (spd.n_qty) as qty_btb from tm_sj_pembelian sp
    //                     inner join tm_sj_pembelian_detail spd on (sp.i_btb = spd.i_btb)
    //                     inner join tm_opbb o on  (o.i_supplier = sp.i_supplier and o.i_op = spd.i_op)
    //                     where sp.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f' and f_sj_cancel = 'f'
    //                     group by o.i_supplier, spd.i_op 
    //                     ) as x
    //                     full join (
    //                       select o.i_supplier, o.i_op, o.d_op, sum(n_quantity) as qty_op from tm_opbb_item oi
    //                       inner join tm_opbb o on (o.i_op = oi.i_op)
    //                       where o.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f'
    //                       group by o.i_supplier, o.i_op, o.d_op
    //                     ) as y
    //                     on (x.i_supplier = y.i_supplier and y.i_op = x.i_op)
    //                 ) as final
    //                 where final.qty_op - final.qty_btb > 0
    //               ) as jml
    //         "
    //     , FALSE);
    // }

    public function data($periode, $dfrom, $dto){
        
        $datatables = new Datatables(new CodeigniterAdapter);
        
        $datatables->query("SELECT ROW_NUMBER() OVER(
            ORDER BY a.i_product) AS i, a.i_fc, a.i_product, b.e_product_basename
            , d.e_color_name, a.n_quantity as qtyforecast
            ,sum(e.n_count) as qtyop, a.n_quantity - sum(e.n_count) as sisafc
            , ((sum(e.n_count)/a.n_quantity)* 100)::numeric(10,2) as fcvsop, a.n_quantity as qtyforecast1
            , sum(g.n_deliver) as qtydo, a.n_quantity - sum(g.n_deliver)as sisafc1
            , ((sum(g.n_deliver)/a.n_quantity)* 100)::numeric(10,2) as fcvsdo
            from tm_fc_distributor_detail a
            inner join tr_product_base b on a.i_product = b.i_product_motif
            inner join tm_fc_distributor c on a.i_fc = c.i_fc 
            inner join tr_color d on a.i_color = d.i_color
            inner join tm_op_item e  on a.i_product = e.i_product and a.i_color = e.i_color
            inner join tm_op f on e.i_op_code = f.i_op_code
            inner join tm_do_item g on a.i_product = g.i_product and a.i_color = g.i_color
            inner join tm_do h on g.i_do = h.i_do
            where c.e_fc_periode = '$periode' and f.d_op >= '$dfrom' and f.d_op >= '$dto'
            group by a.i_fc, a.i_product, b.e_product_basename, d.e_color_name, a.n_quantity, e.v_price, g.v_do_gross", FALSE);
            
        // $datatables->edit('n_deliver', function ($data) {
        //     return number_format($data['n_deliver']);
        // });
        // $datatables->edit('v_unit_price', function ($data) {
        //     return number_format($data['v_unit_price']);
        // });
        // $datatables->edit('total_harga_jual', function ($data) {
        //     return number_format($data['total_harga_jual']);
        // });
        return $datatables->generate();
    }

    public function bacaexport($periode, $dfrom, $dto){
        // $where = '';
        // $where2 = '';
        // if ($supplier == 'ALL'){
        //     $where = '';
        //     $where2 = '';
        // }else{
        //     $where = " sp.i_supplier = '$supplier' and ";
        //     $where2 = "o.i_supplier = '$supplier' and ";
        // }
        return $this->db->query("SELECT ROW_NUMBER() OVER(
            ORDER BY a.i_product) AS i, a.i_fc, a.i_product, b.e_product_basename
            , d.e_color_name, a.n_quantity as qtyforecast
            ,sum(e.n_count) as qtyop, a.n_quantity - sum(e.n_count) as sisafc
            , ((sum(e.n_count)/a.n_quantity)* 100)::numeric(10,2) as fcvsop, a.n_quantity as qtyforecast1
            , sum(g.n_deliver) as qtydo, a.n_quantity - sum(g.n_deliver)as sisafc1
            , ((sum(g.n_deliver)/a.n_quantity)* 100)::numeric(10,2) as fcvsdo
            from tm_fc_distributor_detail a
            inner join tr_product_base b on a.i_product = b.i_product_motif
            inner join tm_fc_distributor c on a.i_fc = c.i_fc 
            inner join tr_color d on a.i_color = d.i_color
            inner join tm_op_item e  on a.i_product = e.i_product and a.i_color = e.i_color
            inner join tm_op f on e.i_op_code = f.i_op_code
            inner join tm_do_item g on a.i_product = g.i_product and a.i_color = g.i_color
            inner join tm_do h on g.i_do = h.i_do
            where c.e_fc_periode = '$periode' and f.d_op >= '$dfrom' and f.d_op >= '$dto'
            group by a.i_fc, a.i_product, b.e_product_basename, d.e_color_name, a.n_quantity, e.v_price, g.v_do_gross",FALSE)->result();
      }
}

/* End of file Mmaster.php */
