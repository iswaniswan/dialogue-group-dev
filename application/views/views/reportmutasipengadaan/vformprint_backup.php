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

        .warna_saldo_akhir {
            background-color: #922B21 !important;
            color:white;
            weight:bold;
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
                                        <?php if ($bagian != '') { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $bagian->i_bagian . ' - ' . $bagian->e_bagian_name; ?></b></td>
                                        <?php } else { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>Semua Unit Jahit</b></td>
                                        <?php } ?>
                                    </tr>

                                    <tr>
                                        <td width="160px" class="text-muted m-l-3" style="font-size: 16px"><b>Tanggal Mutasi</b></td>
                                        <td width="10px" class="text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="text-muted m-l-3" style="font-size: 16px"><b><?php echo $dfrom . ' s/d ' . $dto; ?></b></td>
                                    </tr>

                                    <tr>
                                        <td width="160px" class="xx text-muted m-l-3" style="font-size: 16px"><b>Kategori Barang</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $kategori->e_nama_kelompok; ?></b></td>
                                    </tr>

                                    <tr>
                                        <td width="200" class="xx text-muted m-l-3" style="font-size: 16px"><b>Sub Kategori Barang</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $jenis->e_type_name; ?></b></td>
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
                            <table id="tabledata" class="table nowrap font-12 table-bordered table2excel" cellspacing="0">
                                <?php
                                $sum_saldo = 0;
                                $sum_bb_in = 0;
                                $sum_in_reject = 0;
                                $sum_total_terima = 0;
                                $sum_bb_out = 0;
                                $sum_saldo_akhir = 0;
                                $sum_total_kirim = 0;
                                $sum_bb_repair = 0;
                                $sum_bb_reject = 0;
                                $sum_saldo_akhir = 0;
                                $sum_so = 0;
                                $selisih = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_saldo += $key->saldo_awal;
                                        $sum_bb_in += $key->terima_bb;
                                        $sum_in_reject = $key->terima_reject;
                                        $sum_total_terima += $key->terima_bb + $key->terima_repair + $key->terima_reject;
                                        $sum_bb_out += $key->kirim_bj;
                                        $sum_bb_repair += $key->kirim_repair;
                                        $sum_bb_reject += $key->kirim_reject;
                                        $sum_total_kirim += $key->kirim_bj + $key->kirim_repair + $key->kirim_reject;
                                        $sum_saldo_akhir += $key->saldo_akhir;
                                        $sum_so += $key->so;
                                    }
                                } ?>
                                <thead>
                                    <tr class="warna_table">
                                        <th rowspan="2" class="text-center text-white">No</th>
                                        <th class="text-white" rowspan="2">Kode</th>
                                        <th class="text-white" rowspan="2">Nama Barang</th>
                                        <th class="text-white" rowspan="2">Warna</th>
                                        <th rowspan="2" class="text-right text-white">Saldo Awal</th>
                                        <th colspan="4" class="text-center text-white">Masuk</th>
                                        <th colspan="4" class="text-center text-white">Keluar</th>
                                        <th rowspan="2" class="text-right text-white">Saldo Akhir</th>
                                        <th rowspan="2" class="text-right text-white">SO</th>
                                        <th rowspan="2" class="text-right text-white">Selisih</th>
                                    </tr>
                                    <tr class="warna_table">
                                        <th class="text-right warna_in"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>Terima BB</th>
                                        <th class="text-right warna_in"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>Terima Repair</th>
                                        <!-- <th class="text-right text-white">Pinjaman</th> -->
                                        <th class="text-right warna_in"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>Terima Reject</th>
                                        <th class="text-right warna_in">Total Terima</th>
                                        <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM BAGUS"></i>Kirim BJ</th>
                                        <!-- <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Belum Ada"></i>Makloon</th> -->
                                        <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM PERBAIKAN"></i>Kirim Repair</th>
                                        <!-- <th class="text-right warna_out">Pinjaman</th> -->
                                        <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM KE PENGESETAN"></i>Kirim Reject</th>
                                        <th class="text-right warna_out">Total Kirim</th>
                                    </tr>
                                    <tr class="warna_table">
                                        <th colspan="4" class="text-center text-white">TOTAL</th>
                                        <th class="text-right text-white"><?= $sum_saldo;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_in;?></th>
                                        <th class="text-right text-white"><?= 0;?></th>
                                        <th class="text-right text-white"><?= $sum_in_reject;?></th>
                                        <th class="text-right text-white"><?= $sum_total_terima;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_out;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_repair;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_reject;?></th>
                                        <th class="text-right text-white"><?= $sum_total_kirim;?></th>
                                        <th class="text-right text-white"><?= $sum_saldo_akhir;?></th>
                                        <th class="text-right text-white"><?= $sum_so;?></th>
                                        <th class="text-right text-white"><?= $sum_saldo_akhir-$sum_so;?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $gudang = '';
                                    foreach ($data2 as $row) {
                                        $i++;
                                        $sum_terima = $row->terima_bb + $row->terima_repair + $row->terima_reject;
                                        $sum_kirim = $row->kirim_bj + $row->kirim_repair + $row->kirim_reject;
                                        $sum_akhir = $row->saldo_awal + $sum_terima - $sum_kirim;
                                        $selisih = $sum_akhir - $row->so;
                                        if($row->so == 0){
                                            $selisih = 0;
                                        }

                                        $warna1 = ($row->saldo_awal > 0) ? 'warna_saldo' : '';
                                        $warnain1 = ($row->terima_bb > 0) ? 'warna_in' : '';
                                        $warnain2 = ($row->terima_repair > 0) ? 'warna_in' : '';
                                        $warnain3 = ($row->terima_reject > 0) ? 'warna_in' : '';
                                        $warnain4 = ($sum_terima > 0) ? 'warna_in' : '';
                                        $warnaout1 = ($row->kirim_bj > 0) ? 'warna_out' : '';
                                        $warnaout2 = ($row->kirim_repair > 0) ? 'warna_out' : '';
                                        $warnaout3 = ($row->kirim_reject > 0) ? 'warna_out' : '';
                                        $warnaout4 = ($sum_kirim > 0) ? 'warna_out' : '';
                                        $warnasa = ($sum_akhir < 0) ? 'warna_saldo_akhir' : '';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= trim($row->e_product_wipname); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td class="text-right <?= $warna1; ?>"><?= $row->saldo_awal; ?></td>
                                            <td class="text-right <?= $warnain1; ?>"><?= $row->terima_bb; ?></td>
                                            <td class="text-right <?= $warnain2; ?>"><?= $row->terima_repair; ?></td>
                                            <td class="text-right <?= $warnain3; ?>"><?= $row->terima_reject; ?></td>
                                            <td class="text-right <?= $warnain4; ?>"><?= $sum_terima; ?></td>
                                            <td class="text-right <?= $warnaout1; ?>"><?= $row->kirim_bj; ?></td>
                                            <td class="text-right <?= $warnaout2; ?>"><?= $row->kirim_repair; ?></td>
                                            <td class="text-right <?= $warnaout3; ?>"><?= $row->kirim_reject; ?></td>
                                            <td class="text-right <?= $warnaout4; ?>"><?= $sum_kirim; ?></td>
                                            <td class="text-right <?= $warnasa; ?>"><?= $sum_akhir; ?></td>
                                            <td class="text-right"><?= $row->so; ?></td>
                                            <td class="text-right"><?= $selisih; ?></td>
                                        </tr>
                                    <?php  } ?>
                                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div hidden="true">
                        <table id="datatable">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Kode Barang</th>
                                    <th rowspan="2">Nama Barang</th>
                                    <th rowspan="2">Warna</th>
                                    <th rowspan="2">Saldo Awal</th>
                                    <th colspan="3">Masuk</th>
                                    <th colspan="3">Keluar</th>
                                    <th rowspan="2">Saldo Akhir</th>
                                    <th rowspan="2">SO</th>
                                    <th rowspan="2">Selisih</th>
                                </tr>
                                <tr>
                                    <th>Terima BB</th>
                                    <th>Terima Repair</th>
                                    <th>Terima Reject</th>
                                    <th>Kirim BJ</th>
                                    <th>Kirim Repair</th>
                                    <th>Kirim Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $gudang = '';
                                foreach ($data2 as $row) {
                                    $i++;
                                    $sum_terima = $row->terima_bb + $row->terima_repair + $row->terima_reject;
                                    $sum_kirim = $row->kirim_bj + $row->kirim_repair + $row->kirim_reject;
                                    $sum_akhir = $row->saldo_awal + $sum_terima - $sum_kirim;
                                    $selisih = $sum_akhir - $row->so;
                                    if($row->so = 0){
                                        $selisih = 0;
                                    }
                                ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $row->i_product_wip; ?></td>
                                        <td><?= trim($row->e_product_wipname); ?></td>
                                        <td><?= $row->e_color_name; ?></td>
                                        <td><?= $row->saldo_awal; ?></td>
                                        <td><?= $row->terima_bb; ?></td>
                                        <td><?= $row->terima_repair; ?></td>
                                        <td><?= $row->terima_reject; ?></td>
                                        <td><?= $row->kirim_bj; ?></td>
                                        <td><?= $row->kirim_repair; ?></td>
                                        <td><?= $row->kirim_reject; ?></td>
                                        <td><?= $sum_akhir; ?></td>
                                        <td><?= $row->so; ?></td>
                                        <td><?= $selisih; ?></td>
                                    </tr>
                                <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-3">
                            <div class="pull-center m-t-30 text-center">
                                <p style="font-size: 16px; margin-top: -1rem; margin-bottom: 1rem;">Dibuat,</p>
                                <h3 style="font-size: 16px;">(...................................)</h3>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="pull-center m-t-30 text-center">
                                <p style="font-size: 16px; margin-top: -1rem; margin-bottom: 1rem;">Mengetahui,</p>
                                <h3 style="font-size: 16px;">(...................................)</h3>
                            </div>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                </div> -->
                </div>
                <!-- <hr style="margin-top: 0rem;
                    margin-bottom: 0rem; font-size: 10px;">
            <font face="Courier New" size="2"><?php date_default_timezone_set('Asia/Jakarta');
                                                echo "Tanggal Cetak : " . $tgl = date("d") . " " . $this->fungsi->mbulan(date("m")) . " " . date("Y") . ",  Jam : " . date("H:i:s");
                                                ?>
            </font> -->
                <div class="row">
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
          'columnNum' : 4,
          'scrollable': true,
        });

        $(".exportToExcel").click(function(e) {
            $("#datatable").table2excel({
                // exclude CSS class
                exclude: ".floatThead-col",
                name: "Worksheet Name",
                filename: "Report_Mutasi_Pengadaan", //do not include extension
                fileext: ".xls" // file extension
            });
        });

        $('[data-toggle="popover"]').popover();
    });
</script>