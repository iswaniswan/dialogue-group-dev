<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    
    function bacaperiode($area,$dfrom,$dto){
		return $this->db->query("select	a.i_customer, a.f_spb_stockdaerah, a.d_spb, a.f_spb_cancel, a.i_approve1, a.i_notapprove, a.i_approve2,
                                a.i_store, a.i_nota, a.f_spb_siapnotagudang, a.f_spb_op, a.f_spb_opclose, a.f_spb_siapnotasales,
                                a.i_sj, a.v_spb, a.v_spb_discounttotal, a.i_spb, a.d_spb, a.i_salesman, a.i_product_group, y.e_product_groupname,
                                a.i_area, a.i_spb_old, a.i_spb_program, a.i_product_group, a.i_spb_program, a.i_price_group,
                                b.e_customer_name, c.e_area_name, d.i_dkb, x.e_customer_name as xname, d.v_nota_netto, d.i_nota, d.i_sj
                                from tm_spb a
                                inner join tr_product_group y on(y.i_product_group=a.i_product_group)
                                left join tm_nota d on(a.i_spb=d.i_spb and a.i_area=d.i_area and d.f_nota_cancel='f')
                                left join tr_customer b on(a.i_customer=b.i_customer)
                                left join tr_customer_tmp x on(a.i_customer=x.i_customer and a.i_spb=x.i_spb and a.i_area=x.i_area and x.i_customer like '%000')
                                , tr_area c
                                where 
                                a.i_area=c.i_area and			
                                a.i_area='$area' and
                                (a.d_spb >= to_date('$dfrom','dd-mm-yyyy') AND
                                a.d_spb <= to_date('$dto','dd-mm-yyyy'))
                                order by a.i_product_group, a.i_spb",false);	
    }

	public function bacapromo($ispb,$area){
        return $this->db->query(" 
                SELECT
                    *, 
                    tm_promo.e_promo_name
                FROM 
                    tm_spb 
                INNER JOIN 
                    tm_promo on (tm_spb.i_spb_program=tm_promo.i_promo)
                INNER JOIN 
                    tr_customer on (tm_spb.i_customer=tr_customer.i_customer)
                INNER JOIN 
                    tr_salesman on (tm_spb.i_salesman=tr_salesman.i_salesman)
                INNER JOIN 
                    tr_customer_area on (tm_spb.i_customer=tr_customer_area.i_customer)
                INNER JOIN 
                    tr_price_group on (tm_spb.i_price_group=tr_price_group.i_price_group)
                WHERE 
                    i_spb ='$ispb' 
                    AND tm_spb.i_area='$area'
        ");
    }

    public function bacadetailpromo($ispb,$area){
        return $this->db->query(" 
                SELECT
                    a.*, 
                    b.e_product_motifname 
                FROM 
                    tm_spb_item a, 
                    tr_product_motif b
                WHERE 
                    a.i_spb = '$ispb' 
                    AND i_area='$area'
                    AND a.i_product=b.i_product 
                    AND a.i_product_motif=b.i_product_motif
                ORDER BY a.i_product"
        );
    }


	function baca($ispb,$area){
      $this->db->select(" a.e_remark1 AS emark1, a.*, e.e_price_groupname,
                          d.e_area_name, b.e_customer_name, b.e_customer_address, c.e_salesman_name, b.f_customer_first
                          from tm_spb a
                          inner join tr_customer b on (a.i_customer=b.i_customer)
                          inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                          inner join tr_customer_area d on (a.i_customer=d.i_customer)
                          left join tr_price_group e on (a.i_price_group=e.i_price_group)
                          where a.i_spb ='$ispb' and a.i_area='$area'", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->row();
      }
    }

    function bacadetail($ispb,$area,$ipricegroup){
      $this->db->select(" a.i_spb,a.i_product,a.i_product_grade,a.i_product_motif,a.n_order,a.n_deliver,a.n_stock,
                        a.v_unit_price,a.e_product_name,a.i_op,a.i_area,a.e_remark as ket,a.n_item_no, b.e_product_motifname,
                        c.v_product_retail as hrgnew, a.i_product_status
                        from tm_spb_item a, tr_product_motif b, tr_product_price c
                        where a.i_spb = '$ispb' and i_area='$area' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                        and a.i_product=c.i_product and c.i_price_group='$ipricegroup'
                        order by a.n_item_no", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }

    function bacadetailnilaispb($ispb,$area,$ipricegroup){
      return $this->db->query(" select (sum(a.n_deliver * a.v_unit_price)) AS nilaispb from tm_spb_item a
                                where a.i_spb = '$ispb' and a.i_area='$area' ", false);
    }

    function bacadetailnilaiorderspb($ispb,$area,$ipricegroup){
      return $this->db->query(" select (sum(a.n_order * a.v_unit_price)) AS nilaiorderspb from tm_spb_item a
                                where a.i_spb = '$ispb' and a.i_area='$area' ", false);
    }

    function getdata($iarea,$dfrom,$dto){
        return $this->db->query("Select a.i_customer, a.f_spb_stockdaerah, a.d_spb, a.f_spb_cancel, a.i_approve1, a.i_notapprove, a.i_approve2,
                                a.i_store, a.i_nota, a.f_spb_siapnotagudang, a.f_spb_op, a.f_spb_opclose, a.f_spb_siapnotasales,
                                a.i_sj, a.v_spb, a.v_spb_discounttotal, a.i_spb, a.d_spb, a.i_salesman,
                                a.i_area, a.i_spb_old, a.i_spb_program, a.i_product_group, a.i_spb_program, a.i_price_group,
                                b.e_customer_name, c.e_area_name, d.i_dkb, x.e_customer_name as xname
                                from tm_spb a 
                                left join tm_nota d on(a.i_spb=d.i_spb and a.i_area=d.i_area and d.f_nota_cancel='f')
                                left join tr_customer b on(a.i_customer=b.i_customer and a.i_area=b.i_area)
                                left join tr_customer_tmp x on(a.i_customer=x.i_customer and a.i_spb=x.i_spb and a.i_area=x.i_area and x.i_customer like '%000')
                                , tr_area c
                                where 
                                a.i_area=c.i_area and					
                                a.i_area='$iarea' and
                                (a.d_spb >= to_date('$dfrom','dd-mm-yyyy') and
                                a.d_spb <= to_date('$dto','dd-mm-yyyy'))
                                order by a.i_spb");
    }

    function cancel($ispb, $iarea){
        $this->db->set(
            array(
                'f_spb_cancel'  => 't'
            )
        );
        $this->db->where('i_spb',$ispb);
        $this->db->where('i_area',$iarea);
        return $this->db->update('tm_spb');
    }
}

/* End of file Mmaster.php */
