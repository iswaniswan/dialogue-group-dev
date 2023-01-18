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
                                        <td width="160px" class="xx text-muted m-l-3" style="font-size: 16px"><b>Nama Unit Jahit</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <?php if ($i_bagian != '') { ?>
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
                                $sum_saldo_awalb = 0;
                                $sum_saldo_awalr = 0;
                                $sum_bb_in = 0;
                                $sum_bb_out = 0;
                                $sum_retur_in = 0;
                                $sum_retur_out = 0;
                                $sum_reject_in = 0;
                                $sum_reject_out = 0;
                                $sum_saldo_akhirb = 0;
                                $sum_saldo_akhirr = 0;
                                $sum_sob = 0;
                                $sum_sor = 0;
                                $sum_selisihb = 0;
                                $sum_selisihr = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_saldo_awalb += $key->saldoawal;
                                        $sum_saldo_awalr += $key->saldoawal_repair;
                                        $sum_bb_in += $key->m_masuk;
                                        $sum_bb_out += $key->k_keluar;
                                        $sum_retur_in += $key->m_retur;
                                        $sum_retur_out += $key->k_retur;
                                        $sum_reject_in += $key->m_tolakan;
                                        $sum_reject_out += $key->k_reject;
                                        $sum_saldo_akhirb += $key->saldo_akhir;
                                        $sum_saldo_akhirr += $key->saldo_akhir_repair;
                                        $sum_sob += $key->so;
                                        $sum_sor += $key->so_repair;
                                        $sum_selisihb += $key->selisih;
                                        $sum_selisihr += $key->selisih_repair;
                                    }
                                } ?>
                                <thead>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="4"></th>
                                        <th colspan="4" class="text-center font_14">MASUK</th>
                                        <th colspan="4" class="text-center font_14">KELUAR</th>
                                        <th colspan="9"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th class="text-center font_14 warna_magenta">MUTASI<br>JAHIT</th>
                                        <th colspan="1"></th>
                                        <th class="text-center" colspan="3">Saldo Awal</th>
                                        <th class="text-center">Dari<br>Pengadaan</th>
                                        <th class="text-center">Dari<br>Pengadaan</th>
                                        <th class="text-center">Dari<br>WIP</th>
                                        <th></th>
                                        <th class="text-center">Ke<br>WIP</th>
                                        <th class="text-center">Ke<br>WIP</th>
                                        <th class="text-center">Ke<br>Pengadaan</th>
                                        <th></th>
                                        <th class="text-center" colspan="3">Saldo Akhir</th>
                                        <th colspan="6"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th class="text-center font_14">E1</th>
                                        <th class="text-center font_14">E2</th>
                                        <th class="text-center font_14">E</th>
                                        <th class="text-center font_14">C1</th>
                                        <th class="text-center font_14">C3</th>
                                        <th class="text-center font_14">X</th>
                                        <th class="text-center font_14">C1+C3+X</th>
                                        <th class="text-center font_14">D</th>
                                        <th class="text-center font_14">Y</th>
                                        <th class="text-center font_14">C2</th>
                                        <th class="text-center font_14">D+Y+C2</th>
                                        <th class="text-center font_14 warna_magenta">F1=[E1+C1+C3-D-C2]</th>
                                        <th class="text-center font_14 warna_magenta">F2=[E2+X-Y]</th>
                                        <th class="text-center font_14">F= F1 + F2</th>
                                        <th colspan="6"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Warna</th>
                                        <th>Brand</th>
                                        <th>Unit Jahit</th>
                                        <th class="text-center">Bagus</th>
                                        <th class="text-center">Repair</th>
                                        <th class="text-center warna_kuning">Total</th>
                                        <th class="text-right">Terima BB</th>
                                        <th class="text-right">Terima Retur<br>(SUDAH DILENGKAPI)</th>
                                        <th class="text-right">Terima Repair<br>(UNTUK DIPERBAIKI)</th>
                                        <th class="text-right warna_kuning">Total Terima</th>
                                        <th class="text-right">Kirim BJ</th>
                                        <th class="text-right">Kirim Repair<br>(HASIL PERBAIKAN)</th>
                                        <th class="text-right">Kirim Retur<br>(UNTUK DILENGKAPI)</th>
                                        <th class="text-right warna_kuning">Total Kirim</th>
                                        <th class="text-right">Bagus</th>
                                        <th class="text-right">Repair</th>
                                        <th class="text-right warna_kuning">Total</th>
                                        <th class="text-right">SO Bagus</th>
                                        <th class="text-right">SO Repair</th>
                                        <th class="text-right warna_kuning">Total SO</th>
                                        <th class="text-right">Selisih Bagus</th>
                                        <th class="text-right">Selisih Repair</th>
                                        <th class="text-right warna_kuning">Total Selisih</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="font_14 text-right">TOTAL</th>
                                        <th></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_awalb; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_awalr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_awalb + $sum_saldo_awalr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_reject_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_retur_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_in + $sum_reject_in + $sum_retur_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_retur_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_reject_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_out + $sum_retur_out + $sum_reject_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_akhirb; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_akhirr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_akhirb + $sum_saldo_akhirr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_sob; ?></th>
                                        <th class="font_14 text-right"><?= $sum_sor; ?></th>
                                        <th class="font_14 text-right"><?= $sum_sob + $sum_sor; ?></th>
                                        <th class="font_14 text-right"><?= $sum_selisihb; ?></th>
                                        <th class="font_14 text-right"><?= $sum_selisihr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_selisihb + $sum_selisihr; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $sum_saldo_awalb = 0;
                                    $sum_saldo_awalr = 0;
                                    $sum_bb_in = 0;
                                    $sum_bb_out = 0;
                                    $sum_retur_in = 0;
                                    $sum_retur_out = 0;
                                    $sum_reject_in = 0;
                                    $sum_reject_out = 0;
                                    $sum_saldo_akhirb = 0;
                                    $sum_saldo_akhirr = 0;
                                    $sum_sob = 0;
                                    $sum_sor = 0;
                                    $sum_selisihb = 0;
                                    $sum_selisihr = 0;
                                    $gudang = '';
                                    foreach ($data2 as $row) {
                                        $i++;
                                        $sawal = ($row->saldoawal) ? "bold" : "";
                                        $sawalr = ($row->saldoawal_repair) ? "bold" : "";
                                        $bb = ($row->m_masuk) ? "bold" : "";
                                        $triject = ($row->m_tolakan) ? "bold" : "";
                                        $tretur = ($row->m_retur) ? "bold" : "";
                                        $bj = ($row->k_keluar) ? "bold" : "";
                                        $kretur = ($row->k_retur) ? "bold" : "";
                                        $kriject = ($row->k_reject) ? "bold" : "";
                                        if ($row->saldo_akhir > 0) {
                                            $sakhir = "bold";
                                        } elseif ($row->saldo_akhir < 0) {
                                            $sakhir = "red_bold";
                                        } else {
                                            $sakhir = "";
                                        }
                                        if ($row->saldo_akhir_repair > 0) {
                                            $sakhirr = "bold";
                                        } elseif ($row->saldo_akhir_repair < 0) {
                                            $sakhirr = "red_bold";
                                        } else {
                                            $sakhirr = "";
                                        }

                                        $sob = ($row->so) ? "bold" : "";
                                        $sor = ($row->so_repair) ? "bold" : "";

                                        if ($row->selisih > 0) {
                                            $selisihb = "bold";
                                        } elseif ($row->selisih < 0) {
                                            $selisihb = "red_bold";
                                        } else {
                                            $selisihb = "";
                                        }

                                        if ($row->selisih_repair > 0) {
                                            $selisihr = "bold";
                                        } elseif ($row->selisih_repair < 0) {
                                            $selisihr = "red_bold";
                                        } else {
                                            $selisihr = "";
                                        }
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= wordwrap(trim($row->e_product_basename),30,"<br>\n"); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td><?= trim($row->e_brand_name); ?></td>
                                            <td><?= $row->e_bagian_name; ?></td>
                                            <td class="text-right <?= $sawal; ?>"><?= $row->saldoawal; ?></td>
                                            <td class="text-right <?= $sawalr; ?>"><?= $row->saldoawal_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $row->saldoawal + $row->saldoawal_repair; ?></td>
                                            <td class="text-right <?= $bb; ?>"><?= $row->m_masuk; ?></td>
                                            <td class="text-right <?= $triject; ?>"><?= $row->m_tolakan; ?></td>
                                            <td class="text-right <?= $tretur; ?>"><?= $row->m_retur; ?></td>
                                            <td class="text-right warna_cyan"><?= $row->m_masuk + $row->m_retur + $row->m_tolakan; ?></td>
                                            <td class="text-right <?= $bj; ?>"><?= $row->k_keluar; ?></td>
                                            <td class="text-right <?= $kretur; ?>"><?= $row->k_retur; ?></td>
                                            <td class="text-right <?= $kriject; ?>"><?= $row->k_reject; ?></td>
                                            <td class="text-right warna_cyan"><?= $row->k_retur + $row->k_keluar + $row->k_reject; ?></td>
                                            <td class="text-right <?= $sakhir; ?>"><?= $row->saldo_akhir; ?></td>
                                            <td class="text-right <?= $sakhirr; ?>"><?= $row->saldo_akhir_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $row->saldo_akhir + $row->saldo_akhir_repair; ?></td>
                                            <td class="text-right <?= $sob; ?>"><?= $row->so; ?></td>
                                            <td class="text-right <?= $sor; ?>"><?= $row->so_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $row->so + $row->so_repair; ?></td>
                                            <td class="text-right <?= $selisihb; ?>"><?= $row->selisih; ?></td>
                                            <td class="text-right <?= $selisihr; ?>"><?= $row->selisih_repair; ?></td>
                                            <td class="text-right warna_cyan"><?= $row->selisih + $row->selisih_repair; ?></td>
                                        </tr>
                                    <?php
                                        $sum_saldo_awalb += $row->saldoawal;
                                        $sum_saldo_awalr += $row->saldoawal_repair;
                                        $sum_bb_in += $row->m_masuk;
                                        $sum_bb_out += $row->k_keluar;
                                        $sum_retur_in += $row->m_retur;
                                        $sum_retur_out += $row->k_retur;
                                        $sum_reject_in += $row->m_tolakan;
                                        $sum_reject_out += $row->k_reject;
                                        $sum_saldo_akhirb += $row->saldo_akhir;
                                        $sum_saldo_akhirr += $row->saldo_akhir_repair;
                                        $sum_sob += $row->so;
                                        $sum_sor += $row->so_repair;
                                        $sum_selisihb += $row->selisih;
                                        $sum_selisihr += $row->selisih_repair;
                                    } ?>
                                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                                </tbody>
                                <!-- <tfoot>
                                    <tr>
                                        <th colspan="5" class="font_14 text-right">TOTAL</th>
                                        <th></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_awalb; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_awalr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_awalb + $sum_saldo_awalr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_reject_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_retur_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_in + $sum_reject_in + $sum_retur_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_retur_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_reject_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_bb_out + $sum_retur_out + $sum_reject_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_akhirb; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_akhirr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_saldo_akhirb + $sum_saldo_akhirr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_sob; ?></th>
                                        <th class="font_14 text-right"><?= $sum_sor; ?></th>
                                        <th class="font_14 text-right"><?= $sum_sob + $sum_sor; ?></th>
                                        <th class="font_14 text-right"><?= $sum_selisihb; ?></th>
                                        <th class="font_14 text-right"><?= $sum_selisihr; ?></th>
                                        <th class="font_14 text-right"><?= $sum_selisihb + $sum_selisihr; ?></th>
                                    </tr>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="noDisplay text-center">
                            <div class="text-center mt-4"> <button id="print" class="btn btn-info btn-outline exportToExcel" type="button"> <span><i class="fa fa-download"></i> Export</span> </button> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
<!-- <script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script> -->
<!-- <script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/js/freeze-table.js?v=1"></script>
<script src="<?= base_url(); ?>assets/js/jquery.table2excel.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/tether.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/js/tooltip.js"></script>
<script>
    $(function() {
        // var $table = $('.table');
        // $table.floatThead({
        //     responsiveContainer: function($table) {
        //         return $table.closest('.table-responsive');
        //     }
        // });
        $(".table_fixed").freezeTable({
            'columnNum': 5,
            'scrollable': true,
        });

        $(".exportToExcel").click(function(e) {
            $("#tabledata").table2excel({
                // exclude CSS class
                exclude: ".floatThead-col",
                name: "Worksheet Name",
                filename: "Report_Mutasi_UnitJahit", //do not include extension
                fileext: ".xls" // file extension
            });
        });

        $('[data-toggle="popover"]').popover();
    });
</script>