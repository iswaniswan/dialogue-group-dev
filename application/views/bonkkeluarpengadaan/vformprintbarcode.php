<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak <?= $title; ?></title>
    <link href="<?= base_url('assets/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css?v=2" rel="stylesheet">
    <link href="<?= base_url('assets/css/print.css'); ?>" rel="stylesheet" type="text/css">
    <script src="<?= base_url() . 'assets/js/jsbarcode.all.min.js'; ?>"></script>
    <style>
        .barcode {
            border: 1px solid black;
        }
        .nameproduct-barcode, .idproduct-barcode, .tujuan-dpengadaan {
            width: 100%;
            color: black;
        }
        .nameproduct-barcode>span {
            word-wrap: break-word;
            font-size: .9rem;
        }
        .idproduct-barcode>span {
            word-wrap: break-word;
            font-size: 1rem;
            font-weight: 800;
        }
        .tujuan-dpengadaan>span {
            font-size: .8rem;
        }
        svg {
            margin: 0;
        }
        table {
            width: 100px;
        }
    </style>
</head>

<body>
    <div class="area-print">
        <?php
        if($data_barcode->num_rows() > 0) { ?>
                <div class="row justify-content-around">
                    <?php
                        foreach($data_barcode->result() as $db) {
                            ?>
                            <div class="col-xs-1 mr-4 mb-4">
                                <table>
                                    <tr>
                                        <td class="nameproduct-barcode text-center" colspan="2"><span><?= $db->e_product_basename ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <svg id="barcode<?= $db->id ?>" class="text-center"></svg>
                                            <script>
                                                JsBarcode("#barcode<?= $db->id ?>", "<?= $db->id ?>", {
                                                    width: 2,
                                                    height: 40,
                                                    margin: 0,
                                                    displayValue: false
                                                })
                                            </script>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="idproduct-barcode text-center" colspan="2">
                                            <span><?= $db->i_product_base ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tujuan-dpengadaan">
                                            <span><?= date('ymd', strtotime($db->d_keluar_pengadaan)) ?> <?= $db->i_tujuan ?></span>
                                        </td>
                                        <td class="tujuan-dpengadaan text-right">
                                            <span><?= $db->e_color_name ?></span>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                        <?php } ?>
                </div>
        <?php } ?>
    </div>
    <div class="noDisplay text-center">
        <button class="print-button" onclick="window.print();"><span class="print-icon"></span></button>
    </div>
    <div class="page-break"></div>
</body>
<!-- <script src="https://use.fontawesome.com/3580279ade.js"></script> -->

</html>

<!-- <?php
        if($data_barcode->num_rows() > 0) { ?>
                <div class="d-flex flex-wrap justify-content-between">
            <?php
            foreach($data_barcode->result() as $db) {
                    ?>
                    <div class="barcode d-flex flex-column align-items-center mb-4 mr-1">
                        <div class="nameproduct-barcode text-center">
                            <span><?= $db->e_product_basename ?></span>
                        </div>
                        <svg id="barcode<?= $db->id ?>"></svg>
                        <div class="idproduct-barcode text-center">
                            <span><?= $db->i_product_base ?></span>
                        </div>
                        <div class="tujuan-dpengadaan d-flex flex-wrap justify-content-around">
                            <div class="mr-auto">
                                <span><?= date('ymd', strtotime($db->d_keluar_pengadaan)) ?> <?= $db->i_tujuan ?></span>
                            </div>
                            <div class="ml-auto">
                                <span><?= $db->e_color_name ?></span>
                            </div>
                        </div>
                    </div>
                    <script>
                        JsBarcode("#barcode<?= $db->id ?>", "<?= $db->id ?>", {
                            width: 2.8,
                            height: 40,
                            margin: 0,
                            displayValue: false
                        })
                    </script>
            <?php } ?>
                </div>
        <?php } ?> -->