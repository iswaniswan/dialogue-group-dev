<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Pengirim</label>
                        <div class="col-sm-3">
                            <!-- <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select> -->
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $id; ?>">
                            <input type="text" id="e_bagian" name="ibagian" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SJ-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm" value="<?= $data->d_document; ?>" placeholder="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="e_tujuan" name="itujuan" class="form-control input-sm" value="<?= $data->e_tujuan_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-3">Tanggal Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <!-- <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_reff; ?></option>
                            </select> -->
                            <input type="text" id="reff" name="reff" class="form-control input-sm" value="<?= $data->i_reff; ?>" readonly>
                            <input type="hidden" id="idreff" name="idreff" class="form-control" value="<?= $data->id_document_reff; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly=""><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm mr-2" onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm mr-2" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
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
</div>
<?php $i = 0;
if ($datadetail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-right">Qty (Pengembalian)</th>
                            <!-- <th class="text-right">Qty Sisa Retur</th> -->
                            <th class="text-right">Qty</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $z = 0;
                        $group = "";
                        foreach ($datadetail as $key) {
                            $i++; ?>
                            <tr class="del<?= $i; ?>">
                                <td class="text-center">
                                    <?= $i; ?>
                                </td>
                                <td><?= $key->i_product_wip; ?>
                                    <input type="hidden" name="idproductwip[]" id="idproductwip<?= $i; ?>" value="<?= $key->id_product_wip; ?>">
                                </td>
                                <td><?= $key->e_product_wipname; ?></td>
                                <td class="text-right"><?= $key->n_quantity_wip_keluar; ?>
                                    <input style="width:100px;" readonly class="form-control qty input-sm text-right" autocomplete="off" type="hidden" name="nquantitywipkeluar<?= $i; ?>" id="nquantitywipkeluar<?= $i; ?>" value="<?= $key->n_quantity_wip_keluar; ?>">
                                </td>
                                <td hidden class="text-right"><?= $key->n_quantity_wip_sisa; ?>
                                    <input style="width:100px;" readonly class="form-control qty input-sm text-right" autocomplete="off" type="hidden" name="nquantitywipsisa<?= $i; ?>" id="nquantitywipsisa<?= $i; ?>" value="<?= $key->n_quantity_wip_sisa; ?>">
                                </td>
                                <td class="text-right"><?= $key->n_quantity_wip_masuk; ?>
                                    <input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="hidden" name="nquantitywipmasuk<?= $i; ?>" id="nquantitywipmasuk<?= $i; ?>" value="<?= $key->n_quantity_wip_masuk; ?>" readonly>
                                </td>
                                <td><?= $key->e_remark; ?></td>
                            </tr>

                        <?php } ?>
                        <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </form>
<?php } ?>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            //alert($('#nquantitywipsisa'+i).val());
            if (parseInt($('#nquantitywipmasuk' + i).val()) > parseInt($('#nquantitywipsisa' + i).val())) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                //$('#nquantitywipmasuk'+i).val($('#nquantitywipsisa'+i).val());
                //$('#nquantitymaterialmasuk'+i).val($('#nquantitymaterialsisa'+i).val());
                ada = true;
                return false;
            }
        }
        if (!ada) {
            statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
        } else {
            return false;
        }
    });
</script>