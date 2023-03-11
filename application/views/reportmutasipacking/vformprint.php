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
								<th rowspan="2" colspan="3"></th>
								<th class="table-active middle table-success text-center" colspan="4">MASUK</th>
								<th class="table-active middle table-danger text-center" colspan="5">KELUAR</th>
								<th rowspan="2" colspan="9"></th>								
							</tr>
							<tr>
								<th class="text-center">Dari WIP</th>
								<th class="text-center">Dari Pengisian</th>
								<th class="text-center">Repair</th>
								<th class="text-center table-success middle" rowspan="3">Total<br>Terima</th>
								<th class="text-center">ke GUDANG JADI</th>
								<th class="text-center">ke WIP</th>
								<th class="text-center">Ke Pengisan</th>
								<th class="text-center">Repair</th>
								<th class="text-center table-danger middle" rowspan="3">Total<br>Kirim</th>
							</tr>
							<tr>
								<th class="text-center">A1</th>
								<th class="text-center">A2</th>
								<th class="text-center">A3</th>
								<th class="text-center">G</th>
								<th class="text-center">Makloon</th>
								<th class="text-center">R</th>
								<th class="text-center">K</th>
								<th class="text-center">XX</th>
								<th class="text-center">Makloon</th>
								<th class="text-center">R</th>
								<th class="text-center">J1</th>
								<th class="text-center">J2</th>
								<th class="text-center">J3</th>
								<th class="text-center">K1</th>
								<th class="text-center">K2</th>
								<th class="text-center">K3</th>
								<th class="text-center">L1</th>
								<th class="text-center">L2</th>
								<th class="text-center">L3</th>
							</tr>
							<tr>
								<th class="text-center table-warning middle" rowspan="1">Saldo<br>Awal (Bagus)</th>
								<th class="text-center table-warning middle" rowspan="1">Saldo<br>Awal (Repair)</th>
								<th class="text-center table-warning middle" rowspan="1">Total<br>Saldo Awal</th>
								<!-- <th class="table-active text-center">#</th> -->

								<th class="text-center table-info">Terima untuk<br>di packing<br>(BARANG BAGUS)</th>
								<th class="text-center table-info">Terima<br>Proses Pengisian</th>
								<th class="text-center table-info">Konversi<br>bagus ke repair</th>
								<th class="text-center table-info">Kirim hasil<br>packing</th>
								<th class="text-center table-info">Kirim repair<br>(UNTUK DIPERBAIKI)</th>
								<th class="text-center table-info">Kirim untuk<br>proses pengisian</th>
								<th class="text-center table-info">Konversi<br>bagus ke repair</th>

								<th class="text-center table-warning middle" rowspan="1">Saldo<br>Akhir (Bagus)</th>
								<th class="text-center table-warning middle" rowspan="1">Saldo<br>Akhir (Repair)</th>
								<th class="text-center table-warning middle" rowspan="1">Total<br>Saldo Akhir</th>

								<th class="text-center table-active middle" rowspan="1">SO<br>(Bagus)</th>
								<th class="text-center table-active middle" rowspan="1">SO<br>(Repair)</th>
								<th class="text-center table-active middle" rowspan="1">Total<br>SO</th>

								<th class="text-center table-danger middle" rowspan="1">Selisih<br>(Bagus)</th>
								<th class="text-center table-danger middle" rowspan="1">Selisih<br>(Repair)</th>
								<th class="text-center table-danger middle" rowspan="1">Total<br>Selisih</th>
							</tr>
							<?php
							$n_saldo_awal_bagus = 0;
							$n_saldo_awal_repair = 0;
							$n_saldo_awal_total = 0;
										
							$n_masuk_1 = 0; /** dari WIP */
							$n_masuk_2 = 0; /** dari pengisian */
							$n_masuk_3 = 0; /** dari konversi bagus ke repair */							
							$n_masuk_total = 0;

							$n_keluar_1 = 0; /** ke gudang jadi */
							$n_keluar_2 = 0; /** ke WIP */
							$n_keluar_3 = 0; /** ke Pengisian */
							$n_keluar_4 = 0; /** dari konversi bagus ke repair */							
							$n_keluar_total = 0;

							$n_saldo_akhir_bagus = 0;
							$n_saldo_akhir_repair = 0;
							$n_saldo_akhir_total = 0;

							$n_so_bagus = 0;
							$n_so_repair = 0;
							$n_so_total = 0;

							$n_selisih_bagus = 0;
							$n_selisih_repair = 0;
							$n_selisih_total = 0;

							if ($data->num_rows() > 0) {
								foreach ($data->result() as $row) {
									$n_saldo_awal_bagus += $row->n_saldo_awal;
									$n_masuk_1 += $row->n_masuk_1;
									$n_masuk_2 += $row->n_masuk_2;
									$n_masuk_total += $row->n_masuk_total;
									$n_keluar_1 += $row->n_keluar_1;
									$n_keluar_2 += $row->n_keluar_2;
									$n_keluar_3 += $row->n_keluar_3;
									$n_keluar_total += $row->n_keluar_total;
									// $n_saldo_akhir += $row->n_saldo_akhir;
									// $n_so += $row->n_so;
									// $n_selisih += $row->n_selisih;
								}
							} ?>
							<tr>
								<td class="text-right bold"><?= $n_saldo_awal_bagus ?></td>
								<td class="text-right bold"><?= $n_saldo_awal_repair ?></td>
								<td class="text-right bold"><?= $n_saldo_awal_total ?></td>

								<td class="text-right bold"><?= $n_masuk_1 ?></td>
								<td class="text-right bold"><?= $n_masuk_2 ?></td>
								<td class="text-right bold"><?= $n_masuk_3 ?></td>
								<td class="text-right bold"><?= $n_masuk_total ?></td>

								<td class="text-right bold"><?= $n_keluar_1 ?></td>
								<td class="text-right bold"><?= $n_keluar_2 ?></td>
								<td class="text-right bold"><?= $n_keluar_3 ?></td>
								<td class="text-right bold"><?= $n_keluar_4 ?></td>
								<td class="text-right bold"><?= $n_keluar_total ?></td>

								<td class="text-right bold"><?= $n_saldo_akhir_bagus ?></td>
								<td class="text-right bold"><?= $n_saldo_akhir_repair ?></td>
								<td class="text-right bold"><?= $n_saldo_akhir_total ?></td>

								<td class="text-right bold"><?= $n_so_bagus ?></td>
								<td class="text-right bold"><?= $n_so_repair ?></td>
								<td class="text-right bold"><?= $n_so_total ?></td>

								<td class="text-right bold"><?= $n_selisih_bagus ?></td>
								<td class="text-right bold"><?= $n_selisih_repair ?></td>
								<td class="text-right bold"><?= $n_selisih_total ?></td>
							</tr>
						</thead>
						<tbody>
						<?php $i = 0;foreach ($data->result() as $key) { $i++; ?>
							<tr>
								<td class="text-center"><?= $i; ?></td>
								<td><?= $key->i_product_wip; ?></td>
								<td><?= wordwrap($key->e_product_basename, 30, "<br>\n"); ?></td>
								<td><?= wordwrap($key->e_color_name, 15, "<br>\n"); ?></td>
								<td><?= $key->e_class_name; ?></td>

								<td class="text-right <?= warna($key->n_saldo_awal); ?>"><?= $key->n_saldo_awal; ?></td>
								<td class="text-right <?= warna($key->n_saldo_awal_repair); ?>"><?= $key->n_saldo_awal_repair; ?></td>
								<?php $total_saldo_awal = $key->n_saldo_awal + $key->n_saldo_awal_repair; ?>
								<td class="text-right <?= warna($total_saldo_awal); ?>"><?= $total_saldo_awal; ?></td>

								<td class="text-right <?= warna($key->n_masuk_1); ?>"><?= $key->n_masuk_1; ?></td>
								<td class="text-right <?= warna($key->n_masuk_2); ?>"><?= $key->n_masuk_2; ?></td>
								<td class="text-right <?= warna($key->n_masuk_3); ?>"><?= $key->n_masuk_3; ?></td>
								<?php $total_terima = $key->n_masuk_1 + $key->n_masuk_2 + $key->n_masuk_3; ?>
								<td class="text-right <?= warna($total_terima); ?>"><?= $total_terima; ?></td>

								<td class="text-right <?= warna($key->n_keluar_1); ?>"><?= $key->n_keluar_1; ?></td>
								<td class="text-right <?= warna($key->n_keluar_2); ?>"><?= $key->n_keluar_2; ?></td>
								<td class="text-right <?= warna($key->n_keluar_3); ?>"><?= $key->n_keluar_3; ?></td>
								<?php /** konversi dari bagus ke repair tetep dihitung sebagai pengiriman keluar */ ?>
								<td class="text-right <?= warna($key->n_keluar_4) ?>"><?= $key->n_keluar_4 ?></td>
								<?php $total_kirim = $key->n_keluar_1 + $key->n_keluar_2 + $key->n_keluar_3 + $key->n_keluar_4 ?>
								<td class="text-right <?= warna($total_kirim); ?>"><?= $total_kirim?></td>
								
								<?php /** saldo akhir bagus 
								 * 
								 * (A1 + G + Makloon) - (K + Makloon + R Keluar) 
								 * 
								 * */
									$saldo_akhir_bagus = ($key->n_saldo_awal + $key->n_masuk_1 + $key->n_masuk_2) 
										- ($key->n_keluar_1 + $key->n_keluar_3 + $key->n_keluar_4);
								?>
								<td class="text-right <?= warna($saldo_akhir_bagus); ?>"><?= $saldo_akhir_bagus; ?></td>

								<?php /** saldo akhir repair 
								 * 
								 * (A2 + R Masuk) - XX
								 * 
								 * */ 
									$saldo_akhir_repair = ($key->n_saldo_awal_repair + $key->n_masuk_3) - $key->n_keluar_2;
								?>
								<td class="text-right <?= warna($saldo_akhir_repair); ?>"><?= $saldo_akhir_repair; ?></td>

								<?php /** saldo akhir total (bagus + repair) */ 
									$saldo_akhir_total = $saldo_akhir_bagus + $saldo_akhir_repair;
								?>
								<td class="text-right <?= warna($saldo_akhir_total); ?>"><?= $saldo_akhir_total; ?></td>
								
								<td class="text-right <?= warna($key->n_so); ?>"><?= $key->n_so; ?></td>
								<td class="text-right <?= warna($key->n_so_repair); ?>"><?= $key->n_so_repair; ?></td>
								<?php /** total SO */ 
									$total_so = $key->n_so + $key->n_so_repair;
								?>
								<td class="text-right <?= warna($total_so); ?>"><?= $total_so; ?></td>

								<?php /**  selisih bagus */ 
									$selisih_bagus = $saldo_akhir_bagus - $key->n_so;
								?>
								<td class="text-right <?= warna($selisih_bagus); ?>"><?= $selisih_bagus; ?></td>

								<?php /**  selisih repair */ 
									$selisih_repair = $saldo_akhir_repair - $key->n_so_repair;
								?>
								<td class="text-right <?= warna($selisih_repair); ?>"><?= $selisih_repair; ?></td>

								<?php /**  selisih total */ 
									$selisih_total = abs($selisih_bagus) + abs($selisih_repair);
								?>
								<td class="text-right <?= warna($selisih_total); ?>"><?= $selisih_total; ?></td>
							</tr>
						<?php }?>
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