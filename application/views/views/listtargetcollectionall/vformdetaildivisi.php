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
    if($detail){
      $perper=$periode;
      $area=$iarea;
		  $a=substr($periode,0,4);
	    $b=substr($periode,4,2);
		  $periode=mbulan($b)." - ".$a;
      echo "<input name=\"iperiode\" id=\"iperiode\" value=\"$perper\" type=\"hidden\">";
      echo "<input name=\"iarea\" id=\"iarea\" value=\"$iarea\" type=\"hidden\">";
      echo "<center><h2>".NmPerusahaan."</h2></center>";
		  echo "<center><h3>Target Collection per Divisi</h3></center>";
		  echo "<center><h3>Periode $periode</h3></center>";
		  ?>
      	  <table class="listtable">
	     	    <th>No</th>
	     	    <th>Area</th>
	     	    <th>Nota</th>
	     	    <th>Jatuh Tempo</th>
	     	    <th>Jatuh Tempo + Toleransi</th>
			      <th>Target</th>
			      <th>Realisasi</th>
			      <th>Persen</th>
	      <tbody>
	        <?php 
		  if($detail){
        $no=0;
        $ttotal=0;
        $ttotsub=0;
        $treal=0;
        $trealsub=0;
        $tpers=0;
        $tperssub=0;
        $group='';
			  foreach($detail as $row){
          if($row->total!=0 && $row->total!=0){
            $no++;
            if($row->d_jatuh_tempo){
              $tmp=explode("-",$row->d_jatuh_tempo);
              $th=$tmp[0];
              $bl=$tmp[1];
              $dt=$tmp[2];
              $row->d_jatuh_tempo=$dt.'-'.$bl.'-'.$th;
            }
            if($row->d_jatuh_tempo_plustoleransi){
              $tmp=explode("-",$row->d_jatuh_tempo_plustoleransi);
              $th=$tmp[0];
              $bl=$tmp[1];
              $dt=$tmp[2];
              $row->d_jatuh_tempo_plustoleransi=$dt.'-'.$bl.'-'.$th;
            }
            $ttotal=$ttotal+$row->total;
            $treal =$treal+$row->realisasi;
            if($row->total!=0){
              $persen=number_format(($row->realisasi/$row->total)*100,2);
            }else{
              $persen='0';
            }
            if($group==''){
              echo "<tr><td colspan=8 align=center style='font-size: 15px;'>$row->e_product_groupname</td></tr>";
            }elseif($group!=$row->i_product_group){
              if($ttotsub!=0){
                $persen=number_format(($trealsub/$ttotsub)*100,2);
              }else{
                $persen='0';
              }
              echo "<tr>
                    <td colspan='5' style='font-size: 15px;'>Sub Total</td>
				            <td align=right style='font-size: 15px;'>Rp. ".number_format($ttotsub)."</td>
				            <td align=right style='font-size: 15px;'>Rp. ".number_format($trealsub)."</td>
                    <td align=right style='font-size: 15px;'>".number_format($persen,2)." %</td></tr>";	
              echo "<tr><td colspan=8 align=center style='font-size: 15px;'>$row->e_product_groupname</td></tr>";
              $ttotsub=0;
              $trealsub=0;
              $tperssub=0;
            }
            $ttotsub=$ttotsub+$row->total;
            $trealsub =$trealsub+$row->realisasi;
            if($row->total!=0){
              $persen=number_format(($row->realisasi/$row->total)*100,2);
            }else{
              $persen='0';
            }
            $group=$row->i_product_group;
		        echo "<tr>
              <td>$no</td>
              <td>$row->i_area - $row->e_area_name</td>
				      <td>$row->i_nota</td>
				      <td>$row->d_jatuh_tempo</td>
				      <td>$row->d_jatuh_tempo_plustoleransi</td>
				      <td align=right>Rp. ".number_format($row->total)."</td>
				      <td align=right>Rp. ".number_format($row->realisasi)."</td>
              <td align=right>".number_format($persen,2)." %</td></tr>";	
          }
			  }
        if($ttotal!=0){
          $persen=number_format(($treal/$ttotal)*100,2);
        }else{
          $persen='0';
        }
        if($ttotsub!=0){
          $persen=number_format(($trealsub/$ttotsub)*100,2);
        }else{
          $persen='0';
        }
        echo "<tr>
              <td colspan='5' style='font-size: 15px;'>Sub Total</td>
	            <td align=right style='font-size: 15px;'>Rp. ".number_format($ttotsub)."</td>
	            <td align=right style='font-size: 15px;'>Rp. ".number_format($trealsub)."</td>
              <td align=right style='font-size: 15px;'>".number_format($persen,2)." %</td></tr>";	
        echo "<tr>
              <td colspan='5' style='font-size: 15px;'>Total</td>
				      <td align=right style='font-size: 15px;'>Rp. ".number_format($ttotal)."</td>
				      <td align=right style='font-size: 15px;'>Rp. ".number_format($treal)."</td>
              <td align=right style='font-size: 15px;'>".number_format($persen,2)." %</td></tr>";	
		  }
	        ?>
	      </tbody>
	    </table>
	  <center><input type="button" id="batal" name="batal" value="Tutup" onclick="bbatal('<?php echo $perper;?>')">&nbsp;<!--<input type="button" id="cetak" name="cetak" value="Print" onclick="yyy()">--></center>
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
