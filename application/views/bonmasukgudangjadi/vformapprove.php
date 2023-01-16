<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Bagian Pengirim</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" id="id" name="id" value="<?= $data->id; ?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document; ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_pengirim; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen Reff</label>
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_referensi;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_jenis_name;?>">
                        </div>
                        <div class="col-sm-6">
                            <textarea readonly="" type="text" class="form-control input-sm"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="approve" class="btn btn-success btn-block btn-sm"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0;
if ($datadetail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-3">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th width="10%">Kode</th>
                            <th width="30%">Nama Barang</th>
                            <th width="12%">Warna</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Qty Terima</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $i + 1; ?></td>
                                <td>
                                    <?= $key->i_product; ?>
                                    <input type="hidden" id="iproduct<?= $i; ?>" name="iproduct<?= $i; ?>" value="<?= $key->i_product; ?>">
                                </td>
                                <td><?= $key->e_product; ?></td>
                                <td><?= $key->e_color_name; ?></td>
                                <td class="text-right">
                                    <?= $key->n_quantity_reff; ?>
                                    <input type="hidden" id="nquantity<?= $i; ?>" name="nquantity<?= $i; ?>" value="<?= $key->n_quantity_sisa; ?>">
                                </td>
                                <td class="text-right">
                                    <?= $key->n_quantity; ?>
                                    <input type="hidden" id="npemenuhan<?= $i; ?>" name="npemenuhan<?= $i; ?>" value="<?= $key->n_quantity; ?>">
                                </td>
                                <td><?= $key->e_remark; ?></td>
                            </tr>
                        <?php $i++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<?php } ?>
<script>
    $('#approve').click(function(event) {
        var habis = false;
        var iproduct = '';
        for (var x = 0; x < $("#jml").val(); x++) {
            if (parseInt($('#npemenuhan' + x).val()) > parseInt($('#nquantity' + x).val())) {
                var habis = true;
                iproduct += $('#iproduct' + x).val() + ', ';
            }
        }

        var n = iproduct.length - 2;
        if (habis == false) {
            statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
        } else {
            swal("Yaaahhh :(", "Qty Pemenuhan Kode " + iproduct.substr(0, n) + " Melebihi Qty Sisa :(", "error");
            return false;
        }
    });
</script>