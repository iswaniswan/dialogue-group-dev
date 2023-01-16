<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>assets/logo.png">-->
    <title><?= $this->global['title']; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css?v=1" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url(); ?>assets/css/style_print.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/global.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="<?= base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- File Upload -->
    <style type="text/css">
        .table>thead>tr>th {
            vertical-align: bottom;
            border-bottom: 1px solid #8d8888 !important;
        }

        .table-bordered>tbody>tr>td,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>td,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>thead>tr>th {
            border: 1px solid #8d8888 !important;
        }

        .heading {
            word-break: break-all;
        }

        .warna_table {
            background-color: #4c5667 !important;
        }

        .warna_saldo {
            background-color: #FCF3CF !important;
        }

        .warna_in {
            background-color: #D4EFDF !important;
        }

        .warna_out {
            background-color: #FADBD8 !important;
        }

        .warna_n_saldo_akhir {
            background-color: #922B21 !important;
            color: white;
            weight: bold;
        }

        .style {
            padding: 1px 8px;
        }

        .nowrap {
            white-space: nowrap !important;
            color: white;
        }

        .font-12 {
            padding-left: 3px !important;
            padding-right: 3px !important;
            font-size: 12px !important;
        }

        .warna_kuning {
            background-color: #FFF200 !important;
            font-weight: bold !important;
        }

        .warna_magenta {
            background-color: #ff00ff !important;
            font-weight: bold !important;
            color: black;
        }

        .warna_cyan {
            background-color: #00ffff !important;
            font-weight: bold !important;
            color: black;
        }

        .bold {
            font-weight: bold !important;
            color: black;
        }

        .red_bold {
            font-weight: bold !important;
            color: #bd0000;
        }

        .font_14 {
            font-size: 14px !important;
            font-weight: bold !important;
        }

        @media print {
            .page-break {
                display: block;
                page-break-before: always;
            }

            .noDisplay {
                display: none;
            }

            .pagebreak {
                page-break-before: always;
            }

            @page {
                size: Letter;
                margin: 0mm;
                /* this affects the margin in the printer settings */
            }
        }
    </style>
</head>

