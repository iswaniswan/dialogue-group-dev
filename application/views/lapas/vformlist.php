<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title." Periode : ".mbulan($bulan)." ".$tahun; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<div class="panel-body table-responsive">
				<table class="table color-bordered-table info-bordered-table hover-table" id="sitabel">
					<thead>
						<?php if($penjualan){ ?>
							<tr>
								<th>Area</th>
								<th>Salesman</th>
								<th style="text-align: right;">Target Omset</th>
								<th style="text-align: right;">Real Omset</th>
								<th style="text-align: right;">% Omset</th>
								<th style="text-align: right;">Target Tagihan</th>
								<th style="text-align: right;">Real Tagihan</th>
								<th style="text-align: right;">% Tagih</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							error_reporting(0);
							$subtargetomset	= 0;
							$subrealomset	= 0;
							$subpersenomset	= 0;
							$subtargettagih	= 0;
							$subrealtagih	= 0;
							$subpersentagih	= 0;
							$ada 			= false;
							foreach($penjualan as $raw){
								$ada=true;
								if($raw->v_spb=='' || $raw->v_spb==null){
									$raw->v_spb=0;
								}
								if($raw->v_target=='' || $raw->v_target==null){
									$raw->v_target=0;
								}
								if($raw->v_spb!=0 && $raw->v_target!=0){
									$persenreal=($raw->v_spb/$raw->v_target)*100;
								}else{
									$persenreal=0;
								}
								$subtargetomset = $subtargetomset+$raw->v_target;
								$subrealomset 	= $subrealomset+$raw->v_spb;
								$subpersenomset	= number_format(($subrealomset/$subtargetomset)*100,2); ?>
								<tr>
									<td><?= $raw->i_area." - ".$raw->e_area_name;?></td>
									<td><?= $raw->i_salesman." - ".$raw->e_salesman_name;?></td>
									<td align="right"><?= number_format($raw->v_target);?></td>
									<td align="right"><?= number_format($raw->v_spb);?></td>
									<td align="right"><?= number_format($persenreal);?> %</td>
									<?php 
									$ada=false;
									$totcash = 0;
									$realcash= 0;
									foreach($cash as $riw){
										if( ($riw->i_area==$raw->i_area) && ($riw->i_salesman==$raw->i_salesman)){
											$ada=true;
											if($riw->realisasi==null || $riw->realisasi==''){
												$realcash=0;
											}else{
												$realcash=$riw->realisasi;
											}
											if($riw->total==null || $riw->total==''){
												$totcash=0;
											}else{
												$totcash=$riw->total;
											}
											if($riw->total!=0){
												$persencash=number_format(($riw->realisasi/$riw->total)*100,2);
											}else{
												$persencash='0';
											}
										}
										if($ada)break;
									}
									$ada=false;
									$realcr = 0;
									$totcr = 0;
									foreach($credit as $rew){
										if( ($rew->i_area==$raw->i_area) && ($rew->i_salesman==$raw->i_salesman)){
											$ada=true;
											if($rew->realisasi==null || $rew->realisasi==''){
												$realcr=0;
											}else{
												$realcr=$rew->realisasi;
											}
											if($rew->total==null || $rew->total==''){
												$totcr=0;
											}else{
												$totcr=$rew->total;
											}
											if($rew->total!=0){
												$persencr=number_format(($rew->realisasi/$rew->total)*100,2);
											}else{
												$persencr='0';
											}
										}
										if($ada)break;
									}

									$targettagih=$totcash+$totcr;
									$realtagih=$realcash+$realcr;
									$persentagih=number_format(($realtagih/$targettagih)*100,2);
									$subtargettagih=$subtargettagih+$targettagih;
									$subrealtagih=$subrealtagih+$realtagih;
									$subpersentagih=number_format(($subrealtagih/$subtargettagih)*100,2);?>
									<td align="right"><?= number_format($targettagih);?></td>
									<td align="right"><?= number_format($realtagih);?></td>
									<td align="right"><?= number_format($persentagih);?></td>
								</tr>
							<?php } ?>
							<tr>
								<td colspan="2" align="right"><b>Total Nasional</b></td>
								<td align="right"><b><?= number_format($subtargetomset);?></b></td>
								<td align="right"><b><?= number_format($subrealomset);?></b></td>
								<td align="right"><b><?= number_format($subpersenomset);?> % %</b></td>
								<td align="right"><b><?= number_format($subtargettagih);?></b></td>
								<td align="right"><b><?= number_format($subrealtagih);?></b></td>
								<td align="right"><b><?= number_format($subpersentagih);?> %</b></td>
							</tr>
						<?php } ?>
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