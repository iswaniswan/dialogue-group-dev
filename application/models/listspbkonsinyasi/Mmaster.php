<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
                                SELECT
                                    *
                                FROM
                                    tr_area
                                WHERE
                                    f_area_consigment='t'
                                    and i_area IN (
                                    SELECT
                                        i_area
                                    FROM
                                        public.tm_user_area
                                    WHERE
                                        username = '$username'
                                        AND id_company = '$idcompany')
                                ", FALSE)->result();
    }

	function data($dfrom,$dto,$iarea,  $folder, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                               a.i_spb,
                               a.d_spb,
                               a.i_salesman,
                               a.i_customer,
                               b.e_customer_name,
                               b.e_customer_name as xname,
                               a.i_area,
                               (a.v_spb - a.v_spb_discounttotal) as bersih,
                               a.i_price_group,
                               '$dfrom' as dfrom,
                               '$dto' as dto,
                               '$iarea' as area,
                               '$i_menu' as i_menu,
                               '$folder' as folder
                            from
                               tm_spbkonsinyasi a 
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
                               and a.f_spb_consigment = 't' 
                               and a.i_area = '$iarea' 
                               and 
                               (
                                  a.d_spb >= to_date('$dfrom', 'dd-mm-yyyy') 
                                  AND a.d_spb <= to_date('$dto', 'dd-mm-yyyy')
                               )
                            order by
                               a.i_spb
                            
                            ",false);
		$datatables->add('action', function ($data) {
            $i_spb          = trim($data['i_spb']);
            $i_area         = trim($data['i_area']);
            $area           = trim($data['area']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $ipricegroup    = trim($data['i_price_group']);
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $data           = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_spb/$i_area/$dfrom/$dto/$ipricegroup/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
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

        $datatables->edit('bersih', function ($data) {
            $bersih = $data['bersih'];
            return number_format($bersih);
        });

        $datatables->edit('e_customer_name', function ($data) {
            $e_customer_name = $data['e_customer_name'];
            $i_customer = $data['i_customer'];
            return '('.$i_customer.')'.'-'.$e_customer_name;
        });

        $datatables->edit('e_customer_name', function ($data) {
            $e_customer_name = $data['e_customer_name'];
            $i_customer = $data['i_customer'];
            $xname = $data['xname'];
            if(substr($i_customer,2,3) != '000'){
                return '('.$i_customer.')'.'-'.$e_customer_name;
            }else{
                return $xname;
            }
            
        });

        $datatables->hide('i_menu');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('area');
        $datatables->hide('i_price_group');
        $datatables->hide('i_customer');
        $datatables->hide('xname');
        $datatables->hide('folder');


        return $datatables->generate();
	}

	function baca($ispb,$iarea){
      return $this->db->query(" 
                                select
                                   a.e_remark1 AS emark1,
                                   a.*,
                                   e.e_price_groupname,
                                   d.e_area_name,
                                   b.e_customer_name,
                                   b.e_customer_address,
                                   c.e_salesman_name,
                                   b.f_customer_first 
                                from
                                   tm_spbkonsinyasi a 
                                   inner join
                                      tr_customer b 
                                      on (a.i_customer = b.i_customer) 
                                   inner join
                                      tr_salesman c 
                                      on (a.i_salesman = c.i_salesman) 
                                   inner join
                                      tr_customer_area d 
                                      on (a.i_customer = d.i_customer) 
                                   left join
                                      tr_price_group e 
                                      on (a.i_price_group = e.i_price_group) 
                                where
                                   a.i_spb = '$ispb' 
                                   and a.i_area = '$iarea'
                                ", false);
    }

    function bacadetail($ispb,$iarea,$ipricegroup){
      return $this->db->query(" 
                                select
                                   a.i_spb,
                                   a.i_product,
                                   a.i_product_grade,
                                   a.i_product_motif,
                                   a.n_order,
                                   a.n_deliver,
                                   a.n_stock,
                                   a.v_unit_price,
                                   a.e_product_name,
                                   a.i_op,
                                   a.i_area,
                                   a.e_remark as ket,
                                   a.n_item_no,
                                   b.e_product_motifname,
                                   c.v_product_retail as hrgnew,
                                   a.i_product_status 
                                from
                                   tm_spbkonsinyasi_item a,
                                   tr_product_motif b,
                                   tr_product_price c 
                                where
                                   a.i_spb = '$ispb' 
                                   and i_area = '$iarea' 
                                   and a.i_product = b.i_product 
                                   and a.i_product_motif = b.i_product_motif 
                                   and a.i_product = c.i_product 
                                   and c.i_price_group = '$ipricegroup' 
                                   and c.i_product_grade = 'A' 
                                order by
                                   a.n_item_no
                                ", false);
    }

    function bacadetailnilaispb($ispb,$iarea){
      return $this->db->query(" 
                                select
                                    (sum(a.n_deliver * a.v_unit_price)) AS nilaispb 
                                from
                                   tm_spbkonsinyasi_item a 
                                where
                                   a.i_spb = '$ispb' 
                                   and a.i_area = '$iarea'
                                ", false);
    }

    function bacadetailnilaiorderspb($ispb,$iarea){
      return $this->db->query(" 
                                select
                                    (sum(a.n_order * a.v_unit_price)) AS nilaiorderspb 
                                from
                                   tm_spbkonsinyasi_item a 
                                where
                                   a.i_spb = '$ispb' 
                                   and a.i_area = '$iarea'
                                 ", false);
    }

    function getdata($iarea,$dfrom,$dto){
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
                                   tm_spbkonsinyasi a 
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
                                   and a.i_area = '$iarea' 
                                   and 
                                   (
                                      a.d_spb >= to_date('$dfrom', 'dd-mm-yyyy') 
                                      AND a.d_spb <= to_date('$dto', 'dd-mm-yyyy')
                                   )
                                order by
                                   a.i_spb
                                ", FALSE);
    }

    function updateheader($ispb, $iarea, $nspbdiscount1, $vspbdiscount1, $vspbdiscounttotal, $vspb){
       $query      = $this->db->query("SELECT current_timestamp as c");
       $row        = $query->row();
       $dspbupdate = $row->c;
          $data = array( 
             'n_spb_discount1'    => $nspbdiscount1,
             'v_spb_discount1'    => $vspbdiscount1,
             'v_spb_discounttotal'=> $vspbdiscounttotal,
             'v_spb'              => $vspb,
             'd_spb_update'       => $dspbupdate,
             'i_product_group'    => '01'
                );
       $this->db->where('i_spb', $ispb);
       $this->db->where('i_area', $iarea);
       $this->db->update('tm_spbkonsinyasi', $data);
    }
}

/* End of file Mmaster.php */
