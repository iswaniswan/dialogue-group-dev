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
            padding: 0px;
        }
        @page {     margin: 0 !important; }

    </style>
</head>

<body>
    <div class="area-print">
        <!-- <br> -->
        <table class="borderless">
            <thead>
            </thead>
                    <tbody>
                        <?php
                        // echo $data_barcode->num_rows(). " " . $id;
                        if($data_barcode->num_rows() > 0) { ?>
                            <tr>
                                <td colspan="4">
                                <div class="d-flex flex-wrap justify-content-around">

                            <?php
                            foreach($data_barcode->result() as $db) { ?>
                                
                                    <canvas id="qrcode<?= $db->id ?>" class="qrcode mb-3 bordered"></canvas>
                                    <script>
                                        var qrcode = new QRious({
                                            element: document.getElementById("qrcode<?= $db->id ?>"),
                                            background: '#ffffff',
                                            backgroundAlpha: 1,
                                            foreground: '#111',
                                            foregroundAlpha: 1,
                                            level: 'H',
                                            size: 185,
                                            value: '<?= $db->id ?>'
                                        });
                                    </script>
                            <?php } ?>
                                    </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>

        </table>
    </div>
    <div class="noDisplay text-center">
        <button class="print-button" onclick="window.print();"><span class="print-icon"></span></button>
    </div>
    <div class="page-break"></div>
</body>
<!-- <script src="https://use.fontawesome.com/3580279ade.js"></script> -->

</html>