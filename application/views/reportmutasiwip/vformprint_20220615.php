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
                                $sum_saldo_repair = 0;
                                $sum_saldo_total = 0;
                                $sum_bb_in = 0;
                                $sum_bb_in_repair = 0;
                                $sum_bb_in_retur = 0;
                                $sum_bb_in_retur_repair = 0;
                                $sum_bb_in_total = 0;
                                $sum_bb_out = 0;
                                $sum_bb_out_repair = 0;
                                $sum_bb_out_retur = 0;
                                $sum_bb_out_retur_repair = 0;
                                $sum_bb_out_total = 0;
                                $sum_n_saldo_akhir = 0;
                                $sum_n_saldo_akhir_repair = 0;
                                $sum_so = 0;
                                $sum_so_repair = 0;
                                $sum_so_total = 0;
                                $sum_akhir = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_saldo += $key->n_saldo_awal;
                                        $sum_saldo_repair += $key->n_saldo_awal_repair;
                                        $sum_saldo_total += $key->n_saldo_awal + $key->n_saldo_awal_repair;
                                        $sum_bb_in += $key->n_masuk_bj;
                                        $sum_bb_in_retur += $key->n_masuk_repair_jadi;
                                        $sum_bb_in_repair += $key->n_masuk_repair_jahit;
                                        $sum_bb_in_retur_repair += $key->n_masuk_makloon;
                                        $sum_bb_in_total += $key->n_masuk_bj + $key->n_masuk_repair_jahit + $key->n_masuk_repair_jadi;
                                        $sum_bb_out += $key->n_keluar_bj;
                                        $sum_bb_out_retur += $key->n_keluar_repair_jadi;
                                        $sum_bb_out_repair += $key->n_keluar_repair_jahit;
                                        $sum_bb_out_retur_repair += $key->n_keluar_makloon;
                                        $sum_bb_out_total += $key->n_keluar_bj + $key->n_keluar_repair_jadi + $key->n_keluar_makloon;
                                        $sum_n_saldo_akhir += $key->n_saldo_akhir;
                                        $sum_n_saldo_akhir_repair += $key->n_saldo_akhir_repair;
                                        $sum_so += $key->n_so;
                                        $sum_so_repair += $key->n_so_repair;
                                        $sum_so_total += $key->n_so + $key->n_so_repair;
                                        $sum_akhir += $key->n_saldo_akhir + $key->n_saldo_akhir_repair;
                                    }
                                } ?>
                                <thead>
                                    <tr>
                                        <th rowspan="4" class="text-center ">No</th>
                                        <th class="" rowspan="4">Kode</th>
                                        <th class="" rowspan="4">Nama Barang</th>
                                        <th class="" rowspan="4">Warna</th>
                                        <!-- <th class="" rowspan="2">Unit Jahit</th> -->
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="4" class="text-center font_14">MASUK</th>
                                        <th colspan="4" class="text-center font_14">KELUAR</th>
                                        <th colspan="10"></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center" style="width:20px; ">
                                            <!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Terima Bagus"></i>-->Dari<br>UNIT JAHIT
                                        </th>
                                        <th class="text-center">
                                            <!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Terima Perbaikan"></i>-->Dari<br>UNIT JAHIT
                                        </th>
                                        <!-- <th class="text-right ">Pinjaman</th> -->
                                        <!-- <th class="text-center">
                                            <i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Retur Gudang"></i>Dari<br>GUDANG JADI
                                        </th> -->
                                        <th class="text-center">
                                            Dari<br>PACKING
                                        </th>
                                        <th></th>
                                        <th class="text-center">
                                            <!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Kirim Bagus"></i>-->Ke<br>PACKING
                                        </th>
                                        <!-- <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Belum Ada"></i>Makloon</th> -->
                                        <th class="text-center">
                                            <!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Kirim Perbaikan"></i>-->Ke<br>PACKING
                                        </th>
                                        <!-- <th class="text-right warna_out">Pinjaman</th> -->
                                        <th class="text-center">
                                            <!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Kirim Repair"></i>-->Ke<br>UNIT JAHIT
                                        </th>
                                        <th></th>
                                        <!-- <th class="text-center">
                                            Ke<br>MAKLOON PACKING
                                        </th> -->
                                        <th class="text-center font_14">I1</th>
                                        <th class="text-center font_14">I2</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <!-- <th></th> -->
                                    </tr>
                                    <tr>
                                        <th class="text-center font_14">H1</th>
                                        <th class="text-center font_14">H2</th>
                                        <th class="text-center font_14">H</th>
                                        <th class="text-center font_14">D</th>
                                        <th class="text-center font_14">Y</th>
                                        <!-- <th class="text-center font_14">XXX</th> -->
                                        <th class="text-center font_14">XX</th>
                                        <th></th>
                                        <th class="text-center font_14">G</th>
                                        <th class="text-center font_14">GG</th>
                                        <th class="text-center font_14">X</th>
                                        <th></th>
                                        <th class="text-center font_14">H1 + D + Y - G</th>
                                        <th class="text-center font_14">H2 + XX - x</th>
                                        <th class="text-center font_14">I</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right ">Saldo Awal Bagus</th>
                                        <th class="text-right ">Saldo Awal Repair</th>
                                        <th class="ext-center warna_kuning">Total Saldo Awal</th>
                                        <th class="text-right">Terima BJ</th>
                                        <th class="text-right">Terima Repair<br>(HASIL PERBAIKAN)</th>
                                        <!-- <th class="text-right">Terima Repair<br>(UNTUK DIPERBAIKI)</th> -->
                                        <th class="text-right">Terima Repair<br>(UNTUK DIPERBAIKI)</th>
                                        <th class="text-center warna_kuning">Total<br>Terima</th>
                                        <th class="text-right">Kirim untuk<br>di packing<br>bagus</th>
                                        <th class="text-right">Kirim untuk<br>di packing<br>diperbaiki</th>
                                        <th class="text-right">Kirim repair<br>(UNTUK DIPERBAIKI)</th>
                                        <!-- <th></th> -->
                                        <th class="text-center warna_kuning">Total<br>Kirim</th>
                                        <th class="text-right">Saldo Akhir Bagus</th>
                                        <th class="text-right">Saldo Akhir Repair</th>
                                        <th class="text-center warna_kuning">Total<br>Saldo Akhir</th>
                                        <th class="text-right ">Stock Opname<br>Bagus</th>
                                        <th class="text-right ">Stock Opname<br>Repair</th>
                                        <th class="text-center warna_kuning"> Total<br>Stock Opname</th>
                                        <th class="text-right ">Selisih Bagus</th>
                                        <th class="text-right ">Selisih Repair</th>
                                        <th class="text-center warna_kuning">Total Selisih</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-center ">TOTAL</th>
                                        <th class="text-right font_14"><?= $sum_saldo; ?></th>
                                        <th class="text-right font_14"><?= $sum_saldo_repair; ?></th>
                                        <th class="text-right font_14"><?= $sum_saldo_total; ?></th>
                                        <th class="text-right font_14"><?= $sum_bb_in; ?></th>
                                        <th class="text-right font_14"><?= $sum_bb_in_repair; ?></th>
                                        <th class="text-right font_14"><?= $sum_bb_in_retur; ?></th>
                                        <!-- <th class="text-right font_14"><?= $sum_bb_in_retur_repair; ?></th> -->
                                        <th class="text-right font_14"><?= $sum_bb_in_total; ?></th>
                                        <th class="text-right font_14"><?= $sum_bb_out; ?></th>
                                        <th class="text-right font_14"><?= $sum_bb_out_retur; ?></th>
                                        <!-- <th class="text-right font_14"><?= $sum_bb_out_repair; ?></th> -->
                                        <th class="text-right font_14"><?= $sum_bb_out_retur_repair; ?></th>
                                        <th class="text-right font_14"><?= $sum_bb_out_total; ?></th>
                                        <th class="text-right font_14"><?= $sum_n_saldo_akhir; ?></th>
                                        <th class="text-right font_14"><?= $sum_n_saldo_akhir_repair; ?></th>
                                        <th class="text-right font_14"><?= $sum_akhir; ?></th>
                                        <th class="text-right font_14"><?= $sum_so; ?></th>
                                        <th class="text-right font_14"><?= $sum_so_repair; ?></th>
                                        <th class="text-right font_14"><?= $sum_so_total; ?></th>
                                        <th class="text-right font_14"><?= $sum_n_saldo_akhir - $sum_so; ?></th>
                                        <th class="text-right font_14"><?= $sum_n_saldo_akhir_repair - $sum_so_repair; ?></th>
                                        <th class="text-right font_14"><?= ($sum_n_saldo_akhir - $sum_so) + ($sum_n_saldo_akhir_repair - $sum_so_repair); ?></th>
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
                                        $sum_saldo_total = $row->n_saldo_awal + $row->n_saldo_awal_repair;
                                        $sum_in = $row->n_masuk_bj + $row->n_masuk_repair_jahit + $row->n_masuk_repair_jadi;
                                        $sum_out = $row->n_keluar_bj + $row->n_keluar_repair_jadi + $row->n_keluar_makloon;
                                        $sa_bagus = ($row->n_saldo_awal + $row->n_masuk_bj + $row->n_masuk_repair_jahit) - $row->n_keluar_bj;
                                        $sa_repair = ($row->n_saldo_awal_repair + $row->n_masuk_makloon) - $row->n_keluar_makloon;
                                        $sum_so = $row->n_so + $row->n_so_repair;
                                        $sum_saldo_akhir = $row->n_saldo_akhir + $row->n_saldo_akhir_repair;
                                        $selisih_bagus = $row->n_saldo_akhir - $row->n_so;
                                        $selisih_repair = $row->n_saldo_akhir_repair - $row->n_so_repair;
                                        $total_selisih = $selisih_bagus + $selisih_repair;

                                        $warna1 = ($row->n_saldo_awal > 0) ? 'warna_saldo' : '';
                                        $warnarepair1 = ($row->n_saldo_awal_repair > 0) ? 'warna_saldo' : '';
                                        $warnain1 = ($row->n_masuk_bj > 0) ? 'warna_in' : '';
                                        $warnain2 = ($row->n_masuk_repair_jahit > 0) ? 'warna_in' : '';
                                        $warnain3 = ($row->n_masuk_repair_jadi > 0) ? 'warna_in' : '';
                                        $warnain4 = ($sum_in > 0) ? 'warna_in' : '';
                                        $warnaout1 = ($row->n_keluar_bj > 0) ? 'warna_out' : '';
                                        $warnaout2 = ($row->n_keluar_repair_jadi > 0) ? 'warna_out' : '';
                                        $warnaout3 = ($row->n_keluar_repair_jahit > 0) ? 'warna_out' : '';
                                        $warnaout4 = ($sum_out > 0) ? 'warna_out' : '';
                                        $warna4 = ($row->n_saldo_akhir < 0) ? 'warna_n_saldo_akhir' : '';

                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= trim($row->e_product_basename); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td class="text-right <?= $warna1; ?>"><?= $row->n_saldo_awal; ?></td>
                                            <td class="text-right <?= $warnarepair1; ?>"><?= $row->n_saldo_awal_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_saldo_total; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_bj); ?>"><?= $row->n_masuk_bj; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_repair_jahit); ?>"><?= $row->n_masuk_repair_jahit; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_repair_jadi); ?>"><?= $row->n_masuk_repair_jadi; ?></td>
                                            <!-- <td class="text-right <?php warna($row->n_masuk_makloon); ?>"><?= $row->n_masuk_makloon; ?></td> -->
                                            <td class="text-right warna_cyan"><?= $sum_in; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_bj); ?>"><?= $row->n_keluar_bj; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_repair_jadi); ?>"><?= $row->n_keluar_repair_jadi; ?></td>
                                            <!-- <td class="text-right <?php warna($row->n_keluar_repair_jahit); ?>"><?= $row->n_keluar_repair_jahit; ?></td> -->
                                            <td class="text-right <?php warna($row->n_keluar_makloon); ?>"><?= $row->n_keluar_makloon; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_out; ?></td>
                                            <td class="text-right <?php warna($row->n_saldo_akhir); ?>"><?= $row->n_saldo_akhir; ?></td>
                                            <td class="text-right <?php warna($row->n_saldo_akhir); ?>"><?= $row->n_saldo_akhir_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_saldo_akhir; ?></td>
                                            <td class="text-right <?php warna($row->n_so); ?>"><?= $row->n_so; ?></td>
                                            <td class="text-right <?php warna($row->n_so_repair); ?>"><?= $row->n_so_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_so; ?></td>
                                            <td class="text-right <?php warna($selisih_bagus); ?>"><?= $selisih_bagus; ?></td>
                                            <td class="text-right <?php warna($selisih_repair); ?>"><?= $selisih_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $total_selisih; ?></td>
                                        </tr>
                                    <?php  } ?>
                                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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