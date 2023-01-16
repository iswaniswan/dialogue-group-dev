<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom,$dto,$area, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select a.i_spb, a.d_spb, a.i_salesman, a.i_customer, b.e_customer_name, a.i_area, (a.v_spb-a.v_spb_discounttotal) as bersih, a.f_spb_cancel, a.d_spb_receive, 
                            a.f_spb_stockdaerah, a.i_approve1, a.i_notapprove, a.i_approve2,
                            a.i_store, a.f_spb_siapnotagudang, a.f_spb_op, a.f_spb_opclose, a.f_spb_siapnotasales,
                            a.v_spb, a.v_spb_discounttotal, d.i_dkb, x.e_customer_name as xname, d.v_nota_netto, d.i_nota, d.i_sj,
                            '$dfrom' as dfrom, '$dto' as dto, '$area' as area, '$i_menu' as i_menu
                            from tm_spb a 
                            left join tm_nota d on(a.i_spb=d.i_spb and a.i_area=d.i_area and d.f_nota_cancel='f')
                            left join tr_customer b on(a.i_customer=b.i_customer and a.i_area=b.i_area)
                            left join tr_customer_tmp x on(a.i_customer=x.i_customer and a.i_spb=x.i_spb and a.i_area=x.i_area 
                            and x.i_customer like '%000')
                            ,tr_area c
                            where 
                            a.i_area=c.i_area and					
                            a.i_area='$area' and
                            (a.d_spb >= to_date('$dfrom','dd-mm-yyyy') AND
                            a.d_spb <= to_date('$dto','dd-mm-yyyy'))
                            order by a.i_spb",false);
		$datatables->add('action', function ($data) {
            $i_spb    = trim($data['i_spb']);
            $i_area   = trim($data['i_area']);
            $area     = trim($data['area']);
            $dfrom    = trim($data['dfrom']);
            $dto      = trim($data['dto']);
            $i_menu   = $data['i_menu'];
            $data     = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"listterimaspb/cform/edit/$i_spb/$i_area/$dfrom/$dto/$area/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
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

        $datatables->edit('d_spb_receive', function ($data) {
            $d_spb_receive = $data['d_spb_receive'];
            if($d_spb_receive == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_spb_receive) );
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

        $datatables->edit('f_spb_cancel', function ($data) {
            $f_spb_cancel = $data['f_spb_cancel'];
            $i_approve1 = $data['i_approve1'];
            $i_notapprove = $data['i_notapprove'];
            $i_approve2 = $data['i_approve2'];
            $i_store = $data['i_store'];
            $i_nota = $data['i_nota'];
            $f_spb_siapnotagudang = $data['f_spb_siapnotagudang'];
            $f_spb_op = $data['f_spb_op'];
            $f_spb_opclose = $data['f_spb_opclose'];
            $f_spb_siapnotasales = $data['f_spb_siapnotasales'];
            $i_sj = $data['i_sj'];
            $i_dkb = $data['i_dkb'];
            $f_spb_stockdaerah = $data['f_spb_stockdaerah'];
            if($f_spb_cancel == 't'){
                return 'Batal';
            }else if(($i_approve1 == null) && ($i_notapprove == null)){
                return 'Sales';
            }else if(($i_approve1 == null) && ($i_notapprove != null)){
                return 'Reject (Sls)';
            }else if(($i_approve1 != null) && ($i_approve2 == null) && ($i_notapprove == null)){
                return 'Keuangan';
            }else if(($i_approve1 != null) && ($i_approve2 == null) && ($i_notapprove != null)){
                return 'Reject (AR)';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store == null)){
                return 'Gudang';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 'f')){
                return 'Pemenuhan SPB';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 't') && ($f_spb_opclose == 'f')){
                return 'Proses OP';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_siapnotasales == 'f') && ($f_spb_opclose == 't')){
                return 'OP Close';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 'f')){
                return 'Siap SJ (Sales)';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj == null)){
                return 'Siap SJ';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb == null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                return 'Siap DKB';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                return 'Siap Nota';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 't') && ($i_sj == null)){
                return 'Siap SJ';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb == null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                return 'Siap DKB';
            }else if(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb != null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                return 'Siap Nota';
            }else{
                return 'Unknown';
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
        $datatables->hide('i_notapprove');
        $datatables->hide('i_approve1');
        $datatables->hide('i_approve2');
        $datatables->hide('i_store');
        $datatables->hide('f_spb_siapnotagudang');
        $datatables->hide('f_spb_op');
        $datatables->hide('f_spb_opclose');
        $datatables->hide('f_spb_siapnotasales');
        $datatables->hide('i_sj');
        $datatables->hide('i_dkb');
        $datatables->hide('i_customer');
        $datatables->hide('area');
        $datatables->hide('i_nota');
        $datatables->hide('xname');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('v_spb');
        $datatables->hide('v_spb_discounttotal');
        $datatables->hide('v_nota_netto');

        return $datatables->generate();
	}

	function baca($ispb,$iarea){
      return $this->db->query(" select a.e_remark1 AS emark1, a.*, e.e_price_groupname,
                        d.e_area_name, b.e_customer_name, b.e_customer_address, c.e_salesman_name, b.f_customer_first
                        from tm_spb a
                        inner join tr_customer b on (a.i_customer=b.i_customer)
                        inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                        inner join tr_customer_area d on (a.i_customer=d.i_customer)
                        left join tr_price_group e on (a.i_price_group=e.i_price_group)
                        where a.i_spb ='$ispb' and a.i_area='$iarea'", false);
    }

    function bacadetail($ispb,$iarea){
      return $this->db->query(" select a.i_spb,a.i_product,a.i_product_grade,a.i_product_motif,a.n_order,a.n_deliver,a.n_stock,
                        a.v_unit_price,a.e_product_name,a.i_op,a.i_area,a.e_remark,a.n_item_no, b.e_product_motifname,
                        a.i_product_status
                        from tm_spb_item a, tr_product_motif b
                        where a.i_spb = '$ispb' and i_area='$iarea' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                        order by a.n_item_no", false);
    }

    function bacadetailnilaispb($ispb,$iarea){
      return $this->db->query(" select (sum(a.n_deliver * a.v_unit_price)) AS nilaispb from tm_spb_item a
                 where a.i_spb = '$ispb' and a.i_area='$iarea' ", false);
    }

    function bacadetailnilaiorderspb($ispb,$iarea){
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
