<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("Select a.i_faktur_komersial, a.i_nota, a.d_nota, a.i_salesman, c.e_customer_name, 
                            a.d_jatuh_tempo, a.v_nota_gross, a.v_nota_discounttotal, a.v_nota_netto, a.i_spb, a.i_area, '$i_menu' as i_menu from tm_nota a, tr_area b, tr_customer c
                            where a.i_customer=c.i_customer and a.i_area=b.i_area and not a.i_faktur_komersial isnull  and not a.i_seri_pajak isnull
                            and a.d_nota >= to_date('$dfrom','yyyy-mm-dd') and a.d_nota <= to_date('$dto','yyyy-mm-dd')
                            order by a.d_nota, a.i_area, a.i_nota",false);
		$datatables->add('action', function ($data) {
            $i_nota    = trim($data['i_nota']);
            $i_spb    = trim($data['i_spb']);
            $i_area    = trim($data['i_area']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"fkompengganti/cform/edit/$i_nota/$i_spb/$i_area/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('d_nota', function ($data) {
        $d_nota = $data['d_nota'];
        if($d_nota == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($d_nota) );
        }
        });

        
        $datatables->edit('d_jatuh_tempo', function ($data) {
            $d_jatuh_tempo = $data['d_jatuh_tempo'];
            if($d_jatuh_tempo == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_jatuh_tempo) );
            }
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_spb');
        $datatables->hide('i_area');

        return $datatables->generate();
	}

	function bacanota($inota,$ispb,$area){
		$this->db->select(" tm_nota.i_nota, tm_nota.i_faktur_komersial, tm_nota.d_nota, tm_nota.i_customer, tm_nota.i_salesman, tm_nota.i_area,
							tm_nota.n_nota_toplength, tm_nota.e_remark, tm_nota.f_cicil, tm_nota.i_nota_old, tm_nota.v_nota_netto,
							tm_nota.v_nota_discount1, tm_nota.v_nota_discount2, tm_nota.v_nota_discount3, tm_nota.v_nota_discount4,
							tm_nota.n_nota_discount1, tm_nota.n_nota_discount2, tm_nota.n_nota_discount3, tm_nota.n_nota_discount4,
							tm_nota.v_nota_gross, tm_nota.v_nota_discounttotal, tm_nota.n_price, tm_nota.v_nota_ppn, tm_nota.f_cicil,
							tm_nota.i_spb, tm_spb.i_spb_old, tm_nota.d_spb, tm_spb.v_spb, tm_spb.f_spb_consigment, tm_spb.i_spb_po,
							tm_spb.v_spb_discounttotal, tm_spb.f_spb_plusppn, tm_spb.f_spb_plusdiscount, tm_nota.i_sj, tm_nota.d_sj,
							tr_customer.e_customer_name, tm_nota.f_masalah, tm_nota.f_insentif,
							tr_customer_area.e_area_name,
							tr_salesman.e_salesman_name
				            from tm_nota 
				            left join tm_spb on (tm_nota.i_spb=tm_spb.i_spb and tm_nota.i_area=tm_spb.i_area and tm_spb.i_spb = '$ispb')
				            left join tm_promo on (tm_nota.i_spb_program=tm_promo.i_promo)
				            inner join tr_customer on (tm_nota.i_customer=tr_customer.i_customer)
				            inner join tr_salesman on (tm_nota.i_salesman=tr_salesman.i_salesman)
				            inner join tr_customer_area on (tm_nota.i_customer=tr_customer_area.i_customer)
				            where tm_nota.i_nota = '$inota' and tm_nota.i_area='$area'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacadetailnota($inota,$area){
		$this->db->select("*, tr_product_motif.e_product_motifname from tm_nota_item
						   inner join tr_product_motif on (tr_product_motif.i_product_motif=tm_nota_item.i_product_motif
						   and tr_product_motif.i_product=tm_nota_item.i_product)
						   where tm_nota_item.i_nota = '$inota' and tm_nota_item.i_area = '$area'  
						   order by tm_nota_item.n_item_no", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function updatenota($inota,$iarea,$ifakturkomersial){
      $query=$this->db->query("select i_faktur_komersial, i_seri_pajak from tm_nota where i_nota='$inota' and i_area='$iarea'");
      foreach($query->result() as $row){
            $komersial=$row->i_faktur_komersial;
            $pajak=$row->i_seri_pajak;
      }
      $this->db->query("insert into th_notapajak select * from tm_nota where i_nota='$inota' and i_area='$iarea'");
      $query=$this->db->query(" select a.*, b.i_customer_plu from tm_nota_item a
                                inner join tr_customer_plu b on (a.i_product=b.i_product)
                                where a.i_nota='$inota' and a.i_area='$iarea'");
      foreach($query->result() as $row){
            $this->db->query("insert into th_notapajak_item values('$row->i_sj','$komersial','$pajak',
                              '$row->i_nota', '$row->i_product', '$row->i_product_grade', '$row->i_product_motif', $row->n_deliver,
                              $row->v_unit_price, '$row->e_product_name', '$row->i_area', '$row->d_nota', $row->n_item_no, 
                              '$row->i_customer_plu')");
      }
    	$this->db->query("update tm_nota set i_faktur_komersial='$ifakturkomersial', i_seri_pajak=null, d_pajak=null, f_pajak_pengganti='t' 
                        where i_nota='$inota' and i_area='$iarea'");
    }
}

/* End of file Mmaster.php */
