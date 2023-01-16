<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  public function data($iperiode,$folder){
      $datatables = new Datatables(new CodeigniterAdapter);
      if($iperiode > '201512'){
         $datatables->query("
                              select
                                 x.i_store,
                                 x.i_store_location,
                                 x.e_product_groupname,
                                 x.i_product,
                                 x.e_product_name,
                                 sum(x.selisih) as selisih,
                                 sum(x.selisihrp) as selisihrp 
                              from
                                 (
                                    select
                                       y.i_store,
                                       y.i_store_location,
                                       y.i_product,
                                       y.e_product_name,
                                       y.i_product_group,
                                       y.e_product_groupname,
                                       sum(y.opname) as opname,
                                       sum(y.SaldoAkhir) as SaldoAkhir,
                                       sum(y.selisih) as selisih,
                                       y.v_product_retail,
                                       sum(y.selisihrp) as selisihrp 
                                    from
                                       (
                                          select
                                             i.i_store,
                                             i.i_store_location,
                                             i.i_product,
                                             j.e_product_name,
                                             l.i_product_group,
                                             l.e_product_groupname,
                                             sum(i.n_saldo_stockopname) as opname,
                                             sum(i.n_saldo_akhir) as SaldoAkhir,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir )as selisih,
                                             p.v_product_retail,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir)*p.v_product_retail as selisihrp 
                                          from
                                             f_mutasi_stock_daerah_all_saldoakhir('$iperiode') i,
                                             tr_product_price p,
                                             tr_product j,
                                             tr_product_type k,
                                             tr_product_group l 
                                          where
                                             i.i_product = p.i_product 
                                             and p.i_price_group = '00' 
                                             and i.i_product = j.i_product 
                                             and j.i_product_type = k.i_product_type 
                                             and k.i_product_group = l.i_product_group 
                                          group by
                                             i.i_store,
                                             i.i_store_location,
                                             j.e_product_name,
                                             i.i_product,
                                             p.v_product_retail,
                                             l.i_product_group,
                                             l.e_product_groupname 
                                          union all
                                          select
                                             i.i_store,
                                             i.i_store_location,
                                             i.i_product,
                                             j.e_product_name,
                                             l.i_product_group,
                                             l.e_product_groupname,
                                             sum(i.n_saldo_stockopname) as opname,
                                             sum(i.n_saldo_akhir) as SaldoAkhir,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir )as selisih,
                                             p.v_product_retail,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir)*p.v_product_retail as selisihrp 
                                          from
                                             f_mutasi_stock_pusat_saldoakhir('$iperiode') i,
                                             tr_product_price p,
                                             tr_product j,
                                             tr_product_type k,
                                             tr_product_group l 
                                          where
                                             i.i_product = p.i_product 
                                             and p.i_price_group = '00' 
                                             and i.i_product = j.i_product 
                                             and j.i_product_type = k.i_product_type 
                                             and k.i_product_group = l.i_product_group 
                                          group by
                                             i.i_store,
                                             i.i_store_location,
                                             j.e_product_name,
                                             i.i_product,
                                             p.v_product_retail,
                                             l.i_product_group,
                                             l.e_product_groupname 
                                       )
                                       as y 
                                    group by
                                       y.i_store,
                                       y.i_store_location,
                                       y.e_product_name,
                                       y.i_product,
                                       y.v_product_retail,
                                       y.i_product_group,
                                       y.e_product_groupname 
                                    order by
                                       y.i_store,
                                       y.i_store_location,
                                       y.e_product_name,
                                       y.i_product,
                                       y.v_product_retail,
                                       y.i_product_group,
                                       y.e_product_groupname 
                                 )
                                 x 
                              where
                                 x.selisih <> 0 
                              group by
                                 x.i_store,
                                 x.i_store_location,
                                 x.i_product_group,
                                 x.e_product_groupname,
                                 x.i_product,
                                 x.e_product_name 
                              order by
                                 x.i_store,
                                 x.i_store_location,
                                 x.i_product_group,
                                 x.e_product_groupname,
                                 x.i_product
                                       
                              ");
      }else{
         $datatables->query("
                              select
                                 x.i_store,
                                 x.i_store_location,
                                 x.e_product_groupname,
                                 x.i_product,
                                 x.e_product_name,
                                 sum(x.selisih) as selisih,
                                 sum(x.selisihrp) as selisihrp 
                              from
                                 (
                                    select
                                       y.i_store,
                                       y.i_store_location,
                                       y.i_product,
                                       y.e_product_name,
                                       y.i_product_group,
                                       y.e_product_groupname,
                                       sum(y.opname) as opname,
                                       sum(y.SaldoAkhir) as SaldoAkhir,
                                       sum(y.selisih) as selisih,
                                       y.v_product_retail,
                                       sum(y.selisihrp) as selisihrp 
                                    from
                                       (
                                          select
                                             i.i_store,
                                             i.i_store_location,
                                             i.i_product,
                                             j.e_product_name,
                                             l.i_product_group,
                                             l.e_product_groupname,
                                             sum(i.n_saldo_stockopname) as opname,
                                             sum(i.n_saldo_akhir) as SaldoAkhir,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir )as selisih,
                                             p.v_product_retail,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir)*p.v_product_retail as selisihrp 
                                          from
                                             f_mutasi_stock_daerah_all('$iperiode') i,
                                             tr_product_price p,
                                             tr_product j,
                                             tr_product_type k,
                                             tr_product_group l 
                                          where
                                             i.i_product = p.i_product 
                                             and p.i_price_group = '00' 
                                             and i.i_product = j.i_product 
                                             and j.i_product_type = k.i_product_type 
                                             and k.i_product_group = l.i_product_group 
                                          group by
                                             i.i_store,
                                             i.i_store_location,
                                             j.e_product_name,
                                             i.i_product,
                                             p.v_product_retail,
                                             l.i_product_group,
                                             l.e_product_groupname 
                                          union all
                                          select
                                             i.i_store,
                                             i.i_store_location,
                                             i.i_product,
                                             j.e_product_name,
                                             l.i_product_group,
                                             l.e_product_groupname,
                                             sum(i.n_saldo_stockopname) as opname,
                                             sum(i.n_saldo_akhir) as SaldoAkhir,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir )as selisih,
                                             p.v_product_retail,
                                             sum((n_saldo_stockopname + i.n_mutasi_git + i.n_git_penjualan) - i.n_saldo_akhir)*p.v_product_retail as selisihrp 
                                          from
                                             f_mutasi_stock_pusat('$iperiode') i,
                                             tr_product_price p,
                                             tr_product j,
                                             tr_product_type k,
                                             tr_product_group l 
                                          where
                                             i.i_product = p.i_product 
                                             and p.i_price_group = '00' 
                                             and i.i_product = j.i_product 
                                             and j.i_product_type = k.i_product_type 
                                             and k.i_product_group = l.i_product_group 
                                          group by
                                             i.i_store,
                                             i.i_store_location,
                                             j.e_product_name,
                                             i.i_product,
                                             p.v_product_retail,
                                             l.i_product_group,
                                             l.e_product_groupname 
                                       )
                                       as y 
                                    group by
                                       y.i_store,
                                       y.i_store_location,
                                       y.e_product_name,
                                       y.i_product,
                                       y.v_product_retail,
                                       y.i_product_group,
                                       y.e_product_groupname 
                                    order by
                                       y.i_store,
                                       y.i_store_location,
                                       y.e_product_name,
                                       y.i_product,
                                       y.v_product_retail,
                                       y.i_product_group,
                                       y.e_product_groupname 
                                 )
                                 x 
                              where
                                 x.selisih <> 0 
                              group by
                                 x.i_store,
                                 x.i_store_location,
                                 x.i_product_group,
                                 x.e_product_groupname,
                                 x.i_product,
                                 x.e_product_name 
                              order by
                                 x.i_store,
                                 x.i_store_location,
                                 x.i_product_group,
                                 x.e_product_groupname,
                                 x.i_product
                                       
                              ");
      }

      $datatables->edit('i_store_location', function ($data) {
         $istore = $data['i_store'];
         $istorelocation = $data['i_store_location'];
         if($istore == 'PB' || $istorelocation=='PB'){
             return 'Konsinyasi';
         }else{
             return 'Reguler';
         }
      });

      $datatables->edit('selisih', function ($data) {
        return number_format($data['selisih']);
      });

      $datatables->edit('selisihrp', function ($data) {
         return 'Rp. '.number_format($data['selisihrp']);
       });

      return $datatables->generate();  
  }
}

  /* End of file Mmaster.php */
