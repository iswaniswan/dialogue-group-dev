<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?= $this->global['title']; ?></title>

	<!-- Bootstrap Core CSS -->
	<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/css/mutasi.css" id="theme" rel="stylesheet">
	<!-- color CSS -->
	<link href="<?= base_url(); ?>assets/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/css/fixedColumns.dataTables.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/css/buttons.dataTables.min.css" rel="stylesheet">
</head>
<body>
	<div class="row">
		<div class="col-lg-12">
			<header>
				<h1>Laporan Mutasi</h1>
			</header>
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php if ($i_bagian != '') {
						$bagian = $bagian->i_bagian . ' - ' . $bagian->e_bagian_name;
					} else {
						$bagian = "SEMUA";
					} ?>
					<span>Nama Bagian : <?= $bagian; ?></span><br>
					<span>Tanggal Mutasi : <?= format_bulan($dfrom) . ' s/d ' . format_bulan($dto); ?></span><br>
					<?php
					if (!empty($kategori->e_nama_kelompok)) {
						$e_nama_kelompok = $kategori->e_nama_kelompok;
					} else {
						$e_nama_kelompok = "SEMUA KATEGORI";
					} ?>
					<span>Kategori Barang : <?= $e_nama_kelompok; ?></span><br>
					<?php
					if (!empty($jenis->e_type_name)) {
						$e_type_name = $jenis->e_type_name;
					} else {
						$e_type_name = "SEMUA SUB KATEGORI";
					} ?>
					<span>Sub Kategori Barang : <?= $e_type_name; ?></span><br>
				</div>
				<div class="panel-body">
					<!-- <div class="table-responsive"> -->
					<table id="tabledata" class="stripe row-border order-column table color-table inverse-table table-bordered class" style="width:100%">
						<thead>
							<tr>
								<th class="table-active text-center middle" rowspan="5">#</th>
								<th class="table-active text-center middle" rowspan="5">Kode</th>
								<th class="table-active text-center middle" rowspan="5">Nama Barang</th>
								<th class="table-active text-center middle" rowspan="5">Warna</th>
								<th class="table-active text-center middle" rowspan="5">Kategori<br>Penjualan</th>
								<th class="text-center table-warning middle" rowspan="4">Saldo<br>Awal</th>
								<th class="table-active middle table-success text-center" colspan="3">MASUK</th>
								<th class="table-active middle table-danger text-center" colspan="4">KELUAR</th>
								<th class="text-center table-warning middle" rowspan="4">Saldo<br>Akhir</th>
								<th class="text-center table-active middle" rowspan="4">SO</th>
								<th class="text-center table-danger middle" rowspan="4">Selisih</th>
							</tr>
							<tr>
								<th class="text-center">Dari WIP</th>
								<th class="text-center">Dari Pengisian</th>
								<th class="text-center table-success middle" rowspan="3">Total<br>Terima</th>
								<th class="text-center">ke GUDANG JADI</th>
								<th class="text-center">ke WIP</th>
								<th class="text-center">Ke Pengisan</th>
								<th class="text-center table-success middle" rowspan="3">Total<br>Kirim</th>
							</tr>
							<tr>
								<th class="text-center">G</th>
								<th class="text-center">Makloon</th>
								<th class="text-center">K</th>
								<th class="text-center">XX</th>
								<th class="text-center">Makloon</th>
							</tr>
							<tr>
								<!-- <th class="table-active text-center">#</th> -->
								<th class="text-center table-info">Terima untuk<br>di packing<br>(BARANG BAGUS)</th>
								<th class="text-center table-info">Terima<br>Proses Pengisian</th>
								<th class="text-center table-info">Kirim hasil<br>packing</th>
								<th class="text-center table-info">Kirim repair<br>(UNTUK DIPERBAIKI)</th>
								<th class="text-center table-info">Kirim untuk<br>proses pengisian</th>
							</tr>
							<?php
							$n_saldo_awal_total = 0;
							$n_masuk_1 = 0;
							$n_masuk_2 = 0;
							$n_masuk_total = 0;
							$n_keluar_1 = 0;
							$n_keluar_2 = 0;
							$n_keluar_3 = 0;
							$n_keluar_total = 0;
							$n_saldo_akhir = 0;
							$n_so = 0;
							$n_selisih = 0;
							if ($data->num_rows() > 0) {
								foreach ($data->result() as $row) {
									$n_saldo_awal_total += $row->n_saldo_awal;
									$n_masuk_1 += $row->n_masuk_1;
									$n_masuk_2 += $row->n_masuk_2;
									$n_masuk_total += $row->n_masuk_total;
									$n_keluar_1 += $row->n_keluar_1;
									$n_keluar_2 += $row->n_keluar_2;
									$n_keluar_3 += $row->n_keluar_3;
									$n_keluar_total += $row->n_keluar_total;
									$n_saldo_akhir += $row->n_saldo_akhir;
									$n_so += $row->n_so;
									$n_selisih += $row->n_selisih;
								}
							} ?>
							<tr>
								<td class="text-right bold"><?= $n_saldo_awal_total; ?></td>
								<td class="text-right bold"><?= $n_masuk_1; ?></td>
								<td class="text-right bold"><?= $n_masuk_2; ?></td>
								<td class="text-right bold"><?= $n_masuk_total; ?></td>
								<td class="text-right bold"><?= $n_keluar_1; ?></td>
								<td class="text-right bold"><?= $n_keluar_2; ?></td>
								<td class="text-right bold"><?= $n_keluar_3; ?></td>
								<td class="text-right bold"><?= $n_keluar_total; ?></td>
								<td class="text-right bold"><?= $n_saldo_akhir; ?></td>
								<td class="text-right bold"><?= $n_so; ?></td>
								<td class="text-right bold"><?= $n_selisih; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 0;
							if ($data->num_rows() > 0) {
								foreach ($data->result() as $key) {
									$i++; ?>
									<tr>
										<td class="text-center"><?= $i; ?></td>
										<td><?= $key->i_product_wip; ?></td>
										<td><?= wordwrap($key->e_product_basename, 30, "<br>\n"); ?></td>
										<td><?= wordwrap($key->e_color_name, 15, "<br>\n"); ?></td>
										<td><?= $key->e_class_name; ?></td>
										<td class="text-right <?= warna($key->n_saldo_awal); ?>"><?= $key->n_saldo_awal; ?></td>
										<td class="text-right <?= warna($key->n_masuk_1); ?>"><?= $key->n_masuk_1; ?></td>
										<td class="text-right <?= warna($key->n_masuk_2); ?>"><?= $key->n_masuk_2; ?></td>
										<td class="text-right <?= warna($key->n_masuk_total); ?>"><?= $key->n_masuk_total; ?></td>
										<td class="text-right <?= warna($key->n_keluar_1); ?>"><?= $key->n_keluar_1; ?></td>
										<td class="text-right <?= warna($key->n_keluar_2); ?>"><?= $key->n_keluar_2; ?></td>
										<td class="text-right <?= warna($key->n_keluar_3); ?>"><?= $key->n_keluar_3; ?></td>
										<td class="text-right <?= warna($key->n_keluar_total); ?>"><?= $key->n_keluar_total; ?></td>
										<td class="text-right <?= warna($key->n_saldo_akhir); ?>"><?= $key->n_saldo_akhir; ?></td>
										<td class="text-right <?= warna($key->n_so); ?>"><?= $key->n_so; ?></td>
										<td class="text-right <?= warna($key->n_selisih); ?>"><?= $key->n_selisih; ?></td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>
	</div>
</body>
<script src="<?= base_url(); ?>assets/js/jquery-3.5.1.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/js/dataTables.fixedColumns.min.js"></script>
<script src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.table2excel.js"></script>
<script>
	$(document).ready(function() {
		var table = $('#tabledata').DataTable({
			scrollY: "400px",
			scrollX: true,
			scrollCollapse: true,
			paging: false,
			fixedColumns: {
				left: 5
			},
			dom: 'Bfrtip',
			/* columnDefs: [{
				"targets": [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], //first column / numbering column
				"orderable": false, //set not orderable
			}, ], */
			buttons: [{
				text: 'Export Data',
				action: function(e, dt, node, config) {
					$("#tabledata").table2excel({
						// exclude CSS class
						// exclude: ".floatThead-col",
						name: "Worksheet Name",
						filename: "Report_Mutasi_Packing", //do not include extension
						fileext: ".xls" // file extension
					});
				}
			}]
		});
		table.columns.adjust().draw();
		$('input[type=search]').attr('class', 'input-sm');
        $('input[type=search]').attr('class', 'mr-4');
		$("input[type=search]").attr("size", "15");
		$("input[type=search]").attr("placeholder", "type to search ...");
		$("input[type=search]").focus();
	});
</script>