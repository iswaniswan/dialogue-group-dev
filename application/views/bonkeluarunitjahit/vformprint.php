<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak STB Jahit</title>
    <link href="<?= base_url('assets/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css?v=2" rel="stylesheet">
    <link href="<?= base_url('assets/css/print.css'); ?>" rel="stylesheet" type="text/css">

    <style>
        .border-top {
            border-top: 1px solid #333;
        }
        .border-side {
            border-left: 1px solid #333; 
            border-right: 1px solid #333;
        }
        .border-right {
            border-right: 1px solid #333;
        }
        .border-bottom {
            border-bottom: 1px solid #333;
        }
        table tbody td {
            padding: .25rem .5rem !important;            
        }
        .ml--1 {
            margin-left: -15px;
        }
        @media print {
            body, section {
                width:100% !important;
                -webkit-print-color-adjust: exact;
                font-size: inherit !important;
            }
            .row {
                display: -webkit-box;
                display: -webkit-flex;
                display: -ms-flexbox;
                display: flex;
                -webkit-flex-wrap: wrap;
                    -ms-flex-wrap: wrap;
                        flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
                }
            table tbody td {
                font-size: inherit !important;            
            }
        }
        @page  { 
            size: auto;
            margin: 0mm 0mm 0mm 0mm;
        } 

        <?php 
        $ua = getBrowser(); 
        $browser_name = strtolower($ua['name']);
        if (strpos($browser_name, 'mozilla') !== false) { ?>           
            @media print {
                table tbody td {
                    font-size: 7pt !important;
                }
            }
        <?php } ?>
    </style>
</head>
<body>
    <?php 
        $page_break = 31; 
        $page_data = [
            'data' => $data       
        ];
        
        $index=1;
        foreach ($datadetail as $item) {
            /** cast object as array */
            $item = (array) $item;

            $pages[$index][] = $item;      

            if (count($pages[$index]) >= $page_break) {
                $index++;
            }
        } 

        $total_pages = count($pages);

        $index=1;
        foreach ($pages as $page) {
            $page_data['datadetail'] = $page;
            $page_data['index'] = $index;
            $page_data['total_pages'] = $total_pages;
            $page_data['page_break'] = $page_break;
            $this->view('bonkeluarunitjahit/_print', $page_data);
            $index++;
        }
    ?>

    <div class="noDisplay text-center" style="margin-top: 25px">
        <button class="print-button" onclick="window.print();"><span class="print-icon"></span></button>
    </div>
</body>
</html>