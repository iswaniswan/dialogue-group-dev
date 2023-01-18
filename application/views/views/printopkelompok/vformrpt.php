<?php 
    include ("php/fungsi.php");
    require_once("printipp/PrintIPP.php");
    $isi=$master;
  $cetak='';
  if($isi){
      foreach($isi as $row){
        if($row->n_print > 0){
            die();
          }
          $nor  = str_repeat(" ",5);
          $abn  = str_repeat(" ",12);
          $ab   = str_repeat(" ",9);
          $hal  = 1;
          $ipp    = new PrintIPP();
          $ipp->setHost($host);
          $ipp->setPrinterURI($uri);
          $ipp->setRawText();
          $ipp->unsetFormFeed();
          $cetak.=CHR(18);      
          $tmp=explode("-",$row->d_op);
          $th=$tmp[0];
          $bl=$tmp[1];
          $hr=$tmp[2];
          $dop=$hr." ".mbulan($bl)." ".$th;
          $cetak.=$nor.CHR(27).CHR(71).$company->name."                   ".$company->kota_company.", ".$dop.CHR(27).CHR(72)."\n";        
          $cetak.=$nor.CHR(27).CHR(71).$company->alamat_company.CHR(27).CHR(72)."\n\n";       
          $cetak.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1).CHR(14)."ORDER PEMBELIAN".CHR(20).CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."               Kepada Yth.\n";        
          $cetak.=$nor.str_repeat(" ",45).strtoupper($row->e_supplier_name)."\n";       
          $cetak.=$nor."Nomor   : ".$row->i_op."/".$row->i_area."-".$row->e_area_shortname."              ".CHR(15).$row->e_supplier_address.CHR(18)."\n";      
          if(substr($row->i_reff,0,3)=='SPB'){
              $cetak.=$nor."No.SPmB : ".$row->i_reff."                    ".$row->e_supplier_city.CHR(15)."\n";
          }else{
              $cetak.=$nor."No.SPmB : ".$row->i_reff."                   ".$row->e_supplier_city.CHR(15)."\n";
          }     
          $cetak.=$nor.str_repeat(" ",126)."Hal:".$hal."\n";        
          $cetak.=$ab.CHR(218).str_repeat(CHR(196),5).CHR(194).str_repeat(CHR(196),17).CHR(194).str_repeat(CHR(196),11).CHR(194).str_repeat(CHR(196),46).CHR(194).str_repeat(CHR(196),44).CHR(191)."\n";        
          $cetak.=$ab.CHR(179)." NO. ".CHR(179)."     BANYAK      ".CHR(179)." KODE      ".CHR(179)." NAMA BARANG                                  ".CHR(179)." KETERANGAN                                 ".CHR(179)."\n";     
          $cetak.=$ab.CHR(198).str_repeat(CHR(205),5).CHR(216).str_repeat(CHR(205),8).CHR(209).str_repeat(CHR(205),8).CHR(216).str_repeat(CHR(205),11).CHR(216).str_repeat(CHR(205),46).CHR(216).str_repeat(CHR(205),44).CHR(181)."\n";     
          $i    = 0;
          $j    = 0;
          $hrg= 0;
          $op   = $row->i_op;
  #     $query  = $this->db->query(" select * from dgu.tm_op_item where i_op='$op'",false);
         $this->db->query("update dgu.tm_op set n_print = n_print + 1 where i_op = '$op'");
      if(substr($row->i_reff,0,3)=='SPB'){
            $query  = $this->db->query("select a.i_op from dgu.tm_op_item a, dgu.tm_spb_item b, dgu.tm_op c where a.i_op='$op'
                                    and a.i_op=c.i_op
                                    and a.i_op=b.i_op and c.i_reff=b.i_spb and c.i_area=b.i_area 
                                    and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                                    and a.i_product_grade=b.i_product_grade",false);
      }else{
            $query  = $this->db->query("select a.i_op from dgu.tm_op_item a, dgu.tm_spmb_item b, dgu.tm_op c where a.i_op='$op'
                                    and a.i_op=c.i_op
                                    and a.i_op=b.i_op and c.i_reff=b.i_spmb and c.i_area=b.i_area 
                                    and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                                    and a.i_product_grade=b.i_product_grade",false);
      }
          $jml  = $query->num_rows();
          $detail   = $this->mmaster->bacadetail($row->i_op);
          foreach($detail as $rowi){
              $i++;
              $j++;
              $hrg  = $hrg+($rowi->n_order*$rowi->v_product_mill);
              $pro  = $rowi->i_product;
              if(strlen($rowi->e_product_name )>45){
                  $nam  = substr($rowi->e_product_name,0,45);
              }else{
                  $nam  = $rowi->e_product_name.str_repeat(" ",45-strlen($rowi->e_product_name ));
              }         
  #         $mot    = $rowi->e_product_motifname." ".str_repeat(" ",42-strlen($rowi->e_product_motifname ));
              if(strlen($rowi->e_remark) > 42){
                $mot    = $rowi->e_remark." ".str_repeat(" ",1);
              }else{
                  $mot  = $rowi->e_remark." ".str_repeat(" ",42-strlen($rowi->e_remark ));
              }
              $ord  = number_format($rowi->n_order);
              $pjg  = strlen($ord);
              $spcord   = 7;
              for($xx=1;$xx<=$pjg;$xx++){
                  $spcord   = $spcord-1;
              }
              $pric = number_format($rowi->v_product_mill);
              $pjg  = strlen($pric);
              $spcpric= 18;
              for($xx=1;$xx<=$pjg;$xx++){
                  $spcpric= $spcpric-1;
              }
              $tot  = number_format($rowi->n_order*$rowi->v_product_mill);
              $pjg  = strlen($tot);
              $spctot = 19;
              for($xx=1;$xx<=$pjg;$xx++){
                  $spctot   = $spctot-1;
              }
              $aw       = 3;
              $pjg  = strlen($i);
              for($xx=1;$xx<=$pjg;$xx++){
                  $aw=$aw-1;
              }
              $aw=str_repeat(" ",$aw);
              $cetak.=$ab.CHR(179).$aw.$i.str_repeat(" ",2).CHR(179).CHR(27).CHR(45).CHR(1).str_repeat(" ",8).CHR(179).str_repeat(" ",$spcord).$ord." ".CHR(27).CHR(45).CHR(0).CHR(179)."  ".$pro."  ".CHR(179)." ".$nam.CHR(179)." ".$mot.CHR(179)."\n";
              if($jml>10){
                  if(($i%18)==0){
                      $cetak.=$ab.CHR(192).str_repeat(CHR(196),5).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),11).CHR(193).str_repeat(CHR(196),46).CHR(193).str_repeat(CHR(196),44).CHR(217)."\n";                         
                      $cetak.=$ab.str_repeat(" ",84)."bersambung ......."."\n\n\n";                         
                      $hal=$hal+1;
                      $cetak.=CetakHeader($row,$hal,$nor,$abn,$ab,$host,$uri,$ipp,$cetak);
                      $j    = 0;
                  }elseif(
                      (($i<18)&&($i==$jml)) 
                      ){
                      $cetak.=$ab.CHR(192).str_repeat(CHR(196),5).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),11).CHR(193).str_repeat(CHR(196),46).CHR(193).str_repeat(CHR(196),44).CHR(217)."\n";                 
                      $zz="\n\n\n";
#                     for($yy=14;$yy!=$i;$yy--){
                      for($yy=18;$yy!=$i;$yy--){
                          $zz=$zz."\n";
                      }
                      $cetak.=$ab.str_repeat(" ",84)."bersambung .......".$zz;                          
                      $hal=$hal+1;
                      $cetak.=CetakHeader($row,$hal,$nor,$abn,$ab,$host,$uri,$ipp,$cetak);
                      $j    = 0;
                  }
              } 
          }
          $cetak.=CHR(15);
          $cetak.=$ab.CHR(192).str_repeat(CHR(196),5).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),8).CHR(193).str_repeat(CHR(196),11).CHR(193).str_repeat(CHR(196),46).CHR(193).str_repeat(CHR(196),44).CHR(217)."\n";             
          $cetak.=$ab.str_repeat(" ",84)."    Hormat kami,             Menyetujui,"."\n";               
          $bilangan = new Terbilang;
          $quer     = $this->db->query("select * from dgu.tr_supplier
                                      where i_supplier='$row->i_supplier'",false);
        if($quer->num_rows()>0){
          foreach($quer->result() as $xx){
          if($xx->n_supplier_discount!=0 && $xx->n_supplier_discount!=null){
            $yy=($hrg*$xx->n_supplier_discount)/100;
            $hrg=$hrg-$yy;
          }
          if($xx->n_supplier_discount2!=0 && $xx->n_supplier_discount2!=null){
            $yy=($hrg*$xx->n_supplier_discount2)/100;
            $hrg=$hrg-$yy;
          }
          }
        }
          $kata=ucwords($bilangan->eja($hrg));  
          $hrg=number_format($hrg);
          $cetak.=$ab."Jumlah Total  : Rp. ".$hrg."\n";             
          $cetak.=$ab."( ".$kata." RUPIAH)\n\n";                
          $tmp = explode("-", $row->d_op);
          $det  = $tmp[2];
          $mon  = $tmp[1];
          $yir  = $tmp[0];
          $dop  = $yir."/".$mon."/".$det;
          $dudet    =dateAdd("d",$row->n_delivery_limit,$dop);
          $dudet    = explode("-", $dudet);
          $det1 = $dudet[2];
          $mon1 = $dudet[1];
          $yir1     = $dudet[0];
          $dop  = $det1." ".mbulan($mon1)." ".$yir1;
          $pjg  = strlen($ab."Batas Pengiriman Terakhir : ".$dop);
          $spc1 = str_repeat(" ",92-$pjg);
  #     $ttd1   = 'Agnes F.S.';
          $pjg  = strlen(TtdOP);
          $a=20-$pjg;
          $b=$a/2;
          if(($a%2)!=0){
              $b=$b-0.5;
          }
          $spcttd=str_repeat(" ",$b);
          $spcttx=str_repeat(" ",(20-$pjg)-$b);
          $cetak.=$ab."Batas Pengiriman Terakhir : ".$dop.$spc1.CHR(27).CHR(45).CHR(1).str_repeat(" ",20).CHR(27).CHR(45).CHR(0)."     ".CHR(27).CHR(45).CHR(1).$spcttd.TtdOP.$spcttx.CHR(27).CHR(45).CHR(0)."\n";              
          if($row->n_top_length>0)
              $bayar= "Kredit ".$row->n_top_length." hari";
          else
              $bayar= "Tunai";
          $pjg  = strlen($ab."Cara Pembayaran           : ".$bayar);
          $spc1 = str_repeat(" ",93-$pjg);
          $cetak.=$ab."Cara Pembayaran           : ".$bayar.$spc1."  Adm. Pembelian                 MD         \n\n".CHR(18);               
          $cetak.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1).CHR(14).$row->e_op_statusname.CHR(20).CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."\n";      
          $cetak.=$nor.CHR(27).CHR(120).CHR(1).strtoupper($row->e_op_remark).CHR(27).CHR(120).CHR(0)."\n".CHR(15);              
          $tgl=date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
          if($j>0){
            $tm="\n";

              switch($j){
              case 1:
                  $tm="\n\n\n\n\n\n\n\n\n\n\n";
                  break;
              case 2:
                  $tm="\n\n\n\n\n\n\n\n\n\n";
                  break;
              case 3:
                  $tm="\n\n\n\n\n\n\n\n\n";
                  break;
              case 4:
                  $tm="\n\n\n\n\n\n\n\n";
                  break;
              case 5:
                  $tm="\n\n\n\n\n\n\n";
                  break;
              case 6:
                  $tm="\n\n\n\n\n\n";
                  break;
              case 7:
                  $tm="\n\n\n\n\n";
                  break;
              case 8:
                  $tm="\n\n\n\n";
                  break;
              case 9:
                  $tm="\n\n\n";
                  break;
              case 10:
                  $tm="\n\n";
                  break;
              }         
              $cetak.=$ab."TANGGAL CETAK : ".$tgl.CHR(18).$tm;          
          }else{
              $tm="\n\n\n\n\n\n\n\n\n\n\n";
              $cetak.=$ab."TANGGAL CETAK : ".$tgl."\n".CHR(18).$tm;         
          }
      $this->db->query("update dgu.tm_op set n_op_print=n_op_print+1 where i_op='$op'",false);
      }
    $ipp->setdata($cetak);
    $ipp->printJob();
    echo "<script>this.close();</script>";
   // echo $cetak;
  }else{
    echo 'Data tidak ditemukan !!!';
  }
    function CetakHeader($row,$hal,$nor,$abn,$ab,$host,$uri,$ipp){
        $ipp->unsetFormFeed();
        $cetek=CHR(18);     
        $tmp=explode("-",$row->d_op);
        $th=$tmp[0];
        $bl=$tmp[1];
        $hr=$tmp[2];
        $dop=$hr." ".mbulan($bl)." ".$th;
        $cetek.=$nor.CHR(27).CHR(71).NmPerusahaan."                   BANDUNG, ".$dop.CHR(27).CHR(72)."\n";     
        $cetek.=$nor.CHR(27).CHR(71).AlmtPerusahaan.CHR(27).CHR(72)."\n\n";     
        $cetek.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1).CHR(14)."ORDER PEMBELIAN".CHR(20).CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."               Kepada Yth.\n";      
        $cetek.=$nor.str_repeat(" ",45).strtoupper($row->e_supplier_name)."\n";     
        $cetek.=$nor."Nomor   : ".$row->i_op."/".$row->i_area."-".$row->e_area_shortname."              ".CHR(15).$row->e_supplier_address.CHR(18)."\n";        
        $cetek.=$nor."No.SPmB : ".$row->i_reff."                    ".$row->e_supplier_city.CHR(15)."\n";       
        $cetek.=$nor.str_repeat(" ",126)."Hal:".$hal."\n";      
        $cetek.=$ab.CHR(218).str_repeat(CHR(196),5).CHR(194).str_repeat(CHR(196),17).CHR(194).str_repeat(CHR(196),11).CHR(194).str_repeat(CHR(196),46).CHR(194).str_repeat(CHR(196),44).CHR(191)."\n";      
        $cetek.=$ab.CHR(179)." NO. ".CHR(179)."     BANYAK      ".CHR(179)." KODE      ".CHR(179)." NAMA BARANG                                  ".CHR(179)." KETERANGAN                                 ".CHR(179)."\n";       
        $cetek.=$ab.CHR(198).str_repeat(CHR(205),5).CHR(216).str_repeat(CHR(205),8).CHR(209).str_repeat(CHR(205),8).CHR(216).str_repeat(CHR(205),11).CHR(216).str_repeat(CHR(205),46).CHR(216).str_repeat(CHR(205),44).CHR(181)."\n";
    return $cetek;
    }
?>
