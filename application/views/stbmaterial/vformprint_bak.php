<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak <?= $title; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/pagedjs@0.4.1/dist/paged.min.js"></script>
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
            
        }
        @media print {
            body, section {
                -webkit-print-color-adjust: exact;
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
        }

        @page {
            counter-increment: page;
            counter-reset: page 1;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <div class="container pt-4">
        <section id="kop" style="border: 1px solid #333; background: #c9c9c9!important">
            <div class="row">
                <div class="col-3 p-4 text-center" style="border-right: 1px solid #333;">
                    <img src="<?= base_url() . 'assets/images/logo/' .$company->logo ?>" class="img-fluid" alt="logo-perusahaan"/>
                </div>
                <div class="col-9 p-4 text-center">
                    <b>FORMULIR <br/>SERAH TERIMA BARANG (STB)</b>
                </div>
            </div>
        </section>        
        <section id="header" class="border-side border-bottom">
            <div class="row">
                <div class="col-3 text-center p-2 border-right">
                    <div>Nomor Dokumen</div>
                    <div><?= $data->i_document ?></div>
                </div>
                <div class="col-3 text-center p-2 border-right">
                    <div>REVISI</div>
                    <div>00</div>
                </div>
                <div class="col-3 text-center p-2 border-right">
                    <div>Tanggal Efektif</div>
                    <div>1 Januari 2023</div>
                </div>
                <div class="col-3 text-center p-2">
                    <div>Halaman </div>
                    <div>1 dari 1 </div>
                </div>
            </div>
        </section>
        <section id="body" class="border-side">
            <div class="row justify-content-end">
                <div class="col-3 mb-4 py-2">
                    <?php /** no urut diambil dari ekor nomor dokumen */ 
                    $suffix = substr($data->i_document, -4);                
                    ?>
                    <div>No. Urut: <?= $suffix ?></div>
                </div>
            </div>
            <div class="row ml-4 mr-4 mr-4 mb-5">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-2"><b>Dari</b></div>
                        <div class="col-10">: <?= $data->e_bagian_name ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><b>Untuk</b></div>
                        <div class="col-10">: <?= $data->e_bagian_receive_name ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><b>Tanggal</b></div>
                        <div class="col-10">: <?= $data->date_document ?></div>
                    </div>
                </div>
            </div>
            <div class="row ml-4 mr-4">
                <div class="col-12">
                    <p>Bersama ini dikirimkan barang dengan spesifikasi sebagai berikut:</p>
                </div>
            </div>
            <div class="row ml-4 mr-4">
                <div class="container-fluid table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead style="background: #f8f9fa!important">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                            </tr>    
                        </thead>
                        <tbody>
                        <?php $no=1; foreach($datadetail as $item) { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $item->i_product_wip ?></td>
                                <td><?= $item->e_product_wipname ?></td>
                                <td><?= number_format($item->n_quantity, 2, ".", ",") ?></td> 
                                <td><?= $item->e_satuan_name; ?></td>
                                <td><?= $item->e_remark; ?></td>
                            </tr>
                        <?php $no++ ; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row ml-4">
                <div class="col-12 mb-5">
                    <p>Mohon diperiksa kembali sebelum dilakukan penerimaan barang</p>
                </div>
            </div>
        </section>
        <section id="footer" class="border-side border-bottom pb-5">
            <div class="row justify-content-center">
                <div class="col-3 text-center p-4 mx-1" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Dibuat Oleh,</h5>
                    <p>(_________________)</p>
                </div>
                <div class="col-3 text-center p-4 mx-1" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Diketahui Oleh,</h5>
                    <p>(_________________)</p>
                </div>
                <div class="col-3 text-center p-4 mx-1" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Diterima Oleh,</h5>
                    <p>(_________________)</p>
                </div>
            </div>
        </section>
    </div>
    <div class="noDisplay text-center" style="margin-top: 50px">
        <button class="print-button" onclick="window.print();"><span class="print-icon"></span></button>
    </div>
</body>

</html>