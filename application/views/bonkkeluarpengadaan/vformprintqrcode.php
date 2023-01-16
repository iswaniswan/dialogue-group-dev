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
    <script src="<?= base_url() . 'assets/js/qrious.min.js'; ?>"></script>
    <style>
        .qrcode {
            border: 1px solid black;
            padding: 2px;
        }
    </style>
</head>

<body>
    <div class="area-print">
        <!-- <br> -->
        <table class="bordered">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Warna</th>
                    <th>QTY</th>
                </tr>
            </thead>
            <?php if ($data_product->num_rows() > 0) {
                $material_kode = "";
                foreach ($data_product->result() as $row) { ?>
                    <tbody>
                        <tr class="table-info">
                            <td><?= $row->i_product_base; ?></td>
                            <td><?= $row->e_product_basename; ?></td>
                            <td><?= $row->e_color_name; ?></td>
                            <td><?= $row->n_quantity_product_wip; ?></td>
                        </tr>
                        <?php
                        if($data_barcode->num_rows() > 0) { ?>
                            <tr>
                                <td colspan="4">
                                <div class="d-flex flex-wrap justify-content-around">

                            <?php
                            foreach($data_barcode->result() as $db) {
                                if($row->id_product_wip == $db->id_product_wip) { ?>
                                    <canvas id="qrcode<?= $db->id ?>" class="m-3 qrcode"></canvas>
                                    <script>
                                        var qrcode = new QRious({
                                            element: document.getElementById("qrcode<?= $db->id ?>"),
                                            background: '#ffffff',
                                            backgroundAlpha: 1,
                                            foreground: '#111',
                                            foregroundAlpha: 1,
                                            level: 'H',
                                            size: 110,
                                            value: '<?= $db->id ?>'
                                        });
                                    </script>
                                <?php }
                            } ?>
                                    </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
            <?php 
                }
            } ?>

        </table>
    </div>
    <div class="noDisplay text-center">
        <button class="print-button" onclick="window.print();"><span class="print-icon"></span></button>
    </div>
    <div class="page-break"></div>
</body>
<!-- <script src="https://use.fontawesome.com/3580279ade.js"></script> -->

</html>