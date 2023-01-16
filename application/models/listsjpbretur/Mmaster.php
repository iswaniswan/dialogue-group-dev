<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($cari){
        $cari      = str_replace("'", "", $cari);
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND (UPPER(i_area) LIKE '%$cari%'
                OR UPPER(e_area_name) LIKE '%$cari%')
        ", FALSE);
    }

    public function bacaareasj($iarea){
        $query = $this->db->query("
                                select
                                   e_area_name 
                                from
                                   tr_area 
                                where
                                   i_area = '$iarea'

                                ", FALSE);
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $areasj = $kuy->e_area_name; 
        }else{
          $areasj = '';
        }
        return $areasj;
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                               a.i_sjpbr,
                               a.d_sjpbr,
                               b.e_customer_name,
                               a.d_sjpbr_receive as terima,
                               a.d_sjpbr_receive,
                               a.f_sjpbr_cancel as status,
                               a.i_area,
                               '$dfrom' as dfrom,
                               '$dto' as dto,
                               '$iarea' as iarea,
                               '$folder' as folder,
                               '$i_menu' as i_menu
                            from
                               tm_sjpbr a,
                               tr_customer b 
                            where
                               a.i_area = '$iarea' 
                               and a.i_customer = b.i_customer 
                               and a.d_sjpbr >= to_date('$dfrom', 'dd-mm-yyyy') 
                               and a.d_sjpbr <= to_date('$dto', 'dd-mm-yyyy') 
                            order by
                               a.i_sjpbr
                            
                            ");

        $datatables->add('action', function ($data) {
            $id             = trim($data['i_sjpbr']);
            $status         = $data['status'];
            $i_area         = $data['i_area'];
            $terima         = $data['terima'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$i_area/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $status == 'f' && $terima == ''){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('status', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->edit('terima', function ($data) {
            if ($data['terima']=='') {
                $data = '<span class="label label-success label-rouded">Belum</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Sudah</span>';
            }
            return $data;
        });


        $datatables->hide('i_area');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('iarea');
        return $datatables->generate();
    }

    public function cancel($id, $iarea){
        $this->db->query("
                        update 
                            tm_sjpbr 
                        set 
                            f_sjpbr_cancel='t' 
                        where 
                            i_sjpbr='$id' 
                            and i_area='$iarea'
                        ");
    }

    public function baca($id,$iarea){
        $query = $this->db->query("
                                    select
                                       a.*,
                                       b.e_customer_name,
                                       c.e_area_name,
                                       d.e_spg_name 
                                    from
                                       tm_sjpbr a,
                                       tr_customer b,
                                       tr_area c,
                                       tr_spg d 
                                    where
                                       a.i_customer = b.i_customer 
                                       and a.i_area = c.i_area 
                                       and a.i_spg = d.i_spg 
                                       and a.i_sjpbr = '$id' 
                                       and a.i_area = '$iarea'
                                    ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($id,$iarea){
        $query = $this->db->query("
                                    select
                                       a.i_sjpbr,
                                       a.d_sjpbr,
                                       a.i_area,
                                       a.i_product,
                                       a.i_product_grade,
                                       a.i_product_motif,
                                       a.n_quantity_retur,
                                       a.n_quantity_receive,
                                       a.v_unit_price,
                                       a.e_product_name,
                                       b.e_product_motifname,
                                       a.e_remark 
                                    from
                                       tm_sjpbr_item a,
                                       tr_product_motif b 
                                    where
                                       a.i_sjpbr = '$id' 
                                       and a.i_area = '$iarea' 
                                       and a.i_product = b.i_product 
                                       and a.i_product_motif = b.i_product_motif 
                                    order by
                                       a.n_item_no
                                    ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
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
