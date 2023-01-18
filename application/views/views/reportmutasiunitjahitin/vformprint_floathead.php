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
                        <div class="table-responsive">
                            <table id="tabledata" class="table nowrap font-12 table-bordered table2excel" cellspacing="0">
                                <?php
                                $sum_saldo = 0;
                                $sum_bb_in = 0;
                                $sum_bb_out = 0;
                                $sum_saldo_akhir = 0;
                                $sum_so = 0;
                                if ($data2) {
                                    foreach ($data2 as $key) {
                                        $sum_saldo += $key->saldoawal;
                                        $sum_bb_in += $key->m_masuk;
                                        $sum_bb_out += $key->k_keluar;
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
                                        <th class="text-white" rowspan="2">Unit Jahit</th>
                                        <th rowspan="2" class="text-right text-white">Saldo Awal</th>
                                        <th colspan="4" class="text-center text-white">Masuk</th>
                                        <th colspan="4" class="text-center text-white">Keluar</th>
                                        <th rowspan="2" class="text-right text-white">Saldo Akhir</th>
                                        <th rowspan="2" class="text-right text-white">SO</th>
                                        <th rowspan="2" class="text-right text-white">Selisih</th>
                                    </tr>
                                    <tr class="warna_table">
                                        <th class="text-right warna_in"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="TERIMA BAHAN BAKU (C1) DARI PENGADAAN"></i>Terima BB</th>
                                        <th class="text-right warna_in"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="TERIMA RETUR REPAIR (X) UNTUK DIPERBAIKI (BERUPA BARANG JADI) DARI WIP"></i>Terima Repair</th>
                                        <!-- <th class="text-right text-white">Pinjaman</th> -->
                                        <th class="text-right warna_in"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="TERIMA RETUR REJECT (C3) SUDAH LENGKAP (BERUPA BAHAN BAKU NGESET) DARI PENGADAAN"></i>Terima Reject</th>
                                        <th class="text-right warna_in">Total Terima</th>
                                        <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM BARANG JADI (D) KE WIP"></i>Kirim BJ</th>
                                        <!-- <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Belum Ada"></i>Makloon</th> -->
                                        <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM RETUR REPAIR (Y) HASIL PERBAIKAN (BERUPA BARANG JADI) KE WIP"></i>Kirim Repair</th>
                                        <!-- <th class="text-right warna_out">Pinjaman</th> -->
                                        <th class="text-right warna_out"><i class="fa fa-question-circle fa-lg mr-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="KIRIM RETUR REJECT (C2) UNTUK DILENGKAPI (BERUPA BAHAN BAKU NGESET) KE PENGADAAN"></i>Kirim Reject</th>
                                        <th class="text-right warna_out">Total Kirim</th>
                                    </tr>
                                    <tr class="warna_table">
                                        <th colspan="5" class="text-center text-white">TOTAL</th>
                                        <th class="text-right text-white"><?= $sum_saldo;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_in;?></th>
                                        <th class="text-right text-white"><?= 0;?></th>
                                        <th class="text-right text-white"><?= 0;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_in;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_out;?></th>
                                        <th class="text-right text-white"><?= 0;?></th>
                                        <th class="text-right text-white"><?= 0;?></th>
                                        <th class="text-right text-white"><?= $sum_bb_out;?></th>
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
                                        $warna1 = ($row->saldoawal > 0) ? 'warna_saldo' : '';
                                        $warna2 = ($row->m_masuk > 0) ? 'warna_in' : '';
                                        $warna3 = ($row->k_keluar > 0) ? 'warna_out' : '';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_product_wip; ?></td>
                                            <td><?= trim($row->e_product_basename); ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td><?= $row->e_bagian_name; ?></td>
                                            <td class="text-right <?= $warna1; ?>"><?= $row->saldoawal; ?></td>
                                            <td class="text-right <?= $warna2; ?>"><?= $row->m_masuk; ?></td>
                                            <!-- <td class="text-right"><?= 0; ?></td> -->
                                            <td class="text-right"><?= 0; ?></td>
                                            <td class="text-right"><?= 0; ?></td>
                                            <td class="text-right <?= $warna2; ?>"><?= $row->m_masuk; ?></td>
                                            <td class="text-right <?= $warna3; ?>"><?= $row->k_keluar; ?></td>
                                            <td class="text-right"><?= 0; ?></td>
                                            <!-- <td class="text-right"><?= 0; ?></td> -->
                                            <!-- <td class="text-right"><?= 0; ?></td> -->
                                            <td class="text-right"><?= 0; ?></td>
                                            <td class="text-right <?= $warna3; ?>"><?= $row->k_keluar; ?></td>
                                            <td class="text-right"><?= $row->saldo_akhir; ?></td>
                                            <td class="text-right"><?= $row->so; ?></td>
                                            <td class="text-right"><?= $row->selisih; ?></td>
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
                                    <th rowspan="2">Unit Jahit</th>
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
                                ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $row->i_product_wip; ?></td>
                                        <td><?= trim($row->e_product_basename); ?></td>
                                        <td><?= $row->e_color_name; ?></td>
                                        <td><?= $row->e_bagian_name; ?></td>
                                        <td><?= $row->saldoawal; ?></td>
                                        <td><?= $row->m_masuk; ?></td>
                                        <td><?= 0; ?></td>
                                        <td><?= 0; ?></td>
                                        <td><?= $row->k_keluar; ?></td>
                                        <td><?= 0; ?></td>
                                        <td><?= 0; ?></td>
                                        <td><?= $row->saldo_akhir; ?></td>
                                        <td><?= $row->so; ?></td>
                                        <td><?= $row->selisih; ?></td>
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
<script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.table2excel.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/tether.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap/js/tooltip.js"></script>
<script>
    $(function() {
        var $table = $('.table');
        $table.floatThead({
            responsiveContainer: function($table) {
                return $table.closest('.table-responsive');
            }
        });

        $(".exportToExcel").click(function(e) {
            $("#datatable").table2excel({
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