<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    // herdin 24 Maret 2020
    public function bacagudang(){
        $this->db->select('i_kode_master, e_nama_master');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', 'GD10003');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
        // return $->get('tr_master_gudang')->result();
    }

    public function total($supplier,$dfrom,$dto) {
      return $this->db->query("
                select sum(jml.qty_op) as op ,sum(jml.qty_btb) as btb,sum(jml.sisa) as sisa,count(*) as size 
                  from (
                    select final.*, final.qty_op - final.qty_btb as sisa from (
                      select y.i_supplier, y.i_op, y.d_op, y.qty_op, case when x.qty_btb is null then 0 else x.qty_btb end as qty_btb from (
                        select o.i_supplier, spd.i_op, sum (spd.n_qty) as qty_btb from tm_sj_pembelian sp
                        inner join tm_sj_pembelian_detail spd on (sp.i_btb = spd.i_btb)
                        inner join tm_opbb o on  (o.i_supplier = sp.i_supplier and o.i_op = spd.i_op)
                        where sp.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f' and f_sj_cancel = 'f'
                        group by o.i_supplier, spd.i_op 
                        ) as x
                        full join (
                          select o.i_supplier, o.i_op, o.d_op, sum(n_quantity) as qty_op from tm_opbb_item oi
                          inner join tm_opbb o on (o.i_op = oi.i_op)
                          where o.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f'
                          group by o.i_supplier, o.i_op, o.d_op
                        ) as y
                        on (x.i_supplier = y.i_supplier and y.i_op = x.i_op)
                    ) as final
                    where final.qty_op - final.qty_btb > 0
                  ) as jml
            "
        , FALSE);
    }

    public function data($dfrom,$dto,$gudang){
        // $th = date('y', strtotime($tahun));
        // $thbl = $th.$bulan ;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT ROW_NUMBER() OVER(
                          ORDER BY x.i_pp, y.i_op) as i, x.i_pp, x.d_pp, y.i_op, y.e_supplier_name, x.i_material, c.e_material_name, d.e_satuan, x.n_quantity as qtypp, 
                          case when y.n_quantity is null then 0.00 else y.n_quantity end as qtyop,
                          case when x.n_quantity = y.n_quantity then 'OP Complete' else 'Belum Complete' end as status from (                        
                            select a.d_pp, a.i_kode_master, b.i_pp, b.i_material, b.n_quantity, b.i_satuan from tm_pp a
                            inner join tm_pp_item b on (a.i_pp = b.i_pp)
                            where a.i_kode_master = '$gudang' and a.e_approval = '5' and a.d_pp >= '$dfrom' and a.d_pp <= '$dto'
                          ) as x
                          left join (
                            select a.i_op, c.e_supplier_name, a.e_approval, b.i_pp, b.i_material, b.n_quantity, b.i_satuan from tm_opbb a
                            inner join tm_opbb_item b on (a.i_op = b.i_op)
                            inner join tr_supplier c on (a.i_supplier = c.i_supplier)
                            where b.i_kode_master = '$gudang' and a.e_approval = '6' --and a.i_supplier = 'SA001'
                          ) as y on (x.i_pp = y.i_pp and x.i_material = y.i_material) 
                          inner join tr_material c on x.i_material = c.i_material
                          inner join tr_satuan d on x.i_satuan = d.i_satuan
                          order by x.i_pp, y.i_op"
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
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
