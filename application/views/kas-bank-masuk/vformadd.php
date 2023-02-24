<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus fa-lg mr-2"></i> &nbsp;
                <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                    <?= $title_list; ?>
                </a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Area</label>
                        <div class="col-sm-3">
                            <select name="i_bagian" id="i_bagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row): ?>
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="i_rv_id" id="i_rv_id" readonly autocomplete="off"
                                    placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value=""
                                    aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_rv" name="d_rv" class="form-control input-sm date" required=""
                                readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="i_area" id="i_area" class="form-control select2" onchange="number();">
                                <?php if ($area) {
                                    foreach ($area as $row): ?>
                                        <option value="<?= $row->id; ?>"><?="[" . $row->i_area . "] - " . $row->e_area; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis</label>
                        <label class="col-md-3">CoA</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="i_rv_type" id="i_rv_type" class="form-control select2">
                                <?php if ($rvtype) {
                                    foreach ($rvtype as $row): ?>
                                        <option data-type="<?= $row->i_rv_type_id; ?>" value="<?= $row->i_rv_type; ?>"><?="[" . $row->i_rv_type_id . "] - " . $row->e_rv_type_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_coa" id="i_coa" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea name="e_remark" class="form-control input-sm" placeholder="Note ..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"
                                onclick="return konfirm();"><i class="fa fa-lg fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm"><i
                                    class="fa fa-lg fa-plus"></i>&nbsp;&nbsp;Item</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i
                                    class="fa fa-lg fa-arrow-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm"><i
                                    class="fa fa-lg fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="white-box" id="detail">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-11">
                        <h3 class="box-title m-b-0">Detail Transaksi</h3>
                    </div>
                    <div class="col-sm-1" style="text-align: right;">
                        -
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabledatax" class="table color-table success-table table-bordered class"
                                cellpadding="8" cellspacing="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%;">No</th>
                                        <th style="width: 15%;">CoA</th>
                                        <th style="width: 7%;">Tgl. Bukti</th>
                                        <th style="width: 10%;">Area</th>
                                        <th class="clear" style="width: 10%;">TF/GR/TN</th>
                                        <th class="clear" style="width: 15%;">Referensi</th>
                                        <th>Keterangan</th>
                                        <th class="text-right" width="10%">Jumlah</th>
                                        <th class="text-center" style="width: 3%;">Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right colay" colspan="7">
                                            Sub Total
                                        </th>
                                        <th><input type="text" class="form-control form-control-sm text-right"
                                                name="v_rv" id="v_rv" value="0" readonly></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right colay" colspan="7">
                                            Saldo
                                        </th>
                                        <th><input type="text" class="form-control form-control-sm text-right"
                                                name="v_saldo" id="v_saldo" value="0" readonly></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right colay" colspan="7">
                                            Sisa Saldo
                                        </th>
                                        <th><input type="text" class="form-control form-control-sm text-right"
                                                name="v_sisa_saldo" id="v_sisa_saldo" value="0" readonly></th>
                                    </tr>
                                </tfoot>
                                <input type="hidden" name="jml" id="jml" value="0">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', null, 0);
        $('#i_coa').select2({
            placeholder: 'Cari CoA',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/coa_type/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        i_rv_type: $('#i_rv_type').val(),
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

        $("form").submit(function (event) {
            event.preventDefault();
            $("input").attr("disabled", true);
            $("select").attr("disabled", true);
            $("#submit").attr("disabled", true);
            $("#addrow").attr("disabled", true);
            $("#send").attr("disabled", false);
        });

        $('#i_rv_type').change(function (event) {
            let type = $(this).find(':selected').data('type');
            if (type != 'BM') {
                $('.clear').attr("hidden", true);
                $('.colay').attr('colspan', 5);
            } else {
                $('.clear').attr("hidden", false);
                $('.colay').attr('colspan', 7);
            }
            number();
            clear_table();
            $('#i_coa').val('');
            $('#i_coa').html('');
        });

        $('#i_bagian, #d_rv, #i_area, #i_rv_type, #i_coa').change(function (event) {
            number();
        });

        $('#send').click(function (event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        /**
         * Tambah Item
         */
        var i = $('#jml').val();
        $("#addrow").on("click", function () {
            //alert("tes");
            i++;
            $("#jml").val(i);
            var no = parseInt($('#tabledatax > tbody tr').length + 1);
            var newRow = $('<tr id="tr' + i + '">');
            var cols = "";
            let type = $('#i_rv_type').find(':selected').data('type');
            let hidden = '';
            if (type != 'BM') {
                hidden = 'hidden';
            }
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>
                    <td>
                        <select data-nourut="${i}" id="i_coa_item_${i}" class="form-control input-sm" name="i_coa_item_${i}" required>
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="text" readonly class="form-control input-sm date" placeholder="dd-mm-yyyy" name="d_bukti_${i}" id="d_bukti_${i}" value="" required>
                    </td>
                    <td>
                        <select id="i_area_item_${i}" class="form-control input-sm" name="i_area_item_${i}" required>
                            <option value=""></option>
                        </select>
                    </td>
                    <td class="clear" ${hidden}>
                        <select id="i_rv_refference_type_${i}" class="form-control input-sm" name="i_rv_refference_type_${i}">
                            <option value=""></option>
                        </select>
                    </td>
                    <td class="clear" ${hidden}>
                        <select data-no="${i}" id="i_rv_refference_${i}" class="form-control input-sm" name="i_rv_refference_${i}">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control input-sm" placeholder="Keterangan Transaksi" name="e_remark_item_${i}" id="e_remark_item_${i}" required value="">
                    </td>
                    <td>
                        <input type="text" class="form-control input-sm text-right v_rv" placeholder="Nilai Transaksi" name="v_rv_item_${i}" id="v_rv_item_${i}" required value="" onkeyup="angkahungkul(this);hetang();reformat(this);">
                    </td>
                    <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
            </tr>`;
            newRow.append(cols);
            $("#tabledatax").append(newRow);
            showCalendar('.date', null, 0);
            $('#i_coa_item_' + i).select2({
                placeholder: 'Cari CoA',
                dropdownAutoWidth: true,
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/coa/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term,
                            i_rv_type: $('#i_rv_type').val(),
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
            $('#i_area_item_' + i).select2({
                placeholder: 'Cari Area',
                dropdownAutoWidth: true,
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/area/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term,
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
            $("#i_rv_refference_type_" + i).select2({
                placeholder: 'Pilih Ref',
                dropdownAutoWidth: true,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder . '/cform/referensi_type/'); ?>',
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term,
                        };
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                    cache: false,
                },
            }).change(function (event) {
                var z = $(this).data("nourut");
                $("#i_rv_refference" + z).val("");
                $("#i_rv_refference" + z).html("");
            });
            $("#i_rv_refference_" + i).select2({
                placeholder: 'Pilih Referensi',
                dropdownAutoWidth: true,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder . '/cform/referensi/'); ?>',
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term,
                            i_area: $("#i_area_item_" + $(this).data("no")).val(),
                            i_rv_refference_type: $("#i_rv_refference_type_" + $(this).data("no")).val(),
                        };
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                    cache: false,
                },
            }).change(function () {
                var h = $(this).data("no");
                $.ajax({
                    type: "post",
                    data: {
                        'id': $(this).val(),
                        'i_rv_refference_type': $('#i_rv_refference_type_' + h).val(),
                    },
                    url: '<?= base_url($folder . '/cform/get_detail_referensi/'); ?>',
                    dataType: "json",
                    success: function (data) {
                        if (data['detail'].length > 0) {
                            $("#v_rv_item_" + h).val(formatcemua(data["detail"][0]["v_jumlah"]));
                        }
                        hetang();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
        });

        /**
         * Hapus Detail Item
         */

        $("#tabledatax").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();
            $('#jml').val(i);
            del();
        });

        $('#ceklis').click(function (event) {
            if ($('#ceklis').is(':checked')) {
                $("#i_rv_id").attr("readonly", false);
            } else {
                $("#i_rv_id").attr("readonly", true);
                $("#ada").attr("hidden", true);
                number();
            }
        });
    });

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function (key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'd_rv': $('#d_rv').val(),
                'i_bagian': $('#i_bagian').val(),
                'i_area': $('#i_area').val(),
                'i_rv_type': $('#i_rv_type').val(),
                'i_coa': $('#i_coa').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#i_rv_id').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function hetang() {
        let v_rv = 0;
        $("#tabledatax tbody tr td .v_rv").each(function () {
            let v = parseFloat(formatulang($(this).val()));
            if (isNaN(v)) {
                v = 0;
            }
            v_rv += v;
        });
        $('#v_rv').val(formatcemua(v_rv));
        $('#v_sisa_saldo').val(formatcemua(parseFloat(formatulang($('#v_saldo').val())) + v_rv));
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
           /*  $("#tabledatax tbody tr").each(function () {
                $(this).find("td select").each(function () {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Nota tidak boleh kosong!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            } else {
                return false;
            } */
            return true;
        }

    }

    function clear_table() {
        $("#tabledatax > tbody").remove();
        $("#jml").val(0);
    }
</script>