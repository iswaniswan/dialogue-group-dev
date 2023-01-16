<style>
    .nowrap {
        white-space: nowrap !important;
    }

    .warna {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #ddd;
        font-weight: bold;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id; ?>">
                            <input type="hidden" id="ipcuttingold" name="idocumentold" class="form-control" value="<?= $data->i_document; ?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="ipcutting" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SC-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document; ?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" value="<?= $data->d_document; ?>" placeholder="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">

                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <label class="col-md-4">Keterangan</label>

                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);">
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                            <span class="notekode">Klik (x) lalu pilih Forecast Cutting untuk meload data </span><br>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') { ?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') { ?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php } elseif ($data->i_status == '2') { ?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
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
                <table id="tabledatax" class="table color-table nowrap success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th class="text-center" width="7%">Kode</th>
                            <th class="text-center" width="30%">Nama Material</th>
                            <th class="text-center" width="10%">Bagian</th>
                            <th class="text-center" width="7%">Gelar</th>
                            <th class="text-center" width="7%">Set</th>
                            <th class="text-center" width="10%">Panjang Gelar</th>
                            <th class="text-center" width="10%">Panjang Kain</th>
                            <th class="text-center" width="5%">Auto Cutter</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $z = 0; $iter = 0;
                        $group = "";
                        foreach ($datadetail as $key) {
                            $i++;
                            $iter++;
                            if ($group != $key->id_product_wip) {
                                $z++;
                            }
                        ?>
                            <?php if ($group == "") { ?>
                                <tr id="tr<?= $z; ?>" class="warna">
                                    <td colspan="7">
                                        <input readonly data-nourut="<?= $z; ?>" id="iproduct<?= $z; ?>" type="text" class="form-control input-sm" name="iproduct<?= $z; ?>" value="<?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>">
                                        <input type="hidden" name="idproduct<?= $z; ?>" id="idproduct<?= $z; ?>" class="form-control" value="<?= $key->id_product_wip; ?>">
                                    </td>
                                    <!-- <td>
                                        <input style="width:100px;" readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywip<?= $z; ?>" id="nquantitywip<?= $z; ?>"value="<?= $key->n_quantity_wip_keluar; ?>" >
                                    </td> -->
                                    <td>
                                        <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywipsisa<?= $z; ?>" id="nquantitywipsisa<?= $z; ?>" value="<?= $key->n_quantity_wip_sisa; ?>">
                                    </td>
                                    <!--    <td>
                                        <input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantitywipmasuk<?= $z; ?>" id="nquantitywipmasuk<?= $z; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_quantity_wip_masuk; ?>" onkeyup="angkahungkul(this);validasi(<?= $z; ?>)">
                                    </td> -->
                                    <td colspan="2"></td>
                                </tr>
                                <?php } else {
                                if ($group != $key->id_product_wip) { ?>
                                    <tr id="tr<?= $z; ?>" class="warna">
                                        <td colspan="7">
                                            <input readonly data-nourut="<?= $z; ?>" id="iproduct<?= $z; ?>" type="text" class="form-control input-sm" name="iproduct<?= $z; ?>" value="<?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>">
                                            <input type="hidden" name="idproduct<?= $z; ?>" id="idproduct<?= $z; ?>" class="form-control" value="<?= $key->id_product_wip; ?>">
                                        </td>
                                        <!-- <td>
                                            <input style="width:100px;" readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywip<?= $z; ?>" id="nquantitywip<?= $z; ?>" value="<?= $key->n_quantity_wip_keluar; ?>">
                                        </td> -->
                                        <td>
                                            <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywipsisa<?= $z; ?>" id="nquantitywipsisa<?= $z; ?>" value="<?= $key->n_quantity_wip_sisa; ?>">
                                        </td>
                                        <!-- <td>
                                            <input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantitywipmasuk<?= $z; ?>" id="nquantitywipmasuk<?= $z; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_quantity_wip_masuk; ?>" onkeyup="angkahungkul(this);validasi(<?= $z; ?>)">
                                        </td> -->
                                        <td colspan="2"></td>
                                    </tr>
                            <?php $i = 1;
                                }
                            } ?>
                            <?php $group = $key->id_product_wip; ?>
                            <tr class="del<?= $z; ?>">
                                <td class="text-center"><?= $i; ?></td>
                                <td>
                                    <input type="hidden" name="id_fccutting_item[]" id="id_fccutting_item<?= $iter; ?>" value="<?= $key->id_fccutting_item; ?>">
                                    <input type="hidden" name="id_product_wip[]" id="id_product_wip<?= $iter; ?>" value="<?= $key->id_product_wip; ?>">
                                    <input type="hidden" class="id_material" name="id_material[]" id="id_material<?= $iter; ?>" value="<?= $key->id_material; ?>">
                                    <input class="form-control input-sm" readonly type="text" value="<?= $key->i_material; ?>">
                                </td>
                                <td><input class="form-control input-sm" readonly type="text " value="<?= $key->e_material_name; ?>"></td>
                                <td><input class="form-control input-sm" name="e_bagian[]" readonly type="text " value="<?= $key->e_bagian; ?>"></td>
                                <td><input class="form-control input-sm text-right" name="n_gelar[]" readonly type="text " value="<?= $key->n_gelar; ?>"></td>
                                <td><input class="form-control input-sm text-right" name="n_set[]" readonly type="text " value="<?= $key->n_set; ?>"></td>
                                <td><input class="form-control input-sm text-right" name="n_panjang_gelar[]" readonly type="text " value="<?= $key->n_panjang_gelar; ?>"></td>
                                <td><input class="form-control input-sm text-right" name="n_panjang_kain[]" readonly type="text " value="<?= $key->n_panjang_kain; ?>"></td>
                                <td class="text-center">
                                    <input type="checkbox" id="f_auto_cutter<?= $iter; ?>" onclick="set_check(<?= $iter; ?>);" name="f_auto_cutter[]" <?php if($key->f_auto_cutter=='t'){?> checked <?php } ?>>
                                    <input type="hidden" id="f_auto<?= $iter; ?>" onclick name="f_auto[]"  <?php if($key->f_auto_cutter=='t'){?> value="t" <?php }else{ ?> value="f" <?php } ?>>
                                </td>
                                <td><input class="form-control input-sm" type="text" name="edesc[]" id="edesc<?= $iter; ?>" value="<?= $key->e_remark; ?>" placeholder="Isi keterangan jika ada!"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
    </form>
<?php } ?>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        max_tgl();
        /**
         * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
         */
        showCalendar('.date', null, 0);

        $('#ipcutting').mask('SS-0000-000000S');
        $('#ireff').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/referensi'); ?>',
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
                cache: false
            }
        });
    });

    $("#ipcutting").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $("#ibagian").val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $("#ipengirim").change(function() {
        $('#ireff').attr("disabled", false);
    });

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#ipcutting").attr("readonly", false);
        } else {
            $("#ipcutting").attr("readonly", true);
        }
    });

    function getdataitem(ireff) {
        var idreff = $('#ireff').val();
        // var ipengirim = $('#ipengirim').val();
        if (idreff) {
            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                },
                url: '<?= base_url($folder . '/cform/getdataitem'); ?>',
                dataType: "json",
                success: function(data) {

                    $('#jml').val(data['jmlitem']);
                    $("#tabledatax tbody").remove();
                    $("#detail").attr("hidden", false);

                    var dref = data['datahead']['d_document'];
                    $("#dreferensi").val(dref);
                    group = "";
                    i = 0;
                    for (let a = 0; a < data['jmlitem']; a++) {
                        i++;
                        //var no = a+1;
                        //count=$('#tabledatax tr').length;   
                        var idproduct = data['dataitem'][a]['id_product_wip'];
                        var newRow = $("<tr>");
                        var cols = "";
                        var cols1 = "";
                        if (group == "") {
                            cols1 += '<td colspan="7" class="warna"><input type="text" id="iproduct' + a + '" class="form-control input-sm" name="iproduct' + a + '" value="' + data['dataitem'][a]['i_product_wip'] + ' - ' + data['dataitem'][a]['e_product_wipname'] + ' - ' + data['dataitem'][a]['e_color_name'] + '" readonly><input type="hidden" id="idproduct' + a + '" class="form-control" name="idproduct' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '" readonly></td>';
                            // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" value="'+data['dataitem'][a]['n_quantity_wip']+'" readonly></td>';
                            cols1 += '<td class="warna"><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa' + a + '" name="nquantitywipsisa' + a + '" value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" readonly></td>';
                            // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="validasi('+a+');"></td>';
                            cols1 += '<td class="warna" colspan="2"></td>';
                        } else {
                            if (group != idproduct) {
                                cols1 += '<td class="warna" colspan="7"><input type="text" id="iproduct' + a + '" class="form-control input-sm" name="iproduct' + a + '" value="' + data['dataitem'][a]['i_product_wip'] + ' - ' + data['dataitem'][a]['e_product_wipname'] + ' - ' + data['dataitem'][a]['e_color_name'] + '" readonly><input type="hidden" id="idproduct' + a + '" class="form-control" name="idproduct' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '" readonly></td>';
                                // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" value="'+data['dataitem'][a]['n_quantity_wip']+'" readonly></td>';
                                cols1 += '<td class="warna"><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa' + a + '" name="nquantitywipsisa' + a + '" value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" readonly></td>';
                                // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="validasi('+a+');"></td>';
                                cols1 += '<td class="warna" colspan="2"></td>';
                                i = 1;
                            }
                        }
                        newRow.append(cols1);
                        $("#tabledatax").append(newRow);
                        group = idproduct;
                        var newRow = $("<tr>");
                        cols += '<td class="text-center">' + (i) + '</td>';
                        cols += '<td><input type="hidden" name="id_fccutting_item[]" id="id_fccutting_item' + a + '" value="' + data['dataitem'][a]['id'] + '"><input type="hidden" name="id_product_wip[]" id="id_product_wip' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '">';
                        cols += '<input type="hidden" class="id_material" name="id_material[]" id="id_material' + a + '" value="' + data['dataitem'][a]['id_material'] + '">';
                        cols += '<input class="form-control input-sm" readonly type="text" value="' + data['dataitem'][a]['i_material'] + '"></td>';
                        cols += '<td><input class="form-control input-sm" readonly type="text " value="' + data['dataitem'][a]['e_material_name'] + '"></td>';
                        cols += '<td><input class="form-control input-sm" name="e_bagian[]" readonly type="text " value="' + data['dataitem'][a]['e_bagian'] + '"></td>';
                        cols += '<td><input class="form-control input-sm text-right" name="n_gelar[]" readonly type="text " value="' + data['dataitem'][a]['v_gelar'] + '"></td>';
                        cols += '<td><input class="form-control input-sm text-right" name="n_set[]" readonly type="text " value="' + data['dataitem'][a]['v_set'] + '"></td>';
                        cols += '<td><input class="form-control input-sm text-right" name="n_panjang_gelar[]" readonly type="text " value="0"></td>';
                        cols += '<td><input class="form-control input-sm text-right" name="n_panjang_kain[]" readonly type="text " value="0"></td>';
                        cols += `<td class="text-center"><input type="checkbox" id="f_auto_cutter${a}" onclick="set_check(${a});" name="f_auto_cutter[]" checked><input type="hidden" id="f_auto${a}" onclick name="f_auto[]" value="t"></td>`;
                        cols += '<td colspan="2"><input class="form-control input-sm" type="text" name="edesc[]" id="edesc' + a + '" value="' + data['dataitem'][a]['e_remark'] + '" placeholder="Isi keterangan jika ada!"></td></tr>';
                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
                        showCalendar('.date', 0);
                    }

                    function formatSelection(val) {
                        return val.name;
                    }

                    $("#tabledatax").on("click", ".ibtnDel", function(event) {
                        $(this).closest("tr").remove();
                    });
                    max_tgl();
                },
                error: function() {
                    alert('Error :)');
                }
            });
        }

    }

    function set_check(i) {
        alert($("#f_auto_cutter" + i + ":checked").length);
        if ($("#f_auto_cutter" + i + ":checked").length > 0) {
            $('#f_auto' + i).val('t');
        } else {
            $('#f_auto' + i).val('f');
        }
    }

    function max_tgl() {
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dreferensi').value,
        });
    }

    function validasi(id) {
        nquantityma = $("#nquantitywipsisa" + id).val();
        nquantitymasuk = $("#nquantitywipmasuk" + id).val();
        nquantitymaterial = $("#nquantitybahansisa" + id).val();
        nquantitymasukmaterial = $("#nquantitybahanmasuk" + id).val();

        if (parseFloat(nquantitymasuk) > parseFloat(nquantityma)) {
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) > parseFloat(nquantitymaterial)) {
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        }

        if (parseFloat(nquantitymasuk) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        }
    }

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });
    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });
    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        // $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            } else {
                return false;
            }
        }
    }
</script>