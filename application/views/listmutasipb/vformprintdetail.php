<?php 
#  include ("php/fungsi.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=WINDOWS-1252" />
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
		if($detail){
			foreach($detail as $row){
				if($row->area=='00'){
					$gudang = 'AA';
				}else{
					$gudang = $row->area;
				}
			}
		}
      echo "<center class='judul huruf'><h2>".NmPerusahaan."</h2></center>";
		  echo "<center class='nmper huruf'><h3>LAPORAN MUTASI STOCK - $gudang ($istorelocation)</h3></center>";
		  echo "<center class='nmper huruf'><h3>Periode $iperiode</h3></center>";
		  echo "<h3 class='nmper huruf'>Kode : $row->product - $row->e_product_name</h3>";
?>
      	  <table cellspacing="0.1px" class="garis huruf">
            <tr>
	     	    <td align="center" class="huruf isi">Refferensi</td>
			      <td align="center" class="huruf isi">Tanggal</td>
	     	    <td align="center" class="huruf isi">Nama Toko</td>
			      <td align="center" class="huruf isi">Awal</td>
			      <td align="center" class="huruf isi">In</td>
			      <td align="center" class="huruf isi">Out</td>
			      <td align="center" class="huruf isi">Akhir</td>
            <td align="center" class="huruf isi">GiT</td>
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
        $sahir=$sawal+$row->in-$row->out;
	      echo "<tr><td>$row->ireff</td>
			    <td>$row->dreff</td>
			    <td>$row->e_customer_name</td>
			    <td align=right>$sawal</td>
			    <td align=right>$row->in</td>
			    <td align=right>$row->out</td>
			    <td align=right>$sahir</td>
			    <td align=right>$row->git</td></tr>";	
        $in=$in+$row->in;
        $out=$out+$row->out;
		  }
      echo "<tr><td colspan=3>Total</td>
			      <td>&nbsp;</td>
            <td align=right>".number_format($in)."</td>
			      <td align=right>".number_format($out)."</td>
            <td>&nbsp;</td><td>&nbsp;</td></tr>";	
	  }
	        ?>
	  </table>
<div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div>
</BODY>
</html>
