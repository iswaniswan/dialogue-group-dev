<style type="text/css">
    .font {
        font-size: 16px;
        background-color: #e1f1e4;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-check fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Partner</label>
                            <div class="col-sm-3">
                                <input class="form-control input-sm" type="text" readonly name="ibagianold" id="ibagianold" value="<?= $data->e_bagian_name; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                    <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" required="" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document; ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control input-sm" type="text" readonly value="<?= $data->e_supplier_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Nomor Referensi</label>
                            <label class="col-md-6">Keterangan</label>
                            <div class="col-sm-6">
                                <select type="text" disabled multiple="multiple" name="idreff[]" required="" id="idreff" class="form-control input-sm select2">
                                    <?php if ($referensi) {
                                        foreach ($referensi->result() as $key) { ?>
                                            <option value="<?= $key->id; ?>" selected><?= 'Nomor : ' . $key->i_document . ', Tanggal : ' . $key->d_document; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                                <input type="hidden" name="dreff" id="dreff" class="form-control input-sm" value="<?= $tanggal; ?>">
                                <input type="hidden" name="idtype" id="idtype" class="form-control input-sm" value="<?= $data->id_type_makloon; ?>">
                            </div>
                            <div class="col-sm-6">
                                <textarea type="text" readonly id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
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
    <?php $i = 0;
    $z = 0;
    if ($datadetail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-12">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th>Referensi</th>
                                <th>Kode WIP</th>
                                <th>Nama Barang WIP</th>
                                <th></th>
                                <th colspan="3"></th>
                                <th></th>
                                <th class="text-right" width="7%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $group = "";
                            foreach ($datadetail as $key) {
                                if ($group != $key->id_document . $key->id_keluar) {
                                    $z++;
                                }
                                if ($group == "") { ?>
                                    <tr class='table-active'>
                                        <td class="text-center"><?= $z; ?></td>
                                        <td><?= $key->i_document; ?></td>
                                        <td><?= $key->i_product_wip; ?></td>
                                        <td><?= $key->e_product_wipname; ?></td>
                                        <td></td>
                                        <td colspan="3"></td>
                                        <td></td>
                                        <td class="text-right"></td>
                                    </tr>
                                    <tr class='table-success'>
                                        <td><a href="#" onclick="toge(<?= $z; ?>); return false;" class="toggler<?= $z; ?>" data-icon-name="fa-eye" data-prod-cat="eye_<?= $z; ?>"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                        <td><b>List Detail Barang</b></td>
                                        <td><b>Kode Material</b></td>
                                        <td><b>Nama Material</b></td>
                                        <td><b>Satuan</b></td>
                                        <td class="text-right"><b>Jml Kirim</b></td>
                                        <td class="text-right"><b>Jml Sisa</b></td>
                                        <td class="text-right"><b>Jml Terima</b></td>
                                        <td colspan="2"><b>Keterangan</b></td>
                                    </tr>
                                    <?php } else {
                                    if ($group != $key->id_document . $key->id_keluar) {
                                        /* $z++; */ ?>
                                        <tr class='table-active'>
                                            <td class="text-center"><?= $z; ?></td>
                                            <td><?= $key->i_document; ?></td>
                                            <td><?= $key->i_product_wip; ?></td>
                                            <td><?= $key->e_product_wipname; ?></td>
                                            <td></td>
                                            <td colspan="3"></td>
                                            <td></td>
                                            <td class="text-right"></td>
                                        </tr>
                                        <tr class='table-success'>
                                            <td><a href="#" onclick="toge(<?= $z; ?>); return false;" class="toggler<?= $z; ?>" data-icon-name="fa-eye" data-prod-cat="eye_<?= $z; ?>"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                            <td><b>List Detail Barang</b></td>
                                            <td><b>Kode Material</b></td>
                                            <td><b>Nama Material</b></td>
                                            <td><b>Satuan</b></td>
                                            <td class="text-right"><b>Jml Kirim</b></td>
                                            <td class="text-right"><b>Jml Sisa</b></td>
                                            <td class="text-right"><b>Jml Terima</b></td>
                                            <td colspan="2"><b>Keterangan</b></td>
                                        </tr>
                                <?php }
                                }
                                $group = $key->id_document . $key->id_keluar; ?>
                                <tr class="table-warning cat_eye_<?= $z; ?>">
                                    <td class="text-center">#</td>
                                    <td>
                                        <input type="hidden" id="idreferensiitem<?= $i; ?>" name="idreferensiitem<?= $i; ?>" value="<?= $key->id; ?>">
                                        <input type="hidden" id="idmaterial<?= $i; ?>" name="idmaterial<?= $i; ?>" value="<?= $key->id_material_masuk; ?>">
                                        <input type="hidden" id="nqty<?= $i; ?>" name="nqty<?= $i; ?>" value="<?= $key->n_quantity_sisa; ?>">
                                        <input type="hidden" id="iddocument<?= $i; ?>" name="iddocument<?= $i; ?>" value="<?= $key->id_document; ?>">
                                        <input type="hidden" id="idmateriallist<?= $i; ?>" name="idmateriallist<?= $i; ?>" value="<?= $key->id_material_list; ?>">
                                        <?= $key->i_document; ?>
                                    </td>
                                    <td><?= $key->i_material_list; ?></td>
                                    <td><?= htmlentities($key->e_material_list); ?></td>
                                    <td><?= $key->e_satuan_list; ?></td>
                                    <td class="text-right"><?= $key->n_quantity_masuk; ?></td>
                                    <td class="text-right"><?= $key->n_quantity_sisa; ?></td>
                                    <td class="text-right"><?= $key->n_quantity_list; ?></td>
                                    <td colspan="2"><?= $key->e_remark; ?></td>
                                </tr>
                            <?php
                                $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
</form>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    function toge(i) {
        $('.cat_' + $(".toggler" + i).attr('data-prod-cat')).toggle();

        //Remove the icon class
        if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
            //then change back to the original one
            $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
        } else {
            //Remove the cross from all other icons
            $('.faq-links').each(function() {
                if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
                    $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
                }
            });

            $(".toggler" + i).find('i').addClass('fa-eye-slash').removeClass($(".toggler" + i).data('icon-name'));
        }
    }
</script>