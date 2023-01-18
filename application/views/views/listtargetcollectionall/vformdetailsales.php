<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/dgu.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.js"></script>
<div id='tmp'>
	<table class="maintable">
  	<tr>
   <td align="left">
  	<?php echo $this->pquery->form_remote_tag(array('url'=>'targetcollectioncash/cform/cari','update'=>'#main','type'=>'post'));?>
	<div class="effect">
	  	<div class="accordion2">
		<?php 
		$periode=$iperiode;
    	$perper=$periode;
    	if($detail)
    	{
      	$perper=$periode;
      	$area=$iarea;
		  	$a=substr($periode,0,4);
	    	$b=substr($periode,4,2);
		  	$periode=mbulan($b)." - ".$a;
      	echo "<input name=\"iperiode\" id=\"iperiode\" value=\"$perper\" type=\"hidden\">";
      	echo "<input name=\"iarea\" id=\"iarea\" value=\"$iarea\" type=\"hidden\">";
      	echo "<center><h2>".NmPerusahaan."</h2></center>";
		  	echo "<center><h3>Target Collection per Sales</h3></center>";
		  	echo "<center><h3>Periode $periode</h3></center>";
		?>
      <table class="listtable">
      <tr>  
		   <th rowspan=3>No</th>
		   <th rowspan=3 align="center">Salesman</th>
		   <th rowspan=3 align="center">Target (Rp)</th>
			<th rowspan=3 align="center">Blm Bayar (Rp)</th>
			<th colspan=6 align="center">Realisasi</th>
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
		if($detail)
		{
        	$no=0;
        	$ttotal=0;
        	$treal=0;
        	$tpers=0;
        	$tblm=0;
        	$ttelat=0;
        	$trealtelat=0;
        	$ttdk=0;
        	$trealtdk=0;
			foreach($detail as $row)
			{
          	$no++;
          	$ttotal=$ttotal+$row->total;
          	$treal =$treal+$row->realisasi;
          	$tblm=$tblm+$row->blmbayar;
          	$trealtelat =$trealtelat+$row->realisasitelat;
          	$trealtdk =$trealtdk+$row->realisasitdktelat;
          	$ttelat =$ttelat+$row->telat;
          	$ttdk =$ttdk+$row->tdktelat;
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
		      echo "<tr>
            		<td>$no</td>
				    	<td>$row->e_salesman_name - $row->i_salesman</td>
				    	<td align=right>".number_format($row->total)."</td>
            	 	<td align=right>".number_format($row->blmbayar)."</td>";
				echo "
            		<td align=right>".number_format($row->realisasitdktelat)."</td>
            		<td align=right>".number_format($persentdktelat,2)." %</td>
            		<td align=right>".number_format($row->realisasitelat)."</td>	
            		<td align=right>".number_format($persentelat,2)." %</td>
						<td align=right>".number_format($row->realisasi)."</td>
         			<td align=right>".number_format($persen,2)." %</td></tr>";
			}
        	if($ttotal!=0)
        	{
          	$persen=number_format(($treal/$ttotal)*100,2);
        	}
        	else
        	{
          	$persen='0';
        	}
		}
	   ?>
	</tbody>
	</table>
   <?php 
   }
   else
	{
		echo "<center><h2>Target Collection belum ada</h2></center>";
	}
   ?>
</div>

<script language="javascript" type="text/javascript">
  function bbatal(a){
    show("listtargetcollection/cform/view/"+a+"/","#main");
  }
  function yyy(){
  	lebar =1024;
    tinggi=768;
    periode=document.getElementById("iperiode").value;
    area   =document.getElementById("iarea").value;
    eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollection/cform/cetaksales/"+periode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }
</script>
