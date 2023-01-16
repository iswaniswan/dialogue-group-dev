<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<?php 
			if($isi){
				foreach($isi as $row){
					$periode=$row->e_mutasi_periode;
				}
			}else{
				$periode=$iperiode;
			}
			$a=substr($periode,0,4);
			$b=substr($periode,4,2);
			$periode=mbulan($b)." - ".$a;
			$rpselisih=0;
			$rpsaldoakhir=0;
			$rpstockopname=0;
			if($isi){
				foreach($isi as $row){
					$this->db->select("	i_product, v_product_retail
						from tr_product_price
						where i_product='$row->i_product' and i_price_group='00'");
					$query = $this->db->get();
					if ($query->num_rows() > 0){
						foreach($query->result() as $tmp){
							$row->v_product_retail=$tmp->v_product_retail;
						}
					}else{
						$row->v_product_retail=0;
					}
					$rpselisih=$rpselisih+(($row->n_saldo_stockopname-$row->n_saldo_akhir)*$row->v_product_retail);
					$rpsaldoakhir=$rpsaldoakhir+($row->n_saldo_akhir*$row->v_product_retail);
					$rpstockopname=$rpstockopname+($row->n_saldo_stockopname*$row->v_product_retail);
				}
			}
			?>
			<div class="panel-body table-responsive">
				<div class="col-md-3">
					<div class="form-group row">
						<label class="col-md-4">Periode</label>
						<div class="col-sm-6">
							<b><?= $periode;?></b>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4">Saldo Akhir</label>
						<div class="col-sm-6">
							<b><?= 'Rp. '.number_format($rpsaldoakhir); ?></b>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group row">
						<label class="col-md-6">Saldo Stockopname</label>
						<div class="col-sm-6">
							<b><?= 'Rp. '.number_format($rpstockopname); ?></b>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-6">Selisih</label>
						<div class="col-sm-6">
							<b><?= 'Rp. '.number_format($rpselisih); ?></b>
						</div>
					</div>
				</div>
				<table class="table color-table success-table table-bordered" id="sitabel">
					<thead>
						<tr>
							<th class="text-center" rowspan="2">No</th>
							<th class="text-center" rowspan="2">Kode</th>
							<th class="text-center" rowspan="2">Nama</th>
							<th class="text-center" rowspan="2">Saldo Awal</th>
							<th class="text-center" colspan="2">Penerimaan</th>
							<th class="text-center" colspan="2">Pengeluaran</th>
							<th class="text-center" rowspan="2">Saldo Akhir</th>
							<th class="text-center" rowspan="2">Stock Opname</th>
							<th class="text-center" rowspan="2">Selisih</th>
							<th class="action" rowspan="2">Action</th>
						</tr>
						<tr>
							<th class="text-center">Dari Gudang Pusat</th>
							<th class="text-center">Dari Toko</th>
							<th class="text-center">Retur ke Gudang Pusat</th>
							<th class="text-center">Ke Toko</th>
						</tr>
					</thead>
					<tbody>
						<?php if($isi){ 
							$i=0;
							$selisih=0;
							$tsaldoawal=0;
							$tbbm=0;
							$tdrtk=0;
							$tbbk=0;
							$tktk=0;
							$tsaldoakhir=0;
							$tso=0;
							$tselisih=0;
							foreach($isi as $row){
								$i++;
								$selisih=($row->n_saldo_stockopname)-$row->n_saldo_akhir; ?>
								<tr>
									<td class="text-center"><?= $i;?></td>
									<td><?= $row->i_product;?></td>
									<td><?= $row->e_product_name;?></td>
									<td class="text-right"><?= $row->n_saldo_awal;?></td>
									<td class="text-right"><?= $row->n_mutasi_bbm;?></td>
									<td class="text-right"><?= $row->n_mutasi_daritoko;?></td>
									<td class="text-right"><?= $row->n_mutasi_bbk;?></td>
									<td class="text-right"><?= $row->n_mutasi_ketoko;?></td>
									<td class="text-right"><?= $row->n_saldo_akhir;?></td>
									<td class="text-right"><?= $row->n_saldo_stockopname;?></td>
									<td class="text-right"><?= $selisih;?></td>
									<td class="text-center"><a href="#" onclick='show("<?= $folder;?>/cform/detail/<?= $iperiode.'/'.$row->i_product.'/'.$row->n_saldo_awal;?>","#main");'><i class="fa fa-pencil text-info"></i></a></td>
								</tr>
								<?php 
								$tsaldoawal=$tsaldoawal+$row->n_saldo_awal;
								$tbbm=$tbbm+$row->n_mutasi_bbm;
								$tdrtk=$tdrtk+$row->n_mutasi_daritoko;
								$tbbk=$tbbk+$row->n_mutasi_bbk;
								$tktk=$tktk+$row->n_mutasi_ketoko;
								$tsaldoakhir=$tsaldoakhir+$row->n_saldo_akhir;
								$tso=$tso+$row->n_saldo_stockopname;
								$tselisih=$tselisih+$selisih;
							}
						} ?>
					</tbody>
					<tfoot>
						<tr class="success">
							<th class="text-center text-inverse" colspan=3>Total</th>
							<th class="text-right"><?= $tsaldoawal;?></th>
							<th class="text-right"><?= $tbbm;?></th>
							<th class="text-right"><?= $tdrtk;?></th>
							<th class="text-right"><?= $tbbk;?></th>
							<th class="text-right"><?= $tktk;?></th>
							<th class="text-right"><?= $tsaldoakhir;?></th>
							<th class="text-right"><?= $tso;?></th>
							<th class="text-right"><?= $tselisih;?>
							<th></th>
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