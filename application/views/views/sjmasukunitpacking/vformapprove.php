<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Surat Jalan Dari Supplier</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name; ?>">
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="idocumentold" id="isjold" value="<?= $data->i_document; ?>">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="idocumentsup" name="idocumentsup" class="form-control input-sm" required="" value="<?= $data->i_document_supplier; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Partner</label>
                        <label class="col-sm-3">Refernsi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ipartner" id="ipartner" value="<?= $data->e_supplier_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ireffeks" id="ireffeks" multiple="multiple" class="form-control select2" disabled="true">
                                <?php foreach ($referensi as $row) { ?>
                                    <option value="<?= $row->id; ?>" selected><?= $row->i_document; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250" readonly=""><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="cr" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="rj" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                        </div>
                        <div class="col-sm-3">
                            <button id="submit" type="button" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
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
                            <th class="text-center">No</th>
                            <th>No. Dok Keluar</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th class="text-right">Quantity Keluar</th>
                            <th class="text-right">Quantity Sisa</th>
                            <th class="text-right">Quantity Masuk</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) {
                            $i++;
                        ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $key->i_document; ?>
                                    <input hidden class="form-control input-sm" readonly id="id_document<?= $i; ?>" name="id_document<?= $i; ?>" value="<?= $key->id_document_reff; ?>">
                                    <input hidden class="form-control input-sm" readonly id="id_product<?= $i; ?>" name="id_product<?= $i; ?>" value="<?= $key->id_product; ?>">
                                </td>
                                <td><?= $key->i_product_base . ' - ' . $key->e_product_basename; ?></td>
                                <td><?= $key->e_color_name; ?></td>
                                <td class="text-right">
                                    <?= $key->keluarfull; ?>
                                    <input hidden class="form-control text-right" readonly id="nquantitykeluar<?= $i; ?>" name="nquantitykeluar<?= $i; ?>" value="<?= $key->keluarfull; ?>">
                                </td>
                                <td class="text-right">
                                    <?= $key->keluar; ?>
                                    <input hidden class="form-control text-right" readonly id="sisa<?= $i; ?>" name="sisa<?= $i; ?>" value="<?= $key->keluar; ?>" readonly>
                                </td>
                                <td class="text-right">
                                    <?= $key->masuk; ?>
                                    <input hidden class="form-control text-right" id="nquantity<?= $i; ?>" placeholder="0" name="nquantity<?= $i; ?>" value="<?= $key->masuk; ?>" readonly>
                                </td>
                                <td><?= $key->e_remark; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    function konfirm() {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantity' + i).val()) > parseInt($('#sisa' + i).val())) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                ada = true;
                return false;
            }
        }
        if (!ada) {
            return true;
        } else {
            return false;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#cr").attr("disabled", true);
        $("#rj").attr("disabled", true);
    });

    $('.select2').select2();
</script>