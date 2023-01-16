<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function bacadetailap($iap,$isupplier,$iarea){
	    $this->db->select("a.i_ap, a.d_ap, a.i_op, c.i_product, c.e_product_name, b.e_supplier_name, c.n_receive, c.v_product_mill
						   from tm_ap a, tr_supplier b, tm_ap_item c
						   where a.i_supplier=b.i_supplier and a.i_supplier=c.i_supplier
						   and a.i_ap not in
						   (select d.i_do from tm_dtap_item d, tm_dtap e
						   where  d.i_dtap=e.i_dtap and d.i_area=e.i_area and d.i_supplier=e.i_supplier
						   and a.i_area=d.i_area and a.i_supplier=d.i_supplier and a.i_op=d.i_op and e.f_dtap_cancel='f')
						   and a.i_ap=c.i_ap
						   and a.f_ap_cancel='f'
						   and a.i_area='$iarea'
						   and a.i_supplier='$isupplier'
                           and (upper(a.i_ap) like '%$iap%')",false);
		return $this->db->get();
    }

    function bacaperiode(){
    	return $this->db->get("tm_periode");
    }

    function baca($idt,$iarea,$isupplier,$year){
		$this->db->select(" * from tm_dtap
							inner join tr_area on (tm_dtap.i_area=tr_area.i_area)
							inner join tr_supplier on (tm_dtap.i_supplier=tr_supplier.i_supplier)
							where tm_dtap.i_dtap ='$idt' and tm_dtap.i_area='$iarea' and tm_dtap.i_supplier='$isupplier' and n_dtap_year = '$year'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
	}
	
    function bacadetail($idt,$iarea,$isupplier,$year){
		$this->db->select("	a.*, b.e_product_motifname from tm_dtap_item a
						    left join tm_do on (tm_do.i_do=a.i_do and tm_do.i_supplier=a.i_supplier)
							inner join tr_supplier on (a.i_supplier=tr_supplier.i_supplier)
							left join tm_op on (a.i_op=tm_op.i_op)
							inner join tr_product_motif b on (b.i_product=a.i_product and b.i_product_motif=a.i_product_motif)
						    where a.i_dtap = '$idt' and a.i_area='$iarea' and a.i_supplier='$isupplier' and n_dtap_year = '$year'
						    order by a.i_dtap", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function insertheader($idtap,$iarea,$isupplier,$ipajak,$ddtap,$dduedate,$dpajak,
						  $fpkp,$ndiscount,$vgross,$vdiscount,$vppn,$vnetto,$thbl){
		if($dpajak!=''){
			$this->db->set(
				array(
						'i_dtap'		=> $idtap,
						'i_area' 		=> $iarea,
						'i_supplier'	=> $isupplier,
						'i_pajak' 		=> $ipajak,
						'd_dtap' 		=> $ddtap,
						'd_due_date'	=> $dduedate,
						'd_pajak' 		=> $dpajak,
						'f_pkp' 		=> $fpkp,
						'n_discount'	=> $ndiscount,
						'v_gross' 		=> $vgross,
						'v_discount'	=> $vdiscount,
						'v_ppn' 		=> $vppn,
						'v_netto' 		=> $vnetto,
						'v_sisa'		=> $vnetto,
						'f_dtap_cancel' => 'f',
            			'n_dtap_year' 	=> $thbl
				)
			);
		}else{
	    	$this->db->set(
    		array(
					'i_dtap'	=> $idtap,
					'i_area' 	=> $iarea,
					'i_supplier'=> $isupplier,
					'i_pajak' 	=> $ipajak,
					'd_dtap' 	=> $ddtap,
					'd_due_date'=> $dduedate,
					'f_pkp' 	=> $fpkp,
					'n_discount'=> $ndiscount,
					'v_gross' 	=> $vgross,
					'v_discount'=> $vdiscount,
					'v_ppn' 	=> $vppn,
					'v_netto' 	=> $vnetto,
					'v_sisa'	=> $vnetto
    		)
	    	);
		}    	
    	$this->db->insert('tm_dtap');
    }

    function insertdetail($idtap,$ido,$iop,$isupplier,$iarea,$iproduct,$iproductmotif,
						  $eproductname,$ddtap,$njumlah,$vpabrik,$diskon,$ddo,$j,$thbl){
		$kotor  = $njumlah*$vpabrik;
		$bersih = $kotor-$diskon;
    	$this->db->set(
    		array(
					'i_dtap'	    	=> $idtap,
					'i_do'		    	=> $ido,
					'i_op'		    	=> $iop,
					'd_do'    			=> $ddo,
					'i_supplier'	  	=> $isupplier,
					'i_area' 			=> $iarea,
					'i_product'    		=> $iproduct,
					'i_product_motif'	=> $iproductmotif,
					'e_product_name'	=> $eproductname,
					'd_dtap'			=> $ddtap,
					'n_jumlah'		  	=> $njumlah,
					'v_pabrik'		  	=> $vpabrik,
					'v_gross'			=> $kotor,
					'v_discount'  		=> $diskon,
					'v_netto'			=> $bersih,
          			'n_item_no'       	=> $j,
          			'n_dtap_year'     	=> $thbl
    		)
    	);
    	$this->db->insert('tm_dtap_item');
    }

    function insertdetailkhusus($idtap,$ido,$iop,$isupplier,$iarea,$iproduct,$iproductmotif,
						  $eproductname,$ddtap,$njumlah,$vpabrik,$diskon,$j,$thbl)
    {
		$kotor  = $njumlah*$vpabrik;
		$bersih = $kotor-$diskon;
    	$this->db->set(
    		array(
					'i_dtap'	    		=> $idtap,
					'i_do'		    		=> $ido,
					'i_op'		    		=> $iop,
					'i_supplier'	  	=> $isupplier,
					'i_area' 			    => $iarea,
					'i_product'    		=> $iproduct,
					'i_product_motif'	=> $iproductmotif,
					'e_product_name'	=> $eproductname,
					'd_dtap'			    => $ddtap,
					'n_jumlah'		  	=> $njumlah,
					'v_pabrik'		  	=> $vpabrik,
					'v_gross'			    => $kotor,
					'v_discount'  		=> $diskon,
					'v_netto'			    => $bersih,
          			'n_item_no'       => $j,
          			'n_dtap_year'     => $thbl
    		)
    	);
    	$this->db->insert('tm_dtap_item');
	}
	
    function deleteheader($idtap,$iarea,$isupplier){
		$this->db->query("delete from tm_dtap where i_dtap='$idtap' and i_area='$iarea' and i_supplier='$isupplier'");
    }

	public function deletedetail( $idtap,$ido,$iop,$isupplier,$iarea,$iproduct,$iproductmotif,$njumlah,$vpabrik,$vppn,$ndiskon,$i){
		$kotor	= $njumlah*$vpabrik;
		$diskon	= $ndiskon;
		$bersih = $kotor-$diskon;    
		if( ($vppn==0) || ($vppn=='') ){
			$vppn		= 0;
			$pengurang	= 0;
		}else{
			$pengurang=$bersih*0.1;
			$vppn=$vppn-$pengurang; 
		}
		$bersih	= $bersih+$pengurang;
		$this->db->query("update tm_dtap set v_gross=v_gross-$kotor, v_discount=v_discount-$diskon,
		v_ppn=$vppn, v_netto=v_netto-$bersih, v_sisa=v_sisa-$bersih
		where i_dtap='$idtap' and i_area='$iarea' and i_supplier='$isupplier'");
		$this->db->query("DELETE FROM tm_dtap_item 
		WHERE i_dtap='$idtap' and i_area='$iarea' and i_do='$ido' 
		and i_op='$iop' and i_product='$iproduct' and i_product_motif='$iproductmotif'");
	}
	
    public function delete($iap,$isupplier,$iop) {
		$this->db->query('DELETE FROM tm_ap WHERE i_ap=\''.$iap.'\' and i_supplier=\''.$isupplier.'\'');
		$this->db->query('DELETE FROM tm_ap_item WHERE i_ap=\''.$iap.'\' and i_supplier=\''.$isupplier.'\'');
		$this->db->query("update tm_op set f_op_close='f' WHERE i_op='$iop' and i_supplier='G0000' ",False);
	}
	
    function bacado($periode,$supplier,$area,$num,$offset){
		  if($offset=='') $offset=0;
	      $query=$this->db->query(" select a.i_do, a.d_do, a.i_op, b.e_supplier_name, sum(c.n_deliver*c.v_product_mill) as v_do_gross,
                                  b.e_supplier_address, b.e_supplier_city
                                  from tm_do a, tr_supplier b, tm_do_item c
                                  where a.i_supplier=b.i_supplier and a.i_supplier=c.i_supplier
                                  and a.i_do not in
                                  (select d.i_do from tm_dtap_item d, tm_dtap e
                                  where to_char(d.d_dtap,'yyyymm')='$periode' and d.i_dtap=e.i_dtap and d.i_area=e.i_area and d.i_supplier=e.i_supplier
                                  and a.i_area=d.i_area and a.i_supplier=d.i_supplier and a.i_op=d.i_op and a.d_do=d.d_do and e.f_dtap_cancel='f')
                                  and a.i_do=c.i_do
                                  and a.f_do_cancel='f'
                                  and a.i_area='$area'
                                  and a.i_supplier='$supplier'
                                  and to_char(a.d_do,'yyyymm')='$periode'
                                  group by a.i_do, a.d_do, a.i_op, a.i_supplier, b.e_supplier_name, b.e_supplier_address, b.e_supplier_city
                                  order by a.i_do
                                  limit $num offset $offset",false);
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
	}
	
    function bacaop($num,$offset){
		  $this->db->select(" a.*, 'Lain-lain' as e_supplier_name, c.e_area_name 
							  from tm_op a, tr_area c 
							  where a.i_area=c.i_area and a.i_supplier='G0000'
							  and a.f_op_cancel='f'
							  and a.f_op_close='f'",false)->limit($num,$offset);
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
	
    function bacasupplier($num,$offset,$cari){
		$this->db->select(" * from tr_supplier where (upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%') order by i_supplier",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}    
	
    function carisupplier($cari,$num,$offset){
		$this->db->select(" * from tr_supplier where (upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%') order by i_supplier",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function cariproduct($cari,$num,$offset){
		if($offset=='')
			$offset=0;
		$query=$this->db->query("  	select a.i_product as kode, 
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
	
    function cariop($cari,$num,$offset){
		$this->db->select(" * from tm_op where (upper(i_op) like '%$cari%' or upper(i_supplier) like '%$cari%'
							or upper(e_supplier_name) like '%$cari%') and a.i_supplier='G0000'
							and a.f_op_cancel='f' and substr(i_reff,1,4)='SPMB'
							and a.f_op_close='f'",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
	function runningnumber(){
    	$query 	= $this->db->query("SELECT to_char(current_timestamp,'yymm') as c");
		$row   	= $query->row();
		$thbl	= $row->c;
		$th		= substr($thbl,0,2);
		$this->db->select(" max(substr(i_op,9,4)) as max from tm_ap 
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
	
	function runningnumberdt($iarea){
	   	$query 	= $this->db->query("SELECT to_char(current_timestamp,'yy') as c");
		$row   	= $query->row();
		$th		= $row->c;
		$this->db->select(" max(substr(i_dtap,1,4)) as max from tm_dtap where substr(i_dtap,6,2)='$th' and i_area='$iarea'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$nodt  =$terakhir+1;
			settype($nodt,"string");
			$a=strlen($nodt);
			while($a<4){
			  $nodt="0".$nodt;
			  $a=strlen($nodt);
			}
			$nodt  =$nodt."-".$th;
			return $nodt;
		}else{
			$nodt  ="0001";
			$nodt  =$nodt."-".$th;
			return $nodt;
		}
	}
	
  	function bacaharga($cari,$num,$offset,$iproduct){
	  $this->db->select(" * from tr_harga_beli where i_product='$iproduct'
        				and (upper(i_product) like '%$cari%' or upper(e_product_name) like '%$cari%')  
                        order by i_price_group",false)->limit($num,$offset);
	  $query = $this->db->get();
	  if ($query->num_rows() > 0){
		  return $query->result();
	  }
	  }
	  
    function bacadotirai($cari,$periode,$supplier,$area,$num,$offset,$ddtap){
		  if($offset=='') $offset=0;
        $query=$this->db->query(" select a.i_do, a.d_do, a.i_op, b.e_supplier_name, sum(c.n_deliver*c.v_product_mill) as v_do_gross,
                                  b.e_supplier_address, b.e_supplier_city
                                  from tm_do a, tr_supplier b, tm_do_item c
                                  where a.i_supplier=b.i_supplier
                                  and a.i_do=c.i_do and a.i_supplier=c.i_supplier
                                  and a.f_do_cancel='f'
                                  and a.i_supplier='$supplier'
                                  and to_char(a.d_do,'yyyymm')='$periode'
                                  and a.i_do not in
                                  (select d.i_do from tm_dtap_item d, tm_dtap e
                                  where to_char(d.d_dtap,'yyyymm')='$periode' and d.i_dtap=e.i_dtap and d.i_area=e.i_area and d.i_supplier=e.i_supplier
                                  and a.i_supplier=d.i_supplier and a.i_op=d.i_op and a.d_do=d.d_do and e.f_dtap_cancel='f')
                                  and (upper(a.i_do) like '%$cari%' or upper(a.i_op) like '%$cari%')
                                  group by a.i_do, a.d_do, a.i_op, a.i_supplier, b.e_supplier_name, b.e_supplier_address, b.e_supplier_city
                                  order by a.i_do
                                  limit $num offset $offset",false);
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
	}
	
    function caridotirai($cari,$supplier,$area,$num,$offset,$ddtap){
		if($offset=='') $offset=0;
	    $query=$this->db->query(" select a.i_do, a.d_do, a.i_op, b.e_supplier_name, sum(c.n_deliver*c.v_product_mill) as v_do_gross,
                                  b.e_supplier_address, b.e_supplier_city
                                  from tm_do a, tr_supplier b, tm_do_item c
                                  where a.i_supplier=b.i_supplier
                                  and a.i_do=c.i_do
                                  and a.f_do_cancel='f'
                                  and a.d_do <= to_date('$ddtap','dd-mm-yyyy')
                                  and a.i_supplier='$supplier'
                                  and (upper(a.i_do) like '%$cari%')
                                  group by a.i_do, a.d_do, a.i_op, b.e_supplier_name, b.e_supplier_address, b.e_supplier_city
                                  order by a.i_do
                                  limit $num offset $offset",false);
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
	}
	
##########Posting
	function namaacc($icoa)
    {
		$this->db->select(" e_coa_name from tr_coa where i_coa='$icoa' ",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $tmp)			
			{
				$xxx=$tmp->e_coa_name;
			}
			return $xxx;
		}
	}
	
	function inserttransheader(	$inota,$iarea,$eremark,$fclose,$dnota )
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$eremark=str_replace("'","''",$eremark);
		$this->db->query("insert into tm_jurnal_transharian 
						 (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
						  	  values
					  	 ('$inota','$iarea','$dentry','$eremark','$fclose','$dnota','$dnota')");
	}

	function inserttransitemdebet($accdebet,$ipelunasan,$namadebet,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dnota){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namadebet=str_replace("'","''",$namadebet);
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry, i_area)
						  	  values
					  	 ('$accdebet','$ipelunasan','$namadebet','$fdebet','$fposting','$vjumlah','$dnota','$dnota','$dentry','$iarea')");
	}

	function inserttransitemkredit($acckredit,$ipelunasan,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dnota){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namakredit=str_replace("'","''",$namakredit);
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_area)
						  	  values
					  	 ('$acckredit','$ipelunasan','$namakredit','$fdebet','$fposting','$vjumlah','$dnota','$dnota','$dentry','$iarea')");
	}

	function insertgldebet($accdebet,$ipelunasan,$namadebet,$fdebet,$iarea,$vjumlah,$dnota,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namadebet=str_replace("'","''",$namadebet);
		$eremark=str_replace("'","''",$eremark);
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ipelunasan','$accdebet','$dnota','$namadebet','$fdebet',$vjumlah,'$iarea','$dnota','$eremark','$dentry')");
	}

	function insertglkredit($acckredit,$ipelunasan,$namakredit,$fdebet,$iarea,$vjumlah,$dnota,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namakredit=str_replace("'","''",$namakredit);
		$eremark=str_replace("'","''",$eremark);
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ipelunasan','$acckredit','$dnota','$namakredit','$fdebet','$vjumlah','$iarea','$dnota','$eremark','$dentry')");
	}

	function updatenotaacc($inota,$iarea){
		$this->db->query("update tm_nota set f_posting='t' where i_nota='$inota' and i_area='$iarea' and f_nota_koreksi='f'");
		$this->db->query("update tm_notakoreksi set f_posting='t' where i_nota='$inota' and i_area='$iarea'");
	}

	function updatesaldodebet($accdebet,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet+$vjumlah, v_saldo_akhir=v_saldo_akhir+$vjumlah
						  where i_coa='$accdebet' and i_periode='$iperiode'");
	}

	function updatesaldokredit($acckredit,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vjumlah, v_saldo_akhir=v_saldo_akhir-$vjumlah
						  where i_coa='$acckredit' and i_periode='$iperiode'");
	}

	function updatedo($ido,$idtap,$iop,$isupplier){
		$this->db->query("update tm_do set i_faktur='$idtap' where i_do='$ido' and i_op='$iop' and i_supplier='$isupplier'");
	}
    
}

/* End of file Mmaster.php */
