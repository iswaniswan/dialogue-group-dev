<?php 
 	include ("php/fungsi.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>
<body>
<style type="text/css" media="all">
/*
@page land {size: landscape;}
@media print {
input.noPrint { display: none; }
}
@page
        {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
*/
*{
size: landscape;
}

@page { size: Letter; 
        margin: 0mm;  /* this affects the margin in the printer settings */
}

.huruf {
  FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
}
.miring {
  font-style: italic;
  
}
.wrap {
	margin: 0 auto;
	text-align: left;
}

.ceKotak{-
	background-color:#f0f0f0;
	border-bottom:#80c0e0 1px solid;
	border-top:#80c0e0 1px solid;
	border-left:#80c0e0 1px solid;
	border-right:#80c0e0 1px solid;
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
.garisy { 
	background-color:#000000;
	width: 100%;
  height: 50%;
  border-style: solid;
  border-width:0.01px;
  border-collapse: collapse;
  spacing:1px;
}
.garisy td { 
	background-color:#FFFFFF;
  border-style: solid;
  border-width:0.01px;
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
	margin-top: 0;
  font-size: 12px;
  FONT-WEIGHT: normal; 
}
.isi {
  font-size: 10px;
  font-weight:normal;
}
.eusinya {
  font-size: 8px;
  font-weight:normal;
}
.garisbawah { 
	border-bottom:#000000 0.1px solid;
}
.garisatas { 
	border-top:#000000 0.1px solid;
}
</style>
<style type="text/css" media="print">
.noDisplay{
	display:none;
}
.pagebreak {
    page-break-before: always;
}
</style>
<?php 
$hal=1;
foreach($isi as $row)
{
?>
  <table width="100%" class="nmper" border="0">
    <tr>
      <td colspan="2"><?php echo $company->name; ?></td>
    </tr>
    <tr>
      <td width="500px" rowspan="3" class="huruf judul" ><?php echo "SURAT JALAN (P)"; ?></td>
    </tr>
    <tr>
      <td >KEPADA Yth.</td>
    </tr>
    <tr>
      <td ><?php echo strtoupper($row->e_area_name)?></td>
    </td>
    </tr>
    <tr>
      <td width="75px" colspan="2"><?php echo "No.".$row->i_sjp;?></td>
    </tr>
    <tr>
      <td colspan="2">HARAP DITERIMA BARANG-BARANG BERIKUT INI :</td>
    </tr>
    <tr align="center">
      <td colspan="2">
        <table width="100%" class="nmper" border="0">
          <tr>
            <td colspan="4">&nbsp;</td>
            <td align="right"><?php echo "Hal : ".$hal;?></td>
          </tr>
          <tr>
            <td width="50px" class="garisatas garisbawah">
              NO. URUT
            </td>
            <td width="75px" class="garisatas garisbawah">
              KODE BARANG
            </td>
            <td class="garisatas garisbawah">
              NAMA BARANG
            </td>
            <td width="75px" class="garisatas garisbawah">
              JUMLAH DIPESAN
            </td>
            <td class="garisatas garisbawah">
              KETERANGAN
            </td>
          </tr>
          <?php 
		        $i	= 0;
		        $j	= 0;
		        $hrg= 0;
		        $jml=count($detail);

		      foreach($detail as $rowi){
			    $i++;
			    $j++;
#			    $hrg	= $hrg+($rowi->n_order*$rowi->v_product_mill);
			      ?>
          <tr>
            <td width="25">
              <?php echo $i;?>
            </td>
            <td>
              <?php echo $rowi->i_product;?>
            </td>
            <td>
              <?php 
			        if(strlen($rowi->e_product_name )>50){
				        $nam	= substr($rowi->e_product_name,0,50);
			        }else{
				        $nam	= $rowi->e_product_name.str_repeat(" ",50-strlen($rowi->e_product_name ));
			        }
              echo $nam;?>
            </td>
            <td width="20px" align="center">
              <?php echo $rowi->n_quantity_deliver;?>
            </td>
            <td>
              <?php echo $rowi->e_remark;?>
            </td>
          </tr>
          <?php }?>
      </td>
    </tr>
</table>
  <tr>
    <td colspan=5 class=garisatas>&nbsp;</td>
  </tr>
</table >
<table width="100%" class="nmper" border="0">
  <tr>
    <td colspan="3">&nbsp;</td>
    <td><?php echo "Bandung, ".$row->d_sjp?></td>
  </tr>  <tr>
    <td align="center">
      Penerima
    </td>
    <td align="center">
      Mengetahui,
    </td>
    <td align="center">
      Cek Akhir,
    </td>
    <td align="center">
      Pembuat,
    </td>   
  </tr>
  <tr>
    <td>
      &nbsp;
    </td>
    <td align="center">
      &nbsp;
    </td>
    <td align="center">
      &nbsp;
    </td>
    <td align="center">
      &nbsp;
    </td>   
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
    <td align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
    <td align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
    <td align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
  </tr>
  <tr>
    <td colspan ="4" align="left"><?php echo "TANGGAL CETAK : ".$tgl=date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
?></td>
  </tr>
</table>

<?php    
  }
?>
<div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div>
