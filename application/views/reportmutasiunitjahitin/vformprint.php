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
                                <!-- <th class="table-active text-center middle" rowspan="5">Brand</th> -->
                                <th class="table-active text-center middle" rowspan="5">Unit Jahit</th>
                                <th class="text-center" colspan="3" rowspan="2"></th>
                                <th class="table-active middle table-success text-center" colspan="5">MASUK</th>
                                <th class="table-active middle table-danger text-center" colspan="4">KELUAR</th>
                                <th class="text-center" colspan="9" rowspan="2"></th>
                            </tr>
                            <tr>
                                <th class="text-center">Dari Pengadaan</th>
                                <th class="text-center">Dari Pengadaan</th>
                                <th class="text-center">Dari WIP</th>
                                <th class="text-center">Dari WIP</th>
                                <th class="text-center table-success"></th>
                                <th class="text-center">Ke WIP</th>
                                <th class="text-center">Ke WIP</th>
                                <th class="text-center">Ke Pengadaan</th>
                                <th class="text-center table-success"></th>
                            </tr>
                            <tr>
                                <th class="text-center table-warning">E1</th>
                                <th class="text-center table-warning">E2</th>
                                <th class="text-center table-warning">E</th>
                                <th class="text-center">C1</th>
                                <th class="text-center">C3</th>
                                <th class="text-center">X</th>
                                <th class="text-center">S</th>
                                <th class="text-center table-success">C1+C3+X+S</th>
                                <th class="text-center">D</th>
                                <th class="text-center">Y</th>
                                <th class="text-center">C2</th>
                                <th class="text-center table-success">D+Y+C2</th>
                                <th class="text-center table-warning">F1=[E1+C1+C3+S-D-C2]</th>
                                <th class="text-center table-warning">F2=[E2+X-Y]</th>
                                <th class="text-center table-warning">F=F1+F2</th>
                                <th class="text-center" colspan="6"></th>
                            </tr>
                            <tr>
                                <th class="table-warning text-center">Saldo Awal<br>Bagus</th>
                                <th class="table-warning text-center">Saldo Awal<br>Repair</th>
                                <th class="table-warning text-center">Total<br>Saldo Awal</th>
                                <th class="text-center table-info">Terima<br>BB</th>
                                <th class="text-center table-info">Terima Retur BB<br>(SUDAH DILENGKAPI)</th>
                                <th class="text-center table-info">Terima Repair<br>(UNTUK DIPERBAIKI)</th>
                                <th class="text-center table-info">Terima Sample</th>
                                <th class="text-center table-success">Total<br>Terima</th>
                                <th class="text-center table-info">Kirim<br>BJ</th>
                                <th class="text-center table-info">Kirim Repair<br>(HASIL PERBAIKAN)</th>
                                <th class="text-center table-info">Kirim Retur BB<br>(UNTUK DILENGKAPI)</th>
                                <th class="text-center table-success">Total<br>Kirim</th>
                                <th class="table-warning text-center">Saldo Akhir<br>Bagus</th>
                                <th class="table-warning text-center">Saldo Akhir<br>Repair</th>
                                <th class="table-warning text-center">Total<br>Saldo Akhir</th>
                                <th class="table-active text-center">SO<br>Bagus</th>
                                <th class="table-active text-center">SO<br>Repair</th>
                                <th class="table-active text-center">Total<br>SO</th>
                                <th class="table-danger text-center">Selisih<br>Bagus</th>
                                <th class="table-danger text-center">Selisih<br>Repair</th>
                                <th class="table-danger text-center">Total<br>Selisih</th>
                            </tr>
                            <?php
                            $sum_saldo_awalb = 0;
                            $sum_saldo_awalr = 0;
                            $sum_bb_in = 0;
                            $sum_bb_out = 0;
                            $sum_retur_in = 0;
                            $sum_retur_out = 0;
                            $sum_reject_in = 0;
                            $sum_reject_out = 0;
                            $sum_saldo_akhirb = 0;
                            $sum_saldo_akhirr = 0;
                            $sum_sob = 0;
                            $sum_sor = 0;
                            $sum_selisihb = 0;
                            $sum_selisihr = 0;
                            $sum_sample = 0;
                            if ($data->num_rows() > 0) {
                                foreach ($data->result() as $key) {
                                    $sum_saldo_awalb += $key->saldoawal;
                                    $sum_saldo_awalr += $key->saldoawal_repair;
                                    $sum_bb_in += $key->m_masuk;
                                    $sum_bb_out += $key->k_keluar;
                                    $sum_retur_in += $key->m_retur;
                                    $sum_retur_out += $key->k_retur;
                                    $sum_reject_in += $key->m_tolakan;
                                    $sum_reject_out += $key->k_reject;
                                    $sum_saldo_akhirb += $key->saldo_akhir;
                                    $sum_saldo_akhirr += $key->saldo_akhir_repair;
                                    $sum_sob += $key->so;
                                    $sum_sor += $key->so_repair;
                                    $sum_selisihb += $key->selisih;
                                    $sum_selisihr += $key->selisih_repair;
                                    $sum_sample += $key->m_sample;
                                }
                            } ?>
                            <tr>
                                <th class="bold text-right"><?= $sum_saldo_awalb; ?></th>
                                <th class="bold text-right"><?= $sum_saldo_awalr; ?></th>
                                <th class="bold text-right"><?= $sum_saldo_awalb + $sum_saldo_awalr; ?></th>
                                <th class="bold text-right"><?= $sum_bb_in; ?></th>
                                <th class="bold text-right"><?= $sum_reject_in; ?></th>
                                <th class="bold text-right"><?= $sum_retur_in; ?></th>
                                <th class="bold text-right"><?= $sum_sample; ?></th>
                                <th class="bold text-right"><?= $sum_bb_in + $sum_reject_in + $sum_retur_in + $sum_sample; ?></th>
                                <th class="bold text-right"><?= $sum_bb_out; ?></th>
                                <th class="bold text-right"><?= $sum_retur_out; ?></th>
                                <th class="bold text-right"><?= $sum_reject_out; ?></th>
                                <th class="bold text-right"><?= $sum_bb_out + $sum_retur_out + $sum_reject_out; ?></th>
                                <th class="bold text-right"><?= $sum_saldo_akhirb; ?></th>
                                <th class="bold text-right"><?= $sum_saldo_akhirr; ?></th>
                                <th class="bold text-right"><?= $sum_saldo_akhirb + $sum_saldo_akhirr; ?></th>
                                <th class="bold text-right"><?= $sum_sob; ?></th>
                                <th class="bold text-right"><?= $sum_sor; ?></th>
                                <th class="bold text-right"><?= $sum_sob + $sum_sor; ?></th>
                                <th class="bold text-right"><?= $sum_selisihb; ?></th>
                                <th class="bold text-right"><?= $sum_selisihr; ?></th>
                                <th class="bold text-right"><?= $sum_selisihb + $sum_selisihr; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            if ($data->num_rows() > 0) {
                                foreach ($data->result() as $row) {

                                    // DEBUG
                                    if ($row->i_product_wip != 'DGA4205') continue;
                                    // DEBUG

                                    $i++; ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td><?= $row->i_product_wip; ?></td>
                                        <td><?= wordwrap(trim($row->e_product_basename), 30, "<br>\n"); ?></td>
                                        <td><?= wordwrap(trim($row->e_color_name), 15, "<br>\n"); ?></td>
                                        <td><?= $row->e_class_name; ?></td>
                                        <!-- <td><?= trim($row->e_brand_name); ?></td> -->
                                        <td><?= $row->e_bagian_name; ?></td>
                                        <td class="text-right <?= warna($row->saldoawal); ?>"><?= $row->saldoawal; ?></td>
                                        <td class="text-right <?= warna($row->saldoawal_repair); ?>"><?= $row->saldoawal_repair; ?></td>
                                        <td class="text-right <?= warna($row->saldoawal + $row->saldoawal_repair); ?>"><?= $row->saldoawal + $row->saldoawal_repair; ?></td>
                                        <td class="text-right <?= warna($row->m_masuk); ?>"><?= $row->m_masuk; ?></td>
                                        <td class="text-right <?= warna($row->m_tolakan); ?>"><?= $row->m_tolakan; ?></td>
                                        <td class="text-right <?= warna($row->m_retur); ?>"><?= $row->m_retur; ?></td>
                                        <td class="text-right <?= warna($row->m_sample); ?>"><?= $row->m_sample; ?></td>
                                        <td class="text-right <?= warna($row->m_masuk + $row->m_retur + $row->m_tolakan + $row->m_sample); ?>"><?= $row->m_masuk + $row->m_retur + $row->m_tolakan + $row->m_sample; ?></td>
                                        <td class="text-right <?= warna($row->k_keluar); ?>"><?= $row->k_keluar; ?></td>
                                        <td class="text-right <?= warna($row->k_retur); ?>"><?= $row->k_retur; ?></td>
                                        <td class="text-right <?= warna($row->k_reject); ?>"><?= $row->k_reject; ?></td>
                                        <td class="text-right <?= warna($row->k_retur + $row->k_keluar + $row->k_reject); ?>"><?= $row->k_retur + $row->k_keluar + $row->k_reject; ?></td>
                                        <td class="text-right <?= warna($row->saldo_akhir); ?>"><?= $row->saldo_akhir; ?></td>
                                        <td class="text-right <?= warna($row->saldo_akhir_repair); ?>"><?= $row->saldo_akhir_repair; ?></td>
                                        <td class="text-right <?= warna($row->saldo_akhir + $row->saldo_akhir_repair); ?>"><?= $row->saldo_akhir + $row->saldo_akhir_repair; ?></td>
                                        <td class="text-right <?= warna($row->so); ?>"><?= $row->so; ?></td>
                                        <td class="text-right <?= warna($row->so_repair); ?>"><?= $row->so_repair; ?></td>
                                        <td class="text-right <?= warna($row->so + $row->so_repair); ?>"><?= $row->so + $row->so_repair; ?></td>
                                        <td class="text-right <?= warna($row->selisih); ?>"><?= $row->selisih; ?></td>
                                        <td class="text-right <?= warna($row->selisih_repair); ?>"><?= $row->selisih_repair; ?></td>
                                        <td class="text-right <?= warna($row->selisih + $row->selisih_repair); ?>"><?= $row->selisih + $row->selisih_repair; ?></td>
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
                left: 6
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
                        filename: "Report_Mutasi_Jahit", //do not include extension
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