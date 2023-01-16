<style type="text/css">
    .select2-results__options {
        font-size: 14px !important;
    }

    .select2-selection__rendered {
        font-size: 12px;
    }

    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }

    .font-11 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 11px;
        height: 20px;
    }

    .font-12 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 12px;
    }
    .form-group {
        margin-bottom: 10px !important;
    }

    .table>thead>tr>th {
        padding: 6px 6px;
    }

    .dropify-wrapper {
        height: 105px !important;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus fa-lg mr-2"></i><?= $title; ?>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Referensi Forecast Jahit</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                    <?php /* if ($bagian) {
                                        foreach ($bagian as $row) : */ ?>
                                            <option value="">
                                            </option>
                                    <?php /* endforeach;
                                    } */ ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id">
                                    <input type="text" name="idocument" required="" id="iuraianjahit" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                    <!-- <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span> -->
                                </div>
                                <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" onchange="number();" required="" readonly value="<?= date("d-m-Y"); ?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="idforecast" id="idforecast" class="form-control select2" required="">
                                    <option value=""></option>
                                </select>
                                <!-- <input type="hidden" id="idforecast" name="idforecast" required="" value="<?= $id; ?>"> -->
                                <!-- <input type="hidden" id="iperiode" name="iperiode" required="" value="<?= $tahun . $bulan; ?>">
                                <input type="text" class="form-control input-sm" readonly value="<?= $this->fungsi->mbulan($bulan) . ' ' . $tahun; ?>"> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2"></i>Simpan</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" id="send" hidden="true" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>N O T E : </b></span><br>
                            <span class="notekode">* Item yang disimpan hanya qty retur yang lebih besar dari 0.</span>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class=""></i><?= "Upload Uraian"; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> <?= $title_list; ?></a>
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
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Item</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="4">Total</th>
                            <th class="text-right"><span class="n_fc_jahit text-right">0</span></th>
                            <th class="text-right"><span class="n_fc_jahit_sisa text-right">0</span></th>
                            <th class="text-right"><span class="n_fc_jahit_urai text-right">0</span></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th width="10%;">Kode</th>
                            <th width="25%;">Nama Barang</th>
                            <th class="text-center" width="10%;">Warna</th>
                            <th class="text-right" width="10%;">FC Jahit</th>
                            <th class="text-right" width="10%;">QTY Belum Di Urai</th>
                            <th class="text-right" width="10%;">QTY Urai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="0">
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        // DROPIFY
        $('.dropify').dropify();

        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        fixedtable($('#tabledatay'));

        // $('#iuraianjahit').mask('SSS-0000-0000S');
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date', 0);
        number();

        $('#ibagian').select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            allowClear: true,
            width:"100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $("#upload").on("click", function() {
            var idforecast = $('#idforecast').val();
            var ibagian = $('#ibagian').val();
            if (idforecast.length > 0) {
                var formData = new FormData();
                formData.append('userfile', $('input[type=file]')[0].files[0]);
                formData.append('idforecast', idforecast);
                formData.append('ibagian', ibagian);
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
                                swal({
                                    title: "Success!",
                                    text: "File Success Diupload :)",
                                    type: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                if (json.detail.length > 0) {
                                    clear_table();
                                    $('.n_fc_jahit').text(formatcemua(json.n_quantity));
                                    $('.n_fc_jahit_sisa').text(formatcemua(json.n_quantity_sisa));
                                    $('.n_fc_jahit_urai').text(formatcemua(json.n_quantity_urai));
                                    $('#jml').val(json.detail.length);
                                    var group = '';
                                    var no = 1;
                                    var newRow = $("<tbody>");
                                    for (let i = 0; i < json.detail.length; i++) {
                                        var cols = "";
                                        // var n_quantity_sisa = parseFloat(data['detail'][i]['n_quantity']) - parseFloat(data['detail'][i]['n_quantity_uraian']);
                                        if (group == '') {
                                            cols += `<tr class="table-active">
                                                    <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${json.detail[i].grup}"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                                                    <td colspan="7">${json.detail[i].e_type_name}</td>
                                                </tr>`;
                                        } else {
                                            if (group != json.detail[i].grup) {
                                                cols += `<tr class="table-active">
                                                    <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${json.detail[i].grup}"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                                                    <td colspan="7">${json.detail[i].e_type_name}</td>
                                                </tr>`;
                                                no = 1;
                                            }
                                        }
                                        group = json.detail[i].grup;
                                        cols += `<tr class="${json.detail[i].grup}" style="display:none">
                                                <td class="text-center">${no}</td>
                                                <td>
                                                    <input type="hidden" id="idproduct${i}" name="idproduct${i}" value="${json.detail[i].id}">
                                                    <input class="form-control input-sm" readonly type="text" id="iproduct${i}" name="iproduct${i}" value="${json.detail[i].i_product_wip}">
                                                </td>
                                                <td>
                                                    <input class="form-control input-sm" readonly type="text" id="e_product_name${i}" name="e_product_name${i}" value="${json.detail[i].e_product_name}">
                                                </td>
                                                <td>
                                                    <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="${json.detail[i].e_color_name}">
                                                </td>
                                                <td>
                                                    <input readonly class="form-control input-sm text-right" type="text" id="n_fcjahit${i}" name="n_fcjahit${i}" placeholder="0" value="${json.detail[i].n_quantity}">
                                                </td>
                                                <td>
                                                    <input readonly class="form-control input-sm text-right" type="text" id="n_fcjahit_sisa${i}" name="n_fcjahit_sisa${i}" placeholder="0" value="${json.detail[i].n_quantity_sisa}">
                                                </td>
                                                <td>
                                                    <input class="form-control input-sm text-right" type="number" id="n_uraian_jahit${i}" name="n_uraian_jahit${i}" placeholder="0" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="cekvalidasi(${i});" onkeypress="return event.charCode >= 48 && event.charCode <= 57;" value="${json.detail[i].n_quantity_urai}">
                                                    <input type="hidden" value="${json.detail.length}" id="jmlrow">
                                                </td>
                                                <td>
                                                    <input class="form-control input-sm" type="text" id="e_remark${i}" name="e_remark${i}" value="${json.detail[i].keterangan}" placeholder="Isi keterangan jika ada!">
                                                </td>
                                            </tr>`;
                                        newRow.append(cols);
                                        $("#tabledatay").append(newRow);
                                        fixedtable($('#tabledatay'));
                                        no++;
                                    }
                                    $(".toggler").click(function(e) {
                                        e.preventDefault();
                                        $('.' + $(this).attr('data-prod-cat')).toggle();
                                        // $(this).addClass('active');

                                        //Remove the icon class
                                        if ($(this).find('i').hasClass('fa-eye')) {
                                            //then change back to the original one
                                            $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                                        } else {
                                            //Remove the cross from all other icons
                                            $('.faq-links').each(function() {
                                                if ($(this).find('i').hasClass('fa-eye')) {
                                                    $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                                                }
                                            });

                                            $(this).find('i').addClass('fa-eye').removeClass($(this).data('icon-name'));
                                        }
                                    });
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

        $('#idforecast').select2({
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
        }).change(function(event) {
            $.ajax({
                type: "post",
                data: {
                    'id': $(this).val(),
                    'ibagian': $('#ibagian').val(),
                },
                url: '<?= base_url($folder . '/cform/get_detail_referensi'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['detail'].length > 0) {
                        clear_table();
                        $('.n_fc_jahit').text(formatcemua(data['n_quantity']));
                        $('.n_fc_jahit_sisa').text(formatcemua(data['n_quantity_sisa']));
                        $('#jml').val(data['detail'].length);
                        var group = '';
                        var no = 1;
                        var newRow = $("<tbody>");
                        for (let i = 0; i < data['detail'].length; i++) {
                            var cols = "";
                            // var n_quantity_sisa = parseFloat(data['detail'][i]['n_quantity']) - parseFloat(data['detail'][i]['n_quantity_uraian']);
                            if (group == '') {
                                cols += `<tr class="table-active">
                                        <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${data['detail'][i]['grup']}"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                                        <td colspan="7">${data['detail'][i]['e_type_name']}</td>
                                    </tr>`;
                            } else {
                                if (group != data['detail'][i]['grup']) {
                                    cols += `<tr class="table-active">
                                        <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${data['detail'][i]['grup']}"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                                        <td colspan="7">${data['detail'][i]['e_type_name']}</td>
                                    </tr>`;
                                    no = 1;
                                }
                            }
                            group = data['detail'][i]['grup'];
                            cols += `<tr class="${data['detail'][i]['grup']}" style="display:none">
                                    <td class="text-center">${no}</td>
                                    <td>
                                        <input type="hidden" id="idproduct${i}" name="idproduct${i}" value="${data['detail'][i]['id_product_wip']}">
                                        <input class="form-control input-sm" readonly type="text" id="iproduct${i}" name="iproduct${i}" value="${data['detail'][i]['i_product_wip']}">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" readonly type="text" id="e_product_name${i}" name="e_product_name${i}" value="${data['detail'][i]['e_product_name']}">
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="${data['detail'][i]['e_color_name']}">
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm text-right" type="text" id="n_fcjahit${i}" name="n_fcjahit${i}" placeholder="0" value="${data['detail'][i]['n_quantity']}">
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm text-right" type="text" id="n_fcjahit_sisa${i}" name="n_fcjahit_sisa${i}" placeholder="0" value="${data['detail'][i]['n_quantity_sisa']}">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm text-right" type="number" id="n_uraian_jahit${i}" name="n_uraian_jahit${i}" placeholder="0" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="cekvalidasi(${i});" onkeypress="return event.charCode >= 48 && event.charCode <= 57;" value="0">
                                        <input type="hidden" value="${data['detail'].length}" id="jmlrow">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" type="text" id="e_remark${i}" name="e_remark${i}" value="" placeholder="Isi keterangan jika ada!">
                                    </td>
                                </tr>`;
                            newRow.append(cols);
                            $("#tabledatay").append(newRow);
                            fixedtable($('#tabledatay'));
                            no++;
                        }
                        $(".toggler").click(function(e) {
                            e.preventDefault();
                            $('.' + $(this).attr('data-prod-cat')).toggle();
                            // $(this).addClass('active');

                            //Remove the icon class
                            if ($(this).find('i').hasClass('fa-eye')) {
                                //then change back to the original one
                                $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                            } else {
                                //Remove the cross from all other icons
                                $('.faq-links').each(function() {
                                    if ($(this).find('i').hasClass('fa-eye')) {
                                        $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                                    }
                                });

                                $(this).find('i').addClass('fa-eye').removeClass($(this).data('icon-name'));
                            }
                        });
                    }
                },
                error: function() {
                    alert('Error :)');
                }
            });
        });

        /*----------  UPDATE STATUS DOKUMEN  ----------*/
        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
        $('#ibagian').change(function(event) {
            number();
            clear_table();
            $('#idforecast').html('');
            $('#idforecast').val('');
        });

        /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/
        $('#ceklis').click(function(event) {
            if ($('#ceklis').is(':checked')) {
                $("#iuraianjahit").attr("readonly", false);
            } else {
                $("#iuraianjahit").attr("readonly", true);
                $("#ada").attr("hidden", true);
                number();
            }
        });

        /*----------  CEK NO DOKUMEN  ----------*/
        $("#iuraianjahit").keyup(function() {
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
                    swal('Error :)');
                }
            });
        });

        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
        
            return false;
            return true;
        }

        /*----------  VALIDASI UPDATE DATA  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else {
                    // for(let i = 1; i <= $(`#jml`).val(); i++) {
                    //     console.log(parseFloat($(`#n_uraian_jahit${i}`).val()) > 0);
                    //     if (parseFloat($(`#n_uraian_jahit${i}`).val()) > 0 && parseFloat($(`#n_uraian_jahit${i}`).val()) % 1 != 0) {
                    //         swal('Quantity Tidak Boleh Ada Desimal');
                    //         ada = true;
                    //         return false;
                    //     }
                    // }
                    // for (var i = 1; i <= $('#jml_item').val(); i++) {
                    //     if (parseInt($('#nilai_budgeting' + i).val()) == 0 || parseInt($('#nilai_budgeting' + i).val()) == null) {
                    //         swal("Maaf :(","Nilai Budgeting harus lebih besar dari 0!","error");
                    //         ada = true;
                    //         return false;
                    //     }
                    // }
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
                                        //$("select").attr("disabled", true);
                                        $("#submit").attr("disabled", true);
                                        $("#addrow").attr("disabled", true);
                                        $("#send").attr("hidden", false);
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
                        swal('Maaf :(', 'Total Jumlah Retur harus lebih besar dari 0 !', 'error');
                        return false;
                    }
                }
            }
            return false;
        })
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/
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
                $('#iuraianjahit').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    /* function hetang(i) {
        let nilai_mutasi = parseFloat($('#nilai_mutasi' + i).val());
        let nilai_estimasi = parseFloat($('#nilai_estimasi' + i).val());
        let nilai_kebutuhan = parseFloat($('#nilai_kebutuhan' + i).val());
        let nilai_op_sisa = parseFloat($('#nilai_op_sisa' + i).val());

        let stock_estimasi = nilai_mutasi - nilai_estimasi;
        if (stock_estimasi < 0) {
            stock_estimasi = 0;
        }
        let budgeting = Math.abs(stock_estimasi) - Math.abs(nilai_kebutuhan) + Math.abs(nilai_op_sisa);
        let up = budgeting * (parseFloat($('#up' + i).val()) / 100);
        $('#nilai_budgeting' + i).val(Math.round((Math.abs(budgeting) + Math.abs(up)) * 1000) / 1000);
    } */


    function cekvalidasi(i) {
        n_fcjahit_sisa = $("#n_fcjahit_sisa" + i).val();
        n_uraian_jahit = $("#n_uraian_jahit" + i).val();
        if(isNaN(n_uraian_jahit)) {
            n_uraian_jahit = 0;
        }
        jmlrow = parseInt($("#jmlrow").val());
        let total = 0;
        if (parseFloat(n_uraian_jahit) > parseFloat(n_fcjahit_sisa)) {
            swal('Jumlah = ' + n_uraian_jahit + ' tidak boleh melebihi Sisa = ' + n_fcjahit_sisa);
            $("#n_uraian_jahit" + i).val(n_fcjahit_sisa);
            $(".n_fc_jahit_urai").text(total);
        }
        for(let i = 0; i<=jmlrow; i++) {
            let sub = $(`#n_uraian_jahit${i}`).val();
            if(isNaN(sub) || sub == "") {
                sub = 0;
            }
            if(!isNaN(sub)) {
                total+=parseInt(sub);
                $('.n_fc_jahit_urai').text(total)
            }
        }
    }

    function clear_table() {
        $("#tabledatay tbody tr").remove();
        $("#jml").val(0);
    }

    function export_data() {
        var idforecast = $('#idforecast').val();
        var ddocument = $('#ddocument').val();
        var ibagian = $('#ibagian').val();
        var dfrom = <?= $dfrom; ?>;
        var dto = <?= $dto; ?>;
        if (idforecast == '') {
            swal('Referensi Harus Dipilih!!!');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $dfrom . '/' . $dto . '/'); ?>' + idforecast + '/' + ddocument + '/' + encodeURIComponent(ibagian));
            return true;
        }
    }
</script>