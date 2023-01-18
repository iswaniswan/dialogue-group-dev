<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
	public function get_header($dfrom, $dto)
	{
		return $this->db->query(
			"SELECT DISTINCT CAST(to_char(a.d_document , 'w') AS integer) AS n_week, b.d_document d_sj
			FROM
				tm_spb a
			INNER JOIN tm_sj b ON (b.id_document_reff = a.id)
			WHERE
				a.i_status = '6' AND b.i_status = '6' 
				AND a.d_document BETWEEN '$dfrom' AND '$dto'
			ORDER BY 1, 2"
		);
	}

	public function get_detail($dfrom, $dto)
	{
		return $this->db->query(
			"SELECT
			category,
			oa_tahun_sebelumnya,
			oa_tahun_saat_ini,
			sales_qty_tahun_sebelumnya,
			sales_qty_tahun_saat_ini,
			net_sales_tahun_sebelumnya,
			net_sales_tahun_saat_ini
		FROM
			(
			SELECT
				category AS category,
				sum(oa_tahun_sebelumnya) AS oa_tahun_sebelumnya,
				sum(oa_tahun_saat_ini) AS oa_tahun_saat_ini,
				sum(sales_qty_tahun_sebelumnya) AS sales_qty_tahun_sebelumnya,
				sum(sales_qty_tahun_saat_ini) AS sales_qty_tahun_saat_ini,
				sum(net_sales_tahun_sebelumnya) AS net_sales_tahun_sebelumnya,
				sum(net_sales_tahun_saat_ini) AS net_sales_tahun_saat_ini
			FROM
				(
				SELECT
					d.e_type_name AS category,
					0 AS oa_tahun_sebelumnya,
					0 AS oa_tahun_saat_ini,
					sum(b.n_quantity) AS sales_qty_tahun_sebelumnya,
					0 AS sales_qty_tahun_saat_ini,
					0 AS net_sales_tahun_sebelumnya,
					0 AS net_sales_tahun_saat_ini
				FROM
					tm_nota_penjualan a
				INNER JOIN tm_nota_penjualan_item b ON
					(b.id_document = a.id
						AND b.id_company = a.id_company)
				INNER JOIN tr_product_base c ON
					(c.id = b.id_product
						AND c.id_company = b.id_company)
				INNER JOIN tr_item_type d ON
					(d.i_type_code = c.i_type_code
						AND d.id_company = c.id_company)
				WHERE
					a.d_document BETWEEN ('$dfrom'::date - INTERVAL '1 year')::date AND ('$dto'::date - INTERVAL '1 year')::date
					AND a.id_company = '4'
					AND a.i_status = '6'
				GROUP BY
					1
			UNION ALL
				SELECT
					d.e_type_name AS category,
					0 AS oa_tahun_sebelumnya,
					0 AS oa_tahun_saat_ini,
					0 AS sales_qty_tahun_sebelumnya,
					sum(b.n_quantity) AS sales_qty_tahun_saat_ini,
					0 AS net_sales_tahun_sebelumnya,
					0 AS net_sales_tahun_saat_ini
				FROM
					tm_nota_penjualan a
				INNER JOIN tm_nota_penjualan_item b ON
					(b.id_document = a.id
						AND b.id_company = a.id_company)
				INNER JOIN tr_product_base c ON
					(c.id = b.id_product
						AND c.id_company = b.id_company)
				INNER JOIN tr_item_type d ON
					(d.i_type_code = c.i_type_code
						AND d.id_company = c.id_company)
				WHERE
					a.d_document BETWEEN '$dfrom' AND '$dto'
					AND a.id_company = '4'
					AND a.i_status = '6'
				GROUP BY
					1
			UNION ALL
				SELECT
					d.e_type_name AS category,
					0 AS oa_tahun_sebelumnya,
					0 AS oa_tahun_saat_ini,
					0 AS sales_qty_tahun_sebelumnya,
					0 AS sales_qty_tahun_saat_ini,
					sum(b.v_netto) AS net_sales_tahun_sebelumnya,
					0 AS net_sales_tahun_saat_ini
				FROM
					tm_nota_penjualan a
				INNER JOIN tm_nota_penjualan_item b ON
					(b.id_document = a.id
						AND b.id_company = a.id_company)
				INNER JOIN tr_product_base c ON
					(c.id = b.id_product
						AND c.id_company = b.id_company)
				INNER JOIN tr_item_type d ON
					(d.i_type_code = c.i_type_code
						AND d.id_company = c.id_company)
				WHERE
					a.d_document BETWEEN ('$dfrom'::date - INTERVAL '1 year')::date AND ('$dto'::date - INTERVAL '1 year')::date
					AND a.id_company = '4'
					AND a.i_status = '6'
				GROUP BY
					1
			UNION ALL
				SELECT
					d.e_type_name AS category,
					0 AS oa_tahun_sebelumnya,
					0 AS oa_tahun_saat_ini,
					0 AS sales_qty_tahun_sebelumnya,
					0 AS sales_qty_tahun_saat_ini,
					0 AS net_sales_tahun_sebelumnya,
					sum(b.v_netto) AS net_sales_tahun_saat_ini
				FROM
					tm_nota_penjualan a
				INNER JOIN tm_nota_penjualan_item b ON
					(b.id_document = a.id
						AND b.id_company = a.id_company)
				INNER JOIN tr_product_base c ON
					(c.id = b.id_product
						AND c.id_company = b.id_company)
				INNER JOIN tr_item_type d ON
					(d.i_type_code = c.i_type_code
						AND d.id_company = c.id_company)
				WHERE
					a.d_document BETWEEN '$dfrom' AND '$dto'
					AND a.id_company = '4'
					AND a.i_status = '6'
				GROUP BY
					1
			) AS foo1
			GROUP BY
				1
		) AS foo2 ORDER BY net_sales_tahun_saat_ini DESC;"
		);
	}
}
/* End of file Mmaster.php */