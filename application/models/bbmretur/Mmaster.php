<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacattbdetail($ittb,$iarea,$thn){
		$this->db->select("a.i_product, a.i_product_motif, a.e_product_motifname, c.e_product_name,b.*
						   from tr_product_motif a,tr_product c, tm_ttbretur_item b
						   where trim(b.i_ttb)='$ittb' and b.i_area='$iarea' and n_ttb_year='$thn'
                           and a.i_product=c.i_product and b.i_product1=a.i_product and b.i_product1_motif=a.i_product_motif",false);
		return $this->db->get();
	}
	
	function bacattb($ittb){
		$this->db->select("a.*, c.e_area_name, b.e_customer_name, d.e_salesman_name 
						   from tm_ttbretur a, tr_customer b, tr_area c, tr_salesman d 
						   where a.i_customer=b.i_customer and UPPER(a.i_ttb) = '$ittb' and a.i_area=c.i_area
						   and b.i_area=c.i_area and not a.d_receive1 is NULL and
						   a.i_salesman=d.i_salesman and a.f_ttb_cancel='f' and a.i_bbm isnull",false);
		return $this->db->get();
	}

	function updatettbheader($ittb,$thttb,$iarea,$ibbm,$dbbm)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$drec	= $row->c;
    	$this->db->set(
    		array(
					'i_bbm'		=> $ibbm,
					'd_bbm'		=> $dbbm,
					'd_receive2'=> $drec
    		)
    	);
    	$this->db->where('i_area',$iarea);
		$this->db->where('i_ttb',$ittb);
		$this->db->where('n_ttb_year',$thttb);
    	$this->db->update('tm_ttbretur');
    }
    function updatettbdetail($iproduct,$iproductgrade,$iproductmotif,$nttb,
							 $iproductxxx,$iproductgradexxx,$iproductmotifxxx,$nbbm,
							 $ittb,$thttb,$iarea,$inota)
    {
    	$this->db->set(
    		array(
					'i_product2'			=> $iproductxxx,
					'i_product2_grade'		=> $iproductgradexxx,
					'i_product2_motif'		=> $iproductmotifxxx,
					'n_quantity_receive'	=> $nbbm
    		)
    	);
    	$this->db->where('i_ttb',$ittb);
    	$this->db->where('n_ttb_year',$thttb);
    	$this->db->where('i_area',$iarea);
    	$this->db->where('i_product1',$iproduct);
    	$this->db->where('i_product1_grade',$iproductgrade);
    	$this->db->where('i_product1_motif',$iproductmotif);
    	$this->db->update('tm_ttbretur_item');
    }
    function hapusttbdetail($iproduct,$iproductgrade,$iproductmotif,$ittb,$thttb,$iarea)
    {
    	$this->db->set(
    		array(
					'i_product2'			=> null,
					'i_product2_grade'		=> null,
					'i_product2_motif'		=> null,
					'n_quantity_receive'	=> null
    		)
    	);
    	$this->db->where('i_ttb',$ittb);
    	$this->db->where('n_ttb_year',$thttb);
    	$this->db->where('i_area',$iarea);
    	$this->db->where('i_product1',$iproduct);
    	$this->db->where('i_product1_grade',$iproductgrade);
    	$this->db->where('i_product1_motif',$iproductmotif);
    	$this->db->update('tm_ttbretur_item');
	}
	
	function runningnumberbbm($thbl,$iarea){
		$th	= substr($thbl,0,4);
		$asal=$thbl;
		$thbl=substr($thbl,2,2).substr($thbl,4,2);
			$this->db->select(" n_modul_no as max from tm_dgu_no 
							where i_modul='BBM'
							and i_area='$iarea'
							and substring(e_periode,1,4)='$th' for update", false);
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				foreach($query->result() as $row){
				  $terakhir=$row->max;
				}
				$nobbm  =$terakhir+1;
		  $this->db->query(" update tm_dgu_no 
							  set n_modul_no=$nobbm
							  where i_modul='BBM'
							  and i_area='$iarea'
							  and substring(e_periode,1,4)='$th'", false);
				settype($nobbm,"string");
				$a=strlen($nobbm);
				while($a<4){
				  $nobbm="0".$nobbm;
				  $a=strlen($nobbm);
				}
					$nobbm  ="BBM-".$thbl."-".$iarea.$nobbm;
				return $nobbm;
			}else{
				$nobbm  ="0001";
				$nobbm  ="BBM-".$thbl."-".$iarea.$nobbm;
		  $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
							 values ('BBM','$iarea','$asal',1)");
				return $nobbm;
			}
	  }

	  function insertbbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isalesman)
	  {
		  $this->db->set(
			  array(
				  'i_bbm'					        => $ibbm,
				  'i_bbm_type'			      => $ibbmtype,
				  'i_refference_document'	=> $ittb,
				  'd_refference_document'	=> $dttb,
				  'd_bbm'					        => $dbbm,
				  'e_remark'				      => $eremark,
				  'i_area'				        => $iarea,
				  'i_salesman'			      => $isalesman
			  )
		  );
		  
		  $this->db->insert('tm_bbm');
	  }
	  function deletebbmheader($ibbm)
	  {
		  $this->db->query("delete from tm_bbm where i_bbm='$ibbm'");
	  }
	  function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$ittb,$ibbm,$eremark,$dttb,$ibbmtype,$i,$dbbm)
	  {
		$th=substr($dbbm,0,4);
		$bl=substr($dbbm,5,2);
		$pr=$th.$bl;
		  $this->db->set(
			  array(
				  'i_bbm'					        => $ibbm,
				  'i_bbm_type'		  			=> $ibbmtype,
				  'i_refference_document'	=> $ittb,
				  'i_product'     				=> $iproduct,
				  'i_product_motif'   		=> $iproductmotif,
				  'i_product_grade'   		=> $iproductgrade,
				  'e_product_name'    		=> $eproductname,
				  'n_quantity'			      => $nquantity,
				  'v_unit_price'    			=> $vunitprice,
				  'e_remark'				      => $eremark,
				  'd_refference_document'	=> $dttb,
		  'e_mutasi_periode'      => $pr,
		  'n_item_no'             => $i
			  )
		  );
		  $this->db->insert('tm_bbm_item');
	  }
	  function deletebbmdetail($iproduct,$iproductgrade,$iproductmotif,$ibbm,$ibbmtype)
	  {
		  $this->db->query("	delete from tm_bbm_item where i_product='$iproduct' and i_product_grade='$iproductgrade' 
							  and i_product_motif='$iproductmotif' and i_bbm='$ibbm' and i_bbm_type='$ibbmtype'");
	  }
  
	  function insertheader(	$iarea,$ittb,$dttb,$icustomer,$isalesman,$nttbdiscount1,$nttbdiscount2,
							  $nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$fttbpkp,$fttbplusppn,
							  $fttbplusdiscount,$vttbgross,$vttbdiscounttotal,$vttbnetto,$ettbremark,$fttbcancel,
							  $dreceive1,$tahun	)
	  {
		  $query 	= $this->db->query("SELECT current_timestamp as c");
		  $row   	= $query->row();
		  $dentry	= $row->c;
		  $this->db->set(
			  array(
					  'i_area'				=> $iarea,
					  'i_ttb'					=> $ittb,
					  'd_ttb'					=> $dttb,
					  'i_customer'			=> $icustomer,
					  'i_salesman'			=> $isalesman,
					  'n_ttb_discount1'		=> $nttbdiscount1,
					  'n_ttb_discount2'		=> $nttbdiscount2,
					  'n_ttb_discount3'		=> $nttbdiscount3,
					  'v_ttb_discount1'		=> $vttbdiscount1,
					  'v_ttb_discount2'		=> $vttbdiscount2,
					  'v_ttb_discount3'		=> $vttbdiscount3,
					  'f_ttb_pkp'				=> $fttbpkp,
					  'f_ttb_plusppn'			=> $fttbplusppn,
					  'f_ttb_plusdiscount'	=> $fttbplusdiscount,
					  'v_ttb_gross'			=> $vttbgross,
					  'v_ttb_discounttotal'	=> $vttbdiscounttotal,
					  'v_ttb_netto'			=> $vttbnetto,
					  'v_ttb_sisa'			=> $vttbnetto,
					  'e_ttb_remark'			=> $ettbremark,
					  'f_ttb_cancel'			=> $fttbcancel,
					  'd_receive1'			=> $dreceive1,
					  'd_entry'				=> $dentry,
					  'n_ttb_year'			=> $tahun
			  )
		  );
		  $this->db->insert('tm_ttbretur');
	  }
	  function insertdetail($iarea,$ittb,$dttb,$inota,$dnota,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver)
	  {
		  $this->db->set(
			  array(
					  'i_area'			=> $iarea,
					  'i_ttb'				=> $ittb,
					  'i_nota'			=> $inota,
					  'd_ttb'				=> $dttb,
					  'd_nota'			=> $dnota,
					  'i_product1'		=> $iproduct,
					  'i_product1_grade'	=> $iproductgrade,
					  'i_product1_motif'	=> $iproductmotif,
					  'n_quantity'		=> $nquantity,
					  'v_unit_price'		=> $vunitprice,
					  'e_ttb_remark'		=> $ettbremark,
					  'n_ttb_year'		=> $tahun
			  )
		  );
		  
		  $this->db->insert('tm_ttbretur_item');
	  }
	  function updateheader(	$ittb,$iarea,$tahun,$dttb,$dreceive1,$eremark,
							  $nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,
							  $vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,
							  $vttbgross)
	  {
		  $query 	= $this->db->query("SELECT current_timestamp as c");
		  $row   	= $query->row();
		  $dupdate= $row->c;
		  $this->db->set(
			  array(
			  'd_ttb'					=> $dttb,
			  'd_receive1'			=> $dreceive1,
			  'e_ttb_remark'			=> $eremark,
			  'd_update'				=> $dupdate,
			  'n_ttb_discount1'		=> $nttbdiscount1,
			  'n_ttb_discount2'		=> $nttbdiscount2,
			  'n_ttb_discount3'		=> $nttbdiscount3,
			  'v_ttb_discount1'		=> $vttbdiscount1,
			  'v_ttb_discount2'		=> $vttbdiscount2,
			  'v_ttb_discount3'		=> $vttbdiscount3,
			  'v_ttb_gross'			=> $vttbgross,
			  'v_ttb_discounttotal'	=> $vttbdiscounttotal,
			  'v_ttb_netto'			=> $vttbnetto,
			  'v_ttb_sisa'			=> $vttbnetto
			  )
		  );
		  $this->db->where('i_ttb',$ittb);
		  $this->db->where('i_area',$iarea);
		  $this->db->where('n_ttb_year',$tahun);
		  $this->db->update('tm_ttbretur');
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
	  function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak)
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
									'$eproductname', '$ido', '$now', $qdo, 0, $q_ak+$qdo, $q_ak
								  )
								",false);
	  }
	  function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
	  {
		$ada=false;
		$query=$this->db->query(" SELECT i_product
								  from tm_mutasi
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								  and e_mutasi_periode='$emutasiperiode'
								",false);
		if ($query->num_rows() > 0){
				  $ada=true;
			  }
		return $ada;
	  }
	  function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_mutasi 
								  set n_mutasi_returoutlet=n_mutasi_returoutlet+$qdo, n_saldo_akhir=n_saldo_akhir+$qdo
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								  and e_mutasi_periode='$emutasiperiode'
								",false);
	  }
	  function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  insert into tm_mutasi 
								  (
									i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
									e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
									n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
								  values
								  (
									'$iproduct','00','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,0,$qdo,0,0,0,0,$qdo,0,'f')
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
	  function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_ic set n_quantity_stock=$q_ak+$qdo
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								",false);
	  }
	  function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo)
	  {
		$query=$this->db->query(" 
								  insert into tm_ic 
								  values
								  (
									'$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname',$qdo, 't'
								  )
								",false);
	  }
	  function inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$qbbm,$q_aw,$q_ak)
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
									'$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
									'$eproductname', '$ibbm', '$now', $q_in+$qbbm, $q_out, $q_ak+$qbbm, $q_aw
								  )
								",false);
	  }
	  function updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_mutasi 
								  set n_mutasi_bbm=n_mutasi_bbm+$qbbm, n_saldo_akhir=n_saldo_akhir+$qbbm
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								  and e_mutasi_periode='$emutasiperiode'
								",false);
	  }
	  function insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  insert into tm_mutasi 
								  (
									i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
									e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
									n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
								  values
								  (
									'$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qbbm,0,0,0,$qbbm,0,'f')
								",false);
	  }
	  function updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$q_ak)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qbbm
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								",false);
	  }
	  function inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbm)
	  {
		$query=$this->db->query(" 
								  insert into tm_ic 
								  values
								  (
									'$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qbbm, 't'
								  )
								",false);
	  }
	  function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbbk,$q_aw,$q_ak)
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
									'$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
									'$eproductname', '$ibbk', '$now', $q_in, $q_out+$qbbk, $q_ak-$qbbk, $q_aw
								  )
								",false);
	  }
	  function updatemutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_mutasi 
								  set n_mutasi_penjualan=n_mutasi_penjualan+$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								  and e_mutasi_periode='$emutasiperiode'
								",false);
	  }
	  function insertmutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  insert into tm_mutasi 
								  (
									i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
									e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbk,n_mutasi_penjualan,
									n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
								  values
								  (
									'$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,$qbbk,0,0,$qbbk,0,'f')
								",false);
	  }
	  function updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_mutasi 
								  set n_mutasi_bbk=n_mutasi_bbk+$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								  and e_mutasi_periode='$emutasiperiode'
								",false);
	  }
	  function insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  insert into tm_mutasi 
								  (
									i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
									e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbk,n_mutasi_penjualan,
									n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
								  values
								  (
									'$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,0,0,$qbbk,$qbbk,0,'f')
								",false);
	  }
	  function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$q_ak)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qbbk
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								",false);
	  }
	  function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbk)
	  {
		$query=$this->db->query(" 
								  insert into tm_ic 
								  values
								  (
									'$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0, 't'
								  )
								",false);
	  }
	  function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ibbm,$ntmp,$eproductname)
	  {
		$queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
									  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
									  and i_store='$istore' and i_store_location='$istorelocation'
									  and i_store_locationbin='$istorelocationbin' 
									  order by i_trans desc",false);
  #and i_refference_document='$ibbm'
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
									  '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
									  '$eproductname', '$ibbm', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir
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
	  function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_mutasi set n_mutasi_returoutlet=n_mutasi_returoutlet-$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								  and e_mutasi_periode='$emutasiperiode'
								",false);
	  }
	  function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk)
	  {
		$query=$this->db->query(" 
								  UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qbbk
								  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
								  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
								",false);
	  }
}

/* End of file Mmaster.php */
