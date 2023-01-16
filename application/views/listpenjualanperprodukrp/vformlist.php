<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<div class="panel-body table-responsive">
				<table class="table color-bordered-table info-bordered-table" id="sitabel">
					<thead>
						<?php if($isi){ ?>
							<tr>
								<th>Kategori</th>
								<th>KODE</th>
								<th style="width: 30%;">NAMA PRODUK</th>
								<th style="width: 30%;">SUPPLIER</th>
								<?php 
								$totprod 	= 0;
								$totgrup 	= 0;
								$totkategori= 0;
								$totgrand	= 0;
								foreach($areanya as $row){?>
									<th><?= $row->i_area;?></th>
								<?php }?>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$group 	 	= '';
							$kategori 	= '';
							$judul 		= false;
							foreach($isi as $row){
								if($kategori==''){ ?>
									<tr>
										<td colspan="44"><h3><?= $row->e_product_groupname;?></h3>
										</td>
									</tr>";

								<?php }
								if( ($kategori!='' && $kategori!=$row->kategori) || ($group!='' && $group!=$row->e_product_groupname)){?>
									<tr><td colspan="44"><h5>Total Qty <?= $group." ".$kategori." = ".number_format($totkategori);?></h5></td></tr> 
									<?php if($kategori!='LAMA'){?>
										<tr><td colspan=44><h3><?= $row->e_product_groupname;?></h3></td></tr>
									<?php }
									$totkategori=0;
								}
								if($group!='' && $group!=$row->e_product_groupname){ ?>
									<tr><td colspan=44><h5>Total Qty <?= $group." = ".number_format($totgrup);?></h5></td></tr>
									<tr><td colspan=44><h3><?= $row->e_product_groupname;?></h3></td></tr>
									<?php
									$totgrup=0;
								}
								if(!$judul){?>
									<tr>
										<td><?= $row->kategori;?></td>
										<td><?= $row->i_product;?></td>
										<td><?= $row->e_product_name;?></td>
										<td><?= $row->e_supplier_name;?></td>
										<?php
										if($row->area_00==''){
											$row->area_00=0;
										}
										if($row->area_01==''){
											$row->area_01=0;
										}
										if($row->area_02==''){
											$row->area_02=0;
										}
										if($row->area_03==''){
											$row->area_03=0;
										}
										if($row->area_04==''){
											$row->area_04=0;
										}
										if($row->area_05==''){
											$row->area_05=0;
										}
										if($row->area_06==''){
											$row->area_06=0;
										}
										if($row->area_07==''){
											$row->area_07=0;
										}
										if($row->area_08==''){
											$row->area_08=0;
										}
										if($row->area_09==''){
											$row->area_09=0;
										}
										if($row->area_10==''){
											$row->area_10=0;
										}
										if($row->area_11==''){
											$row->area_11=0;
										}
										if($row->area_12==''){
											$row->area_12=0;
										}
										if($row->area_13==''){
											$row->area_13=0;
										}
										if($row->area_14==''){
											$row->area_14=0;
										}
										if($row->area_15==''){
											$row->area_15=0;
										}
										if($row->area_16==''){
											$row->area_16=0;
										}
										if($row->area_17==''){
											$row->area_17=0;
										}
										if($row->area_18==''){
											$row->area_18=0;
										}
										if($row->area_19==''){
											$row->area_19=0;
										}
										if($row->area_20==''){
											$row->area_20=0;
										}
										if($row->area_21==''){
											$row->area_21=0;
										}
										if($row->area_22==''){
											$row->area_22=0;
										}
										if($row->area_23==''){
											$row->area_23=0;
										}
										if($row->area_24==''){
											$row->area_24=0;
										}
										if($row->area_25==''){
											$row->area_25=0;
										}
										if($row->area_26==''){
											$row->area_26=0;
										}
										if($row->area_27==''){
											$row->area_27=0;
										}
										if($row->area_28==''){
											$row->area_28=0;
										}
										if($row->area_29==''){
											$row->area_29=0;
										}
										if($row->area_30==''){
											$row->area_30=0;
										}
										if($row->area_31==''){
											$row->area_31=0;
										}
										if($row->area_32==''){
											$row->area_32=0;
										}
										if($row->area_33==''){
											$row->area_33=0;
										}
										if($row->area_pb==''){
											$row->area_pb=0;
										} ?>
										<td align="right"><?= number_format($row->area_00);?></td>
										<td align="right"><?= number_format($row->area_01);?></td>
										<td align="right"><?= number_format($row->area_02);?></td>
										<td align="right"><?= number_format($row->area_03);?></td>
										<td align="right"><?= number_format($row->area_04);?></td>
										<td align="right"><?= number_format($row->area_05);?></td>
										<td align="right"><?= number_format($row->area_06);?></td>
										<td align="right"><?= number_format($row->area_07);?></td>
										<td align="right"><?= number_format($row->area_08);?></td>
										<td align="right"><?= number_format($row->area_09);?></td>
										<td align="right"><?= number_format($row->area_10);?></td>
										<td align="right"><?= number_format($row->area_11);?></td>
										<td align="right"><?= number_format($row->area_12);?></td>
										<td align="right"><?= number_format($row->area_13);?></td>
										<td align="right"><?= number_format($row->area_14);?></td>
										<td align="right"><?= number_format($row->area_15);?></td>
										<td align="right"><?= number_format($row->area_16);?></td>
										<td align="right"><?= number_format($row->area_17);?></td>
										<td align="right"><?= number_format($row->area_18);?></td>
										<td align="right"><?= number_format($row->area_19);?></td>
										<td align="right"><?= number_format($row->area_20);?></td>
										<td align="right"><?= number_format($row->area_21);?></td>
										<td align="right"><?= number_format($row->area_22);?></td>
										<td align="right"><?= number_format($row->area_23);?></td>
										<td align="right"><?= number_format($row->area_24);?></td>
										<td align="right"><?= number_format($row->area_25);?></td>
										<td align="right"><?= number_format($row->area_26);?></td>
										<td align="right"><?= number_format($row->area_27);?></td>
										<td align="right"><?= number_format($row->area_28);?></td>
										<td align="right"><?= number_format($row->area_29);?></td>
										<td align="right"><?= number_format($row->area_30);?></td>
										<td align="right"><?= number_format($row->area_31);?></td>
										<td align="right"><?= number_format($row->area_32);?></td>
										<td align="right"><?= number_format($row->area_33);?></td>
										<td align="right"><?= number_format($row->area_pb);?></td>
										<?php $totprod=$row->area_00+$row->area_01+$row->area_02+$row->area_03+$row->area_04+$row->area_05+$row->area_06+$row->area_07+$row->area_08+$row->area_09+$row->area_10+$row->area_11+$row->area_12+$row->area_13+$row->area_14+$row->area_15+$row->area_16+$row->area_17+$row->area_18+$row->area_19+$row->area_20+$row->area_21+$row->area_22+$row->area_23+$row->area_24+$row->area_25+$row->area_26+$row->area_27+$row->area_28+$row->area_29+$row->area_30+$row->area_31+$row->area_32+$row->area_33+$row->area_pb;
										?>
										<td align=right><?= number_format($totprod);?></td>
									</tr>
									<?php 
									$totgrup=$totgrup+$totprod;
									$totkategori=$totkategori+$totprod;
									$totgrand=$totgrand+$totprod;
								}
								$group=$row->e_product_groupname;
								$kategori=$row->kategori;
								$judul=false;
							} ?>
							<tr><td colspan="44"><h5>Total Qty <?= $group." ".$kategori." = ".number_format($totkategori);?></h5></td></tr>
							<tr><td colspan="44"><h5>Total Qty <?= $group." = ".number_format($totgrup);?></h5></td></tr>
							<tr><td colspan="44"><h5>Grand Total Qty = <?= number_format($totgrand);?></h5></td></tr>
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