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
                              ",FALSE)->result();
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

    public function data($dfrom,$dto,$departemen,$folder){
      $username = $this->session->userdata('username');
      $datatables = new Datatables(new CodeigniterAdapter);
      $datatables->query("
                          select
                             a.i_bbk,
                             a.d_bbk,
                             a.i_supplier,
                             a.f_bbk_cancel,
                             b.e_customer_name,
                             '$dfrom' as dfrom,
                             '$dto' as dto,
                             '$folder' as folder,
                             '$departemen' as departemen
                          from
                             tm_bbk a,
                             tr_customer b 
                          where
                             a.i_supplier = b.i_customer 
                             and a.i_bbk_type = '03' 
                             and a.d_bbk >= to_date('$dfrom', 'dd-mm-yyyy') 
                             and a.d_bbk <= to_date('$dto', 'dd-mm-yyyy') 
                          order by
                             a.i_bbk"
                          );
      
      $datatables->add('action', function ($data) {
        $ibbk          = trim($data['i_bbk']);
        $dbbk          = trim($data['d_bbk']);
        $fbbkcancel    = trim($data['f_bbk_cancel']);
        $dfrom         = trim($data['dfrom']);
        $dto           = trim($data['dto']);
        $folder        = trim($data['folder']);
        $departemen    = trim($data['departemen']);
        $data          = '';
        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ibbk/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
        if(($fbbkcancel != 't' && ($departemen == '7' || $departemen == '1'))){
          $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ibbk\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
        }
			  return $data;
      });

      $datatables->edit('d_bbk', function ($data) {
          $d_bbk = $data['d_bbk'];
          if($d_bbk == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_bbk) );
          }
      });

      $datatables->edit('i_supplier', function ($data) {
        $i_supplier = $data['i_supplier'];
        $e_customer_name = $data['e_customer_name'];
        return '('.$i_supplier.')'.' - '.$e_customer_name;
      });

      $datatables->edit('i_bbk', function ($data) {
        $i_bbk = "<h2><b>".$data['i_bbk']."</b></h2>";
        $fbbkcancel = $data['f_bbk_cancel'];
        if($fbbkcancel == 't'){
            return $i_bbk;
        }else{
            return $data['i_bbk'];
        }
      });

      $datatables->hide('folder');
      $datatables->hide('e_customer_name');
      $datatables->hide('f_bbk_cancel');
      $datatables->hide('departemen');
      $datatables->hide('dto');
      $datatables->hide('dfrom');
      return $datatables->generate();  
    }

    public function jumlah($ibbk){
      return $this->db->query("
                              select
                               i_product
                              from
                                tm_bbk_item
                              where 
                                i_bbk='$ibbk'
                                and i_bbk_type='03'
                              ",false);
    }

    public function baca($ibbk,$departemen){
      return $this->db->query("
                              select
                                 a.*,
                                 b.e_customer_name,
                                 b.i_customer,
                                 '$departemen' as departemen
                              from
                                 tm_bbk a,
                                 tr_customer b 
                              where
                                 a.i_supplier = b.i_customer 
                                 and i_bbk = '$ibbk' 
                                 and a.i_bbk_type = '03'"
                            ,false);
    }

    public function bacadetail($ibbk){
      return $this->db->query("
                              select
                                 a.*,
                                 b.e_product_motifname 
                              from
                                 tm_bbk_item a,
                                 tr_product_motif b 
                              where
                                 a.i_bbk = '$ibbk' 
                                 and i_bbk_type = '03' 
                                 and a.i_product = b.i_product 
                                 and a.i_product_motif = b.i_product_motif 
                              order by
                                 a.n_item_no"
                              ,false);
    }

    public function getproduct($cari){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
                              SELECT
                                  a.i_product AS kode,
                                  c.e_product_name AS nama
                              FROM
                                  tr_product_motif a,
                                  tr_product c
                              WHERE
                                  a.i_product = c.i_product
                                  AND (UPPER(a.i_product) LIKE '%$cari%'
                                  OR UPPER(c.e_product_name) LIKE '%$cari%')
                              ORDER BY
                                  c.i_product,
                                  a.e_product_motifname",
                              FALSE);
    } 

    public function getdetailproduct($iproduct){
      return $this->db->query("
                              SELECT
                                  a.i_product AS kode,
                                  a.i_product_motif AS motif,
                                  a.e_product_motifname AS namamotif,
                                  c.e_product_name AS nama,
                                  c.v_product_mill AS harga
                              FROM
                                  tr_product_motif a,
                                  tr_product c
                              WHERE
                                  a.i_product = c.i_product
                                  AND a.i_product = '$iproduct'
                              ORDER BY
                                  c.i_product,
                                  a.e_product_motifname",
                              FALSE);
    } 


    public function delete($ibbk){
      $this->db->query("
                        update 
                          tm_bbk 
                        set 
                          f_bbk_cancel='t' 
                        where 
                          i_bbk='$ibbk' 
                          and i_bbk_type='03'
                        ");
	    $ibbk=trim($ibbk);
      $query = $this->db->query("
                                select 
                                  b.e_product_name, 
                                  a.d_bbk, 
                                  b.n_quantity, 
                                  b.i_product, 
                                  b.i_product_grade, 
                                  b.i_product_motif 
                                from 
                                  tm_bbk a, 
                                  tm_bbk_item b 
                                where 
                                  a.i_bbk=b.i_bbk 
                                  and a.i_bbk='$ibbk'
                                ");
	    foreach($query->result() as $row){
	  	  $jml                = $row->n_quantity;
	  	  $product            = $row->i_product;
	  	  $grade              = $row->i_product_grade;
	  	  $motif              = $row->i_product_motif;
	  	  $eproductname       = $row->e_product_name;
        $dbbk               = $row->d_bbk;
        $istore				      = 'AA';
	  		$istorelocation		  = '01';
	  		$istorelocationbin  = '00';
        $th=substr($dbbk,0,4);
	  	  $bl=substr($dbbk,5,2);
	  	  $emutasiperiode=$th.$bl;
	  	  $queri 		= $this->db->query("
                                    SELECT 
                                      n_quantity_akhir, 
                                      i_trans 
                                    FROM 
                                      tm_ic_trans 
                                    WHERE 
                                      i_product='$product' 
                                      and i_product_grade='$grade' 
                                      and i_product_motif='$motif'
                                      and i_store='$istore' 
                                      and i_store_location='$istorelocation'
                                      and i_store_locationbin='$istorelocationbin' 
                                      and i_refference_document='$ibbk'
                                    ORDER BY 
                                      d_transaction desc, 
                                      i_trans desc",
                                    false);
        if ($queri->num_rows() > 0){
      	  $row  = $queri->row();
          $que 	= $this->db->query("SELECT current_timestamp as c");
	        $ro 	= $que->row();
	        $now	= $ro->c;
          $this->db->query(" 
                              INSERT INTO tm_ic_trans
                              (
                                i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                n_quantity_in, n_quantity_out,
                                n_quantity_akhir, n_quantity_awal)
                              VALUES 
                              (
                                '$product','$grade','$motif','$istore','$istorelocation','$istorelocationbin', 
                                '$eproductname', '$ibbk', '$now', $jml, 0, $row->n_quantity_akhir+$jml, $row->n_quantity_akhir
                              )
                           ",false);
        }

        if( ($jml!='') && ($jml!=0) ){
          $this->db->query(" 
                            UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$jml, n_saldo_akhir=n_saldo_akhir+$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                            and e_mutasi_periode='$emutasiperiode'
                           ",false);
          $this->db->query(" 
                            UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                           ",false);
        }
      }
    }

    function updateheader($ibbk, $dbbk, $icustomer, $eremark){
    	$this->db->set(
    		        array(
			              'd_bbk'	    => $dbbk,
			              'i_supplier'=> $icustomer,
                    'e_remark'  => $eremark
    	              ));
    	$this->db->where('i_bbk',$ibbk);
    	$this->db->where('i_bbk_type','03');
    	$this->db->update('tm_bbk');
    }
    function insertheader($ibbk, $ibbktype, $dbbk, $icustomer, $ibbkold, $eremark){
    	$this->db->set(
    		        array(
			            'i_bbk'		              => $ibbk,
                  'i_bbk_type'            => $ibbktype,
			            'd_bbk'		              => $dbbk,
			            'i_area'    	          => '00',#substr($icustomer,0,2),
			            'i_supplier' 	          => $icustomer,
                  'e_remark'              => $eremark,
                  'i_refference_document' => 'HADIAH'
    		        ));
    	$this->db->insert('tm_bbk');
    }

    function insertdetail($ibbk,$ibbktype,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$vunitprice,$eremark,$i,$thbl){
    	$this->db->set(
    		        array(
				        	'i_bbk'	   	            => $ibbk,
                  'i_bbk_type'            => $ibbktype,
				        	'i_product'	 	          => $iproduct,
				        	'i_product_grade'	      => $iproductgrade,
				        	'i_product_motif'	      => $iproductmotif,
				        	'n_quantity'		        => $nquantity,
				        	'v_unit_price'		      => $vunitprice,
				        	'e_product_name'	      => $eproductname,
				        	'e_remark'		          => $eremark,
                  'i_refference_document' => 'HADIAH',
                  'e_mutasi_periode'      => $thbl,
                  'n_item_no'             => $i
    		        ));
    	$this->db->insert('tm_bbk_item');
    }

    public function deletedetail($iproduct, $iproductgrade, $ibbk, $iproductmotif, $nquantityx, $istore, $istorelocation, $istorelocationbin){
		  $this->db->query("DELETE FROM tm_bbk_item WHERE i_bbk='$ibbk' and i_product='$iproduct' and i_product_grade='$iproductgrade' 
			and i_product_motif='$iproductmotif' and i_bbk_type='03'");
    }

    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                order by i_trans desc",false);
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }

    function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }

    function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbbk,$q_aw,$q_ak){
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
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$ibbk', '$now', 0, $qbbk, $q_ak-$qbbk, $q_ak
                                )
                              ",false);
    }

    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
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

    function updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_bbk=n_mutasi_bbk+$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }

    function insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,0,0,$qbbk,$qbbk,0,'f')
                              ",false);
    }

    function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
        $ada=true;
      }
      return $ada;
    }

    function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$q_ak){
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }

    function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbk){
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0, 't'
                                )
                              ",false);
    }

    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ibbk,$ntmp,$eproductname){
      $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin'
                                    order by i_trans desc",false);
      if ($queri->num_rows() > 0){
        $row   		= $queri->row();
        $que 	= $this->db->query("SELECT current_timestamp as c");
        $ro 	= $que->row();
        $now	 = $ro->c;
        if($ntmp!=0 || $ntmp!=''){
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
                                    '$eproductname', '$ibbk', '$now', $ntmp, 0, $row->n_quantity_akhir+$ntmp, $row->n_quantity_akhir
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

    function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$qbbk, n_saldo_akhir=n_saldo_akhir+$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }

    function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk){
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
} 

    /* End of file Mmaster.php */
