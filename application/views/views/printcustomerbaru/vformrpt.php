<?php 
	include ("php/fungsi.php");
	require_once("printipp/PrintIPP.php");
  $cetak='';
  foreach($lang as $row){
	  $nor	= str_repeat(" ",5);
	  $abn	= str_repeat(" ",12);
	  $ab	= str_repeat(" ",9);
	  $ipp 	= new PrintIPP();
	  $ipp->setHost($host);
	  $ipp->setPrinterURI($uri);

//echo $host.'<br>';
//echo $uri.'<br>';die;

	  $ipp->setRawText();
	  $ipp->unsetFormFeed();
	  $cetak.=CHR(18)."\n";		
	  $cetak.=$nor.CHR(27).CHR(71).CHR(14)."           DATA PELANGGAN\n";
	  $cetak.=$nor.CHR(27).CHR(71).CHR(14)."     ".$company->name.CHR(20)."\n";
	  $cetak.=$nor.str_repeat(CHR(205),76).CHR(27).CHR(72)."\n";
    $pjgkata=strlen("AREA : ".substr($row->i_customer,0,2)."-".$row->e_area_name." Kota : ".$row->e_customer_kota1);
    $spchiji=58-$pjgkata;
    $spchiji=str_repeat(" ",$spchiji);
    if($row->f_spb_pkp=='t') 
      $pkp="PKP";
    else 
      $pkp="non PKP";
	  $cetak.=$nor."AREA : ".substr($row->i_customer,0,2)."-".$row->e_area_name." Kota : ".$row->e_customer_kota1.$spchiji.CHR(14).$pkp.CHR(20)."\n";
	  $cetak.=$nor."Kode Pelanggan : "."\n";
    $cetak.=str_repeat(CHR(196),76)."\n";
	  $cetak.=$nor.CHR(14)."DATA TOKO".CHR(20)."\n";
    $cetak.=$nor."Nama Toko      : ".$row->e_customer_name."\n";
    if(strlen($row->e_customer_address<50))
      $cetak.=$nor."Alamat Toko    : ".$row->e_customer_address."\n";
    else
      $cetak.=$nor."Alamat Toko    : ".CHR(15).$row->e_customer_address.CHR(18)."\n";
    $pjgkata=strlen("Kota           : ".$row->e_customer_kota1);
    $spchiji=50-$pjgkata;
    $spchiji=str_repeat(" ",$spchiji);
    $cetak.=$nor."Kota           : ".$row->e_customer_kota1.$spchiji."Kode Pos : ".$row->e_postal1."\n";
    $pjgkata=strlen("No.Telepon     : ".$row->e_customer_phone);
    $spchiji=50-$pjgkata;
    $spchiji=str_repeat(" ",$spchiji);
    $cetak.=$nor."No.Telepon     : ".$row->e_customer_phone.$spchiji."Fax : ".$row->e_fax1."\n";
    $cetak.=$nor."Yang dihubungi : ".$row->e_customer_contact."\n";
    $cetak.=$nor."Alamat E-mail  : ".$row->e_customer_contact."\n";
    $cetak.=str_repeat(CHR(196),76)."\n";
	  $cetak.=$nor.CHR(14)."DATA PEMILIK".CHR(20)."\n";
    $cetak.=$nor."Nama Pemilik   : ".$row->e_customer_owner."\n";
    if(strlen($row->e_customer_owneraddress<50))
      $cetak.=$nor."Alamat Pemilik : ".$row->e_customer_owneraddress."\n";
    else
      $cetak.=$nor."Alamat Pemilik : ".CHR(15).$row->e_customer_owneraddress.CHR(18)."\n";
    $cetak.=$nor."Telepon        : ".$row->e_customer_ownerphone." / ".$row->e_customer_ownerhp."\n";
    $cetak.=str_repeat(CHR(196),76)."\n";
	  $cetak.=$nor.CHR(14)."DATA PKP".CHR(20)."\n";
    $cetak.=$nor."Nama PKP   : ".$row->e_customer_npwpname."\n";
    $cetak.=$nor."Alamat PKP : ".$row->e_customer_npwpaddress."\n";
    $cetak.=$nor."NPWP       : ".$row->e_customer_pkpnpwp."\n";
    $cetak.=str_repeat(CHR(196),76)."\n";
    $cetak.=$nor."Tipe Pelanggan    : ".$row->e_customer_classname."\n";
    $cetak.=$nor."Pola Bayar        : ".$row->e_paymentmethod."\n";
    $cetak.=$nor."T.O.P             : ".$row->n_spb_toplength." hari\n";
    $cetak.=$nor."Jadwal Kontra Bon : \n";
    $disc='';
    if($row->n_spb_discount1!='' && $row->n_spb_discount1!='0'){
      $disc=$disc.$row->n_spb_discount1;
    }
    if($row->n_spb_discount2!='' && $row->n_spb_discount2!='0'){
      $disc=$disc."+".$row->n_spb_discount2;
    }
    if($row->n_spb_discount3!='' && $row->n_spb_discount3!='0'){
      $disc=$disc."+".$row->n_spb_discount3;
    }
    if($row->n_spb_discount4!='' && $row->n_spb_discount4!='0'){
      $disc=$disc."+".$row->n_spb_discount4;
    }
    $cetak.=$nor."Discount          : ".$disc." % / ".$row->i_price_group."\n";
    $cetak.=$nor."Kode Salesman     : ".$row->i_salesman." - ".$row->e_salesman_name."\n";
    $tmp=explode("-",$row->d_customer_entry);
	  $th=$tmp[0];
	  $bl=$tmp[1];
	  $hr=$tmp[2];
    $daftar=$hr." ".mbulan($bl)." ".$th;
    $cetak.=$nor."Tanggal Terdaftar : ".$daftar."\n\n";
    $cetak.=$nor.CHR(218).str_repeat(CHR(196),15).CHR(191)."\n";
    $cetak.=$nor.CHR(179)."  Cap/Stempel  ".CHR(179)." ........ ,Tgl..............\n";
    $cetak.=$nor.CHR(179)."               ".CHR(179)."    Admin Sales Supervisor   Admin Sales\n";
    $cetak.=$nor.CHR(179)."               ".CHR(179)."\n";
    $cetak.=$nor.CHR(179)."               ".CHR(179)."\n";
    $cetak.=$nor.CHR(179)."               ".CHR(179)."\n";
    $cetak.=$nor.CHR(179)."               ".CHR(179)."\n";
    $cetak.=$nor.CHR(192).str_repeat(CHR(196),15).CHR(217)."       ".str_repeat(CHR(196),17)."     ".str_repeat(CHR(196),17)."\n\n\n\n\n\n\n\n\n\n";
    $cetak.=$nor.str_repeat(CHR(196),20)."  ".str_repeat(CHR(196),20)."  ".str_repeat(CHR(196),20)."\n";
    $cetak.=$nor."  Sales Coordinator           S.D.H                  F.A.D.H     \n\n";
    $tgl=date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
    $cetak.=$nor."Tanggal Cetak : ".$tgl;
    $ipp->setFormFeed();
	  $cetak.=CHR(18);
    $ipp->setdata($cetak);
    $ipp->printJob();
   // echo $cetak;
  }
	echo "<script>this.close();</script>";
?>
