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
                    <i class="fa fa-check-circle fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> <?= $title_list; ?></a>
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
                                <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                <input type="hidden" name="id_company_referensi" id="id_company_referensi" value="<?= $data->id_company_referensi; ?>">
                                <input type="text" readonly class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control input-sm" name="idocument" id="idocument" value="<?= $data->i_document; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control input-sm" name="ddocument" id="ddocument" value="<?= date("d-m-Y", strtotime($data->d_document)); ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control input-sm" name="idreferensi" id="idreferensi" value="<?= $data->i_document_referensi . ' - [' . $data->periode . ']'; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Group Jahit</label>
                            <label class="col-md-6">Keterangan</label>
                            <div class="col-sm-6">
                                <textarea id="group_jahit" readonly name="group_jahit" class="form-control input-sm" placeholder="Isi Group Jahit"><?= $data->e_group_jahit; ?></textarea>
                            </div>
                            <div class="col-sm-6">
                                <textarea id="keterangan" name="keterangan" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save fa-lg mr-3"></i>Realisasi</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-3 fa-lg"></i>Kembali</button>
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
                            $x = 0;
                            $group = '';
                            foreach ($detail as $key) {
                                $d_schedule = $key->d_schedule;
                                if ($d_schedule != '') {
                                    $d_schedule = formatdmY($key->d_schedule);
                                }
                                if ($group != $key->id) {
                                    $x++;

                            ?>
                                    <tr class="head<?= $x; ?>">
                                        <td class="text-center">
                                            <spanx id="snum<?= $x; ?>"><?= $x; ?></spanx>
                                        </td>
                                        <td><?= $d_schedule; ?></td>
                                        <td><?= $key->i_product_wip; ?></td>
                                        <td>
                                            <?= $key->e_product_wipname; ?>
                                            <input type="hidden" id="id_item<?= $x; ?>" name="id_item<?= $x; ?>" value="<?= $key->id; ?>">
                                            <input type="hidden" id="idproduct<?= $x; ?>" name="idproduct<?= $x; ?>" value="<?= $key->id_product_wip; ?>">
                                        </td>
                                        <td><?= $key->e_color_name; ?></td>
                                        <td class="text-right"><?= $key->n_quantity_uraian; ?></td>
                                        <td class="text-right"><?= $key->n_quantity; ?></td>
                                        <td><?= $key->e_remark; ?></td>
                                        <td class="text-center">
                                            <button data-urut="<?= $x; ?>" onclick="tambah_realisasi(<?= $x; ?>,$('#jml_detail').val());" type="button" id="addlist<?= $x; ?>" title="Tambah Realisasi" class="btn btn-sm btn-circle btn-info"><b><i class="ti-plus fa-lg"></i></b></button>
                                        </td>
                                    </tr>
                                <?php }
                                $group = $key->id;

                                if ($key->id_product !='' || $key->id_product !=null) {
                                ?>
                                <tr>
                                    <td class="text-center"><i class="fa fa-check-square-o fa-lg text-success"></i></td>
                                    <td>Realisasi</td>
                                    <td>
                                        <input class="form-control input-sm" readonly type="text" id="i_product<?= $i; ?>" name="i_product<?= $i; ?>" value="<?= $key->i_product; ?>">
                                        <input class="form-control input-sm" readonly type="hidden" id="id_item_sc<?= $i; ?>" name="id_item_sc<?= $i; ?>" value="<?= $key->id; ?>">
                                    </td>
                                    <td>
                                        <select class="form-control product select2" id="id_product<?= $i; ?>" required name="id_product<?= $i; ?>" onchange="get_detail(<?= $i; ?>);">
                                            <option value="<?= $key->id_product; ?>"><?= $key->e_product; ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm" type="text" id="e_color_name<?= $i; ?>" name="e_color_name<?= $i; ?>" value="<?= $key->e_color; ?>">
                                    </td>
                                    <td colspan="2">
                                        <input class="form-control inputitem input-sm text-right" type="number" min="0" id="n_realisasi<?= $i; ?>" name="n_realisasi<?= $i; ?>" placeholder="0" onkeyup="hehetangan(<?= $i; ?>);" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_realisasi; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" type="text" id="e_note<?= $i; ?>" name="e_note<?= $i; ?>" value="<?= $key->e_note; ?>" placeholder="Isi keterangan jika ada!">
                                    </td>
                                    <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                </tr>
                            <?php
                                $i++;

                            }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="jml" id="jml" value="<?= $x; ?>">
        <input type="hidden" name="jml_detail" id="jml_detail" value="<?= $i; ?>">
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
        $('.select2').select2();

        for (let i = 0; i < $('#jml_detail').val(); i++) {
            $('#id_product' + i).select2({
                placeholder: 'Pilih Product',
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder . '/cform/product'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
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

        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                if ($('#jml_detail').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else {
                    $("#tabledatax tbody tr").each(function() {
                        $(this).find("td select .product").each(function() {
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
                            title: "Realisasi Data Ini?",
                            text: "Anda Dapat Membatalkannya Nanti",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonColor: 'LightSeaGreen',
                            confirmButtonText: "Ya, Realisasi!",
                            closeOnConfirm: false
                        }, function() {
                            $.ajax({
                                type: "POST",
                                data: $("form").serialize(),
                                url: '<?= base_url($folder . '/cform/realisasi_act/'); ?>',
                                dataType: "json",
                                success: function(data) {
                                    if (data.sukses == true) {
                                        swal("Sukses!", "No Dokumen : " + data.kode +
                                            ", Berhasil Direalisasi :)", "success");
                                        $("input").attr("disabled", true);
                                        $("#submit").attr("disabled", true);
                                    } else {
                                        swal("Maaf :(", "No Dokumen : " + data.kode +
                                            ", Gagal Direalisasi :(", "error");
                                    }
                                },
                                error: function() {
                                    swal("Maaf", "Data Gagal Direalisasi :(", "error");
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

    var i = $("#jml_detail").val();

    function tambah_realisasi(x, i) {
        i = parseInt(i);
        var id_item = $('#id_item' + x).val();
        var newRow = $("<tr>");
        var cols = "";
        cols +=
            `<td class="text-center"><i class="fa fa-check-square-o fa-lg text-success"></i></td>
        <td>Realisasi</td>
        <td>
            <input class="form-control input-sm" readonly type="text" id="i_product${i}" name="i_product${i}" value="">
            <input class="form-control input-sm" readonly type="hidden" id="id_item_sc${i}" name="id_item_sc${i}" value="${id_item}">
        </td>
        <td>
            <select class="form-control product select2" id="id_product${i}" required name="id_product${i}" onchange="get_detail(${i});"><option value=""></option></select>
        </td>
        <td>
            <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="">
        </td>
        <td colspan="2">
            <input class="form-control inputitem input-sm text-right" type="number" min="0" id="n_realisasi${i}" name="n_realisasi${i}" placeholder="0" onkeyup="hehetangan(${i});"onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0">
        </td>
        <td>
            <input class="form-control input-sm" type="text" id="e_note${i}" name="e_note${i}" value="" placeholder="Isi keterangan jika ada!">
        </td>
        <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        // $("#tabledatax").append(newRow);
        $(newRow).insertAfter("#tabledatax .head" + x);
        // $("#tabledatax tr:first").after(newRow);
        showCalendar2('.tgl');
        restart();
        $('#id_product' + i).select2({
            placeholder: 'Pilih Product',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/product'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        id_company_referensi: $('#id_company_referensi').val()
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
        $("#jml_detail").val(i + 1);
    }

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
                'id_product': $('#id_product' + id).val(),
            },
            url: '<?= base_url($folder . '/cform/get_detail_product'); ?>',
            dataType: "json",
            success: function(data) {
                if (data['detail'].length > 0) {
                    var ada = false;
                    for (var i = 0; i < $('#jml_detail').val(); i++) {
                        if (($('#id_product' + id).val() == $('#id_product' + i).val()) && (i != id) && ($('#id_item_sc' + id).val() == $('#id_item_sc' + i).val())) {
                            swal("kode : " + data['detail'][0]['i_product_wip'] + ", warna : " + data['detail'][0]['e_color_name'] + "  sudah ada !!!!!");
                            ada = true;
                            break;
                        } else {
                            ada = false;
                        }
                    }
                    if (!ada) {
                        $('#i_product' + id).val(data['detail'][0]['i_product_wip']);
                        $('#e_color_name' + id).val(data['detail'][0]['e_color_name']);
                        $('#n_realisasi' + id).focus();
                    } else {
                        $('#id_product' + id).html('');
                        $('#id_product' + id).val('');
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
        if (f_uraian_jahit == 't') {
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