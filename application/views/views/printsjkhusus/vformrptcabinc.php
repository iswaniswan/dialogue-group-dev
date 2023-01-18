<?php 
 	include ("php/fungsi.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
</head>
<body>
<style type="text/css" media="all">
/*
@page land {size: landscape;}
*/
*{
size: landscape;
}

.huruf {
  FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
}
.miring {
  font-style: italic;
  
}
.ceKotak{-
	background-color:#f0f0f0;
	border-bottom:#000000 1px solid;
	border-top:#000000 1px solid;
	border-left:#000000 1px solid;
	border-right:#000000 1px solid;
}
.garis { 
	background-color:#000000;
	width: 100%;
  height: 50%;
	font-size: 100px;
  border-style: solid;
  border-width:0.01px;
  border-collapse: collapse;
  spacing:1px;
}
.garis td { 
	background-color:#FFFFFF;
  border-style: solid;
  border-width:0.01px;
	font-size: 10px;
  FONT-WEIGHT: normal; 
  padding:1px;
}
.garisx { 
	background-color:#000000;
	width: 100%;
  height: 50%;
  border-style: none;
  border-collapse: collapse;
  spacing:1px;
}
.garisx td { 
	background-color:#FFFFFF;
  border-style: none;
	font-size: 10px;
  FONT-WEIGHT: normal; 
  padding:1px;
}
.judul {
  font-size: 20px;
  FONT-WEIGHT: normal; 
}
.nmper {
  font-size: 18px;
  FONT-WEIGHT: normal; 
}
.isi {
  font-size: 14px;
  font-weight:normal;
  padding:1px;
}
.eusinya {
  font-size: 10px;
  font-weight:normal;
}
.ici {
  font-size: 12px;
  font-weight:normal;
}
.garisbawah { 
	border-top:#000000 0.1px solid;
}
</style>
<style type="text/css" media="print">
  @page 
    {
        size: 8.5in 11in;   /* auto is the initial value */
        margin: 0.6cm;  /* this affects the margin in the printer settings */
    }
.pagebreak {
    page-break-before: always;
}
.noDisplay {
       display: none;
    }
</style>

<?php 
foreach($isi as $row)
{
  $row->f_plus_ppn = 't';
?>
  <table width="100%" border="0" class="eusinya">
    <tr>
      <td colspan="3" class="huruf isi" ><?php echo NmPerusahaan; ?></td>
      <td width="26">&nbsp;</td>
      <td width="86">&nbsp;</td>
      <td width="36">&nbsp;</td>
      <td width="354" class="huruf isi">KEPADA Yth. </td>
      <td width="87">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="huruf isi"><?php echo AlmtPerusahaan; ?> </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="huruf isi"><?php echo $row->e_customer_name; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="53" class="huruf isi">Telp.</td>
      <td width="8" class="huruf isi">:</td>
      <td width="359" class="huruf isi"><?php echo TlpPerusahaan; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <?php 
      if(strlen($row->e_customer_address)<35){
      ?>
        <td class="huruf isi"><?php echo $row->e_customer_address; ?></td>
      <?php 
      }else{
      ?>
        <td class="huruf isi"><?php echo $row->e_customer_address; ?></td>
      <?php 
      }
      ?>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="huruf isi">Fax</td>
      <td class="huruf isi">:</td>
      <td class="huruf isi"><?php echo FaxPerusahaan;?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="huruf isi"><?php echo $row->e_customer_city; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="huruf isi">NPWP</td>
      <td class="huruf isi">:</td>
      <td class="huruf isi">01.548.571.7.441.000</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="huruf isi"><?php echo "Telp. ".$row->e_customer_phone; ?></td>
      <td>&nbsp;</td>
    </tr>
</table>
  <table width="506" border="0" class="eusinya">
    <tr>
      <td width="28" class="huruf isi">No. </td>
      <td width="140" class="huruf isi">Surat Jalan </td>
      <td width="8" class="huruf isi">:</td>
      <td width="302" class="huruf isi"><?php echo $row->i_sj; ?></td>
    </tr>
    <tr>
      <td class="huruf ici">No. </td>
      <td class="huruf ici">PO</td>
      <td class="huruf ici">:</td>
      <td class="huruf ici"><?php echo $row->i_spb_po; ?></td>
    </tr>
    <tr>
      <td class="huruf ici">No. </td>
      <td class="huruf ici">SPB</td>
      <td class="huruf ici">:</td>
      <td class="huruf ici"><?php echo $row->i_spb; ?></td>
    </tr>
		 <tr>
      <td colspan=4 class="huruf ici">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" class="huruf ici">Harap diterima barang-barang berikut ini : </td>
    </tr>
  </table>
  <table width="100%" border="0" class="ceKotak huruf ici">
    <tr bordercolor="1">
      <td width="24">No.</td>
      <td width="58">KD-BARANG</td>
      <td width="850" align=center>NAMA BARANG</td>
      <td align=center width="90">UNIT</td>
      <td align=center width="100">HARGA</td>
      <td align=center width="110">JUMLAH</td>
    </tr>
</table>
<?php 
  $i	= 0;
	$j	= 0;
	$hrg    = 0;
	$sj			= $row->i_sj;
	$iarea	= substr($row->i_sj,8,2);
	$query 	= $this->db->query(" select * from tm_nota_item where i_sj='$sj' and i_area='$iarea'",false);
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
	    if(strlen($rowi->e_product_name )>65){
		    $nam	= substr($rowi->e_product_name,0,65);
	    }else{
		    $nam	= $rowi->e_product_name.str_repeat(" ",65-strlen($rowi->e_product_name ));
	    }			
		  $del	= number_format($rowi->n_deliver);
		  $pjg	= strlen($del);
		  $spcdel	= 4;
		  for($xx=1;$xx<=$pjg;$xx++){
			  $spcdel	= $spcdel-1;
		  }
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
		  }
		  $aw=str_repeat(" ",$aw);
      $total	= $total+$tot;
      $tot=number_format($tot);
?>
      <table width="98%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="1" width="3%" class="huruf ici" align=right><?php echo $i; ?></td>
          <td height="1" width="12%" class="huruf ici">&nbsp;&nbsp;<?php echo $pro; ?></td>
          <td height="1" width="57%" class="huruf ici">&nbsp;&nbsp;<?php echo $nam; ?></td>
          <td height="1" align=center width="7%" class="huruf ici"><?php echo $del; ?></td>
          <td height="1" align=right width="9%" class="huruf ici"><?php echo $pric; ?></td>
          <td height="1" align=right width="12%" class="huruf ici"><?php echo $tot; ?></td>
        </tr>
      </table>
<?php 
    }
  }

  $row->v_nota_gross=$total;
	$gro	= number_format($row->v_nota_gross);
  
  if($row->n_nota_discount1==0)
    $vdisc1=$row->v_nota_discounttotal;
  else
    $vdisc1=0;
  //if( ($row->n_nota_discount1+$row->n_nota_discount3+$row->n_nota_discount3+$row->n_nota_discount4==0) && $row->v_nota_discounttotal <> 0 )
  //{
     // $vdisc1=$row->v_nota_discounttotal;
      $vdisc2=0;
      $vdisc3=0;
      $vdisc4=0;
  //} else {
    if($row->n_nota_discount1>0) $vdisc1=$vdisc1+($total*$row->n_nota_discount1)/100;
    $vdisc2=$vdisc2+((($total-$vdisc1)*$row->n_nota_discount2)/100);
    $vdisc3=$vdisc3+((($total-($vdisc1+$vdisc2))*$row->n_nota_discount3)/100);
	  $vdisc4=$vdisc4+((($total-($vdisc1+$vdisc2+$vdisc3))*$row->n_nota_discount4)/100);
  //}
    $vdistot	= round($vdisc1+$vdisc2+$vdisc3+$vdisc4);
    if( ($row->f_plus_ppn=='f') && ($row->n_nota_discount1==0) ){
      $vdistot=$vdistot/1.1;
    }
    $row->v_nota_discounttotal=$vdistot;
	  $dis	= number_format($row->v_nota_discounttotal);
	  $tot	= number_format($row->v_nota_gross-$row->v_nota_discounttotal);
