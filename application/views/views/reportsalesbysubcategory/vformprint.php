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
                    <table class="stripe row-border order-column table color-table inverse-table table-bordered class" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-active text-center middle">#</th>
                                <th class="table-active text-center middle">Kode</th>
                                <th class="table-active text-center middle">Nama Barang</th>
                                <?php
                                $week = "";
                                $i = 0;
                                if ($header->num_rows() > 0) {
                                    foreach ($header->result() as $key) {
                                        if ($week != $key->n_week) { $i++;?>
                                            <th class="table-warning text-center middle">SPB M<?= $i; ?></th>
                                        <?php }
                                        $week = $key->n_week;
                                        ?>
                                    <th class="text-right"><?= $key->d_sj;?></th>
                                <?php  }
                                } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if ($detail->num_rows()>0) {
                                foreach ($detail->result() as $key) {?>
                                <tr>
                                    <td><?= $no;?></td>
                                    <td><?= $key->i_product;?></td>
                                    <td><?= $key->e_product_name;?></td>
                                    <?php
                                    $week = "";
                                    $i = 0;
                                    if ($header->num_rows() > 0) {                                        
                                        foreach ($header->result() as $row) {
                                            if ($week!=$row->n_week) {$i++;
                                            $weekend = 'n_week'.$i;
                                            ?>
                                                <td class="text-right table-warning"><?= $key->$weekend;?></td>
                                            <?php }
                                            $val = 0;
                                            ?>
                                            <td class="text-right val">
                                                <?php 
                                                foreach (json_decode($key->d_sj) as $x => $d_sj) {
                                                    if ($d_sj == $row->d_sj) {
                                                        echo json_decode($key->n_deliver)[$x];
                                                    }
                                                }?>
                                            </td>
                                    <?php  $week = $row->n_week;}
                                    } ?>
                                </tr>
                                <?php 
                                $no++;}
                            }?>
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

        var td = document.querySelectorAll('.val');
        td.forEach((x, y) => {
            if(x.innerText == ''){
                x.innerText = 0;
            }
        })
    });
    
</script>