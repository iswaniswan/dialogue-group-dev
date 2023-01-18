<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://unpkg.com/bootstrap-table@1.21.2/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">
    <style>
        .nowrap {
            white-space: nowrap !important;
        }
    </style>
</head>

<body>
    <!-- <body oncontextmenu='return false;' onkeydown='return false;' onmousedown='return false;'> -->
    <div class="container-fluid">
        <div class="card shadow mt-3">
            <div class="card-header text-center">
                <h3><?= $title; ?></h3>
                <h4>Periode : <?= format_bulan($d_from) . ' [s/d] ' . format_bulan($d_to); ?></h4>
            </div>
            <div class="card-body">
                <?php
                if ($laporan == 'exp_pembelian') { ?>
                    <ul class="nav nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">CREDIT</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">CASH</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data?type=credit'); ?>" data-fixed-columns="true" data-fixed-number="6">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-align="center">No</th>
                                        <th data-field="i_supplier">KODE SUPPLIER</th>
                                        <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                        <th data-field="i_sj_supplier">NO. SJ</th>
                                        <th data-field="d_sj_supplier">TGL. SJ</th>
                                        <th data-field="i_material">KODE BARANG</th>
                                        <th data-field="e_material_name">LIST BARANG</th>
                                        <th data-field="e_nama_kelompok">KATEGORI</th>
                                        <th data-field="e_type_name">SUB KATEGORI</th>
                                        <th data-field="i_coa">COA</th>
                                        <th data-field="v_price" data-align="right">HARGA EXCLUDE (RP)</th>
                                        <th data-field="n_quantity" data-align="right">QTY</th>
                                        <th data-field="e_satuan_name">SATUAN</th>
                                        <th data-field="total" data-align="right">TOTAL</th>
                                        <th data-field="discount" data-align="right">DISKON</th>
                                        <th data-field="dpp" data-align="right">DPP</th>
                                        <th data-field="ppn" data-align="right">PPN</th>
                                        <th data-field="hutang_dagang" data-align="right">HUTANG DAGANG</th>
                                        <th data-field="bahan_baku" data-align="right">BAHAN BAKU</th>
                                        <th data-field="bahan_pembantu" data-align="right">BAHAN PEMBANTU</th>
                                        <th data-field="wip" data-align="right">BARANG WIP</th>
                                        <th data-field="lainnya" data-align="right">BIAYA LAINNYA</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data?type=cash'); ?>" data-fixed-columns="true" data-fixed-number="6">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-align="center">No</th>
                                        <th data-field="i_supplier">KODE SUPPLIER</th>
                                        <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                        <th data-field="i_sj_supplier">NO. SJ</th>
                                        <th data-field="d_sj_supplier">TGL. SJ</th>
                                        <th data-field="i_material">KODE BARANG</th>
                                        <th data-field="e_material_name">LIST BARANG</th>
                                        <th data-field="e_nama_kelompok">KATEGORI</th>
                                        <th data-field="e_type_name">SUB KATEGORI</th>
                                        <th data-field="i_coa">COA</th>
                                        <th data-field="v_price" data-align="right">HARGA EXCLUDE (RP)</th>
                                        <th data-field="n_quantity" data-align="right">QTY</th>
                                        <th data-field="e_satuan_name">SATUAN</th>
                                        <th data-field="total" data-align="right">TOTAL</th>
                                        <th data-field="discount" data-align="right">DISKON</th>
                                        <th data-field="dpp" data-align="right">DPP</th>
                                        <th data-field="ppn" data-align="right">PPN</th>
                                        <th data-field="hutang_dagang" data-align="right">HUTANG DAGANG</th>
                                        <th data-field="bahan_baku" data-align="right">BAHAN BAKU</th>
                                        <th data-field="bahan_pembantu" data-align="right">BAHAN PEMBANTU</th>
                                        <th data-field="wip" data-align="right">BARANG WIP</th>
                                        <th data-field="lainnya" data-align="right">BIAYA LAINNYA</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($laporan == 'exp_kartu') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true" data-fixed-number="2">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                <th data-field="i_nota">NO. NOTA</th>
                                <th data-field="d_nota">TGL. NOTA</th>
                                <th data-field="v_saldo_awal" data-align="right">SALDO AWAL</th>
                                <th data-field="v_pelunasan" data-align="right">PELUNASAN</th>
                                <th data-field="v_dn" data-align="right">D/N</th>
                                <th data-field="v_pembulatan_1" data-align="right">PEMBULATAN</th>
                                <th data-field="v_pembelian" data-align="right">PEMBELIAN</th>
                                <th data-field="v_cn" data-align="right">C/N</th>
                                <th data-field="v_pembulatan_2" data-align="right">PEMBULATAN</th>
                                <th data-field="v_saldo_akhir" data-align="right">SALDO AKHIR</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_opname') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true" data-fixed-number="2">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="d_faktur_supplier">TGL. FAKTUR</th>
                                <th data-field="n_top">T.O.P</th>
                                <th data-field="d_jatuh_tempo">TGL. JT. TEMPO</th>
                                <th data-field="i_sj_supplier">NO. SJ</th>
                                <th data-field="i_supplier">KODE SUPPLIER</th>
                                <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                <th data-field="e_supplier_group_name">JENIS</th>
                                <th data-field="v_sisa" data-align="right">TOTAL (Rp.)</th>
                                <th data-field="v_total" data-align="right">SUB TOTAL (Rp.)</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_rekapitulasi') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true" data-fixed-number="3">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="i_supplier">KODE SUPPLIER</th>
                                <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                <th data-field="v_saldo_awal" data-align="right">SALDO AWAL</th>
                                <th data-field="v_pembelian" data-align="right">PEMBELIAN BB/BP</th>
                                <th data-field="v_pembelian_lain" data-align="right">PEMBELIAN LL</th>
                                <th data-field="v_pembelian_makloon" data-align="right">PEMBELIAN MAKLOON</th>
                                <th data-field="v_retur" data-align="right">RETUR</th>
                                <th data-field="v_pelunasan" data-align="right">PELUNASAN A/P</th>
                                <th data-field="v_cn" data-align="right">C/N</th>
                                <th data-field="v_pembulatan" data-align="right">PEMBULATAN</th>
                                <th data-field="v_saldo_akhir" data-align="right">SALDO AKHIR</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_buku') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true" data-fixed-number="3">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="e_supplier_name">SUPPLIER</th>
                                <th data-field="coa">COA</th>
                                <th data-field="v_ap" data-align="right">AP</th>
                                <th data-field="v_dpp" data-align="right">DPP</th>
                                <th data-field="v_ppn" data-align="right">PPN</th>
                                <th data-field="v_retur" data-align="right">RETUR</th>
                                <th data-field="v_pph21" data-align="right">PPH 21</th>
                                <th data-field="v_pph23" data-align="right">PPH 23</th>
                                <th data-field="v_skb" data-align="right">SKB</th>
                                <th data-field="v_total" data-align="right">HUTANG DAGANG</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_opvsbtb') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true" data-fixed-number="3">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="d_op">TGL. OP</th>
                                <th data-field="i_op">NO. OP</th>
                                <th data-field="i_supplier">KODE SUPPLIER</th>
                                <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                <th data-field="i_material">KODE MATERIAL</th>
                                <th data-field="e_material_name">NAMA MATERIAL</th>
                                <th data-field="e_satuan_name">SATUAN</th>
                                <th data-field="n_quantity" data-align="right">QTY OP</th>
                                <th data-field="n_quantity_sj" data-align="right">QTY BTB/SJ</th>
                                <th data-field="n_quantity_sisa" data-align="right">SISA OP</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_btb_faktur') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true" data-fixed-number="6">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="d_btb">TGL. BTB</th>
                                <th data-field="i_btb">NO. BTB</th>
                                <th data-field="i_sj_supplier">SJ SUPPLIER</th>
                                <th data-field="i_supplier">KODE SUPPLIER</th>
                                <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                <th data-field="d_nota">TGL. NOTA</th>
                                <th data-field="i_nota">NO. NOTA</th>
                                <th data-field="i_material">KODE MATERIAL</th>
                                <th data-field="e_material_name">NAMA MATERIAL</th>
                                <th data-field="e_satuan_name">SATUAN</th>
                                <th data-field="n_quantity_btb" data-align="right">QTY BTB</th>
                                <th data-field="n_quantity_nota" data-align="right">QTY FAKUTR</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_rekap_supplier') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="false">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="i_supplier">KODE SUPPLIER</th>
                                <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                <th data-field="v_total" data-align="right">TOTAL</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_btb_dan_faktur') { ?>
                    <ul class="nav nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">BTB</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">FAKTUR</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data?type=btb'); ?>" data-fixed-columns="true" data-fixed-number="5">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-align="center">No</th>
                                        <th data-field="d_btb">TGL. BTB</th>
                                        <th data-field="i_btb">NO. BTB</th>
                                        <th data-field="i_sj_supplier">NO. SJ SUPPLIER</th>
                                        <th data-field="i_supplier">KODE SUPPLIER</th>
                                        <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                        <th data-field="i_material">KODE MATERIAL</th>
                                        <th data-field="e_material_name">NAMA MATERIAL</th>
                                        <th data-field="e_satuan_name">SATUAN</th>
                                        <th data-field="n_qty" data-align="right">QTY</th>
                                        <th data-field="v_price" data-align="right">HARGA</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data?type=faktur'); ?>" data-fixed-columns="true" data-fixed-number="5">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-align="center">No</th>
                                        <th data-field="d_nota">TGL. NOTA</th>
                                        <th data-field="i_nota">NO. NOTA</th>
                                        <th data-field="i_supplier">KODE SUPPLIER</th>
                                        <th data-field="e_supplier_name">NAMA SUPPLIER</th>
                                        <th data-field="i_material">KODE MATERIAL</th>
                                        <th data-field="e_material_name">NAMA MATERIAL</th>
                                        <th data-field="e_satuan_name">SATUAN</th>
                                        <th data-field="n_qty" data-align="right">QTY</th>
                                        <th data-field="v_price" data-align="right">HARGA</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($laporan == 'exp_per_kategori') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true" data-fixed-number="3">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="i_material">KODE MATERIAL</th>
                                <th data-field="e_material_name">NAMA MATERIAL</th>
                                <th data-field="e_satuan_name">SATUAN</th>
                                <th data-field="e_kelompok_barang_name">KATEGORI BARANG</th>
                                <th data-field="e_type_name">SUB KATEGORI BARANG</th>
                                <th data-field="n_qty" data-align="right">QTY</th>
                                <th data-field="v_price" data-align="right">HARGA</th>
                            </tr>
                        </thead>
                    </table>
                <?php } elseif ($laporan == 'exp_pp') { ?>
                    <table class="nowrap" data-locale="id-ID" data-toggle="table" data-height="600" data-search="true" data-side-pagination="server" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-query-params="queryParams" data-url="<?= base_url('export_pembelian/cform/data'); ?>" data-fixed-columns="true"  data-fixed-number="3">
                        <thead>
                            <tr>
                                <th data-field="id" data-align="center">No</th>
                                <th data-field="d_pp">TGL. PP</th>
                                <th data-field="i_pp">NO. PP</th>
                                <th data-field="e_bagian_name">BAGIAN</th>
                                <th data-field="i_material">KODE MATERIAL</th>
                                <th data-field="e_material_name">NAMA MATERIAL</th>
                                <th data-field="e_satuan_name">SATUAN</th>
                                <th data-field="n_quantity" data-align="right">QTY PP</th>
                                <th data-field="n_op" data-align="right">QTY OP</th>
                                <th data-field="n_sisa" data-align="right">QTY SISA PP BELUM OP</th>
                            </tr>
                        </thead>
                    </table>
                <?php } ?>
            </div>
            <div class="card-footer text-muted">
                <a href="#" class="btn btn-block btn-warning" onclick="window.close();">Close</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script> -->
    <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table-locale-all.min.js"></script>
    <!-- <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/extensions/export/bootstrap-table-export.min.js"></script> -->
    <script>
        function queryParams(params) {
            // params.search = 8;
            params.date_from = '<?= $d_from; ?>';
            params.date_to = '<?= $d_to; ?>';
            params.i_supplier = '<?= $i_supplier; ?>';
            params.laporan = '<?= $laporan; ?>';
            return params
        }
        // your custom ajax request here
        // function ajaxRequest(params) {
        //     var url = 'https://examples.wenzhixin.net.cn/examples/bootstrap_table/data'
        //     $.get(url + '?' + $.param(params.data)).then(function(res) {
        //         params.success(res)
        //     })
        // }
    </script>
</body>

</html>