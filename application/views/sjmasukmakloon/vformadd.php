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
                <i class="fa fa-plus fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
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
                                        <option value="<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="">
                                <input type="text" name="idocument" required="" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="idpartner" id="idpartner" required="" class="form-control input-sm select2"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-6">
                            <select type="text" multiple="multiple" name="idreff[]" required="" id="idreff" class="form-control input-sm select2"></select>
                            <input type="hidden" name="dreff" id="dreff" class="form-control input-sm">
                            <input type="hidden" name="idtype" id="idtype" class="form-control input-sm">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save fa-lg mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" disabled="true" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o fa-lg mr-2"></i>Send</button>
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
<div class="white-box" id="detail" hidden="true">
    <div class="col-sm-12">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%" hidden="true">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th>Referensi</th>
                        <th>Kode WIP</th>
                        <th>Nama Barang WIP</th>
                        <th>Kode Material</th>
                        <th colspan="2">Nama Material</th>
                        <th>Satuan</th>
                        <th class="text-right" width="7%">Jml Kirim</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
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
        // $('#isj').mask('SS-0000-000000S');
        number();
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
                                    <td></td>
                                    <td colspan="2"></td>
                                    <td></td>
                                    <td class="text-right"></td></tr>`;
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
                                if (group != data['detail'][x]['id_document'] + data['detail'][x]['id_keluar']) {
                                    var newRow = $("<tr class='table-active'>");
                                    no++;
                                    cols += `<td class="text-center">${no}</td>
                                    <td>${data['detail'][x]['i_document']}</td>
                                    <td>${data['detail'][x]['i_product_wip']}</td>
                                    <td>${data['detail'][x]['e_product_wipname']}</td>
                                    <td></td>
                                    <td colspan="2"></td>
                                    <td></td>
                                    <td class="text-right"></td></tr>`;
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
                            group = data['detail'][x]['id_document'] + data['detail'][x]['id_keluar'];
                            var newRow2 = $(`<tr class="table-warning cat_eye_${no}">`);
                            cols1 += `<td class="text-center">#</td>';
                            <td>
                            <input type="hidden" id="idreferensiitem${x}" name="idreferensiitem${x}" value="${data['detail'][x]['id']}">
                            <input type="hidden" id="idmaterial${x}" name="idmaterial${x}" value="${data['detail'][x]['id_material_masuk']}">
                            <input type="hidden" id="nqty${x}" name="nqty${x}" value="${data['detail'][x]['n_quantity_masuk']}">
                            <input type="hidden" id="iddocument${x}" name="iddocument${x}" value="${data['detail'][x]['id_document']}">
                            <input type="hidden" id="idmateriallist${x}" name="idmateriallist${x}" value="${data['detail'][x]['id_material_masuk']}">
                            <input class="form-control input-sm" readonly type="text" id="idocument${x}" name="idocument${x}" value="${data['detail'][x]['i_document']}"></td>
                            <td><input class="form-control input-sm" readonly type="text" id="imateriallist${x}" name="imateriallist${x}" value="${data['detail'][x]['i_material_masuk']}"></td>
                            <td><input class="form-control input-sm" readonly type="text" id="emateriallist${x}" name="emateriallist${x}" value="${htmlentity(data['detail'][x]['e_material_masuk'])}"></td>
                            <td><input readonly class="form-control input-sm" type="text" id="esatuanlist${x}" name="esatuanlist${x}" value="${data['detail'][x]['e_satuan_masuk']}"></td>
                            <td><input class="form-control input-sm text-right" type="text" readonly id="nqtylistsisa${x}" name="nqtylistsisa${x}" value="${data['detail'][x]['n_quantity_sisa']}"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nqtylist${x}" name="nqtylist${x}" onkeyup="angkahungkul(this); cekjml(${x});" value="${data['detail'][x]['n_quantity_sisa']}"></td>
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

        /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/

        $('#ceklis').click(function(event) {
            if ($('#ceklis').is(':checked')) {
                $("#isj").attr("readonly", false);
            } else {
                $("#isj").attr("readonly", true);
                $("#ada").attr("hidden", true);
                number();
            }
        });

        /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/

        $("#isj").keyup(function() {
            $.ajax({
                type: "post",
                data: {
                    'kode': $(this).val(),
                    'ibagian': $('#ibagian').val(),
                },
                url: '<?= base_url($folder . '/cform/cekkode'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data == 1) {
                        $("#ada").attr("hidden", false);
                        $("#submit").attr("disabled", true);
                    } else {
                        $("#ada").attr("hidden", true);
                        $("#submit").attr("disabled", false);
                    }
                },
                error: function() {
                    swal('Error :(');
                }
            });
        });

        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  UPDATE NO DOKUMEN SAAT TANGGAL DOKUMEN DAN BAGIAN PEMBUAT DIRUBAH  ----------*/

        $('#ddocument, #ibagian').change(function(event) {
            number();
        });

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/

        // $('#submit').click(function(event) {
        //     var d1 = splitdate($('#ddocument').val());
        //     var d2 = splitdate($('#dreffhide').val());
        //     if ((d1 != null || d1 != '') && (d2 != null || d2 != '')) {
        //         if (d1 < d2) {
        //             swal('Maaf', 'Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!!!', 'error');
        //             $('#ddocument').val($('#dreffhide').val());
        //             return false;
        //         }
        //     } else {
        //         swal('Maaf', 'Tanggal Dokumen Tidak Boleh Kosong!!!', 'error');
        //         return false;
        //     }
        //     if ($("#jml").val() == 0) {
        //         swal('Isi data item minimal 1 !!!');
        //         return false;
        //     } else {
        //         // for (var i = 0; i < $("#jml").val(); i++) {
        //         //     if($("#nqty"+i).val()=='' || $("#nqty"+i).val()==null || $("#nqty"+i).val()==0){
        //         //         swal('Maaf :(','Jumlah Pemenuhan Harus Lebih Besar Dari 0!','error');
        //         //         return false;
        //         //     }
        //         // }
        //     }
        // });

        // /*----------  KONDISI SETELAH MENEKAN TOMBOL SIMPAN  ----------*/

        // $("form").submit(function(event) {
        //     event.preventDefault();
        //     $("input").attr("disabled", true);
        //     $("select").attr("disabled", true);
        //     $("#submit").attr("disabled", true);
        //     $("#send").attr("hidden", false);
        // });

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
                        title: "Simpan Data Ini?",
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Simpan!",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            type: "POST",
                            data: $("form").serialize(),
                            url: '<?= base_url($folder . '/cform/simpan/'); ?>',
                            dataType: "json",
                            success: function(data) {
                                if (data.sukses == true) {
                                    $('#id').val(data.id);
                                    swal("Sukses!", "No Dokumen : " + data.kode +
                                        ", Berhasil Disimpan :)", "success");
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("disabled", false);
                                } else if (data.sukses == 'ada') {
                                    swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                                } else {
                                    swal("Maaf :(", "No Dokumen : " + data.kode +
                                        ", Gagal Disimpan :(", "error");
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

        // if (parseInt($('#nqtylist'+i).val()) <= 0 ) {
        //     swal('Maaf :(','Jumlah Pemenuhan List Harus Lebih Besar dari 0!','error');
        //     $('#nqtylist'+i).val($('#nqtylistsisa'+i).val());
        // }
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
        /* $(".toggler"+i).click(function(e) {
            e.preventDefault(); */
        $('.cat_' + $(".toggler" + i).attr('data-prod-cat')).toggle();
        // console.log($(".toggler" + i).find('i'));
        // $(".toggler"+i).addClass('active');

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
        // });
    }
</script>