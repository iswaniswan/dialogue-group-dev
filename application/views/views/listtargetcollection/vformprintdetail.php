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
				$periode=$row->e_periode;
			}
		}else{
			$periode=$iperiode;
		}
    $perper=$periode;
    $area=$row->i_area;
		$a=substr($periode,0,4);
	  $b=substr($periode,4,2);
		$periode=mbulan($b)." - ".$a;
    if($detail){
      echo "<center class='judul huruf'><h2>".NmPerusahaan."</h2></center>";
		  echo "<center class='nmper huruf'><h3>Target Collection Credit per Nota</h3></center>";
		  echo "<center class='nmper huruf'><h3>Periode $periode</h3></center>";
?>
      	  <table cellspacing="0.1px" class="garis huruf">
          <tr>
            <td align="center" class="huruf isi">No</td>
	     	    <td align="center" class="huruf isi">Area</td>
	     	    <td align="center" class="huruf isi">Toko</td>
	     	    <td align="center" class="huruf isi">Jenis</td>
			      <td align="center" class="huruf isi">Tanggal</td>
	     	    <td align="center" class="huruf isi">Nota</td>
			      <td align="center" class="huruf isi">Target</td>
			      <td align="center" class="huruf isi">Realisasi</td>
          </tr>
	        <?php 
      $no=0;
      $ttotal=0;
      $treal=0;
		  foreach($detail as $row){
        if($row->total>0 || $row->realisasi>0){
          $no++;
          $tmp=explode('-',$row->d_nota);
		      $tgl=$tmp[2];
		      $bln=$tmp[1];
		      $thn=$tmp[0];
          if(strlen($tgl)==2){
      		  $row->d_nota=$tgl.'-'.$bln.'-'.$thn;
          }
          $ttotal=$ttotal+$row->total;
          $treal =$treal+$row->realisasi;
		      echo "<tr>
            <td>$no</td>
            <td>$row->i_area - $row->e_area_name</td>
				    <td>($row->i_customer) - $row->e_customer_name</td>
				    <td>$row->e_customer_classname</td>
				    <td>$row->d_nota</td>
				    <td>$row->i_nota</td>
				    <td align=right>Rp. ".number_format($row->total)."</td>
				    <td align=right>Rp. ".number_format($row->realisasi)."</td></tr>";	
        }
			}
      echo "<tr>
            <td colspan='6'>Total</td>
			      <td align=right>Rp. ".number_format($ttotal)."</td>
			      <td align=right>Rp. ".number_format($treal)."</td></tr>";
	  }
	        ?>
	  </table>
<div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div>
</BODY>
</html>
