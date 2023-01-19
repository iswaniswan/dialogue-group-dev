<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area where f_area_real='t' order by i_area
        ", FALSE)->result();
    }

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($dfrom, $dto, $iarea, $folder){
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
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        SELECT 
                            a.i_faktur_komersial,
                            a.i_seri_pajak,
                            a.d_pajak,
                            a.i_nota,
                            a.d_nota,
                            a.i_area,
                            a.i_customer,
                            a.v_nota_discounttotal,
                            a.v_nota_netto,
                            a.f_nota_cancel, 
                            b.e_customer_name,
                            a.i_spb,
                            '$folder' as folder,
                            '$dto' as dto,
                            '$dfrom' as dfrom
                        FROM 
                            tm_nota a, 
                            tr_customer b
                        WHERE 
                            a.i_customer=b.i_customer 
                            and a.f_ttb_tolak='f'
                            and not a.i_nota isnull 
                            and not a.i_faktur_komersial isnull
                            and a.i_area='$iarea'
                            and a.d_nota >= '$dfrom'
                            and a.d_nota <= '$dto'
                        ORDER BY 
                            a.i_faktur_komersial"
                    ,false);

        $datatables->edit('v_nota_discounttotal', function($data){
            return number_format($data['v_nota_discounttotal']);
        });

        $datatables->edit('v_nota_netto', function($data){
            return number_format($data['v_nota_netto']);
        });

        $datatables->edit('d_pajak', function($data){
            return date("d-m-Y", strtotime($data['d_pajak']));
        });

        $datatables->edit('d_nota', function($data){
            return date("d-m-Y", strtotime($data['d_nota']));
        });

        $datatables->edit('i_customer', function($data){
            return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->add('action', function ($data) {
            $ifk            = $data['i_faktur_komersial'];
            $folder         = $data['folder'];
            $iarea          = $data['i_area'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $inota          = $data['i_nota'];
            $fnotacancel    = $data['f_nota_cancel'];
            $ispb           = $data['i_spb'];
            $data           = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$inota/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('e_customer_name');
        $datatables->hide('f_nota_cancel');
        $datatables->hide('i_spb');
        return $datatables->generate();
    }

    function bacanota($inota,$ispb,$area){
        $query = $this->db->query(" 
                                SELECT 
                                    tm_nota.i_nota, 
                                    tm_nota.d_nota, 
                                    tm_nota.i_customer, 
                                    tm_nota.i_salesman, 
                                    tm_nota.i_area,
                                    tm_nota.n_nota_toplength, 
                                    tm_nota.e_remark, 
                                    tm_nota.f_cicil, 
                                    tm_nota.i_nota_old, 
                                    tm_nota.v_nota_netto,
                                    tm_nota.v_nota_discount1, 
                                    tm_nota.v_nota_discount2, 
                                    tm_nota.v_nota_discount3, 
                                    tm_nota.v_nota_discount4,
                                    tm_nota.n_nota_discount1, 
                                    tm_nota.n_nota_discount2, 
                                    tm_nota.n_nota_discount3, 
                                    tm_nota.n_nota_discount4,
                                    tm_nota.v_nota_gross, 
                                    tm_nota.v_nota_discounttotal, 
                                    tm_nota.n_price, 
                                    tm_nota.v_nota_ppn, 
                                    tm_nota.f_cicil,
                                    tm_nota.i_spb, 
                                    tm_spb.i_spb_old, 
                                    tm_nota.d_spb,
                                    tm_spb.v_spb, 
                                    tm_spb.f_spb_consigment, 
                                    tm_spb.i_spb_po,
                                    tm_spb.v_spb_discounttotal, 
                                    tm_spb.f_spb_plusppn, 
                                    tm_spb.f_spb_plusdiscount, 
                                    tm_spb.n_spb_toplength,
                                    tm_nota.i_sj, tm_nota.d_sj,
                                    tr_customer.e_customer_name, 
                                    tm_nota.f_masalah, 
                                    tm_nota.f_insentif,
							        tr_customer_area.e_area_name,
							        tr_salesman.e_salesman_name
                                FROM 
                                    tm_nota 
				                    left join tm_spb on (tm_nota.i_spb=tm_spb.i_spb and tm_nota.i_area=tm_spb.i_area and tm_spb.i_spb = '$ispb')
				                    left join tm_promo on (tm_nota.i_spb_program=tm_promo.i_promo)
				                    inner join tr_customer on (tm_nota.i_customer=tr_customer.i_customer)
				                    inner join tr_salesman on (tm_nota.i_salesman=tr_salesman.i_salesman)
				                    inner join tr_customer_area on (tm_nota.i_customer=tr_customer_area.i_customer)
                                WHERE 
                                    tm_nota.i_nota = '$inota' and 
                                    tm_nota.i_area='$area'"
                                , false);
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacadetailnota($inota,$area){
        $query = $this->db->query("
                                    SELECT
                                        * 
                                    FROM 
                                        tm_nota_item
						                inner join tr_product_motif on (tr_product_motif.i_product_motif=tm_nota_item.i_product_motif
										and tr_product_motif.i_product=tm_nota_item.i_product)
                                    WHERE 
                                        tm_nota_item.i_nota = '$inota' 
                                        and tm_nota_item.i_area = '$area'  
                                    ORDER BY 
                                        tm_nota_item.n_item_no"
                                    , false);
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }
}

/* End of file Mmaster.php */
