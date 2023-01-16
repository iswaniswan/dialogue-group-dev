<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil mr-2"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Surat Jalan Dari Supplier</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <option value="" selected>-- Pilih Bagian --</option>
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document; ?>">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="idocumentsup" name="idocumentsup" class="form-control input-sm" required="" value="<?= $data->i_document_supplier; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Partner</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value="<?= $data->id_supplier; ?>"><?= $data->e_supplier_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3" id="eks">
                            <select name="ireffeks" id="ireffeks" multiple="multiple" class="form-control select2">
                                <?php foreach ($referensi as $row) { ?>
                                    <option value="<?= $row->id; ?>" selected><?= $row->i_document; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                            <div class="col-sm-3">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Update</button>
                            </div>
                        <?php } ?>
                        <?php if ($data->i_status == '1') { ?>
                            <div class="col-sm-3">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                        <?php } elseif ($data->i_status == '2') { ?>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0;
if ($datadetail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-3">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th style="width: 10%;">No. Dok Keluar</th>
                            <th style="width: 30%;">Nama Barang</th>
                            <th style="width: 10%;">Warna</th>
                            <th class="text-right" style="width: 8%;">Qty Keluar</th>
                            <th class="text-right" style="width: 8%;">Qty Sisa</th>
                            <th class="text-right" style="width: 8%;">Qty Masuk</th>
                            <th style="width: 15%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) {
                            $i++;
                        ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td style="text-align: center">
                                    <input type="text" class="form-control input-sm" readonly id="ireferensi<?= $i; ?>" name="ireferensi<?= $i; ?>" value="<?= $key->i_document; ?>">
                                    <input hidden class="form-control input-sm" readonly id="id_document<?= $i; ?>" name="id_document<?= $i; ?>" value="<?= $key->id_document_reff; ?>">
                                    <input hidden class="form-control input-sm" readonly id="id_product<?= $i; ?>" name="id_product<?= $i; ?>" value="<?= $key->id_product; ?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm" readonly id="iproduct<?= $i; ?>" name="iproduct<?= $i; ?>" value="<?= $key->i_product_base . ' - ' . $key->e_product_basename; ?>">
                                </td>
                                <td>
                                    <input readonly class="form-control input-sm" id="ecolor<?= $i; ?>" name="ecolor<?= $i; ?>" value="<?= $key->e_color_name; ?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm text-right" readonly id="nquantitykeluar<?= $i; ?>" name="nquantitykeluar<?= $i; ?>" value="<?= $key->keluarfull; ?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm text-right" readonly id="sisa<?= $i; ?>" name="sisa<?= $i; ?>" value="<?= $key->keluar; ?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm text-right inputitem" id="nquantity<?= $i; ?>" placeholder="0" name="nquantity<?= $i; ?>" value="<?= $key->masuk; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo(<?= $i; ?>);">
                                </td>
                                <td>
                                    <input class="form-control input-sm" id="eremark<?= $i; ?>" name="eremark<?= $i; ?>" value="<?= $key->e_remark; ?>">
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.date', null, 0);
        //number();

        $('#ipartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/partner/'); ?>',
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
        }).change(function(event) {
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireffeks").val("");
            $("#ireffeks").html("");
        });;

        $('#ireffeks').select2({
            placeholder: 'Cari No Referensi',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/refeksternal'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ipartner: $('#ipartner').val(),
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

    $("#ireffeks").change(function() {
        $("#ireffeks").val($(this).val());
        $("#tabledatax tr:gt(0)").remove();
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'id': $(this).val(),
            },
            url: '<?= base_url($folder . '/cform/getdetailrefeks'); ?>',
            dataType: "json",
            success: function(data) {
                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    var no = a + 1;
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">' + no + '</td>';
                    cols += '<td style="text-align: center"><input style="width:200px;" type="text" class="form-control" readonly id="ireferensi' + no + '" name="ireferensi' + no + '" value="' + data['detail'][a]['i_document'] + '"><input hidden class="form-control" readonly id="id_document' + no + '" name="id_document' + no + '" value="' + data['detail'][a]['id'] + '"><input hidden class="form-control" readonly id="id_product' + no + '" name="id_product' + no + '" value="' + data['detail'][a]['id_product_base'] + '"></td>';
                    cols += '<td><input style="width:400px;" class="form-control" readonly id="iproduct' + no + '" name="iproduct' + no + '" value="' + data['detail'][a]['i_product_base'] + ' - ' + data['detail'][a]['e_product_basename'] + '"></td>';
                    cols += '<td><input type="hidden" id="icolor' + no + '" name="icolor' + no + '" value="' + data['detail'][a]['i_color'] + '"><input style="width:150px;" readonly class="form-control" id="ecolor' + no + '" name="ecolor' + no + '" value="' + data['detail'][a]['e_color_name'] + '"></td>';
                    cols += '<td><input style="width:100px;" class="form-control text-right" readonly id="nquantitykeluar' + no + '" name="nquantitykeluar' + no + '" value="' + data['detail'][a]['n_quantity'] + '"></td>';
                    cols += '<td><input style="width:100px;" class="form-control text-right" readonly id="sisa' + no + '" name="sisa' + no + '" value="' + data['detail'][a]['n_quantity_sisa'] + '"></td>';
                    cols += '<td><input style="width:100px;" class="form-control text-right inputitem" id="nquantity' + no + '" name="nquantity' + no + '" value="' + data['detail'][a]['n_quantity_sisa'] + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo(' + no + ');"></td>';
                    cols += '<td><input style="width:400px;" class="form-control" id="eremark' + no + '" name="eremark' + no + '" value=""></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
            },
            error: function() {
                swal('Data kosong :)');
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    function ceksaldo(i) {
        if (parseFloat($('#nquantity' + i).val()) > parseFloat($('#sisa' + i).val())) {
            swal('Quantity Masuk tidak boleh lebih dari Quantity sisa!!!');
            $('#nquantity' + i).val($('#sisa' + i).val());
        }
        if (parseFloat($('#nquantity' + i).val()) == 0 || parseFloat($('#nquantity' + i).val()) == null) {
            swal('Quantity Masuk tidak boleh 0 atau Kosong!!!');
            $('#nquantity' + i).val($('#sisa' + i).val());
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    /**
     * Input Kode Manual
     */
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
                    $("#send").attr("disabled", true);
                    $("#hapus").attr("disabled", true);
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

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#isj").attr("readonly", false);
        } else {
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /**
     * Running Number
     */
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#isj').val($('#idocumentold').val());
        } else {
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
                    swal('Error :)');
                }
            });
        }
    }

    function konfirm() {
        ada = false;
        if ($('#jml').val() == 0) {
            swal('Isi item minimal 1!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
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