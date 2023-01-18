<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<style type="text/css" media="print">
    .noDisplay {
        display: none;
    }

    .pagebreak {
        page-break-before: always;
    }

    @media print {
        .page-break {
            display: block;
            page-break-before: always;
        }
    }

    .style {
        padding: 1px 8px;
    }

        {
        size: portrait;
    }

    @page {
        size: Letter;
        margin: 0mm;
        /* this affects the margin in the printer settings */
    }
</style>
<?php
include("php/fungsi.php");
?>
<?php
$hal = 1;
foreach ($data as $row) { ?>
    <!-- color CSS -->
    <div class="white-box printableArea">
        <!-- <table class="isinya" border='0' align="center" width="70%"> -->
        <div class="row">
            <div class="col-sm-12">
                <!--  <hr style="margin-top: -1rem;
                    margin-bottom: 0rem;"> -->
                <div class="row">


                    <div class="col-sm-6 text-left" style="margin-bottom: 1rem; margin-top: 0.5rem !important">
                        <h3 style="font-size: 16px; line-height: 0px;"><b> BUKTI TERIMA BARANG</b><br></h3>
                    </div>
                    <div class="col-sm-6 text-right" style="margin-bottom: 1rem; margin-top: 0.5rem !important">
                        <h3 style="font-size: 16px; line-height: 0px;"><b> <?= $row->e_supplier_name; ?></b><br></h3>
                    </div>

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-8">
                                <p class="text-muted" style="font-size: 12px;
                                    margin-bottom: 1rem;">Nomor BTB : <b><?= $row->i_btb; ?></b> <br> Tanggal BTB : <b><?= $row->d_btb; ?></b> </p>
                            </div>

                            <div class="col-sm-4">
                                <p class="text-muted" style="font-size: 12px;
                                    margin-left:3rem;margin-bottom: 1rem;"> Nomor SJ Supplier : <b> <?= $row->i_sj_supplier; ?> </b> <br> Tanggal SJ Supplier : <b> <?= $row->d_sj_supplier; ?></b> </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-9">
                                <p class="text-muted" style="font-size: 12px;
                                    margin-bottom: 0rem;">Berikut adalah Daftar Barang yang sudah di terima oleh <b><?= $row->e_bagian_name ?></b> lokasi <b><?= $row->e_lokasi_name ?></b> </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="table-responsive m-t-0">
                            <table class="table table-bordered" cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 13px; padding: 2px 10px; width: 3%;" class="text-center">No</th>
                                        <th style="font-size: 13px; padding: 2px 10px;">Nama Barang</th>
                                        <th style="font-size: 13px; padding: 2px 10px;">Satuan</th>
                                        <th style="font-size: 13px; padding: 2px 10px;" class="text-right">Jumlah Diterima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 0;
                                    if ($data2) {
                                        $hasiltotal = 0;
                                        $qty   = 0;
                                        foreach ($data2 as $rowi) {
                                            $counter++;
                                            $qty   = $qty + $rowi->n_quantity;
                                    ?>
                                            <tr>
                                                <td style="font-size: 11px; padding: 1px 8px;" class="text-center">
                                                    <?= $counter; ?>
                                                </td>
                                                <td style="font-size: 11px; padding: 1px 8px;">
                                                    <?php
                                                    if (strlen($rowi->e_material_name) > 50) {
                                                        $nam    = substr($rowi->e_material_name, 0, 50);
                                                    } else {
                                                        $nam    = $rowi->e_material_name . str_repeat(" ", 50 - strlen($rowi->e_material_name));
                                                    }
                                                    echo $rowi->i_material . " - " . $nam;
                                                    ?>
                                                </td>
                                                <td style="font-size: 11px; padding: 1px 8px;" class="text-left">
                                                    <?= $rowi->e_satuan_name; ?>
                                                </td>
                                                <td style="font-size: 11px; padding: 1px 8px;" class="text-right">
                                                    <?= $rowi->n_quantity; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="3" class="text-right" style="font-size: 12px; padding: 0px 8px;border:0;">Total : </td>
                                            <td colspan="1" class="text-right" style="font-size: 12px; padding: 0px 8px;border:0;">
                                                <?= number_format($qty, 2, ',', '.'); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <p class="text-muted" style="font-size: 12px; 
                            margin-bottom: -1rem;">Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih</p> <br>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <font face="Courier New" size="2">
                                    <?php date_default_timezone_set('Asia/Jakarta');
                                    echo "Tanggal Cetak : " . $tgl = date("d") . " " . $this->fungsi->mbulan(date("m")) . " " . date("Y") . ",  Jam : " . date("H:i:s"); ?>
                                </font>
                            </div>
                            <div class="col-sm-6 text-right">
                                <?php if ($approve->num_rows() > 0) { ?>
                                    <font class="text-right" face="Courier New" size="2">Approve by </font>
                                    <?php foreach ($approve->result() as $key) { ?>
                                        <br>
                                        <font face="Courier New" size="2"><?= $key->approve; ?></font>
                                <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="noDisplay">
                    <div class="text-center"> <button id="print" class="btn btn-info btn-outline" onclick="window.print();" type="button"> <span><i class="fa fa-print"></i> Print</span> </button> </div>
                </div>
            </div>
        </div>
        <!-- </table> -->
    </div>
    <script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>