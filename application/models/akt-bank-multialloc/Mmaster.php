<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($from,$to,$ibank,$i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_kbank, a.d_bank, b.e_area_name, a.v_bank, a.v_sisa, c.i_bank, '$i_menu' as i_menu
		 					from tm_kbank a, tr_area b, tr_bank c
		 					where a.i_area=b.i_area and a.f_kbank_cancel='false'
		 					and a.d_bank >= to_date('$from','dd-mm-yyyy')
		 					and a.d_bank <= to_date('$to','dd-mm-yyyy')
		 					and c.i_bank='$ibank'
		 					and a.i_coa_bank=c.i_coa
		 					and a.v_sisa>0
		 					and a.i_coa like '%210-1%'
							and a.i_kbank like '%BK-%'",false);
		$datatables->add('action', function ($data) {
			$ikbank    = trim($data['i_kbank']);
			$ibank    = trim($data['i_bank']);
			$i_menu     = $data['i_menu'];
			$data       = '';
			if(check_role($i_menu, 3)){
				$data .= "<a href=\"#\" onclick='show(\"akt-bank-multialloc/cform/edit/$ikbank/$ibank\",\"#main\"); return false;'>&nbsp;&nbsp;<i class='fa fa-pencil'></i></a>";
			}
			return $data;
		});
		$datatables->hide('i_menu');
		$datatables->hide('i_bank');
        return $datatables->generate();
	}

	function cek_kbank($id){
		$this->db->select('a.*, b.e_bank_name');
		$this->db->from('tm_kbank a');
      $this->db->join('tr_bank b','a.i_coa_bank=b.i_coa');
      $this->db->where('i_kbank', $id);
        return $this->db->get();
	}

	function insertheader($ialokasi,$ikbank,$isupplier,$dalokasi,$ebankname,$vjumlah,$vlebih,$icoabank){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $this->db->query("insert into tm_alokasi_bk (i_alokasi,i_kbank,i_supplier,d_alokasi,e_bank_name,v_jumlah,v_lebih,d_entry,i_coa_bank)
                     values
                     ('$ialokasi','$ikbank','$isupplier','$dalokasi','$ebankname',$vjumlah,$vlebih,'$dentry','$icoabank')");
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
	  
  	function inserttranskredit($ikbank,$iarea,$dalokasi,$icoabank){
  	  $query  = $this->db->query("SELECT current_timestamp as c");
  	  $row    = $query->row();
  	  $dentry = $row->c;
  	  $this->db->query("insert into tm_jurnal_transharian
  	           			(i_refference, i_area, d_entry,d_refference, d_mutasi, i_coa_bank)
  	                	values
  	             		('$ikbank','00','$dalokasi','$dalokasi','$dalokasi','$icoabank')");
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

  function insertgldebet($acckredit,$ireff,$namadebet,$fdebet,$vjumlah,$dalokasi,$iarea,$icoabank,$egirodescription){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $this->db->query("insert into tm_general_ledger
             		(i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry,i_coa_bank)
                 	values
               		('$ireff','$acckredit','$dalokasi','$namadebet','$fdebet',$vjumlah,'$iarea','$dalokasi','$egirodescription','$dentry','$icoabank')");
  }

  function insertglkredit($accdebet,$ireff,$namakredit,$fdebet,$vjumlah,$dalokasi,$iarea,$icoabank,$egirodescription){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $this->db->query("insert into tm_general_ledger
             		(i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry,i_coa_bank)
                  	values
               		('$ireff','$accdebet','$dalokasi','$namakredit','$fdebet',$vjumlah,'$iarea','$dalokasi','$egirodescription','$dentry','$icoabank')");
  }

  function inserttransitemkredit($acckredit,$ireff,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dalokasi,$icoabank){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $this->db->query("insert into tm_jurnal_transharianitem
             		(i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry,i_coa_bank)
                  	values
               		('$acckredit','$ireff','$namakredit','$fdebet','$fposting','$vjumlah','$dalokasi','$dalokasi','$dentry',$icoabank)");
  }
  function inserttransitemdebet($accdebet,$ireff,$namadebet,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dalokasi,$icoabank){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $this->db->query("insert into tm_jurnal_transharianitem
             		(i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry,i_coa_bank)
                 	values
               		('$accdebet','$ireff','$namadebet','$fdebet','$fposting','$vjumlah','$dalokasi','$dalokasi','$dentry',$icoabank)");
  }

  function namaacc($icoa){
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
   function jmlasalkn(  $ipl,$idt,$iarea,$ddt){
      $this->db->select("* from tm_pelunasan where i_pelunasan='$ipl' and i_dt='$idt' and i_area='$iarea' and d_dt='$ddt'",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }

   function deleteheader(  $ipl,$idt,$iarea,$ddt){
      $this->db->query("delete from tm_pelunasan where i_pelunasan='$ipl' and i_dt='$idt' and i_area='$iarea' and d_dt='$ddt'",false);
   }

   function updatebank($ikbank,$icoabank,$isupplier,$pengurang){
      $this->db->query("update tm_kbank set v_sisa=v_sisa-$pengurang where i_kbank='$ikbank' and i_coa_bank='$icoabank'");
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
	
   function updatekn($group,$iarea,$igiro,$pengurang,$asal){
      $this->db->query("update tm_kn set v_sisa=v_sisa-$pengurang+$asal
                        where i_kn='$igiro' and (i_area='$iarea' or i_customer in(select i_customer from tr_customer_groupbayar
                        where i_customer_groupbayar='$group'))");
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
	
   function insertdetail($ialokasi,$ikbank,$isupplier,$idtap,$ddtap,$vjumlah,$vsisa,$i,$eremark,$icoabank) {
    $tmp=$this->db->query(" select i_alokasi from tm_alokasi_bk_item
                            where i_alokasi='$ialokasi' and i_supplier='$isupplier' and i_nota='$idtap' and i_kbank='$ikbank' 
                            and i_coa_bank='$icoabank'", false);
    if($tmp->num_rows()>0){
      $this->db->query("update tm_alokasi_bk_item set d_nota='$ddtap',v_jumlah=$vjumlah,v_sisa=$vsisa,n_item_no=$i,
                        e_remark='$eremark'
                        where i_alokasi='$ialokasi' and i_supplier='$isupplier' and i_nota='$idtap' and i_kbank='$ikbank' 
                        and i_coa_bank='$icoabank'");
    }else{
        $this->db->query("insert into tm_alokasi_bk_item
                      ( i_alokasi,i_kbank,i_supplier,i_nota,d_nota,v_jumlah,v_sisa,n_item_no,e_remark,i_coa_bank)
                      values
                      ('$ialokasi','$ikbank','$isupplier','$idtap','$ddtap',$vjumlah,$vsisa,$i,'$eremark','$icoabank')");
    }
  }

   function updatedt($idt,$iarea,$ddt,$inota,$vsisa)
    {
      $this->db->query("update tm_dt_item set v_sisa=$vsisa where i_dt='$idt' and i_area='$iarea' and d_dt='$ddt' and i_nota='$inota'");
    }
   function updatenota($idtap,$isupplier,$vsisa)
    {
      $this->db->query("update tm_dtap set v_sisa=v_sisa-$vsisa where i_dtap='$idtap' and i_supplier='$isupplier'");
    }
  function hitungsisadt($idt,$iarea,$ddt)
    {
      $this->db->select(" sum(v_sisa) as v_sisa from tm_dt_item
               where i_area='$iarea'
               and i_dt='$idt' and d_dt='$ddt'
               group by i_dt, i_area",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         foreach($query->result() as $row){
            $jml=$row->v_sisa;
         }
         return $jml;
      }
  }
   function updatestatusdt($idt,$isupplier,$ddt)
    {
      $this->db->query("update tm_dt set f_sisa='f' where i_dt='$idt' and i_area='$iarea' and d_dt='$ddt'");
    }
   function deletedetail($ipl,$idt,$iarea,$inota,$ddt)
    {
      $this->db->query("DELETE FROM tm_pelunasan_item WHERE i_pelunasan='$ipl' and i_area='$iarea' and i_dt='$idt'
                      and i_nota='$inota' and d_dt='$ddt'");
    }
   function bacasupplier($iarea,$num,$offset){
      $this->db->select(" * from tr_supplier where (upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%') order by i_supplier",FALSE)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
   function carisupplier($cari,$isupplier,$num,$offset){
      if($cari=='sikasep'){
        $this->db->select(" * from tr_supplier where (upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%') order by i_supplier ",FALSE)->limit($num,$offset);      
      }else{
        $this->db->select(" * from tr_supplier where (upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%') order by i_supplier ",FALSE)->limit($num,$offset);      
      }
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
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
   function carisaldo($icoa,$iperiode)
  {
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
   function updatetunai($group,$iarea,$igiro,$pengurang,$asal)
    {
      $this->db->query("update tm_tunai set v_sisa=v_sisa-$pengurang+$asal
                    where i_tunai='$igiro' and (i_area='$iarea' or i_customer in(select i_customer from tr_customer_groupbayar where i_customer_groupbayar='$group'))");
    }
##########
   function bacakn($icustomer,$iarea,$num,$offset,$group,$xdbukti){
      $this->db->select(" a.* from tm_kn a, tr_customer_groupbayar b
                     where b.i_customer_groupbayar='$group' and a.i_customer=b.i_customer and a.i_area='$iarea'
                     and a.v_sisa>0 and d_kn<='$xdbukti' and a.f_kn_cancel='f'
                     order by a.i_kn,a.i_customer ",FALSE)->limit($num,$offset);

      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
   function carikn($cari,$icustomer,$iarea,$num,$offset,$group,$xdbukti){
      $this->db->select(" a.* from tm_kn a, tr_customer_groupar b
                               where b.i_customer_groupar='$group' and a.i_customer=b.i_customer and a.v_sisa>0 and d_kn<='$xdbukti'
                        and (upper(i_kn) like '%$cari%') and a.f_kn_cancel='f'
                               order by a.i_kn,a.i_customer ",FALSE)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
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
                        where i_modul='BAO'
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
                          where i_modul='BAO'
                          and substr(e_periode,1,4)='$th'
                          and i_area='$iarea'", false);
         settype($noal,"string");
         $a=strlen($noal);
         while($a<5){
           $noal="0".$noal;
           $a=strlen($noal);
         }
         $noal  ="AO-".$thbl."-".$noal;
         return $noal;
      }else{
         $noal  ="00001";
         $noal  ="AO-".$thbl."-".$noal;
         $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                         values ('BAO','$iarea','$asal',1)");


         return $noal;
      }
    }
   function bacapelunasan($icustomer,$iarea,$num,$offset,$group){
      $this->db->select(" a.i_dt, min(a.v_jumlah) as v_jumlah, min(a.v_lebih) as v_lebih, a.i_area, a.i_pelunasan,
                  a.d_bukti,a.i_dt||'-'||max(substr(a.i_pelunasan,9,2)) as i_pelunasan, a.i_customer
               from tm_pelunasan_lebih a, tr_customer_groupar b
            where b.i_customer_groupar='$group' and a.i_customer=b.i_customer
               and a.v_lebih>0 and a.f_pelunasan_cancel='f'
               group by a.i_dt, a.d_bukti, a.i_area, a.i_customer, a.i_pelunasan ",FALSE)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
   function caripelunasan($cari,$icustomer,$iarea,$num,$offset,$group){
      $this->db->select(" a.i_dt, min(a.v_jumlah) as v_jumlah, min(a.v_lebih) as v_lebih, a.i_area, a.i_pelunasan,
                  a.d_bukti,a.i_dt||'-'||max(substr(a.i_pelunasan,9,2)) as i_pelunasan, a.i_customer
               from tm_pelunasan_lebih a, tr_customer_groupar b
            where b.i_customer_groupar='$group' and a.i_customer=b.i_customer
               and a.v_lebih>0 and a.f_pelunasan_cancel='f'
                and (upper(a.i_pelunasan) like '%$cari%') 
               group by a.i_dt, a.d_bukti, a.i_area, a.i_customer, a.i_pelunasan ",FALSE)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
  function cekpl($iarea,$ipl,$idt){
      $this->db->select(" i_pelunasan from tm_pelunasan where i_pelunasan='$ipl' and i_area='$iarea' and i_dt='$idt'",FALSE);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        $this->db->select(" max(substring(i_pelunasan,9,2)) as no from tm_pelunasan where i_pelunasan like '$idt%' and i_area='$iarea'",FALSE);
        $quer = $this->db->get();
        if ($quer->num_rows() > 0){
          foreach($quer->result() as $tmp){
            $nopl=$tmp->no+1;
            break;
          }
        }
        settype($nopl,"string");
        $a=strlen($nopl);
        while($a<2){
          $nopl="0".$nopl;
          $a=strlen($nopl);
        }
        $nopl  = $idt."-".$nopl.substr($ipl,10,1);
        return $nopl;
      }else{
      return $ipl;
    }
   }
  function bacapl($isupplier,$ialokasi,$ikbank){
     $xkbank=strtoupper($ikbank);
      $xalokasi=strtoupper($ialokasi);
      $this->db->select(" a.*, b.e_supplier_name, e.d_bank
                         from tm_alokasi_bk a
                         inner join tr_supplier b on (a.i_supplier=b.i_supplier)
                         inner join tm_kbank e on (a.i_kbank=e.i_kbank)
                         where
                         upper(a.i_kbank)='$xkbank' and upper(a.i_alokasi)='$xalokasi' and upper(a.i_supplier)='$isupplier'",FALSE);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
   function sisa($isupplier,$ialokasi,$ikbank){
      $this->db->select(" sum(v_sisa)as sisa
                     from tm_kbank
                     where  i_kbank='$ikbank'",FALSE);
      $query = $this->db->get();
      foreach($query->result() as $isi){
         return $isi->sisa;
      }
   }
   function bulat($isupplier,$ialokasi,$ikbank){
      $bulat=0;
      $reff=$ialokasi.'|'.$ikbank;
      $this->db->select(" sum(v_mutasi_debet) as bulat from tm_general_ledger where i_refference='$reff' and i_area='00'",FALSE);
      $query = $this->db->get();
      if($query->num_rows()>0){
        foreach($query->result() as $isi){
           $bulat=$isi->bulat;
        }
      }
      return $bulat;
   }
   function bacadetailpl($isupplier,$ialokasi,$ikbank){
      $this->db->select(" a.*, b.v_sisa as v_sisa_nota, b.v_netto as v_nota
                       from tm_alokasi_bk_item a
                          inner join tm_dtap b on (a.i_nota=b.i_dtap)
                          where a.i_alokasi = '$ialokasi'
                          and a.i_supplier='$isupplier'
                          and a.i_kbank='$ikbank'
                          order by a.i_alokasi,a.i_supplier ",FALSE);
#
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
   function bacanota($isupplier,$num,$offset){
      $this->db->select(" * from tm_dtap where  v_sisa!='0' and i_supplier='$isupplier' order by i_dtap ",FALSE)->limit($num,$offset);
#and a.i_area=c.i_area
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
   function carinota($baris,$isupplier,$num,$offset){
    $this->db->select("* from tm_dtap where i_supplier='$isupplier' and v_sisa!='0' and (upper(i_dtap) like '%$baris%') order by i_dtap ",FALSE)->limit($num,$offset);
#and a.i_area=c.i_area
      $query = $this->db->get();

      if ($query->num_rows() > 0){
         return $query->result();
      }
   }

    function bacabank($cari,$num,$offset){
    $this->db->select(" i_bank, e_bank_name
                        from tr_bank
                        where (upper(i_bank) like '%$cari%' or upper(e_bank_name) like '%$cari%')
                        order by i_bank ",FALSE)->limit($num,$offset);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      return $query->result();
    } 
  }

   function bacaperiode($ibank,$dfrom,$dto,$num,$offset,$cari)
   {
      $this->db->select(" a.i_kbank, a.i_area, a.d_bank, a.v_bank, b.e_area_name, c.e_bank_name, a.i_coa_bank, a.v_sisa 
                                      from tm_kbank a, tr_area b, tr_bank c
                                      where a.i_area=b.i_area and a.f_kbank_cancel='false'
                                      and a.d_bank >= to_date('$dfrom','dd-mm-yyyy')
                                      and a.d_bank <= to_date('$dto','dd-mm-yyyy')
                                      and a.i_coa_bank=c.i_coa
                                      and c.i_bank='$ibank'
                                      and a.v_sisa>0
                                      and a.i_coa like '%210-1%'
                                      and a.i_kbank like '%BK-%'",false)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
   function bacaremark($num,$offset) {
      $this->db->select("* from tr_pelunasan_remark order by i_pelunasan_remark", false)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
   }
}

/* End of file Mmaster.php */
