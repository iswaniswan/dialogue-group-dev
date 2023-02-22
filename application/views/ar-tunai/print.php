<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0">
    <title>Print
        <?= $this->global['title']; ?>
    </title>
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print/print.css"> -->
    <link href="<?= base_url('assets/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css?v=2"
        rel="stylesheet">
    <link href="<?= base_url('assets/css/print.css'); ?>" rel="stylesheet" type="text/css">

    <style>
        @page {
            size: A4 landscape !important;
        }

        @media print {
            * {
                margin: 0 !important;
                padding: 0 !important;
            }

            html,
            body {
                width: 100%;
                height: 100%;
                overflow: hidden;
                background: #FFF;
                font-size: 9.5pt;
                zoom: 95%
            }
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body>

    <!-- <body> -->
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <h1 class="header text-uppercase text-bold-700 text-primary">Daftar Tagihan</h1>
                <span class="text-muted">NO. </span><span class="font-medium-1">
                    <?= $data->i_dt_id; ?>
                </span>
            </td>
            <td class="text-right" rowspan="2"><img src="<?= base_url(); ?>assets/images/logo/<?= $company->logo; ?>"
                    alt="company-logo" height="70" width="100"></td>
        </tr>
        <tr>
            <td><span class="judul2 font-weight-bold">
                </span></td>
            <hr class="mt-0 mb-0">
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700">
                    <?= $company->name; ?>
                </span></td>
            <td class="text-right" width="50%"><span class="header text-bold-700">
                    <?= $data->e_area_name; ?>
            </td>
        </tr>
        <tr>
            <td><span class="judul2">
                    <?php if (check_constant('NPWPPerusahaan') != null) { ?>NPWP -
                        <?= check_constant('NPWPPerusahaan'); ?>
                    <?php } ?>
                </span></td>
            <td class="text-right">
                <?= format_bulan($data->d_dt); ?>
            </td>
        </tr>
        <tr>
            <td><span class="judul2">
                    <?php if (check_constant('TlpPerusahaan') != null) { ?>Telepon -
                        <?= check_constant('TlpPerusahaan'); ?>
                    <?php } ?>
                </span></td>
            <td class="text-right"><span class="judul2"></span></td>
        </tr>
        <tr>
            <td colspan="2" class="text-right judul2"><span><br></span></td>
        </tr>
    </table>


    <input type="hidden" id="id" value="<?= $data->i_dt; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->global['folder']); ?>">
    <table class="judul2" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" rowspan="2" class="text-center" width="3%">No</th>
                <th scope="col" colspan="3" class="text-center" width="10%">Faktur</th>
                <th scope="col" colspan="3" class="text-center" width="10%">Debitur</th>
                <th scope="col" rowspan="2" class="text-center" width="5%">Jumlah</th>
                <th scope="col" rowspan="2" class="text-center" width="5%">Sisa</th>
                <th scope="col" rowspan="2" class="text-center" width="10%">Tunai</th>
                <th scope="col" colspan="4" class="text-center">Transfer / Cek / Giro Bilyet</th>
                <!-- <th scope="col" rowspan="2" class="text-center" width="10%">Total</th> -->
                <th scope="col" rowspan="2" class="text-center" width="14%">Catatan</th>
            </tr>
            <tr>
                <th scope="col" class="text-center" width="5%">No</th>
                <th scope="col" class="text-center" width="5%">Tgl</th>
                <th scope="col" class="text-center" width="5%">Jt</th>
                <th scope="col" class="text-center" width="5%">Kode</th>
                <th scope="col" class="text-center" width="10%">Nama</th>
                <th scope="col" class="text-center" width="10%">Kota</th>
                <th scope="col" class="text-center">No</th>
                <th scope="col" class="text-center" width="4%">Bank</th>
                <th scope="col" class="text-center" width="10%">Jumlah</th>
                <th scope="col" class="text-center">Tgl</th>
            </tr>

        </thead>
        <tbody class="font-small-1">
            <?php $i = 0;
            $total = 0;
            if ($detail) {
                foreach ($detail as $key) {
                    $i++;
                    ?>
                    <tr>
                        <td class="text-center">
                            <?= $i; ?>
                        </td>
                        <td>
                            <?= substr($key->i_document, -6); ?>
                        </td>
                        <td>
                            <?= date('d-m-y', strtotime($key->d_nota)); ?>
                        </td>
                        <td>
                            <?= date('d-m-y', strtotime($key->d_jatuh_tempo)); ?>
                        </td>
                        <td>
                            <?= $key->i_customer; ?>
                        </td>
                        <td>
                            <?= $key->e_customer; ?>
                        </td>
                        <td>
                            <?= $key->e_city_name; ?>
                        </td>
                        <td class="text-right">
                            <?= number_format($key->v_bayar); ?>
                        </td>
                        <td class="text-right">
                            <?= number_format($key->v_sisa); ?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                        <td></td>
                    </tr>
                    <?php
                    $total += $key->v_bayar;
                }
            } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">Jumlah Rp. </th>
                <th class="text-right">
                    <?= number_format($total); ?>
                </th>
                <th colspan="8"></th>
            </tr>
        </tfoot>
        <table width="100%;" class="judul2">
            <tbody>
                <tr>
                    <td width="10%">Sudah Terima : Tunai</td>
                    <td colspan="5">= Rp.</td>
                </tr>
                <tr>
                    <td width="160px">Giro / Cek = ....... lbr</td>
                    <td>= Rp.</td>
                    <td class="text-center">Ditagih Oleh :</td>
                    <td class="text-center">Diserahkan Oleh :</td>
                    <td class="text-center">Dibuat Oleh :</td>
                    <td class="text-center">Diterima Oleh :</td>
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
                    <td class="text-center" colspan="2">
                        (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)
                    </td>
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
                        (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)
                    </td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2">Kasir</td>
                    <td class="text-center" colspan="2">Penagih</td>
                    <td class="text-center" colspan="2">Adm Keuangan</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6"><span class="judul2"><br></span></td>
                </tr>
                <tr>
                    <td colspan="6"><span class="judul2"><br></span></td>
                </tr>
                <tr>
                    <td colspan="6"><span class="judul2"><br></span></td>
                </tr>
                <tr>
                    <td colspan="6"><span class="font-12">*Tanggal Cetak :
                            <?= format_bulan(date('Y-m-d')) . ' ' . date('H:i:s'); ?>
                        </span></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-center">
                        <div class="noDisplay text-center">
                            <button class="print-button" onclick="window.print();"><span
                                    class="print-icon"></span></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="page-break"></div>
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