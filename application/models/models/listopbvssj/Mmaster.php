<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($dfrom,$dto,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                               a.i_orderpb,
                               a.d_orderpb,
                               a.i_customer,
                               c.e_customer_name,
                               a.i_spg,
                               sum(d.n_quantity_order) as jumlah_order,
                               a.i_spmb,
                               h.d_spmb,
                               e.i_sjpb,
                               e.d_sjpb,
                               sum(f.n_deliver) as jumlah_kirim,
                               sum(f.n_deliver * f.v_unit_price) as pemenuhan,
                               e.d_sjpb_receive,
                               e.i_bapb,
                               g.d_bapb,
                               (
                               h.d_spmb - a.d_orderpb
                               )
                               as orderkespmb,
                               (
                                  e.d_sjpb - h.d_spmb
                               )
                               as spmbkesjpb,
                               (
                                  g.d_bapb - e.d_sjpb
                               )
                               as sjpbkebapb,
                               (
                                  e.d_sjpb_receive - g.d_bapb
                               )
                               bapbkesjreceive,
                               (
                                  e.d_sjpb_receive - a.d_orderpb
                               )
                               orderkereceive,
                               a.f_orderpb_cancel as status,  
                               '$dfrom' as  dfrom,
                               '$dto' as dto,
                               '$folder' as folder,
                               '$i_menu' as i_menu
                            from
                               tr_spg b,
                               tr_customer c,
                               tm_orderpb a 
                               inner join
                                  tm_orderpb_item d 
                                  on(a.i_orderpb = d.i_orderpb 
                                  and a.i_area = d.i_area 
                                  and a.i_customer = d.i_customer) 
                               left join
                                  tm_sjpb e 
                                  on((a.i_spmb = e.i_spmb 
                                  or TRIM(a.i_orderpb) = TRIM(e.i_spmb) ) 
                                  and a.i_area = e.i_area 
                                  and a.i_customer = e.i_customer) 
                               left join
                                  tm_sjpb_item f 
                                  on(a.i_area = f.i_area 
                                  and d.i_area = f.i_area 
                                  and e.i_sjpb = f.i_sjpb 
                                  and e.i_area = f.i_area 
                                  and d.i_product = f.i_product 
                                  and d.i_product_grade = f.i_product_grade 
                                  and d.i_product_motif = f.i_product_motif) 
                               left join
                                  tm_bapbsjpb_item g 
                                  on(e.i_sjpb = g.i_sjpb 
                                  and e.i_area = g.i_area) 
                               left join
                                  tm_spmb h 
                                  on(a.i_spmb = h.i_spmb 
                                  and a.i_area = h.i_area) 
                            where
                               a.i_spg = b.i_spg 
                               and a.i_customer = c.i_customer 
                               and a.d_orderpb >= to_date('$dfrom', 'dd-mm-yyyy') 
                               and a.d_orderpb <= to_date('$dto', 'dd-mm-yyyy') 
                               and a.f_orderpb_cancel = 'f' 
                            group by
                               a.i_orderpb,
                               a.d_orderpb,
                               a.i_spg,
                               b.e_spg_name,
                               a.i_customer,
                               c.e_customer_name,
                               a.f_orderpb_cancel,
                               e.i_sjpb,
                               e.d_sjpb,
                               e.d_sjpb_receive,
                               a.i_spmb,
                               g.d_bapb,
                               e.i_bapb,
                               h.d_spmb 
                            ORDER BY
                               a.i_orderpb
                            ");

        $datatables->add('action', function ($data) {
            $iorderpb       = trim($data['i_orderpb']);
            $status         = $data['status'];
            $ispmb          = $data['i_spmb'];
            $icustomer      = $data['i_customer'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iorderpb/$dfrom/$dto/$icustomer\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && ($ispmb == '' || $ispmb == null)){
                if($status == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$iorderpb\",\"$icustomer\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            
                }
            }
            return $data;
        });

        $datatables->edit('d_orderpb', function ($data) {
            $d_orderpb = $data['d_orderpb'];
            if($d_orderpb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_orderpb) );
            }
        });

        $datatables->edit('pemenuhan',function($data){
            return number_format($data['pemenuhan']);
        });

        $datatables->edit('d_spmb', function ($data) {
            $d_spmb = $data['d_spmb'];
            if($d_spmb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_spmb) );
            }
        });

        $datatables->edit('d_sjpb', function ($data) {
            $d_sjpb = $data['d_sjpb'];
            if($d_sjpb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjpb) );
            }
        });

        $datatables->edit('d_sjpb_receive', function ($data) {
            $d_sjpb_receive = $data['d_sjpb_receive'];
            if($d_sjpb_receive == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjpb_receive) );
            }
        });

        $datatables->edit('d_bapb', function ($data) {
            $d_bapb = $data['d_bapb'];
            if($d_bapb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_bapb) );
            }
        });

        $datatables->edit('i_customer', function ($data) {
            $icust = $data['i_customer'];
            $ecust = $data['e_customer_name'];
            return '( '.$icust.' )'.' - '.$ecust;
        });

        $datatables->edit('status', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->hide('e_customer_name');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cancel($iorderpb, $icustomer){
        $this->db->query("
                        update 
                            tm_orderpb 
                        set 
                            f_orderpb_cancel='t' 
                        where 
                            i_orderpb='$iorderpb' 
                            and i_customer='$icustomer'
                        ");
    }

    public function baca($iorderpb,$icustomer){
        return $this->db->query("
                                    select
                                       a.*,
                                       b.e_area_name,
                                       c.e_customer_name,
                                       d.e_spg_name 
                                    from
                                       tm_orderpb a,
                                       tr_area b,
                                       tr_customer c,
                                       tr_spg d 
                                    where
                                       a.i_area = b.i_area 
                                       and a.i_customer = c.i_customer 
                                       and a.i_spg = d.i_spg 
                                       and a.i_orderpb = '$iorderpb' 
                                       and a.i_customer = '$icustomer'
                                    ", false);
    }

    public function bacadetail($iorderpb,$icustomer){
        return $this->db->query("
                                    select
                                       a.*,
                                       b.e_product_motifname 
                                    from
                                       tm_orderpb_item a,
                                       tr_product_motif b 
                                    where
                                       a.i_orderpb = '$iorderpb' 
                                       and a.i_customer = '$icustomer' 
                                       and a.i_product = b.i_product 
                                       and a.i_product_motif = b.i_product_motif 
                                    order by
                                       a.n_item_no
                                    ", false);
    }

    public function getproduct($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.e_product_name AS nama
            FROM
                tr_product a,
                tr_product_price b,
                tr_product_motif c
            WHERE
                a.i_product = b.i_product
                AND b.i_price_group = '00'
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(a.e_product_name) LIKE '%$cari%')
                AND a.i_product = c.i_product
            ORDER BY
                a.e_product_name",
        FALSE);
    } 

    public function getdetailproduct($iproduct){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.e_product_name AS nama,
                b.v_product_retail AS harga,
                c.i_product_motif AS motif,
                c.e_product_motifname AS namamotif
            FROM
                tr_product a,
                tr_product_price b,
                tr_product_motif c
            WHERE
                a.i_product = b.i_product
                AND b.i_price_group = '00'
                AND a.i_product = '$iproduct'
                AND a.i_product = c.i_product
            ORDER BY
                a.e_product_name",
        FALSE);
    }

    public function updatesjheader($isj,$dsj,$iarea,$vsjpbr,$icustomer,$ispg){
        $query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjupdate= $row->c;
    	$this->db->set(
    		array(
				'v_sjpbr'         => $vsjpbr,
                'i_customer'      => $icustomer,
                'i_spg'           => $ispg,
                'd_sjpbr_update'  => $dsjupdate,
                'f_sjpbr_cancel'  => 'f'
    		));
    	$this->db->where('i_sjpbr',$isj);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjpbr');
    }

    public function deletesjdetail( $isj, $iarea, $iproduct, $iproductgrade, $iproductmotif){
	    $this->db->query("
                        DELETE FROM 
                            tm_sjpbr_item 
                        WHERE 
                            i_sjpbr='$isj'
                            and i_area='$iarea'
                            and i_product='$iproduct' 
                            and i_product_grade='$iproductgrade' 
                            and i_product_motif='$iproductmotif'
                        ");
    }

    public function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj){
        $queri = $this->db->query("
                                SELECT 
                                    i_trans 
                                FROM 
                                    tm_ic_trans 
                                WHERE 
                                    i_product='$iproduct' 
                                    and i_product_grade='$iproductgrade' 
                                    and i_product_motif='$iproductmotif'
                                    and i_store='$istore' 
                                    and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' 
                                    and i_refference_document='$isj'
                                ");
		$row   = $queri->row();
        $query=$this->db->query(" 
                                DELETE FROM 
                                    tm_ic_trans 
                                WHERE 
                                    i_product='$iproduct' 
                                    and i_product_grade='$iproductgrade' 
                                    and i_product_motif='$iproductmotif'
                                    and i_store='$istore' 
                                    and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' 
                                    and i_refference_document='$isj'
                                 ",false);
        if($row->i_trans!=''){
            return $row->i_trans;
        }else{
            return 1;
        }
    }

    function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                update
                                   tm_mutasi 
                                set
                                   n_mutasi_bbm = n_mutasi_bbm -$ qsj, n_saldo_akhir = n_saldo_akhir -$ qsj 
                                where
                                   i_product = '$iproduct' 
                                   and i_product_grade = '$iproductgrade' 
                                   and i_product_motif = '$iproductmotif' 
                                   and i_store = '$istore' 
                                   and i_store_location = '$istorelocation' 
                                   and i_store_locationbin = '$istorelocationbin' 
                                   and e_mutasi_periode = '$emutasiperiode'
                                ",false);
    }
    
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                update
                                   tm_ic 
                                set
                                   n_quantity_stock = n_quantity_stock -$ qsj 
                                where
                                   i_product = '$iproduct' 
                                   and i_product_grade = '$iproductgrade' 
                                   and i_product_motif = '$iproductmotif' 
                                   and i_store = '$istore' 
                                   and i_store_location = '$istorelocation' 
                                   and i_store_locationbin = '$istorelocationbin'
                                 ",false);
    }

    function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,$vunitprice,$isj,$dsj,$iarea,$eremark,$i){
      $th=substr($dsj,0,4);
      $bl=substr($dsj,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_sjpbr'		        => $isj,
				'i_area'  	            => $iarea,
				'd_sjpbr'		        => $dsj,
				'i_product'       		=> $iproduct,
				'i_product_motif'   	=> $iproductmotif,
				'i_product_grade'   	=> $iproductgrade,
				'e_product_name'    	=> $eproductname,
				'n_quantity_retur'  	=> $nretur,
				'n_quantity_receive'	=> $nreceive,
				'v_unit_price'		    => $vunitprice,
                'e_remark'              => $eremark,
                'e_mutasi_periode'      => $pr,
                'n_item_no'             => $i
    		)
    	);
    	
    	$this->db->insert('tm_sjpbr_item');
    }

    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query=$this->db->query(" 
                                    select 
                                        n_quantity_awal, 
                                        n_quantity_akhir, 
                                        n_quantity_in, 
                                        n_quantity_out 
                                    from 
                                        tm_ic_trans
                                    where 
                                        i_product='$iproduct' 
                                        and i_product_grade='$iproductgrade' 
                                        and i_product_motif='$iproductmotif'
                                        and i_store='$istore' 
                                        and i_store_location='$istorelocation' 
                                        and i_store_locationbin='$istorelocationbin'
                                    order by 
                                        d_transaction desc"
                                ,false);
        if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function qic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
        $query=$this->db->query(" 
                                  select 
                                    n_quantity_stock
                                  from 
                                    tm_ic_consigment
                                  where 
                                    i_product='$iproduct' 
                                    and i_product_grade='$iproductgrade' 
                                    and i_product_motif='$iproductmotif'
                                    and i_customer='$icustomer'
                                ",false);
        if ($query->num_rows() > 0){
		    return $query->result();
		}
    }

    function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak,$trans){
        $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	= $row->c;
        if($trans==''){
            $query=$this->db->query(" 
                                    INSERT INTO tm_ic_trans
                                    (
                                      i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                      i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                      n_quantity_in, n_quantity_out,
                                      n_quantity_akhir, n_quantity_awal)
                                    VALUES 
                                    (
                                      '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                      '$eproductname', '$isj', '$now', $q_in+$qsj, $q_out, $q_ak+$qsj, $q_aw
                                    )
                                  ",false);
        }else{
            $query=$this->db->query(" 
                                    INSERT INTO tm_ic_trans
                                    (
                                      i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                      i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                      n_quantity_in, n_quantity_out,
                                      n_quantity_akhir, n_quantity_awal, i_trans)
                                    VALUES 
                                    (
                                      '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                      '$eproductname', '$isj', '$now', $q_in+$qsj, $q_out, $q_ak+$qsj, $q_aw, $trans
                                    )
                                  ",false);
        }
    }

    function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode){
        $hasil='kosong';
        $query=$this->db->query(" 
                                select 
                                    i_product
                                from 
                                    tm_mutasi_consigment
                                where 
                                    i_product='$iproduct' 
                                    and i_product_grade='$iproductgrade' 
                                    and i_product_motif='$iproductmotif'
                                    and i_customer='$icustomer' 
                                    and e_mutasi_periode='$emutasiperiode'
                                ",false);
        if ($query->num_rows() > 0){
	    	$hasil='ada';
	    }
        return $hasil;
    }

    function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode){
        if( ($qsj=='')||($qsj==null) ){
            $qsj=0;
        } 
        $query=$this->db->query(" 
                                update
                                   tm_mutasi_consigment 
                                set
                                   n_mutasi_kepusat = n_mutasi_kepusat +$qsj, n_mutasi_git = n_mutasi_git +$qsj , n_saldo_akhir = n_saldo_akhir -$qsj 
                                where
                                   i_product = '$iproduct' 
                                   and i_product_grade = '$iproductgrade' 
                                   and i_product_motif = '$iproductmotif' 
                                   and i_customer = '$icustomer' 
                                   and e_mutasi_periode = '$emutasiperiode'
                                ",false);
    }

    function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode,$emutasiperiodesj,$iarea){
        if( ($qsj=='')||($qsj==null) ){
            $qsj=0;
        } 
        $query=$this->db->query(" 
                                insert into tm_mutasi_consigment
                                (
                                  i_product,i_product_motif,i_product_grade,i_customer,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_daripusat,n_mutasi_darilang,n_mutasi_penjualan,n_mutasi_kepusat,
                                  n_saldo_akhir,n_saldo_stockopname,f_mutasi_close, n_mutasi_git)
                                values
                                (
                                '$iproduct','$iproductmotif','$iproductgrade','$icustomer','$emutasiperiode',0,0,0,0,$qsj,$qsj,0,'f',$qsj)
                              ",false);
    }

    function cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
        $ada=false;
        $query=$this->db->query(" 
                                select 
                                    i_product
                                from 
                                    tm_ic_consigment
                                where 
                                    i_product='$iproduct' 
                                    and i_product_grade='$iproductgrade' 
                                    and i_product_motif='$iproductmotif'
                                    and i_customer='$icustomer'"
                                ,false);
        if ($query->num_rows() > 0){
	    			$ada=true;
	    		}
        return $ada;
    }

    function updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$q_ak){
        if( ($q_ak=='')||($q_ak==null) ){
            $q_ak=0;
        } 
        if( ($qsj=='')||($qsj==null) ){
            $qsj=0;
        } 
        $query=$this->db->query("
                                update
                                   tm_ic_consigment 
                                set
                                   n_quantity_stock =$q_ak -$qsj 
                                where
                                   i_product            = '$iproduct' 
                                   and i_product_grade  = '$iproductgrade' 
                                   and i_product_motif  = '$iproductmotif' 
                                   and i_customer       = '$icustomer'
                                 ",false);
    }  

    function insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$qsj){
        if( ($qsj=='')||($qsj==null) ){
            $qsj=0;
        }
        $query=$this->db->query(" 
                                insert into tm_ic_consigment
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$icustomer', '$eproductname', 0-$qsj, 't'
                                )
                              ",false);
    }

    function updatemutasi04($icustomer,$iproduct,$iproductgrade,$iproductmotif,$nasal,$emutasiperiode){
        if( ($nasal=='')||($nasal==null) ){
            $nasal=0;
        } 
        $query=$this->db->query(" 
                                update
                                   tm_mutasi_consigment 
                                set
                                   n_mutasi_kepusat = n_mutasi_kepusat -$nasal, n_saldo_akhir = n_saldo_akhir +$nasal, n_mutasi_git = n_mutasi_git -$nasal 
                                where
                                   i_product = '$iproduct' 
                                   and i_product_grade = '$iproductgrade' 
                                   and i_product_motif = '$iproductmotif' 
                                   and i_customer = '$icustomer' 
                                   and e_mutasi_periode = '$emutasiperiode'
                              ",false);
    }

    function updateic04($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nasal){
        if( ($nasal=='')||($nasal==null) ){
            $nasal=0;
        } 
        $query=$this->db->query(" 
                                update
                                   tm_ic_consigment 
                                set
                                   n_quantity_stock = n_quantity_stock +$nasal 
                                where
                                   i_product = '$iproduct' 
                                   and i_product_grade = '$iproductgrade' 
                                   and i_product_motif = '$iproductmotif' 
                                   and i_customer = '$icustomer'
                              ",false);
    }

    function insertsjpbdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isjpb,$iarea,$i,$dsjpb,$nreceive){
    	$this->db->set(
    		array(
				'i_sjpb'			=> $isjpb,
				'i_area'	        => $iarea,
				'i_product'			=> $iproduct,
				'i_product_motif'	=> $iproductmotif,
				'i_product_grade'	=> $iproductgrade,
				'n_deliver'         => $ndeliver,
				'n_receive'         => $nreceive,
				'v_unit_price'		=> $vunitprice,
				'e_product_name'	=> $eproductname,
                'd_sjpb'            => $dsjpb,
				'n_item_no'         => $i
            )
        );
    	$this->db->insert('tm_sjpb_item');
    }
}

/* End of file Mmaster.php */
