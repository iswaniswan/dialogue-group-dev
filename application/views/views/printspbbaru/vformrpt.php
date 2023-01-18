<?php 
    include ("php/fungsi.php");
    require_once("printipp/PrintIPP.php");
  $cetak='';
  foreach($lang as $row){
      $nor  = str_repeat(" ",5);
      $abn  = str_repeat(" ",12);
      $ab   = str_repeat(" ",9);
      $ipp  = new PrintIPP();
      $ipp->setHost($host);
      $ipp->setPrinterURI($uri);
      $ipp->setRawText();
      $ipp->unsetFormFeed();
      $cetak.=CHR(18)."\n";     
      $cetak.=$nor.CHR(27).CHR(71).CHR(14)."           DATA PELANGGAN\n\n";
      $cetak.=$nor.CHR(15)."Kode Pelanggan :                                                 Contact Person : ".$row->e_customer_contact."\n";
    $spchiji=str_repeat(" ",48-strlen($row->e_customer_name));
    if($row->f_spb_pkp=='t') 
      $pkp="PKP";
    else 
      $pkp="non PKP";
      $cetak.=$nor."Nama Pelanggan : ".$row->e_customer_name.$spchiji.$pkp."\n";
      if(strlen($row->e_customer_address) > 48){
        $spchiji=str_repeat(" ",1);
      }else{
          $spchiji=str_repeat(" ",48-strlen($row->e_customer_address));
      }
      $cetak.=$nor."Alamat         : ".$row->e_customer_address.$spchiji."Nama Pemilik   : ".$row->e_customer_owner."\n";
    $spchiji=str_repeat(" ",48-strlen($row->e_postal1));
      $cetak.=$nor."Kodepos        : ".$row->e_postal1.$spchiji."Alamat Pemilik : ".$row->e_customer_owneraddress."\n";
      $cetak.=$nor."No.Telepon     : ".$row->e_customer_phone."\n";
    $spchiji=str_repeat(" ",48-strlen($row->e_fax1));
      $cetak.=$nor."Fax            : ".$row->e_fax1.$spchiji."NPWP           : ".$row->e_customer_pkpnpwp."\n";
    $spchiji=str_repeat(" ",48-strlen($row->e_customer_mail));
      $cetak.=$nor."E-mail         : ".$row->e_customer_mail.$spchiji."No.Telepon     : ".$row->e_customer_ownerphone." / ".$row->e_customer_ownerhp."\n";
    $spchiji=str_repeat(" ",48-strlen($row->i_area."-".$row->e_area_name));
      $cetak.=$nor."Kode Daerah    : ".$row->i_area."-".$row->e_area_name.$spchiji."Tipe Pelanggan : ".$row->e_customer_classname."\n\n";
    $cetak.=$nor.CHR(27).CHR(45).CHR(1)."Pesanan Barang : ".CHR(27).CHR(45).CHR(0).CHR(27).CHR(53).CHR(15)."\n";
    $cetak.=$nor."Nomor SPB      : ".$row->i_spb."\n";
    $tmp=explode("-",$row->d_spb);
      $th=$tmp[0];
      $bl=$tmp[1];
      $hr=$tmp[2];
    $daftar=$hr." ".mbulan($bl)." ".$th;
    $cetak.=$nor."Tanggal        : ".$daftar."\n";
    $cetak.=$nor.str_repeat("=",115)."\n";      
        $cetak.=$nor."No.  Kode     Nama Barang                                Banyak yg   Harga   Banyak yg     Motif\n";      
    $cetak.=$nor."Urut Barang                                               dipesan    Satuan  dipenuhi\n";     
        $cetak.=$nor.str_repeat("-",115)."\n";      
        $i=0;   
        foreach($detail as $rowi){
            $i++;
            $hrg=number_format($rowi->v_unit_price);
            $prod   = $rowi->i_product;
      if($rowi->i_product_status=='4') $rowi->e_product_name='* '.$rowi->e_product_name;
            if(strlen($rowi->e_product_name) > 46){
                $name   = $rowi->e_product_name.str_repeat(" ",1);
            }else{
                $name   = $rowi->e_product_name.str_repeat(" ",46-strlen($rowi->e_product_name ));
            }
            $motif  = $rowi->e_remark;
            $orde   = number_format($rowi->n_order);
            $deli   = number_format($rowi->n_deliver);
            $aw     = 9;
            $pjg    = strlen($i);
            for($xx=1;$xx<=$pjg;$xx++){
                $aw=$aw-1;
            }
            $pjg    = strlen($orde);
            $spcord = 4;            
            for($xx=1;$xx<=$pjg;$xx++){
                $spcord = $spcord-1;
            }
            $pjg    = strlen($hrg);
            $spcprc = 13;
            for($xx=1;$xx<=$pjg;$xx++){
                $spcprc = $spcprc-1;
            }
            $cetak.=str_repeat(" ",$aw).$i.str_repeat(" ",1).$prod.str_repeat(" ",2).$name.str_repeat(" ",$spcord).$orde.str_repeat(" ",$spcprc).$hrg.CHR(27).CHR(45).CHR(1).str_repeat(" ",10).CHR(27).CHR(45).CHR(0).str_repeat(" ",4).$motif."\n";
    }
        $cetak.=$nor.str_repeat("-",115)."\n";      
        $kotor=$row->v_spb;
        $kotor=number_format($kotor);
        $pjg=strlen($kotor);
        $spckot=14;
        for($xx=1;$xx<=$pjg;$xx++){
            $spckot=$spckot-1;
        }
        $spckot=str_repeat(" ",$spckot);
        $cetak.=str_repeat(" ",86)."NILAI KOTOR           : ".$spckot.$kotor."\n";      
    $nNDisc      = $row->n_spb_discount1 + $row->n_spb_discount2*(100-$row->n_spb_discount1)/100;
    $nNDisc0     = $row->n_spb_discount3 + $row->n_spb_discount4*(100-$row->n_spb_discount3)/100;
    $dis      = $nNDisc + (100-$nNDisc)*$nNDisc0/100;
        $dis    = number_format($dis,2);
        $pjg    = strlen($dis);
        $spcdis = 6;
        for($xx=1;$xx<=$pjg;$xx++){
            $spcdis = $spcdis-1;
        }
        $spcdis=str_repeat(" ",$spcdis);
        $vdis   = number_format($row->v_spb_discounttotal);
        $pjg    = strlen($vdis);
        $spcvdis    = 14;
        for($xx=1;$xx<=$pjg;$xx++){
            $spcvdis = $spcvdis-1;
        }
        $spcvdis=str_repeat(" ",$spcvdis);
        $cetak.=str_repeat(" ",86)."POTONGAN ( ".$dis." % )".$spcdis." : ".$spcvdis.$vdis."\n";     
        $nb = number_format($row->v_spb-$row->v_spb_discounttotal);
        $pjg=strlen($nb);
        $spcnb=14;
        for($xx=1;$xx<=$pjg;$xx++){
            $spcnb=$spcnb-1;
        }
        $spcnb=str_repeat(" ",$spcnb);
        $cetak.=str_repeat(" ",86)."NILAI BERSIH          : ".$spcnb.$nb."\n";
    $cetak.=$nor."           Menyetujui,\n\n\n\n";
    $cetak.=$nor."Area Supervisor/SDH       ADH\n\n";
    $spchiji=str_repeat(" ",48-strlen("     TOP : ".$row->n_spb_toplength." hari"));
    $cetak.=$nor."      TOP : ".$row->n_spb_toplength." hari".$spchiji."Kode Salesman : ".$row->i_salesman."\n";
    $spchiji=str_repeat(" ",48-strlen("Discount : ".$row->n_customer_discount." %"));
    $cetak.=$nor."Discount  : ".$row->n_customer_discount." %".$spchiji."Nama Salesman : ".$row->e_salesman_name."\n";
    $cetak.=$nor."REFERENSI   : \n";
    $cetak.=$nor.$row->e_customer_refference."\n";
      $cetak.=$nor."lama usaha  : ".$row->e_customer_age."\n";    
      $cetak.=$nor."status toko : ".$row->e_shop_status."\n";
      $cetak.=$nor."luas   toko : ".$row->n_shop_broad." m2\n";
      $cetak.=$nor."Cara Bayar  : ".$row->e_paymentmethod."\n";
      $cetak.=$nor."Tipe        : ".$row->e_customer_classname."\n";

        $cetak.=$nor.CHR(218).str_repeat(CHR(196),10).CHR(194).str_repeat(CHR(196),27).CHR(194).str_repeat(CHR(196),26).CHR(194).str_repeat(CHR(196),8).CHR(194).str_repeat(CHR(196),20).CHR(194).str_repeat(CHR(196),23).CHR(191)."\n";        
        $cetak.=$nor.CHR(179)."Tgl & Jam ".CHR(179)."       G U D A N G         ".CHR(179)."  Serah terima GUDANG     ".CHR(179).str_repeat(" ",8).CHR(179)."Tgl&Jam Terima Nota ".CHR(179)."      CEK PLAFON       ".CHR(179)."\n";        
        $cetak.=$nor.CHR(179)."Terima SPB".CHR(195).str_repeat(CHR(196),8).CHR(194).str_repeat(CHR(196),8).CHR(194).str_repeat(CHR(196),9).CHR(197).str_repeat(CHR(196),7).CHR(194).str_repeat(CHR(196),9).CHR(194).str_repeat(CHR(196),8).CHR(180)."   MD   ".CHR(195).str_repeat(CHR(196),6).CHR(194).str_repeat(CHR(196),6).CHR(194).str_repeat(CHR(196),6).CHR(194).str_repeat(CHR(196),7).CHR(194).str_repeat(CHR(196),7).CHR(194).str_repeat(CHR(196),7).CHR(180)."\n";       
        $cetak.=$nor.CHR(179).str_repeat(" ",10).CHR(179)." CEK I  ".CHR(179)." CEK II ".CHR(179)."CEK AKHIR".CHR(179)."   I   ".CHR(179)."   II    ".CHR(179)."  III   ".CHR(179).str_repeat(" ",8).CHR(179)."   I  ".CHR(179)."  II  ".CHR(179)."  III ".CHR(179)."   AR  ".CHR(179)."  FADH ".CHR(179)."  SDH  ".CHR(179)."\n";      
        $cetak.=$nor.CHR(195).str_repeat(CHR(196),10).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),9).CHR(197).str_repeat(CHR(196),7).CHR(197).str_repeat(CHR(196),9).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),8).CHR(197).str_repeat(CHR(196),6).CHR(197).str_repeat(CHR(196),6).CHR(197).str_repeat(CHR(196),6).CHR(197).str_repeat(CHR(196),7).CHR(197).str_repeat(CHR(196),7).CHR(197).str_repeat(CHR(196),7).CHR(179)."\n";        
        $cetak.=$nor.CHR(179).str_repeat(" ",10).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179)."\n";      
        $cetak.=$nor.CHR(179).str_repeat(" ",10).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179)."\n";      
        $cetak.=$nor.CHR(179).str_repeat(" ",10).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",9).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",8).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",6).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179).str_repeat(" ",7).CHR(179)."\n";      
        $cetak.=$nor.CHR(192).str_repeat(CHR(196),10).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),9).CHR(193).str_repeat(CHR(196),7).CHR(193).str_repeat(CHR(196),9).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),6).CHR(193).str_repeat(CHR(196),6).CHR(193).str_repeat(CHR(196),6).CHR(193).str_repeat(CHR(196),7).CHR(193).str_repeat(CHR(196),7).CHR(193).str_repeat(CHR(196),7).CHR(217)."\n\n";
        // $cetak.=$ab.$row->e_remark1."\n";
    $ipp->setFormFeed();
      $cetak.=CHR(18);
    $ipp->setdata($cetak);
    $ipp->printJob();
   // echo $cetak;
  }
    echo "<script>this.close();</script>";
?>
