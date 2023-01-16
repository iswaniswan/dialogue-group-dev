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
                <h1>Laporan Realisasi</h1>
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
                                <th class="table-active text-center middle" rowspan="2">#</th>
                                <th class="table-active text-center middle" colspan="6">Schedule</th>
                                <th class="table-success text-center middle" colspan="6">Realisasi</th>
                            </tr>
                            <tr>
                                <th class="table-active text-center middle">Kode</th>
                                <th class="table-active text-center middle">Nama Barang</th>
                                <th class="table-active text-center middle">Warna</th>
                                <th class="table-active text-center middle">Tanggal</th>
                                <th class="table-active text-center middle">Quantity</th>
                                <th class="table-success text-center middle">Kode</th>
                                <th class="table-success text-center middle">Nama Barang</th>
                                <th class="table-success text-center middle">Warna</th>
                                <th class="table-success text-center middle">Tanggal</th>
                                <th class="table-success text-center middle">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
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
                    console.log("under develepment")
                    return false;
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