<?php 
	include ("php/fungsi.php");
	require_once("printipp/PrintIPP.php");
	$isi=$master;
	foreach($isi as $row){
#    echo $row->i_spb.'<br>';
    $cetak='';
		$nor	= str_repeat(" ",5);
		$abn	= str_repeat(" ",12);
		$ab		= str_repeat(" ",9);
		$ipp 	= new PrintIPP();
		$ipp->setHost($host);
		$ipp->setPrinterURI($uri);
		$ipp->setRawText();
		$ipp->unsetFormFeed();
		$ambilprioritas = $this->db->query("select f_prioritas from tr_customer where i_customer = '$row->i_customer' and f_prioritas = 't' ");
		if($ambilprioritas->num_rows() > 0){	
			$prioritas = "PRIORITAS";
		}else{
			$prioritas = " ";
		}
		$cetak.=CHR(18)."\n";
		$cetak.=$nor.$company->name."                                     ".CHR(27).CHR(71).$prioritas.CHR(27).CHR(72)."\n\n";
		$cetak.=$nor." SURAT PEMESANAN BARANG                   No.SPB    :".substr($row->i_spb,9,6)."\n";
		$tmp=explode("-",$row->d_spb);
		$th=$tmp[0];
		$bl=$tmp[1];
		$hr=$tmp[2];
		$dspb=$hr." ".mbulan($bl)." ".$th;
		$cetak.=$nor."       ( SPB )                            Tgl.Pesan :".$dspb."\n";
		$pjg=strlen($nor."Data Pelanggan : ".$row->e_customer_classname);
		$jrk=47-$pjg;
		if ($jrk<0) {
			$jrk = 0;
		}
		$cetak.=$nor."Data Pelanggan : ".$row->e_customer_classname.str_repeat(" ",$jrk)."Kode Sales: ".$row->i_salesman." - ".$row->e_salesman_name."\n";
		$pjg=strlen("( ".$row->i_customer." )".$row->e_customer_name.$nor);
		$jrk=42-$pjg;
		$cetak.=$nor."( ".$row->i_customer." )".$row->e_customer_name.str_repeat(" ",$jrk)."Top : ".$row->n_spb_toplength." hari"." KdHarga : ".$row->i_price_group."\n";
		$cetak.=$nor.$row->e_customer_address."\n";
		$cetak.=$nor.$row->e_customer_city."\n";
		if($row->f_customer_pkp=='t'){
			$cetak.=$nor."N.P.W.P : ".$row->e_customer_pkpnpwp.CHR(15)."\n\n";
		}else{
			$cetak.=$nor.''.CHR(15)."\n\n";
		}
		$cetak.=$ab.str_repeat("=",115)."\n";
		$cetak.=$ab."No.  Kode     Nama Barang                                Banyak yg   Harga   Banyak yg     Motif\n";
 		$cetak.=$ab."Urut Barang                                               dipesan    Satuan  dipenuhi\n";
		$cetak.=$ab.str_repeat("-",115)."\n";
		$i=0;	
		$detail	= $this->mmaster->bacadetail($area,$row->i_spb);
		foreach($detail as $rowi){
			$i++;
			$hrg=number_format($rowi->v_unit_price);
			$prod	= $rowi->i_product;
      if($rowi->i_product_status=='4') $rowi->e_product_name='* '.$rowi->e_product_name;
			$name	= $rowi->e_product_name.str_repeat(" ",46-strlen($rowi->e_product_name ));
      $motif	= $rowi->e_remark;
			$orde	= number_format($rowi->n_order);
			$deli	= number_format($rowi->n_deliver);
			$aw		= 13;
			$pjg	= strlen($i);
			for($xx=1;$xx<=$pjg;$xx++){
				$aw=$aw-1;
			}
			$pjg	= strlen($orde);
			$spcord	= 4;			
			for($xx=1;$xx<=$pjg;$xx++){
				$spcord	= $spcord-1;
			}
			$pjg	= strlen($hrg);
			$spcprc	= 13;
			for($xx=1;$xx<=$pjg;$xx++){
				$spcprc	= $spcprc-1;
			}
			$cetak.=str_repeat(" ",$aw).$i.str_repeat(" ",1).$prod.str_repeat(" ",2).$name.str_repeat(" ",$spcord).$orde.str_repeat(" ",$spcprc).$hrg.CHR(27).CHR(45).CHR(1).str_repeat(" ",10).CHR(27).CHR(45).CHR(0).str_repeat(" ",4).$motif."\n";
		}
		$cetak.=$ab.str_repeat("-",115)."\n";
		$kotor=$row->v_spb;
		$kotor=number_format($kotor);
		$pjg=strlen($kotor);
		$spckot=14;
		for($xx=1;$xx<=$pjg;$xx++){
			$spckot=$spckot-1;
		}
		$spckot=str_repeat(" ",$spckot);
		$cetak.=$ab."Tanggal daftar                    : ".$row->d_signin.str_repeat(" ",20)."NILAI KOTOR           : ".$spckot.$kotor."\n";		
#		$dis=$row->n_spb_discount1+$row->n_spb_discount2+$row->n_spb_discount3+$row->n_spb_discount4;
    $nNDisc      = $row->n_spb_discount1 + $row->n_spb_discount2*(100-$row->n_spb_discount1)/100;
    $nNDisc0     = $row->n_spb_discount3 + $row->n_spb_discount4*(100-$row->n_spb_discount3)/100;
    $dis      = $nNDisc + (100-$nNDisc)*$nNDisc0/100;
		$dis	= number_format($dis,2);
		$pjg	= strlen($dis);
		$spcdis	= 6;
		for($xx=1;$xx<=$pjg;$xx++){
			$spcdis	= $spcdis-1;
		}
		$spcdis=str_repeat(" ",$spcdis);
		$vdis	= number_format($row->v_spb_discounttotal);
		$pjg	= strlen($vdis);
		$spcvdis	= 14;
		for($xx=1;$xx<=$pjg;$xx++){
			$spcvdis = $spcvdis-1;
		}
		$spcvdis=str_repeat(" ",$spcvdis);
		$cetak.=$ab."Plafon                            : Rp ".number_format($row->v_flapond).str_repeat(" ",17)."POTONGAN ( ".$dis." % )".$spcdis." : ".$spcvdis.$vdis."\n";		
		$cetak.=str_repeat(" ",104).str_repeat("-",12)."\n";		
		$nb	= number_format($row->v_spb-$row->v_spb_discounttotal);
		$pjg=strlen($nb);
		$spcnb=14;
		for($xx=1;$xx<=$pjg;$xx++){
			$spcnb=$spcnb-1;
		}
		$spcnb=str_repeat(" ",$spcnb);
		$cetak.=$ab."Rata-rata Keterlambatan Pelunasan : ".$row->n_ratatelat." hari".str_repeat(" ",23)."NILAI BERSIH          : ".$spcnb.$nb."\n\n";		
		$saldo=$this->mmaster->bacapiutang($row->i_spb,$area);
		$nota=$this->mmaster->bacanotapiutang($row->i_spb,$area);
		$cetak.=$ab."Saldo Piutang           : RP ".number_format($saldo)."(".$nota.")"."\n";

    if($row->d_signin){
		  $tmp=explode("-",$row->d_signin);
		  $th=$tmp[0];
		  $bl=$tmp[1];
		  $hr=$tmp[2];
      $row->d_signin=$hr.'-'.$bl.'-'.$th;
    }

		//$cetak.=$ab."Tanggal daftar                    : ".$row->d_signin."\n";
		//$cetak.=$ab."Plafon                            : Rp ".number_format($row->v_flapond)."\n";
		//$cetak.=$ab."Rata-rata Keterlambatan Pelunasan : ".$row->n_ratatelat." hari\n";
    //$saldo=$saldopiutang;
#		$cetak.=$ab."Saldo Piutang                     : RP ".number_format($row->v_saldo)."\n";
		//$cetak.=$ab."Saldo Piutang                     : RP ".number_format($saldo)."\n";
		$cetak.=$ab."Penjualan               : ";
#####
    $per=substr($row->i_spb,4,4);
    $perth=substr($per,0,2);
    $perbl=substr($per,2,2);
    for($q=1;$q<=6;$q++){
      settype($perth,"integer");
      settype($perbl,"integer");
      $perbl=$perbl-1;
      if($perbl==0){
        $perbl=12;
        $perth=$perth-1;
      }      
      settype($perth,"string");
      settype($perbl,"string");
      $a=strlen($perth);
      while($a<2){
		    $perth="0".$perth;
		    $a=strlen($perth);
		  }
      $a=strlen($perbl);
      while($a<2){
		    $perbl="0".$perbl;
		    $a=strlen($perbl);
		  }
      $row->i_area=substr($row->i_customer,0,2);
      $nota='FP-'.$perth.$perbl.'-'.$row->i_area.'%';
      $thnota=$perth.$perbl;
#      $nota='FP-'.$perth.'%-'.$row->i_area.'%';
      $tesi=0;
      $this->db->select(" sum(v_nota_netto) as total, substring(i_nota,1,10) as no from dgu.tm_nota
		                      where i_nota like '$nota' and i_customer='$row->i_customer' 
                          and f_nota_cancel='f' group by no",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $tes){
          $tesi=$tes->total;
        }
      }
      $this->db->select(" round(totalharibyr/totalalokasi) as ratarata from(
select sum(haribayar) as totalharibyr, sum(bykalokasi) as totalalokasi from(
select distinct a.total, a.no, a.i_alokasi, a.haribayar, count(a.i_alokasi) as bykalokasi
from(
SELECT distinct sum(d.v_nota_netto) as total, substring(d.i_nota, 1, 10) as no, c.i_alokasi, (c.d_alokasi)-(d.d_nota) as haribayar, 0 as bykalokasi
from dgu.tm_nota_item a, dgu.tm_alokasi_item b, dgu.tm_alokasi c, dgu.tm_nota d 
where a.i_nota=b.i_nota and b.i_alokasi=c.i_alokasi and a.i_nota=d.i_nota and c.f_alokasi_cancel='f' 
and d.i_nota like '%$nota%' and c.i_customer='$row->i_customer' group by d.v_nota_netto, d.i_nota, c.i_alokasi, c.d_alokasi, d.d_nota) as a
group by a.total, a.no, a.i_alokasi, a.haribayar) as b
group by b.i_alokasi) as c",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $tes2){
          $ratarata=$tes2->ratarata;
          }
        }else{
          $ratarata=0;
        }
      $peri=substr(mbulan($perbl),0,3).'-20'.$perth;
      $spasi		= 12;
	    $pjg	= strlen(number_format($tesi));
	    for($xx=1;$xx<=$pjg;$xx++){
		    $spasi=$spasi-1;
	    }
      $spasi=str_repeat(" ",$spasi);
      switch($q){
        case 1:
          $cetak.=$peri.'-->'.number_format($tesi)."(".$ratarata."Hari)";
          break;
        case 2:
          $cetak.=str_repeat(" ",4).$peri.'-->'.number_format($tesi)."(".$ratarata."Hari)";
          break;
        case 3:
          $cetak.=str_repeat(" ",3).$peri.'-->'.number_format($tesi)."(".$ratarata."Hari)"."\n";
          break;
        case 4:
          $cetak.=str_repeat(" ",35).$peri.'-->'.number_format($tesi)."(".$ratarata."Hari)";
          break;
        case 5:
          $cetak.=str_repeat(" ",4).$peri.'-->'.number_format($tesi)."(".$ratarata."Hari)";
          break;
        case 6:
          $cetak.=str_repeat(" ",4).$peri.'-->'.number_format($tesi)."(".$ratarata."Hari)"."\n";
          break;
      }
    }
