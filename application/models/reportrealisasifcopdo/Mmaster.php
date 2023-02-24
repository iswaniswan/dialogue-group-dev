<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
	public function customer($search, $idcompany)
	{
		return $this->db->query("
			SELECT id, e_customer_name from tr_customer a where i_supplier_group = 'KTG04' and id_company = '$idcompany' and f_status = 't' AND (e_customer_name ILIKE '%$search%');
        ", false);
	}

	public function customerId($id, $idcompany)
	{
		return $this->db->query("
			SELECT id, e_customer_name from tr_customer a where i_supplier_group = 'KTG04' and id_company = '$idcompany' and f_status = 't' AND id = '$id';
        ", false);
	}

	public function cek_datadet($id_company, $i_periode, $dfrom, $dto, $icustomer)
	{
		// $this->db->select("
		// 	x.*, a.i_product_wip, upper(a.e_product_basename) AS e_product_basename, e_class_name, case when e_jenis_bagian isnull then initcap(e_bagian_name) else initcap(e_bagian_name||' - '||coalesce(e_jenis_bagian,'')) end as e_bagian_name, initcap(b.e_color_name) AS e_color_name 
		// 	from f_mutasi_gudang_jadi('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
		// 	INNER JOIN tr_product_base a ON
		// 		(a.id_company = x.id_company
		// 		AND a.id = x.id_product_base)
		// 	INNER JOIN tr_color b ON
		// 		(a.id_company = b.id_company
		// 		AND a.i_color = b.i_color)
		// 	INNER JOIN tr_class_product cc ON (cc.id = a.id_class_product)
		// 	LEFT JOIN tr_bagian c ON (c.i_bagian = x.i_bagian AND x.id_company=c.id_company)
		// 	WHERE x.id_company is not null
		// 	ORDER BY e_class_name, a.i_product_wip, e_product_basename, e_color_name
		// 	", FALSE);
		// return $this->db->query("SELECT e.e_customer_name, a.i_product_base, a.e_product_basename, f.e_color_name, c.v_price, x.n_quantity_fc, c.n_quantity as n_quantity_op, (c.n_quantity * c.v_price) AS price_op, ROUND((c.n_quantity / NULLIF(x.n_quantity_fc, 0)) * 100, 2) AS opfc,
		// d.n_quantity AS n_quantity_do,
		// (d.n_quantity * c.v_price) AS price_do,
		// ROUND((d.n_quantity / NULLIF(x.n_quantity_fc, 0)) * 100, 2) AS dofc,
		// ROUND((d.n_quantity / NULLIF(c.n_quantity, 0)) * 100, 2) AS doop, (c.n_quantity - d.n_quantity) AS qty_pendingan, (c.v_price*(c.n_quantity - d.n_quantity)) AS price_pendingan, (x.n_quantity_fc - c.n_quantity) AS qty_dropping, (c.v_price * (x.n_quantity_fc - c.n_quantity)) AS price_dropping FROM tr_product_base a
		// 	LEFT JOIN produksi.f_get_forecast_distributor('$id_company', '$i_periode', '$icustomer') x ON (x.id_product = a.id AND x.id_company = a.id_company)
		// 	LEFT JOIN (SELECT distinct ca.*, cb.id AS id_spb, cb.id_customer FROM tm_spb_item ca LEFT JOIN tm_spb cb ON (cb.id = ca.id_document) WHERE cb.i_status = '6' AND (cb.d_document BETWEEN '$dfrom' AND '$dto') ) c ON (c.id_product = a.id AND c.id_company = a.id_company AND c.id_product = x.id_product and c.id_customer = x.id_customer)
		// 	LEFT JOIN tm_sj_item d ON (d.id_document_reff = c.id_spb AND d.id_product = c.id_product AND d.id_company = c.id_company)
		// 	LEFT JOIN tr_customer e ON (e.id = x.id_customer AND e.id_company = x.id_company)
		// 	LEFT JOIN tr_color f ON (a.i_color = f.i_color AND f.id_company = a.id_company)
		// 	WHERE x.id_company IS NOT NULL;");

		return $this->db->query("
			SELECT
				e.e_customer_name,
				a.i_product_base,
				a.e_product_basename,
				f.e_color_name,
				COALESCE(c.v_price, 0) v_price,
				COALESCE(x.n_quantity_fc, 0) n_quantity_fc,
				COALESCE(c.n_quantity, 0) AS n_quantity_op,
				(COALESCE(c.n_quantity, 0) * COALESCE(c.v_price, 0)) AS price_op,
				CASE WHEN x.n_quantity_fc = 0 THEN 0 ELSE ROUND((COALESCE(c.n_quantity, 0) / NULLIF(x.n_quantity_fc, 0)) * 100, 2) END AS opfc,
				COALESCE(d.n_quantity,0) AS n_quantity_do,
				(COALESCE(d.n_quantity,0) * COALESCE(c.v_price, 0)) AS price_do,
				CASE WHEN x.n_quantity_fc = 0 THEN 0 ELSE ROUND((COALESCE(d.n_quantity,0) / NULLIF(x.n_quantity_fc, 0)) * 100, 2) END AS dofc,
				CASE WHEN c.n_quantity = 0 THEN 0 ELSE ROUND((COALESCE(d.n_quantity,0) / NULLIF(c.n_quantity, 0)) * 100, 2) END AS doop,
				(COALESCE(c.n_quantity, 0) - COALESCE(d.n_quantity,0)) AS qty_pendingan,
				(COALESCE(c.v_price, 0) * (COALESCE(c.n_quantity, 0) - COALESCE(d.n_quantity,0))) AS price_pendingan,
				(COALESCE(x.n_quantity_fc, 0) - COALESCE(c.n_quantity, 0)) AS qty_dropping,
				(COALESCE(c.v_price, 0) * (COALESCE(x.n_quantity_fc, 0) - COALESCE(c.n_quantity, 0))) AS price_dropping,
				c.i_document
			FROM
				tr_product_base a
			inner JOIN produksi.f_get_forecast_distributor('$id_company',
				'$i_periode',
				'$icustomer') x ON
				(x.id_product = a.id
					AND x.id_company = a.id_company)
			LEFT JOIN (
				SELECT
					ca.id_product, ca.id_company, ca.v_price,
					cb.id AS id_spb, cb.i_document,
					cb.id_customer, sum(ca.n_quantity) AS n_quantity
				FROM
					tm_spb_item ca
				INNER JOIN tm_spb cb ON
					(cb.id = ca.id_document)
				WHERE
					cb.i_status = '6' AND cb.id_customer = '$icustomer'
					AND (cb.d_document BETWEEN '$dfrom' AND '$dto')
				GROUP BY 1,2,3,4,5,6
			) c ON
				(c.id_product = a.id
					AND c.id_company = a.id_company
					AND c.id_product = x.id_product
					AND c.id_customer = x.id_customer)
			LEFT JOIN (
				SELECT
					da.id_document_reff, da.id_product, da.id_company,
					db.id_customer,
					dc.i_product_base, sum(da.n_quantity) AS n_quantity
				FROM
					tm_sj_item da
				INNER JOIN tm_sj db ON
					(db.id = da.id_document)
				INNER JOIN tr_product_base dc ON (dc.id = da.id_product AND dc.id_company = db.id_company)
				WHERE
					db.i_status = '6' AND db.id_customer = '$icustomer'
					AND (db.d_document BETWEEN '$dfrom' AND '$dto')
				GROUP BY 1,2,3,4,5
			) d ON
				(d.id_document_reff = c.id_spb
					AND d.i_product_base = a.i_product_base AND d.id_product = c.id_product
					AND d.id_company = c.id_company)
			LEFT JOIN tr_customer e ON
				(e.id = x.id_customer
					AND e.id_company = x.id_company)
			LEFT JOIN tr_color f ON
				(a.i_color = f.i_color
					AND f.id_company = a.id_company)
			/* WHERE
				x.id_company IS NOT NULL */;
		");
	}
}
/* End of file Mmaster.php */