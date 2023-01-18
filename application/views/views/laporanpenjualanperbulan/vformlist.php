<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<?php 
			$periode=$tahun.$bulan;
			$a=substr($periode,0,4);
			$b=substr($periode,4,2);
			$periode=mbulan($b)." - ".$a;
			?>
			<div class="panel-body table-responsive">
				<h5 class="box-title">Periode <code><?= $periode;?></code></h5>
				<table class="table color-table success-table table-bordered" id="sitabel">
					<thead>
						<tr>
							<th style="text-align: center;">Area</th>
							<th style="text-align: center;">Target</th>
							<th style="text-align: center;">SPB(GROSS)</th>
							<th style="text-align: center;">%</th>
							<th style="text-align: center;">SPB(NETTO)</th>
							<th style="text-align: center;">%</th>
							<th style="text-align: center;">Nota(GROSS)</th>
							<th style="text-align: center;">%</th>
							<th style="text-align: center;">Nota(NETTO)</th>
							<th style="text-align: center;">%</th>
							<th style="text-align: center;">Retur</th>
							<th style="text-align: center;">%</th>
						</tr>
					</thead>
					<tbody>
						<?php if($isi){ 
							$ret		= 0;
							$retnetto	= 0;
							$target		= 0;
							$spb		= 0;
							$spbnetto	= 0;
							$ntgross	= 0;
							$ntnetto	= 0;
							$ntreguler	= 0;
							$ntbaby		= 0;
							foreach($isi as $row){
								if($row->v_nota_gross==null || $row->v_nota_gross==''){
									$row->v_nota_gross=0;
								}
								if($row->v_target!=0){
									$persen=number_format(($row->v_nota_gross/$row->v_target)*100,2);
									$persennetto=number_format(($row->v_nota_netto/$row->v_target)*100,2);
								}else{
									$persen='0.00';
									$persennetto='0.00';
								}
								if($row->v_spb_gross==null || $row->v_spb_gross=='')$row->v_spb_gross=0;
								if($row->v_target!=0){
									$persenspb=number_format(($row->v_spb_gross/$row->v_target)*100,2);
									$persenspbnetto=number_format(($row->v_spb_netto/$row->v_target)*100,2);
								}else{
									$persenspb='0.00';
									$persenspbnetto='0.00';
								}
								if($row->v_retur_insentif==null || $row->v_retur_insentif=='')$row->v_retur_insentif=0;
								if($row->v_target!=0){
									$persenret=number_format(($row->v_retur_insentif/$row->v_target)*100,2);
								}else{
									$persenret='0.00';
								} ?>
								<tr>
									<td><?= $row->e_area_name;?></td>
									<td style="font-size: 12px; text-align: right;">Rp. <?= number_format($row->v_target);?></td>
									<td style="font-size: 12px; text-align: right;">Rp. <?= number_format($row->v_spb_gross);?></td>
									<td style="font-size: 12px; text-align: right;"><?= $persenspb;?> %</td>
									<td style="font-size: 12px; text-align: right;">Rp. <?= number_format($row->v_spb_netto);?></td>
									<td style="font-size: 12px; text-align: right;"><?= $persenspbnetto;?> %</td>
									<td style="font-size: 12px; text-align: right;">Rp. <?= number_format($row->v_nota_gross);?></td>
									<td style="font-size: 12px; text-align: right;"><?= $persen;?> %</td>
									<td style="font-size: 12px; text-align: right;">Rp. <?= number_format($row->v_nota_netto);?></td>
									<td style="font-size: 12px; text-align: right;"><?= $persennetto;?> %</td>
									<td style="font-size: 12px; text-align: right;">Rp. <?= number_format($row->v_retur_insentif);?></td>
									<td style="font-size: 12px; text-align: right;"><?= $persenret;?> %</td>
								</tr>
								<?php 
								$target=$target+$row->v_target;
								$ntgross=$ntgross+$row->v_nota_gross;
								$ntnetto=$ntnetto+$row->v_nota_netto;
								$spb=$spb+$row->v_spb_gross;
								$spbnetto=$spbnetto+$row->v_spb_netto;
								$ret=$ret+$row->v_retur_insentif;
							}
						} ?>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align: center;"><b>Total</b></td>
							<td style="font-size: 12px; text-align: right;"><b>Rp. <?= number_format($target);?></b></td>
							<td style="font-size: 12px; text-align: right;"><b>Rp. <?= number_format($spb);?></b></td>
							<td style="font-size: 12px; text-align: right;">&nbsp;</td>
							<td style="font-size: 12px; text-align: right;"><b>Rp. <?= number_format($spbnetto);?></b></td>
							<td style="font-size: 12px; text-align: right;">&nbsp;</td>
							<td style="font-size: 12px; text-align: right;"><b>Rp. <?= number_format($ntgross);?></b></td>
							<td style="font-size: 12px; text-align: right;">&nbsp;</td>
							<td style="font-size: 12px; text-align: right;"><b>Rp. <?= number_format($ntnetto);?></b></td>
							<td style="font-size: 12px; text-align: right;">&nbsp;</td>
							<td style="font-size: 12px; text-align: right;"><b>Rp. <?= number_format($ret);?></b></td>
							<td style="font-size: 12px; text-align: right;">&nbsp;</td>
						</tr>
					</tfoot>
				</table>
				<button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
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