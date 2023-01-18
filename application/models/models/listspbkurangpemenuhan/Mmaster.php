<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($area, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select distinct a.i_spb, a.d_spb, a.i_salesman, a.i_customer, 
                            c.e_customer_name, a.i_area, a.v_spb, a.v_spb_discounttotal, (a.v_spb-a.v_spb_discounttotal) as bersih,
                            a.i_sj, a.i_nota, a.f_spb_stockdaerah, a.i_product_group, a.i_spb_program,
                            '$i_menu' as i_menu, a.i_price_group,'$area' as area
                            from tm_spb a
                            inner join tm_spb_item b on (a.i_spb=b.i_spb and a.i_area=b.i_area)
                            inner join tr_customer c on (a.i_customer=c.i_customer and a.i_area=c.i_area)
                            where not a.i_sj is null and not a.i_nota is null
                            and a.f_spb_cancel='f' and b.n_deliver < b.n_order
                            and a.i_area='$area' 
                            order by a.i_spb, a.d_spb",false);
		$datatables->add('action', function ($data) {
            $i_spb          = trim($data['i_spb']);
            $i_spb          = trim($data['i_spb']);
            $i_spb_program  = trim($data['i_spb_program']);
            $i_area         = trim($data['i_area']);
            $area           = trim($data['area']);
            $ipricegroup    = trim($data['i_price_group']);
            $i_product_group= trim($data['i_product_group']);
            $i_menu         = $data['i_menu'];
            $data           = '';
            if(check_role($i_menu, 3)){
                if($i_spb_program!=null){
                    $data .= "<a href=\"#\" onclick='show(\"listspbkurangpemenuhan/cform/editpromo/$i_spb/$i_area/$i_spb_program/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
                }else{
                    $data .= "<a href=\"#\" onclick='show(\"listspbkurangpemenuhan/cform/editspb/$i_spb/$i_area/$ipricegroup/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
                }
            }
			return $data;
        });

        $datatables->edit('d_spb', function ($data) {
        $d_spb = $data['d_spb'];
            if($d_spb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_spb) );
            }
        });

        $datatables->edit('f_spb_stockdaerah', function ($data) {
            $f_spb_stockdaerah = $data['f_spb_stockdaerah'];
            if($f_spb_stockdaerah == 't'){
                return 'Ya';
            }else{
                return 'Tidak';
            }
        });

        $datatables->edit('bersih', function ($data) {
            $bersih = $data['bersih'];
            $v_spb = $data['v_spb'];
            $v_spb_discounttotal = $data['v_spb_discounttotal'];
            return number_format($v_spb-$v_spb_discounttotal);
        });

        $datatables->edit('v_spb', function ($data) {
            $v_spb = $data['v_spb'];
            return number_format($v_spb);
        });

        $datatables->edit('v_spb_discounttotal', function ($data) {
            $v_spb_discounttotal = $data['v_spb_discounttotal'];
            return number_format($v_spb_discounttotal);
        });

        $datatables->edit('e_customer_name', function ($data) {
            $e_customer_name = $data['e_customer_name'];
            $i_customer = $data['i_customer'];
            return '('.$i_customer.')'.'-'.$e_customer_name;
        });

        $datatables->hide('i_menu');
        $datatables->hide('i_customer');
        $datatables->hide('i_price_group');
        $datatables->hide('i_product_group');
        $datatables->hide('i_spb_program');
        $datatables->hide('i_price_group');
        $datatables->hide('v_spb_discounttotal');
        $datatables->hide('area');

        return $datatables->generate();
    }
    
    public function bacapromo($ispb,$iarea){
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
                    AND tm_spb.i_area='$iarea'
        ");
    }

    public function bacadetailpromo($ispb,$iarea){
        return $this->db->query(" 
                SELECT
                    a.*, 
                    b.e_product_motifname 
                FROM 
                    tm_spb_item a, 
                    tr_product_motif b
                WHERE 
                    a.i_spb = '$ispb' 
                    AND i_area='$iarea'
                    AND a.i_product=b.i_product 
                    AND a.i_product_motif=b.i_product_motif
                ORDER BY a.i_product"
        );
    }


	function baca($ispb,$iarea){
      $this->db->select(" a.e_remark1 AS emark1, a.*, e.e_price_groupname,
                          d.e_area_name, b.e_customer_name, b.e_customer_address, c.e_salesman_name, b.f_customer_first
                          from tm_spb a
                          inner join tr_customer b on (a.i_customer=b.i_customer)
                          inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                          inner join tr_customer_area d on (a.i_customer=d.i_customer)
                          left join tr_price_group e on (a.i_price_group=e.i_price_group)
                          where a.i_spb ='$ispb' and a.i_area='$iarea'", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->row();
      }
    }

    function bacadetail($ispb,$iarea,$ipricegroup){
      $this->db->select(" a.i_spb,a.i_product,a.i_product_grade,a.i_product_motif,a.n_order,a.n_deliver,a.n_stock,
                        a.v_unit_price,a.e_product_name,a.i_op,a.i_area,a.e_remark as ket,a.n_item_no, b.e_product_motifname,
                        c.v_product_retail as hrgnew, a.i_product_status
                        from tm_spb_item a, tr_product_motif b, tr_product_price c
                        where a.i_spb = '$ispb' and i_area='$iarea' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                        and a.i_product=c.i_product and c.i_price_group='$ipricegroup'
                        order by a.n_item_no", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }

    function bacadetailnilaispb($ispb,$iarea,$ipricegroup){
      return $this->db->query(" select (sum(a.n_deliver * a.v_unit_price)) AS nilaispb from tm_spb_item a
                                where a.i_spb = '$ispb' and a.i_area='$iarea' ", false);
    }

    function bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup){
      return $this->db->query(" select (sum(a.n_order * a.v_unit_price)) AS nilaiorderspb from tm_spb_item a
                                where a.i_spb = '$ispb' and a.i_area='$iarea' ", false);
    }

    function getdata($area){
        return $this->db->query("Select a.i_customer, a.f_spb_stockdaerah, a.d_spb, a.f_spb_cancel, a.i_approve1, a.i_notapprove, a.i_approve2,
                                a.i_store, a.i_nota, a.f_spb_siapnotagudang, a.f_spb_op, a.f_spb_opclose, a.f_spb_siapnotasales,
                                a.i_sj, a.v_spb, a.v_spb_discounttotal, a.i_spb, a.d_spb, a.i_salesman,
                                a.i_area, a.i_spb_old, a.i_spb_program, a.i_product_group, a.i_spb_program, a.i_price_group,
                                b.e_customer_name, c.e_area_name, d.i_dkb, x.e_customer_name as xname, e.e_product_groupname_short
                                from tm_spb a 
                                left join tm_nota d on(a.i_spb=d.i_spb and a.i_area=d.i_area and d.f_nota_cancel='f')
                                left join tr_customer b on(a.i_customer=b.i_customer and a.i_area=b.i_area)
                                left join tr_customer_tmp x on(a.i_customer=x.i_customer and a.i_spb=x.i_spb and a.i_area=x.i_area and x.i_customer like '%000')
                                , tr_area c, tr_product_group e
                                where a.i_sj is null and a.f_spb_cancel='f' and
                                a.i_area=c.i_area and	a.i_nota is null and 				
                                a.i_product_group=e.i_product_group and a.i_area='$area' 
                                order by a.d_spb");
    }
}

/* End of file Mmaster.php */
