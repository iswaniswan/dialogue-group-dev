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
                                        <td width="160px" class="xx text-muted m-l-3" style="font-size: 16px"><b>Nama Bagian</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <?php if ($bagian != '') { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $bagian->i_bagian . ' - ' . $bagian->e_bagian_name; ?></b></td>
                                        <?php } else { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>Semua Pengadaan</b></td>
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
                            <table id="tabledata" class="table nowrap font-12 table-bordered table2excel" cellspacing="0">
                                <?php
                                $sum_saldo = 0;
                                $sum_in = 0;
                                $sum_inn = 0;
                                $sum_innn = 0;
                                $total_sum_in = 0;
                                $sum_out = 0;
                                $sum_oout = 0;
                                $sum_ooout = 0;
                                $total_sum_out = 0;
                                $sum_akhir_bagus = 0;
                                $sum_saldo_akhir = 0;
                                $sum_so = 0;
                                $sum_so_bagus = 0;
                                $sum_so_repair = 0;
                                $selisih = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_saldo += $key->n_saldo_awal;
                                        $sum_in += $key->n_masuk_set_bagus;
                                        $sum_inn += $key->n_masuk_set_repair;
                                        $sum_innn += $key->n_masuk_retur_jahit;
                                        $total_sum_in = $sum_in + $sum_inn + $sum_innn;
                                        $sum_out += $key->n_keluar_jahit;
                                        $sum_oout += $key->n_keluar_retur_jahit;
                                        $sum_ooout += $key->n_keluar_retur_set;
                                        $total_sum_out = $sum_out + $sum_oout + $sum_ooout;

                                        $sum_akhir_bagus = $sum_saldo + $total_sum_in - $total_sum_out;
                                        $sum_saldo_akhir += $key->n_saldo_akhir;
                                        $sum_so_bagus += $key->n_so_bagus;
                                        $selisih = $sum_saldo_akhir - $sum_so_bagus;
                                        
                                    }
                                } ?>
                                <thead>
                                    <tr>
                                        <th rowspan="4" class="text-center ">No</th>
                                        <th class="" rowspan="4">Kode</th>
                                        <th class="" rowspan="4">Nama Barang</th>
                                        <th class="" rowspan="4">Warna</th>
                                        <th rowspan="4" class="text-right ">Saldo Awal<br>Bagus</th>
                                        <!-- <th rowspan="4" class="text-right ">Saldo Awal<br>Reject</th> -->
                                        <!-- <th rowspan="4" class="text-right warna_kuning">Total<br>Saldo Awal</th> -->
                                        <th colspan="4" class="text-center font_14">MASUK</th>
                                        <th colspan="4" class="text-center font_14">KELUAR</th>
                                        <th colspan="6"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>-->Dari<br>Pengesettan</th>
                                        <th class="text-right ">Dari<br>Pengesettan</th>
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>-->Dari UNIT JAHIT</th>
                                        
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>--></th>
                                        <!-- <th class="text-right "></th> -->
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM BAGUS"></i>-->Ke<br>UNIT JAHIT</th>
                                        <!-- <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Belum Ada"></i>Makloon</th> -->
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM PERBAIKAN"></i>-->Ke<br>UNIT JAHIT</th>
                                        <!-- <th class="text-right warna_out">Pinjaman</th> -->
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM KE PENGESETAN"></i>-->Ke<br>Pengesettan</th>
                                        <th class="text-right "></th>
                                        <th colspan="6"></th>
                                    </tr>
                                    
                                    <tr>
                                        <th class="text-center font_14 ">HASIL SET</th>
                                        <th></th>
                                        <th class="text-center font_14 ">C2</th>
                                        <!-- <th class="text-center "></th> -->
                                        <th class="text-center font_14 ">HASIL<br>SET + C2</th>
                                        <th class="text-center font_14 ">C1</th>
                                        <th class="text-center font_14 ">C3</th>
                                        <th class="text-center font_14 ">Retur</th>
                                        <th class="text-center font_14 ">C1+C3+Retur</th>
                                        <th class="warna_magenta"></th>
                                        <!-- <th class="warna_magenta"></th> -->
                                        <th colspan="5"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center ">Terima HASIL<br>SET</th>
                                        <th class="text-center ">Terima HASIL<br>SET</th>
                                        <th class="text-center ">Terima Retur<br>BB<br>(UNTUK DILENGKAPI)</th>
                                        <!-- <th class="text-center "></th> -->
                                        <th class="text-center warna_kuning">Total Terima</th>
                                        <th class="text-center ">Kirim BB</th>
                                        <th class="text-center ">Kirim Retur<br>BB<br>(SUDAH DILENGKAPI)</th>
                                        <th class="text-center ">Kirim BB<br>tidak set</th>
                                        <th class="text-center warna_kuning">Total Kirim</th>
                                        <th class="text-right ">Saldo Akhir<br>Bagus</th>
                                        <!-- <th class="text-right ">Saldo Akhir<br>Retur BB</th> -->
                                        <!-- <th class="text-right warna_kuning">Total<br>Saldo Akhir</th> -->
                                        <th class="text-right ">SO<br>Bagus</th>
                                        <!-- <th class="text-right ">SO<br>Repair</th> -->
                                        <!-- <th class="text-right warna_kuning">Total<br>SO</th> -->
                                        <th class="text-right ">Selisih<br>Bagus</th>
                                        <!-- <th class="text-right ">Selisih<br>Repair</th> -->
                                        <!-- <th class="text-right warna_kuning">Total<br>Selisih</th> -->
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-center font_14">TOTAL</th>
                                        <th class="text-right font_14"><?= $sum_saldo;?></th>
                                        <!-- <th class="text-right font_14"><?= 0;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_saldo;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_bb_in;?></th> -->
                                        <th class="text-right font_14"><?= $sum_in;?></th>
                                        <th class="text-right font_14"><?= $sum_inn;?></th>
                                        <th class="text-right font_14"><?= $sum_innn;?></th>
                                        <th class="text-right font_14"><?= $total_sum_in;?></th>
                                        <th class="text-right font_14"><?= $sum_out;?></th>
                                        <th class="text-right font_14"><?= $sum_oout;?></th>
                                        <th class="text-right font_14"><?= $sum_ooout;?></th>
                                        <th class="text-right font_14"><?= $total_sum_out;?></th>
                                        <th class="text-right font_14"><?= $sum_akhir_bagus;?></th>
                                        <!-- <th class="text-right font_14"><?= 0;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_saldo_akhir;?></th> -->
                                        <th class="text-right font_14"><?= $sum_so_bagus;?></th>
                                        <!-- <th class="text-right font_14"><?= $sum_so_repair;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_so;?></th> -->
                                        <th class="text-right font_14"><?= $selisih;?></th>
                                        <!-- <th class="text-right font_14"><?= 0;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_saldo_akhir-$sum_so;?></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $gudang = '';

                                    function warna($angka) {
                                        if($angka>0){
                                            echo "bold";
                                        }
                                        else if($angka<0){
                                            echo "red_bold";
                                        }

                                      }
                                    foreach ($data2 as $row) {
                                        $i++;
                                        $sum_terima = $row->n_masuk_set_bagus + $row->n_masuk_set_repair + $row->n_masuk_retur_jahit;
                                        $sum_kirim = $row->n_keluar_jahit + $row->n_keluar_retur_jahit + $row->n_keluar_retur_set;

                                        $sum_akhir_bagus = $row->n_saldo_awal + $sum_terima - $sum_kirim;
                                        $sum_akhir_retur = 0;
                                        $sum_akhir = $sum_terima;
                                        $selisih = $sum_akhir_bagus - $row->n_so_bagus;
                                        // if($row->so == 0){
                                        //     $selisih = 0;
                                        // }

                                        // $warna1 = ($row->saldo_awal > 0) ? 'warna_saldo' : '';
                                        // $warnain1 = ($row->terima_bb > 0) ? 'warna_in' : '';
                                        // $warnain2 = ($row->terima_repair > 0) ? 'warna_in' : '';
                                        // $warnain3 = ($row->terima_reject > 0) ? 'warna_in' : '';
                                        // $warnain4 = ($sum_terima > 0) ? 'warna_in' : '';
                                        // $warnaout1 = ($row->kirim_bj > 0) ? 'warna_out' : '';
                                        // $warnaout2 = ($row->kirim_repair > 0) ? 'warna_out' : '';
                                        // $warnaout3 = ($row->kirim_reject > 0) ? 'warna_out' : '';
                                        // $warnaout4 = ($sum_kirim > 0) ? 'warna_out' : '';
                                        // $warnasa = ($sum_akhir < 0) ? 'warna_saldo_akhir' : '';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= trim($row->e_product_wipname); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td class="text-right <?= warna($row->n_saldo_awal); ?>"><?= $row->n_saldo_awal ?></td>
                                            <!-- <td class="text-right ">0</td> -->
                                            <!-- <td class="text-right warna_cyan"><?= $row->saldo_awal; ?></td> -->
                                            <td class="text-right <?php warna($row->n_masuk_set_bagus); ?>"><?= $row->n_masuk_set_bagus; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_set_repair); ?>"><?= $row->n_masuk_set_repair; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_retur_jahit); ?>"><?= $row->n_masuk_retur_jahit; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_terima; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_jahit); ?>"><?= $row->n_keluar_jahit; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_retur_jahit); ?>"><?= $row->n_keluar_retur_jahit; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_retur_set); ?>"><?= $row->n_keluar_retur_set; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_kirim; ?></td>
                                            <th class="text-right <?php warna($sum_akhir_bagus); ?>"><?= $sum_akhir_bagus;?></th>
                                            <!-- <th class="text-right <?php warna($sum_akhir_retur); ?>"><?= $sum_akhir_retur;?></th> -->
                                            <!-- <td class="text-right warna_cyan"><?= $row->n_saldo_akhir; ?></td> -->
                                            <th class="text-right <?php warna($row->n_so_bagus); ?>"><?= $row->n_so_bagus;?></th>
                                            <!-- <th class="text-right <?php warna($row->n_so_repair); ?>"><?= $row->n_so_repair;?></th> -->
                                            <!-- <td class="text-right warna_cyan"><?= 0; ?></td> -->
                                            <th class="text-right <?php warna($selisih); ?>"><?= $selisih;?></th>
                                            <!-- <th class="text-right "></th> -->
                                            <!-- <th class="text-right "></th> -->
                                        </tr>

                                        
                                    <?php  } ?>
                                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">

                                    
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div hidden="true">
                        <table id="datatable">
                        <?php
                                $sum_saldo = 0;
                                $sum_in = 0;
                                $sum_inn = 0;
                                $sum_innn = 0;
                                $total_sum_in = 0;
                                $sum_out = 0;
                                $sum_oout = 0;
                                $sum_ooout = 0;
                                $total_sum_out = 0;
                                $sum_akhir_bagus = 0;
                                $sum_saldo_akhir = 0;
                                $sum_so = 0;
                                $sum_so_bagus = 0;
                                $sum_so_repair = 0;
                                $selisih = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_saldo += $key->n_saldo_awal;
                                        $sum_in += $key->n_masuk_set_bagus;
                                        $sum_inn += $key->n_masuk_set_repair;
                                        $sum_innn += $key->n_masuk_retur_jahit;
                                        $total_sum_in = $sum_in + $sum_inn + $sum_innn;
                                        $sum_out += $key->n_keluar_jahit;
                                        $sum_oout += $key->n_keluar_retur_jahit;
                                        $sum_ooout += $key->n_keluar_retur_set;
                                        $total_sum_out = $sum_out + $sum_oout + $sum_ooout;
                                        $sum_akhir_bagus = $sum_saldo + $total_sum_in;
                                        $sum_saldo_akhir += $key->n_saldo_akhir;
                                        $sum_so_bagus += $key->n_so_bagus;
                                        $selisih = $sum_saldo_akhir - $sum_so_bagus;
                                        
                                    }
                                } ?>
                                <thead>
                                    <tr>
                                        <th rowspan="4" class="text-center ">No</th>
                                        <th class="" rowspan="4">Kode</th>
                                        <th class="" rowspan="4">Nama Barang</th>
                                        <th class="" rowspan="4">Warna</th>
                                        <th rowspan="4" class="text-right ">Saldo Awal<br>Bagus</th>
                                        <!-- <th rowspan="4" class="text-right ">Saldo Awal<br>Reject</th> -->
                                        <!-- <th rowspan="4" class="text-right warna_kuning">Total<br>Saldo Awal</th> -->
                                        <th colspan="4" class="text-center font_14">MASUK</th>
                                        <th colspan="4" class="text-center font_14">KELUAR</th>
                                        <th colspan="6"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>-->Dari<br>Pengesettan</th>
                                        <th class="text-right ">Dari<br>Pengesettan</th>
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>-->Dari UNIT JAHIT</th>
                                        
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="- - -"></i>--></th>
                                        <!-- <th class="text-right "></th> -->
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM BAGUS"></i>-->Ke<br>UNIT JAHIT</th>
                                        <!-- <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Belum Ada"></i>Makloon</th> -->
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM PERBAIKAN"></i>-->Ke<br>UNIT JAHIT</th>
                                        <!-- <th class="text-right warna_out">Pinjaman</th> -->
                                        <th class="text-center "><!--<i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM KE PENGESETAN"></i>-->Ke<br>Pengesettan</th>
                                        <th class="text-right "></th>
                                        <th colspan="6"></th>
                                    </tr>
                                    
                                    <tr>
                                        <th class="text-center font_14 ">HASIL SET</th>
                                        <th></th>
                                        <th class="text-center font_14 ">C2</th>
                                        <!-- <th class="text-center "></th> -->
                                        <th class="text-center font_14 ">HASIL<br>SET + C2</th>
                                        <th class="text-center font_14 ">C1</th>
                                        <th class="text-center font_14 ">C3</th>
                                        <th class="text-center font_14 ">Retur</th>
                                        <th class="text-center font_14 ">C1+C3+Retur</th>
                                        <th class="warna_magenta"></th>
                                        <!-- <th class="warna_magenta"></th> -->
                                        <th colspan="5"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center ">Terima HASIL<br>SET</th>
                                        <th class="text-center ">Terima HASIL<br>SET</th>
                                        <th class="text-center ">Terima Retur<br>BB<br>(UNTUK DILENGKAPI)</th>
                                        <!-- <th class="text-center "></th> -->
                                        <th class="text-center warna_kuning">Total Terima</th>
                                        <th class="text-center ">Kirim BB</th>
                                        <th class="text-center ">Kirim Retur<br>BB<br>(SUDAH DILENGKAPI)</th>
                                        <th class="text-center ">Kirim BB<br>tidak set</th>
                                        <th class="text-center warna_kuning">Total Kirim</th>
                                        <th class="text-right ">Saldo Akhir<br>Bagus</th>
                                        <!-- <th class="text-right ">Saldo Akhir<br>Retur BB</th> -->
                                        <!-- <th class="text-right warna_kuning">Total<br>Saldo Akhir</th> -->
                                        <th class="text-right ">SO<br>Bagus</th>
                                        <!-- <th class="text-right ">SO<br>Repair</th> -->
                                        <!-- <th class="text-right warna_kuning">Total<br>SO</th> -->
                                        <th class="text-right ">Selisih<br>Bagus</th>
                                        <!-- <th class="text-right ">Selisih<br>Repair</th> -->
                                        <!-- <th class="text-right warna_kuning">Total<br>Selisih</th> -->
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-center font_14">TOTAL</th>
                                        <th class="text-right font_14"><?= $sum_saldo;?></th>
                                        <!-- <th class="text-right font_14"><?= 0;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_saldo;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_bb_in;?></th> -->
                                        <th class="text-right font_14"><?= $sum_in;?></th>
                                        <th class="text-right font_14"><?= $sum_inn;?></th>
                                        <th class="text-right font_14"><?= $sum_innn;?></th>
                                        <th class="text-right font_14"><?= $total_sum_in;?></th>
                                        <th class="text-right font_14"><?= $sum_out;?></th>
                                        <th class="text-right font_14"><?= $sum_oout;?></th>
                                        <th class="text-right font_14"><?= $sum_ooout;?></th>
                                        <th class="text-right font_14"><?= $total_sum_out;?></th>
                                        <th class="text-right font_14"><?= $sum_akhir_bagus;?></th>
                                        <!-- <th class="text-right font_14"><?= 0;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_saldo_akhir;?></th> -->
                                        <th class="text-right font_14"><?= $sum_so_bagus;?></th>
                                        <!-- <th class="text-right font_14"><?= $sum_so_repair;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_so;?></th> -->
                                        <th class="text-right font_14"><?= $selisih;?></th>
                                        <!-- <th class="text-right font_14"><?= 0;?></th> -->
                                        <!-- <th class="text-right font_14"><?= $sum_saldo_akhir-$sum_so;?></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $gudang = '';
                                    
                                    foreach ($data2 as $row) {
                                        $i++;
                                        $sum_terima = $row->n_masuk_set_bagus + $row->n_masuk_set_repair + $row->n_masuk_retur_jahit;
                                        $sum_kirim = $row->n_keluar_jahit + $row->n_keluar_retur_jahit + $row->n_keluar_retur_set;
                                        $sum_akhir_bagus = $row->n_saldo_awal + $sum_terima;
                                        $sum_akhir_retur = 0;
                                        $sum_akhir = $sum_terima;
                                        $selisih = $sum_akhir_bagus - $row->n_so_bagus;
                                        // if($row->so == 0){
                                        //     $selisih = 0;
                                        // }

                                        // $warna1 = ($row->saldo_awal > 0) ? 'warna_saldo' : '';
                                        // $warnain1 = ($row->terima_bb > 0) ? 'warna_in' : '';
                                        // $warnain2 = ($row->terima_repair > 0) ? 'warna_in' : '';
                                        // $warnain3 = ($row->terima_reject > 0) ? 'warna_in' : '';
                                        // $warnain4 = ($sum_terima > 0) ? 'warna_in' : '';
                                        // $warnaout1 = ($row->kirim_bj > 0) ? 'warna_out' : '';
                                        // $warnaout2 = ($row->kirim_repair > 0) ? 'warna_out' : '';
                                        // $warnaout3 = ($row->kirim_reject > 0) ? 'warna_out' : '';
                                        // $warnaout4 = ($sum_kirim > 0) ? 'warna_out' : '';
                                        // $warnasa = ($sum_akhir < 0) ? 'warna_saldo_akhir' : '';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= trim($row->e_product_wipname); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td class="text-right <?= warna($row->n_saldo_awal); ?>"><?= $row->n_saldo_awal ?></td>
                                            <!-- <td class="text-right ">0</td> -->
                                            <!-- <td class="text-right warna_cyan"><?= $row->saldo_awal; ?></td> -->
                                            <td class="text-right <?php warna($row->n_masuk_set_bagus); ?>"><?= $row->n_masuk_set_bagus; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_set_repair); ?>"><?= $row->n_masuk_set_repair; ?></td>
                                            <td class="text-right <?php warna($row->n_masuk_retur_jahit); ?>"><?= $row->n_masuk_retur_jahit; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_terima; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_jahit); ?>"><?= $row->n_keluar_jahit; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_retur_jahit); ?>"><?= $row->n_keluar_retur_jahit; ?></td>
                                            <td class="text-right <?php warna($row->n_keluar_retur_set); ?>"><?= $row->n_keluar_retur_set; ?></td>
                                            <td class="text-right warna_cyan"><?= $sum_kirim; ?></td>
                                            <th class="text-right <?php warna($sum_akhir_bagus); ?>"><?= $sum_akhir_bagus;?></th>
                                            <!-- <th class="text-right <?php warna($sum_akhir_retur); ?>"><?= $sum_akhir_retur;?></th> -->
                                            <!-- <td class="text-right warna_cyan"><?= $row->n_saldo_akhir; ?></td> -->
                                            <th class="text-right <?php warna($row->n_so_bagus); ?>"><?= $row->n_so_bagus;?></th>
                                            <!-- <th class="text-right <?php warna($row->n_so_repair); ?>"><?= $row->n_so_repair;?></th> -->
                                            <!-- <td class="text-right warna_cyan"><?= 0; ?></td> -->
                                            <th class="text-right <?php warna($selisih); ?>"><?= $selisih;?></th>
                                            <!-- <th class="text-right "></th> -->
                                            <!-- <th class="text-right "></th> -->
                                        </tr>

                                        
                                    <?php  } ?>
                                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">

                                    
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