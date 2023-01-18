<style>
    .form-group {
        margin-bottom: 10px !important;
    }

    .table>thead>tr>th {
        padding: 6px 6px;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> <?= $title_list; ?></a>
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
                                <select name="ibagian" id="ibagian" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control input-sm" name="idocument" id="idocument" value="<?= $data->i_document ?>">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control input-sm date" name="ddocument" id="ddocument" value="<?= formatdmY($data->d_document); ?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="id_uraian" id="id_referensi" class="form-control id_uraian select2" required="">
                                    <option value="<?= $data->id_referensi; ?>"><?= $data->i_document_referensi . ' - [' . $data->periode . ']'; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Group Jahit</label>
                            <label class="col-md-6">Keterangan</label>
                            <div class="col-sm-6">
                                <textarea id="group_jahit" name="group_jahit" class="form-control input-sm" placeholder="Isi Group Jahit"><?= $data->e_group_jahit; ?></textarea>
                            </div>
                            <div class="col-sm-6">
                                <textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                    <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save fa-lg mr-3"></i>Update</button>
                                <?php } ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-3 fa-lg"></i>Kembali</button>
                                <?php if ($data->i_status == '1') { ?>
                                    <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-lg mr-3 fa-paper-plane-o"></i>Send</button>
                                    <button type="button" onclick="tambah($('#jml').val());" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus fa-lg mr-2"></i>Item</button>
                                    <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-lg mr-3 fa-trash"></i>Delete</button>
                                <?php } elseif ($data->i_status == '2') { ?>
                                    <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-lg mr-3 fa-refresh"></i>Cancel</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($detail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-5">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatax" class="table color-table nowrap success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
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
                            <?php
                            $i = 0;
                            foreach ($detail as $key) {
                                $d_schedule = $key->d_schedule;
                                if ($d_schedule!='') {
                                    $d_schedule = formatdmY($key->d_schedule);
                                }
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><?= $i + 1; ?></spanx>
                                    </td>
                                    <td>
                                        <input class="form-control tgl input-sm" readonly type="text" id="d_schedule<?= $i; ?>" required name="d_schedule<?= $i; ?>" value="<?= $d_schedule; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" readonly type="text" id="iproduct<?= $i; ?>" name="iproduct<?= $i; ?>" value="<?= $key->i_product_wip; ?>">
                                    </td>
                                    <td>
                                        <?php if ($key->f_uraian_jahit == 'f') { ?>
                                            <select class="form-control select2" id="idproduct<?= $i; ?>" required name="idproduct<?= $i; ?>" onchange="get_detail(<?= $i; ?>);">
                                                <option value="<?= $key->id_product_wip; ?>"><?= $key->e_product_wipname; ?></option>
                                            </select>
                                        <?php } else { ?>
                                            <input class="form-control input-sm" readonly type="text" id="e_product_name<?= $i; ?>" name="e_product_name<?= $i; ?>" value="<?= $key->e_product_wipname; ?>">
                                            <input type="hidden" id="idproduct<?= $i; ?>" name="idproduct<?= $i; ?>" value="<?= $key->id_product_wip; ?>">
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm" type="text" id="e_color_name<?= $i; ?>" name="e_color_name<?= $i; ?>" value="<?= $key->e_color_name; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit<?= $i; ?>" name="n_uraian_jahit<?= $i; ?>" placeholder="0" value="<?= $key->n_quantity_uraian; ?>">
                                    </td>
                                    <td hidden>
                                        <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit_sisa<?= $i; ?>" name="n_uraian_jahit_sisa<?= $i; ?>" placeholder="0" value="<?= $key->n_quantity_uraian_sisa; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm text-right" type="number" min="0" id="n_schedule_jahit<?= $i; ?>" name="n_schedule_jahit<?= $i; ?>" placeholder="0" onkeyup="hehetangan(<?= $i;?>);" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" type="text" id="e_note<?= $i; ?>" name="e_note<?= $i; ?>" value="<?= $key->e_remark; ?>" placeholder="Isi keterangan jika ada!">
                                        <input type="hidden" id="f_uraian_jahit<?= $i; ?>" name="f_uraian_jahit<?= $i; ?>" value="<?= $key->f_uraian_jahit; ?>">
                                    </td>
                                    <?php if ($key->f_uraian_jahit == 'f') { ?>
                                        <td class="text-center">
                                            <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php
                                $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
    <?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        fixedtable($('#tabledatax'));
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        showCalendar2('.tgl');
        $('.select2').select2();
        $("#ddocument").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#ibagian').change(function(event) {
            $('#id_uraian').val("");
            $('#id_uraian').html("");
        });

        /*----------  UPDATE STATUS DOKUMEN  ----------*/
        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom; ?>', '<?= $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom; ?>', '<?= $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom; ?>', '<?= $dto; ?>');
        });

        for (let i = 0; i < $('#jml').val(); i++) {
            let f_urai = $('#f_uraian_jahit' + i).val();
            if (f_urai == 'f') {
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
                })
            }
        }

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
        //.change(function(event) {
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
        //                             <td class="text-center">${no}</td>
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
        //                             <td>
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
                                        // $('#id').val(data.id);
                                        swal("Sukses!", "No Dokumen : " + data.kode +
                                            ", Berhasil Diupdate :)", "success");
                                        $("input").attr("disabled", true);
                                        //$("select").attr("disabled", true);
                                        $("#submit").attr("disabled", true);
                                        $("#addrow").attr("disabled", true);
                                        $("#send").attr("disabled", false);
                                    } else if (data.sukses == 'ada') {
                                        swal("Maaf :(", "Unit Jahit Tersebut Masih Dalam Proses :(", "error");
                                    } else {
                                        swal("Maaf :(", "No Dokumen : " + data.kode +
                                            ", Gagal Diupdate :(", "error");
                                    }
                                },
                                error: function() {
                                    swal("Maaf", "Data Gagal Diupdate :(", "error");
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

        /* $("form").submit(function(event) {
            event.preventDefault();
            $("input").attr("disabled", true);
            $("select").attr("disabled", true);
            $("#submit").attr("disabled", true);
            $("#addrow").attr("disabled", true);
        }); */
    });

    var i = $("#jml").val();

    function tambah(jml) {
        let i = parseInt(jml);
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
            <input class="form-control input-sm text-right" type="number" min="0" id="n_schedule_jahit${i}" name="n_schedule_jahit${i}" placeholder="0" onkeyup="hehetangan(${i});"onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0">
        </td>
        <td>
            <input class="form-control input-sm" type="text" id="e_note${i}" name="e_note${i}" value="" placeholder="Isi keterangan jika ada!">
            <input type="hidden" id="f_uraian_jahit${i}" name="f_uraian_jahit${i}" value="f">
        </td>
        <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        // $("#tabledatax").append(newRow);
        $("#tabledatax tr:first").after(newRow);
        showCalendar2('.tgl');
        restart();
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
        })
        $("#jml").val(i + 1);
    }
    /*
        $("#addrow").on("click", function () {
            i++; */
    /* function tambah(jml) {
        let i = parseInt(jml) + 1;
        $("#jml").val(i);

        var no = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><input type="text" id="tanggal${i}" name="tanggal${i}" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" placeholder="<?= date('d-m-Y'); ?>"></td>`;
        cols += `<td><select data-nourut="${i}" id="ibarang${i}" class="form-control input-sm id" name="ibarang${i}" ></select></td>`;
        cols += `<td><input type="text" id="nqty${i}" name="nqty${i}" size="4" ></td>`;
        cols += `<td><select data-nourut="${i}" id="ikategori${i}" class="form-control input-sm id" name="ikategori${i}" style="width: 100%;" ></select></td>`;
        cols += `<td><select data-nourut="${i}" id="iunit${i}" class="form-control input-sm id" name="iunit${i}" style="width: 100%;"></select></td>`;
        cols += `<td><input type="text" id="eremark${i}" name="eremark${i}"></td>`;
        cols += `<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $("#iunit" + i).attr("disabled", true);

        $("#tanggal" + i).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#ibarang' + i).select2({
            placeholder: 'Cari Kode / Nama Produk',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/productwip/'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#ikategori' + i).select2({
            placeholder: 'Cari Kategori Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getkategori'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function() {
            $("#iunit" + i).attr("disabled", false);
        });

        //var kategori = $('#ikategori'+i).val();

        $('#iunit' + i).select2({
            placeholder: 'Cari Unit Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getunit'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        kategori: $('#ikategori' + i).val(),
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
        });


    } */

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        /* alert(i); */
        /* $('#jml').val(i); */
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
                        $('#n_schedule_jahit' + id).val(data['detail'][0]['n_quantity']);
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
        $("#tabledatax tbody tr:gt(0)").remove();
        $("#jml").val(0);
    }

    function cekvalidasi(i) {
        n_uraian_jahit = $("#n_uraian_jahit_sisa" + i).val();
        n_schedule_jahit = $("#n_schedule_jahit" + i).val();
        f_uraian_jahit = $("#f_uraian_jahit" + i).val();
        if (f_uraian_jahit=='t') {   
            if (parseFloat(n_uraian_jahit) < parseFloat(n_schedule_jahit)) {
                swal('Jumlah Schedule = ' + n_schedule_jahit + ' tidak boleh melebihi Sisa Uraian Jahit = ' + n_uraian_jahit);
                $("#n_schedule_jahit" + i).val(n_uraian_jahit);
            }
        }
    }

    function hehetangan(i) {
        var sumuraian = parseFloat($('#n_uraian_jahit' + i).val());
        var sumsc = 0;
        for (var j = 0; j <= $('#jml').val(); j++) {
            if (typeof $('#idproduct' + i).val() != 'undefined' && typeof $('#idproduct' + j).val() != 'undefined') {
                if ($('#idproduct' + i).val() == $('#idproduct' + j).val()) {
                    sumsc += parseFloat($('#n_schedule_jahit' + j).val());
                }
            }
        }
        if (sumsc > sumuraian) {
            swal('Maaf :(', 'Jumlah Barang ' + $('#iproduct' + i).val() + ' = ' + sumsc + ', melebihi jumlah uraian = ' + sumuraian, 'error');
            $('#n_schedule_jahit' + i).val(0);
            return false;
        }
    }
</script>