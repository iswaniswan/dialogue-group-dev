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
                <h1>Laporan Penerimaan Bahan Belum Realisasi</h1>
            </header>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <?php $e_bagian = (@$bagian->e_bagian_name != null) ? $bagian->e_bagian_name: "SEMUA"; ?>
                    <span>Nama Bagian : <?= $e_bagian; ?></span><br>
                    <span>Tanggal Mutasi : <?= format_bulan($dfrom) . ' s/d ' . format_bulan($dto); ?></span><br>                    
                </div>
                <div class="panel-body">
                    <!-- <div class="table-responsive"> -->
                    <table id="tabledata" class="stripe row-border order-column table color-table inverse-table table-bordered class" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-active text-center" rowspan="2">#</th>
                                <th class="table-active text-center" rowspan="2">Kode</th>
                                <th class="table-active text-center" rowspan="2">Nama Barang</th>
                                <th class="table-active text-center" rowspan="2">Satuan</th>
                                <th class="table-active text-center" colspan="3">Quantity</th>
                                <th class="table-active text-center" rowspan="2">Pengirim</th>
                            </tr>
                            <tr>
                                <th class="table-active table-warning text-center" style="background-color: #fcf8e3 !important; width:150px;">Kirim
                                    <p style="font-weight: 400 !important;">(Pabrikan ke Cutting)</p>
                                </th>
                                <th class="table-active table-success text-center" style="width:150px;">Terima
                                    <p style="font-weight: 400 !important;">(Belum realisasi Cutting)</p>
                                </th>
                                <th class="table-active table-danger text-center" style="width:100px;">Selisih
                                    <p style="font-weight: 400 !important;"></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; foreach ($data->result() as $data) { ?>
                            <tr>
                                <td><?= $counter ?></td>
                                <td><?= $data->i_material ?></td>
                                <td><?= $data->e_material_name ?></td>
                                <td><?= $data->e_satuan_name ?></td>
                                <td><?= number_format($data->qty_kirim, 2, ".", ",") ?></td>
                                <td><?= number_format($data->qty_terima, 2, ".", ",") ?></td>
                                <?php $selisih = floatval($data->qty_kirim) - floatval($data->qty_terima) ?>
                                <?php $danger = ($selisih > 0) ? 'table-danger' : ''; ?>
                                <td class="<?= $danger ?>"><?= number_format($selisih, 2, ".", ",") ?></td>
                                <td><?= $data->e_company_name ?></td>
                            </tr>
                        <?php $counter++; } ?>
                        </tbody>
                    </table>
                    <!-- </div> -->
                </div>
                <div class="panel-footer">
                    <p class="font-italic">*Selisih adalah total quantity kirim dikurangi total quantity terima.</p>
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