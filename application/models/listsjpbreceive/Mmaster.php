<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username,$idcompany){
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
        ", FALSE)->result();
    }

    public function data($dfrom,$dto,$iarea,$i_menu){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        if($iarea == '00'){
            $datatables->query("
                            SELECT
                                a.i_sjpb,
                                a.d_sjpb,
                                a.i_customer, 
                                b.e_customer_name,
                                a.d_sjpb_receive as terima,
                                a.d_sjpb_receive,
                                a.f_sjpb_cancel,
                                '$i_menu' as i_menu,
                                '$dfrom' as dfrom,
                                '$dto' as dto
                                '$iarea' as iarea
                            FROM 
                                tm_sjpb a, 
                                tr_customer b
                            WHERE 
                                a.f_sjpb_cancel='f' 
                                AND a.i_customer=b.i_customer 
                                AND NOT a.d_sjpb_receive is null
							    AND a.d_sjpb >= to_date('$dfrom','dd-mm-yyyy')
							    AND a.d_sjpb <= to_date('$dto','dd-mm-yyyy')
                            ORDER BY 
                                a.i_sjpb"
                            );
        }else{
            $datatables->query("
                            SELECT
                                a.i_sjpb,
                                a.d_sjpb,
                                a.i_customer, 
                                b.e_customer_name,
                                a.d_sjpb_receive as terima,
                                a.d_sjpb_receive,
                                a.f_sjpb_cancel,
                                '$i_menu' as i_menu,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$iarea' as iarea
                            FROM 
                                tm_sjpb a, 
                                tr_customer b
                            WHERE 
                                a.f_sjpb_cancel='f' 
                                AND a.i_customer=b.i_customer 
                                AND NOT a.d_sjpb_receive is null
                                AND a.d_sjpb >= to_date('$dfrom','dd-mm-yyyy')
                                AND a.d_sjpb <= to_date('$dto','dd-mm-yyyy')
                                AND a.i_area='$iarea'
                            ORDER BY 
                                a.i_sjpb"
                            );
        }
        
        $datatables->add('action', function ($data) {
            $i_sjpb           = trim($data['i_sjpb']);
            $i_customer       = trim($data['i_customer']);
            $f_sjpb_cancel    = trim($data['f_sjpb_cancel']);
            $i_menu           = $data['i_menu'];
            $dfrom            = $data['dfrom'];
            $dto              = $data['dto'];
            $iarea            = $data['iarea'];
            $data             = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"listsjpbreceive/cform/edit/$i_sjpb/$iarea/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }

            if($f_sjpb_cancel == 'f'){
                if(check_role($i_menu, 4)){
                    $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sjpb\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>";
                }
            }

			return $data;
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
            $d_sjpb_terima = $data['d_sjpb_receive'];
            if($d_sjpb_terima == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjpb_terima) );
            }
        });

        $datatables->edit('terima', function ($data) {
            $terima = $data['terima'];
            if($terima == ''){
                return 'Belum';
            }else{
                return 'Sudah';
            }
        });

        $datatables->edit('i_sjpb', function ($data) {
            $i_sjpb         = $data['i_sjpb'];
            $f_sjpb_cancel  = $data['f_sjpb_cancel'];
            if($f_sjpb_cancel == 't'){
            }else{
                return $i_sjpb;
            }
        });

        $datatables->edit('i_customer', function ($data) {
            $i_customer      = $data['i_customer'];
            $e_customer_name = $data['e_customer_name'];
            if($i_customer == ''){
                return '';
            }else{
                return "(".$i_customer.")"." - ".$e_customer_name;
            }
        });

        $datatables->hide('i_menu');
        $datatables->hide('iarea');
        $datatables->hide('e_customer_name');
        $datatables->hide('f_sjpb_cancel');
        $datatables->hide('dfrom');
        $datatables->hide('dto');

        return $datatables->generate();  
    }

    function baca($isjpb,$iarea){
        $query = $this->db->query(" 
                                SELECT 
                                    a.*, 
                                    b.e_customer_name, 
                                    c.e_area_name
                                FROM 
                                    tm_sjpb a, 
                                    tr_customer b, 
                                    tr_area c
                                WHERE 
                                    a.i_customer=b.i_customer 
                                    AND a.f_sjpb_cancel='f' 
                                    AND a.i_area=c.i_area
                                    AND a.i_sjpb ='$isjpb' 
                                    AND a.i_area='$iarea'"
                                , false);
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacadetail($isjpb,$iarea){
        $query = $this->db->query(" 
                                SELECT 
                                    a.i_sjpb,
                                    a.d_sjpb,
                                    a.i_area,
                                    a.i_product,
                                    a.i_product_grade,
                                    a.i_product_motif,
                                    a.n_deliver,
                                    a.n_receive,
                                    a.v_unit_price,
                                    a.e_product_name,
                                    b.e_product_motifname 
                                FROM 
                                    tm_sjpb_item a, 
                                    tr_product_motif b
                                WHERE 
                                    a.i_sjpb = '$isjpb' 
                                    AND a.i_area='$iarea' 
                                    AND a.i_product=b.i_product 
                                    AND a.i_product_motif=b.i_product_motif
                                ORDER BY 
                                    a.n_item_no"
                                , false);
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function updatesjheader($isjpb,$iarea,$dsjreceive,$vsjnetto,$vsjrec){
        $query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjupdate= $row->c;
    	$this->db->set(
    		            array(
			            	'v_sjpb_receive' => $vsjrec,
			            	'd_sjpb_receive' => $dsjreceive,
                            'd_sjpb_update'  => $dsjupdate
                        
    		            ));
    	$this->db->where('i_sjpb',$isjpb);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjpb');
    }

    public function deletesjdetail( $isjp, $isjpb, $iarea, $iproduct, $iproductgrade, $iproductmotif, $ndeliver) {
        $cek=$this->db->query("select * from tm_sjpb_item WHERE i_sjpb='$isjpb' 
                                and i_area='$iarea'
                                and i_product='$iproduct' 
                                and i_product_grade='$iproductgrade' 
							    and i_product_motif='$iproductmotif'");
        if($cek->num_rows()>0){
		    $this->db->query("DELETE FROM tm_sjpb_item WHERE i_sjpb='$isjpb'
                            and i_area='$iarea'
							and i_product='$iproduct' 
                            and i_product_grade='$iproductgrade' 
							and i_product_motif='$iproductmotif'");
        }
    }

    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj){
      $queri 		= $this->db->query("SELECT i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' and i_refference_document='$isj'");
		  $row   		= $queri->row();
      $query=$this->db->query(" 
                                DELETE FROM tm_ic_trans 
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation'
                                and i_store_locationbin='$istorelocationbin' and i_refference_document='$isj'
                              ",false);
      if($row->i_trans!=''){
        return $row->i_trans;
      }else{
        return 1;
      }
    }

    public function delete($isj, $iarea) {
			$this->db->query("update tm_sjp set f_sjp_cancel='t' WHERE i_sjp='$isj' and i_area='$iarea'");
    }
    
    function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbm=n_mutasi_bbm-$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$ndeliver,
			                      $vunitprice,$isj,$dsj,$iarea, $istore,$istorelocation,$istorelocationbin,$eremark,$i)
    {
      $th=substr($dsj,0,4);
      $bl=substr($dsj,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_sjp'			        => $isj,
				'd_sjp'			        => $dsj,
				'i_area'		        => $iarea,
				'i_product'       		=> $iproduct,
				'i_product_motif'   	=> $iproductmotif,
				'i_product_grade'   	=> $iproductgrade,
				'e_product_name'    	=> $eproductname,
				'n_quantity_deliver' 	=> $ndeliver,
				'n_quantity_receive'	=> $nreceive,
				'v_unit_price'		    => $vunitprice,
				'i_store'         		=> $istore,
				'i_store_location'	    => $istorelocation,
				'i_store_locationbin'	=> $istorelocationbin, 
                'e_remark'              => $eremark,
                'e_mutasi_periode'      => $pr,
                'n_item_no'             => $i
    		)
    	);
    	
    	$this->db->insert('tm_sjp_item');
    }
    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                order by d_transaction desc",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function qic($iproduct,$iproductgrade,$iproductmotif,$icustomer)
    {
      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak,$trans)
    {
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
    function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode)
    {
      $hasil='kosong';
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$hasil='ada';
			}
      return $hasil;
    }
    function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode,$emutasiperiodesj,$iarea)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_mutasi_consigment
                                set n_mutasi_daripusat=n_mutasi_daripusat+$qsj, n_saldo_akhir=n_saldo_akhir+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if($emutasiperiodesj==$emutasiperiode){
        $query=$this->db->query(" 
                                  UPDATE tm_mutasi
                                  set n_mutasi_git=n_mutasi_git-$qsj
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$iarea' and i_store_location='PB' and i_store_locationbin='00'
                                  and e_mutasi_periode='$emutasiperiodesj'
                                ",false);
      }
    }
    function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode,$emutasiperiodesj,$iarea)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                insert into tm_mutasi_consigment
                                (
                                  i_product,i_product_motif,i_product_grade,i_customer,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_daripusat,n_mutasi_darilang,n_mutasi_penjualan,n_mutasi_kepusat,
                                  n_saldo_akhir,n_saldo_stockopname,f_mutasi_close, n_mutasi_git)
                                values
                                (
 '$iproduct','$iproductmotif','$iproductgrade','$icustomer','$emutasiperiode',0,$qsj,0,0,0,$qsj,0,'f',0)
                              ",false);
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$q_ak)
    {
      if( ($q_ak=='')||($q_ak==null) ) $q_ak=0;
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_ic_consigment set n_quantity_stock=$q_ak+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'",false);
    }
    function insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$qsj)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                insert into tm_ic_consigment
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$icustomer', '$eproductname', $qsj, 't'
                                )
                              ",false);
    }
    function bacaproduct($num,$offset,$cari)
    {
			$this->db->select("	a.i_product as kode, a.e_product_name as nama, b.v_product_retail as harga, 
                          c.i_product_motif as motif, c.e_product_motifname as namamotif
                          from tr_product a, tr_product_price b, tr_product_motif c
                          where a.i_product=b.i_product and b.i_price_group='00'
                          and (upper(a.i_product) like '%$cari%' or upper(a.e_product_name) like '%$cari%')
                          and a.i_product=c.i_product ORDER BY a.e_product_name",false)->limit($num,$offset);
                          #and a.i_product_status<>'4'
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function updatemutasi04($icustomer,$iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_mutasi_consigment set n_mutasi_daripusat=n_mutasi_daripusat-$qsj, 
                                n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if($emutasiperiodesj==$emutasiperiode){
        $query=$this->db->query(" 
                                  UPDATE tm_mutasi set n_mutasi_git=n_mutasi_git+$qsj
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin' 
                                  and e_mutasi_periode='$emutasiperiodesj'
                                ",false);
      }
    }
    function updateic04($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_ic_consigment set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'
                              ",false);
    }
    function insertsjpbdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,
                  			    $vunitprice,$isjpb,$iarea,$i,$dsjpb,$nreceive)
    {
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