?>
  <table width="100%" border="0" class="huruf ici">
    <tr>
      <td colspan=6 class="garisbawah">&nbsp;</td>
    </tr>
    <tr>
      <td width="2%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="61%">&nbsp;</td>
      <td width="7%" align=left>TOTAL</td>
      <td width="9%" align=center>:</td>
      <td align=right width="10%"><?php echo number_format($row->v_nota_gross); ?></td>
      <td width="12%">&nbsp;</td>
    </tr>
    <tr>
      <td width="2%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="61%">&nbsp;</td>
      <td width="7%" align=left>POTONGAN</td>
      <td width="9%" align=center>:</td>
      <td align=right width="10%"><?php echo number_format($row->v_nota_discounttotal); ?></td>
      <td>&nbsp;</td>
    </tr>

<?php 	
    if($row->f_plus_ppn=='f'){
      $vppn=(round($row->v_nota_gross)-round($vdistot))*0.1;
      $row->v_nota_ppn=$vppn;
?>
      <tr>
        <td width="2%">&nbsp;</td>
        <td width="9%">&nbsp;</td>
        <td width="61%">&nbsp;</td>
        <td width="7%" align=left>PPN (10%)</td>
        <td width="9%" align=center>:</td>
        <td align=right  width="10%"><?php echo number_format($row->v_nota_discounttotal); ?></td>
        <td>&nbsp;</td>
      </tr>
<?php 
    }
