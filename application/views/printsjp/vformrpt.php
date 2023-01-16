<?php 
   include ("php/fungsi.php");
   require_once("printipp/PrintIPP.php");
   $cetak='';
   foreach($isi as $row)
   {
      $ipp        = new PrintIPP();
      $ipp->setHost($host);
      $ipp->setPrinterURI($uri);
      $ipp->setRawText();
      $ipp->unsetFormFeed();
      $nor        = str_repeat(" ",5);
      $abn        = str_repeat(" ",12);
      $ab         = str_repeat(" ",9);
      $hal        = 1;
      $tmp        = explode("-",$row->d_sjp);
      $th         = $tmp[0];
      $bl         = $tmp[1];
      $hr         = $tmp[2];
      $row->d_sjp = $hr." ".mbulan($bl)." ".$th;
      $xmp        = CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company);
      $ymp        = '';
      $i          = 0;
      $j          = 0;
      $hrg        = 0;
      $sj         = $row->i_sjp;
      $iarea      = $row->i_area;
      $isjtype    = '01';
      $query      = $this->db->query(" select * from dgu.tm_sjp_item where i_sjp='$sj' and i_area='$iarea'",false);
      $jml        = $query->num_rows();

      foreach($detail as $rowi)
      {
         $i++;
         $j++;
         $pro = $rowi->i_product;
         if(strlen($rowi->e_product_name )>46)
         {
            $nam = substr($rowi->e_product_name,0,46);
         }
         else
         {
            $nam = $rowi->e_product_name.str_repeat(" ",46-strlen($rowi->e_product_name ));
         }

         $del     = number_format($rowi->n_quantity_deliver);
         $pjg     = strlen($del);
         $spcdel  = 8;

         for($xx=1;$xx<=$pjg;$xx++)
         {
            $spcdel = $spcdel-1;
         }

         $aw   = 2;
         $pjg  = strlen($i);

         for($xx=1;$xx<=$pjg;$xx++)
         {
            $aw = $aw-1;
         }
         $aw = 1;
         $aw = str_repeat(" ",$aw);

         if(strlen($rowi->e_remark )>47)
         {
            $rem = substr($rowi->e_remark,0,47);
         }
         else
         {
            $rem = $rowi->e_remark.str_repeat(" ",47-strlen($rowi->e_remark ));
         }

         $cetak.=$nor.CHR(179).$aw." ".$i." ".CHR(179).$pro."  ".$nam.chr(179).str_repeat(" ",$spcdel).$del." ".chr(179)." ".$rem.CHR(179)."\n";

         if($jml>10)
         {
            if($i%15==0)
            {
               $cetak.=$nor.CHR(192).str_repeat(CHR(196),4).CHR(193).str_repeat(CHR(196),55).CHR(193).str_repeat(CHR(196),9).CHR(193).str_repeat(CHR(196),48).CHR(217)."\n";
               $cetak.=$nor.str_repeat(" ",62)."bersambung ......."."\n\n\n\n\n\n";
               $hal   = $hal+1;
               $ymp   = CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company);
               $cetak.= $ymp;
               $j     = 0;
            }
         }
      }

      if($j>10)
      {
         $cetak.=$nor.CHR(192).str_repeat(CHR(196),4).CHR(193).str_repeat(CHR(196),55).CHR(193).str_repeat(CHR(196),9).CHR(193).str_repeat(CHR(196),48).CHR(217)."\n";
         switch($j)
         {
            case 11:
               $tm="\n\n\n\n\n\n\n\n\n\n";
               break;
            case 12:
               $tm="\n\n\n\n\n\n\n\n\n";
               break;
            case 13:
               $tm="\n\n\n\n\n\n\n\n";
               break;
            case 14:
               $tm="\n\n\n\n\n\n\n";
               break;
         }
         $cetak.=$nor.str_repeat(" ",62)."bersambung .......".$tm;
         $hal   = $hal+1;
         $ymp   = CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company);
         $cetak.=$ymp;
         $j     = 0;
      }

      $cetak   = $xmp.$cetak;
      $zmp     = CetakFooter($row,$nor,$abn,$ab,$hrg,$j,$ipp);
      $cetak   = $cetak.$zmp;
   }
   $ipp->setdata($cetak);
   $ipp->printJob();
   echo "<script>this.close();</script>";
   // echo $cetak;

   function CetakHeader($row,$hal,$nor,$abn,$ab,$ipp,$company)
   {
      $cetak='';
      $cetak.=CHR(18);
      $cetak.=$nor.$company->name."\n\n";
      $cetak.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1)."S U R A T   J A L A N (P)             ".CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."KEPADA Yth.\n";
      $cetak.=$nor."No.".$row->i_sjp."                     ".$row->e_area_name."\n\n";
      $cetak.=$nor."Harap diterima barang-barang berikut ini:".CHR(15)."\n";
      $cetak.=$nor."                                                                                                             Hal : ".$hal."\n";
      $cetak.=$nor.CHR(218).str_repeat(CHR(196),4).CHR(194).str_repeat(CHR(196),55).CHR(194).str_repeat(CHR(196),9).CHR(194).str_repeat(CHR(196),48).CHR(191)."\n";
      $cetak.=$nor.CHR(179)."NO. ".CHR(179)." KODE                                                  ".CHR(179)." JUMLAH  ".CHR(179)." K  E  T  E  R  A  N  G  A  N                   ".CHR(179)."\n";
      $cetak.=$nor.CHR(179)."URUT".CHR(179)." BARANG         N A M A   B A R A N G                  ".CHR(179)." DIPESAN ".CHR(179)."                                                ".CHR(179)."\n";
      $cetak.=$nor.CHR(198).str_repeat(CHR(205),4).CHR(216).str_repeat(CHR(205),55).CHR(216).str_repeat(CHR(205),9).CHR(216).str_repeat(CHR(205),48).CHR(181)."\n";
      return $cetak;
   }
   
   function CetakFooter($row,$nor,$abn,$ab,$hrg,$j,$ipp)
   {
      $cetak='';
      $cetak.=$nor.CHR(192).str_repeat(CHR(196),4).CHR(193).str_repeat(CHR(196),55).CHR(193).str_repeat(CHR(196),9).CHR(193).str_repeat(CHR(196),48).CHR(217)."\n";
      $cetak.=$nor.str_repeat(' ',91)."Bandung, ".$row->d_sjp."\n";
      $cetak.=$nor."  Penerima                                           Mengetahui               Cek Akhir                 Pembuat    \n\n\n\n\n";
      $cetak.=$nor."(              )                                  (              )       (                 )        (              )\n";
      $tgl="TANGGAL CETAK : ".date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
      switch($j)
      {
         case 0:
            $tm="\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
            break;
         case 1:
            $tm="\n\n\n\n\n\n\n\n\n\n\n\n\n";
            break;
         case 2:
            $tm="\n\n\n\n\n\n\n\n\n\n\n\n";
            break;
         case 3:
            $tm="\n\n\n\n\n\n\n\n\n\n\n";
            break;
         case 4:
            $tm="\n\n\n\n\n\n\n\n\n\n";
            break;
         case 5:
            $tm="\n\n\n\n\n\n\n\n\n";
            break;
         case 6:
            $tm="\n\n\n\n\n\n\n\n";
            break;
         case 7:
            $tm="\n\n\n\n\n\n\n";
            break;
         case 8:
            $tm="\n\n\n\n\n\n";
            break;
         case 9:
            $tm="\n\n\n\n\n";
            break;
         case 10:
            $tm="\n\n\n\n";
            break;
         case 11:
            $tm="\n\n\n";
            break;
         case 12:
            $tm="\n\n";
            break;
         case 13:
            $tm="\n";
            break;
         case 14:
            $tm="";
            break;
         default:
            $tm="";
            break;
      }
      $cetak.=$nor.$tgl.$tm.CHR(18);
      return $cetak;
   }
?>
