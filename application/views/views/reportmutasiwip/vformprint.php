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
                                <th class="text-center" colspan="3" rowspan="2"></th>
                                <th class="table-active middle table-success text-center" colspan="5">MASUK</th>
                                <th class="table-active middle table-danger text-center" colspan="6">KELUAR</th>
                                <th class="text-center" colspan="10" rowspan="2"></th>
                            </tr>
                            <tr>
                                <th class="text-center">Dari UNIT JAHIT</th>
                                <th class="text-center">Dari UNIT JAHIT</th>
                                <th class="text-center">Dari Gudang Jadi</th>
                                <th class="text-center">Dari Packing</th>
                                <th class="text-center table-success"></th>
                                <th class="text-center">ke PACKING</th>
                                <th class="text-center">ke Gudang Jadi</th>
                                <th class="text-center">ke Gudang Jadi</th>
                                <th class="text-center">ke UNIT JAHIT</th>
                                <th class="text-center">ke Lain-lain</th>
                                <th class="text-center table-success"></th>
                            </tr>
                            <tr>
                                <th class="text-center table-warning">H1</th>
                                <th class="text-center table-warning">H2</th>
                                <th class="text-center table-warning">H</th>
                                <th class="text-center">D</th>
                                <th class="text-center">Y</th>
                                <th class="text-center">XXX</th>
                                <th class="text-center">XX</th>
                                <th class="text-center table-success">D+Y+XXX+XX</th>
                                <th class="text-center">G</th>
                                <th class="text-center">GG</th>
                                <th class="text-center">Z2</th>
                                <th class="text-center">X</th>
                                <th class="text-center">S</th>
                                <th class="text-center table-success">G+GG+Z2+X+S</th>
                                <th class="text-center table-warning">H1 + D + Y - G  - GG  - Z2 - S</th>
                                <th class="text-center table-warning">H2 + XXX + XX - X</th>
                                <th class="text-center table-warning">I</th>
                                <th class="text-center" colspan="7"></th>
                            </tr>
                            <tr>
                                <th class="table-warning text-center">Saldo Awal<br>Bagus</th>
                                <th class="table-warning text-center">Saldo Awal<br>Repair</th>
                                <th class="table-warning text-center">Total<br>Saldo Awal</th>
                                <th class="text-center table-info">Terima<br>BJ</th>
                                <th class="text-center table-info">Terima Repair<br>(HASIL PERBAIKAN)</th>
                                <th class="text-center table-info">Terima Repair<br>(UNTUK DIPERBAIKI)</th>
                                <th class="text-center table-info">Terima Repair<br>(UNTUK DIPERBAIKI)</th>
                                <th class="text-center table-success">Total<br>Terima</th>
                                <th class="text-center table-info">Kirim untuk<br>di packing<br>(BARANG BAGUS)</th>
                                <th class="text-center table-info">Kirim pengembalian repair<br>(HASIL PERBAIKAN)</th>
                                <th class="text-center table-info">Kirim Grade B<br>(FINAL)</th>
                                <th class="text-center table-info">Kirim repair<br>(UNTUK DIPERBAIKI)</th>
                                <th class="text-center table-info">Kirim Sample<br>(UNTUK SAMPLE)</th>
                                <th class="text-center table-success">Total<br>Kirim</th>
                                <th class="table-warning text-center">Saldo Akhir<br>Bagus</th>
                                <th class="table-warning text-center">Saldo Akhir<br>Repair</th>
                                <th class="table-warning text-center">Total<br>Saldo Akhir</th>
                                <th class="table-active text-center">SO<br>Bagus</th>
                                <th class="table-active text-center">SO<br>Repair</th>
                                <th class="table-active text-center">SO<br>Tambahan</th>
                                <th class="table-active text-center">Total<br>SO</th>
                                <th class="table-danger text-center">Selisih<br>Bagus</th>
                                <th class="table-danger text-center">Selisih<br>Repair</th>
                                <th class="table-danger text-center">Total<br>Selisih</th>
                            </tr>
                            <?php
                            $sum_n_saldo_awal = 0;
                            $sum_n_saldo_awal_repair = 0;
                            $sum_n_saldo_awal_total = 0;
                            $sum_n_masuk_1 = 0;
                            $sum_n_masuk_2 = 0;
                            $sum_n_masuk_3 = 0;
                            $sum_n_masuk_4 = 0;
                            $sum_n_masuk_total = 0;
                            $sum_n_keluar_1 = 0;
                            $sum_n_keluar_2 = 0;
                            $sum_n_keluar_3 = 0;
                            $sum_n_keluar_4 = 0;
                            $sum_n_keluar_5 = 0;
                            $sum_n_keluar_total = 0;
                            $sum_n_saldo_akhir = 0;
                            $sum_n_saldo_akhir_repair = 0;
                            $sum_n_saldo_akhir_total = 0;
                            $sum_n_so = 0;
                            $sum_n_so_repair = 0;
                            $sum_n_so_tambahan = 0;
                            $sum_n_so_total = 0;
                            $sum_n_selisih = 0;
                            $sum_n_selisih_repair = 0;
                            $sum_n_selisih_total = 0;
                            if ($data->num_rows()>0) {
                                foreach ($data->result() as $key) {
                                    $sum_n_saldo_awal += $key->n_saldo_awal;
                                    $sum_n_saldo_awal_repair += $key->n_saldo_awal_repair;
                                    $sum_n_saldo_awal_total += $key->n_saldo_awal_total;
                                    $sum_n_masuk_1 += $key->n_masuk_1;
                                    $sum_n_masuk_2 += $key->n_masuk_2;
                                    $sum_n_masuk_3 += $key->n_masuk_3;
                                    $sum_n_masuk_4 += $key->n_masuk_4;
                                    $sum_n_masuk_total += $key->n_masuk_total;
                                    $sum_n_keluar_1 += $key->n_keluar_1;
                                    $sum_n_keluar_2 += $key->n_keluar_2;
                                    $sum_n_keluar_3 += $key->n_keluar_3;
                                    $sum_n_keluar_4 += $key->n_keluar_4;
                                    $sum_n_keluar_5 += $key->n_keluar_5;
                                    $sum_n_keluar_total += $key->n_keluar_total;
                                    $sum_n_saldo_akhir += $key->n_saldo_akhir;
                                    $sum_n_saldo_akhir_repair += $key->n_saldo_akhir_repair;
                                    $sum_n_saldo_akhir_total += $key->n_saldo_akhir_total;
                                    $sum_n_so += $key->n_so;
                                    $sum_n_so_repair += $key->n_so_repair;
                                    $sum_n_so_tambahan += $key->n_so_tambahan;
                                    $sum_n_so_total += $key->n_so_total;
                                    $sum_n_selisih += $key->n_selisih;
                                    $sum_n_selisih_repair += $key->n_selisih_repair;
                                    $sum_n_selisih_total += $key->n_selisih_total;
                                }
                            }
                            ?>
                            <tr>
                                <td class="bold text-right"><?= $sum_n_saldo_awal; ?></td>
                                <td class="bold text-right"><?= $sum_n_saldo_awal_repair; ?></td>
                                <td class="bold text-right"><?= $sum_n_saldo_awal_total; ?></td>
                                <td class="bold text-right"><?= $sum_n_masuk_1; ?></td>
                                <td class="bold text-right"><?= $sum_n_masuk_2; ?></td>
                                <td class="bold text-right"><?= $sum_n_masuk_3; ?></td>
                                <td class="bold text-right"><?= $sum_n_masuk_4; ?></td>
                                <td class="bold text-right"><?= $sum_n_masuk_total; ?></td>
                                <td class="bold text-right"><?= $sum_n_keluar_1; ?></td>
                                <td class="bold text-right"><?= $sum_n_keluar_2; ?></td>
                                <td class="bold text-right"><?= $sum_n_keluar_3; ?></td>
                                <td class="bold text-right"><?= $sum_n_keluar_4; ?></td>
                                <td class="bold text-right"><?= $sum_n_keluar_5; ?></td>
                                <td class="bold text-right"><?= $sum_n_keluar_total; ?></td>
                                <td class="bold text-right"><?= $sum_n_saldo_akhir; ?></td>
                                <td class="bold text-right"><?= $sum_n_saldo_akhir_repair; ?></td>
                                <td class="bold text-right"><?= $sum_n_saldo_akhir_total; ?></td>
                                <td class="bold text-right"><?= $sum_n_so; ?></td>
                                <td class="bold text-right"><?= $sum_n_so_repair; ?></td>
                                <td class="bold text-right"><?= $sum_n_so_tambahan; ?></td>
                                <td class="bold text-right"><?= $sum_n_so_total; ?></td>
                                <td class="bold text-right"><?= $sum_n_selisih; ?></td>
                                <td class="bold text-right"><?= $sum_n_selisih_repair; ?></td>
                                <td class="bold text-right"><?= $sum_n_selisih_total; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            if ($data->num_rows() > 0) {
                                foreach ($data->result() as $row) {
                                    $i++; ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td><?= $row->i_product_wip; ?></td>
                                        <td><?= wordwrap(trim($row->e_product_basename), 30, "<br>\n"); ?></td>
                                        <td><?= wordwrap(trim($row->e_color_name), 15, "<br>\n"); ?></td>
                                        <td><?= $row->e_class_name; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_awal); ?>"><?= $row->n_saldo_awal; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_awal_repair); ?>"><?= $row->n_saldo_awal_repair; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_awal_total); ?>"><?= $row->n_saldo_awal_total; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_1); ?>"><?= $row->n_masuk_1; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_2); ?>"><?= $row->n_masuk_2; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_3); ?>"><?= $row->n_masuk_3; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_4); ?>"><?= $row->n_masuk_4; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_total); ?>"><?= $row->n_masuk_total; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_1); ?>"><?= $row->n_keluar_1; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_2); ?>"><?= $row->n_keluar_2; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_3); ?>"><?= $row->n_keluar_3; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_4); ?>"><?= $row->n_keluar_4; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_5); ?>"><?= $row->n_keluar_5; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_total); ?>"><?= $row->n_keluar_total; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_akhir); ?>"><?= $row->n_saldo_akhir; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_akhir_repair); ?>"><?= $row->n_saldo_akhir_repair; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_akhir_total); ?>"><?= $row->n_saldo_akhir_total; ?></td>
                                        <td class="text-right <?= warna($row->n_so); ?>"><?= $row->n_so; ?></td>
                                        <td class="text-right <?= warna($row->n_so_repair); ?>"><?= $row->n_so_repair; ?></td>
                                        <td class="text-right <?= warna($row->n_so_tambahan); ?>"><?= $row->n_so_tambahan; ?></td>
                                        <td class="text-right <?= warna($row->n_so_total); ?>"><?= $row->n_so_total; ?></td>
                                        <td class="text-right <?= warna($row->n_selisih); ?>"><?= $row->n_selisih; ?></td>
                                        <td class="text-right <?= warna($row->n_selisih_repair); ?>"><?= $row->n_selisih_repair; ?></td>
                                        <td class="text-right <?= warna($row->n_selisih_total); ?>"><?= $row->n_selisih_total; ?></td>
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
                        filename: "Report_Mutasi_WIP", //do not include extension
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