<body>
    <?php
    include("php/fungsi.php");
    ?>
    <?php
    $hal = 1; ?>
    <!-- color CSS -->
    <div class="white-box printableArea">
        <!-- <table class="isinya" border='0' align="center" width="70%"> -->
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 align="center" style="font-size: 16px; line-height: 0px;"><b>Laporan Mutasi</b><br></h3>
                        <div class="pull-left">
                            <address style="padding:0px">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="160px" class="xx text-muted m-l-3" style="font-size: 16px"><b>Nama Bagian</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <?php if ($i_bagian != '') { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $bagian->i_bagian . ' - ' . $bagian->e_bagian_name; ?></b></td>
                                        <?php } else { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>Semua</b></td>
                                        <?php } ?>
                                    </tr>

                                    <tr>
                                        <td width="160px" class="text-muted m-l-3" style="font-size: 16px"><b>Tanggal Mutasi</b></td>
                                        <td width="10px" class="text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="text-muted m-l-3" style="font-size: 16px"><b><?php echo $dfrom . ' s/d ' . $dto; ?></b></td>
                                    </tr>

                                    <tr>
                                        <?php
                                        if (!empty($kategori->e_nama_kelompok)) {
                                            $e_nama_kelompok = $kategori->e_nama_kelompok;
                                        } else {
                                            $e_nama_kelompok = "SEMUA KATEGORI";
                                        }
                                        ?>
                                        <td width="160px" class="xx text-muted m-l-3" style="font-size: 16px"><b>Kategori Barang</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $e_nama_kelompok; ?></b></td>
                                    </tr>

                                    <tr>
                                        <?php
                                        if (!empty($jenis->e_type_name)) {
                                            $e_type_name = $jenis->e_type_name;
                                        } else {
                                            $e_type_name = "SEMUA SUB KATEGORI";
                                        }
                                        ?>
                                        <td width="200" class="xx text-muted m-l-3" style="font-size: 16px"><b>Sub Kategori Barang</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $e_type_name; ?></b></td>
                                    </tr>
                                </table>
                            </address>
                        </div>
                        <!-- <div class="pull-right text-right" style="font-size: 13px"> <address>Cimahi,&nbsp;//date('d', strtotime($row->d_op)).' '.$this->fungsi->mbulan(date('m', strtotime($row->d_op))).' '.date('Y', strtotime($row->d_op));</address> </div> -->
                    </div>
                </div>
                <!-- <hr style="margin-top: -1rem;
                    margin-bottom: 0rem;">
                <br> -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_fixed" style="width: 100%; max-height: 600px;">
                            <!-- <div class="table-responsive"> -->
                            <table id="tabledata" class="table nowrap font-12 table-bordered table2excel" cellspacing="0">
                                <?php
                                $sum_saldo = 0;
                                $sum_in = 0;
                                $sum_in_repair = 0;
                                $sum_in_total = 0;
                                $sum_out = 0;
                                $sum_out_repair = 0;
                                $sum_out_total = 0;
                                $sum_n_saldo_akhir = 0;
                                $sum_so = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_saldo += $key->n_saldo_awal;
                                        $sum_in += $key->n_masuk;
                                        $sum_in_repair += $key->n_masuk_repair;
                                        $sum_in_total += $key->n_masuk + $key->n_masuk_repair;
                                        $sum_out += $key->n_keluar;
                                        $sum_out_repair += $key->n_keluar_repair;
                                        $sum_out_total += $key->n_keluar + $key->n_keluar_repair;
                                        $sum_n_saldo_akhir += $key->n_saldo_akhir;
                                        $sum_so += $key->n_so;
                                    }
                                } ?>
                                <thead>
                                    <tr>
                                        <th rowspan="4" class="text-center ">No</th>
                                        <th rowspan="4">Kode</th>
                                        <th rowspan="4">Nama Barang</th>
                                        <th rowspan="4">Warna</th>
                                        <th rowspan="4" class="text-right">Saldo Awal</th>
                                        <th colspan="3" class="text-center font_14">MASUK</th>
                                        <th colspan="4" class="text-center font_14">KELUAR</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="width:20px; ">
                                            Dari<br>WIP
                                        </th>
                                        <th class="text-center">
                                            Dari<br>WIP
                                        </th>
                                        <th></th>
                                        <th class="text-center">
                                            Ke<br>GUDANG JADI
                                        </th>
                                        <th class="text-center">
                                            Ke<br>GUDANG JADI
                                        </th>
                                        <th class="text-center">
                                            Ke<br>WIP
                                        </th>
                                        <th></th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center font_14">D</th>
                                        <th class="text-center font_14">Y</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center font_14">XX</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Terima Bagus</th>
                                        <th class="text-right">Terima Repair<br>(HASIL PERBAIKAN)</th>
                                        <th class="text-center warna_kuning">Total<br>Terima</th>
                                        <th class="text-right">Kirim Bagus</th>
                                        <th class="text-right">Kirim Repair<br>(HASIL PERBAIKAN)</th>
                                        <th class="text-right">Kirim Repair<br>(UNTUK DIPERBAIKI)</th>
                                        <th class="text-center warna_kuning">Total<br>Kirim</th>
                                        <th class="text-right">Saldo Akhir</th>
                                        <th class="text-right">SO</th>
                                        <th class="text-right">n_Selisih</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-center ">TOTAL</th>
                                        <th class="text-right font_14"><?= $sum_saldo; ?></th>
                                        <th class="text-right font_14"><?= $sum_in; ?></th>
                                        <th class="text-right font_14"><?= $sum_in_repair; ?></th>
                                        <th class="text-right font_14"><?= $sum_in_total; ?></th>
                                        <th class="text-right font_14"><?= $sum_out; ?></th>
                                        <th class="text-right font_14"><?= $sum_out_repair; ?></th>
                                        <th class="text-right font_14"><?= 0; ?></th>
                                        <th class="text-right font_14"><?= $sum_out_total; ?></th>
                                        <th class="text-right font_14"><?= $sum_n_saldo_akhir; ?></th>
                                        <th class="text-right font_14"><?= $sum_so; ?></th>
                                        <th class="text-right font_14"><?= $sum_n_saldo_akhir - $sum_so; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $gudang = '';

                                    function warna($angka)
                                    {
                                        if ($angka > 0) {
                                            echo "bold";
                                        } else if ($angka < 0) {
                                            echo "red_bold";
                                        }
                                    }

                                    foreach ($data2 as $row) {
                                        $i++;
                                        $sum_in = $row->n_masuk + $row->n_masuk_repair;
                                        $sum_out = $row->n_keluar + $row->n_keluar_repair;

                                        $warna1 = ($row->n_saldo_awal > 0) ? 'warna_saldo' : '';
                                        $warnain1 = ($row->n_masuk > 0) ? 'warna_in' : '';
                                        $warnain2 = ($row->n_masuk_repair > 0) ? 'warna_in' : '';
                                        $warnain4 = ($sum_in > 0) ? 'warna_in' : '';
                                        $warnaout1 = ($row->n_keluar > 0) ? 'warna_out' : '';
                                        $warnaout2 = ($row->n_keluar_repair > 0) ? 'warna_out' : '';
                                        $warnaout4 = ($sum_out > 0) ? 'warna_out' : '';
                                        $warna4 = ($row->n_saldo_akhir < 0) ? 'warna_n_saldo_akhir' : '';

                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= trim($row->e_product_basename); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td class="text-right <?= $warna1; ?>"><?= $row->n_saldo_awal; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk); ?>"><?= $row->n_masuk; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_repair); ?>"><?= $row->n_masuk_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_in; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar); ?>"><?= $row->n_keluar; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_repair); ?>"><?= $row->n_keluar_repair; ?></td>
                                            <td class="text-right"><?= 0; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_out; ?></td>
                                            <td class="text-right <?php warna($row->n_saldo_akhir); ?>"><?= $row->n_saldo_akhir; ?></td>
                                            <td class="text-right <?php warna($row->n_so); ?>"><?= $row->n_so; ?></td>
                                            <td class="text-right <?php warna($row->n_selisih); ?>"><?= $row->n_selisih; ?></td>
                                        </tr>
                                    <?php  } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="noDisplay">
                    <div class="text-center mt-4"> <button id="print" class="btn btn-info btn-outline exportToExcel" type="button"> <span><i class="fa fa-download"></i> Export</span> </button> </div>
                </div>
            </div>
        </div>
        <!-- </table> -->
    </div>
</body>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/freeze-table.js?v=1"></script>
<script src="<?= base_url(); ?>assets/js/jquery.table2excel.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/tether.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/js/tooltip.js"></script>
<script>
    $(function() {
        /* var $table = $('.table');
        $table.floatThead({
            responsiveContainer: function($table) {
                return $table.closest('.table-responsive');
            }
        }); */
        $(".table_fixed").freezeTable({
            'columnNum': 4,
            'scrollable': true,
        });

        $(".exportToExcel").click(function(e) {
            $("#tabledata").table2excel({
                // exclude CSS class
                exclude: ".floatThead-col",
                name: "Worksheet Name",
                filename: "Report_Mutasi_WIP", //do not include extension
                fileext: ".xls" // file extension
            });
        });

        $('[data-toggle="popover"]').popover();
    });
</script>