<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<div class="panel-body table-responsive">
				<h5 class="box-title">Dari Tanggal <code><?= $dfrom;?></code> Sampai Tanggal <code><?= $dto;?></code></h5>
				<table class="table color-table success-table table-bordered" id="sitabel">
					<thead>
						<tr>
							<th style="text-align: center;">Area</th>
							<th style="text-align: center;">SPB</th>
							<th style="text-align: center;">Nota Kotor</th>
							<th style="text-align: center;">Potongan</th>
							<th style="text-align: center;">Nota Bersih</th>
							<th style="text-align: center;">Retur</th>
						</tr>
					</thead>
					<tbody>
						<?php if($isi){ 
							$ret		= 0;
							$target		= 0;
							$spb		= 0;
							$ntgross	= 0;
							$ntnetto	= 0;
							$ntreguler	= 0;
							$ntbaby		= 0;
							$dis		= 0;
							foreach($isi as $row){
								if($row->v_nota_gross==null || $row->v_nota_gross==''){
									$row->v_nota_gross=0;
								}
								if($row->v_nota_netto==null || $row->v_nota_netto==''){
									$row->v_nota_netto=0;
								} ?>
								<tr>
									<td><?= $row->i_area." - ".$row->e_area_name;?></td>
									<td style="text-align: right;"><?= number_format($row->v_spb);?></td>
									<td style="text-align: right;"><?= number_format($row->v_nota_gross);?></td>
									<td style="text-align: right;"><?= number_format($row->v_nota_discounttotal);?></td>
									<td style="text-align: right;"><?= number_format($row->v_nota_netto);?></td>
									<td style="text-align: right;"><?= number_format($row->v_kn);?></td>
								</tr>
								<?php 
								$ntgross=$ntgross+$row->v_nota_gross;
								$spb=$spb+$row->v_spb;
								$ret=$ret+$row->v_kn;
								$dis=$dis+$row->v_nota_discounttotal;
								$ntnetto=$ntnetto+$row->v_nota_netto;
							}
						} ?>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align: center;"><b>Grand Total</b></td>
							<td style="text-align: right;"><b><?= number_format($spb);?></b></td>
							<td style="text-align: right;"><b><?= number_format($ntgross);?></b></td>
							<td style="text-align: right;"><b><?= number_format($dis);?></b></td>
							<td style="text-align: right;"><b><?= number_format($ntnetto);?></b></td>
							<td style="text-align: right;"><b><?= number_format($ret);?></b></td>
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