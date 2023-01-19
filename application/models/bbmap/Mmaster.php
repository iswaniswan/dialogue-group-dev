<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cek_op($iop){
      $this->db->select('*');
      $this->db->from('tm_op');
      $this->db->where('i_op',$iop);
      return $this->db->get();
    }

    function bacadetailop($iop){
      $this->db->select(" a.*, b.e_product_motifname, (a.n_order - a.n_delivery) as sisa, d.i_supplier, d.e_supplier_name, c.d_op, e.i_area, e.e_area_name, d.n_supplier_discount, d.n_supplier_discount2 
                        from tm_op_item a, tr_product_motif b, tm_op c, tr_supplier d, tr_area e
                        where a.i_op = '$iop' and (a.n_delivery<a.n_order or n_delivery isnull)
                        and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                        and a.i_op=c.i_op and c.i_supplier=d.i_supplier and c.i_area=e.i_area
                        order by a.e_product_name", false);
      return $this->db->get();
    }

    function baca($iap,$isupplier){
		  $this->db->select(" * from tm_ap 
				                inner join tr_supplier on (tm_ap.i_supplier=tr_supplier.i_supplier)
				                inner join tr_area on (tm_ap.i_area=tr_area.i_area)
				                where tm_ap.i_ap ='$iap'", false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->row();
		  }
    }

    function bacadetail($iap,$isupplier)
    {
		  $this->db->select(" a.*, b.e_product_motifname from tm_ap_item a, tr_product_motif b
				                where a.i_ap = '$iap' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
				                order by a.i_product", false);//and i_supplier='$isupplier' 
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    function insertheader($iap,$isupplier,$iop,$iarea,$dap,$vapgross,$iapold){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
					'i_ap'		    => $iap,
					'i_ap_old'	  => $iapold,
					'i_supplier'  => $isupplier,
					'i_op'		    => $iop,
					'i_area'	    => $iarea,
					'd_ap'		    => $dap,
					'v_ap_gross'  => $vapgross,
					'f_ap_cancel' => 'f',
					'd_entry'     => $dentry
    		)
    	);
    	
    	$this->db->insert('tm_ap');
    }

    function insertdetail($iap,$dap,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$vproductmill,$iop,$i){
      $th=substr($dap,0,4);
      $bl=substr($dap,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
					'i_ap'				    => $iap,
					'd_ap'				    => $dap,
					'i_supplier'		  => $isupplier,
					'i_product'			  => $iproduct,
					'i_product_grade'	=> $iproductgrade,
					'i_product_motif'	=> $iproductmotif,
					'e_product_name'	=> $eproductname,
					'n_receive'			  => $nreceive,
					'v_product_mill'	=> $vproductmill,
          'e_mutasi_periode'=> $pr,
          'n_item_no'       => $i
    		)
    	);
    	$this->db->insert('tm_ap_item');
		  $this->db->query("	update tm_op_item set n_delivery=n_delivery+$nreceive where i_op='$iop' and i_product='$iproduct' 
							            and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
		  $this->db->query("	update tm_spmb_item set n_deliver=n_deliver+$nreceive where i_op='$iop' and i_product='$iproduct' 
							            and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }

    function updateheader($iap,$isupplier,$iop,$iarea,$dap,$vapgross,$iapold){
		  $query 	= $this->db->query("SELECT current_timestamp as c");
		  $row   	= $query->row();
		  $dentry	= $row->c;
      $data = array(
		  		'i_ap'		        => $iap,
		  		'i_ap_old'	      => $iapold,
		  		'i_supplier'      => $isupplier,
		  		'i_op'		        => $iop,
		  		'i_area'	        => $iarea,
		  		'd_ap'		        => $dap,
		  		'v_ap_gross'      => $vapgross,
		  		'f_ap_cancel'     => 'f',
		  		'd_update'        => $dentry
		  );
		  $this->db->where('i_ap', $iap);
		  $this->db->update('tm_ap', $data);
		  $this->db->query("update tm_op set f_op_close='t' where i_op='$iop'");
    }

	  public function updatespb($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispb,$iarea){
	  	$this->db->query("update tm_spb_item set n_stock=n_stock+$nreceive where i_spb='$ispb' and i_area='$iarea' and i_product='$iproduct' 
	  					        	and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }
    
	  public function updatespmb($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispmb,$iarea){
	  	$this->db->query("update tm_spmb_item set n_stock=n_stock+$nreceive where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' 
	  						        and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }
    
	  public function updatespbx($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispb,$iarea){
	  	$this->db->query("update tm_spb_item set n_stock=n_stock-$nreceive where i_spb='$ispb' and i_area='$iarea' and i_product='$iproduct' 
	  						        and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }
    
	  public function updatespmbx($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispmb,$iarea){
	  	$this->db->query("update tm_spmb_item set n_stock=n_stock-$nreceive where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' 
	  						        and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }
    
    public function deletedetail($iproduct, $iproductgrade, $iap, $isupplier, $iproductmotif, $tahun){
	    $this->db->query("DELETE FROM tm_ap_item WHERE i_ap='$iap' 
	    			 	          and i_product='$iproduct' and i_product_grade='$iproductgrade' 
	    				          and i_product_motif='$iproductmotif'");
	    $this->db->query("DELETE FROM tm_bbm_item WHERE i_refference_document='$iap' and i_bbm_type='04' and to_char(d_refference_document,'yyyy')='$tahun'
	    				          and i_product='$iproduct' and i_product_motif='$iproductmotif' and i_product_grade='$iproductgrade'");
    }

    function uphead($iap,$isupplier,$iop,$iarea,$dap,$vapgross){
	    $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $dentry	= $row->c;
	    $this->db->query("update tm_ap set i_op='$iop', d_update='$dentry', i_area='$iarea', d_ap='$dap', v_ap_gross=v_ap_gross-$vapgross where i_ap='$iap' and i_supplier='$isupplier'",false);
    }

    public function delete($iap,$isupplier,$iop) {
	  	$this->db->query("update tm_op set f_op_close='f' WHERE i_op='$iop' and i_supplier='G0000' ",False);
    }

    function bacaproduct($op,$num,$offset){
		if($offset=='')
			$offset=0;
		  $query=$this->db->query("	select a.i_product as kode, a.i_product_motif as motif, a.e_product_motifname as namamotif, 
									            c.e_product_name as nama, b.v_product_mill as harga, b.n_item_no 
									            from tr_product_motif a, tm_op_item b, tr_product c, tr_harga_beli d
									            where a.i_product=c.i_product and d.i_price_group='00' and b.i_product_grade='A'
                              and b.i_product=d.i_product and b.i_op='$op' and b.i_product=a.i_product 
                              and b.i_product_motif=a.i_product_motif limit $num offset $offset",false);
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    function bacasupplier($num,$offset){
		  $this->db->select(" * from tr_supplier where i_supplier_group='G0000'",false)->limit($num,$offset);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    function bacaop($num,$offset){
		  $this->db->select(" a.*, b.e_supplier_name, b.n_supplier_toplength, c.e_area_name 
		  			            from tm_op a, tr_area c, tr_supplier b
		  			            where a.i_area=c.i_area and b.i_supplier_group='G0000'
		  			            and a.f_op_cancel='f' and a.i_supplier=b.i_supplier
		  			            and a.f_op_close='f'
		  			            order by a.d_reff, a.i_reff, a.i_op",false)->limit($num,$offset);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    function cariop($cari,$num,$offset){
		  $this->db->select("a.*, 'Lain-lain' as e_supplier_name, c.e_area_name 
					              from tm_op a, tr_area c, tr_supplier b 
					              where a.i_area=c.i_area and b.i_supplier_group='G0000' 
					              and (upper(a.i_op) like '%$cari%' or upper(a.i_supplier) like '%$cari%'
					              or upper(b.e_supplier_name) like '%$cari%' or upper(a.i_reff) like '%$cari%')
					              and a.f_op_cancel='f' and a.i_supplier=b.i_supplier
					              and a.f_op_close='f'
					              order by a.d_reff, a.i_reff,a.i_op",FALSE)->limit($num,$offset);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    function cari($cari,$num,$offset){
		  $this->db->select(" * from tm_ap where upper(i_ap) like '%$cari%' or upper(i_supplier) like '%$cari%'
							          order by i_ap",FALSE)->limit($num,$offset);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    function carisupplier($cari,$num,$offset){
		  $this->db->select(" * from tr_supplier where (upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%')
		  					        and i_supplier_group='G0000' order by i_supplier",FALSE)->limit($num,$offset);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    function cariproduct($cari,$num,$offset){
		  if($offset=='')
		  	$offset=0;
		    $query=$this->db->query("select a.i_product as kode, 
		  							            a.i_product_motif as motif,
		  							            a.e_product_motifname as namamotif, 
		  							            c.e_product_name as nama,
		  							            c.v_product_mill as harga
		  							            from tr_product_motif a, tr_product c
		  							            where a.i_product=c.i_product
		  						              and (upper(a.i_product) like '%$cari%' or upper(c.e_product_name) like '%$cari%')
		  							            limit $num offset $offset",false);
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }
	function runningnumber($thbl){
		$th		= substr($thbl,0,2);
		$this->db->select(" max(substr(i_ap,9,4)) as max from tm_ap 
        				  			where substr(i_ap,4,2)='$th' ", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$noop  =$terakhir+1;
			settype($noop,"string");
			$a=strlen($noop);
			while($a<4){
			  $noop="0".$noop;
			  $a=strlen($noop);
			}
			$noop  ="AP-".$thbl."-".$noop;
			return $noop;
		}else{
			$noop  ="0001";
			$noop  ="AP-".$thbl."-".$noop;
			return $noop;
		}
  }

	function runningnumberbbm($thbl){
		$th		= substr($thbl,0,2);
		$this->db->select(" max(substr(i_bbm,10,6)) as max from tm_bbm where substr(i_bbm,5,2)='$th' and i_bbm_type='04'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$noso  =$terakhir+1;
			settype($noso,"string");
			$a=strlen($noso);
			while($a<6){
			  $noso="0".$noso;
			  $a=strlen($noso);
			}
			$noso  ="BBM-".$thbl."-".$noso;
			return $noso;
		}else{
			$noso  ="000001";
			$noso  ="BBM-".$thbl."-".$noso;
			return $noso;
		}
  }

	function insertbbmheader($iap,$dap,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea){
    	$this->db->set(
    		array(
				'i_bbm'	        				=> $ibbm,
				'i_bbm_type'		      	=> $ibbmtype,
				'i_refference_document'	=> $iap,
				'd_refference_document'	=> $dap,
				'd_bbm'       					=> $dbbm,
				'e_remark'		      		=> $eremark,
				'i_area'	        			=> $iarea
    		)
    	);
    	
    	$this->db->insert('tm_bbm');
  }

	function updatebbmheader($iap,$dap,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea){
    	$this->db->set(
    		array(
				'i_refference_document'	=> $iap,
				'd_refference_document'	=> $dap,
				'd_bbm'	        				=> $dbbm,
				'e_remark'	      			=> $eremark,
				'i_area'	        			=> $iarea
    		)
    	);
    	$this->db->where('i_bbm',$ibbm);
		  $this->db->where('i_bbm_type',$ibbmtype);
    	$this->db->update('tm_bbm');
  }

	function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$iap,$ibbm,$eremark,$dap){
      $th=substr($dap,0,4);
      $bl=substr($dap,5,2);
      $pr=$th.$bl;
    	  $this->db->set(
    		array(
				'i_bbm'					        => $ibbm,
				'i_refference_document'	=> $iap,
				'i_product'		      		=> $iproduct,
				'i_product_motif'	    	=> $iproductmotif,
				'i_product_grade'   		=> $iproductgrade,
				'e_product_name'	    	=> $eproductname,
				'n_quantity'      			=> $nquantity,
				'v_unit_price'	    		=> $vunitprice,
				'e_remark'      				=> $eremark,
				'd_refference_document'	=> $dap,
        'e_mutasi_periode'      => $pr,
        'i_bbm_type'            => '04'
    		)
    	);    	
    	$this->db->insert('tm_bbm_item');
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

    function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak){
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query("INSERT INTO tm_ic_trans(
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$ido', '$now', $qdo, 0, $q_ak+$qdo, $q_ak
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

    function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
      $query=$this->db->query(" UPDATE tm_mutasi 
                                set n_mutasi_pembelian=n_mutasi_pembelian+$qdo, n_saldo_akhir=n_saldo_akhir+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }

    function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
      $query=$this->db->query("insert into tm_mutasi (
                                i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,$qdo,0,0,0,0,0,$qdo,0,'f')
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

    function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak){
      $query=$this->db->query(" UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }

    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo){
      $query=$this->db->query(" insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname',$qdo, 't'
                                )
                              ",false);
    }

    function inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$qbbm,$q_aw,$q_ak){
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query(" INSERT INTO tm_ic_trans(
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

    function updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode){
      $query=$this->db->query(" UPDATE tm_mutasi 
                                set n_mutasi_bbm=n_mutasi_bbm+$qbbm, n_saldo_akhir=n_saldo_akhir+$qbbm
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }

    function insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode){
      $query=$this->db->query(" insert into tm_mutasi (
                                i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qbbm,0,0,0,$qbbm,0,'f')
                              ",false);
    }

    function updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$q_ak){
      $query=$this->db->query(" UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qbbm
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }

    function inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbm){
      $query=$this->db->query(" insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qbbm, 't'
                                )
                              ",false);
    }

    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$iap,$ntmp,$eproductname){
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
                                    '$eproductname', '$iap', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir
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
    function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qap,$emutasiperiode){
      $query=$this->db->query(" UPDATE tm_mutasi set n_mutasi_pembelian=n_mutasi_pembelian-$qap, n_saldo_akhir=n_saldo_akhir-$qap
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }

    function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qap){
      $query=$this->db->query(" UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qap
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    
}

/* End of file Mmaster.php */
