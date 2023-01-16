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

	public function get_detail($dfrom, $dto, $dfromperiode, $dtoperiode)
	{
		return $this->db->query(
			"SELECT
			periode,
			target_rp,
			spb_rp,
			spb_qty,
			sj_rp,
			sj_qty,
			(spb_rp - sj_rp) AS spb_sj_rp,
			(spb_qty - sj_qty) AS spb_sj_qty,
			nota_rp,
			nota_qty
		FROM
			(
			SELECT
				periode,
				sum(target_rp) AS target_rp,
				sum(spb_rp) AS spb_rp,
				sum(spb_qty) AS spb_qty,
				sum(sj_rp) AS sj_rp,
				sum(sj_qty) AS sj_qty,
				sum(nota_rp) AS nota_rp,
				sum(nota_qty) AS nota_qty
			FROM
				(
				SELECT
					to_char(a.d_document, 'Month') AS periode,
					sum(c.v_netto) AS spb_rp,
					sum(c.n_quantity) AS spb_qty,
					0 AS sj_rp,
					0 AS sj_qty,
					0 AS nota_rp,
					0 AS nota_qty,
					0 AS target_rp
				FROM
					tm_spb a
				INNER JOIN tm_spb_item c ON
					(c.id_document = a.id)
				WHERE
					d_document BETWEEN '$dfrom' AND '$dto'
					AND a.id_company = '4'
					AND a.i_status = '6'
				GROUP BY
					1
			UNION ALL
				SELECT
					to_char(b.d_document, 'Month') AS periode,
					0 AS spb_rp,
					0 AS spb_qty,
					sum(d.v_netto) AS sj_rp,
					sum(d.qty) AS sj_qty,
					0 AS nota_rp,
					0 AS nota_qty,
					0 AS target_rp
				FROM
					tm_sj b
				INNER JOIN (
					SELECT
						id_document,
						sum(v_netto) AS v_netto,
						sum(n_quantity) AS qty
					FROM
						tm_sj_item
					GROUP BY
						1) d ON
					(d.id_document = b.id)
				WHERE
					d_document BETWEEN '$dfrom' AND '$dto'
					AND b.id_company = '4'
					AND b.i_status = '6'
				GROUP BY
					1
			UNION ALL
				SELECT
					to_char(e.d_document, 'Month') AS periode,
					0 AS spb_rp,
					0 AS spb_qty,
					0 AS sj_rp,
					0 AS sj_qty,
					sum(e.v_bersih) AS nota_rp,
					sum(f.qty) AS nota_qty,
					0 AS target_rp
				FROM
					tm_nota_penjualan e
				INNER JOIN (
					SELECT
						id_document,
						sum(n_quantity) AS qty
					FROM
						tm_nota_penjualan_item
					GROUP BY
						1) f ON
					(f.id_document = e.id)
				WHERE
					d_document BETWEEN '$dfrom' AND '$dto'
					AND e.id_company = '4'
					AND e.i_status = '6'
				GROUP BY
					1
			UNION ALL
				SELECT
					to_char(i_periode::date, 'Month') AS periode,
					0 spb_rp,
					0 AS spb_qty,
					0 AS sj_rp,
					0 AS sj_qty,
					0 AS nota_rp,
					0 AS nota_qty,
					sum(v_target) AS target_rp
				FROM
					tr_target_penjualan
				WHERE
					id_company = '4'
					AND i_periode BETWEEN '$dfromperiode' AND '$dtoperiode'
					AND i_status = '6'
				GROUP BY 1
			) AS foo
			GROUP BY
				1
		) AS foo2;"
		);
	}
}
/* End of file Mmaster.php */