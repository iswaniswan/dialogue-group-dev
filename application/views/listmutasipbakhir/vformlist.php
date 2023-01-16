<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<center>
				<h3><b>DAILY REPORT SO TOKO</b></h3>
			</center>
			<?php echo "<center><h3>Periode " . $iperiode . "</h3></center>"; ?>
			<div class="panel-body table-responsive">
				<table class="table color-table success-table table-bordered" width="80%" id="sitabel">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th class="text-center">Nama Toko</th>
							<th class="text-center">Kode Lang</th>
							<th class="text-center">Saldo Akhir</th>
							<th class="text-center">Saldo SO</th>
							<th class="text-center">Selisih</th>
						</tr>
					</thead>
					<tbody>
						<?php 	
						$tmpselisih = 0;
						$tmprpsaldoakhir = 0;
						$tmprpstockopname = 0;
						$no = 1;
						foreach ($isi as $row) {
							if ((int) $row->akhir > 0) {
								$tmpselisih = $tmpselisih + $row->selisih;
								$tmprpsaldoakhir = $tmprpsaldoakhir + $row->akhir;
								$tmprpstockopname = $tmprpstockopname + $row->opname;
								?>
								<tr>
									<td><?php echo $no; ?></td>
									<td><?php echo $row->e_customer_name; ?></td>
									<td class="text-center"><?php echo $row->i_customer; ?></td>
									<td class="text-right"><?php echo number_format($row->akhir); ?></td>
									<td class="text-right"><?php echo number_format($row->opname); ?></td>
									<td class="text-right"><?php echo number_format($row->selisih); ?></td>
								</tr>
								<?php 
								$no++;
							}
						}
						?>
					</tbody>
					<tfoot>
						<tr class="success">
							<th class="text-center text-inverse" colspan=3>Total</th>
							<th class="text-right"><?= number_format($tmprpsaldoakhir);?></th>
							<th class="text-right"><?= number_format($tmprpstockopname);?></th>
							<th class="text-right"><?= number_format($tmpselisih);?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
</script>