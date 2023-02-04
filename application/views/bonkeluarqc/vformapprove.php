<style>
    .bold {
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="text" name="ibonk" id="dokumenbon" value="<?= $data->i_keluar_qc; ?>" class="form-control input-sm" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm" required="" readonly value="<?= $data->d_keluar_qc; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled="">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                         <?php $selected = '';  if ($row->i_bagian == $data->i_tujuan && $row->id_company == $data->id_company_tujuan) { 
                                            $selected = 'selected';
                                        } ?>
                                        <option value="<?= $row->i_bagian; ?>" <?= $selected ?>>
                                            <?= "$row->e_bagian_name - $row->name" ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang Keluar</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2" disabled=''>
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row) : ?>
                                        <option value="<?= $row->id; ?>" <?php if ($row->id == $data->id_jenis_barang_keluar) { ?> selected <?php } ?>>
                                            <?= $row->e_jenis_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-9">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm mr-2" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm mr-2" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="approve" class="btn btn-success btn-block btn-sm mr-2"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-11">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-1" style="text-align: right;">
                <?= $doc; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 3%;">No</th>
                                <th style="width: 15%;">Kode Barang</th>
                                <th style="width: 27%;">Nama Barang Jadi</th>
                                <th style="width: 15%;">Warna</th>
                                <th class="text-right" style="width: 10%;">Quantity</th>
                                <th colspan="2" style="width: 30%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            $counter = 0;
                            $group = "";
                            if ($detail) {
                                foreach ($detail as $row) {
                                    if ($data->id_jenis_barang_keluar == '1') {
                                        $saldo_akhir = $row->saldo_akhir;
                                    } else {
                                        $saldo_akhir = $row->saldo_akhir_repair;
                                    }
                                    if ($group != $row->id_product) {
                                        $counter++;
                                        $j = 0; ?>

                                        <tr class='no tr<?= $counter; ?>'>
                                            <td class="text-center">
                                                <spanx id="snum<?= $counter; ?>"><?= $counter; ?></spanx>
                                            </td>
                                            <td>
                                                <input value="<?= $row->id_product; ?>" type="hidden" readonly id="idproduct<?= $counter; ?>" class="form-control" name="idproduct[]">
                                                <?= $row->i_product_base; ?>
                                            </td>
                                            <td  class="d-flex justify-content-between">
                                                <span>
                                                    <?= $row->e_product_basename; ?>
                                                    <input type="hidden" id="stok<?= $counter; ?>" name="stok<?= $counter; ?>" value="<?= $saldo_akhir; ?>">
                                                </span>
                                                <span>
                                                    <?= $row->e_marker_name; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <input type="hidden" value="<?= $row->id_color; ?>" id="idcolorproduct<?= $counter; ?>" name="idcolorproduct[]">
                                                <?= $row->e_color_name; ?>
                                            </td>
                                            <td class="text-right">
                                                <input type="hidden" value="<?= $row->n_quantity_product; ?>" id="nquantity<?= $counter; ?>" name="nquantity[]">
                                                <?= $row->n_quantity_product; ?>
                                            </td>
                                            <td colspan="2"><?= $row->e_remark; ?></td>
                                        </tr>
                                        <tr class="th<?= $counter; ?> bold table-active">
                                            <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                            <td colspan="7"><b>Bundling Produk</b></td>
                                        </tr>
                                        <?php $o = 1; foreach($bundling as $b) {
                                            if($b->id_keluar_qc_item == $row->id) { 
                                                ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <spanx id="snum<?= $counter; ?>"><?= $o; ?></spanx>
                                                    </td>
                                                    <td>
                                                        <?= $b->i_product_base; ?>
                                                    </td>
                                                    <td  class="d-flex justify-content-between">
                                                        <span>
                                                            <?= $b->e_product_basename; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?= $b->e_color_name; ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?= $b->n_quantity_bundling; ?>
                                                    </td>
                                                    <td colspan="2">
                                                        <?= $b->e_remark; ?>
                                                    </td>
                                                </tr>
                                        <?php
                                                } $o++;
                                            } ?>
                                        <tr class="th<?= $counter; ?> bold">
                                            <td class="text-center"><i class="fa fa-hashtag fa-lg" aria-hidden="true"></i></td>
                                            <td>Kode Material</td>
                                            <td>Nama Material</td>
                                            <td>Satuan</td>
                                            <td class="text-right">Kebutuhan<br>Per PCs</td>
                                            <td class="text-right">Stock Acc<br>Packing</td>
                                            <td class="text-right">Kebutuhan<br>Material</td>
                                        </tr>
                                    <?php }
                                    $group = $row->id_product;
                                    ?>
                                    <tr class="td<?= $counter; ?>">
                                        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                        <td><?= $row->i_material; ?></td>
                                        <td><?= $row->e_material_name; ?></td>
                                        <td><?= $row->e_satuan_name; ?></td>
                                        <td class="text-right">
                                            <span id="n_kebutuhan_perpcs<?= $counter; ?>_<?= $j; ?>">
                                                <?= number_format($row->n_kebutuhan, 4, ".", ",") ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span id="n_stock_material<?= $counter; ?>_<?= $j; ?>">
                                                <?= number_format($row->n_saldo_akhir, 4, ".", ",") ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span class="reset_<?= $counter; ?>" id="n_kebutuhan_material<?= $counter; ?>_<?= $j; ?>">
                                                <?= number_format($row->n_kebutuhan_material, 4, ".", ",") ?>
                                            </span>
                                        </td>
                                    </tr>
                            <?php
                                    $j++;
                                }
                            } ?>
                            <input type="hidden" name="jml" id="jml" value="<?= $counter; ?>">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });
    });

    function berhitung(i) {
        var n_quantity_product = parseFloat($('#nquantity' + i).val());
        if (isNaN(n_quantity_product)) {
            n_quantity_product = 0;
        }
        var ada = 0;
        // console.log($(`#tabledatax .td${i}`).length);
        for (let j = 0; j < $(`#tabledatax .td${i}`).length; j++) {
            var elementKebutuhan = $('#n_kebutuhan_perpcs' + i + '_' + j);
            var n_kebutuhan_perpcs = parseFloat(elementKebutuhan.text());

            var elementStockMaterial = $('#n_stock_material' + i + '_' + j);
            var n_stock_material = parseFloat(elementStockMaterial.text().replace(/[^\d\.]/g,''));
            $('#n_kebutuhan_material' + i + '_' + j).text(n_quantity_product * n_kebutuhan_perpcs);
            var n_kebutuhan_material = parseFloat($('#n_kebutuhan_material' + i + '_' + j).text());
            if (n_kebutuhan_material > n_stock_material) {
                console.log(n_kebutuhan_material, n_stock_material);
                ada = 1;
                break;
            }
        }

        if (ada > 0) {
            swal("Maaf :(", "Jumlah kebutuhan material tidak boleh melebihi stok, mohon untuk dicek kembali :)", "error");
            $('.reset_' + i).text(0);
            return ada;
        }
    }

    $('#approve').click(function(event) {
        var count = 0;
        for (let i = 1; i <= $('#jml').val(); i++) {
            berhitung(i);
            if (berhitung(i) > 0) {
                count = 1;
            };
        }
        /* alert(count);
        return false; */
        if (count == 0) {
            // alert('success');
            statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
        } else {
            // alert('x');
            show("<?= $folder."/cform/approval/".$id."/".$dfrom."/".$dto."/".$i_bagian;?>","#main");
            // return false;
        }
    });
</script>