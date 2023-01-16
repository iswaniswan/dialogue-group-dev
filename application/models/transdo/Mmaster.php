<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function insertheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross){
    $query   = $this->db->query("SELECT current_timestamp as c");
    $row     = $query->row();
    $dentry  = $row->c;
		$this->db->set(
    		array(
			'i_do'				=> $ido,
			'i_supplier'		=> $isupplier,
			'i_op'				=> $iop,
			'i_area'			=> $iarea,
			'd_do'				=> $ddo, 
			'v_do_gross'		=> $vdogross,
			'd_entry'           => $dentry
    		));
    	$this->db->insert('tm_redo');
    }

    function updatedotrans($nodo,$xop,$iproduct){
	    $konek 	= "host=192.168.0.93 user=dedy dbname=distributor port=5432 password=g#>m[J2P^^";
	    $db    	= pg_connect($konek);
	    $sql	= " update duta_prod.tm_trans_do set f_transfer='t'
					 where i_do_code='$nodo' and i_op_code='$xop' and i_product='$iproduct'";
	    pg_query($sql);
    }

    function updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver){
      $this->db->query("update tm_op_item set n_delivery=n_delivery+$ndeliver where i_op='$iop' and i_product='$iproduct' and   
                        i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }

    function updatespbdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver){
		  $this->db->select(" i_reff, i_area from tm_op where i_op='$iop'", false);
		  $query = $this->db->get();
		  if($query->num_rows>0){
			  foreach($query->result() as $row){
				  $spb =$row->i_reff;
				  $area=$row->i_area;
			  }
				$que=$this->db->query("	select n_order, n_deliver from tm_spb_item 
										where i_spb='$spb' and i_area='$area' and i_product='$iproduct' 
										and i_product_grade='$iproductgrade'");
				if($que->num_rows>0){
					$tmp=0;
				  foreach($que->result() as $raw){
						$jmlord =$raw->n_order;
						$jmldel =$raw->n_deliver;
						$tmp=$ndeliver+$jmldel;
					}
					if($jmlord>=$tmp){
						$this->db->query("update tm_spb_item set n_deliver=n_deliver+$ndeliver, n_stock=n_stock+$ndeliver
										where i_spb='$spb' and i_area='$area' and i_product='$iproduct' and i_product_grade='$iproductgrade'");
					}
				}
		  }
    }

    function updateheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross){
		$data = array(
			'i_do'				=> $ido,
			'i_supplier'		=> $isupplier,
			'i_op'				=> $iop,
			'i_area'			=> $iarea,
			'd_do'				=> $ddo, 
			'v_do_gross'		=> $vdogross
    	);
		$this->db->where('i_do',$ido);
		$this->db->where('i_supplier', $isupplier);
    	$this->db->update('tm_do',$data);
    }


    function insertdetail($iop,$ido,$iproduct,$iproductgrade,$eproductname,$jumlah,$vproductmill,$iproductmotif,$isupplier,$i,$ddo,$emutasiperiode){
		  $this->db->set(
      		array(
			  'i_do'			=> $ido,
			  'i_supplier'		=> $isupplier,
			  'i_product'		=> $iproduct,
			  'i_product_grade'	=> $iproductgrade,
			  'i_product_motif'	=> $iproductmotif,
			  'e_product_name'	=> $eproductname,
			  'n_deliver'		=> $jumlah,
			  'v_product_mill'	=> $vproductmill,
			  'i_op'		    => $iop,
              'n_item_no'       => $i,
              'd_do'            => $ddo,
              'e_mutasi_periode'=> $emutasiperiode
      		)
      	);
      	$this->db->insert('tm_do_item');
    }

	function updateopitem($iop,$iproduct,$iproductgrade,$iproductmotif,$jumlah,$ido){
		$this->db->query("	update tm_ic set n_quantity_stock=n_quantity_stock+$jumlah
						 	where i_product='$iproduct'
							and i_product_grade='$iproductgrade'
							and i_product_motif='$iproductmotif'
							and i_store='AA'
							and i_store_location='01'
							and i_store_locationbin='00'", false);
		$query=$this->db->query("select * from tm_op_item 
								 where i_op='$iop' 
								 and i_product='$iproduct'
								 and i_product_grade='$iproductgrade'
								 and i_product_motif='$iproductmotif'", false);
		foreach ($query->result() as $row){
		   	$jmltmp=$row->n_delivery;
			$jmlop =$row->n_order;
		}
		if(($jmltmp+$jumlah)<=$jmlop){
			$this->db->set(
				array(
				'n_delivery'	=> $jumlah
				)
			);
			$this->db->where('i_op',$iop);
			$this->db->where('i_product',$iproduct);
			$this->db->where('i_product_grade',$iproductgrade);
			$this->db->where('i_product_motif',$iproductmotif);
			$this->db->insert('tm_op_item');
		}else{
			return false;
		}
    }

	function cekadaop($iop,$iproduct,$iproductmotif,$iproductgrade,$iarea){
		$que=$this->db->query("select * from tm_op where i_op like '%$iop%' and i_area='$iarea'", false);
		if($que->num_rows()>0){
			foreach($que->result() as $tmp){
				$query=$this->db->query("select i_op from tm_op_item 
										 where i_op='$tmp->i_op'and i_product='$iproduct' 
										 and i_product_grade='$iproductgrade'", false);
				return $query->num_rows();
			}
		}
    }
    
	function cekdataitem($ido,$isupplier,$iproduct,$iproductmotif,$iproductgrade,$thbl){
		$query=$this->db->query("select i_do from tm_do_item 
								 where i_do='$ido' and i_supplier='$isupplier'
								 and i_product='$iproduct' and i_product_grade='$iproductgrade' 
								 and i_product_motif='00'", false);
		if($query->num_rows()>0){
			return $query->num_rows();
		}else{
			$ido='DO-'.$thbl.'-DT'.substr($ido,2,4);
			$query=$this->db->query("select i_do from tm_do_item 
									 where i_do='$ido' and i_supplier='$isupplier'
									 and i_product='$iproduct' and i_product_grade='$iproductgrade' 
									 and i_product_motif='00'", false);
			return $query->num_rows();
		}
    }
    
	function cekdata($ido,$isupplier,$thbl){
		$query=$this->db->query("select i_do from tm_do where i_do='$ido' and i_supplier='$isupplier'", false);
		if($query->num_rows()>0){
			return $query->num_rows();
		}else{
			$ido='DO-'.$thbl.'-DT'.substr($ido,2,4);
			$query=$this->db->query("select i_do from tm_do where i_do='$ido' and i_supplier='$isupplier'", false);
			return $query->num_rows();
		}
    }
    
    function inserttmp($ido,$iproduct,$iproductgrade,$eproductname,$jumlah,$vproductmill,$iproductmotif,$isupplier,$ddo,$iarea,$iop,$dop){
		$this->db->select(" n_delivery from tt_do where i_do='$ido' and i_product='$iproduct'
							and i_product_grade='$iproductgrade' and i_product_motif='00' 
							and i_area='$iarea' and i_op='$iop'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			$this->db->query("	update tt_do set n_delivery=n_delivery+$jumlah
						 	where i_do='$ido' and i_product='$iproduct'
							and i_product_grade='$iproductgrade' and i_product_motif='00' 
							and i_area='$iarea' and i_op='$iop'", false);
		}else{
			$this->db->set(
				array(
				'i_do'					=> $ido,
				'i_supplier'			=> $isupplier,
				'i_product'				=> $iproduct,
				'i_product_grade'		=> $iproductgrade,
				'i_product_motif'		=> $iproductmotif,
				'e_product_name'		=> $eproductname,
				'n_delivery'			=> $jumlah,
				'v_product_mill'		=> $vproductmill,
				'd_do'					=> $ddo,
				'i_area'				=> $iarea,
				'i_op'					=> $iop,
                'd_op'                  => $dop
				)
			);
			$this->db->insert('tt_to');
		}
    }
	function runningnumberbbm(){
	   	$query 	= $this->db->query("SELECT to_char(current_timestamp,'yymm') as c");
		$row   	= $query->row();
		$thbl	= $row->c;
		$th		= substr($thbl,0,2);
		$this->db->select(" max(substr(i_bbm,10,6)) as max from tm_bbm where substr(i_bbm,5,2)='$th' ", false);
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

	function insertbbmheader($ido,$ddo,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isupplier){
    	$this->db->set(
    		array(
				'i_bbm'					=> $ibbm,
				'i_bbm_type'			=> $ibbmtype,
				'i_refference_document'	=> $ido,
				'd_refference_document'	=> $ddo,
				'd_bbm'					=> $dbbm,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea,
				'i_supplier'			=> $isupplier
    		)
    	);
    	
    	$this->db->insert('tm_bbm');
    }

	function cekbbm($ido,$ibbmtype,$iarea,$isupplier,$ddo){
		$query=$this->db->query("select i_bbm from tm_bbm 
								 where trim(i_refference_document)='$ido' and i_bbm_type='$ibbmtype' 
								 and i_area='$iarea' and i_supplier='$isupplier' and d_refference_document='$ddo'", false);
		if($query->num_rows()>0){
			foreach($query->result() as $row){
			  $no=$row->i_bbm;
			}
			return $no;
		}
    }
    
	function updatebbmheader($ido,$ddo,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isupplier){
    	$this->db->set(
    		array(
				'i_refference_document'	=> $ido,
				'd_refference_document'	=> $ddo,
				'd_bbm'					=> $dbbm,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea,
				'i_supplier'			=> $isupplier
    		)
    	);
    	$this->db->where('i_bbm',$ibbm);
		$this->db->where('i_bbm_type',$ibbmtype);
    	$this->db->update('tm_bbm');
    }
    
	function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$ido,$ibbm,$eremark,$ddo){
		$this->db->select(" n_quantity from tm_bbm_item where i_bbm='$ibbm' and i_product='$iproduct' and i_refference_document='$ido'
							and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			$this->db->query("	update tm_bbm_item set n_quantity=n_quantity+$nquantity
						 	where i_bbm='$ibbm' and i_product='$iproduct' and i_refference_document='$ido'
							and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif' ", false);			
		}else{
        $th=substr($ddo,0,4);
        $bl=substr($ddo,5,2);
        $pr=$th.$bl;
			$this->db->set(
				array(
					'i_bbm'					=> $ibbm,
					'i_refference_document'	=> $ido,
					'i_product'				=> $iproduct,
					'i_product_motif'		=> $iproductmotif,
					'i_product_grade'		=> $iproductgrade,
					'e_product_name'		=> $eproductname,
					'n_quantity'			=> $nquantity,
					'v_unit_price'			=> $vunitprice,
					'e_remark'				=> $eremark,
					'd_refference_document'	=> $ddo,
          'e_mutasi_periode'      => $pr
				)
			);
			$this->db->insert('tm_bbm_item');
		}
    }

	function runningnumberbbk(){
	   	$query 	= $this->db->query("SELECT to_char(current_timestamp,'yymm') as c");
		$row   	= $query->row();
		$thbl	= $row->c;
		$th		= substr($thbl,0,2);
		$this->db->select(" max(substr(i_bbk,10,6)) as max from tm_bbk where substr(i_bbk,5,2)='$th' ", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$nobbk  =$terakhir+1;
			settype($nobbk,"string");
			$a=strlen($nobbk);
			while($a<6){
			  $nobbk="0".$nobbk;
			  $a=strlen($nobbk);
			}
			$nobbk  ="BBK-".$thbl."-".$nobbk;
			return $nobbk;
		}else{
			$nobbk  ="000001";
			$nobbk  ="BBK-".$thbl."-".$nobbk;
			return $nobbk;
		}
    }

	function insertbbkheader($ispb,$dspb,$ibbk,$dbbk,$ibbktype,$eremark,$iarea,$isupplier){
    	$this->db->set(
    		array(
				'i_bbk'					=> $ibbk,
				'i_bbk_type'			=> $ibbktype,
				'i_refference_document'	=> $ispb,
				'd_refference_document'	=> $dspb,
				'd_bbk'					=> $dbbk,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea,
				'i_supplier'			=> $isupplier
    		)
    	);
    	
    	$this->db->insert('tm_bbk');
    }

	function insertbbkdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,
							 $vunitprice,$ispb,$ibbk,$eremark,$dspb,$ibbktype,
							 $istore,$istorelocation,$istorelocationbin){
      $th=substr($dspb,0,4);
      $bl=substr($dspb,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_bbk'					=> $ibbk,
				'i_bbk_type'			=> $ibbktype,
				'i_refference_document'	=> $ispb,
				'i_product'				=> $iproduct,
				'i_product_motif'		=> $iproductmotif,
				'i_product_grade'		=> $iproductgrade,
				'e_product_name'		=> $eproductname,
				'n_quantity'			=> $nquantity,
				'v_unit_price'			=> $vunitprice,
				'e_remark'				=> $eremark,
				'd_refference_document'	=> $dspb,
                'e_mutasi_periode'      => $pr
    		)
    	);
    	
    	$this->db->insert('tm_bbk_item');
    }

	function updatebbkheader($ido,$ddo,$ibbk,$dbbk,$ibbktype,$eremark,$iarea,$isupplier){
    	$this->db->set(
    		array(
				'i_refference_document'	=> $ido,
				'd_refference_document'	=> $ddo,
				'd_bbk'					=> $dbbk,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea
    		)
    	);
    	$this->db->where('i_bbk',$ibbk);
		$this->db->where('i_bbk_type',$ibbktype);
		$this->db->where('i_supplier',$isupplier);
    	$this->db->update('tm_bbk');
    }

	function cekbbk($ido,$ibbktype,$iarea,$isupplier,$ddo){
		$query=$this->db->query("select i_bbk from tm_bbk 
								 where trim(i_refference_document)='$ido' and i_bbk_type='$ibbktype' 
								 and i_area='$iarea' and i_supplier='$isupplier' and d_refference_document='$ddo'", false);
		if($query->num_rows()>0){
			foreach($query->result() as $row){
			  $no=$row->i_bbk;
			}
			return $no;
		}
    }
    
	function cekono($iop,$iarea){
		$query= $this->db->query("select i_op from tm_op where i_op_old like '%$iop%' and i_area='$iarea'");
		if($query->num_rows()>0){
			foreach($query->result() as $row){
			  $no=$row->i_op;
			}
			return $no;
		}
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
    function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,
													$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak){
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
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_pembelian=n_mutasi_pembelian+$qdo, n_saldo_akhir=n_saldo_akhir+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin',
																	'$emutasiperiode',0,$qdo,0,0,0,0,0,$qdo,0,'f')
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
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=$q_ak+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }

    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo){
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin',
								'$eproductname',$qdo, 't'
                                )
                              ",false);
    }
}

/* End of file Mmaster.php */
