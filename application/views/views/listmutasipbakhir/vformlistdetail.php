<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<div class="panel-body table-responsive">
				<?php 
				if($detail){
					foreach($detail as $row){
						$periode=$row->periode;
					}
				}else{
					$periode=$iperiode;
				}
				$perper=$periode;
				$a=substr($periode,0,4);
				$b=substr($periode,4,2);
				$periode=mbulan($b)." - ".$a;
				$kode = '';
				if($detail){
					foreach($detail as $row){ 
						$kode = $row->product." - ".$row->e_product_name;
					}
				}
				?>
				<input name="iperiode" id="iperiode" value="<?= $perper;?>" type="hidden">
				<input name="iproduct" id="iproduct" value="<?= $iproduct;?>" type="hidden">
				<input name="nsaldo" id="nsaldo" value="<?= $saldo;?>" type="hidden">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-2">Kode</label>
						<div class="col-sm-10">
							<b><?= $kode;?></b>
						</div>
					</div>
				</div>
				<table class="table color-table success-table table-bordered" id="sitabel">
					<thead>
						<tr>
							<th class="text-center">Refferensi</th>
							<th class="text-center">Tanggal</th>
							<th class="text-center">Awal</th>
							<th class="text-center">In</th>
							<th class="text-center">Out</th>
							<th class="text-center">Akhir</th>
						</tr>
					</thead>
					<tbody>
						<?php if($detail){ 
							$no=0;
							$tsawal=0;
							$in=0;
							$out=0;
							$sawal=0;
							$sahir=$saldo;
							foreach($detail as $row){
								$no++;
								$tmp=explode('-',$row->dreff);
								$tgl=$tmp[2];
								$bln=$tmp[1];
								$thn=$tmp[0];
								if(strlen($tgl)==2){
									$row->dreff=$tgl.'-'.$bln.'-'.$thn;
								}
								$sawal=$sahir;
								if($no==0)$tsawal=$sawal;
								$sahir=$sawal+$row->masuk-$row->keluar;
								?>
								<tr>
									<td><?= $row->ireff." (".$row->i_customer."".$row->i_customer1." - ".$row->e_customer_name1."".$row->e_customer_name2."".$row->i_spmb;?>)</td>
									<td class="text-center"><?= $row->dreff;?></td>
									<td class="text-right"><?= $sawal;?></td>
									<td class="text-right"><?= $row->masuk;?></td>
									<td class="text-right"><?= $row->keluar;?></td>
									<td class="text-right"><?= $sahir;?></td>
								</tr>
								<?php 
								$in=$in+$row->masuk;
								$out=$out+$row->keluar;
							}
						} ?>
					</tbody>
					<tfoot>
						<tr>
							<th class="text-center text-inverse" colspan="2">Total</th>
							<th class="text-right"><?= number_format($tsawal);?></th>
							<th class="text-right"><?= number_format($in);?></th>
							<th class="text-right"><?= number_format($out);?></th>
							<th class="text-right"><?= number_format($sahir);?></th>
						</tr>
					</tfoot>
				</table>
				<button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>&nbsp;&nbsp;
				<button type="button" name="cmdprint" id="cmdprint" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-print"></i>&nbsp;&nbsp;Print</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#cmdreset" ).click(function() {  
		var Contents = $('#sitabel').html();    
		window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
	});

	$("#cmdprint" ).click(function() {  
		var lebar 	= 1024;
		var tinggi 	= 768;
		var periode = $("#iperiode").val();
		var product = $("#iproduct").val();
		var sawal   = $("#nsaldo").val();
		eval('window.open("<?php echo site_url($folder); ?>"+"/cform/cetakdetail/"+periode+"/"+product+"/"+sawal,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
	});
</script>