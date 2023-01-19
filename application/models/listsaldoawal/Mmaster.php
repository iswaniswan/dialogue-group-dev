<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	public function getarea(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT *
            FROM public.tm_user_area
            WHERE username = '$username'
            AND id_company = '$id_company'
            AND i_area IN ('PB','00')
            ", FALSE);
        if ($query->num_rows()>0) {
            $key = $query->row();
            $iarea = $key->i_area;
            return 'PB';
        }else{
            return 'xx';
        }
    }

    public function data($folder,$dfrom1,$dto1,$dfrom,$dto,$iarea){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
			SELECT 
				e_periode,
				'$dfrom1' AS dfrom,
				'$dto1' AS dto,
				'$dfrom' AS dfrom1,
				'$dto' AS dto1,
				'$iarea' AS iarea,
				'$folder' AS folder
			FROM
				tm_saldoawal_fc
			WHERE
				e_periode between '$dfrom1'
				and '$dto1' 
			GROUP BY
				e_periode
        ", FALSE);
        $datatables->add('action', function ($data) {
			$e_periode = $data['e_periode'];
            $dfrom     = $data['dfrom'];
			$dto       = $data['dto'];
			$dfrom1    = $data['dfrom1'];
			$dto1      = $data['dto1'];
			$iarea     = $data['iarea'];
            $folder    = $data['folder'];
			$data      = '';
			
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$e_periode/$dfrom1/$dto1/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            return $data;
        });
        $datatables->hide('dfrom');
		$datatables->hide('dto');
		$datatables->hide('dfrom1');
        $datatables->hide('dto1');
		$datatables->hide('folder');
		$datatables->hide('iarea');
        return $datatables->generate();
    }

	public function bacadetailsaldo($e_periode){
		return $this->db->query("
							select
								row_number() over() as no,
								z.e_periode,
								z.i_product,
								z.i_product_motif,
								z.e_product_name,
								z.n_saldo_awal,
								sum(z.n_sisa) as n_sisa
							from
							(
							   select
								  a.e_periode,
								  a.i_product,
								  a.i_product_motif,
								  a.n_saldo_awal,
								  sum(b.n_deliver) as n_sisa,
								  c.e_product_name 
							   from
								  tr_product c,
								  tm_saldoawal_fc as a 
								  left join
									 tm_dofc_item as b 
									 on (a.i_product = b.i_product 
									 and a.i_product_grade = b.i_product_grade 
									 and a.i_product_motif = b.i_product_motif 
									 and b.e_mutasi_periode = '$e_periode') 
							   where
								  a.i_product = c.i_product 
								  and a.e_periode = '$e_periode' 
							   group by
								  a.e_periode,
								  a.i_product,
								  a.i_product_motif,
								  a.n_saldo_awal,
								  c.e_product_name 
							   UNION
							   select
								  a.e_periode,
								  a.i_product,
								  a.i_product_motif,
								  a.n_saldo_awal,
								  0 as n_sisa,
								  c.e_product_name 
							   from
								  tm_saldoawal_fc as a 
								  inner join
									 tr_product as c 
									 on a.i_product = c.i_product 
							   where
								  a.i_product not in 
								  (
									 select
										i_product 
									 from
										tm_dofc_item
								  )
								  and a.e_periode = '$e_periode' 
							   group by
								  a.e_periode,
								  a.i_product,
								  a.i_product_motif,
								  a.n_saldo_awal,
								  c.e_product_name 
							) as z 
							where
								z.e_periode = '$e_periode' 
								group by
								z.e_periode,
								z.i_product,
								z.i_product_motif,
								z.n_saldo_awal,
								z.e_product_name 
								order by
								z.i_product
							",false);
	}

	public function getproduct($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
							select distinct
								d.i_product,
								d.e_product_name 
							from
								tr_product_motif c,
								tr_product d 
							where
								d.i_product_status <> '4' 
								and d.i_product = c.i_product 
								and 
								(
								   upper(d.i_product) like '%$cari%' 
								   or upper(d.e_product_name) like '%$cari%'
								)
							order by
								d.i_product
								
							", 
        					FALSE);
    }

    public function getdetailproduct($iproduct){
        return $this->db->query("
								select distinct
									c.e_product_motifname,
									c.i_product_motif,
									d.v_product_mill,
									d.i_product,
									d.e_product_name 
								from
									tr_product_motif c,
									tr_product d 
								where
									d.i_product_status <> '4' 
									and d.i_product = c.i_product 
									and d.i_product = '$iproduct'
								order by
									d.i_product
									
								", 
       						 FALSE);
	}

	public function delete($e_periode){
		$this->db->query("DELETE FROM tm_saldoawal_fc WHERE e_periode='$e_periode'");
      	return TRUE;
    }

	public function insertdetail($e_periode,$i_product,$iproductgrade,$iproductmotif,$n_saldo_awal,$n_sisa){
		$this->db->set(array(
               				'e_periode'       => $e_periode,
               				'i_product'       => $i_product,
               				'i_product_grade' => $iproductgrade,
               				'i_product_motif' => $iproductmotif,
               				'n_saldo_awal'    => $n_saldo_awal,
               				'n_sisa'          => $n_sisa
        				));
      	$this->db->insert('tm_saldoawal_fc');
	}
	
	public function cekdata($dfrom,$dto,$iarea){
		$this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
		$dto	= $yir."-".$mon."-".$det;
		if($iarea != 'NA'){
			return $this->db->query("
									select
										a.i_customer,
										a.f_spb_stockdaerah,
										a.d_spb,
										a.f_spb_cancel,
										a.i_approve1,
										a.i_notapprove,
										a.i_approve2,
										a.i_store,
										a.i_nota,
										a.f_spb_siapnotagudang,
										a.f_spb_op,
										a.f_spb_opclose,
										a.f_spb_siapnotasales,
										a.i_sj,
										a.v_spb,
										a.v_spb_discounttotal,
										a.i_spb,
										a.d_spb,
										a.i_salesman,
										a.i_area,
										a.i_spb_old,
										a.i_spb_program,
										a.i_product_group,
										a.i_spb_program,
										a.i_price_group,
										b.e_customer_name,
										c.e_area_name,
										d.i_dkb,
										x.e_customer_name as xname 
									from
										tm_spb a 
										left join
										   tm_nota d 
										   on(a.i_spb = d.i_spb 
										   and a.i_area = d.i_area 
										   and d.f_nota_cancel = 'f') 
										left join
										   tr_customer b 
										   on(a.i_customer = b.i_customer 
										   and a.i_area = b.i_area) 
										left join
										   tr_customer_tmp x
										   on(a.i_customer = x.i_customer 
										   and a.i_spb = x.i_spb 
										   and a.i_area = x.i_area 
										   and x.i_customer like '%000'),
										tr_area c 
									where
										a.i_area = c.i_area 
										and a.d_spb >= '$dfrom'
										and a.d_spb <= '$dto'
										and a.i_area = '$iarea'
									order by 
										a.i_area, a.i_spb"
									,false);
		}else{
			return $this->db->query("
									select
										a.i_customer,
										a.f_spb_stockdaerah,
										a.d_spb,
										a.f_spb_cancel,
										a.i_approve1,
										a.i_notapprove,
										a.i_approve2,
										a.i_store,
										a.i_nota,
										a.f_spb_siapnotagudang,
										a.f_spb_op,
										a.f_spb_opclose,
										a.f_spb_siapnotasales,
										a.i_sj,
										a.v_spb,
										a.v_spb_discounttotal,
										a.i_spb,
										a.d_spb,
										a.i_salesman,
										a.i_area,
										a.i_spb_old,
										a.i_spb_program,
										a.i_product_group,
										a.i_spb_program,
										a.i_price_group,
										b.e_customer_name,
										c.e_area_name,
										d.i_dkb,
										x.e_customer_name as xname 
									from
										tm_spb a 
										left join
										   tm_nota d 
										   on(a.i_spb = d.i_spb 
										   and a.i_area = d.i_area 
										   and d.f_nota_cancel = 'f') 
										left join
										   tr_customer b 
										   on(a.i_customer = b.i_customer 
										   and a.i_area = b.i_area) 
										left join
										   tr_customer_tmp x
										   on(a.i_customer = x.i_customer 
										   and a.i_spb = x.i_spb 
										   and a.i_area = x.i_area 
										   and x.i_customer like '%000'),
										tr_area c 
									where
										a.i_area = c.i_area 
										and a.d_spb >= '$dfrom'
										and a.d_spb <= '$dto'
									order by 
										a.i_area, a.i_spb"
									,false);
		}
	}
}

/* End of file Mmaster.php */
