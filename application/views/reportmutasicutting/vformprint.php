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
                    <span>Perusahaan : <?= $company; ?></span><br>
                </div>
                <div class="panel-body">
                    <!-- <div class="table-responsive"> -->
                    <table id="tabledata" class="stripe row-border order-column table color-table inverse-table table-bordered class" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-active text-center middle" rowspan="5">#</th>
                                <th class="table-active text-center middle" rowspan="5">Kode<br>Barang</th>
                                <th class="table-active text-center middle" rowspan="5">Nama<br>Barang</th>
                                <th class="table-active text-center middle" rowspan="5">Satuan<br>Pemakaian</th>
                                <th class="text-center" rowspan="2"></th>
                                <th class="table-active middle table-success text-center" colspan="3">MASUK</th>
                                <th class="table-active middle table-danger text-center" colspan="3">KELUAR</th>
                                <th class="text-center" colspan="3" rowspan="3"></th>
                            </tr>
                            <tr>
                                <th class="text-center">Bagus</th>
                                <th class="text-center">Memo BB</th>
                                <th class="text-center table-success"></th>
                                <th class="text-center">Bagus</th>
                                <th class="text-center">Retur</th>
                                <th class="text-center table-success"></th>
                            </tr>
                            <tr>
                                <th class="text-center table-warning"></th>
                                <th class="text-center">X</th>
                                <th class="text-center">Y</th>
                                <th class="text-center table-success">X + Y</th>
                                <th class="text-center">A</th>
                                <th class="text-center">B</th>
                                <th class="text-center table-success">A + B</th>
                            </tr>
                            <tr>
                                <th class="table-warning text-center">Saldo Awal</th>
                                <th class="text-center table-info">Gudang BB</th>
                                <th class="text-center table-info">Memo BB</th>
                                <th class="text-center table-success">Total Terima<br></th>
                                <th class="text-center table-info">Schedule Cutting</th>
                                <th class="text-center table-info">Retur Gd Material</th>
                                <th class="text-center table-success">Total Kirim<br></th>
                                <th class="table-warning text-center">Saldo Akhir</th>
                                <th class="table-active text-center">SO</th>
                                <th class="table-danger text-center">Selisih</th>
                            </tr>
                            <?php
                            $sum_n_saldo_awal = 0;
                            $sum_n_masuk_1 = 0;
                            $sum_n_masuk_2 = 0;
                            $sum_n_masuk_total = 0;
                            $sum_n_keluar_1 = 0;
                            $sum_n_keluar_2 = 0;
                            $sum_n_keluar_total = 0;
                            $sum_n_saldo_akhir = 0;
                            $sum_n_so = 0;
                            $sum_n_selisih = 0;
                            if ($data->num_rows()>0) {
                                foreach ($data->result() as $key) {
                                    $sum_n_saldo_awal += $key->n_saldo_awal;
                                    $sum_n_masuk_1 += $key->n_masuk_1;
                                    $sum_n_masuk_2 += $key->n_masuk_2;
                                    $sum_n_masuk_total += $key->n_masuk_total;
                                    $sum_n_keluar_1 += $key->n_keluar_1;
                                    $sum_n_keluar_2 += $key->n_keluar_2;
                                    $sum_n_keluar_total += $key->n_keluar_total;
                                    $sum_n_saldo_akhir += $key->n_saldo_akhir; 
                                    $sum_n_so += $key->n_so;
                                    $sum_n_selisih += $key->n_selisih;
                                }
                            }
                            ?>
                            <tr>
                                <td class="bold text-right"><?= number_format($sum_n_saldo_awal,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_masuk_1,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_masuk_2,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_masuk_total,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_keluar_1,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_keluar_2,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_keluar_total,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_saldo_akhir,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_so,2); ?></td>
                                <td class="bold text-right"><?= number_format($sum_n_selisih,2); ?></td>
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
                                        <td><?= $row->i_material; ?></td>
                                        <td><?= $row->e_material_name; ?></td>
                                        <td><?= $row->e_satuan_name; ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_awal); ?>"><?= number_format($row->n_saldo_awal,2); ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_1); ?>"><?= number_format($row->n_masuk_1,2); ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_2); ?>"><?= number_format($row->n_masuk_2,2); ?></td>
                                        <td class="text-right <?= warna($row->n_masuk_total); ?>"><?= number_format($row->n_masuk_total,2); ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_1); ?>"><?= number_format($row->n_keluar_1,2); ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_2); ?>"><?= number_format($row->n_keluar_2,2); ?></td>
                                        <td class="text-right <?= warna($row->n_keluar_total); ?>"><?= number_format($row->n_keluar_total,2); ?></td>
                                        <td class="text-right <?= warna($row->n_saldo_akhir); ?>"><?= number_format($row->n_saldo_akhir,2); ?></td>
                                        <td class="text-right <?= warna($row->n_so); ?>"><?= number_format($row->n_so,2); ?></td>
                                        <td class="text-right <?= warna($row->n_selisih); ?>"><?= number_format($row->n_selisih,2); ?></td>
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
                left: 4
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
                        filename: "Report_Mutasi_Gudang_jadi", //do not include extension
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