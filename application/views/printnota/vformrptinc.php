<?php 
   require_once ("php/fungsi.php");
   require_once("printipp/PrintIPP.php");
   $cetak='';
   if($isi){
   foreach($isi as $row){
      $row->f_plus_ppn = 't';
      $nor  = str_repeat(" ",5);
      $abn  = str_repeat(" ",12);
      $ab   = str_repeat(" ",9); 
      $ba   = str_repeat(" ",2);
      $bc   = str_repeat(" ",1);
      $hal  = 1;
      $ipp  = new PrintIPP();
      $ipp->setHost($host);
      $ipp->setPrinterURI($uri);
      $ipp->setRawText();
      $ipp->unsetFormFeed();
      $xmp=CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company);
      $ymp='';
      $i = 0;
      $j = 0;
      $iarea   = substr($row->i_sj,8,2);
      $query   = $this->db->query(" select * from tm_nota_item where i_nota='$row->i_nota' and i_area='$iarea'",false);
      $jml  = $query->num_rows();
      //var_dump($jml);
      //die();
    $total=0;
    $i=0;
    $j=0;
      foreach($detail as $rowi){
         if($rowi->n_deliver>0){
            $i++;
            $j++;
        $group='';
        $plu='';
         $qu   = $this->db->query(" select i_customer_plugroup from tr_customer_plugroup
                                 where i_customer='$row->i_customer'");
        if($qu->num_rows()>0){
          foreach($qu->result() as $ts){
            $group=$ts->i_customer_plugroup;
          }
          $qx  = $this->db->query("select i_customer_plu from tr_customer_plu
                                  where i_customer_plugroup='$group' and i_product='$rowi->i_product'");
          if($qx->num_rows()>0){
            foreach($qx->result() as $tx){
              $plu=$tx->i_customer_plu;
            }
          }
        }else{
          $plu='';
        }
            $prod = $rowi->i_product;
#        if($plu!=''){
#          $prod=$plu;
#          if(strlen($prod)>10){
#               $prod   = substr($prod,0,10);
#            }else{
#               $prod   = $prod.str_repeat(" ",10-strlen($prod));
#            }
#        }
        if($plu!=''){
             if(strlen($plu.' - '.$rowi->e_product_name )>65){
                $name   = substr($plu.' - '.$rowi->e_product_name,0,65);
             }else{
                $name   = $plu.' - '.$rowi->e_product_name.str_repeat(" ",65-strlen($plu.' - '.$rowi->e_product_name ));
             }
        }else{
             if(strlen($rowi->e_product_name )>65){
                $name   = substr($rowi->e_product_name,0,65);
             }else{
                $name   = $rowi->e_product_name.str_repeat(" ",65-strlen($rowi->e_product_name ));
             }
        }
#           $name = $rowi->e_product_name." ".str_repeat(".",66-strlen($rowi->e_product_name ));
            $deli = number_format($rowi->n_deliver);
            $pjg  = strlen($deli);
            $spcdel  = 4;
            for($xx=1;$xx<=$pjg;$xx++){
               $spcdel  = $spcdel-1;
            }
            if($row->f_plus_ppn=='t'){
               $pric = number_format($rowi->v_unit_price);
            }else{
               $pric = number_format($rowi->v_unit_price/1.1);
            }
            $pjg  = strlen($pric);
            $spcpric= 15;
            for($xx=1;$xx<=$pjg;$xx++){
               $spcpric= $spcpric-1;
            }
            if($row->f_plus_ppn=='t'){
               $tot  = $rowi->n_deliver*$rowi->v_unit_price;
               $totx = number_format($rowi->n_deliver*$rowi->v_unit_price);
            }else{
               $tot  = $rowi->n_deliver*($rowi->v_unit_price/1.1);
               $totx = number_format($rowi->n_deliver*($rowi->v_unit_price/1.1));
            }
            $pjg  = strlen($totx);
            $spctot = 19;
            for($xx=1;$xx<=$pjg;$xx++){
               $spctot  = $spctot-1;
            }
            $aw=13;
            $pjg  = strlen($i);
            for($xx=1;$xx<=$pjg;$xx++){
               $aw=$aw-1;
            }
            $aw=str_repeat(" ",$aw);
#        $total   = $total+str_replace(',','',$tot);
        $total = $total+$tot;
        $cetak.=CHR(15).$aw.$i.str_repeat(" ",3).$prod.str_repeat(" ",3).$name.str_repeat(" ",$spcdel).$deli.str_repeat(" ",$spcpric).$pric.str_repeat(" ",$spctot).number_format($tot)."\n";
         if($jml>47){
                 if(($i%40)==0){
                    $cetak.=CHR(18).$nor.str_repeat('-',73)."\n";
                    $cetak.=$nor.str_repeat(" ",40)."bersambung .......\n\n\n\n\n\n";
                    $hal=$hal+1;
                    $ymp=CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company);
                    $cetak.=$ymp;
//                    $cetak.="\n\n\n\n\n\n\n\n\n";
                    $j  = 0;
                 }elseif(
                    (($i<40)&&($i==$jml))
                    ){
                    $cetak.=CHR(18).$nor.str_repeat('-',73)."\n";
                    $cetak.=$nor.str_repeat(" ",40)."bersambung .......\n\n\n\n\n\n";
                    $hal=$hal+1;
                    $ymp=CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company);
                    $j  = 0;
                 }
              }

         }
      }
      $cetak=$xmp.$cetak;
      $zmp=CetakFooter($row,$nor,$abn,$ab,$j,$ipp,$total,$company);
      $cetak=$cetak.$zmp;
    //$cetak.=$ab.CHR(27).CHR(71).PermataBandung.CHR(27).CHR(72)."\n".CHR(18);
      //$ipp->setFormFeed();
#     $ipp->setBinary();
   }
}
 // echo $cetak;
  $ipp->setFormFeed();
  $ipp->setdata($cetak);
  $ipp->printJob();
  echo "<script>this.close();</script>";

  function CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company){
    foreach($company->result() as $cmp){
      $cetak = '';
      $cetak.=CHR(18);
      $alm  = strlen(trim($row->e_customer_address));
      $cetak.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1).$cmp->name.CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."                 Kepada Yth.\n";
      if($row->f_customer_pkp=="t"){
      $cetak.=$nor.$cmp->alamat.",".$cmp->kota."                 ".rtrim($row->e_customer_pkpname)."\n";
      }else{
      $cetak.=$nor.$cmp->alamat.",".$cmp->kota."                 ".rtrim($row->e_customer_ownername)."\n";
      }
      if($alm<35){
         $cetak.=$nor."Telp.: ".$cmp->telepon."                         ".trim($row->e_customer_address)."\n";
      }else{
         $cetak.=$nor."Telp.: ".$cmp->telepon."                         ".CHR(15).trim($row->e_customer_address).CHR(18)."\n";
      }
      $cetak.=$nor."Fax  : ".$cmp->fax."                         ".rtrim($row->e_customer_city)."\n";
      if($row->f_customer_pkp=='t'){
         $cetak.=$nor."NPWP : ".$cmp->npwp."                 NPWP : ".$row->e_customer_pkpnpwp."\n";
      }else{
         $cetak.=$nor."NPWP : ".$cmp->npwp."                 "."\n";
      }
      $cetak.=$nor.CHR(27).CHR(71)."BCA CABANG CIMAHI - BANDUNG\n";
      $cetak.=$nor."NO.AC: 139.300.1236".CHR(27).CHR(72)."\n\n";
      $cetak.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1).CHR(14)."             NOTA PENJUALAN".CHR(20).CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."\n\n";

      $pjgpo  = strlen(trim($row->i_spb_po));
      $pjgpo  = 9-$pjgpo;

      $cetak.=$nor."NO PO:".trim($row->i_spb_po).str_repeat(" ",$pjgpo)."No.FAK. / No.SJ     : ".trim(substr($row->i_nota,8,7))."/".substr($row->i_sj,8,6)."\n";
      $cetak.=$nor.str_repeat(" ",15)."KODE SALES/KODELANG : ".$row->i_salesman."/".$row->i_customer."\n";
      #$xxx=datediff('d',$row->d_nota,$row->d_jatuh_tempo,false);
      $xxx=$row->n_customer_toplength_print;
      if(($xxx)>0){
         $cetak.=$nor.str_repeat(" ",15)."MASA PEMBAYARAN     : ".$xxx." hari SETELAH BARANG DITERIMA\n";
      }else{
         $cetak.=$nor.str_repeat(" ",15)."MASA PEMBAYARAN     : "."TUNAI\n";
      }
