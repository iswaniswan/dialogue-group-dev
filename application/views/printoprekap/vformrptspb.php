<style type="text/css" media="all">
.huruf {
  FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
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
  border-width:1px;
}
.judul {
  font-size: 12px;
  font-weight:normal;
}
.isi {
  font-size: 10px;
  font-weight:normal;
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
	foreach($spb as $row){
    $area=$row->i_area;
    $iop=$row->i_op;
    $dop=$row->d_op;
  }
  if($dop){
    $tmp=explode("-",$dop);
    $hh=$tmp[2];
    $mm=$tmp[1];
    $yy=$tmp[0];
    $dop=$hh.'-'.$mm.'-'.$yy;
  }else{
    $dop='';
  }
  $i=1;
  $page=0;
?>
<center><h3>OP : <?php echo $iop.' __ Tanggal : '.$dop; ?></h3></center>
<center>
<table border=0 cellspacing="0.1px" class="garis huruf">
<tr>
  <td class="isi">SPB</th>
  <td class="isi">kode</th>
  <td class="isi">nama</th>
  <td class="isi">jml pesan</th>
  <td class="isi">Keterangan</th>
</tr>
<?php 
	foreach($spb as $row){
	  echo "<tr>
            <td class=\"isi\">$row->i_spb</td>
            <td class=\"isi\">$row->i_product</td>
            <td class=\"isi\">$row->e_product_name</td>
            <td class=\"isi\" align=right>$row->n_order</td>
            <td class=\"isi\">$row->e_remark</td>
          </tr>";
    $i++;
    if($i%50==0){
      $page++;
      echo "</table>halaman : $page";
      echo "</center><div class=\"pagebreak\"></div>";
      echo "<center><h3>OP : $iop"."  __ Tanggal : ".$dop."</h3></center>
      <center>";
      echo "<table border=0 cellspacing=\"0.1px\" class=\"garis huruf\">
      <tr>
        <td class=\"isi\">SPB</th>
        <td class=\"isi\">kode</th>
        <td class=\"isi\">nama</th>
        <td class=\"isi\">jml pesan</th>
        <td class=\"isi\">Keterangan</th>
      </tr>";
    }
  }
  $page++;
?>
</table>
</center>
<center><?php echo "halaman : ".$page; ?></center>
<div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b>&nbsp;&nbsp;<b><a href="#" onClick="window.close()">Keluar</a></b></center></div>

