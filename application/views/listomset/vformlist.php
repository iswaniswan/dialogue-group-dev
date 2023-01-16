<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
			<?php } ?>
		</div>
		<div class="panel-body table-responsive">
			<table class="table color-bordered-table info-bordered-table" id="sitabel">
				<thead>
					<?php if($isi){ ?>
						<tr>
							<th rowspan="2" style="text-align: center;">AREA</th>
							<?php if($dfrom!=''){
								$tmp=explode("-",$dfrom);
								$blasal=$tmp[1];
								settype($bl,'integer');
							}
							$bl=$blasal;
							?>
							<th style="text-align: center;" colspan="<?php echo $interval; ?>">SPB</th>
						</tr>
						<tr>
							<?php 
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									echo '<th>Jan</th>';
									break;
									case '2' :
									echo '<th>Feb</th>';
									break;
									case '3' :
									echo '<th>Mar</th>';
									break;
									case '4' :
									echo '<th>Apr</th>';
									break;
									case '5' :
									echo '<th>Mei</th>';
									break;
									case '6' :
									echo '<th>Jun</th>';
									break;
									case '7' :
									echo '<th>Jul</th>';
									break;
									case '8' :
									echo '<th>Agu</th>';
									break;
									case '9' :
									echo '<th>Sep</th>';
									break;
									case '10' :
									echo '<th>Okt</th>';
									break;
									case '11' :
									echo '<th>Nov</th>';
									break;
									case '12' :
									echo '<th>Des</th>';
									break;
								}
								$bl++;
								if($bl==13)$bl=1;
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php 
						$grandtot01=0;
						$grandtot02=0;
						$grandtot03=0;
						$grandtot04=0;
						$grandtot05=0;
						$grandtot06=0;
						$grandtot07=0;
						$grandtot08=0;
						$grandtot09=0;
						$grandtot10=0;
						$grandtot11=0;
						$grandtot12=0;
						foreach($isi as $row){
							echo "<tr>
							<td>".$row->iarea."-".$row->area."</td>";
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									echo '<th align=right>'.number_format($row->spbjan).'</th>';
									$grandtot01=$grandtot01+$row->spbjan;
									break;
									case '2' :
									echo '<th align=right>'.number_format($row->spbfeb).'</th>';
									$grandtot02=$grandtot02+$row->spbfeb;
									break;
									case '3' :
									echo '<th align=right>'.number_format($row->spbmar).'</th>';
									$grandtot03=$grandtot03+$row->spbmar;
									break;
									case '4' :
									echo '<th align=right>'.number_format($row->spbapr).'</th>';
									$grandtot04=$grandtot04+$row->spbapr;
									break;
									case '5' :
									echo '<th align=right>'.number_format($row->spbmay).'</th>';
									$grandtot05=$grandtot05+$row->spbmay;
									break;
									case '6' :
									echo '<th align=right>'.number_format($row->spbjun).'</th>';
									$grandtot06=$grandtot06+$row->spbjun;
									break;
									case '7' :
									echo '<th align=right>'.number_format($row->spbjul).'</th>';
									$grandtot07=$grandtot07+$row->spbjul;
									break;
									case '8' :
									echo '<th align=right>'.number_format($row->spbaug).'</th>';
									$grandtot08=$grandtot08+$row->spbaug;
									break;
									case '9' :
									echo '<th align=right>'.number_format($row->spbsep).'</th>';
									$grandtot09=$grandtot09+$row->spbsep;
									break;
									case '10' :
									echo '<th align=right>'.number_format($row->spboct).'</th>';
									$grandtot10=$grandtot10+$row->spboct;
									break;
									case '11' :
									echo '<th align=right>'.number_format($row->spbnov).'</th>';
									$grandtot11=$grandtot11+$row->spbnov;
									break;
									case '12' :
									echo '<th align=right>'.number_format($row->spbdes).'</th>';
									$grandtot12=$grandtot12+$row->spbdes;
									break;
								}
								$bl++;
							}
							echo "<tr>";    
						}
						echo "<tr>
						<td style='background-color:#F2F2F2;' align=center>G r a n d   T o t a l</td>";
						$bl=$blasal;
						for($i=1;$i<=$interval;$i++){
							switch ($bl){
								case '1' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot01).'</th>';
								break;
								case '2' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot02).'</th>';
								break;
								case '3' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot03).'</th>';
								break;
								case '4' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot04).'</th>';
								break;
								case '5' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot05).'</th>';
								break;
								case '6' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot06).'</th>';
								break;
								case '7' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot07).'</th>';
								break;
								case '8' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot08).'</th>';
								break;
								case '9' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot09).'</th>';
								break;
								case '10' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot10).'</th>';
								break;
								case '11' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot11).'</th>';
								break;
								case '12' :
								echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot12).'</th>';
								break;
							}
							$bl++;
						}
					}
					?>
				</tbody>
			</table>
			<td colspan='13' align='center'>
				<br>
				<button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button></a>
			</td>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	$( "#cmdreset" ).click(function() {  
    var Contents = $('#sitabel').html();    
    window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
  });
</script>