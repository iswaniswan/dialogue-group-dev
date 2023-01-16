<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak <?= $title; ?></title>
    <link href="<?= base_url('assets/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
</head>

<body>
    <div class="panel-body">
        <div class="card border-secondary">
            <div class="card-header">
                <h2 class="text-center font-weight-normal">CHECKLIST SHEET</h2>
            </div>
            <div class="card-body">
                <table class="table table-sm nowrap display" width="100%">
                    <?php
                    $kode_wip = "";
                    $kode_material = "";
                    if ($data->num_rows() > 0) {
                        foreach ($data->result() as $key) { ?>
                            <?php if ($kode_wip != $key->i_product_wip) { ?>
                                <thead>
                                    <tr>
                                        <td>Kode Barang</td>
                                        <td colspan="3">: <?= $key->i_product_wip; ?></td>
                                        <td>Jumlah Jenis Bahan Baku</td>
                                        <td colspan="2">: <?= $key->jml; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Barang</td>
                                        <td colspan="3">: <?= $key->e_product_wipname; ?></td>
                                        <td>Qty Kirim</td>
                                        <td colspan="2">: <?= $key->n_quantity_product_wip; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Warna</td>
                                        <td colspan="3">: <?= $key->e_color_name; ?></td>
                                        <td>Nama Perusahaan</td>
                                        <td colspan="2">: <?= $this->session->e_company_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td>No. Dokumen</td>
                                        <td colspan="3">: <?= $key->i_keluar_pengadaan; ?></td>
                                        <td>Tujuan</td>
                                        <td colspan="2">: <?= $key->e_bagian_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">&nbsp;</td>
                                    </tr>
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
                            <?php } ?>
                            <tbody>
                                <?php if ($kode_material != $key->i_product_wip . $key->i_material) { ?>
                                    <tr class="table-info">
                                        <td><?= $key->i_material;?></td>
                                        <td colspan="6"><?= $key->e_material_name;?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><?= $key->bagian; ?></td>
                                    <td class="text-center"><?= $key->n_qty_penyusun; ?></td>
                                    <td class="text-center"><?= $key->n_quantity_product_wip * $key->n_qty_penyusun; ?></td>
                                    <td class="text-center"><input class="text-center" type="checkbox"></td>
                                    <td class="text-center"><input class="text-center" type="checkbox"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                    <?php
                            $kode_wip = $key->i_product_wip;
                            $kode_material = $key->i_product_wip . $key->i_material;
                        }
                    } ?>
                </table>
            </div>
        </div>
    </div>
</body>
<!-- <script src="https://use.fontawesome.com/3580279ade.js"></script> -->

</html>