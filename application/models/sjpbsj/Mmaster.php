<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function runningnumberspb($thbl){
        $th	= substr($thbl,0,4);
        $asal=$th;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
  
            $this->db->select("n_modul_no as max, i_group from tm_dgu_nopb
        where i_modul='SPB' and substr(e_group_name,1,3)='CLA'
        and substr(e_periode,1,4)='$asal'", false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                  $terakhir=$row->max;
            $grup=$row->i_group;
          }
                $nospb  =$terakhir+1;
          $this->db->query(" update tm_dgu_nopb 
                             set n_modul_no=$nospb
                             where i_modul='SPB' and i_group='$grup'
                             and substr(e_periode,1,4)='$asal' ", false);
                settype($nospb,"string");
                $a=strlen($nospb);
                while($a<4){
                  $nospb="0".$nospb;
                  $a=strlen($nospb);
                }
                $nospb  ="SPB-".$thbl."-".$grup.$nospb;
                $query->free_result();
                return $nospb;
            }
      }
  
      function data_sjpb_item($i_sjpb, $i_kode_harga){
        $query = $this->db->query("select a.*, b.v_product_retail, round(b.v_product_retail*0.75) as bersih  from tm_sjpb_item a
                                  left join tr_product_priceco b on(a.i_product = b.i_product and b.i_product_grade = b.i_product_grade and b.i_price_group = '$i_kode_harga')
                                  where a.i_sjpb = '$i_sjpb' 
                                  and a.n_receive <> 0 
                                  and a.n_deliver <> 0
                                  ");
        if ($query->num_rows() > 0){
        return $query->result();
        }
      }
  
      function update_sjpb($i_sjpb, $i_customer, $i_spb){
        $this->db->query("update tm_sjpb set i_spb = '$i_spb' where i_sjpb = '$i_sjpb' and i_customer = '$i_customer'");
      }
  
      function data_sjpb_header($i_sjpb){
        $this->db->select("a.i_sjpb, a.i_customer, b.e_customer_name, b.e_customer_address, a.d_sjpb_receive, a.i_spb");
        $this->db->from("tm_sjpb a");
        $this->db->join("tr_customer b","a.i_customer = b.i_customer");
        $this->db->where("b.e_customer_name like 'CLANDY%'");
        $this->db->where("not a.d_sjpb_receive isnull");
        $this->db->where("a.d_sjpb >= '2019-05-01'");
        $this->db->where("a.i_sjpb",$i_sjpb);
        return $this->db->get();
      }
  
     function carinilaikotor($i_sjpb, $i_kode_harga,$pilihan){
       if($pilihan == 'biasa'){
         $query = $this->db->query("
         select sum(x.nilai) as nilaikotor from(
         select a.*, b.v_product_retail, round(b.v_product_retail*0.75) as bersih, (round(b.v_product_retail*0.75) * a.n_receive) as nilai  from tm_sjpb_item a
         left join tr_product_priceco b on(a.i_product = b.i_product and b.i_product_grade = b.i_product_grade and b.i_price_group = '$i_kode_harga')
         where a.i_sjpb = '$i_sjpb' 
         and a.n_receive <> 0 
         and a.n_deliver <> 0
         ) as x");
       }else{
        $query = $this->db->query("
        select sum(x.v_product_retail) as nilaikotor from(
        select a.*, (b.v_product_retail* a.n_receive ) as v_product_retail, round(b.v_product_retail*0.75) as bersih, (round(b.v_product_retail*0.75) * a.n_receive) as nilai  from tm_sjpb_item a
        left join tr_product_priceco b on(a.i_product = b.i_product and b.i_product_grade = b.i_product_grade and b.i_price_group = '$i_kode_harga')
        where a.i_sjpb = '$i_sjpb' 
        and a.n_receive <> 0 
        and a.n_deliver <> 0
        ) as x");
       }
  
        if ($query->num_rows() > 0){
          return $query->row();
          }
     }
  
     function insert_spb_header($ispb, $icustomer,$dspb){
      $query 	= $this->db->query("SELECT current_timestamp as c");
      $row   	= $query->row();
      $now	  = $row->c;
      $this->db->set(
        array(
        'i_spb'                    => $ispb,
        'i_customer'               => $icustomer,
        'i_salesman'               => 'TL',
        'i_price_group'            => '03',
        'i_store'                  => 'PB',
        'i_store_location'         => '00',
        'd_spb'                    => $dspb,
        'd_spb_entry'              => $now,
        'f_spb_op'                 => 'f',
        'f_spb_pkp'                => 't',
        'f_spb_plusppn'            => 't',
        'f_spb_plusdiscount'       => 'f',
        'f_spb_stockdaerah'        => 't',
        'f_spb_program'            => 'f',
        'f_spb_consigment'         => 't',
        'f_spb_valid'              => 't',
        'f_spb_siapnotagudang'     => 't',
        'f_spb_cancel'             => 'f',
        'n_spb_toplength'          => 60,
        'n_spb_discount1'          => 0,
        'n_spb_discount2'          => 0,
        'n_spb_discount3'          => 0,
        'v_spb_discount1'          => 0,
        'v_spb_discount2'          => 0,
        'v_spb_discount3'          => 0,
        'v_spb_discounttotal'      => 0,
        'v_spb_discounttotalafter' => 0,
        'v_spb'                    => 0,
        'v_spb_after'              => 0,
        'i_approve1'               => 'SYSTEM',
        'i_approve2'               => 'SYSTEM',
        'i_area'                   => 'PB',
        'n_spb_discount4'          => 0,
        'v_spb_discount4'          => 0,
        'f_spb_siapnotasales'      => 't',
        'i_product_group'          => '01',
        'f_spb_opclose'            => 'f',
        'f_spb_pemenuhan'          => 'f',
        'n_print'                  => 0,
        'i_cek'                    => 'SYSTEM',
        'f_spb_rekap'              => 'f',
        )
      );
      $this->db->insert('tm_spb');
     }
  
     function insert_spb_item($ispb, $iproduct, $iproductgrade, $iproductmotif, $norder, $vunitprice, $eproductname, $nitemno){
       $iarea = 'PB';
       $iproductstatus = '1';
      $this->db->set(
        array(
          'i_spb'            => $ispb,
          'i_product'        => $iproduct,
          'i_product_grade'  => $iproductgrade,
          'i_product_motif'  => $iproductmotif,
          'n_order'          => $norder,
          'n_deliver'        => $norder,
          'n_stock'          => $norder,
          'v_unit_price'     => $vunitprice,
          'e_product_name'   => $eproductname,
          'i_op'             => null,
          'i_area'           => $iarea,
          'e_remark'         => '',
          'n_item_no'        => $nitemno,
          'i_product_status' => $iproductstatus
        )
      );
      $this->db->insert('tm_spb_item');
     }
  
     function update_spb_header($ispb, $diskon1persen, $v_spb, $v_spb_after, $pilihan){
      if($pilihan == 'biasa'){                               
        $diskon = 1;
      }else{
        $diskon = 0;
      }
  
       $this->db->query("update tm_spb set n_spb_discount1 = '$diskon', v_spb_discount1 = '$diskon1persen', v_spb_discounttotal = '$diskon1persen', v_spb_discounttotalafter = '$diskon1persen', v_spb = '$v_spb', v_spb_after = '$v_spb_after' where i_spb = '$ispb' and i_area = 'PB'");
     }
  
     function runningnumbersj($thbl)
     {
      $th	= substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
      
       $iarea = 'PB';
         $this->db->select(" n_modul_no as max from tm_dgu_no 
                             where i_modul='SJ'
                             and e_periode='$asal' 
                             and i_area='$iarea' for update", false);
       
       $query = $this->db->get();
       if ($query->num_rows() > 0){
         foreach($query->result() as $row){
           $terakhir=$row->max;
         }
         $nosj  =$terakhir+1;
           $this->db->query(" update tm_dgu_no 
                               set n_modul_no=$nosj
                               where i_modul='SJ'
                               and e_periode='$asal' 
                               and i_area='$iarea'", false);
         
         settype($nosj,"string");
         $a=strlen($nosj);
         while($a<4){
           $nosj="0".$nosj;
           $a=strlen($nosj);
         }
           $nosj  ="SJ-".$thbl."-".$iarea.$nosj;
         return $nosj;
       }else{
         $nosj  ="0001";
           $nosj  ="SJ-".$thbl."-".$iarea.$nosj;
           $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                              values ('SJ','$iarea','$asal',1)");
         return $nosj;
       }
     }
  
     function insert_sj_header($isj, $ispb, $dspb, $icustomer, $diskon1persen, $vspbgross, $vspbnetto, $pilihan){
      if($pilihan == 'biasa'){                               
        $diskon = 1;
      }else{
        $diskon = 0;
      }
  
        $this->db->set(
          array(	  
            'i_sj'            		=> $isj,
            'i_spb'           		=> $ispb,
            'd_spb'           		=> $dspb,
            'd_sj'            		=> $dspb,
            'd_sj_receive'        => $dspb,
            'i_area'		          => 'PB',
            'i_salesman'      		=> 'TL',
            'i_customer'      		=> $icustomer,
            'f_plus_ppn'          => 'f',
            'f_plus_discount'     => 'f',
            'n_nota_toplength'    => 60,
            'n_nota_discount1'  	=> $diskon,
            'n_nota_discount2'  	=> 0,
            'n_nota_discount3'   	=> 0,
            'v_nota_discount1'  	=> $diskon1persen,
            'v_nota_discount2'  	=> 0,
            'v_nota_discount3'  	=> 0,
            'v_nota_discounttotal'=> $diskon1persen,
            'v_nota_discount'     => $diskon1persen,
            'v_nota_gross'		    => $vspbgross,
            'v_nota_netto'    		=> $vspbnetto,
            'v_sisa'              => $vspbnetto,
            'd_sj_entry'      		=> $dspb,
            'i_dkb'               => 'SYSTEM',
            'd_dkb'               => $dspb,
            'f_nota_cancel'		    => 'f'
          )
        );
        $this->db->insert('tm_nota');
     }
  
     function updatespbsj($ispb,$isj,$dsj)
     {
     $this->db->query(" update tm_spb set i_sj = '$isj',d_sj='$dsj' where i_spb='$ispb' and i_area='PB'",false);
     }
  
      function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,
                                    $vunitprice,$isj,$i)
      {
        $iarea = 'PB';
          $query=$this->db->query(" select i_product_category, i_product_class from tr_product where i_product='$iproduct'",false);
              if ($query->num_rows() > 0){
                  foreach($query->result() as $qq){
                      $i_productcategory	=$qq->i_product_category;
                      $i_productclass	=$qq->i_product_class;
                  }
          $query2=$this->db->query(" select a.i_product_category, a.e_product_categoryname, b.i_product_class, b.e_product_classname from tr_product_category a, tr_product_class b 
                                      where a.i_product_category='$i_productcategory' and b.i_product_class='$i_productclass' ",false);
              if ($query2->num_rows() > 0){
                  foreach($query2->result() as $oo){
                      $i_product_category		=$oo->i_product_category;
                      $e_product_categoryname	=$oo->e_product_categoryname;
                      $i_product_class		=$oo->i_product_class;
                      $e_product_classname	=$oo->e_product_classname;
                  }
              }
  
          $this->db->set(
              array(
                  'i_sj'			     	 => $isj,
                  'i_area'	         	 => $iarea,
                  'i_product'			 	 => $iproduct,
                  'i_product_motif'	 	 => $iproductmotif,
                  'i_product_grade'	 	 => $iproductgrade,
                  'e_product_name'	 	 => $eproductname,
                  'n_deliver'       	 	 => $ndeliver,
                  'v_unit_price'		 	 => $vunitprice,
                  'i_product_category' 	 => $i_product_category,
                  'e_product_categoryname' => $e_product_categoryname,
                  'i_product_class' 		 => $i_product_class,
                  'e_product_classname' 	 => $e_product_classname,
                  'n_item_no'       	 	 => $i
              )
          );
          
          $this->db->insert('tm_nota_item');
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
  
      function inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
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
                                    '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
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
                                  set n_git_penjualan=n_git_penjualan+$qsj
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
                                    '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',$qaw,0,0,0,0,0,0,0,0,'f',$qsj) ",false);
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
  
      function cek_sj_nota($i_sjpb){
        $data = $this->db->query("select a.i_spb, b.i_sj, b.i_nota from tm_sjpb a
                                  left join tm_nota b on( a.i_spb = b.i_spb and a.i_area = b.i_area)
                                  where i_sjpb = '$i_sjpb'");
        if($data->num_rows() > 0){
          return $data->row();
        }
      }
}

/* End of file Mmaster.php */
