<?php 
#  include ("php/fungsi.php");
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
.pagebreak {
	page-break-before: always;
}
.huruf {
	FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
}
.miring {
	font-style: italic;

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
	border-style: solid;
	border-width:0.01px;
	border-collapse: collapse;
	cellspacing:0.1px;
}
.garis td { 
	background-color:#FFFFFF;
	border-style: solid;
	border-width:0.01px;
	font-size: 12px;
	FONT-WEIGHT: normal; 
}
.judul {
	font-size: 16px;
	FONT-WEIGHT: normal; 
}
.nmper {
	font-size: 14px;
	FONT-WEIGHT: normal; 
}
.isi {
	font-size: 12px;
	font-weight:normal;
}
.eusi {
	font-size: 12px;
	font-weight:normal;
}
.garisbawah { 
	border-bottom:#000000 0.1px solid;
}
</style>
<style type="text/css" media="print">
	.noDisplay{
		display:none;
	}
</style>
<?php 
if($detail){
	foreach($detail as $row){
		$periode=$row->periode;
	}
}else{
	$periode=$iperiode;
}
$perper=$periode;
$a=substr($periode,0,4);
$b=substr($periode,4,2);
$periode=mbulan($b)." - ".$a;
if($detail){
	echo "<center class='judul huruf'><h2>".NmPerusahaan."</h2></center>";
	echo "<center class='nmper huruf'><h3>LAPORAN MUTASI STOCK PUSAT BABY</h3></center>";
	echo "<center class='nmper huruf'><h3>Periode $periode</h3></center>";
	echo "<h3 class='nmper huruf'>Kode : $row->product - $row->e_product_name</h3>";
	?>
	<table cellspacing="0.1px" class="garis huruf" border="1">
		<tr>
			<td class="huruf isi">Refferensi</td>
			<td class="huruf isi">Tanggal</td>
			<td class="huruf isi">Awal</td>
			<td class="huruf isi">In</td>
			<td class="huruf isi">Out</td>
			<td class="huruf isi">Akhir</td>
		</tr>
		<?php 
		$in=0;
		$out=0;
		$sawal=0;
		$sahir=$saldo;
		foreach($detail as $row){
			$tmp=explode('-',$row->dreff);
			$tgl=$tmp[2];
			$bln=$tmp[1];
			$thn=$tmp[0];
			if(strlen($tgl)==2){
				$row->dreff=$tgl.'-'.$bln.'-'.$thn;
			}
			$sawal=$sahir;
			$sahir=$sawal+$row->masuk-$row->keluar;
			echo "<tr><td>$row->ireff</td>
			<td>$row->dreff</td>
			<td>$sawal</td>
			<td>$row->masuk</td>
			<td>$row->keluar</td>
			<td>$sahir</td></tr>";	
			$in=$in+$row->masuk;
			$out=$out+$row->keluar;
		}
		echo "<tr><td colspan=2>Total</td>
		<td>&nbsp;</td>
		<td>".number_format($in)."</td>
		<td>".number_format($out)."</td>
		<td>&nbsp;</td></tr>";	
	}
	?>
</table>
<div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div>
</BODY>
</html>
