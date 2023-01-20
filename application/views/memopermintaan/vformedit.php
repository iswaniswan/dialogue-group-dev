<style type="text/css">
    #table td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-pencil mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Permintaan ke Gudang</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="idocument" required="" id="imemo" value="<?= $data->i_document; ?>" readonly="" class="form-control input-sm">
                                </div>
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= formatdmY($data->d_document); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <?php /*
                                <select name="id_type" id="id_type" required="" class="form-control select2" onchange="clear_table();">
                                    <?php if ($type) {
                                        foreach ($type->result() as $key) { ?>
                                            <option value="<?= trim($key->id); ?>" <?php if ($key->id == $data->id_type_penerima) { ?> selected <?php } ?>><?= $key->e_type_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                                */ ?>
                                <select name="id_type" id="id_type" required="" class="form-control select2" onchange="clear_table();">
                                    <?php if ($type) {
                                        $group = "";
                                        foreach ($type as $row) : ?>
                                        <?php if ($group!=$row->name) {?>
                                            </optgroup>
                                            <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                        <?php }
                                        $group = $row->name;
                                        ?>
                                        <?php $selected = '';
                                            if (($row->i_bagian == $data->i_tujuan) and ($row->id_company == $data->id_company_tujuan)) {
                                                $selected = 'selected';
                                            }
                                        ?>
                                            <option value="<?= $row->id_company . '|' . trim($row->id) ?>" <?= $selected?>> 
                                                <?= $row->e_type_name; ?> - <?= $row->name ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="itujuan_kirim" class="col-md-3">Tujuan Kirim</label>
                            <label for="dkirim" class="col-md-3">Tanggal Kirim</label>
                            <label class="col-md-6">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="itujuan_kirim" id="itujuan_kirim" class="form-control select2" onchange="number();">
                                    <!-- <option value="<?= $data->i_tujuan ?>" selected><?= $data->e_tujuan_name ?> - <?= $data->company_tujuan ?></option> -->
                                    <?php if ($tujuan) {
                                        $group = "";
                                        foreach ($tujuan as $row) : ?>
                                        <?php if ($group!=$row->name) {?>
                                            </optgroup>
                                            <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                        <?php }
                                        $group = $row->name;
                                        ?>
                                            <option value="<?= "$row->id"; ?>" <?= ($row->id == $data->i_tujuan) ? 'selected' : '' ?>>
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="dkirim" required="" id="dkirim" class="form-control input-sm date-kirim" value="<?= formatdmY($data->d_kirim); ?>" readonly>
                            </div>
                            <div class="col-sm-6">
                                <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <div class="col-sm-3">
                                    <button type="button" id="submit" class="btn btn-success btn-block btn-sm" onclick="return simpan();"><i class="fa fa-save mr-2 fa-lg"></i>Update</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2 fa-lg"></i>Send</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-info btn-block btn-sm mr-2" onclick="tambah_product(parseInt($('#jml').val()));"> <i class="fa fa-plus fa-lg mr-2"></i>Item</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash mr-2 fa-lg"></i>Delete</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                                </div>
                            <?php } elseif ($data->i_status == '2') { ?>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh mr-2 fa-lg"></i>Cancel</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
                </div>
                <div class="col-sm-6 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
            </div>
            <div class="table-responsive">
                <table id="table" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th style="width: 35%;">Nama Barang</th>
                            <th class="text-right" style="width: 10%;">Qty</th>
                            <th colspan="3" style="width: 30%;"></th>
                            <th class="text-center" style="width: 3%;">Act</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                        $i = 0;
                        $ii = 0;
                        $group = "";
                        if ($datadetail) {
                            foreach ($datadetail as $key) {
                                $ii++;
                                if ($group != $key->id_product) {
                                    $i++; ?>
                                    <tr class="tr tr_first<?= $i; ?>">
                                        <td class="text-center">
                                            <spanlistx id="snum<?= $i; ?>"><b><?= $i; ?></b></spanlistx>
                                        </td>
                                        <td>
                                            <select id="id_product_wip<?= $i; ?>" class="form-control input-sm" name="id_product_wip<?= $i; ?>" onchange="cek_product(<?= $i; ?>);">
                                                <option value="<?= $key->id_product; ?>"><?= '[' . $key->i_product . '] - ' . $key->e_product . ' ' . $key->e_color_name; ?></option>
                                            </select>
                                            <select id="id_marker<?= $i; ?>" class="form-control input-sm" name="id_marker<?= $i; ?>" onchange="cek_product(<?= $i; ?>);">
                                                <option value="<?= $key->id_marker; ?>"><?= $key->e_marker_name; ?></option>
                                            </select>
                                        </td>
                                        <td><input type="number" id="n_quantity<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="n_quantity<?= $i; ?>" onblur="if(this.value==''){this.value=='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity_product ?>" onkeyup="berhitung(<?= $i; ?>);setqtyproduct(<?= $i; ?>)"></td>
                                        <td colspan="3"></td>
                                        <td class="text-center"><button type="button" onclick="hapus_detail(<?= $i; ?>);" title="Delete" class="btn btn-sm btn-circle btn-danger"><i class="fa fa-close fa-lg" aria-hidden="true"></i></button></td>
                                    </tr>
                                    <tr class="table-active tr_second<?= $i; ?>">
                                        <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                        <td><b>LIST BARANG MATERIAL</b></td>
                                        <td class="text-right"><b>Kebutuhan Per Pcs</b></td>
                                        <td class="text-right"><b>Stock Material</b></td>
                                        <td class="text-right"><b>Kebutuhan Material</b></td>
                                        <td><b>Keterangan</b></td>
                                        <td class="text-center"><button data-urut="<?= $i; ?>" type="button" onclick="tambah_material(<?= $i; ?>);" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i data-urut="<?= $i; ?>" id="addlist<?= $i; ?>" class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></button></td>
                                    </tr>
                                <?php }
                                $group = $key->id_product;
                                ?>
                                <tr class="td_<?= $i; ?>">
                                    <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
                                    <td>
                                        <select data-iter="<?= $i; ?>_<?= $ii; ?>" class="form-control input-sm material_<?= $i; ?>" id="id_material_<?= $i; ?>_<?= $ii; ?>" name="id_material[]" onchange="get_material_detail(<?= $i; ?>,<?= $ii; ?>);">
                                            <option value="<?= $key->id_material; ?>"><?= '[' . $key->i_material . '] - ' . $key->e_material_name . ' ' . $key->e_satuan_name; ?></option>
                                        </select>
                                        <input type="hidden" class="product_<?= $i; ?>" id="id_product_<?= $i; ?>_<?= $ii; ?>" name="id_product[]" value="<?= $key->id_product; ?>">
                                        <input type="hidden" class="product_<?= $i; ?>" id="id_marker_<?= $i; ?>_<?= $ii; ?>" name="id_marker[]" value="<?= $key->id_marker; ?>">
                                        <input type="hidden" class="product_<?= $i; ?>" id="n_quantity_product_<?= $i; ?>" name="n_quantity_product[]" value="<?= $key->n_quantity_product; ?>">
                                    </td>
                                    <td><input type="text" id="n_kebutuhan_<?= $i; ?>_<?= $ii; ?>" class="form-control text-right input-sm" readonly name="n_kebutuhan[]" value="<?= $key->n_kebutuhan; ?>"></td>
                                    <td><input type="text" id="n_stock_material_<?= $i; ?>_<?= $ii; ?>" class="form-control text-right input-sm" readonly name="n_stock_material[]" value="<?= $key->n_saldo_akhir; ?>"></td>
                                    <td><input type="number" id="n_kebutuhan_material_<?= $i; ?>_<?= $ii; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_kebutuhan_material[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity; ?>" onkeyup="angkahungkul(this);"></td>
                                    <td><input type="text" class="form-control input-sm" name="e_note[]" value="<?= $key->e_remark; ?>" placeholder="Isi keterangan jika ada!" /></td>
                                    <td class="text-center"><button type="button" title="Delete" data-b="<?= $i; ?>" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
    <input type="hidden" name="jml_item" id="jml_item" value="<?= $ii; ?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/

    $(document).ready(function() {
        // var data = $('#itujuan_kirim option:selected').text();
        // $('#itujuan_kirim option:selected').text(`${data} - <?= $data->company_tujuan ?>`);
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('.select2').select2();
        showCalendar('.date-kirim');
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.tgl', 0);

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

        $("#table").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            var obj = $('#table tr:visible').find('spanlistx');
            $.each(obj, function(key, value) {
                id = value.id;
                $('#' + id).html(key + 1);
            });
        });

        for (let i = 1; i <= $('#jml').val(); i++) {
            $('#id_product_wip' + i).select2({
                placeholder: 'Cari Kode / Nama WIP',
                allowClear: true,
                width: "75%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product_wip/'); ?>',
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
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $('#id_marker' + i).select2({
                placeholder: 'Cari Nama Marker',
                allowClear: true,
                width: "25%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/marker/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            id_product_wip: $('#id_product_wip' + i).val()
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

            for (let ii = 1; ii <= $('#jml_item').val(); ii++) {
                $(`#id_material_${i}_${ii}`).select2({
                    placeholder: 'Cari Kode / Nama Material',
                    allowClear: true,
                    width: "100%",
                    type: "POST",
                    ajax: {
                        url: '<?= base_url($folder . '/cform/material/'); ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            var query = {
                                q: params.term,
                                id_product: $('#id_product_wip' + i).val(),
                                id_type: $('#id_type').val(),
                            }
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                        cache: true
                    }
                });
            }
        }

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                /* if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else { */
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
                                swal("Sukses!", "No Dokumen : " + data.kode + ", Berhasil Diupdate :)", "success");
                                $("input").attr("disabled", true);
                                $("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("disabled", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                            } else {
                                swal("Maaf :(", "No Dokumen : " + data.kode + ", Gagal Diupdate :(", "error");
                            }
                        },
                        error: function() {
                            swal("Maaf", "Data Gagal Diupdate :(", "error");
                        }
                    });
                });
                // }
            }
            return false;
        });
    });

    function tambah_product(jml) {
        i = jml + 1;
        $("#jml").val(i);
        var no = $(`#table .tr`).length;
        var newRow = $(`<tr class="tr tr_first${i}">`);
        var cols = "";
        cols += `
            <td class="text-center"><spanlistx id="snum${i}"><b>${(no+1)}</b></spanlistx></td>
            <td><select id="id_product_wip${i}" class="form-control input-sm" name="id_product_wip${i}" onchange="cek_product(${i});"></select> <select id="id_marker${i}" class="form-control input-sm" name="id_marker${i}" onchange="cek_product(${i});"></select></td>
            <td><input type="number" id="n_quantity${i}" class="form-control text-right input-sm" autocomplete="off" name="n_quantity${i}" onblur="if(this.value==''){this.value=='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="berhitung(${i});setqtyproduct(${i})"></td>
            <td colspan="3"></td>
            <td class="text-center"><button type="button" onclick="hapus_detail(${i});" title="Delete" class="btn btn-sm btn-circle btn-danger"><i class="fa fa-close fa-lg" aria-hidden="true"></i></button></td>
        `;
        newRow.append(cols);
        $("#table tr:first").after(newRow);
        var newRow1 = $(
            `<tr class="table-active tr_second${i}">
                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                <td><b>LIST BARANG MATERIAL</b></td>
                <td class="text-right"><b>Kebutuhan Per Pcs</b></td>
                <td class="text-right"><b>Stock Material</b></td>
                <td class="text-right"><b>Kebutuhan Material</b></td>
                <td><b>Keterangan</b></td>
                <td class="text-center"><button data-urut="${i}" type="button" onclick="tambah_material(${i});" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i data-urut="${i}" id="addlist${i}"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></button></td>
            </tr>`);
        $(newRow1).insertAfter("#table .tr_first" + i);
        restart();
        $('#id_product_wip' + i).select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "75%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/product_wip/'); ?>',
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
                        results: data
                    };
                },
                cache: true
            }
        });
        $('#id_marker' + i).select2({
            placeholder: 'Cari Nama Marker',
            allowClear: true,
            width: "25%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/marker/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        id_product_wip: $('#id_product_wip' + i).val()
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
        }).change(function(e) {
            $.ajax({
                type: "post",
                data: {
                    id_product_wip: $('#id_product_wip' + i).val(),
                    // 'id_material': $('#id_material_' + i + '_' + ii).val(),
                    id_type: $('#id_type').val(),
                    id_marker: $(this).val()
                },
                url: '<?= base_url($folder . '/cform/get_material_onchange_detail'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['detail'].length > 0) {
                        data['detail'].map((d) => {
                            // console.log(d)
                            var ii = parseInt($('#jml_item').val()) + 1;
                            var col = "";
                            $('#jml_item').val(ii);
                            var newRow = $(`<tr class="td_${i}">`);
                            col += `
                            <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
                            <td>
                                <select data-iter = "${i}_${ii}" class="form-control input-sm material_${i}" id="id_material_${i}_${ii}" name="id_material[]" onchange="get_material_detail(${i},${ii});">
                                    <option value="${d.id_material}">[${d.i_material}] - ${d.e_material_name} - ${d.e_satuan_name}</option>
                                </select>
                                <input type="hidden" class="product_${i}" id="id_product_${i}_${ii}" name="id_product[]">
                                <input type="hidden" class="product_${i}" id="id_marker_${i}_${ii}" name="id_marker[]">
                                <input type="hidden" class="product_${i}" id="n_quantity_product_${i}" name="n_quantity_product[]">
                            </td>
                            <td><input type="text" id="n_kebutuhan_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_kebutuhan[]" value="${d.n_kebutuhan}"></td>
                            <td><input type="text" id="n_stock_material_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_stock_material[]" value="${d.n_saldo_akhir}"></td>
                            <td><input type="number" id="n_kebutuhan_material_${i}_${ii}" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_kebutuhan_material[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>
                            <td><input type="text" class="form-control input-sm" name="e_note[]" placeholder="Isi keterangan jika ada!"/></td>
                            <td class="text-center"><button type="button" title="Delete" data-b="${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
                            `;
                            newRow.append(col);
                            $(newRow).insertAfter("#table .tr_second" + i);
                            $(`#id_product_${i}_${ii}`).val($('#id_product_wip' + i).val());
                            $(`#id_marker_${i}_${ii}`).val($('#id_marker' + i).val());
    
                            $(`#id_material_${i}_${ii}`).select2({
                                placeholder: 'Cari Kode / Nama Material',
                                allowClear: true,
                                width: "100%",
                                type: "POST",
                                ajax: {
                                    url: '<?= base_url($folder . '/cform/material/'); ?>',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        var query = {
                                            q: params.term,
                                            id_product: $('#id_product_wip' + i).val(),
                                            id_type: $('#id_type').val(),
                                            id_marker: $('#id_marker' + i).val()
                                        }
                                        return query;
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data,
                                        };
                                    },
                                    cache: true
                                }
                            });
                        })
                    }
                },
                error: function() {
                    swal('Error :)');
                }
            });
        });
    }

    function cek_product(i) {
        $(".td_" + i).remove();
        var ada = true;
        for (var x = 1; x <= $('#jml').val(); x++) {
            if ($('#id_product_wip' + i).val() != null) {
                if ((($('#id_product_wip' + i).val()) == $('#id_product_wip' + x).val()) && (i != x)) {
                    swal("kode barang tersebut sudah ada !!!!!");
                    ada = false;
                    break;
                }
            }
        }
        if (!ada) {
            $('#id_product_wip' + i).val('');
            $('#id_product_wip' + i).html('');
        }
    }

    function hapus_detail(x) {
        $(".tr_first" + x).remove();
        $(".tr_second" + x).remove();
        $(".td_" + x).remove();
        restart();
    }

    function restart() {
        var obj = $('#table tr:visible').find('spanlistx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function tambah_material(i) {
        var ii = parseInt($('#jml_item').val()) + 1;
        var col = "";
        $('#jml_item').val(ii);
        var newRow = $(`<tr class="td_${i}">`);
        col += `
        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
        <td>
            <select data-iter = "${i}_${ii}" class="form-control input-sm material_${i}" id="id_material_${i}_${ii}" name="id_material[]" onchange="get_material_detail(${i},${ii});"></select>
            <input type="hidden" class="product_${i}" id="id_product_${i}_${ii}" name="id_product[]">
            <input type="hidden" class="product_${i}" id="id_marker_${i}_${ii}" name="id_marker[]">
            <input type="hidden" class="product_${i}" id="n_quantity_product_${i}" name="n_quantity_product[]">
        </td>
        <td><input type="text" id="n_kebutuhan_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_kebutuhan[]" value="0"></td>
        <td><input type="text" id="n_stock_material_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_stock_material[]" value="0"></td>
        <td><input type="number" id="n_kebutuhan_material_${i}_${ii}" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_kebutuhan_material[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>
        <td><input type="text" class="form-control input-sm" name="e_note[]" placeholder="Isi keterangan jika ada!"/></td>
        <td class="text-center"><button type="button" title="Delete" data-b = "${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
        `;
        newRow.append(col);
        $(newRow).insertAfter("#table .tr_second" + i);
        $(`#id_product_${i}_${ii}`).val($('#id_product_wip' + i).val());
        $(`#id_marker_${i}_${ii}`).val($('#id_marker' + i).val());

        $(`#id_material_${i}_${ii}`).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        id_product: $('#id_product_wip' + i).val(),
                        id_type: $('#id_type').val(),
                        id_marker: $('#id_marker' + i).val()
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: true
            }
        });
    }

    function get_material_detail(i, ii) {
        var ada = true;
        var y = i + '_' + ii;
        $(`#table tr .material_${i}`).each(function() {
            var z = ($(this).data('iter'));
            if ($(this).val() != null) {
                if ((($(this).val()) == $('#id_material_' + i + '_' + ii).val()) && (z != y)) {
                    swal("kode material tersebut sudah ada !!!!!");
                    ada = false;
                    // break;
                    return false;
                }
            }
        });
        if (!ada) {
            $('#id_material_' + i + '_' + ii).val('');
            $('#id_material_' + i + '_' + ii).html('');
        } else {
            $.ajax({
                type: "post",
                data: {
                    'id_product_wip': $('#id_product_wip' + i).val(),
                    'id_material': $('#id_material_' + i + '_' + ii).val()
                },
                url: '<?= base_url($folder . '/cform/get_material_detail'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['detail'].length > 0) {
                        $('#n_kebutuhan_' + i + '_' + ii).val(data['detail'][0]['n_kebutuhan']);
                        $('#n_stock_material_' + i + '_' + ii).val(data['detail'][0]['n_saldo_akhir']);
                        $('#n_quantity' + i).focus();
                        berhitung(i);
                    }
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }
    }

    /**
     * Hapus Detail Item
     */

    function clear_table() {
        $("#table tr:gt(0)").remove();
        $('#jml').val(0);
    }

    function berhitung(i) {
        var n_quantity_product = parseFloat($('#n_quantity' + i).val());
        if (isNaN(n_quantity_product)) {
            n_quantity_product = 0;
        }
        $(`#table .td_${i}`).each(function() {
            var v = parseFloat($(this).find("input[name='n_kebutuhan[]']").val());
            $(this).find("input[name='n_kebutuhan_material[]']").val(n_quantity_product * v);
        })
    }
    function setqtyproduct(i) {
        var n_quantity_product = parseFloat($('#n_quantity' + i).val());
        if (isNaN(n_quantity_product)) {
            n_quantity_product = 0;
        }
        $(`#table .td_${i}`).each(function() {
            // var v = parseFloat($(this).find("input[name='n_kebutuhan[]']").val());
            $(this).find(`#n_quantity_product_${i}`).val(n_quantity_product);
        })
    }
</script>