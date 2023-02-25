<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="<?= base_url('assets/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css?v=2"
        rel="stylesheet">
    <link href="<?= base_url('assets/css/print.css'); ?>" rel="stylesheet" type="text/css">
</head>

<body>
    <section class="app-invoice-wrapper">
        <div class="row">
            <div class="col-md-12 col-12 printable-content">
                <!-- using a bootstrap card -->
                <div class="card">
                    <!-- card body -->
                    <div class="card-body p-2 area-print">



                        <div class="invoice-logo-title row">
                            <div class="col-9 d-flex flex-column justify-content-center align-items-start">
                                <!-- <span>Software Development</span> -->
                                <h1 class="font-large-2 text-uppercase text-primary">
                                    <?= $company->name; ?>
                                    <input type="hidden" id="id" value="<?= $data->i_rv; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->global['folder']); ?>">
                                    <br><span class="font-medium-3 text-capitalize">
                                        <?= check_constant('AlmtPerusahaan'); ?>
                                    </span><br>
                                </h1>
                            </div>

                            <div class="col-3 d-flex justify-content-end invoice-logo">
                            <img src="<?= base_url(); ?>assets/images/logo/<?= $company->logo; ?>" alt="company-logo"
                                    height="94" width="149">
                            </div>
                        </div>
                        <hr class="mt-0 mb-0">


                        <!-- invoice address and contacts -->
                        <div class="row mb-1">
                            <div class="col-12">
                                <div class="info-title text-center">
                                    <span class="text-bold-700 font-large-1 text-uppercase">Bukti Penerimaan
                                        <?= $data->e_coa_name; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- invoice address and contacts -->
                        <div class="row invoice-adress-info">
                            <div class="col-6 from-info">
                                <div class="info-title">
                                    <span class="font-medium-3">NO. </span><span class="font-medium-1">
                                        <?= $data->i_rv_id; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 to-info">
                                <div class="info-title text-right mb-1">
                                    <p class="font-medium-3 text-capitalize">
                                        <?= $data->e_area . ', ' . format_bulan($data->d_rv); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--product details table -->
                        <div class="product-details-table mt-0 mb-0">
                            <table class="table table-xs table-bordered mt-0 mb-0 text-capitalize font-medium-3">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">CoA</th>
                                        <th class="text-center">Tgl Bukti</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 0;
                                    $subtotal = 0;
                                    $saldo = 0;
                                    $saldoakhir = 0;
                                    if ($detail) {
                                        foreach ($detail as $key) {
                                            $i++; ?>
                                            <tr>
                                                <td class="text-center" valign="center">
                                                    <?= $i; ?>
                                                </td>
                                                <td>
                                                    <?= $key->i_coa_id; ?>
                                                </td>
                                                <td>
                                                    <?= $key->date_bukti; ?>
                                                </td>
                                                <td>
                                                    <?= $key->e_remark; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= number_format($key->v_rv); ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $subtotal += $key->v_rv;
                                        }
                                        $saldo = 0;
                                        $saldoakhir = $subtotal + $saldo;
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">
                                            <?= $this->lang->line('Jumlah'); ?> Rp.
                                        </th>
                                        <th class="text-right">
                                            <?= number_format($subtotal); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right"><span class="text-capitalize"><em>(Terbilang
                                                    :
                                                    <?= terbilang($subtotal); ?> Rupiah)
                                                </em></span></th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                        <hr class="mt-0 mb-2">

                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table nowrap table-borderless table-xs">
                                        <tbody class="font-medium-3">
                                            <tr>
                                                <td class="text-center">Dibayar :</td>
                                                <td class="text-center">Diperiksa :</td>
                                                <td class="text-center">Diketahui :</td>
                                                <td class="text-center">Disetujui :</td>
                                                <td></td>
                                                <td width="160px" class="text-center">Diterima</td>
                                            </tr>
                                            <tr height="50">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <?php if ($data->i_coa == '359' or $data->i_coa == '6') { ?>
                                                    <td class="text-center">(<u> Novi </u>)</td>
                                                <?php } else { ?>
                                                    <td class="text-center">
                                                        (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)
                                                    </td>
                                                <?php } ?>
                                                <td class="text-center">
                                                    (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)
                                                </td>
                                                <td class="text-center">
                                                    (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)
                                                </td>
                                                <td class="text-center">
                                                    (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)
                                                </td>
                                                <td class="text-center">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                                                </td>
                                                <td class="text-center">
                                                    (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- buttons section -->
            <div class="col-md-4 col-6 action-btns m-auto no-print">
                <div class="noDisplay text-center">
                    <button class="print-button" onclick="window.print();"><span class="print-icon"></span></button>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
    window.onafterprint = function () {
        var id = '<?= $id ?>';
        $.ajax({
            type: "POST",
            url: "<?= site_url($folder . '/cform/updateprint'); ?>",
            data: {
                'id': id,
            },
            success: function (data) {
                opener.window.refreshview();
                setTimeout(window.close, 0);
            },
            error: function (XMLHttpRequest) {
                alert('fail');
            }
        });
    }
</script>

</html>