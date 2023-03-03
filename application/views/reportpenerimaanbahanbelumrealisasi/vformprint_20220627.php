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
    <link href="<?= base_url(); ?>assets/css/excel/excel-2007.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.sheetjs.css" />
    <!-- color CSS -->
    <link href="<?= base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
</head>
<style>
    .ExcelTable2007 {
        white-space: nowrap !important;
    }

    .bold {
        font-weight: bold !important;
        color: black;
        /* color: #504c4c; */
    }

    .heading {
        color: #504c4c !important;
    }

    .red_bold {
        font-weight: bold !important;
        color: #bd0000;
    }

    .font_14 {
        font-size: 14px !important;
        font-weight: bold !important;
    }

    .repair{
        background-color: #F9E79F !important;
    }
    .bagus{
        background-color: #e4ecf7 !important;
    }
</style>

<body>
    <div class="white-box printableArea">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- <h3 align="center" style="font-size: 16px; line-height: 0px;"><b>Laporan Mutasi</b><br></h3> -->
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_fixed" style="width: 100%; max-height: 600px;">
                            <table id="TheTable" border="1" class="ExcelTable2007 sheetjs">
                                <thead>
                                    <!-- <tr>
                                        <th class="heading">&nbsp;</th>
                                        <th class="text-center bold">A</th>
                                        <th class="text-center bold">B</th>
                                        <th class="text-center bold">C</th>
                                        <th class="text-center bold">D</th>
                                        <th class="text-center bold">E</th>
                                        <th class="text-center bold">F</th>
                                        <th class="text-center bold">G</th>
                                        <th class="text-center bold">H</th>
                                        <th class="text-center bold">I</th>
                                        <th class="text-center bold">J</th>
                                        <th class="text-center bold">K</th>
                                        <th class="text-center bold">L</th>
                                        <th class="text-center bold">M</th>
                                        <th class="text-center bold">N</th>
                                        <th class="text-center bold">O</th>
                                        <th class="text-center bold">P</th>
                                        <th class="text-center bold">Q</th>
                                        <th class="text-center bold">R</th>
                                        <th class="text-center bold">S</th>
                                        <th class="text-center bold">T</th>
                                        <th class="text-center bold">U</th>
                                        <th class="text-center bold">V</th>
                                        <th class="text-center bold">W</th>
                                        <th class="text-center bold">X</th>
                                        <th class="text-center bold">Y</th>
                                    </tr> -->
                                    <tr>
                                        <td class="heading"></td>
                                        <td colspan="6"></td>
                                        <td colspan="5" class="heading text-center font_14">MASUK</td>
                                        <td colspan="5" class="heading text-center font_14">KELUAR</td>
                                        <td colspan="9"></td>
                                    </tr>
                                    <tr>
                                        <td class="heading"></td>
                                        <td colspan="6"></td>
                                        <td class="heading text-center font_14">Dari <br>Unit Jahit</td>
                                        <td class="heading text-center font_14">Dari <br>Unit Jahit</td>
                                        <td class="heading text-center font_14">Dari <br>Gudang Jadi</td>
                                        <td class="heading text-center font_14">Dari <br>Packing</td>
                                        <td class="heading"></td>
                                        <td class="heading text-center font_14">Ke <br>Packing</td>
                                        <td class="heading text-center font_14">Ke <br>Gudang Jadi</td>
                                        <td class="heading text-center font_14">Ke <br>Gudang Jadi</td>
                                        <td class="heading text-center font_14">Ke <br>Unit Jahit</td>
                                        <td class="heading text-center font_14"></td>
                                        <td colspan="9"></td>
                                    </tr>
                                    <tr>
                                        <td class="heading"></td>
                                        <td colspan="3"></td>
                                        <td class="heading text-center font_14">H1</td>
                                        <td class="heading text-center font_14">H2</td>
                                        <td class="heading text-center font_14">H</td>
                                        <td class="heading text-center font_14">D</td>
                                        <td class="heading text-center font_14">Y</td>
                                        <td class="heading text-center font_14">XXX</td>
                                        <td class="heading text-center font_14">XX</td>
                                        <td class="heading"></td>
                                        <td class="heading text-center font_14">G</td>
                                        <td class="heading text-center font_14">GG</td>
                                        <td class="heading text-center font_14">Z2</td>
                                        <td class="heading text-center font_14">X</td>
                                        <td class="heading"></td>
                                        <td class="heading text-center font_14">H1 + D + Y - G</td>
                                        <td class="heading text-center font_14">H2 + XXX + XX - GG  - Z2 - X</td>
                                        <td class="heading text-center font_14">I</td>
                                        <td colspan="6"></td>
                                    </tr>
                                    <tr>
                                        <th class="bagus"></th>
                                        <th class="text-center bagus">Kode</th>
                                        <th class="text-center bagus">Nama Barang</th>
                                        <th class="text-center bagus">Warna</th>
                                        <th class="text-center bagus">Saldo Awal<br>Bagus</th>
                                        <th class="text-center repair">Saldo Awal<br>Repair</th>
                                        <th class="text-center bagus">Total<br>Saldo Awal</th>
                                        <th class="text-center bagus">Terima<br>BJ</th>
                                        <th class="text-center bagus">Terima Repair<br>(HASIL PERBAIKAN)</th>
                                        <th class="text-center repair">Terima Repair<br>(UNTUK DIPERBAIKI)</th>
                                        <th class="text-center repair">Terima Repair<br>(UNTUK DIPERBAIKI)</th>
                                        <th class="text-center bagus">Total<br>Terima</th>
                                        <th class="text-center bagus">Kirim untuk<br>di packing bagus</th>
                                        <th class="text-center repair">Kirim Pengembalian Repair<br>(HASIL PERBAIKAN)</th>
                                        <th class="text-center repair">Kirim Grade B <br>(FINAL)</th>
                                        <th class="text-center repair">Kirim Repair<br>(UNTUK DIPERBAIKI)</th>
                                        <th class="text-center bagus">Total<br>Kirim</th>
                                        <th class="text-center bagus">Saldo Akhir<br>Bagus</th>
                                        <th class="text-center repair">Saldo Akhir<br>Repair</th>
                                        <th class="text-center bagus">Total<br>Saldo Akhir</th>
                                        <th class="text-center bagus">Stock Opname<br>Bagus</th>
                                        <th class="text-center repair">Stock Opname<br>Repair</th>
                                        <th class="text-center bagus">Total<br>Stock Opname</th>
                                        <th class="text-center bagus">Selisih Bagus</th>
                                        <th class="text-center repair">Selisih Repair</th>
                                        <th class="text-center bagus">Total Selisih</th>
                                    </tr>
                                    <?php
                                    $sum_n_saldo_awal = 0;
                                    $sum_n_saldo_awal_repair = 0;
                                    $sum_n_saldo_awal_total = 0;
                                    $sum_n_masuk_1 = 0;
                                    $sum_n_masuk_2 = 0;
                                    $sum_n_masuk_3 = 0;
                                    $sum_n_masuk_4 = 0;
                                    $sum_n_masuk_total = 0;
                                    $sum_n_keluar_1 = 0;
                                    $sum_n_keluar_2 = 0;
                                    $sum_n_keluar_3 = 0;
                                    $sum_n_keluar_4 = 0;
                                    $sum_n_keluar_total = 0;
                                    $sum_n_saldo_akhir = 0;
                                    $sum_n_saldo_akhir_repair = 0;
                                    $sum_n_saldo_akhir_total = 0;
                                    $sum_n_so = 0;
                                    $sum_n_so_repair = 0;
                                    $sum_n_so_total = 0;
                                    $sum_n_selisih = 0;
                                    $sum_n_selisih_repair = 0;
                                    $sum_n_selisih_total = 0;
                                    if ($data2) {
                                        foreach ($data2 as $key) {
                                            $sum_n_saldo_awal += $key->n_saldo_awal;
                                            $sum_n_saldo_awal_repair += $key->n_saldo_awal_repair;
                                            $sum_n_saldo_awal_total += $key->n_saldo_awal_total;
                                            $sum_n_masuk_1 += $key->n_masuk_1;
                                            $sum_n_masuk_2 += $key->n_masuk_2;
                                            $sum_n_masuk_3 += $key->n_masuk_3;
                                            $sum_n_masuk_4 += $key->n_masuk_4;
                                            $sum_n_masuk_total += $key->n_masuk_total;
                                            $sum_n_keluar_1 += $key->n_keluar_1;
                                            $sum_n_keluar_2 += $key->n_keluar_2;
                                            $sum_n_keluar_3 += $key->n_keluar_3;
                                            $sum_n_keluar_4 += $key->n_keluar_4;
                                            $sum_n_keluar_total += $key->n_keluar_total;
                                            $sum_n_saldo_akhir += $key->n_saldo_akhir;
                                            $sum_n_saldo_akhir_repair += $key->n_saldo_akhir_repair;
                                            $sum_n_saldo_akhir_total += $key->n_saldo_akhir_total;
                                            $sum_n_so += $key->n_so;
                                            $sum_n_so_repair += $key->n_so_repair;
                                            $sum_n_so_total += $key->n_so_total;
                                            $sum_n_selisih += $key->n_selisih;
                                            $sum_n_selisih_repair += $key->n_selisih_repair;
                                            $sum_n_selisih_total += $key->n_selisih_total;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="heading"></td>
                                        <td class="heading text-center" colspan="3">TOTAL</td>
                                        <td class="heading text-right <?php warna($sum_n_saldo_awal); ?>"><?= $sum_n_saldo_awal; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_saldo_awal_repair); ?>"><?= $sum_n_saldo_awal_repair; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_saldo_awal_total); ?>"><?= $sum_n_saldo_awal_total; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_masuk_1); ?>"><?= $sum_n_masuk_1; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_masuk_2); ?>"><?= $sum_n_masuk_2; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_masuk_3); ?>"><?= $sum_n_masuk_3; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_masuk_4); ?>"><?= $sum_n_masuk_4; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_masuk_total); ?>"><?= $sum_n_masuk_total; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_keluar_1); ?>"><?= $sum_n_keluar_1; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_keluar_2); ?>"><?= $sum_n_keluar_2; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_keluar_3); ?>"><?= $sum_n_keluar_3; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_keluar_4); ?>"><?= $sum_n_keluar_4; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_keluar_total); ?>"><?= $sum_n_keluar_total; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_saldo_akhir); ?>"><?= $sum_n_saldo_akhir; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_saldo_akhir_repair); ?>"><?= $sum_n_saldo_akhir_repair; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_saldo_akhir_total); ?>"><?= $sum_n_saldo_akhir_total; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_so); ?>"><?= $sum_n_so; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_so_repair); ?>"><?= $sum_n_so_repair; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_so_total); ?>"><?= $sum_n_so_total; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_selisih); ?>"><?= $sum_n_selisih; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_selisih_repair); ?>"><?= $sum_n_selisih_repair; ?></td>
                                        <td class="heading text-right <?php warna($sum_n_selisih_total); ?>"><?= $sum_n_selisih_total; ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    /* function warna($angka)
                                    {
                                        if ($angka > 0) {
                                            echo "bold";
                                        } else if ($angka < 0) {
                                            echo "red_bold";
                                        }
                                    } */
                                    if ($data2) {
                                        foreach ($data2 as $row) {
                                            $i++;

                                    ?>
                                            <tr>
                                                <td align="center" valign="bottom" class="heading"><?= $i; ?></td>
                                                <td align="left" valign="bottom"><?= $row->i_product_wip; ?></td>
                                                <td align="left" valign="bottom"><?= trim($row->e_product_basename); ?></td>
                                                <td align="left" valign="bottom"><?= $row->e_color_name; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_saldo_awal); ?>"><?= $row->n_saldo_awal; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_saldo_awal_repair); ?>"><?= $row->n_saldo_awal_repair; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_saldo_awal_total); ?>"><?= $row->n_saldo_awal_total; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_masuk_1); ?>"><?= $row->n_masuk_1; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_masuk_2); ?>"><?= $row->n_masuk_2; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_masuk_3); ?>"><?= $row->n_masuk_3; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_masuk_4); ?>"><?= $row->n_masuk_4; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_masuk_total); ?>"><?= $row->n_masuk_total; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_keluar_1); ?>"><?= $row->n_keluar_1; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_keluar_2); ?>"><?= $row->n_keluar_2; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_keluar_3); ?>"><?= $row->n_keluar_3; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_keluar_4); ?>"><?= $row->n_keluar_4; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_keluar_total); ?>"><?= $row->n_keluar_total; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_saldo_akhir); ?>"><?= $row->n_saldo_akhir; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_saldo_akhir_repair); ?>"><?= $row->n_saldo_akhir_repair; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_saldo_akhir_total); ?>"><?= $row->n_saldo_akhir_total; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_so); ?>"><?= $row->n_so; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_so_repair); ?>"><?= $row->n_so_repair; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_so_total); ?>"><?= $row->n_so_total; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_selisih); ?>"><?= $row->n_selisih; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_selisih_repair); ?>"><?= $row->n_selisih_repair; ?></td>
                                                <td align="right" valign="bottom" class="<?php warna($row->n_selisih_total); ?>"><?= $row->n_selisih_total; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="noDisplay">
                    <div class="text-center mt-4"> <button id="print" class="btn btn-info btn-outline exportToExcel" type="button"> <span><i class="fa fa-download"></i> Export</span> </button> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.sheetjs.js"></script>
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
            $(".ExcelTable2007").table2excel({
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