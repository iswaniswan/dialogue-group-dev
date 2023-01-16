<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($iperiode){
        if($iperiode>'201512'){
            return $this->db->query("
                            select
                               z.i_customer,
                               z.e_customer_name,
                               sum(z.n_saldo_akhir * z.v_product_retail) as akhir,
                               sum(z.n_saldo_stockopname * z.v_product_retail) as opname,
                               sum((z.n_saldo_stockopname - z.n_saldo_akhir) * z.v_product_retail) as selisih 
                            from
                               (
                                  select
                                     x.*,
                                     case
                                        when
                                           b.v_product_retail > 0 
                                        then
                                           b.v_product_retail 
                                        else
                                           0 
                                     end
                                     as v_product_retail 
                                  from
                                     (
                                        select
                                           i_product,
                                           sum(n_saldo_awal) as n_saldo_awal,
                                           sum(n_mutasi_daripusat) as n_mutasi_daripusat,
                                           sum(n_mutasi_darilang) as n_mutasi_darilang,
                                           sum(n_mutasi_kepusat) as n_mutasi_kepusat,
                                           sum(n_mutasi_penjualan) as n_mutasi_penjualan,
                                           sum(n_saldo_akhir) as n_saldo_akhir,
                                           e_mutasi_periode,
                                           i_customer,
                                           sum(n_saldo_stockopname) as n_saldo_stockopname,
                                           e_product_name,
                                           e_customer_name 
                                        from
                                           f_mutasi_stock_mo_cust_all_saldoakhir('$iperiode') 
                                        group by
                                           i_product,
                                           e_mutasi_periode,
                                           i_customer,
                                           e_product_name,
                                           e_customer_name 
                                        order by
                                           i_product,
                                           e_product_name,
                                           e_mutasi_periode,
                                           i_customer,
                                           e_customer_name 
                                     )
                                     as x 
                                     left join
                                        tr_product_price b 
                                        on(x.i_product = b.i_product 
                                        and i_price_group = '00') 
                                  where
                                     x.i_product != '' 
                               )
                               as z 
                            group by
                               z.i_customer,
                               z.e_customer_name 
                            order by
                               z.e_customer_name asc
                            ",false);
        }else{
            return $this->db->query("
                                select
                                   i_product,
                                   sum(n_saldo_awal) as n_saldo_awal,
                                   sum(n_mutasi_daripusat) as n_mutasi_daripusat,
                                   sum(n_mutasi_darilang) as n_mutasi_darilang,
                                   sum(n_mutasi_kepusat) as n_mutasi_kepusat,
                                   sum(n_mutasi_penjualan) as n_mutasi_penjualan,
                                   sum(n_saldo_akhir) as n_saldo_akhir,
                                   e_mutasi_periode,
                                   i_customer,
                                   sum(n_saldo_stockopname) as n_saldo_stockopname,
                                   e_product_name,
                                   e_customer_name 
                                from
                                   f_mutasi_stock_mo_cust_all('$iperiode') 
                                group by
                                   i_product,
                                   e_mutasi_periode,
                                   i_customer,
                                   e_product_name,
                                   e_customer_name 
                                order by
                                   i_product,
                                   e_product_name,
                                   e_mutasi_periode,
                                   i_customer,
                                   e_customer_name
                                ",false);
        }
        // $query = $this->db->get();
        // if ($query->num_rows() > 0){
        //     return $query->result();
        // }
    }
}

/* End of file Mmaster.php */
