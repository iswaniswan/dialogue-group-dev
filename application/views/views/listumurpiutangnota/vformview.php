<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="table color-table success-table table-bordered" cellspacing="0" width="100%" id="sitabel">
                    <thead>
                        <tr>
                            <th align="center">No</th>
							<th align="center">Area</th>
							<th align="center">Customer</th>
							<th align="center">TOP</th>
							<th align="center">Nota</th>
							<th align="center">Tgl Nota</th>
							<th align="center">Jth Tempo<br>Plus Toleransi</th>
							<th align="center">Umur</th>
							<th align="center">Umur<br>Piutang</th>
							<th align="center">Telat<br>(hari)</th>
							<th align="center">Kelompok</th>
							<th align="center">Keterangan</th>
							<th align="center" rowspan=2>Jml Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
		            if($isi){
		                $i=0;
#		$j=0;
						$area='';
						$eareaname='';
						$total=0;
						$grandtotal=0;
						$jmltotal = count($isi);
						$jml0 = 0;
						$jml31 = 0;
						$jml61 = 0;
						$jml90 = 0;
						$nom0 = 0;
						$nom31 = 0;
						$nom61 = 0;
						$nom90 = 0;
						foreach($isi as $row){
						    $today = new DateTime($d_opname);
						    $tglnota = new DateTime($row->d_nota);
						    $umur = $today->diff($tglnota)->format("%a");
						    if($umur > 90){
						    	$umurpiutang = ">90 Hari";
						    	$jml90 = $jml90 + 1;
						    	$nom90 = $nom90 + $row->v_sisa;
						    }elseif($umur > 60){
						    	$umurpiutang = "61-90 Hari";
						    	$jml61 = $jml61 + 1;
						    	$nom61 = $nom61 + $row->v_sisa;
						    }elseif($umur > 30){
						    	$umurpiutang = "31-60 Hari";
						    	$jml31 = $jml31 + 1;
						    	$nom31 = $nom31 + $row->v_sisa;
						    }elseif($umur >= 0){
						    	$umurpiutang = "0-30 Hari";
						    	$jml0 = $jml0 + 1;
						    	$nom0 = $nom0 + $row->v_sisa;
						    }
						    	$i++;
						    	echo "<tr>";
						    	echo "<td>$i</td>
						    	<td>$row->e_area_name</td>
						    	<td>$row->i_customer-$row->e_customer_name</td>
						    	<td>$row->n_top</td>
						    	<td>$row->i_nota</td>
						    	<td>$row->d_nota</td>
						    	<td>$row->d_jtempo_plustoleransi</td>
						    	<td align='right'>$umur</td>
						    	<td align='right'>$umurpiutang</td>
						    	<td align='right'>$row->umurpiutang</td>
						    	<td>$row->e_umur_piutangname</td>
						    	<td>$row->ketsisa</td>
						    	<td align='right'>".number_format($row->v_sisa)."</td>
						    </tr>
						    ";
						    $total = $total + $row->v_sisa;
						    $grandtotal = $grandtotal+ $row->v_sisa;
						    $area=$row->i_area;
						    $eareaname = $row->e_area_name;
						    if($i==$jmltotal){
						    	$jmltoko = $jml0 + $jml31 + $jml61 + $jml90;
						    	$persen0 = ($nom0/$grandtotal)*100;
						    	$persen31 = ($nom31/$grandtotal)*100;
						    	$persen61 = ($nom61/$grandtotal)*100;
						    	$persen90 = ($nom90/$grandtotal)*100;
						    	echo "<tr><th colspan='3' align='center'>Umur (Hari)</th><th colspan='3' align='center'>Jml Toko</th><th colspan='4' align='center'>Nominal</th><th colspan='3' align='center'>%</th></tr>";
						    	echo "<tr><th colspan='3' align='center'>0-30 Hari</th><th colspan='3' align='center'>".number_format($jml0)."</th><th colspan='4' align='center'>".number_format($nom0)."</th><th colspan='3' align='center'>".number_format($persen0,2,'.',',')." %</th></tr>";
						    	echo "<tr><th colspan='3' align='center'>31-60 Hari</th><th colspan='3' align='center'>".number_format($jml31)."</th><th colspan='4' align='center'>".number_format($nom31)."</th><th colspan='3' align='center'>".number_format($persen31,2,'.',',')." %</th></tr>";
						    	echo "<tr><th colspan='3' align='center'>61-90 Hari</th><th colspan='3' align='center'>".number_format($jml61)."</th><th colspan='4' align='center'>".number_format($nom61)."</th><th colspan='3' align='center'>".number_format($persen61,2,'.',',')." %</th></tr>";
						    	echo "<tr><th colspan='3' align='center'>>90 Hari</th><th colspan='3' align='center'>".number_format($jml90)."</th><th colspan='4' align='center'>".number_format($nom90)."</th><th colspan='3' align='center'>".number_format($persen90,2,'.',',')." %</th></tr>";
						    	echo "<tr><th colspan='3' align='center'>Total</th><th colspan='3' align='center'>".number_format($jmltoko)."</th><th colspan='4' align='center'>".number_format($grandtotal)."</th><th colspan='3' align='center'>1.00 %</th></tr>";		
                            
						    }					
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <button type="button" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
            </div>
        </div>
    </div>
</div>
<script>
    $( "#cmdreset" ).click(function() {  
		var Contents = $('#sitabel').html();    
		window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
	});
</script>