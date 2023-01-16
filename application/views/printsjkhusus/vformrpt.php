<?php 
	include ("php/fungsi.php");
	require_once("printipp/PrintIPP.php");
  $cetak='';
	foreach($isi as $row){
		$nor	= str_repeat(" ",5);
		$abn	= str_repeat(" ",12);
		$ab		= str_repeat(" ",9);
		$hal	= 1;
		$ipp    = new PrintIPP();
		$ipp->setHost($host);
		$ipp->setPrinterURI($uri);
		$ipp->setRawText();
		$ipp->unsetFormFeed();
		$tmp=explode("-",$row->d_sj);
		$th=$tmp[0];
		$bl=$tmp[1];
		$hr=$tmp[2];
		$row->d_sj=$hr." ".substr(mbulan($bl),0,3)." ".$th;
		$xmp=CetakHeader($row,$hal,$nor,$abn,$ab,$ipp);
		$ymp='';
		$i	= 0;
		$j	= 0;
		$hrg    = 0;
		$sj			= $row->i_sj;
		$iarea	= substr($row->i_sj,8,2);
		$query 	= $this->db->query(" select * from tm_nota_item where i_sj='$sj'",false);
		$jml 	= $query->num_rows();
    $total=0;
		foreach($detail as $rowi){
      if($rowi->n_deliver>0){
			  $i++;
			  $j++;
        $group='';
     		$qu	= $this->db->query(" select i_customer_plugroup from tr_customer_plugroup 
                                 where i_customer='$row->i_customer'");
        if($qu->num_rows()>0){
          foreach($qu->result() as $ts){
            $group=$ts->i_customer_plugroup;
          }
          $qx	= $this->db->query("select i_customer_plu from tr_customer_plu
                                  where i_customer_plugroup='$group' and i_product='$rowi->i_product'");
          if($qx->num_rows()>0){
            foreach($qx->result() as $tx){
              $plu=$tx->i_customer_plu;
            }
          }
        }else{
          $plu='';
        }
			  $hrg	= $hrg+($rowi->n_deliver*$rowi->v_unit_price);
        if($rowi->i_product=='') $rowi->i_product=$rowi->product;
			  $pro	= $rowi->i_product;
        if($plu!=''){
          $pro=$plu;
          if(strlen($pro)>10){
				    $pro	= substr($pro,0,10);
			    }else{
				    $pro	= $pro.str_repeat(" ",10-strlen($pro));
			    }
        }
#        if($plu!=''){
#			    if(strlen($plu.' - '.$rowi->e_product_name )>65){
#				    $nam	= substr($plu.' - '.$rowi->e_product_name,0,65);
#			    }else{
#				    $nam	= $plu.' - '.$rowi->e_product_name.str_repeat(" ",65-strlen($plu.' - '.$rowi->e_product_name ));
#			    }
#        }else{
			    if(strlen($rowi->e_product_name )>65){
				    $nam	= substr($rowi->e_product_name,0,65);
			    }else{
				    $nam	= $rowi->e_product_name.str_repeat(" ",65-strlen($rowi->e_product_name ));
			    }
#        }
			  $del	= number_format($rowi->n_deliver);
			  $pjg	= strlen($del);
			  $spcdel	= 4;
			  for($xx=1;$xx<=$pjg;$xx++){
				  $spcdel	= $spcdel-1;
			  }
#			  $pric	= number_format($rowi->v_unit_price);
        if($row->f_plus_ppn=='t'){
					$pric	= number_format($rowi->v_unit_price);
				}else{
					$pric	= number_format($rowi->v_unit_price/1.1);
				}
			  $pjg	= strlen($pric);
			  $spcpric= 15;
			  for($xx=1;$xx<=$pjg;$xx++){
				  $spcpric= $spcpric-1;
			  }
#			  $tot	= number_format($rowi->n_deliver*$rowi->v_unit_price);
				if($row->f_plus_ppn=='t'){
					$tot	= $rowi->n_deliver*$rowi->v_unit_price;
				}else{
					$tot	= $rowi->n_deliver*($rowi->v_unit_price/1.1);
				}
			  $pjg	= strlen(number_format($tot));
			  $spctot = 20;
			  for($xx=1;$xx<=$pjg;$xx++){
				  $spctot	= $spctot-1;
			  }
			  $aw	= 3;
			  $pjg	= strlen($i);
			  for($xx=1;$xx<=$pjg;$xx++){
				  $aw=$aw-1;
			  }+
			  $aw=str_repeat(" ",$aw);
        $total	= $total+$tot;
        $tot=number_format($tot);
			  $cetak.=$ab.$aw." ".$i.str_repeat(" ",6).$pro." ".$nam." ".str_repeat(" ",$spcdel).$del.str_repeat(" ",$spcpric).$pric.str_repeat(" ",$spctot)." ".$tot."\n";			  
			  if($jml>42){
				  if(($i%51)==0){
					  $cetak.=CHR(18).$nor.str_repeat('-',73)."\n";					  		
					  $cetak.=$nor.str_repeat(" ",40)."bersambung ......."."\n";					  		
					  $hal=$hal+1;
					  $ymp=CetakHeader($row,$hal,$nor,$abn,$ab,$ipp);
					  $j	= 0;
				  }elseif(
					  (($i<51)&&($i==$jml)) 
					  ){
					  $cetak.=CHR(18).$nor.str_repeat('-',73)."\n";					  
					  $cetak.=$nor.str_repeat(" ",40)."bersambung ......."."\n";					  		
					  $hal=$hal+1;
					  $ymp=CetakHeader($row,$hal,$nor,$abn,$ab,$ipp);
					  $j	= 0;
				  }
			  }	
      }
		}
		$cetak=$xmp.$cetak.$ymp;
		$zmp=CetakFooter($row,$nor,$abn,$ab,$hrg,$j,$ipp,$total);
		$cetak=$cetak.$zmp;
	}
#	echo $cetak;
  $ipp->setFormFeed();
  $ipp->setdata($cetak);
  $ipp->printJob();
	echo "<script>this.close();</script>";

	function CetakHeader($row,$hal,$nor,$abn,$ab,$ipp){
		$cetak =CHR(18);		
		$cetak.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1).NmPerusahaan."            ".CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."KEPADA Yth.\n";		
		$cetak.=$nor.AlmtPerusahaan."                    ".$row->e_customer_name."\n";		
		if(strlen($row->e_customer_address)<35){
			$cetak.=$nor."Telp.: ".TlpPerusahaan."                    ".$row->e_customer_address."\n";
		}else{
			$cetak.=$nor."Telp.: ".TlpPerusahaan."                    ".CHR(15).$row->e_customer_address.CHR(18)."\n";
		}		
		$cetak.=$nor."Fax  : ".FaxPerusahaan."                    ".$row->e_customer_city."\n";		
		$cetak.=$nor."NPWP : ".NPWPPerusahaan."            Telp. ".$row->e_customer_phone."\n\n";		
		$cetak.=$nor.CHR(27).CHR(120).CHR(1).CHR(27).CHR(119).CHR(1)."No. Surat Jalan: ".$row->i_sj.CHR(27).CHR(120).CHR(0).CHR(27).CHR(119).CHR(0)."\n";		
		$cetak.=$nor."No. PO         : ".$row->i_spb_po."\n";		
		$cetak.=$nor."No. SPB        : ".$row->i_spb."\n\n";		
		$cetak.=$nor."Harap diterima barang-barang berikut ini:\n";		
		$cetak.=$nor.CHR(218).str_repeat(CHR(196),72).CHR(191)."\n";		
		$cetak.=$nor.CHR(179)."NO.  KD-BARANG       NAMA BARANG                UNIT   HARGA     JUMLAH ".CHR(179)."\n";		
		$cetak.=$nor.CHR(212).str_repeat(CHR(205),72).CHR(190).CHR(15)."\n";		
		return $cetak;
	}
	function CetakFooter($row,$nor,$abn,$ab,$hrg,$j,$ipp,$total){
		$cetak =CHR(18);		
		$cetak.=$nor.str_repeat('-',74)."\n";
#    if($row->f_spb_consigment=='f'){
      $row->v_nota_gross=$total;
#    }
		$gro	= number_format($row->v_nota_gross);
		$pjg	= strlen($gro);
		$spcgro = 13;
		for($xx=1;$xx<=$pjg;$xx++){
			$spcgro= $spcgro-1;
		}
###
    if($row->n_nota_discount1==0)
      $vdisc1=$row->v_nota_discounttotal;
    else
      $vdisc1=0;
    $vdisc2=0;
    $vdisc3=0;
    $vdisc4=0;
    if($row->n_nota_discount1>0) $vdisc1=$vdisc1+($total*$row->n_nota_discount1)/100;
	  $vdisc2=$vdisc2+((($total-$vdisc1)*$row->n_nota_discount2)/100);
	  $vdisc3=$vdisc3+((($total-($vdisc1+$vdisc2))*$row->n_nota_discount3)/100);
  	$vdisc4=$vdisc4+((($total-($vdisc1+$vdisc2+$vdisc3))*$row->n_nota_discount4)/100);
    $vdistot	= round($vdisc1+$vdisc2+$vdisc3+$vdisc4);
    if( ($row->f_plus_ppn=='f') && ($row->n_nota_discount1==0) ){
      $vdistot=$vdistot/1.1;
    }
    if($row->f_spb_consigment=='f'){
      $row->v_nota_discounttotal=$vdistot;
    }else{
      $row->v_nota_discounttotal=$row->v_nota_discounttotal/1.1;
    }
###
		$dis	= number_format($row->v_nota_discounttotal);
		$pjg	= strlen($dis);
		$spcdis = 13;
		for($xx=1;$xx<=$pjg;$xx++){
			$spcdis= $spcdis-1;
		}
		$tot	= number_format($row->v_nota_gross-$row->v_nota_discounttotal);
		$pjg	= strlen($tot);
		$spctot = 13;
		for($xx=1;$xx<=$pjg;$xx++){
			$spctot= $spctot-1;
		}
		$cetak.=$nor.str_repeat(" ",45)."TOTAL        : ".str_repeat(" ",$spcgro).number_format($row->v_nota_gross)."\n";
		$cetak.=$nor.str_repeat(" ",45)."POTONGAN     : ".str_repeat(" ",$spcdis).number_format($row->v_nota_discounttotal)."\n";		
####
    if($row->f_plus_ppn=='f'){
      $vppn=(round($row->v_nota_gross)-round($vdistot))*0.1;
      $row->v_nota_ppn=$vppn;
			if(strlen(number_format($row->v_nota_ppn))==1){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :             ".number_format($row->v_nota_ppn)."\n";				
			}elseif(strlen(number_format($row->v_nota_ppn))==5){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :         ".number_format($row->v_nota_ppn)."\n";				
			}elseif(strlen(number_format($row->v_nota_ppn))==6){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :        ".number_format($row->v_nota_ppn)."\n";				
			}elseif(strlen(number_format($row->v_nota_ppn))==7){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :       ".number_format($row->v_nota_ppn)."\n";				
			}elseif(strlen(number_format($row->v_nota_ppn))==8){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :      ".number_format($row->v_nota_ppn)."\n";				
			}elseif(strlen(number_format($row->v_nota_ppn))==9){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :     ".number_format($row->v_nota_ppn)."\n";				
			}elseif(strlen(number_format($row->v_nota_ppn))==10){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :    ".number_format($row->v_nota_ppn)."\n";				
			}elseif(strlen(number_format($row->v_nota_ppn))==11){
				$cetak.=str_repeat(" ",50)."PPN (10%)    :   ".number_format($row->v_nota_ppn)."\n";				
			}
		}
####
		$cetak.=$nor.str_repeat(' ',59)."--------------- -\n";
####
    if($row->f_plus_ppn=='f'){
#      if($row->f_spb_consigment=='t'){
#        $row->v_nota_netto=round($total)-round($vdistot)+round($vppn);
#      }else{
        $row->v_nota_netto=round($total)-round($vdistot)+$vppn;
#      }
#      $row->v_nota_netto=$row->v_nota_gross-$row->v_nota_discounttotal+$ppn;
    	$cetak.=$nor.str_repeat(" ",45)."NILAI        : ".str_repeat(" ",$spctot).number_format($row->v_nota_netto)."\n\n";
    }else{
      $row->v_nota_netto=$row->v_nota_gross-$row->v_nota_discount;
    	$cetak.=$nor.str_repeat(" ",45)."NILAI        : ".str_repeat(" ",$spctot).number_format($row->v_nota_gross-$row->v_nota_discounttotal)."\n\n";
    }
####
#		$cetak.=$nor.str_repeat(" ",45)."NILAI        : ".str_repeat(" ",$spctot).number_format($row->v_nota_gross-$row->v_nota_discounttotal)."\n\n";
		$cetak.=$nor.str_repeat(' ',53)."Bandung, ".$row->d_sj."\n\n";		
		$cetak.=$nor."  Penerima          Mengetahui           Pengirim              Pembuat      "."\n\n\n\n\n";		
		$cetak.=$nor."(          )      (            )      (             )       (           )\n\n";		
		$tgl=date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
		$cetak.=CHR(27).CHR(120).CHR(1).str_repeat(" ",25).CHR(218).str_repeat(CHR(196),35).CHR(191).CHR(27).CHR(120).CHR(0)."\n";
      	$cetak.=CHR(27).CHR(120).CHR(1).str_repeat(" ",25).CHR(179)."           P E N T I N G           ".CHR(179).CHR(27).CHR(120).CHR(0)."\n";
      	$cetak.=CHR(27).CHR(120).CHR(1).str_repeat(" ",25).CHR(179)." CLAIM KEKURANGAN / TOLAKAN BARANG ".CHR(179).CHR(27).CHR(120).CHR(0)."\n";
      	$cetak.=CHR(27).CHR(120).CHR(1).str_repeat(" ",25).CHR(179)."MAX 3 HARI SETELAH BARANG DI TERIMA".CHR(179).CHR(27).CHR(120).CHR(0)."\n";
      	$cetak.=CHR(27).CHR(120).CHR(1).str_repeat(" ",25).CHR(192).str_repeat(CHR(196),35).CHR(217).CHR(27).CHR(120).CHR(0)."\n\n";
      	$cetak.=$nor.str_repeat(" ",1)."Catatan : Konfirmasi pembayaran hubungi telp\n";
      	$cetak.=$nor.str_repeat(" ",11)."(".$row->e_area_phone.")\n";
		return $cetak;		
	}
?>

