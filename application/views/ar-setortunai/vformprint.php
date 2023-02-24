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
</head>

<body>
    <?php
    $kode_wip = "";
    $kode_material = "";
    if ($data->num_rows() > 0) {
        foreach ($data->result() as $key) {
            if ($kode_wip != $key->id) {
    ?>
                <div class="area-print">
                    <table>
                        <tr>
                            <td>
                                <h1 class="text-center">CHECKLIST SHEET</h1>
                            </td>
                        </tr>
                    </table>
                    <table class="outer-border">
                        <tr>
                            <td>Kode Barang</td>
                            <td>: <?= $key->i_product_wip; ?></td>
                            <td>Jumlah Jenis Material</td>
                            <td>: <?= $key->jml; ?></td>
                        </tr>
                        <tr>
                            <td>Nama Barang</td>
                            <td>: <?= wordwrap($key->e_product_wipname, 50, "<br>\n"); ?></td>
                            <td>Qty Kirim</td>
                            <td>: <?= $key->n_quantity_product_wip; ?></td>
                        </tr>
                        <tr>
                            <td>Warna</td>
                            <td>: <?= $key->e_color_name; ?></td>
                            <td>Nama Perusahaan</td>
                            <td>: <?= $this->session->e_company_name; ?></td>
                        </tr>
                        <tr>
                            <td>No. Dokumen</td>
                            <td>: <?= $key->i_keluar_pengadaan; ?></td>
                            <td>Tujuan</td>
                            <td>: <?= $key->e_bagian_name; ?></td>
                        </tr>
                    </table>
                    <br>
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Komponen</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Check Pabrikan</th>
                                <th class="text-center">Check Unit</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <?php if ($data->num_rows() > 0) {
                            $material_kode = "";
                            $grup = 'panel_item';
                            foreach ($data->result() as $row) {
                                if ($row->grup == $grup) {
                                    if ($row->id == $key->id) { ?>
                                        <tbody>
                                            <?php if ($material_kode != $row->id . $row->i_material) { ?>
                                                <tr class="table-info">
                                                    <td><?= $row->i_material; ?></td>
                                                    <td colspan="6"><?= $row->e_material_name; ?></td>
                                                </tr>

                                            <?php } ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><?= $row->bagian; ?></td>
                                                <td class="text-center"><?= $row->n_qty_penyusun; ?></td>
                                                <td class="text-center"><?= $row->n_quantity_product_wip * $row->n_qty_penyusun; ?></td>
                                                <td class="text-center"><input class="text-center" type="checkbox"></td>
                                                <td class="text-center"><input class="text-center" type="checkbox"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                        <?php }
                                    $material_kode = $row->id . $row->i_material;
                                }
                            }
                        } ?>

                        <!-- <tbody>
                            <tr class="table-info">
                                <td><?= $key->i_material; ?></td>
                                <td colspan="6"><?= $key->e_material_name; ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><?= $key->bagian; ?></td>
                                <td class="text-center"><?= $key->n_qty_penyusun; ?></td>
                                <td class="text-center"><?= $key->n_quantity_product_wip * $key->n_qty_penyusun; ?></td>
                                <td class="text-center"><input class="text-center" type="checkbox"></td>
                                <td class="text-center"><input class="text-center" type="checkbox"></td>
                                <td></td>
                            </tr>
                        </tbody> -->
                    </table>
                    <br>
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Acc Jahit</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Check Pabrikan</th>
                                <th class="text-center">Check Unit</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <?php if ($data->num_rows() > 0) {
                            $material_kode = "";
                            $grup = 'polacutting';
                            foreach ($data->result() as $row) {
                                if ($row->grup == $grup) {
                                    if ($row->id == $key->id) { ?>
                                        <tbody>
                                            <tr>
                                                <td><?= $row->i_material; ?></td>
                                                <td><?= $row->e_material_name; ?></td>
                                                <td class="text-center"><?= $row->n_qty_penyusun; ?></td>
                                                <td class="text-center"><?= $key->n_quantity_product_wip * $row->n_qty_penyusun; ?></td>
                                                <td class="text-center"><?= $row->e_satuan_name; ?></td>
                                                <td class="text-center"><input class="text-center" type="checkbox"></td>
                                                <td class="text-center"><input class="text-center" type="checkbox"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                        <?php }
                                    // $material_kode = $row->id . $row->i_material;
                                }
                            }
                        } ?>
                    </table>
                    <table>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">Bandung, <?= format_bulan($key->d_keluar_pengadaan); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">Mengetahui</td>
                            <td></td>
                            <td class="text-center">Menyetujui</td>
                        </tr>
                        <tr class="height">
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">(Checker Pabrikan)</td>
                            <td></td>
                            <td class="text-center">(Checker Unit)</td>
                        </tr>
                    </table>
                </div>
                <div class="noDisplay text-center">
                    <button class="print-button" onclick="window.print();"><span class="print-icon"></span></button>
                </div>
                <div class="page-break"></div>
    <?php }
            $kode_wip = $key->id;
        }
    } ?>
</body>
<!-- <script src="https://use.fontawesome.com/3580279ade.js"></script> -->

</html>