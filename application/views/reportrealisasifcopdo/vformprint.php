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
                <h1>Laporan Data Realisasi FC vs OP vs DO</h1>
            </header>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <span>Tanggal Data : <?= format_bulan($dfrom) . ' s/d ' . format_bulan($dto); ?></span><br>
                </div>
                <div class="panel-body">
                    <!-- <div class="table-responsive"> -->
                    <table id="tabledata" class="stripe row-border order-column table color-table inverse-table table-bordered class" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-active text-center middle" rowspan="2">#</th>
                                <th class="table-active text-center middle" rowspan="2">Perusahaan</th>
                                <th class="table-active text-center middle" rowspan="2">Kode</th>
                                <th class="table-active text-center middle" rowspan="2">Nama Barang Jadi</th>
                                <th class="table-active text-center middle" rowspan="2">Warna</th>
                                <th class="table-active text-center middle" rowspan="2">HJP</th>
                                <th class="table-active text-center middle" rowspan="2">FC</th>
                                <th class="table-active text-center middle" colspan="2">Order Pembelian</th>
                                <th class="table-active middle text-center" rowspan="2">% OP-FC</th>
                                <th class="table-active middle text-center" colspan="2">Delivery Order (DO)</th>
                                <th class="table-active middle text-center" rowspan="2">% DO-FC</th>
                                <th class="table-active middle text-center" rowspan="2">% DO-OP</th>
                                <th class="table-active middle text-center" colspan="2">Pendingan</th>
                                <th class="table-active middle text-center" colspan="2">Dropping</th>
                                <th class="table-active middle text-center" rowspan="2">Nomor Dokumen Referensi</th>
                                <th class="table-active middle text-center" rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th class="table-active text-center">Qty</th>
                                <th class="table-active text-center">Rp.</th>
                                <th class="table-active text-center">Qty</th>
                                <th class="table-active text-center">Rp.</th>
                                <th class="table-active text-center">Qty</th>
                                <th class="table-active text-center">Rp.</th>
                                <th class="table-active text-center">Qty</th>
                                <th class="table-active text-center">Rp.</th>
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
                                        <td><?= $row->e_customer_name; ?></td>
                                        <td><?= $row->i_product_base; ?></td>
                                        <td><?= wordwrap(trim($row->e_product_basename), 30, "<br>\n"); ?></td>
                                        <td><?= wordwrap(trim($row->e_color_name), 15, "<br>\n"); ?></td>
                                        <td class="text-right <?= warna($row->v_price); ?>"><?= number_format($row->v_price); ?></td>
                                        <td class="text-right <?= warna($row->n_quantity_fc); ?>"><?= number_format($row->n_quantity_fc); ?></td>
                                        <td class="text-right <?= warna($row->n_quantity_op); ?>"><?= number_format($row->n_quantity_op); ?></td>
                                        <td class="text-right <?= warna($row->price_op); ?>"><?= number_format($row->price_op); ?></td>
                                        <td class="text-right <?= warna($row->opfc); ?>"><?= $row->opfc; ?></td>
                                        <td class="text-right <?= warna($row->n_quantity_do); ?>"><?= number_format($row->n_quantity_do); ?></td>
                                        <td class="text-right <?= warna($row->price_do); ?>"><?= number_format($row->price_do); ?></td>
                                        <td class="text-right <?= warna($row->dofc); ?>"><?= $row->dofc; ?></td>
                                        <td class="text-right <?= warna($row->doop); ?>"><?= $row->doop; ?></td>
                                        <td class="text-right <?= warna($row->qty_pendingan); ?>"><?= number_format($row->qty_pendingan); ?></td>
                                        <td class="text-right <?= warna($row->price_pendingan); ?>"><?= number_format($row->price_pendingan); ?></td>
                                        <td class="text-right <?= warna($row->qty_dropping); ?>"><?= number_format($row->qty_dropping); ?></td>
                                        <td class="text-right <?= warna($row->price_dropping); ?>"><?= number_format($row->price_dropping); ?></td>
                                        <td class="text-right <?= warna($row->i_document); ?>"><?= $row->i_document; ?></td>
                                        <td></td>
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
                        filename: "Report_Realisasi_FC_OP_DO", //do not include extension
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