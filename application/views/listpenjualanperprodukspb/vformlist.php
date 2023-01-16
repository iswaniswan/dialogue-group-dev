<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title." Periode : ".mbulan($bulan)." ".$tahun; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<div class="panel-body table-responsive">
				<table class="table color-bordered-table info-bordered-table" id="sitabel">
					<thead>
						<?php if($isi){ 
							$ntotal		= 0;
							$vtotal		= 0;
							$nsubtotal	= 0;
							$vsubtotal	= 0;
							$ngrandtotal= 0;
							$vgrandtotal= 0;
							?>
							<tr>
								<th>KODE</th>
								<th>NAMA PRODUK</th>
								<?php 
								$kol=4;
								foreach($areanya as $row){?>
									<th><?= $row->i_area;?></th>
									<?php 
									$kol++; 
								}
								$sub=$kol-2;?>
								<th>Jml Total</th>
								<th>Nilai Total</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$group 	 	= '';
							foreach($prodnya as $row){
								if($group==''){?>
									<tr>
										<td colspan="<?= $kol;?>">
											<h2><?= $row->e_product_groupname;?></h2>
										</td>
									</tr>";
								<?php }
								if($group!='' && $group!=$row->e_product_groupname){?>
									<tr>
										<td colspan="<?= $sub;?>"><h2>Sub Total</h2></td>
										<td align="right"><?= number_format($nsubtotal);?></td>
										<td align="right"><?= number_format($vsubtotal);?></td>
									</tr>
									<tr>
										<td colspan="<?= $kol;?>"><h2><?= $row->e_product_groupname;?></h2></td>
									</tr>
									<?php 
									$nsubtotal=0;
									$vsubtotal=0;
								}?>
								<tr>
									<td><?= $row->i_product;?></td>
									<td><?= $row->e_product_name;?></td>
									<?php
									foreach($areanya as $raw){
										$ada=false;
										foreach($isi as $riw){
											if( ($riw->i_product==$row->i_product) && ($raw->i_area==$riw->i_area) ){
												$ada=true;?>
												<td align="right"><?= $riw->jumlah;?></td>
												<?php 
												$ntotal=$ntotal+$riw->jumlah;
												$vtotal=$vtotal+$riw->nilai;
												$nsubtotal=$nsubtotal+$riw->jumlah;
												$vsubtotal=$vsubtotal+$riw->nilai;
												$ngrandtotal=$ngrandtotal+$riw->jumlah;
												$vgrandtotal=$vgrandtotal+$riw->nilai;
											}
											if($ada)break;
										}
										if(!$ada){?>
											<td align="right">0</td>
										<?php }
									}?>
									<td align="right"><?= number_format($ntotal);?></td>
									<td align="right"><?= number_format($vtotal);?></td>
								</tr>
								<?php 
								$ntotal=0;
								$vtotal=0;
								$group=$row->e_product_groupname;
							}?>
							<tr>
								<td colspan="<?= $sub;?>"><h2>Sub Total</h2></td>
								<td align="right"><?= number_format($nsubtotal);?></td>
								<td align="right"><?= number_format($vsubtotal);?></td>
							</tr>
							<tr>
								<td colspan="<?= $sub;?>"><h2>Grand Total</h2></td>
								<td align="right"><?= number_format($ngrandtotal);?></td>
								<td align="right"><?= number_format($vgrandtotal);?></td>
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