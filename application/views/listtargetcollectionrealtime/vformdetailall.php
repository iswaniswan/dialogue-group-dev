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
    if($detail){
      $perper=$periode;
		  $a=substr($periode,0,4);
	    $b=substr($periode,4,2);
		  $periode=mbulan($b)." - ".$a;
      echo "<input name=\"iperiode\" id=\"iperiode\" value=\"$perper\" type=\"hidden\">";
      echo "<center><h2>".NmPerusahaan."</h2></center>";
		  echo "<center><h3>Target Collection per Sales</h3></center>";
		  echo "<center><h3>Periode $periode</h3></center>";
		  ?>
      	  <table class="listtable">
	     	    <th>No</th>
	     	    <th>Area</th>
	     	    <th>salesman</th>
			      <th>Target</th>
			      <th>Realisasi</th>
			      <th>Persen</th>
	      <tbody>
	        <?php 
		  if($detail){
        $no=0;
        $ttotal=0;
        $treal=0;
        $tpers=0;
			  foreach($detail as $row){
          $no++;
          $ttotal=$ttotal+$row->total;
          $treal =$treal+$row->realisasi;
          if($row->total!=0){
            $persen=number_format(($row->realisasi/$row->total)*100,2);
          }else{
            $persen='0.00';
          }
		      echo "<tr>
            <td>$no</td>
            <td>$row->i_area</td>
				    <td>($row->i_salesman) - $row->e_salesman_name</td>
				    <td align=right>Rp. ".number_format($row->total)."</td>
				    <td align=right>Rp. ".number_format($row->realisasi)."</td>
            <td align=right>".$persen." %</td></tr>";	
			  }
        if($ttotal!=0){
          $persen=number_format(($treal/$ttotal)*100,2);
        }else{
          $persen='0';
        }
        echo "<tr>
              <td colspan='3'>Total</td>
				      <td align=right>Rp. ".number_format($ttotal)."</td>
				      <td align=right>Rp. ".number_format($treal)."</td>
              <td align=right>".number_format($persen,2)." %</td></tr>";	
		  }
	        ?>
	      </tbody>
	    </table>
	  <center><input type="button" id="batal" name="batal" value="Tutup" onclick="bbatal('<?php echo $perper;?>')"></center>
    <?php 
    }else{
      echo "<center><h2>Target Collection belum ada</h2></center>";
      echo "<center><input type=\"button\" id=\"batal\" name=\"batal\" value=\"Tutup\" onclick=\"bbatal('$perper')\"></center>";
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
