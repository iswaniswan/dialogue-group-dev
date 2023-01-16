<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	public function baca($iperiode){
		if ($iperiode > '201512') {
			$this->db->select("
					x.*,
				    CASE
				        WHEN b.v_product_retail > 0 THEN b.v_product_retail
				        ELSE 0
				    END AS v_product_retail
				FROM
				    (
				    SELECT
				        i_product, sum(n_saldo_awal) AS n_saldo_awal, sum(n_mutasi_daripusat) AS n_mutasi_daripusat, sum(n_mutasi_darilang) AS n_mutasi_darilang, sum(n_mutasi_kepusat) AS n_mutasi_kepusat, sum(n_mutasi_penjualan) AS n_mutasi_penjualan, sum(n_saldo_akhir) AS n_saldo_akhir, e_mutasi_periode, i_customer, sum(n_saldo_stockopname) AS n_saldo_stockopname, e_product_name, e_customer_name
				    FROM
				        f_mutasi_stock_mo_cust_all_saldoakhir('$iperiode')
				    GROUP BY
				        i_customer, i_product, e_mutasi_periode, e_product_name, e_customer_name
				    ORDER BY
				        i_customer, i_product, e_product_name, e_mutasi_periode, e_customer_name) AS x
				LEFT JOIN tr_product_price b ON
				    (x.i_product = b.i_product
				    AND i_price_group = '00')
				WHERE
				    x.i_product != ''
				ORDER BY
				    x.i_customer
			", false);
		} else {
			$this->db->select("
					i_product,
				    sum(n_saldo_awal) AS n_saldo_awal,
				    sum(n_mutasi_daripusat) AS n_mutasi_daripusat,
				    sum(n_mutasi_darilang) AS n_mutasi_darilang,
				    sum(n_mutasi_kepusat) AS n_mutasi_kepusat,
				    sum(n_mutasi_penjualan) AS n_mutasi_penjualan,
				    sum(n_saldo_akhir) AS n_saldo_akhir,
				    e_mutasi_periode,
				    i_customer,
				    sum(n_saldo_stockopname) AS n_saldo_stockopname,
				    e_product_name,
				    e_customer_name
				FROM
				    f_mutasi_stock_mo_cust_all('$iperiode')
				GROUP BY
				    i_customer,
				    i_product,
				    e_mutasi_periode,
				    e_product_name,
				    e_customer_name
				ORDER BY
				    i_customer,
				    i_product,
				    e_product_name,
				    e_mutasi_periode,
				    e_customer_name
			", false);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	}

	public function detail($iperiode,$icustomer,$iproduct){
		$query =  $this->db->query("
			SELECT
			    b.e_product_name,
			    a.ireff,
			    a.dreff,
			    a.customer,
			    a.periode,
			    a.product,
			    e.e_customer_name,
			    sum(a.in) AS IN,
			    sum(a.out) AS OUT
			FROM
			    tr_product b,
			    vmutasiconsigmentdetail a
			LEFT JOIN tr_customer e ON
			    a.customer = e.i_customer
			WHERE
			    b.i_product = a.product
			    AND a.periode = '$iperiode'
			    AND a.customer = '$icustomer'
			    AND a.product = '$iproduct'
			GROUP BY
			    b.e_product_name,
			    a.ireff,
			    a.dreff,
			    a.customer,
			    a.periode,
			    a.product,
			    e.e_customer_name
			ORDER BY
			    dreff,
			    ireff
			",false);
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
}

/* End of file Mmaster.php */
