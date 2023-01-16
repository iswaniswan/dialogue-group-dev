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
			"SELECT i_product, e_product_name, e_color_name,
			sum(coalesce(case when (n_week = 1) then n_order end,0)) as n_week1,
			sum(coalesce(case when (n_week = 2) then n_order end,0)) as n_week2,
			sum(coalesce(case when (n_week = 3) then n_order end,0)) as n_week3,
			sum(coalesce(case when (n_week = 4) then n_order end,0)) as n_week4,
			sum(coalesce(case when (n_week = 5) then n_order end,0)) as n_week5,
			jsonb_agg(d_sj ORDER BY d_sj) as d_sj,
			jsonb_agg(DISTINCT n_week) as d_sj_week,
			jsonb_agg(n_deliver ORDER BY d_sj) n_deliver
			from (
				SELECT DISTINCT d.i_product_base AS i_product, d.e_product_basename e_product_name, e_color_name,
				cast(to_char(b.d_document ,'w') as integer) as n_week, c.d_sj, sum(a.n_quantity) as n_order, coalesce(sum(c.n_deliver),0) as n_deliver
				from tm_spb_item a
				inner join tm_spb b on (b.id = a.id_document)
				INNER JOIN tr_product_base d ON (d.id = a.id_product)
				INNER JOIN tr_color e ON (e.i_color = d.i_color AND d.id_company = e.id_company)
				inner join(
					SELECT b.id_document_reff, b.id_product,  a.d_document d_sj, b.n_quantity n_deliver
					FROM tm_sj a
					INNER JOIN tm_sj_item b ON (b.id_document = a.id)
					WHERE a.i_status = '6') c ON (c.id_product = a.id_product 
					AND a.id_document = c.id_document_reff
				)
				where b.d_document between '$dfrom' and '$dto'
				and b.i_status='6'
				group by 1, 2, 3, 4, 5
				order by 1, 4
			) as x1
			group by 1, 2, 3
			"
		);
	}
}
/* End of file Mmaster.php */