#####

		$cetak.=$ab.CHR(218).str_repeat(CHR(196),10).CHR(194).str_repeat(CHR(196),27).CHR(194).str_repeat(CHR(196),26).CHR(194).str_repeat(CHR(196),8).CHR(194).str_repeat(CHR(196),20).CHR(194).str_repeat(CHR(196),23).CHR(191)."\n";
		$cetak.=$ab.CHR(179)."Tgl & Jam ".CHR(179)."       G U D A N G         ".CHR(179)."  Serah terima GUDANG     ".CHR(179).str_repeat(" ",8).CHR(179)."Tgl&Jam Terima Nota ".CHR(179)."      CEK PLAFON       ".CHR(179)."\n";
		$cetak.=$ab.CHR(179)."Terima SPB".CHR(195).str_repeat(CHR(196),8).CHR(194).str_repeat(CHR(196),8).CHR(194).str_repeat(CHR(196),9).CHR(197).str_repeat(CHR(196),7).CHR(194).str_repeat(CHR(196),9).CHR(194).str_repeat(CHR(196),8).CHR(180)."   MD   ".CHR(195).str_repeat(CHR(196),6).CHR(194).str_repeat(CHR(196),6).CHR(194).str_repeat(CHR(196),6).CHR(194).str_repeat(CHR(196),7).CHR(194).str_repeat(CHR(196),7).CHR(194).str_repeat(CHR(196),7).CHR(180)."\n";
		$cetak.=$ab.CHR(179).str_repeat(" ",10).CHR(179)." CEK I  ".CHR(179)." CEK II ".CHR(179)."CEK AKHIR".CHR(179)."   I   ".CHR(179)."   II    ".CHR(179)."  III   ".CHR(179).str_repeat(" ",8).CHR(179)."   I  ".CHR(179)."  II  ".CHR(179)."  III ".CHR(179)."   AR  ".CHR(179)."  FADH ".CHR(179)."  SDH  ".CHR(179)."\n";
		$cetak.=$ab.CHR(195).str_repeat(CHR(196),10).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),9).CHR(197).str_repeat(CHR(196),7).CHR(197).str_repeat(CHR(196),9).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),6).CHR(197).str_repeat(CHR(196),6).CHR(197).str_repeat(CHR(196),6).CHR(197).str_repeat(CHR(196),7).CHR(197).str_repeat(CHR(196),7).CHR(197).str_repeat(CHR(196),7).CHR(179)."\n";
		$cetak.=$ab.CHR(179).str_repeat(" ",10).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179)."\n";
		$cetak.=$ab.CHR(179).str_repeat(" ",10).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179)."\n";
		$cetak.=$ab.CHR(179).str_repeat(" ",10).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179)."\n";
		$cetak.=$ab.CHR(192).str_repeat(CHR(196),10).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),9).CHR(193).str_repeat(CHR(196),7).CHR(193).str_repeat(CHR(196),9).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),6).CHR(193).str_repeat(CHR(196),6).CHR(193).str_repeat(CHR(196),6).CHR(193).str_repeat(CHR(196),7).CHR(193).str_repeat(CHR(196),7).CHR(193).str_repeat(CHR(196),7).CHR(217)."\n\n";
		$cetak.=$ab.$row->e_remark1."\n";
		$cetak.=$ab.$row->e_approve1."\n";
		$cetak.=$ab.$row->e_approve2."\n";
		$ipp->setFormFeed();
		$cetak.=CHR(18)."\n";
    $ipp->setdata($cetak);
    $ipp->printJob();
  // echo $cetak;
	}
	echo "<script>this.close();</script>";
?>
