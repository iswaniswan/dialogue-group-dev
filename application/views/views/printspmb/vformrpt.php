<?php 
	include ("php/fungsi.php");
	require_once("printipp/PrintIPP.php");
  $cetak='';
  $hal=1;
	foreach($isi as $row){
		$nor	= str_repeat(" ",5);
		$abn	= str_repeat(" ",12);
		$ab	  = str_repeat(" ",9);
		$ipp  = new PrintIPP();
		$ipp->setHost($host);
		$ipp->setPrinterURI($uri);
		$ipp->setRawText();
		$ipp->unsetFormFeed();
#####
#		CetakHeader($row,$hal,$nor,$abn,$ab,$host,$uri,$cetak,$ipp);
		$cetak.=CHR(18);		
		$cetak.=$nor.$company->name."\n";		
		$cetak.=$nor.$company->alamat_company.",".$company->kota_company."\n\n";		
		$cetak.=$nor.str_repeat(" ",13)."S U R A T     P E R M I N T A A N     B A R A N G\n";		
		$cetak.=$nor.str_repeat(" ",24)."Nomor SPmB : ".$row->i_spmb."/".$row->i_area."-".$row->e_area_name."\n";		
		$tmp=explode("-",$row->d_spmb);
		$th=$tmp[0];
		$bl=$tmp[1];
		$hr=$tmp[2];
		$dspmb=$hr." ".mbulan($bl)." ".$th;
		$cetak.=$nor.str_repeat(" ",24)."Tanggal    : ".$dspmb."\n\n";		
		$cetak.=$nor."Kepada Yth.\n";		
		$cetak.=$nor."Bag. Pembelian\n";		
		$cetak.=$nor.$company->name." (PUSAT)\n\n";		
		$cetak.=$nor."Dengan hormat,\n";		
		$cetak.=$nor."Bersama surat ini kami mohon dikirimkan barang-barang sbb:\n".CHR(15);		
		$cetak.=$nor.str_repeat(" ",124)."Hal:".$hal."\n";		
		$cetak.=$ab.CHR(218).str_repeat(CHR(196),5).CHR(194).str_repeat(CHR(196),13).CHR(194).str_repeat(CHR(196),66).CHR(194).str_repeat(CHR(196),26).CHR(194).str_repeat(CHR(196),10).CHR(191)."\n";		
		$cetak.=$ab.CHR(179)." NO. ".CHR(179)." KODE BARANG ".CHR(179)." NAMA BARANG                                                      ".CHR(179)." KETERANGAN               ".CHR(179)."  JUMLAH  ".CHR(179)."\n";		
		$cetak.=$ab.CHR(198).str_repeat(CHR(205),5).CHR(216).str_repeat(CHR(205),13).CHR(216).str_repeat(CHR(205),66).CHR(216).str_repeat(CHR(205),26).CHR(216).str_repeat(CHR(205),10).CHR(181)."\n";		
#####
		$spmb	= $row->i_spmb;
		$query 	= $this->db->query(" select * from tm_spmb_item where i_spmb='$spmb'",false);
		$jml 	= $query->num_rows();
		$i=0;	
		foreach($detail as $rowi){
      if($rowi->n_acc>0){
			  $i++;
			  $prod	= $rowi->i_product;
			  $name	= $rowi->e_product_name.", ".$rowi->e_product_motifname;
			  if(strlen($name)>64){
				  $name=substr($name,0,64);
			  }
			  $name	= $name.str_repeat(" ",65-strlen($name));
			  $orde	= number_format($rowi->n_acc);
			  $ket	= $rowi->e_remark;
			  if(strlen($ket)>24){
				  $ket=substr($ket,0,24);
			  }
			  $ket	= $ket.str_repeat(" ",25-strlen($ket));
			  $aw		= 4;
			  $pjg	= strlen($i);
			  for($xx=1;$xx<=$pjg;$xx++){
				  $aw=$aw-1;
			  }
			  $aw		= str_repeat(" ",$aw);
			  $pjg	= strlen($orde);
			  $spcord	= 6;
			  for($xx=1;$xx<=$pjg;$xx++){
				  $spcord	= $spcord-1;
			  }
			  $spcend=str_repeat(" ",10-($spcord+1+$pjg));
			  $spcord=str_repeat(" ",$spcord);
			  $cetak.=$ab.CHR(179).$aw.$i." ".CHR(179)." ".$prod.str_repeat(" ",5).CHR(179)." ".$name.CHR(179)." ".$ket.CHR(179)." ".$spcord.$orde.$spcend.CHR(179)."\n";			
			  if($jml>35){
				  if(($i%40)==0){
					  $cetak.=$ab.CHR(192).str_repeat(CHR(196),5).CHR(193).str_repeat(CHR(196),13).CHR(193).str_repeat(CHR(196),66).CHR(193).str_repeat(CHR(196),26).CHR(193).str_repeat(CHR(196),10).CHR(217)."\n";					
					  $cetak.=$ab.str_repeat(" ",84)."bersambung ......."."\n";
            $ipp->setFormFeed();
            $ipp->setdata($cetak);
            $ipp->printJob();
            $cetak='';
					  $hal=$hal+1;
	          $cetak.=CHR(18);		
	          $cetak.=$nor.$company->name."\n";		
	          $cetak.=$nor.$company->alamat_company.",".$company->kota_company."\n\n";		
	          $cetak.=$nor.str_repeat(" ",13)."S U R A T     P E R M I N T A A N     B A R A N G\n";		
	          $cetak.=$nor.str_repeat(" ",24)."Nomor SPmB : ".$row->i_spmb."/".$row->i_area."-".$row->e_area_name."\n";		
	          $tmp=explode("-",$row->d_spmb);
	          $th=$tmp[0];
	          $bl=$tmp[1];
	          $hr=$tmp[2];
	          $dspmb=$hr." ".mbulan($bl)." ".$th;
	          $cetak.=$nor.str_repeat(" ",24)."Tanggal    : ".$dspmb."\n\n";		
	          $cetak.=$nor."Kepada Yth.\n";		
	          $cetak.=$nor."Bag. Pembelian\n";		
	          $cetak.=$nor.$company->name." (PUSAT)\n\n";		
	          $cetak.=$nor."Dengan hormat,\n";		
	          $cetak.=$nor."Bersama surat ini kami mohon dikirimkan barang-barang sbb:\n".CHR(15);		
	          $cetak.=$nor.str_repeat(" ",124)."Hal:".$hal."\n";		
	          $cetak.=$ab.CHR(218).str_repeat(CHR(196),5).CHR(194).str_repeat(CHR(196),13).CHR(194).str_repeat(CHR(196),66).CHR(194).str_repeat(CHR(196),26).CHR(194).str_repeat(CHR(196),10).CHR(191)."\n";		
	          $cetak.=$ab.CHR(179)." NO. ".CHR(179)." KODE BARANG ".CHR(179)." NAMA BARANG                                                      ".CHR(179)." KETERANGAN               ".CHR(179)."  JUMLAH  ".CHR(179)."\n";		
	          $cetak.=$ab.CHR(198).str_repeat(CHR(205),5).CHR(216).str_repeat(CHR(205),13).CHR(216).str_repeat(CHR(205),66).CHR(216).str_repeat(CHR(205),26).CHR(216).str_repeat(CHR(205),10).CHR(181)."\n";		
  #####
					  $j	= 0;
				  }
			  }
      }
		}
		$ipp->unsetFormFeed();
		$cetak.=CHR(15);		
		$cetak.=$ab.CHR(192).str_repeat(CHR(196),5).CHR(193).str_repeat(CHR(196),13).CHR(193).str_repeat(CHR(196),66).CHR(193).str_repeat(CHR(196),26).CHR(193).str_repeat(CHR(196),10).CHR(217)."\n\n";				
		$cetak.=$ab."Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.".CHR(18)."\n\n";		
		$cetak.=$nor."         Hormat kami,                             Menyetujui,\n\n\n\n\n";		
		$cetak.=$nor.CHR(15)."        ".str_repeat(CHR(196),35).str_repeat(" ",34).str_repeat(CHR(196),39).CHR(18)."\n";		
		$cetak.=$nor."        Kepala Gudang                        Supervisor Administrasi\n\n".CHR(15);		
		$tgl=date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
		$ipp->setFormFeed();
		$cetak.=$ab."TANGGAL CETAK : ".$tgl.CHR(18);		
    $ipp->setdata($cetak);
    $ipp->printJob();
   // echo $cetak;
	}
	echo "<script>this.close();</script>";
?>
