<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-check fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Referensi</label>
                            <div class="col-sm-3">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                    <input type="text" name="i_document" readonly="" class="form-control input-sm" value="<?= $data->i_document; ?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= formatdmY($data->d_document); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document_referensi; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o fa-lg mr-2"></i>Change Requested</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times fa-lg mr-2"></i>Reject</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o fa-lg mr-2"></i>Approve</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="sitabel" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th>WIP</th>
                        <th>Nama WIP</th>
                        <th>Material</th>
                        <th>Nama Material</th>
                        <th>Satuan</th>
                        <th class="text-right">Qty Kirim</th>
                        <th class="text-right">Qty Terima</th>
                        <th class="text-right">Jml Gelar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $key) { ?>
                            <tr>
                                <td class="text-center"><?= $i + 1; ?></td>
                                <td><?= $key->i_product_wip; ?></td>
                                <td><?= $key->e_product_wipname.' - '.$key->e_color_name; ?></td>
                                <td><?= $key->i_material; ?></td>
                                <td><?= $key->e_material_name; ?></td>
                                <td><?= $key->e_satuan_name; ?></td>
                                <td class="text-right"><?= $key->n_quantity_reff; ?></td>
                                <td class="text-right"><?= $key->n_quantity; ?></td>
                                <td class="text-right"><?= $key->n_jumlah_gelar; ?></td>
                                <td><?= $key->e_remark; ?></td>
                            </tr>
                    <?php $i++;
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
</form>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    $(document).ready(function() {
        fixedtable($('#sitabel'));
    });
</script>