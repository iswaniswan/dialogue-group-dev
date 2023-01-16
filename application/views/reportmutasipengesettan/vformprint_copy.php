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
                                        <?php if ($i_bagian != '') { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $bagian->i_bagian . ' - ' . $bagian->e_bagian_name; ?></b></td>
                                        <?php } else { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>Semua Bagian</b></td>
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
                                $sum_kebutuhan = 0;
                                $sum_awal_bagus = 0;
                                $sum_in1 = 0;
                                $sum_in2 = 0;
                                $sum_in3 = 0;
                                $sum_in = 0;
                                $sum_out1 = 0;
                                $sum_out2 = 0;
                                $sum_out = 0;
                                $sum_akhir_bagus = 0;
                                $sum_so_bagus = 0;
                                $selisih = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_kebutuhan += $key->n_qty_penyusun;
                                        $sum_awal_bagus += $key->n_saldo_awal;
                                        $sum_in1 += $key->n_masuk_cutting_bagus;
                                        $sum_in2 += $key->n_masuk_retur_pengadaan;
                                        $sum_in3 += $key->n_masuk_cutting_repair;
                                        $sum_in += $key->n_masuk_cutting_bagus + $key->n_masuk_retur_pengadaan + $key->n_masuk_cutting_repair;
                                        $sum_out1 += $key->n_keluar_cutting_baru;
                                        $sum_out2 += $key->n_keluar_cutting_ganti;
                                        $sum_out += $key->n_keluar_cutting_baru + $key->n_keluar_cutting_ganti;
                                        $sum_akhir_bagus += $key->n_saldo_akhir;
                                        $sum_so_bagus += $key->n_so_bagus;
                                        $selisih += $key->n_saldo_akhir - $key->n_so_bagus;
                                    }
                                } ?>
                                <thead>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="2"></th>
                                        <th colspan="4" class="text-center font_14">MASUK</th>
                                        <th colspan="3" class="text-center font_14">KELUAR</th>
                                        <th colspan="9"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th class="text-center font_14 warna_magenta">MUTASI<br>PENGESETAN</th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th class="text-center">Saldo Awal</th>
                                        <th class="text-center">Dari<br>Cutting</th>
                                        <th class="text-center">Dari<br>Pengadaan</th>
                                        <th class="text-center">Dari Cutting<br>(Pihak Pengesettan)</th>
                                        <th></th>
                                        <th class="text-center">Ke<br>Pengadaan</th>
                                        <th class="text-center">Ke<br>Pengadaan</th>
                                        <th></th>
                                        <th class="text-center">Saldo Akhir</th>
                                        <th class="text-center">Stock Opname</th>
                                        <th class="text-center">Selisih</th>
                                    </tr>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="1"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center">Kain Potongan<br>Untuk Melengkapi</th>
                                        <th></th>
                                        <th class="text-center">(angka harus sama)</th>
                                        <th class="text-center">(angka harus sama)</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th colspan="1"></th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14">BBM</th>
                                        <th class="text-center font_14">BBMR</th>
                                        <th class="text-center font_14">BBM</th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14">STB</th>
                                        <th class="text-center font_14">STB</th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14"></th>
                                        <th class="text-center font_14"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Warna</th>
                                        <th>Brand</th>
                                        <th>Kode Panel</th>
                                        <th>Bagian</th>
                                        <th>Jenis Kain</th>
                                        <th>Kebutuhan</th>
                                        <th class="text-center">Bagus</th>
                                        <th class="text-center">Terima Semua<br>Hasil Cutting</th>
                                        <th class="text-center">Terima BB<br>(Tidak Lengkap)</th>
                                        <th class="text-center">Terima BB<br>(Untuk Melengkapi)</th>
                                        <th class="text-right warna_kuning">Total Terima</th>
                                        <th class="text-center">Kirim Hasil<br>Set Lengkap<br>(Baru)</th>
                                        <th class="text-center">Kirim Hasil<br>Set Lengkap<br>(Pengganti)</th>
                                        <th class="text-right warna_kuning">Total Kirim</th>
                                        <th class="text-right">Bagus</th>
                                        <th class="text-right">SO Bagus</th>
                                        <th class="text-right">Selisih Bagus</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="font_14 text-right">TOTAL</th>
                                        <th></th>
                                        <th class="font_14 text-right"></th>
                                        <th class="font_14 text-right"></th>
                                        <th class="font_14 text-right"><?= $sum_kebutuhan; ?></th>
                                        <th class="font_14 text-right"><?= $sum_awal_bagus; ?></th>
                                        <th class="font_14 text-right"><?= $sum_in1; ?></th>
                                        <th class="font_14 text-right"><?= $sum_in2; ?></th>
                                        <th class="font_14 text-right"><?= $sum_in3; ?></th>
                                        <th class="font_14 text-right"><?= $sum_in; ?></th>
                                        <th class="font_14 text-right"><?= $sum_out1; ?></th>
                                        <th class="font_14 text-right"><?= $sum_out2; ?></th>
                                        <th class="font_14 text-right"><?= $sum_out; ?></th>
                                        <th class="font_14 text-right"><?= $sum_akhir_bagus; ?></th>
                                        <th class="font_14 text-right"><?= $sum_so_bagus; ?></th>
                                        <th class="font_14 text-right"><?= $selisih; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;

                                    // function warna($angka) {
                                    //     if($angka>0){
                                    //         echo "bold";
                                    //     }
                                    //     else if($angka<0){
                                    //         echo "red_bold";
                                    //     }

                                    // }
                                    
                                    $gudang = '';
                                    foreach ($data2 as $row) {
                                        $i++;

                                        $sum_in = $row->n_masuk_cutting_bagus + $row->n_masuk_retur_pengadaan + $row->n_masuk_cutting_repair;
                                        $sum_out = $row->n_keluar_cutting_baru + $row->n_keluar_cutting_ganti;
                                        $selisih = $row->n_saldo_akhir - $row->n_so_bagus;
                                        
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= wordwrap(trim($row->e_product_basename),30,"<br>\n"); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td><?= trim($row->e_brand_name); ?></td>
                                            <td><?= $row->i_panel; ?></td>
                                            <td><?= $row->bagian; ?></td>
                                            <td><?= $row->e_material_name; ?></td>
                                            <td><?= $row->n_qty_penyusun; ?></td>
                                            <td class="text-right <?= warna($row->n_saldo_awal); ?>"><?= $row->n_saldo_awal; ?></td>
                                            
                                            <td class="text-right <?= warna($row->n_masuk_cutting_bagus); ?>"><?= $row->n_masuk_cutting_bagus; ?></td>
                                            <td class="text-right <?= warna($row->n_masuk_retur_pengadaan); ?>"><?= $row->n_masuk_retur_pengadaan; ?></td>
                                            <td class="text-right <?= warna($row->n_masuk_cutting_repair); ?>"><?= $row->n_masuk_cutting_repair; ?></td>
                                            <td class="text-right warna_cyan <?= warna($sum_in); ?>"><?= $sum_in; ?></td>
                                            <td class="text-right <?= warna($row->n_keluar_cutting_baru); ?>"><?= $row->n_keluar_cutting_baru; ?></td>
                                            <td class="text-right <?= warna($row->n_keluar_cutting_ganti); ?>"><?= $row->n_keluar_cutting_ganti; ?></td>
                                            <td class="text-right warna_cyan <?= warna($sum_out); ?>"><?= $sum_out; ?></td>
                                            <td class="text-right <?= warna($row->n_saldo_akhir); ?>"><?= $row->n_saldo_akhir; ?></td>
                                            <td class="text-right <?= warna($row->n_so_bagus); ?>"><?= $row->n_so_bagus; ?></td>
                                            <td class="text-right warna_cyan <?= warna($selisih); ?>"><?= $selisih; ?></td>
                                        </tr>
                                    <?php
                                        
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