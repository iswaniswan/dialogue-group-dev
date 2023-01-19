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

    public function bacaareauser($username,$idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iarea = $kuy->i_area; 
        }else{
            $iarea = '';
        }
        return $iarea;
    }

    public function cekdepartemen($username,$idcompany){
        $this->db->select('i_departement');
        $this->db->from('public.tm_user_deprole');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $idepartemen = $kuy->i_departement; 
        }else{
            $idepartemen = '';
        }
        return $idepartemen;
    }

    public function cekperiode(){
      $query = $this->db->query("select i_periode from tm_periode");
      if($query->num_rows() > 0){
        $periode = $query->row();
        $iperiode = $periode->i_periode;
      }else{
        $iperiode='';
      }
      return $iperiode;
    }

    public function data($dfrom,$dto,$iarea,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        select
                          a.i_sjbr,
                          a.d_sjbr, 
                          a.i_area,
                          b.e_area_name,
                          a.d_sjbr_receive as terima,
                          a.f_sjbr_cancel,
                          '$dfrom' as dfrom,
                          '$dto' as dto,
                          '$iarea' as iarea,
                          '$folder' as folder
                        from 
                          tm_sjbr a, 
                          tr_area b
                        where 
                          a.i_area=b.i_area
                          and a.i_area='PB' 
                          and a.d_sjbr >= to_date('$dfrom','dd-mm-yyyy')
                          and a.d_sjbr <= to_date('$dto','dd-mm-yyyy')
                        order by 
                          a.i_sjbr desc"
                        );
        
        $datatables->add('action', function ($data) {
            $isjbr          = trim($data['i_sjbr']);
            $dsjbr          = trim($data['d_sjbr']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $fsjbrcancel    = trim($data['f_sjbr_cancel']);
            $iarea          = trim($data['i_area']);
            $folder         = trim($data['folder']);
            $data           = '';
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjbr/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if($fsjbrcancel == 'f'){
                    $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isjbr\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
            }

			return $data;
        });

        $datatables->edit('d_sjbr', function ($data) {
            $d_sjbr = $data['d_sjbr'];
            if($d_sjbr == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjbr) );
            }
        });

        $datatables->edit('i_sjbr', function ($data) {
          $i_sjbr = "<h2><b>".$data['i_sjbr']."</b></h2>";
          $fsjbrcancel = $data['f_sjbr_cancel'];
          if($fsjbrcancel == 't'){
              return $i_sjbr;
          }else{
              return $data['i_sjbr'];
          }
        });

        $datatables->edit('terima', function ($data) {
          if ($data['terima']!=null) {
              $data = '<span class="label label-info label-rouded">Sudah</span>';
          }else{
              $data = '<span class="label label-danger label-rouded">Belum</span>';
          }
          return $data;
      });

        $datatables->hide('folder');
        $datatables->hide('f_sjbr_cancel');
        $datatables->hide('i_area');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();  
    }

    function baca($isjbr,$iarea){
        $query = $this->db->query(" 
                              select
                                a.*, 
                                b.e_area_name, 
                                b.i_store 
                              from 
                                tm_sjbr a, 
                                tr_area b 
                              where 
                                a.i_area=b.i_area
                                and a.i_sjbr ='$isjbr' 
                                and a.i_area='$iarea'"
                                , false);
		  if ($query->num_rows() > 0){
		  	return $query->row();
		  }
    }

    function bacadetail($isjbr,$iarea){
        $query = $this->db->query(" 
                                select
                                  a.i_sjbr,
                                  a.d_sjbr,
                                  a.i_area,
                                  a.i_product,
                                  a.i_product_grade,
                                  a.i_product_motif,
                                  a.n_quantity_retur,
                                  a.n_quantity_receive,
                                  a.v_unit_price,
                                  a.e_product_name,
                                  a.i_store,
                                  a.i_store_location,
                                  a.i_store_locationbin,
                                  a.e_remark,
                                  b.e_product_motifname 
                                from 
                                  tm_sjbr_item a, 
                                  tr_product_motif b
                                where 
                                  a.i_sjbr = '$isjbr' 
                                  and a.i_area='$iarea'
                                  and a.i_product=b.i_product 
                                  and a.i_product_motif=b.i_product_motif
                                order by 
                                  a.n_item_no"
                                , false);
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    public function bacadetailspb($isjrt,$ittb,$iarea){
      return $this->db->query("
                            select
                              a.*, 
                              b.e_product_motifname 
                            from 
                              tm_ttbretur_item a, 
                              tr_product_motif b, 
                              tm_ttbretur c
                            where 
                              a.i_ttb = '$ittb' 
                              and a.i_area='$iarea' 
                              and a.i_product1 not in (select i_product from tm_sjrt_item 
                              where 
                                i_sjr='$isjrt' 
                                and i_area='$iarea')
                              and a.i_ttb=c.i_ttb 
                              and a.i_area=c.i_area
                              and a.i_product1=b.i_product 
                              and a.i_product1_motif=b.i_product_motif
                            order by 
                            a.n_item_no"
                            ,false);
    }

    public function product($cari) {
      $cari = str_replace("'", "", $cari);      
      return $this->db->query("
                            select 
                              a.i_product as kode, 
                              a.e_product_name as nama, 
                              b.v_product_retail as harga, 
                              c.i_product_motif as motif, 
                              c.e_product_motifname as namamotif
                            from 
                              tr_product a, 
                              tr_product_price b, 
                              tr_product_motif c, 
                              tm_ic d
                            where 
                              a.i_product=b.i_product 
                              and b.i_price_group='00' 
                              and a.i_product=d.i_product 
                              and c.i_product_motif=d.i_product_motif 
                              and d.i_store='PB' 
                              and d.i_store_location='00'
                              and (upper(a.i_product) like '%$cari%' 
                              or upper(a.e_product_name) like '%$cari%')
                              and a.i_product=c.i_product
                            order by 
                              a.e_product_name
                            ",false);
    }

    public function detailproduct($iproduct) {    
      return $this->db->query("
                            select 
                              a.i_product as kode, 
                              a.e_product_name as nama, 
                              b.v_product_retail as harga, 
                              c.i_product_motif as motif, 
                              c.e_product_motifname as namamotif
                            from 
                              tr_product a, 
                              tr_product_price b, 
                              tr_product_motif c, 
                              tm_ic d
                            where 
                              a.i_product=b.i_product 
                              and b.i_price_group='00' 
                              and a.i_product=d.i_product 
                              and c.i_product_motif=d.i_product_motif 
                              and d.i_store='PB' 
                              and d.i_store_location='00'
                              and a.i_product = '$iproduct'
                              and a.i_product=c.i_product
                            order by 
                              a.e_product_name
                            ",false);
    }

    public function cancel($isjbr, $iarea) {
			$this->db->query("update tm_sjbr set f_sjbr_cancel='t' WHERE i_sjbr='$isjbr' and i_area='$iarea'");
			$this->db->query("update tm_sjpbr set i_sjbr='' WHERE i_sjbr='$isjbr' and i_area='$iarea'");
      $this->db->select(" * from tm_sjbr where i_sjbr='$isjbr' and i_area='$iarea'");
			$qry = $this->db->get();
			if ($qry->num_rows() > 0){
        foreach($qry->result() as $qyr){  
          $dsjbr=$qyr->d_sjbr;
        }
      }
      $th=substr($dsjbr,0,4);
		  $bl=substr($dsjbr,5,2);
		  $emutasiperiode=$th.$bl;
      $que = $this->db->query("select i_store from tr_area where i_area='$iarea'");
      $st=$que->row();
      $istore=$st->i_store;
      if($istore=='AA'){
				$istorelocation		= '01';
			}else{
 				$istorelocation		= '00';
			}
			$istorelocationbin	= '00';
      $this->db->select(" * from tm_sjbr_item where i_sjbr='$isjbr' and i_area='$iarea' order by n_item_no");
			$qery = $this->db->get();
			if ($qery->num_rows() > 0){
        foreach($qery->result() as $qyre){  
          $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                        and i_product_motif='$qyre->i_product_motif'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin' and i_refference_document='$isjbr'
                                        order by d_transaction desc, i_trans desc",false);
          if ($queri->num_rows() > 0){
        	  $row   		= $queri->row();
            $que 	= $this->db->query("SELECT current_timestamp as c");
	          $ro 	= $que->row();
	          $now	 = $ro->c;
            $this->db->query(" 
                              INSERT INTO tm_ic_trans
                              (
                                i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                n_quantity_in, n_quantity_out,
                                n_quantity_akhir, n_quantity_awal)
                              VALUES 
                              (
                                '$qyre->i_product','$qyre->i_product_grade','$qyre->i_product_motif',
                                '$istore','$istorelocation','$istorelocationbin', 
                                '$qyre->e_product_name', '$isj', '$now', $qyre->n_quantity_retur, 0, 
                                $row->n_quantity_akhir+$qyre->n_quantity_retur, $row->n_quantity_akhir
                              )
                             ",false);

            $this->db->query(" 
                              UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$qyre->n_quantity_retur, 
                              n_mutasi_git=n_mutasi_git-$qyre->n_quantity_retur,
                              n_saldo_akhir=n_saldo_akhir+$qyre->n_quantity_retur
                              where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                              and i_product_motif='$qyre->i_product_motif'
                              and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                             ",false);
            $this->db->query(" 
                              UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qyre->n_quantity_retur
                              where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                              and i_product_motif='$qyre->i_product_motif' and i_store='$istore' and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                             ",false);

          }
        }
      }
    }

    function insertsjheader($isjbr,$dsjbr,$iarea,$vsjbr)
    {
		  $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjentry	= $row->c;
		  $this->db->set(
		    array(	  
          'i_sjbr'     		=> $isjbr,
	        'd_sjbr'     		=> $dsjbr,
	        'i_area'        => $iarea,
	        'v_sjbr'    		=> $vsjbr,
	        'd_sjbr_entry'	=> $dsjentry,
	        'f_sjbr_cancel'	=> 'f'
		    )
    	);
    	$this->db->insert('tm_sjbr');
    }

    
    /*function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	= $row->c;
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
                                  '$eproductname', '$isj', $now, $q_in+$qsj, $q_out, $q_ak+$qsj, $q_aw
                                )
                              ",false);
    }*/

    function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,
			                      $vunitprice,$isjbr,$dsjbr,$iarea,$istore,$istorelocation,$istorelocationbin,$i)
    {
      $th=substr($dsjbr,0,4);
      $bl=substr($dsjbr,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_sjbr'			        => $isjbr,
				'd_sjbr'			        => $dsjbr,
				'i_area'		          => $iarea,
				'i_product'       		=> $iproduct,
				'i_product_motif'   	=> $iproductmotif,
				'i_product_grade'   	=> $iproductgrade,
				'e_product_name'    	=> $eproductname,
				'n_quantity_retur'  	=> $nretur,
				'n_quantity_receive'	=> $nreceive,
				'v_unit_price'		    => $vunitprice,
				'i_store'         		=> $istore,
				'i_store_location'	  => $istorelocation,
				'i_store_locationbin'	=> $istorelocationbin, 
        'e_remark'            => '',
        'e_mutasi_periode'    => $pr,
        'n_item_no'           => $i
    		)
    	);
    	
    	$this->db->insert('tm_sjbr_item');
    }  
  
    function updatesjpbr($isjpbr,$dsjpbr,$isjbr,$iarea)
    {
	    $this->db->query("update tm_sjpbr set i_sjbr = '$isjbr'
                        where i_sjpbr='$isjpbr' and i_area='$iarea'",false);
    }

    function updatesjheader($isj,$iarea,$dsj,$vsjnetto)
    {
      $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjupdate= $row->c;
    	$this->db->set(
    		array(
				'v_sjbr'	      => $vsjnetto,
				'd_sjbr'       => $dsj,
        'd_sjbr_update'=> $dsjupdate

    		)
    	);
    	$this->db->where('i_sjbr',$isj);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjbr');
    }

    function searchsjheader($isjbr,$iarea)
    {
		return $this->db->query(" SELECT * FROM tm_sjbr WHERE i_sjbr='$isjbr' AND i_area='$iarea' ");
	  }    
    function deletesjdetail($isj, $iarea, $iproduct, $iproductgrade, $iproductmotif) 
    {
	    $this->db->query("DELETE FROM tm_sjbr_item WHERE i_sjbr='$isj'
                        and i_area='$iarea'
									      and i_product='$iproduct' and i_product_grade='$iproductgrade' 
									      and i_product_motif='$iproductmotif'");
    }

    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                order by i_trans desc",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isjbr,$q_in,$q_out,$qsj,$q_aw,$q_ak,$tra)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isjbr', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    }
    function inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isjbr,$q_in,$q_out,$qsj,$q_aw,$q_ak)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isjbr', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    }
    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_bbk=n_mutasi_bbk+$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$qaw)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close,n_git_penjualan)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',$qaw,0,0,0,$qsj,0,$qsj,$qaw-$qsj,0,'f',0)
                              ",false);
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj,$q_aw)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $q_aw-$qsj, 't'
                                )
                              ",false);
    }

    function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_bbm=n_mutasi_bbm+$qsj, n_saldo_akhir=n_saldo_akhir+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qsj,0,0,0,$qsj,0,'f')
                              ",false);
    }
    function updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qsj, 't'
                                )
                              ",false);
    }
    function deletetrans($iproduct,$iproductgrade,$istore,$istorelocation,$istorelocationbin,$isjbr,$nretur,$eproductname)
    {
      $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin'
                                    order by i_trans desc",false);
      if ($queri->num_rows() > 0){
#       return $query->result();
    	  $row   		= $queri->row();
        $que 	= $this->db->query("SELECT current_timestamp as c");
	      $ro 	= $que->row();
	      $now	 = $ro->c;
        if($nretur!=0 || $nretur!=''){
          $query=$this->db->query(" 
                                  INSERT INTO tm_ic_trans
                                  (
                                    i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                    i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                    n_quantity_in, n_quantity_out,
                                    n_quantity_akhir, n_quantity_awal)
                                  VALUES 
                                  (
                                    '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                    '$eproductname', '$isjbr', '$now', $nretur, 0, $row->n_quantity_akhir+$nretur, $row->n_quantity_akhir
                                  )
                                ",false);
        }
      }
      if(isset($row->i_trans)){
        if($row->i_trans!=''){
          return $row->i_trans;
        }else{
          return 1;
        }
      }else{
        return 1;
      }
    }
    function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_penjualan=n_mutasi_penjualan-$qsj, n_saldo_akhir=n_saldo_akhir+$qsj,
                                n_git_penjualan=n_git_penjualan-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbm=n_mutasi_bbm-$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
}

/* End of file Mmaster.php */
