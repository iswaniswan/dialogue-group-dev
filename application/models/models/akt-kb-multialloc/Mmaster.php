<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($from,$to,$i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_kb, a.d_kb, b.e_area_name, a.v_kb, a.v_sisa, '$i_menu' as i_menu
							from tm_kb a, tr_area b
							where a.i_area=b.i_area and a.f_kb_cancel='false'
							and a.d_kb >= to_date('$from','dd-mm-yyyy')
							and a.d_kb <= to_date('$to','dd-mm-yyyy')
							and a.v_sisa>0
							and a.i_coa like '%210-1%'",false);
		$datatables->add('action', function ($data) {
			$ikb    	= trim($data['i_kb']);
			$i_menu     = $data['i_menu'];
			$data       = '';
			if(check_role($i_menu, 3)){
				$data .= "<a href=\"#\" onclick='show(\"akt-kb-multialloc/cform/edit/$ikb\",\"#main\"); return false;'>&nbsp;&nbsp;<i class='fa fa-pencil'></i></a>";
			}
			return $data;
		});
		$datatables->edit('d_kb', function ($data) {
			$d_kb = $data['d_kb'];
			if($d_kb == ''){
				return '';
			}else{
				return date("d-m-Y", strtotime($d_kb));
			}
		});
		
		$datatables->hide('i_menu');
		return $datatables->generate();
	}

	function cek_kb($id){
		$this->db->select('*');
		$this->db->from('tm_kb');
        $this->db->where('i_kb', $id);
        return $this->db->get();
	}

	function insertheader($ialokasi,$ikb,$isupplier,$dalokasi,$vjumlahx,$vlebih){
    	$query  = $this->db->query("SELECT current_timestamp as c");
    	$row    = $query->row();
    	$dentry = $row->c;
    	$this->db->query("insert into tm_alokasi_kb (i_alokasi,i_kb,i_supplier,d_alokasi,v_jumlah,v_lebih,d_entry)
                          values
                          ('$ialokasi','$ikb','$isupplier','$dalokasi',$vjumlahx,$vlebih,'$dentry')");
	}
	  
  	function inserttransheader($ireff,$iarea,$egirodescription,$fclose,$dbukti ){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $egirodescription=str_replace("'","''",$egirodescription);
  	  $this->db->query("insert into tm_jurnal_transharian 
  	           			(i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
  	                	values
  	             		('$ireff','$iarea','$dentry','$egirodescription','$fclose','$dbukti','$dbukti')");
	  }
	  
  	function inserttranskredit($ikb,$iarea,$dalokasi){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $this->db->query("insert into tm_jurnal_transharian
  	           			(i_refference, i_area, d_entry,d_refference, d_mutasi)
  	                	values
  	             		('$ikb','00','$dalokasi','$dalokasi','$dalokasi')");
  	}

  	function inserttransdebet($ikbank,$iarea,$dalokasi,$icoabank){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $this->db->query("insert into tm_jurnal_transharian
  	           			(i_refference, i_area, d_entry,d_refference, d_mutasi, i_coa_bank)
  	                	values
  	             		('$ikbank','00','$dalokasi','$dalokasi','$dalokasi','$icoabank')");
	}
	  
  	function insertgldebet($acckredit,$ireff,$namadebet,$fdebet,$vjumlah,$dalokasi,$iarea,$egirodescription){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $this->db->query("insert into tm_general_ledger
  	           			(i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
  	                	values
  	             		('$ireff','$acckredit','$dalokasi','$namadebet','$fdebet',$vjumlah,'$iarea','$dalokasi','$egirodescription','$dentry')");
	}
	  
  	function insertglkredit($accdebet,$ireff,$namakredit,$fdebet,$vjumlah,$dalokasi,$iarea,$egirodescription){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $this->db->query("insert into tm_general_ledger
  	           			(i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry)
  	                	values
  	             		('$ireff','$accdebet','$dalokasi','$namakredit','$fdebet',$vjumlah,'$iarea','$dalokasi','$egirodescription','$dentry')");
	  }
	  
  	function inserttransitemkredit($acckredit,$ireff,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dalokasi){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $this->db->query("insert into tm_jurnal_transharianitem
  	           			(i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry)
  	                	values
  	             		('$acckredit','$ireff','$namakredit','$fdebet','$fposting','$vjumlah','$dalokasi','$dalokasi','$dentry')");
	}
	  
  	function inserttransitemdebet($accdebet,$ireff,$namadebet,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dalokasi){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $this->db->query("insert into tm_jurnal_transharianitem
  	           			(i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry)
  	                	values
  	             		('$accdebet','$ireff','$namadebet','$fdebet','$fposting','$vjumlah','$dalokasi','$dalokasi','$dentry')");
	}
	  
	function namaacc($icoa){
    	$this->db->select(" e_coa_name from tr_coa where i_coa='$icoa' ",false);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0){
    	  foreach($query->result() as $tmp){
    	    $xxx=$tmp->e_coa_name;
    	  }
    	  return $xxx;
    	}
	}
	
	function updatekasbesar($ikb,$isupplier,$pengurang){
      $this->db->query("update tm_kb set v_sisa=v_sisa-$pengurang where i_kb='$ikb' ");
	}
	
   function updategiro($group,$iarea,$igiro,$pengurang,$asal){
      $this->db->query("update tm_giro set v_sisa=v_sisa-$pengurang+$asal, f_giro_use='t'
                    where i_giro='$igiro' and (i_area='$iarea' or i_customer in(select i_customer from tr_customer_groupbayar where i_customer_groupbayar='$group'))");
	}
	
   function updateku($group,$iarea,$igiro,$pengurang,$asal,$nkuyear){
      $this->db->query("update tm_kum set v_sisa=v_sisa-$pengurang+$asal
                        where i_kum='$igiro' and (i_area='$iarea' or i_customer in(select i_customer from tr_customer_groupbayar where
                        i_customer_groupbayar='$group')) and n_kum_year='$nkuyear'");
	}
	
	function updatelebihbayar($group,$iarea,$egirobank,$pengurang,$asal){
      $this->db->query("update tm_pelunasan_lebih set v_lebih=0
                        where i_pelunasan='$egirobank' and (i_area='$iarea' or i_customer in(select i_customer from tr_customer_groupbayar where
                        i_customer_groupbayar='$group'))");
	}
	
  	function updatesaldo($group,$icustomer,$pengurang){
      $this->db->query("update tr_customer_groupar set v_saldo=v_saldo-$pengurang
                        where i_customer='$icustomer' and i_customer_groupar='$group'");
	}
	
   function insertdetail($ialokasi,$ikb,$isupplier,$idtap,$ddtap,$vjumlah,$vsisa,$i,$eremark){
    $tmp=$this->db->query(" select i_alokasi from tm_alokasi_kb_item
                            where i_alokasi='$ialokasi' and i_supplier='$isupplier' and i_nota='$idtap' and i_kb='$ikb'", false);
    if($tmp->num_rows()>0){
      $this->db->query("update tm_alokasi_kb_item set d_nota='$ddtap',v_jumlah=$vjumlah,v_sisa=$vsisa,n_item_no=$i,
                        e_remark='$eremark'
                        where i_alokasi='$ialokasi' and i_supplier='$isupplier' and i_nota='$idtap' and i_kb='$ikb' 
                        ");
    }else{
        $this->db->query("insert into tm_alokasi_kb_item
                      	( i_alokasi,i_kb,i_supplier,i_nota,d_nota,v_jumlah,v_sisa,n_item_no,e_remark)
                      	values
                      	('$ialokasi','$ikb','$isupplier','$idtap','$ddtap',$vjumlah,$vsisa,$i,'$eremark')");
    }
  	}

  	function updatenota($idtap,$isupplier,$vsisa){
      $this->db->query("update tm_dtap set v_sisa=v_sisa-$vsisa where i_dtap='$idtap' and i_supplier='$isupplier'");
	}

	function bacagiro($icustomer,$iarea,$num,$offset,$group,$dbukti){
		$this->db->select(" a.* from tm_giro a, tr_customer_groupar b
					   where b.i_customer_groupar='$group' and a.i_customer=b.i_customer
					   and (a.f_giro_tolak='f' and a.f_giro_batal='f') and a.v_sisa>0 and a.v_sisa=a.v_jumlah
					   and not a.d_giro_cair isnull and a.d_giro_cair<='$dbukti'
					   order by a.i_giro,a.i_customer ",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		   return $query->result();
		}
	}

	function carisaldo($icoa,$iperiode){
    	$query = $this->db->query("select * from tm_coa_saldo where i_coa='$icoa' and i_periode='$iperiode'");
    	if ($query->num_rows() > 0)
    	{
    	  $row = $query->row();
    	  return $row;
    	} 
	  }
	  
	function carigiro($cari,$icustomer,$iarea,$num,$offset,$group,$dbukti){
		$this->db->select(" a.* from tm_giro a, tr_customer_groupar b
							where b.i_customer_groupar='$group' and a.i_customer=b.i_customer
							and (a.f_giro_tolak='f' and a.f_giro_batal='f') and a.v_sisa>0 and a.v_sisa=a.v_jumlah
              				and (upper(a.i_giro) like '%$cari%') and not a.d_giro_cair isnull and a.d_giro_cair<='$dbukti'
							order by a.i_giro,a.i_customer ",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}	
	}

	function bacatunai($icustomer,$iarea,$num,$offset,$group,$dbukti){

		$coa='111.3'.$iarea;
		$this->db->select("a.*, c.d_rtunai, e.e_bank_name, e.i_coa, c.i_bank
						   from tm_tunai a, tr_customer_groupar b, tm_rtunai c, tm_rtunai_item d, tr_bank e
						   where b.i_customer_groupar='$group' and a.i_customer=b.i_customer and a.i_area='$iarea'
						   and c.i_rtunai=d.i_rtunai and c.i_area=d.i_area and a.i_area=d.i_area_tunai
						   and a.i_tunai=d.i_tunai and not c.i_cek isnull and a.d_tunai<='$dbukti'
						   and a.v_sisa>0 and a.v_sisa=a.v_jumlah and c.i_bank=e.i_bank
						   and a.f_tunai_cancel='f' and c.f_rtunai_cancel='f'
						   ",false)->limit($num,$offset);
		 $query = $this->db->get();
		 if ($query->num_rows() > 0){
			return $query->result();
		 }
	  }
	function caritunai($cari,$icustomer,$iarea,$num,$offset,$group,$dbukti){
	 	$coa='111.3'.$iarea;
		$this->db->select("a.*, c.d_rtunai, e.e_bank_name, e.i_coa, c.i_bank
					 from tm_tunai a, tr_customer_groupar b, tm_rtunai c, tm_rtunai_item d, tr_bank e
					 where b.i_customer_groupar='$group' and a.i_customer=b.i_customer and a.i_area='$iarea'
					 and c.i_rtunai=d.i_rtunai and c.i_area=d.i_area and a.i_area=d.i_area_tunai
					 and a.i_tunai=d.i_tunai and not c.i_cek isnull and a.d_tunai<='$dbukti'
					 and a.v_sisa>0 and a.v_sisa=a.v_jumlah and c.i_bank=e.i_bank
					 and a.f_tunai_cancel='f' and c.f_rtunai_cancel='f' and (upper(a.i_tunai) like '%$cari%')
					 ",false)->limit($num,$offset);
	   $query = $this->db->get();
	   if ($query->num_rows() > 0){
		   return $query->result();
	   }
	}

	function updatetunai($group,$iarea,$igiro,$pengurang,$asal){
		 $this->db->query("update tm_tunai set v_sisa=v_sisa-$pengurang+$asal
					      where i_tunai='$igiro' and (i_area='$iarea' or i_customer in(select i_customer from tr_customer_groupbayar where i_customer_groupbayar='$group'))");
	}

	function bacaku($icustomer,$iarea,$num,$offset,$group,$dbukti){
		$this->db->select("a.* from tm_kum a, tr_customer_groupar b
						   where b.i_customer_groupar='$group' and a.i_customer=b.i_customer and a.d_kum<='$dbukti'
						   and a.v_sisa>0 and a.v_sisa=a.v_jumlah and a.f_close='f' and a.f_kum_cancel='f'
						   order by a.i_kum,a.i_customer",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		   return $query->result();
		}
	 }
	function cariku($cari,$icustomer,$iarea,$num,$offset,$group,$dbukti){
		$this->db->select(" a.* from tm_kum a, tr_customer_groupar b
						where b.i_customer_groupar='$group' and a.i_customer=b.i_customer
						and a.v_sisa>0 and a.v_sisa=a.v_jumlah and a.f_close='f' and a.f_kum_cancel='f'
					    and (upper(a.i_kum) like '%$cari%') and a.d_kum<='$dbukti'
						order by a.i_kum,a.i_customer",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		  return $query->result();
		}	
	}

	function bacaku2($icustomer,$iarea,$num,$offset,$group){
		$this->db->select(" a.* from tm_kum a
				 where a.i_customer='$icustomer'
				 and a.i_area='$iarea'
				 and a.v_sisa>0
				 and a.f_close='f'
				 order by a.i_kum,a.i_customer",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		   return $query->result();
		}
	}

	function bacagirocek($num,$offset,$area){
	  	if($area=='00'||$area=='PB'){
			$this->db->select(" * from tr_jenis_bayar order by i_jenis_bayar ",FALSE)->limit($num,$offset);
	  	}else{
			$this->db->select(" * from tr_jenis_bayar where i_jenis_bayar<>'05' order by i_jenis_bayar ",FALSE)->limit($num,$offset);
	  	}
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		   return $query->result();
		}
	}	

	function runningnumberpl($iarea,$thbl,$idtapx){
	   $th   = substr($thbl,0,4);
	   $asal=$thbl;  
	   $thbl=substr($thbl,2,2).substr($thbl,4,2);
	   $this->db->select(" n_modul_no as max from tm_dgu_no
						 where i_modul='AK'
						 and substr(e_periode,1,4)='$th'
						 and i_area='$iarea' for update", false);
	   $query = $this->db->get();
	   if ($query->num_rows() > 0){
		  foreach($query->result() as $row){
			$terakhir=$row->max;
		  }
		  $noal  =$terakhir+1;
		  $this->db->query(" update tm_dgu_no
						   set n_modul_no=$noal
						   where i_modul='AK'
						   and substr(e_periode,1,4)='$th'
						   and i_area='$iarea'", false);
		  settype($noal,"string");
		  $a=strlen($noal);
		  while($a<5){
			$noal="0".$noal;
			$a=strlen($noal);
		  }
		  $noal  ="AK-".$thbl."-".$noal;
		  return $noal;
	   }else{
		  $noal  ="00001";
		  $noal  ="AK-".$thbl."-".$noal;
		  $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
						  values ('AK','$iarea','$asal',1)");
		  return $noal;
	   }
	}
}	

/* End of file Mmaster.php */
