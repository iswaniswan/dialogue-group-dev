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
                    <i class="fa fa-pencil fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
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
                                <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                                <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                    <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" required="" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document; ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="idpartner" id="idpartner" required="" class="form-control input-sm select2">
                                    <option value="<?= $data->id_supplier; ?>"><?= $data->e_supplier_name; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Nomor Referensi</label>
                            <label class="col-md-6">Keterangan</label>
                            <div class="col-sm-6">
                                <select type="text" multiple="multiple" name="idreff[]" required="" id="idreff" class="form-control input-sm select2">
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
                                <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                    <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save fa-lg mr-3"></i>Update</button>
                                <?php } ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left fa-lg mr-3"></i>Kembali</button>
                                <?php if ($data->i_status == '1') { ?>
                                    <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o fa-lg mr-3"></i>Send</button>
                                    <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash fa-lg mr-3"></i>Delete</button>
                                <?php } elseif ($data->i_status == '2') { ?>
                                    <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh fa-lg mr-3"></i>Cancel</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <span class="notekode"><b>Note : Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0; $z = 0;
    if ($datadetail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-12">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th>Referensi</th>
                                <th>Kode WIP</th>
                                <th>Nama Barang WIP</th>
                                <th></th>
                                <th colspan="2"></th>
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
                                        <td colspan="2"></td>
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
                                            <td colspan="2"></td>
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
                                            <td class="text-right"><b>Jml Terima</b></td>
                                            <td colspan="2"><b>Keterangan</b></td>
                                        </tr>
                                <?php }
                                } 
                                $group = $key->id_document . $key->id_keluar;?>
                                <tr class="table-warning cat_eye_<?= $z; ?>">
                                    <td class="text-center">#</td>
                                    <td>
                                        <input type="hidden" id="idreferensiitem<?= $i;?>" name="idreferensiitem<?= $i;?>" value="<?= $key->id; ?>">
                                        <input type="hidden" id="idmaterial<?= $i;?>" name="idmaterial<?= $i;?>" value="<?= $key->id_material_masuk; ?>">
                                        <input type="hidden" id="nqty<?= $i;?>" name="nqty<?= $i;?>" value="<?= $key->n_quantity_masuk; ?>">
                                        <input type="hidden" id="iddocument<?= $i;?>" name="iddocument<?= $i;?>" value="<?= $key->id_document; ?>">
                                        <input type="hidden" id="idmateriallist<?= $i;?>" name="idmateriallist<?= $i;?>" value="<?= $key->id_material_list; ?>">
                                        <input class="form-control input-sm" readonly type="text" id="idocument<?= $i;?>" name="idocument<?= $i;?>" value="<?= $key->i_document; ?>">
                                    </td>
                                    <td><input class="form-control input-sm" readonly type="text" id="imateriallist<?= $i;?>" name="imateriallist<?= $i;?>" value="<?= $key->i_material_list; ?>"></td>
                                    <td><input class="form-control input-sm" readonly type="text" id="emateriallist<?= $i;?>" name="emateriallist<?= $i;?>" value="<?= htmlentities($key->e_material_list); ?>"></td>
                                    <td><input readonly class="form-control input-sm" type="text" id="esatuanlist<?= $i;?>" name="esatuanlist<?= $i;?>" value="<?= $key->e_satuan_list; ?>"></td>
                                    <td><input class="form-control input-sm text-right" type="text" readonly id="nqtylistsisa<?= $i;?>" name="nqtylistsisa<?= $i;?>" value="<?= $key->n_quantity_sisa; ?>"></td>
                                    <td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nqtylist<?= $i;?>" name="nqtylist<?= $i;?>" onkeyup="angkahungkul(this); cekjml(<?= $i;?>);" value="<?= $key->n_quantity_list; ?>"></td>
                                    <td colspan="2"><input class="form-control input-sm" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!"></td>
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
    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/

    $(document).ready(function() {
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.tgl', 0);

        /* $('#idtype').change(function(event) {
            $('#idpartner').val('');
            $('#idpartner').html('');
            $('#idreff').val('');
            $('#idreff').html('');
            $("#tabledatay tr:gt(0)").remove();
            $("#jml").val(0);
        }); */

        $('#idpartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/partner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        idtype: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event) {
            $('#idreff').val('');
            $('#idreff').html('');
            $("#tabledatay tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#idreff').select2({
            placeholder: 'Cari Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        idpartner: $('#idpartner').val(),
                        idtype: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function() {

            /*----------  GET DATA DETAIL AFTER CHANGE REFERENSI  ----------*/

            $.ajax({
                type: "post",
                data: {
                    'id': $(this).val(),
                },
                url: '<?= base_url($folder . '/cform/detailreferensi'); ?>',
                dataType: "json",
                success: function(data) {
                    // console.log(data['data']['id_type_makloon'].length);
                    if (data['data']) {
                        $('#dreff').val(data['data']['d_date']);
                        $('#idtype').val(data['data']['id_type_makloon']);
                    }

                    if (data['detail'].length > 0) {
                        $("#tabledatay").attr("hidden", false);
                        $("#detail").attr("hidden", false);
                        $("#tabledatay tr:gt(0)").remove();
                        $('#jml').val(data['detail'].length);

                        var jmllist = data['detail'].length;

                        var no = 1;
                        var group = "";
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols = "";
                            var cols1 = "";
                            var cols2 = "";
                            var newRow = $("<tr class='table-active'>");
                            var newRow1 = $("<tr class='table-success'>");
                            if (group == "") {
                                cols += `<td class="text-center">${no}</td>
                                    <td>${data['detail'][x]['i_document']}</td>
                                    <td>${data['detail'][x]['i_product_wip']}</td>
                                    <td>${data['detail'][x]['e_product_wipname']}</td>
                                    <td>${data['detail'][x]['i_material']}</td>
                                    <td colspan="2">${data['detail'][x]['e_material_name']}</td>
                                    <td>${data['detail'][x]['e_satuan_name']}</td>
                                    <td class="text-right">${data['detail'][x]['n_quantity']}</td></tr>`;
                                cols2 += `<td><a href="#" onclick="toge(${no}); return false;" class="toggler${no}" data-icon-name="fa-eye" data-prod-cat="eye_${no}"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                    <td><b>List Detail Barang</b></td>
                                    <td><b>Kode Material</b></td>
                                    <td><b>Nama Material</b></td>
                                    <td><b>Satuan</b></td>
                                    <td class="text-right"><b>Jml Kirim</b></td>
                                    <td class="text-right"><b>Jml Terima</b></td>
                                    <td colspan="2"><b>Keterangan</b></td>
                                    </tr>`;
                            } else {
                                if (group != data['detail'][x]['id_document'] + data['detail'][x]['id_material']) {
                                    var newRow = $("<tr class='table-active'>");
                                    no++;
                                    cols += `<td class="text-center">${no}</td>
                                    <td>${data['detail'][x]['i_document']}</td>
                                    <td>${data['detail'][x]['i_product_wip']}</td>
                                    <td>${data['detail'][x]['e_product_wipname']}</td>
                                    <td>${data['detail'][x]['i_material']}</td>
                                    <td colspan="2">${data['detail'][x]['e_material_name']}</td>
                                    <td>${data['detail'][x]['e_satuan_name']}</td>
                                    <td class="text-right">${data['detail'][x]['n_quantity']}</td></tr>`;
                                    cols2 += `<td><a href="#" onclick="toge(${no}); return false;" class="toggler${no}" data-icon-name="fa-eye" data-prod-cat="eye_${no}"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                    <td><b>List Detail Barang</b></td>
                                    <td><b>Kode Material</b></td>
                                    <td><b>Nama Material</b></td>
                                    <td><b>Satuan</b></td>
                                    <td class="text-right"><b>Jml Kirim</b></td>
                                    <td class="text-right"><b>Jml Terima</b></td>
                                    <td colspan="2"><b>Keterangan</b></td>
                                    </tr>`;
                                }
                            }
                            newRow.append(cols);
                            newRow1.append(cols2);
                            $("#tabledatay").append(newRow);
                            $("#tabledatay").append(newRow1);
                            group = data['detail'][x]['id_document'] + data['detail'][x]['id_material'];
                            var newRow2 = $(`<tr class="table-warning cat_eye_${no}">`);
                            cols1 += `<td class="text-center">#</td>';
                            <td>
                            <input type="hidden" id="idreferensiitem${x}" name="idreferensiitem${x}" value="${data['detail'][x]['id']}">
                            <input type="hidden" id="idmaterial${x}" name="idmaterial${x}" value="${data['detail'][x]['id_material']}">
                            <input type="hidden" id="nqty${x}" name="nqty${x}" value="${data['detail'][x]['n_quantity_sisa']}">
                            <input type="hidden" id="iddocument${x}" name="iddocument${x}" value="${data['detail'][x]['id_document']}">
                            <input type="hidden" id="idmateriallist${x}" name="idmateriallist${x}" value="${data['detail'][x]['id_material_list']}">
                            <input class="form-control input-sm" readonly type="text" id="idocument${x}" name="idocument${x}" value="${data['detail'][x]['i_document']}"></td>
                            <td><input class="form-control input-sm" readonly type="text" id="imateriallist${x}" name="imateriallist${x}" value="${data['detail'][x]['i_material_list']}"></td>
                            <td><input class="form-control input-sm" readonly type="text" id="emateriallist${x}" name="emateriallist${x}" value="${htmlentity(data['detail'][x]['e_material_list'])}"></td>
                            <td><input readonly class="form-control input-sm" type="text" id="esatuanlist${x}" name="esatuanlist${x}" value="${data['detail'][x]['e_satuan_list']}"></td>
                            <td><input class="form-control input-sm text-right" type="text" readonly id="nqtylistsisa${x}" name="nqtylistsisa${x}" value="${data['detail'][x]['n_quantity_list_sisa']}"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nqtylist${x}" name="nqtylist${x}" onkeyup="angkahungkul(this); cekjml(${x});" value="${data['detail'][x]['n_quantity_list_sisa']}"></td>
                            <td colspan="2"><input class="form-control input-sm" type="text" id="eremark${x}" name="eremark${x}" value="" placeholder="Isi keterangan jika ada!"></td></tr>`;
                            newRow2.append(cols1);
                            $("#tabledatay").append(newRow2);
                        }
                    }
                },
                error: function() {
                    swal('Ada Kesalahan :(');
                    $("#tabledatay tr:gt(0)").remove();
                    $("#jml").val(0);
                }
            })
        });

        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                var d1 = splitdate($('#ddocument').val());
                var d2 = splitdate($('#dreff').val());
                if ((d1 != null || d1 != '') && (d2 != null || d2 != '')) {
                    if (d1 < d2) {
                        swal('Maaf', 'Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!!!', 'error');
                        $('#ddocument').val($('#dreff').val());
                        return false;
                    }
                } else {
                    swal('Maaf', 'Tanggal Dokumen Tidak Boleh Kosong!!!', 'error');
                    return false;
                }
                if ($("#jml").val() == 0) {
                    swal('Isi data item minimal 1 !!!');
                    return false;
                } else {
                    swal({
                        title: "Update Data Ini?",
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Update!",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            type: "POST",
                            data: $("form").serialize(),
                            url: '<?= base_url($folder . '/cform/update/'); ?>',
                            dataType: "json",
                            success: function(data) {
                                if (data.sukses == true) {
                                    swal("Sukses!", "No Dokumen : " + data.kode +
                                        ", Berhasil Diupdate :)", "success");
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#send").attr("disabled", false);
                                } else if (data.sukses == 'ada') {
                                    swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                                } else {
                                    swal("Maaf :(", "No Dokumen : " + data.kode +
                                        ", Gagal Diupdate :(", "error");
                                }
                            },
                            error: function() {
                                swal("Maaf", "Data Gagal Disimpan :(", "error");
                            }
                        });
                    });
                }
            }
            return false;
        });
    });

    /*----------  CEK QTY HEADER  ----------*/

    function cekqty(i, jml) {
        if (parseInt($('#nquantity' + i).val()) > parseInt($('#nquantitysisa' + i).val())) {
            swal('Maaf', 'Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = ' + $('#nquantitysisa' + i).val() + '!', 'error');
            $('#nquantity' + i).val($('#nquantitysisa' + i).val());
        }
    }

    /*----------  CEK QTY ITEM  ----------*/

    function cekjml(i) {
        if (parseInt($('#nqtylist' + i).val()) > parseInt($('#nqtylistsisa' + i).val())) {
            swal('Maaf', 'Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = ' + $('#nqtylistsisa' + i).val() + '!', 'error');
            $('#nqtylist' + i).val($('#nqtylistsisa' + i).val());
        }
    }

    /*----------  SET VALUE DETAIL  ----------*/

    function hetang(qty, id, iddoc) {
        for (var i = 0; i < $('#jml').val(); i++) {
            if (id == $("#idmaterial" + i).val() && iddoc == $("#iddocument" + i).val()) {
                if (qty == '') {
                    qty = 0;
                }
                $('#nqty' + i).val(qty);
            }
        }
    }

    /*----------  NOMOR DOKUMEN  ----------*/

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#isj').val(data);
            },
            error: function() {
                swal('Error :(');
            }
        });
    }

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