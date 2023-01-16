<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function bacastock($periode){
		$bulan_bon = '01-'.substr($periode, 4, 2).'-'.substr($periode, 0, 4);
    	$bon =  date('ym', strtotime('+1 month', strtotime($bulan_bon)));
        return $this->db->query("SELECT
								'$periode' as e_periode, 
								i_store, 
								e_store_name, 
								substring(jenis,1,2) as i_jenis, 
								substring(jenis,4,20) as e_jenis, 
								n_saldoakhir, 
								v_saldoakhir_rp, 
								n_totalopname, 
								v_totalopname_rp,
								n_opname, 
								v_opname_rp, 
								n_git, 
								v_git_rp, 
								n_gitjual, 
								v_gitjual_rp, 
								n_selisih_qty as n_selisih, 
								v_selisih_rp, 
								now() 
							FROM(
								SELECT
									i_store, 
									'01-Gudang' as jenis, 
									sum(n_saldo_akhir) as n_saldoakhir, 
									sum(n_saldo_akhir*v_product_mill) as v_saldoakhir_rp, 
									sum(n_totalopname) as n_totalopname, 
									sum(n_totalopname*v_product_mill) as v_totalopname_rp, 
									sum(n_opname) as n_opname, 
									sum(n_opname*v_product_mill) as v_opname_rp, 
									sum(n_git) as n_git, 
									sum(n_git*v_product_mill) as v_git_rp, 
									sum(n_gitjual) as n_gitjual, 
									sum(n_gitjual*v_product_mill) as v_gitjual_rp, 
									sum(selisih) as n_selisih_qty, 
									sum(selisih*v_product_mill) as v_selisih_rp
								FROM(
									SELECT 
										i_store, 
										n_saldo_akhir, 
										n_saldo_stockopname+n_mutasi_git+n_git_penjualan as n_totalopname, 
										n_saldo_Stockopname as n_opname,
										n_mutasi_git as n_git,
										n_git_penjualan as n_gitjual,
										selisih, 
										b.v_product_mill 
									FROM 
										f_mutasi_stock_daerah_all_saldoakhir('$periode') a
									LEFT JOIN 
										tr_product b on b.i_product=a.i_product
									WHERE 
										i_store <>'PB'
									UNION ALL
										SELECT
											i_store, 
											n_saldo_akhir, 
											n_saldo_stockopname+n_mutasi_git+n_git_penjualan as n_totalopname, 
											n_saldo_stockopname as n_opname,
											n_mutasi_git as n_git,
											n_git_penjualan as n_gitjual,
											selisih, 
										b.v_product_mill 
										FROM
											f_mutasi_stock_pusat_saldoakhir('$periode') a
										LEFT JOIN 
											tr_product b on b.i_product=a.i_product
								) as data
								GROUP BY 
									i_store
								UNION ALL
									SELECT
										i_store, 
										'02-Gudang MO' as jenis, 
										sum(n_saldo_akhir) as n_saldoakhir, 
										sum(n_saldo_akhir*v_product_mill) as v_saldoakhir_rp, 
										sum(n_opname) as opname,
										sum(n_opname*v_product_mill) as v_opname_rp, 
										sum(n_opname) as opname,
										sum(n_opname*v_product_mill) as v_opname_rp, 
										sum(n_git) as n_git,
										sum(n_git*v_product_mill) as v_git_rp, 
										sum(n_gitjual) as n_gitjual,
										sum(n_gitjual*v_product_mill) as v_gitjual_rp, 
										sum(selisih) as selisih_qty,sum(selisih*v_product_mill) as selisih_rp
									FROM(
										SELECT
											a.i_store, 
											a.n_saldo_akhir, 
											a.n_saldo_stockopname as n_totalopname, 
											a.n_saldo_stockopname as n_opname, 
											0 as n_git, 
											0 as n_gitjual, 
											a.selisih, 
											b.v_product_mill 
										FROM 
											f_mutasi_stock_mo_pb_saldoakhir('$periode') a
										LEFT JOIN
											tr_product b on (a.i_product = b.i_product )
										WHERE a.i_product_grade='A'
									) as data
									GROUP BY 
										i_store
									UNION ALL
										SELECT
											x.i_area as i_store, '03-Gudang MO Cust' as jenis,
											sum(x.n_saldoakhir) as n_saldoakhir, 
											sum(x.n_saldoakhir*x.v_product_mill) as v_saldoakhir_rp, 
											sum(x.n_saldo_stockopname) as n_totalopname,  
											sum(x.n_saldo_stockopname*x.v_product_mill) as v_totalopname_rp, 
											sum(x.n_saldo_stockopname) as n_opname,  
											sum(x.n_saldo_stockopname*x.v_product_mill) as v_opname_rp, 
											0 as n_git,  
											0 as v_git_rp, 
											0 as n_gitjual,  
											0 as v_gitjual_rp,
											sum(x.selisih) as selisih_qty,
											sum(x.selisih*x.v_product_mill) as selisih_rp 
										FROM (
											SELECT 
												i_area,
											CASE WHEN
												(a.n_saldo_akhir-a.n_mutasi_git) < 0 then 0
											ELSE 
												(a.n_saldo_akhir-a.n_mutasi_git) 
											END as n_saldoakhir,
												b.v_product_mill, 
												a.n_saldo_stockopname, 
												a.selisih
											FROM 
												f_mutasi_stock_mo_cust_all_saldoakhir('$periode') a
											LEFT JOIN 
												tr_product b on (a.i_product = b.i_product )
											WHERE NOT 
												a.e_product_name isnull
											ORDER BY 
												a.i_customer
										) as x
										GROUP BY
											x.i_area
										ORDER BY 
											i_store, 
											jenis
							) as data2
							LEFT JOIN
								tr_store 
							USING 
								(i_store)
							UNION ALL
								SELECT 
									'$periode' as e_periode, 
									'PB' as i_store,
									'MO Pusat' as e_store_name,
									'04' as i_jenis,
									'Penjualan MO' as e_jenis_name,
									sum(n_quantity) as n_saldoakhir,
									sum(n_quantity*v_product_mill) as v_saldoakhir_rp,
									sum(n_quantity) as n_totalopname,
									sum(n_quantity*v_product_mill) as v_totalopname_rp,
									sum(n_quantity) as n_opname,
									sum(n_quantity*v_product_mill) as v_opname_rp,
									0 as n_git,
									0 as v_git_rp,
									0 as n_gitjual,
									0 as v_gitjual_rp,
									0 as n_selisih_qty,
									0 as v_selisih_rp,
									now()
								FROM 
									tm_notapb_item a
								LEFT JOIN 
									tr_product b on b.i_product=a.i_product
								LEFT JOIN 
									tm_notapb c on c.i_notapb = a.i_notapb 
								AND 
									c.i_customer=a.i_customer 
								AND 
									c.i_area = a.i_area
								WHERE 
									c.f_notapb_cancel='f' 
								AND 
									to_char(a.d_notapb,'yyyymm')='$periode' 
								AND 
									c.i_spb 
								LIKE 
								'%-$bon-%'
							");
	}
}

/* End of file Mmaster.php */