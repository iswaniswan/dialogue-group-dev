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
                    <!-- <span>Kategori Barang : <?= $e_nama_kelompok; ?></span><br>
                    <?php
                    if (!empty($jenis->e_type_name)) {
                        $e_type_name = $jenis->e_type_name;
                    } else {
                        $e_type_name = "SEMUA SUB KATEGORI";
                    } ?>
                    <span>Sub Kategori Barang : <?= $e_type_name; ?></span><br> -->
                </div>
                <div class="panel-body">
                    <!-- <div class="table-responsive"> -->
                    <table id="tabledata" class="stripe row-border order-column table color-table inverse-table table-bordered class" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-active text-center middle" rowspan="4">#</th>
                                <th class="table-active text-center middle" rowspan="4">Kode</th>
                                <th class="table-active text-center middle" rowspan="4">Nama Barang</th>
                                <th class="table-active text-center middle" rowspan="4">Warna</th>
                                <th class="table-active text-center middle" rowspan="4">Kategori<br>Penjualan</th>
                                <!-- <th class="table-active text-center middle" rowspan="5">Brand</th> -->
                                <th class="table-active text-center middle" rowspan="4">Kode Panel</th>
                                <th class="table-active text-center middle" rowspan="4">Bagian</th>
                                <th class="table-active text-center middle" rowspan="4">Jenis Kain</th>
                                <th class="table-active text-center middle" rowspan="4">Kebutuhan</th>
                                <th class="text-center" rowspan="2"></th>
                                <th class="table-active middle table-success text-center" colspan="5">MASUK</th>
                                <th class="table-active middle table-danger text-center" colspan="4">KELUAR</th>
                                <th class="text-center" colspan="3" rowspan="2"></th>
                            </tr>
                            <tr>
                                <th class="text-center">Dari Cutting</th>
                                <th class="text-center">Dari Cutting <br> Pihak Pengesetan</th>
                                <th class="text-center">Dari Pengadaan</th>
                                <th class="text-center">Dari Makloon</th>
                                <th class="text-center table-success"></th>
                                <th class="text-center">Ke Pengadaan</th>
                                <th class="text-center">Ke Pengadaan</th>
                                <th class="text-center">Ke Makloon</th>
                                <th class="text-center table-success"></th>
                            </tr>
                            <!-- <tr>
                                <th class="text-center table-warning">E1</th>
                                <th class="text-center table-warning">E2</th>
                                <th class="text-center table-warning">E</th>
                                <th class="text-center">C1</th>
                                <th class="text-center">C3</th>
                                <th class="text-center">X</th>
                                <th class="text-center table-success">C1+C3+X</th>
                                <th class="text-center">Y</th>
                                <th class="text-center">C2</th>
                                <th class="text-center table-success">D+Y+C2</th>
                                <th class="text-center table-warning">F1=[E1+C1+C3-D-C2]</th>
                            </tr> -->
                            <tr>
                                <th class="table-warning text-center">Saldo Awal<br>Bagus</th>
                                <th class="text-center table-info">Terima Semua<br>Hasil Cutting</th>
                                <th class="text-center table-info">Terima BB<br>(UNTUK MELENGKAPI)</th>
                                <th class="text-center table-info">Terima BB<br>(TIDAK LENGKAP)</th>
                                <th class="text-center table-info">Hasil Makloon<br>Masuk</th>
                                <th class="text-center table-success">Total<br>Terima</th>
                                <th class="text-center table-info">Kirim Hasil<br>Set Lengkap<br>(BARU)</th>
                                <th class="text-center table-info">Kirim Hasil<br>Set Lengkap<br>(PENGGANTI)</th>
                                <th class="text-center table-info">Hasil Makloon<br>Keluar</th>
                                <th class="text-center table-success">Total<br>Kirim</th>
                                <th class="table-warning text-center">Saldo Akhir</th>
                                <th class="table-active text-center">SO</th>
                                <th class="table-danger text-center">Selisih</th>
                            </tr>
                            <?php
                            $sum_kebutuhan = 0;
                            $sum_saldo_awalb = 0;
                            $sum_bb_in = 0;
                            $sum_bb_out = 0;
                            $sum_retur_in = 0;
                            $sum_retur_out = 0;
                            $sum_reject_in = 0;
                            $sum_masuk_makloon = 0;
                            $sum_keluar_makloon = 0;
                            $sum_saldo_akhirb = 0;
                            $sum_sob = 0;
                            $sum_selisihb = 0;
                            if ($data2->num_rows() > 0) {
                                foreach ($data2->result() as $key) {
                                    $sum_kebutuhan += $key->n_qty_penyusun;
                                    $sum_saldo_awalb += $key->n_saldo_awal;
                                    $sum_bb_in += $key->n_masuk_cutting_bagus;
                                    $sum_bb_out += $key->n_keluar_cutting_baru;
                                    $sum_retur_in += $key->n_masuk_cutting_repair;
                                    $sum_retur_out += $key->n_keluar_cutting_ganti;
                                    $sum_reject_in += $key->n_masuk_retur_pengadaan;
                                    $sum_masuk_makloon += $key->n_masuk_makloon;
                                    $sum_keluar_makloon += $key->n_keluar_makloon;
                                    $sum_saldo_akhirb += $key->n_saldo_akhir;
                                    $sum_sob += $key->n_so_bagus;
                                    $sum_selisihb += $key->n_saldo_akhir - $key->n_so_bagus;
                                }
                            } ?>
                            <tr>
                                <th class="bold text-right"><?= $sum_saldo_awalb; ?></th>
                                <th class="bold text-right"><?= $sum_bb_in; ?></th>
                                <th class="bold text-right"><?= $sum_retur_in; ?></th>
                                <th class="bold text-right"><?= $sum_reject_in; ?></th>
                                <th class="bold text-right"><?= $sum_masuk_makloon; ?></th>
                                <th class="bold text-right"><?= $sum_bb_in + $sum_reject_in + $sum_retur_in + $sum_masuk_makloon; ?></th>
                                <th class="bold text-right"><?= $sum_bb_out; ?></th>
                                <th class="bold text-right"><?= $sum_retur_out; ?></th>
                                <th class="bold text-right"><?= $sum_keluar_makloon; ?></th>
                                <th class="bold text-right"><?= $sum_bb_out + $sum_retur_out + $sum_keluar_makloon; ?></th>
                                <th class="bold text-right"><?= $sum_saldo_akhirb; ?></th>
                                <th class="bold text-right"><?= $sum_sob; ?></th>
                                <th class="bold text-right"><?= $sum_selisihb; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            if ($data2->num_rows() > 0) {
                                foreach ($data2->result() as $row) {
                                    $i++; ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td><?= $row->i_product_wip; ?></td>
                                        <td><?= wordwrap(trim($row->e_product_basename), 30, "<br>\n"); ?></td>
                                        <td><?= wordwrap(trim($row->e_color_name), 15, "<br>\n"); ?></td>
                                        <td><?= $row->e_class_name; ?></td>
                                        <!-- <td><?= trim($row->e_brand_name); ?></td> -->
                                        <td><?= $row->i_panel; ?></td>
                                        <td><?= $row->bagian; ?></td>
                                        <td><?= $row->e_material_name; ?></td>
                                        <td><?= $row->n_qty_penyusun; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_awal); ?>"><?= $row->n_saldo_awal; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_cutting_bagus); ?>"><?= $row->n_masuk_cutting_bagus; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_cutting_repair); ?>"><?= $row->n_masuk_cutting_repair; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_retur_pengadaan); ?>"><?= $row->n_masuk_retur_pengadaan; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_makloon); ?>"><?= $row->n_masuk_makloon; ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_cutting_bagus + $row->n_masuk_cutting_repair + $row->n_masuk_retur_pengadaan + $row->n_masuk_makloon); ?>"><?= $row->n_masuk_cutting_bagus + $row->n_masuk_cutting_repair + $row->n_masuk_retur_pengadaan + $row->n_masuk_makloon; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_cutting_baru); ?>"><?= $row->n_keluar_cutting_baru; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_cutting_ganti); ?>"><?= $row->n_keluar_cutting_ganti; ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_makloon); ?>"><?= $row->n_keluar_makloon; ?></td>

                                        <td class="text-right <?= warna($row->n_keluar_cutting_baru + $row->n_keluar_cutting_ganti + $row->n_keluar_makloon); ?>"><?= $row->n_keluar_cutting_baru + $row->n_keluar_cutting_ganti + $row->n_keluar_makloon; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_akhir); ?>"><?= $row->n_saldo_akhir; ?></td>
                                        <td class="text-right <?= warna($row->n_so_bagus); ?>"><?= $row->n_so_bagus; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_akhir - $row->n_so_bagus); ?>"><?= $row->n_saldo_akhir - $row->n_so_bagus; ?></td>
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