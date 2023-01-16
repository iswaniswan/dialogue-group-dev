<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/dgu.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.js"></script>
<div id='tmp'>
	<table class="maintable">
  	<tr>
   <td align="left">
  	<?php echo $this->pquery->form_remote_tag(array('url'=>'listtargetcollectionrealtime/cform/export','update'=>'#main','type'=>'post'));?>
	<div class="effect">
	<div class="accordion2">
	<?php 
		$periode=$iperiode;
		$a=substr($periode,0,4);
	  $b=substr($periode,4,2);
		$periode=mbulan($b)." - ".$a;
	?>
   <input name="iperiode" id="iperiode" value="<?php echo $periode; ?>" type="hidden">
   <input name="akhir" id="akhir" value="<?php echo $akhir; ?>" type="hidden">
	<?php 
  	echo "<center><h2>".NmPerusahaan."</h2></center>";
		echo "<center><h3>Target Collection per Area</h3></center>";
		echo "<center><h3>Periode $periode</h3></center>";
	?>
   <table class="listtable" border=none>
   <tr>
		<th rowspan=3>No</th>
		<th rowspan=3 align="center">Area</th>
		<th rowspan=3 align="center">Target (Rp)</th>
		<th rowspan=3 align="center">Blm Bayar (Rp)</th>
		<th colspan=6 align="center">Realisasi</th>
 		<th rowspan=3 class="action">/Nota</th>
 		<th rowspan=3 class="action">/Sales</th>
<!-- 		<th rowspan=3 class="action">/Divisi</th>-->
 	</tr>
	<tr>
		<th colspan=2 align="center">Tidak Telat</th>
		<th colspan=2 align="center">Telat</th>
		<th colspan=2 align="center">Total</th>
    </tr>
	<tr>
		<th align="center">Jumlah (Rp)</th>
		<th align="center">Persen</th>
		<th align="center">Jumlah (Rp)</th>
		<th align="center">Persen</th>
		<th align="center">Jumlah (Rp)</th>
		<th align="center">Persen</th>
	</tr>
	<tbody>
	<?php 
		if($isi)
		{
	      $i=1;
	      $ttarget=0;
	      $trealis=0;
	      $tblm=0;
	      $tsdh=0;
	      $ttlt=0;
	      $trealsdh=0;
	      $trealtlt=0;
  			$persenall=0;
  			$persenalltdktelat=0;
  			$persenalltelat=0;
			foreach($isi as $row)
			{
			  	settype($row->lama,"integer");
        		if($row->realisasi==null || $row->realisasi=='')$row->realisasi=0;
        		if($row->total!=0)
        		{
          		$persen=number_format(($row->realisasi/$row->total)*100,2);
          		$persentdktelat=number_format(($row->realisasitdktelat/$row->total)*100,2);
          		$persentelat=number_format(($row->realisasitelat/$row->total)*100,2);
        		}
        		else
        		{
          		$persen='0';
          		$persentdktelat='0';
          		$persentelat='0';
        		}
        		$tblm=$tblm+$row->blmbayar;
        		$tsdh=$tsdh+$row->tdktelat;
        		$ttlt=$ttlt+$row->telat;
        		$trealsdh=$trealsdh+$row->realisasitdktelat;
        		$trealtlt=$trealtlt+$row->realisasitelat;
        		$ttarget=$ttarget+$row->total;
        		$trealis=$trealis+$row->realisasi;
        		$iarea = $row->i_area;
	      	echo "<tr>
          		<td align=right><a href=\"#\" onclick='chartx(\"$iperiode\");'>$i</a></td>
          		<td>$row->i_area-$row->e_area_name</td>
          		<td align=right>".number_format($row->total)."</td>
          		<td align=right>".number_format($row->blmbayar)."</td>";
        		echo "
          		<td align=right>".number_format($row->realisasitdktelat)."</td>
          		<td align=right>".number_format($persentdktelat,2)." %</td>
          		<td align=right>".number_format($row->realisasitelat)."</td>
          		<td align=right>".number_format($persentelat,2)." %</td>
          		<td align=right>".number_format($row->realisasi)."</td>
		    		<td align=right>".number_format($persen,2)." %</td>";
        		$i++;
			  	echo "<td class=\"action\">";
				echo "<a href=\"#\" onclick='view_detail(\"$iperiode\",\"$akhir\",\"$iarea\");'><img height=15px; style=\"cursor:hand;\" src=\"". base_url()."img/edit.png\" border=\"0\" alt=\"edit\"></a>";
				echo "</td>";	
			  	echo "<td class=\"action\">";
				echo "<a href=\"#\" onclick='view_sales(\"$iperiode\",\"$akhir\",\"$iarea\");'><img height=15px; style=\"cursor:hand;\" src=\"". base_url()."img/edit.png\" border=\"0\" alt=\"edit\"></a>";
#				echo "</td>";
#			  	echo "<td class=\"action\">";
#				echo "<a href=\"#\" onclick='view_divisi(\"$iperiode\",\"$akhir\",\"$row->i_area\");'><img height=15px; style=\"cursor:hand;\" src=\"". base_url()."img/edit.png\" border=\"0\" alt=\"edit\"></a>";
				echo "</td></tr>";	
			}
     		if($ttarget!=0)
     		{ 
     			$persenall=number_format(($trealis/$ttarget)*100,2);
     			$persenalltdktelat=number_format(($trealsdh/$ttarget)*100,2);
     			$persenalltelat=number_format(($trealtlt/$ttarget)*100,2);
     		}
     		else
     		{
     			$persenall='0';
     			$persenalltdktelat='0';
     			$persenalltelat='0';
     		}
      	echo "<tr><th colspan=2>Total</th>";
      	echo "<th align=right>".number_format($ttarget)."</th>";
      	echo "<th align=right>".number_format($tblm)."</th>";
      	echo "<th align=right>".number_format($trealsdh)."</th>";
      	echo "<th align=right>".number_format($persenalltdktelat,2)." %</th>";
      	echo "<th align=right>".number_format($trealtlt)."</th>";
      	echo "<th align=right>".number_format($persenalltelat,2)." %</th>";
      	echo "<th align=right>".number_format($trealis)."</th>";
      	echo "<th align=right>".number_format($persenall,2)." %</th>";
      	echo "<th colspan=3></th></tr>";
		}
	   ?>
	</tbody>
	</table>
</div>

<script language="javascript" type="text/javascript">
  function yyy(a,c)
  {
	  document.getElementById("iperiode").value=a;
	  document.getElementById("iarea").value=c;
	  formna=document.getElementById("listform");
	  formna.action="<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/viewdetail";
	  formna.submit();
  }
  function view_detail(a,b,c){
    lebar =1366;
    tinggi=768;
    eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/detail/"+a+"/"+b+"/"+c,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }
  function view_sales(a,b,c){
    lebar =1366;
    tinggi=768;
    periode=document.getElementById("iperiode").value;
    akhir=document.getElementById("akhir").value;
    eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/sales/"+a+"/"+b+"/"+c,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }
  function view_divisi(a,b){
    lebar =1366;
    tinggi=768;
    periode=document.getElementById("iperiode").value;
    eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/divisi/"+a+"/"+b,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }
  function chartx(iperiode){
    lebar =1366;
    tinggi=768;
    show("listtargetcollectionrealtime/cform/fcf/"+iperiode,"#main");
  }
</script>
