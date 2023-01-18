<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2 fa-lg"></i></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Pembuat Dokumen</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $datahead->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?= $datahead->id; ?>" readonly="">
                            <input type="text" name="idocument" id="idocument" class="form-control input-sm" value="<?= $datahead->i_document; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $datahead->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <textarea name="eremarkh" id="eremarkh" class="form-control input-sm" readonly><?= $datahead->e_remark; ?></textarea>
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
    <div class="row">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
        </div>
        <div class="col-sm-6 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
    </div>
    <div class="table-responsive">
        <table id="sitabel" class="table color-table inverse-table table-bordered class" cellspacing="0" width="100%">
            <caption>Detail Material</caption>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th>Kode</th>
                    <th>Nama Material</th>
                    <th>Satuan</th>
                    <th class="text-right">Jumlah SO</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0;
                if ($datadetail) {
                    foreach ($datadetail as $key) {
                        $i++;
                ?>
                        <tr>
                            <td class="text-center">
                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                            </td>
                            <td><?= $key["i_material"]; ?></td>
                            <td><?= $key["e_material_name"]; ?></td>
                            <td><?= $key['e_satuan_name']; ?></td>
                            <td class="text-right"><?= $key["n_quantity"]; ?></td>
                            <td><?= $key["e_remark"]; ?></td>
                        </tr>
                <?php
                    }
                }

                ?>
                <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
            </tbody>
        </table>
    </div>
</div>
</form>
<script>
    $(document).ready(function() {
        fixedtable($('#sitabel'));
    });
</script>