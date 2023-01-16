<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<style type="text/css" media="print">
	.noDisplay{
		display:none;
	}
	.pagebreak {
		page-break-before: always;
	}

	@media print {
	.page-break { display: block; page-break-before: always; }
	}

	.style {
		padding: 1px 8px;
	}

	{
		size: landscape;
	}

	@page { size: Letter; 
		margin: 0mm;  /* this affects the margin in the printer settings */
	}
</style>
<!-- color CSS -->
<div class="row">
	<div class="col-md-12">
		<div class="white-box printableArea">
			<h3><img src="<?= base_url(); ?>assets/images/logo/dsg.png"><b>&nbsp;&nbsp;<?= check_constant('NmPerusahaan');?></b><span class="pull-right">Cimahi,&nbsp;<?= date('d', strtotime($data->d_sj)).' '.$this->fungsi->mbulan(date('m', strtotime($data->d_sj))).' '.date('Y', strtotime($data->d_sj));?></span></h3>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="pull-left">
						<address>
							<h3> &nbsp;<b>SURAT JALAN</b><br><font size="3px;">&nbsp;(Makloon)</font></h3>
							<p class="text-muted m-l-5">Nomor Dokumen : <b><?= $data->i_sj;?></b>
								<br> 
								<br>Kami kirimkan barang-barang sebagai berikut :</p>
							</address>
						</div>
						<div class="pull-right text-right">
							<address>
								<h3>Kepada Yth,</h3>
								<h4 class="font-bold"><?= ucwords(strtolower($data->e_supplier_name));?></h4>						
							</address>
						</div>
				</div>
					<div class="col-md-12">
						<div class="table-responsive m-t-0">
							<table class="table table-bordered" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th style="padding: 2px 10px; width: 3%;" class="text-center">No</th>
										<th style="padding: 2px 10px;"  >Kode Barang</th>
										<th style="padding: 2px 10px;"  class="text-left">Nama Barang</th>
										<th style="padding: 2px 10px;"  class="text-right">Unit</th>
										<th style="padding: 2px 10px;"  class="text-center">Satuan</th>
										<th style="padding: 2px 10px;"  class="text-center">Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$counter = 0;
									if ($detail) {
										foreach ($detail as $row) {
											$counter++;?>
											<tr>
												<td style="padding: 1px 8px;"  class="text-center">
													<?= $counter;?>
												</td>
												<td style="padding: 1px 8px;" >
													<?= $row->i_material;?>
												</td>
												<td style="padding: 1px 8px;" >
													<?= $row->e_material_name;?>
												</td>
												<td style="padding: 1px 8px;"  class="text-right">
													<?= $row->n_pemenuhan;?>
												</td>
												<td style="padding: 1px 8px;"  class="text-center">
													pcs
												</td>
												<td style="padding: 1px 8px;" >
													<?= $row->e_remark;?>
												</td>
											</td>
										</tr>
									<?php } 
								}?>
							</tbody>
						</table>
					</div>
				</div>
					<div class="col-md-4">
						<div class="pull-center m-t-30 text-center">
							<p>Tanda Terima,</p>
							<p>&nbsp;</p>
							<hr>
							<h3>(.............................................................)</h3>
						</div>
					</div>		
					<div class="col-md-4">
						<div class="pull-center m-t-30 text-center">
							<p>Mengetahui</p>
							<p>&nbsp;</p>
							<hr>
							<h3>(.............................................................)</h3>
						</div>
					</div>
					<div class="col-md-4">
						<div class="pull-center m-t-30 text-center">
							<p>Hormat Kami,</p>
							<p>&nbsp;</p>
							<hr>
							<h3>(.............................................................)</h3>
						</div>
					</div>
					<hr>
				</div>
				<div class="page-break">
					<div class="row">
				<div class="col-md-12">
					<div class="white-box printableArea">	
					
						<div class="col-md-12">
							<h3 align="center"><b>L  A  M  P  I  R  A  N</b>
						</h3>
							<p></p>
						</div>
					</div>
					<div class="pull-left">
						<address>
							<h3> &nbsp;<b>SURAT JALAN</b><br><font size="3px;">&nbsp;(Makloon)</font></h3>
							<p class="text-muted m-l-5">Nomor Dokumen : <b><?= $data->i_sj;?></b></p>
								<br> 
						</address>
					</div>
					<div class="col-md-12">	
						<div class="table-responsive m-t-0">
							<table class="table table-bordered" cellpadding="0" cellspacing="0">	
								<thead>
									<tr>
										<th style="padding: 2px 10px; width: 3%;" class="text-center">No</th>
										<th style="padding: 2px 10px;"  >Kode Barang</th>
										<th style="padding: 2px 10px;"  >List Kode Barang</th>
										<th style="padding: 2px 10px;"  class="text-left">Nama Barang</th>
										<th style="padding: 2px 10px;"  class="text-right">Unit</th>
										<th style="padding: 2px 10px;"  class="text-center">Satuan</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$counter = 0;
									$lastmaterial = '';
									if ($item) {
										foreach ($item as $row) {
											$counter++;?>
											<tr>
												<td style="padding: 1px 8px;"  class="text-center">
													<?= $counter;?>
												</td>
											<?php if ($lastmaterial == $row->i_material){ ?>
												<td style="padding: 1px 8px;" >
													<input style="width:100px;" type="hidden" class="form-control" id="imaterial<?=$counter;?>" name="imaterial<?=$counter;?>"value="<?= $row->i_material; ?>" readonly >
												</td>
											<?}else{?>
												<td style="padding: 1px 8px;" >
													<?= $row->i_material;?>
												</td>
											<?}?>
												<td style="padding: 1px 8px;" >
													<?= $row->i_material2;?>
												</td>
												<td style="padding: 1px 8px;" >
													<?= $row->e_material_name;?>
												</td>
												<td style="padding: 1px 8px;"  class="text-right">
													<?= $row->n_pemenuhan2;?>
												</td>
												<td style="padding: 1px 8px;"  class="text-center">
													pcs
												</td>
												<?php 
				                                	$lastmaterial = $row->i_material;
				                                } ?>
											</td>
										</tr>
									<?php }?>
							</tbody>
						</table>
					</div>
					<div class="noDisplay">
						<div class="text-center">
							<button id="print" class="btn btn-info btn-outline" onclick="window.print();" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>