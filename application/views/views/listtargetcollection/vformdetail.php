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
		  echo "<center><h3>Target Collection Per Nota</h3></center>";
		  echo "<center><h3>Periode $periode</h3></center>";
		  ?>
      	  <table class="listtable">
      	    <tr>
	       	    <th rowspan=2>No</th>
	       	    <th rowspan=2>Area</th>
	       	    <th rowspan=2>Salesman</th>
	       	    <th rowspan=2>Toko</th>
<!--              <th rowspan=2>Jenis</th>-->
			        <th rowspan=2>Tanggal</th>
	       	    <th rowspan=2>Nota</th>
			        <th rowspan=2>Target</th>
			        <th rowspan=2>Realisasi</th>
   			      <th rowspan=2>Blm Bayar</th>
			        <th colspan=2>Tdk Telat</th>
			        <th colspan=2>Telat</th>
            </tr>
            <tr>
			        <th>Target</th>
			        <th>Realisasi</th>
			        <th>Target</th>
			        <th>Realisasi</th>
            </tr>
	      <tbody>
	        <?php 
		  if($detail){
        $no=0;
        $ttotal=0;
        $treal=0;
        $tblm=0;
        $ttelat=0;
        $trealtelat=0;
        $ttdk=0;
        $trealtdk=0;
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
            $tblm=$tblm+$row->blmbayar;
            $trealtelat =$trealtelat+$row->realisasitelat;
            $trealtdk =$trealtdk+$row->realisasitdktelat;
            $ttelat =$ttelat+$row->telat;
            $ttdk =$ttdk+$row->tdktelat;
		        echo "<tr>
              <td>$no</td>
              <td>$row->i_area - $row->e_area_name</td>
  				    <td>$row->i_salesman ($row->e_salesman_name)</td>
				      <td>($row->i_customer) - $row->e_customer_name</td>";
#              <td>$row->e_customer_classname</td>
            echo "
				      <td>$row->d_nota</td>
				      <td>$row->i_nota</td>
				      <td align=right>Rp. ".number_format($row->total)."</td>
				      <td align=right>Rp. ".number_format($row->realisasi)."</td>";
				    echo "
              <td align=right>Rp. ".number_format($row->blmbayar)."</td>
              <td align=right>Rp. ".number_format($row->tdktelat)."</td>
              <td align=right>Rp. ".number_format($row->realisasitdktelat)."</td>
              <td align=right>Rp. ".number_format($row->telat)."</td>
              <td align=right>Rp. ".number_format($row->realisasitelat)."</td></tr>";	
          }
			  }
        echo "<tr>
              <th colspan='6'>Total</th>
				      <th align=right>Rp. ".number_format($ttotal)."</th>
				      <th align=right>Rp. ".number_format($treal)."</th>
				      <th align=right>Rp. ".number_format($tblm)."</th>
				      <th align=right>Rp. ".number_format($ttdk)."</th>
				      <th align=right>Rp. ".number_format($trealtdk)."</th>
				      <th align=right>Rp. ".number_format($ttelat)."</th>
				      <th align=right>Rp. ".number_format($trealtelat)."</th>
				      </tr>";
		  }
	        ?>
	      </tbody>
	    </table>
    <?php 
    }else{
      echo "<center><h2>Target Collection belum ada</h2></center>";
    }
    ?>
</div>
<script language="javascript" type="text/javascript">
  function bbatal(a){
    show("listtargetcollectionrealtime/cform/view/"+a+"/","#main");
  }
  function yyy(){
  	lebar =1024;
    tinggi=768;
    periode=document.getElementById("iperiode").value;
    area   =document.getElementById("iarea").value;
    eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/cetakdetail/"+periode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }
</script>