?>
    <tr>
      <td width="2%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="61%">&nbsp;</td>
      <td width="7%" align=left>&nbsp;</td>
      <td width="9%" align=center></td>
      <td align=right  width="10%">--------------</td>
      <td>-</td>
    </tr>
<?php 
    if($row->f_plus_ppn=='f'){
      $row->v_nota_netto=round($total)-round($vdistot)+$vppn;
?>
    <tr>
      <td width="2%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="61%">&nbsp;</td>
      <td width="7%" align=left>NILAI</td>
      <td width="9%" align=center>:</td>
      <td align=right  width="10%"><?php echo number_format($row->v_nota_netto); ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
<?php 
    }else{
      $row->v_nota_netto=$row->v_nota_gross-$row->v_nota_discount;
?>
    <tr>
      <td width="2%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="61%">&nbsp;</td>
      <td width="7%" align=left>NILAI</td>
      <td width="9%" align=center>:</td>
      <td align=right  width="10%"><?php echo number_format($row->v_nota_gross-$row->v_nota_discounttotal); ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>

<?php 
    }
?>
<?php 
		$tmp=explode("-",$row->d_sj);
		$th=$tmp[0];
		$bl=$tmp[1];
		$hr=$tmp[2];
		$row->d_sj=$hr." ".substr(mbulan($bl),0,3)." ".$th;
?>
  <table width="100%" border="0">
    <tr>
      <td colspan="2" align=right width="20%" class="huruf ici">Bandung, <?php echo $row->d_sj; ?></td>
    </tr>
  </table>
  <table width="100%" border="0">
    <tr>
      <td colspan="2" align=center class="huruf ici">Penerima</td>
      <td colspan="2" align=center class="huruf ici">Mengetahui</td>
      <td colspan="2" align=center class="huruf ici">Pengirim</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align=center class="huruf ici">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td align=center class="huruf ici">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
      <td align=center class="huruf ici">(</td>
      <td align=center class="huruf ici">)</td>
      <td align=center class="huruf ici">(</td>
      <td align=center class="huruf ici">)</td>
    </tr>
  </table>
<br>
  <table width="90%" border="0" align="center" class="ceKotak">
    <tr>
      <td><div align="center" class="huruf ici">P E N T I N G</div></td>
    </tr>
    <tr>
      <td><div align="center" class="huruf ici"> TIDAK BERLAKU CLAIM KEKURANGAN/TOLAKAN BARANG SETELAH BRG DITERIMA </div></td>
    </tr>
    <tr>
      <td><div align="center" class="huruf ici">PENERIMA WAJIB TTD&/CAP TOKO,       PEMBAYARAN DAPAT DI TRANSFER KE:</div></td>
    </tr>
    <tr>
      <td><div align="center" class="huruf ici">BCA-CIMAHI NO.REK. 139.300.1236 A/N PT.DIALOGUE GARMINDO UTAMA</div></td>
    </tr>
    <tr>
      <td><div align="center" class="huruf ici">MOHON UNTUK KONFIRMASI APABILA SUDAH MELAKUKAN PEMBAYARAN KE :</div></td>
    </tr>
    <tr>
      <td><div align="center" class="huruf ici">BAGIAN KEUANGAN PUSAT/CABANG  : (<?php echo $row->e_area_phone; ?>)</div></td>
    </tr>
    <tr>
      <td><div align="center" class="huruf ici">TERIMA KASIH ATAS KERJASAMANYA</div></td>
    </tr>
  </table>
<?php 
}
?>
<div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div>