#      $cetak.="\n";
      $cetak.=$nor.CHR(179).str_repeat(CHR(196),72).CHR(191)."\n";
      $cetak.=$nor.CHR(179)."No. KD-BARANG       NAMA BARANG                 UNIT   HARGA     JUMLAH ".CHR(179)."\n";
      $cetak.=$nor.CHR(212).str_repeat(CHR(205),70).CHR(190).CHR(10)."\n";
    }
    return $cetak;
   }
   function CetakFooter($row,$nor,$abn,$ab,$j,$ipp,$total,$company){
      foreach($company->result() as $cmp){
      $cetak = '';
      $cetak.=CHR(18).$nor.str_repeat("-",73)."\n";
#    if($row->f_spb_consigment=='f' || $row->f_plus_ppn!='t'){
      $row->v_nota_gross=$total;
#    }
      if(strlen(number_format($row->v_nota_gross))==1){
         $cetak.=str_repeat(" ",46)."TOTAL        :                ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==2){
         $cetak.=str_repeat(" ",46)."TOTAL        :               ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==3){
         $cetak.=str_repeat(" ",46)."TOTAL        :              ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==4){
         $cetak.=str_repeat(" ",46)."TOTAL        :             ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==5){
         $cetak.=str_repeat(" ",46)."TOTAL        :            ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==6){
         $cetak.=str_repeat(" ",46)."TOTAL        :           ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==7){
         $cetak.=str_repeat(" ",46)."TOTAL        :          ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==8){
         $cetak.=str_repeat(" ",46)."TOTAL        :         ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==9){
         $cetak.=str_repeat(" ",46)."TOTAL        :        ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==10){
         $cetak.=str_repeat(" ",46)."TOTAL        :       ".number_format($row->v_nota_gross)."\n";
      }elseif(strlen(number_format($row->v_nota_gross))==11){
         $cetak.=str_repeat(" ",46)."TOTAL        :      ".number_format($row->v_nota_gross)."\n";
      }

      if( ($row->n_nota_discount1+$row->n_nota_discount2+$row->n_nota_discount3+$row->n_nota_discount4==0) && $row->v_nota_discounttotal <> 0 )
      {
        $vdisc1=$row->v_nota_discounttotal;
        $vdisc2=0;
        $vdisc3=0;
        $vdisc4=0;
        $vdistot   = round($vdisc1+$vdisc2+$vdisc3+$vdisc4);
        if( ($row->f_plus_ppn=='f') ){#&&($row->n_nota_discount1==0) ){
            $vdistot=$vdistot/1.1; # dibuka tanggal 27-03-2013 contoh nota FP-1303-PB00465
        }
      }
      else
      {
         $vdisc1=($total*$row->n_nota_discount1)/100;
         $vdisc2=((($total-$vdisc1)*$row->n_nota_discount2)/100);
         $vdisc3=((($total-($vdisc1+$vdisc2))*$row->n_nota_discount3)/100);
         $vdisc4=((($total-($vdisc1+$vdisc2+$vdisc3))*$row->n_nota_discount4)/100);
         $vdistot   = round($vdisc1+$vdisc2+$vdisc3+$vdisc4);
      }
    
#    if($row->f_spb_consigment=='f'){
      $row->v_nota_discounttotal=$vdistot;
#    }else{
#      $row->v_nota_discounttotal=$row->v_nota_discounttotal/1.1;
#    }
      if(strlen(number_format($row->v_nota_discounttotal))==1){
         $cetak.=str_repeat(" ",46)."POTONGAN     :                ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==2){
         $cetak.=str_repeat(" ",46)."POTONGAN     :               ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==3){
         $cetak.=str_repeat(" ",46)."POTONGAN     :              ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==4){
         $cetak.=str_repeat(" ",46)."POTONGAN     :             ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==5){
         $cetak.=str_repeat(" ",46)."POTONGAN     :            ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==6){
         $cetak.=str_repeat(" ",46)."POTONGAN     :           ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==7){
         $cetak.=str_repeat(" ",46)."POTONGAN     :          ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==8){
         $cetak.=str_repeat(" ",46)."POTONGAN     :         ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==9){
         $cetak.=str_repeat(" ",46)."POTONGAN     :        ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==10){
         $cetak.=str_repeat(" ",46)."POTONGAN     :       ".number_format($row->v_nota_discounttotal)."\n";
      }elseif(strlen(number_format($row->v_nota_discounttotal))==11){
         $cetak.=str_repeat(" ",46)."POTONGAN     :      ".number_format($row->v_nota_discounttotal)."\n";
      }
      if($row->f_plus_ppn=='f'){
      $vppn=(round($total)-round($vdistot))*0.1;
      $row->v_nota_ppn=$vppn;
         if(strlen(number_format($row->v_nota_ppn))==1){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :                ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==2){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :               ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==3){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :              ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==4){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :             ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==5){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :            ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==6){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :           ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==7){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :          ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==8){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :         ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==9){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :        ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==10){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :       ".number_format($row->v_nota_ppn)."\n";
         }elseif(strlen(number_format($row->v_nota_ppn))==11){
            $cetak.=str_repeat(" ",46)."PPN (10%)    :      ".number_format($row->v_nota_ppn)."\n";
         }
      }
      $cetak.=str_repeat(" ",66).str_repeat("-",14).CHR(" ").CHR("-")."\n";
    if($row->f_plus_ppn=='f'){
#      $row->v_nota_netto=$total-$vdistot+$vppn;
#      $row->v_nota_netto=round($row->v_nota_gross)-round($row->v_nota_discount)+round($vppn);
      $row->v_nota_netto=round($row->v_nota_gross)-round($row->v_nota_discounttotal)+$vppn;
        if(strlen(number_format($row->v_nota_netto))==1){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :                ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==2){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :               ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==3){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :              ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==4){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :             ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==5){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :            ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==6){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :           ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==7){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :          ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==8){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :         ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==9){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :        ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==10){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :       ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==11){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :      ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }
    }else{
      $row->v_nota_netto=$row->v_nota_gross-$row->v_nota_discounttotal;
        if(strlen(number_format($row->v_nota_netto))==1){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :                ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==2){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :               ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==3){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :              ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==4){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :             ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==5){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :            ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==6){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :           ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==7){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :          ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==8){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :         ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==9){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :        ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==10){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :       ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }elseif(strlen(number_format($row->v_nota_netto))==11){
           $cetak.=str_repeat(" ",46)."NILAI FAKTUR :      ".number_format($row->v_nota_netto).CHR(15)."\n\n";
        }
    }
#    $cetak.="nilai na teh ieu yeuh : ".$row->v_nota_netto."\n\n";
      $bilangan = new Terbilang;
      $kata=$bilangan->eja(round($row->v_nota_netto));
      $cetak.=$ab."(".$kata." Rupiah)".CHR(18)."\n";
      $tmp=explode("-",$row->d_nota);
      $th=$tmp[0];
      $bl=$tmp[1];
      $hr=$tmp[2];
      $dnota=$hr." ".mbulan($bl)." ".$th;
      $cetak.=str_repeat(" ",50)."Bandung, ".$dnota."\n";
      $cetak.=str_repeat(" ",3)."        Penerima                                     S E & O\n\n\n\n\n";
      $cetak.=str_repeat(" ",3)."    (              )                            ( ".$cmp->ttd_nota." )\n".CHR(15);
      $cetak.=$ab."Catatan :\n";
      $cetak.=$ab."1. Barang-barang yang sudah dibeli tidak dapat ditukar/dikembalikan, kecuali ada perjanjian terlebih dahulu\n";
      if($row->f_plus_ppn=='t'){
         $cetak.=$ab."2. Faktur asli merupakan bukti pembayaran yang sah. (Harga sudah termasuk PPN)\n";
      }else{
         $cetak.=$ab."2. Faktur asli merupakan bukti pembayaran yang sah.\n";
      }
      $cetak.=$ab."3. Pembayaran dengan cek/giro berharga baru dianggap sah setelah diuangkan/cair.\n";
    $cetak.=$ab.CHR(15)."4. Mohon untuk konfirmasi apabila sudah melakukan pembayaran ke :\n";
    $cetak.=$ab.CHR(27).CHR(71)."       HP 082216449371 (Telkomsel) / 08552322413 (Matrix) / WA 081324236922 > UP : Grease S".CHR(27).CHR(72)."\n";
    // $cetak.=$ab.CHR(27).CHR(71)."       WA 081324236922".CHR(27).CHR(72)."\n";
    $cetak.=$ab."5. Pembayaran dapat ditransfer atas nama ".CHR(27).CHR(71).CHR(18).$cmp->name." ".CHR(27).CHR(72).CHR(15)."ke Rekening :".CHR(18)."\n";
    $cetak.=$ab.CHR(27).CHR(71).$cmp->bca_cmh.CHR(27).CHR(72)."\n";
    $cetak.=$ab.CHR(27).CHR(71).$cmp->bri_bdg.CHR(27).CHR(72)."\n";
    // $cetak.=$ba."5. Mohon untuk konfirmasi apabila sudah melakukan pembayaran ke :\n";
    // $cetak.=$bc.CHR(27).CHR(71)." HP 082216449371 (Telkomsel) / 08552322413 (Matrix) > UP : Grease S".CHR(27).CHR(71)."\n";
    // $cetak.=$bc.CHR(27).CHR(71)." WA 081324236922".CHR(27).CHR(71)."\n";
    /*$cetak.=$ab.CHR(15)."5. Mohon untuk konfirmasi apabila sudah melakukan pembayaran ke :\n";
    $cetak.=$ab.CHR(27).CHR(71)." HP 082216449371 (Telkomsel) / 08552322413 (Matrix) > UP : Grease S".CHR(27).CHR(72)."\n";
    $cetak.=$ab.CHR(27).CHR(71)." WA 081324236922".CHR(27).CHR(72)."\n";*/
}
      return $cetak;
   }
?>
