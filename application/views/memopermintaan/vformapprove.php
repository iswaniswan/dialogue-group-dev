<style type="text/css">
    #tabledatalistx td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lg fa-check mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Permintaan ke Gudang</label>
                        <div class="col-sm-3">
                            <input type="text" name="e_bagian" id="e_bagian" value="<?= $data->e_bagian_name; ?>" readonly class="form-control input-sm">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" required="" id="imemo" value="<?= $data->i_document; ?>" readonly="" class="form-control input-sm">
                            </div>
                            <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= formatdmY($data->d_document); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="e_type" id="e_type" value="<?= $data->e_type_name ?> - <?= $data->company_tujuan ?>" readonly class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="itujuan_kirim" class="col-md-3">Tujuan Kirim</label>
                        <label for="dkirim" class="col-md-3">Tanggal Kirim</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" name="dkirim" required="" id="dkirim" class="form-control input-sm" value="<?= $data->e_tujuan_name ?> - <?= $data->company_tujuan ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="dkirim" required="" id="dkirim" class="form-control input-sm" value="<?= formatdmY($data->d_kirim) ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2 fa-lg"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2 fa-lg"></i>Reject</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="approve" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o mr-2 fa-lg"></i>Approve</button>
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
            <div class="col-sm-6">
                <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
            </div>
            <div class="col-sm-6 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
        </div>
        <div class="table-responsive">
            <table id="table" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Warna</th>
                        <th>Qty</th>
                        <th colspan="3"></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                    $i = 0;
                    $ii = 0;
                    $group = "";
                    if ($datadetail) {
                        foreach ($datadetail as $key) {
                            $ii++;
                            if ($group != $key->id_product) {
                                $i++;
                                if ($i > 1) { ?>
                                    <tr class="table-info">
                                        <td colspan="8">
                                            <hr class="mb-0 mt-0">
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                <tr class="tr tr_first<?= $i; ?>">
                                    <td class="text-center">
                                        <spanlistx id="snum<?= $i; ?>"><b><?= $i; ?></b></spanlistx>
                                    </td>
                                    <td><?= $key->i_product; ?></td>
                                    <td><?= $key->e_product; ?> [ <?= $key->e_marker_name ?> ]</td>
                                    <td><?= $key->e_color_name; ?></td>
                                    <td><?= $key->n_quantity_product; ?></td>
                                    <td colspan="3"></td>
                                </tr>
                                <tr class="table-active tr_second<?= $i; ?>">
                                    <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                    <td><b>Kode Material</b></td>
                                    <td><b>Nama Material</b></td>
                                    <td><b>Satuan</b></td>
                                    <td class="text-right"><b>Kebutuhan Per Pcs</b></td>
                                    <td class="text-right"><b>Stock Material</b></td>
                                    <td class="text-right"><b>Kebutuhan Material</b></td>
                                    <td><b>Keterangan</b></td>
                                </tr>
                            <?php }
                            $group = $key->id_product;
                            ?>
                            <tr class="td_<?= $i; ?>">
                                <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                <td><?= $key->i_material; ?></td>
                                <td><?= $key->e_material_name; ?></td>
                                <td><?= $key->e_satuan_name; ?></td>
                                <td class="text-right"><?= $key->n_kebutuhan; ?></td>
                                <td class="text-right"><?= $key->n_saldo_akhir; ?></td>
                                <td class="text-right"><?= $key->n_quantity; ?></td>
                                <td><?= $key->e_remark; ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<input type="hidden" name="jml_item" id="jml_item" value="<?= $ii; ?>">
<script>
    $(document).ready(function() {
        fixedtable($('#sitabel'));
    });
</script>