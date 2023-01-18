<style>
    .form-group {
        margin-bottom: 10px !important;
    }

    .table>thead>tr>th {
        padding: 6px 6px;
    }

    .dropify-wrapper {
        height: 174px !important;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus fa-lg mr-2"></i><?= $title; ?>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Group Jahit</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->i_bagian; ?>">
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control input-sm" name="idocument" id="docure" value="<?= $format ?>">
                                <input type="hidden" name="id" id="id">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" onchange="number();" readonly class="form-control input-sm date" name="ddocument" id="ddocument" value="<?php echo date("d-m-Y"); ?>">
                            </div>
                            <div class="col-sm-3">
                                <textarea id="group_jahit" name="group_jahit" class="form-control input-sm" required placeholder="Isi Group Jahit"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Referensi</label>
                            <div class="col-sm-12">
                                <select name="id_uraian" id="id_uraian" class="form-control id_uraian select2" required="">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save fa-lg mr-2"></i>Simpan</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main');"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" onclick="tambah($('#jml').val());" id="addrow" class="btn btn-info btn-block btn-sm"><i class="fa fa-plus fa-lg mr-2"></i>Item</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o fa-lg mr-2"></i>Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class=""></i><?= "Upload Schedule"; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> <?= $title_list; ?></a>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-6">Upload File (Optional)</label>
                            <label class="col-md-6 text-right notekode">Formatnya .xls</label>
                            <div class="col-sm-12">
                                <input type="file" id="input-file-now" name="userfile" class="dropify" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload</button>
                            </div>
                            <div class="col-md-6">
                                <a id="href" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Template</button> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Material</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th width="8%;">Tgl. Schedule</th>
                            <th width="8%;">Kode</th>
                            <th width="25%;">Nama Barang</th>
                            <th width="8%;">Warna</th>
                            <th class="text-right" width="7%;">Uraian Jahit</th>
                            <!-- <th class="text-right" width="7%;">Uraian Jahit Sisa</th> -->
                            <th class="text-right" width="7%;">Schedule</th>
                            <th width="13%;">Keterangan</th>
                            <th class="text-center" width="3%;">Act</th>
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
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        fixedtable($('#tabledatax'));

        $('.dropify').dropify();

        $("#upload").on("click", function() {
            var id_uraian = $('#id_uraian').val();
            if (id_uraian.length > 0) {
                var formData = new FormData();
                formData.append('userfile', $('input[type=file]')[0].files[0]);
                formData.append('id_uraian', id_uraian);
                $.ajax({
                    type: "POST",
                    url: "<?= base_url($folder . '/cform/load'); ?>",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    success: function(data) {
                        var json = JSON.parse(data);
                        var sama = json.sama;
                        var status = json.status;
                        var detail = json.datadetail;
                        if (sama == true) {
                            if (status == 'berhasil') {
                                // console.log(detail);
                                /* swal({
                                    title: "Success!",
                                    text: "File Success Diupload :)",
                                    type: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                }); */
                                if (detail.length > 0) {
                                    clear_table();
                                    $('#jml').val(detail.length);
                                    for (let i = 0; i < detail.length; i++) {
                                        var no = i + 1;
                                        var newRow = $("<tr>");
                                        var cols = "";
                                        cols +=
                                            `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>
                                        <td>
                                            <input class="form-control tgl input-sm" readonly type="text" id="d_schedule${i}" name="d_schedule${i}" value="${detail[i]['d_schedule']}" required>
                                        </td>
                                        <td>
                                            <input value="${detail[i]['i_product_wip']}" class="form-control input-sm" readonly type="text" id="iproduct${i}" name="iproduct${i}">
                                        </td>
                                        <td>
                                            <select class="form-control select2" id="idproduct${i}" required name="idproduct${i}" onchange="get_detail(${i});"><option value="${detail[i]['id']}">${detail[i]['i_product_wip']} - ${detail[i]['e_product_wipname']} ${detail[i]['e_color_name']}</option></select>
                                        </td>
                                        <td>
                                            <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="${detail[i]['e_color_name']}">
                                        </td>
                                        <td>
                                            <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit${i}" name="n_uraian_jahit${i}" placeholder="0" value="${detail[i]['n_uraian_jahit']}">
                                        </td>
                                        <td hidden>
                                            <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit_sisa${i}" name="n_uraian_jahit_sisa${i}" placeholder="0" value="${detail[i]['n_uraian_jahit']}">
                                        </td>
                                        <td>
                                            <input class="form-control input-sm text-right" type="number" min="0" id="n_schedule_jahit${i}" name="n_schedule_jahit${i}" placeholder="0" onkeyup="hehetangan(${i});" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="${detail[i]['n_schedule']}">
                                        </td>
                                        <td>
                                            <input value="" class="form-control input-sm" type="text" id="e_note${i}" name="e_note${i}" value="" placeholder="Isi keterangan jika ada!">
                                            <input type="hidden" id="f_uraian_jahit${i}" name="f_uraian_jahit${i}" value="t">
                                        </td>
                                        <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
                                        // ${detail[i]['e_remark']}
                                        newRow.append(cols);
                                        $("#tabledatax").append(newRow);
                                        // $("#tabledatax tr:first").after(newRow);
                                        showCalendar2('.tgl');
                                        $('#idproduct' + i).select2({
                                            placeholder: 'Pilih Product',
                                            width: '100%',
                                            allowClear: true,
                                            ajax: {
                                                url: '<?= base_url($folder . '/cform/productwip'); ?>',
                                                dataType: 'json',
                                                delay: 250,
                                                data: function(params) {
                                                    var query = {
                                                        q: params.term,
                                                        'id_referensi': $('.id_uraian').val(),
                                                    }
                                                    return query;
                                                },
                                                processResults: function(data) {
                                                    return {
                                                        results: data,
                                                    };
                                                },
                                                cache: false
                                            }
                                        });
                                        hehetangan(i);
                                    }
                                }
                            } else {
                                swal({
                                    title: "Gagal!",
                                    text: "File Gagal Diupload :)",
                                    type: "error",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        } else {
                            swal({
                                title: "Maaf!",
                                text: "Referensi yang dipilih tidak sama dengan referensi yang di download :)",
                                type: "info",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                });
            } else {
                swal({
                    title: "Maaf!",
                    text: "Referensi tidak boleh kosong :)",
                    type: "info",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });

        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        showCalendar2('.tgl', 0, 999);
        $('.select2').select2();
        $("#ddocument").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#ibagian').change(function(event) {
            $('.id_uraian').val("");
            $('.id_uraian').html("");
            number();
        });

        /*----------  UPDATE STATUS DOKUMEN  ----------*/
        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#id_uraian').select2({
            placeholder: 'Pilih Referensi',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/get_referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        })
        // .change(function(event) {
        //     $.ajax({
        //         type: "post",
        //         data: {
        //             'id': $(this).val(),
        //             'ibagian': $('#ibagian').val(),
        //         },
        //         url: '<?= base_url($folder . '/cform/get_detail_referensi'); ?>',
        //         dataType: "json",
        //         success: function(data) {
        //             if (data['detail'].length > 0) {
        //                 clear_table();
        //                 $('#jml').val(data['detail'].length);
        //                 var group = '';
        //                 var no = 1;
        //                 var newRow = $("<tbody>");
        //                 for (let i = 0; i < data['detail'].length; i++) {
        //                     var cols = "";
        //                     // var n_quantity_sisa = parseFloat(data['detail'][i]['n_quantity']) - parseFloat(data['detail'][i]['n_quantity_uraian']);
        //                     /* if (group == '') {
        //                         cols += `<tr class="table-active">
        //                                 <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${data['detail'][i]['grup']}"><i class="fa fa-eye-slash text-success"></i></a></td>
        //                                 <td colspan="7">${data['detail'][i]['e_type_name']}</td>
        //                             </tr>`;
        //                     } else {
        //                         if (group != data['detail'][i]['grup']) {
        //                             cols += `<tr class="table-active">
        //                                 <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${data['detail'][i]['grup']}"><i class="fa fa-eye-slash text-success"></i></a></td>
        //                                 <td colspan="7">${data['detail'][i]['e_type_name']}</td>
        //                             </tr>`;
        //                             no = 1;
        //                         }
        //                     } */
        //                     // group = data['detail'][i]['grup'];
        //                     cols += `<tr class="${data['detail'][i]['grup']}">
        //                             <td class="text-center"><spanx id="snum${i}">${no}</spanx></td>
        //                             <td>
        //                                 <input class="form-control tgl input-sm" readonly type="text" id="d_schedule${i}" name="d_schedule${i}" value="" required>
        //                             </td>
        //                             <td>
        //                                 <input type="hidden" id="idproduct${i}" name="idproduct${i}" value="${data['detail'][i]['id_product_wip']}">
        //                                 <input class="form-control input-sm" readonly type="text" id="iproduct${i}" name="iproduct${i}" value="${data['detail'][i]['i_product_wip']}">
        //                             </td>
        //                             <td>
        //                                 <input class="form-control input-sm" readonly type="text" id="e_product_name${i}" name="e_product_name${i}" value="${data['detail'][i]['e_product_name']}">
        //                             </td>
        //                             <td>
        //                                 <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="${data['detail'][i]['e_color_name']}">
        //                             </td>
        //                             <td>
        //                                 <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit${i}" name="n_uraian_jahit${i}" placeholder="0" value="${data['detail'][i]['n_quantity']}">
        //                             </td>
        //                             <td hidden>
        //                                 <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit_sisa${i}" name="n_uraian_jahit_sisa${i}" placeholder="0" value="${data['detail'][i]['n_quantity_sisa']}">
        //                             </td>
        //                             <td>
        //                                 <input class="form-control input-sm text-right" type="number" min="0" id="n_schedule_jahit${i}" name="n_schedule_jahit${i}" placeholder="0" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="${data['detail'][i]['n_quantity_sisa']}">
        //                             </td>
        //                             <td>
        //                                 <input class="form-control input-sm" type="text" id="e_note${i}" name="e_note${i}" value="" placeholder="Isi keterangan jika ada!">
        //                                 <input type="hidden" id="f_uraian_jahit${i}" name="f_uraian_jahit${i}" value="t">
        //                             </td>
        //                         </tr>`;
        //                     newRow.append(cols);
        //                     $("#tabledatax").append(newRow);
        //                     showCalendar2('.tgl', 0, 999);
        //                     no++;
        //                 }
        //                 $(".toggler").click(function(e) {
        //                     e.preventDefault();
        //                     $('.' + $(this).attr('data-prod-cat')).toggle();
        //                     // $(this).addClass('active');

        //                     //Remove the icon class
        //                     if ($(this).find('i').hasClass('fa-eye')) {
        //                         //then change back to the original one
        //                         $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
        //                     } else {
        //                         //Remove the cross from all other icons
        //                         $('.faq-links').each(function() {
        //                             if ($(this).find('i').hasClass('fa-eye')) {
        //                                 $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
        //                             }
        //                         });

        //                         $(this).find('i').addClass('fa-eye').removeClass($(this).data('icon-name'));
        //                     }
        //                 });
        //             }
        //         },
        //         error: function() {
        //             alert('Error :)');
        //         }
        //     });
        // })
        ;

        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else {
                    $("#tabledatax tbody tr").each(function() {
                        $(this).find("td select .id").each(function() {
                            if ($(this).val() == '' || $(this).val() == null) {
                                swal('Kode barang tidak boleh kosong!');
                                ada = true;
                            }
                        });
                        $(this).find("td .inputitem").each(function() {
                            if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                                swal('Jml harus lebih besar dari 0 !');
                                ada = true;
                            }
                        });
                    });
                    if (!ada) {
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
                                        $("#submit").attr("disabled", true);
                                        $("#addrow").attr("disabled", true);
                                        $("#send").attr("disabled", false);
                                    } else if (data.sukses == 'ada') {
                                        swal("Maaf :(", "Unit Jahit Tersebut Masih Dalam Proses :(", "error");
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
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        });
    });

    var i = $("#jml").val();

    function tambah(jml) {
        /* let i = parseInt(jml) + 1;
        $("#jml").val(i); */
        let i = parseInt(jml);
        // $("#id_uraian").attr("id", "id_referensi");
        var no = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols +=
            `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>
        <td>
            <input class="form-control tgl input-sm" readonly type="text" id="d_schedule${i}" name="d_schedule${i}" value="" required>
        </td>
        <td>
            <input class="form-control input-sm" readonly type="text" id="iproduct${i}" name="iproduct${i}" value="">
        </td>
        <td>
            <select class="form-control select2" id="idproduct${i}" required name="idproduct${i}" onchange="get_detail(${i});"><option value=""></option></select>
        </td>
        <td>
            <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="">
        </td>
        <td>
            <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit${i}" name="n_uraian_jahit${i}" placeholder="0" value="0">
        </td>
        <td hidden>
            <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit_sisa${i}" name="n_uraian_jahit_sisa${i}" placeholder="0" value="0">
        </td>
        <td>
            <input class="form-control input-sm text-right" type="number" min="0" id="n_schedule_jahit${i}" name="n_schedule_jahit${i}" placeholder="0" onkeyup="hehetangan(${i});" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0">
        </td>
        <td>
            <input class="form-control input-sm" type="text" id="e_note${i}" name="e_note${i}" value="" placeholder="Isi keterangan jika ada!">
            <input type="hidden" id="f_uraian_jahit${i}" name="f_uraian_jahit${i}" value="f">
        </td>
        <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        // $("#tabledatax").append(newRow);
        $("#tabledatax tr:first").after(newRow);
        restart();
        showCalendar2('.tgl');
        $('#idproduct' + i).select2({
            placeholder: 'Pilih Product',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/productwip'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        'id_referensi': $('.id_uraian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
        $("#jml").val(i + 1);
    }

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });

    function get_detail(id) {
        $.ajax({
            type: "post",
            data: {
                'id_product': $('#idproduct' + id).val(),
                'id_referensi': $('.id_uraian').val(),
            },
            url: '<?= base_url($folder . '/cform/det_detail_product'); ?>',
            dataType: "json",
            success: function(data) {
                if (data['detail'].length > 0) {
                    var ada = false;
                    /* for (var i = 0; i < $('#jml').val(); i++) {
                        if (($('#idproduct' + id).val() == $('#idproduct' + i).val()) && (i != id)) {
                            swal("kode : " + data['detail'][0]['i_product_wip'] + ", warna : " + data['detail'][0]['e_color_name'] + "  sudah ada !!!!!");
                            ada = true;
                            break;
                        } else {
                            ada = false;
                        }
                    } */
                    if (!ada) {
                        $('#iproduct' + id).val(data['detail'][0]['i_product_wip']);
                        $('#e_color_name' + id).val(data['detail'][0]['e_color_name']);
                        // $('#n_schedule_jahit' + id).val(data['detail'][0]['n_quantity']);
                        $('#n_uraian_jahit' + id).val(data['detail'][0]['n_quantity']);
                        $('#n_schedule_jahit' + id).focus();
                    } else {
                        $('#idproduct' + id).html('');
                        $('#idproduct' + id).val('');
                    }
                }
            },
            error: function() {
                alert('Error :)');
            }
        });
    }

    function restart() {
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function clear_table() {
        $("#tabledatax tr:gt(0)").remove();
        $("#jml").val(0);
    }

    function cekvalidasi(i) {
        n_uraian_jahit = $("#n_uraian_jahit_sisa" + i).val();
        n_schedule_jahit = $("#n_schedule_jahit" + i).val();
        if (parseFloat(n_uraian_jahit) < parseFloat(n_schedule_jahit)) {
            swal('Jumlah Schedule = ' + n_schedule_jahit + ' tidak boleh melebihi Sisa Uraian Jahit = ' + n_uraian_jahit);
            $("#n_schedule_jahit" + i).val(n_uraian_jahit);
        }
    }

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'i_bagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#docure').val(data);
            },
            error: function() {
                swal('Error :(');
            }
        });
    }

    function hehetangan(i) {
        var sumuraian = parseFloat($('#n_uraian_jahit' + i).val());
        // console.log(sumuraian, $('#jml').val());
        var sumsc = 0;

        var last = 0;
        for (var j = 0; j <= $('#jml').val(); j++) {
            if (typeof $('#idproduct' + i).val() != 'undefined' && typeof $('#idproduct' + j).val() != 'undefined') {
                if ($('#idproduct' + i).val() == $('#idproduct' + j).val()) {
                    last = sumsc;
                    sumsc += parseFloat($('#n_schedule_jahit' + j).val());
                    
                }
            }
        }

        // 115 > #112
        if (sumsc > sumuraian) {
            swal('Maaf :(', 'Jumlah Barang ' + $('#iproduct' + i).val() + ' = ' + sumsc + ', melebihi jumlah uraian = '+ sumuraian + '\n' + 'Quantity akhir diatur oleh sistem', 'warning');
            $('#n_schedule_jahit' + i).val(sumuraian - last);
            return false;
        }
    }

    function export_data() {
        var id_uraian = $('.id_uraian').val();
        var ddocument = $('#ddocument').val();
        var dfrom = <?= $dfrom; ?>;
        var dto = <?= $dto; ?>;
        if (id_uraian == '') {
            swal('Referensi Harus Dipilih!!!');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $dfrom . '/' . $dto . '/'); ?>' + id_uraian + '/' + ddocument);
            return true;
        }
    }
</script>