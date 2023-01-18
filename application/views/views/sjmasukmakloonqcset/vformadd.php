<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main');" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Surat Jalan Dari Supplier</label>
                        <label class="col-md-2">Tanggal Supplier</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br> -->
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" onchange="number();" required="" value="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="idocumentsup" name="idocumentsup" class="form-control input-sm" required="" value="">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsupplier" name="dsupplier" class="form-control input-sm date" required="" value="<?= date('d-m-Y'); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Partner</label>
                        <label class="col-sm-2">Tipe Makloon</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="itype" id="itype" class="form-control select2" required="" onchange="number();">
                                <option value="">Pilih Tipe Makloon</option>
                                <?php if ($type) {
                                    foreach ($type as $row) : ?>
                                        <option value="<?= $row->id; ?>">
                                            <?= $row->e_type_makloon_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3" id="eks">
                            <select name="ireffeks" id="ireffeks" multiple="multiple" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return konfirm();"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                        <div class="col-sm-4">
                            <button type="button" disabled="true" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <th class="text-center" style="width: 15%;">No Dokumen Keluar</th>
                        <th class="text-center" style="width: 35%;">Nama Panel</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Qty Kirim</th>
                        <th class="text-center" style="width: 10%;">Qty Terima</th>
                        <th class="text-center" style="width: 15%;">Ket</th>
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
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.date', null, 0);

        $('#itype').select2({
            placeholder: 'Type Makloon',
        }).change(function() {
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireffeks").val("");
            $("#ireffeks").html("");
            number();
        });

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
                        itype: $('#itype').val()
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
                    cols += '<td style="text-align: center"><input type="text" class="form-control input-sm" readonly id="ireferensi' + no + '" name="ireferensi' + no + '" value="' + data['detail'][a]['i_document'] + '"><input hidden class="form-control input-sm" readonly id="id_document' + no + '" name="id_document' + no + '" value="' + data['detail'][a]['id'] + '"><input hidden class="form-control input-sm" readonly id="id_panel_item' + no + '" name="id_panel_item' + no + '" value="' + data['detail'][a]['id_panel'] + '"><input hidden class="form-control input-sm" readonly id="idmarker' + no + '" name="idmarker' + no + '" value="' + data['detail'][a]['id_marker'] + '"></td>';
                    cols += '<td class="d-flex"><input class="form-control input-sm w-75" readonly id="iproduct' + no + '" name="iproduct' + no + '" value="' + data['detail'][a]['i_panel'] + '"> <input class="form-control input-sm w-25" readonly id="marker' + no + '" name="marker' + no + '" value="' + data['detail'][a]['e_marker_name'] + '"></td>';
                    cols += '<td><input type="hidden" id="icolor' + no + '" name="icolor' + no + '" value="' + data['detail'][a]['i_color'] + '"><input readonly class="form-control input-sm" id="ecolor' + no + '" name="ecolor' + no + '" value="' + data['detail'][a]['e_color_name'] + '"></td>';
                    cols += '<td><input class="form-control input-sm text-right" readonly id="sisa' + no + '" name="sisa' + no + '" value="' + data['detail'][a]['n_quantity'] + '"></td>';
                    cols += '<td><input class="form-control input-sm text-right" id="nquantity' + no + '" placeholder="0" name="nquantity' + no + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo(' + no + ');"></td>';
                    cols += '<td><input class="form-control input-sm" id="eremark' + no + '" name="eremark' + no + '" value="' + data['detail'][a]['e_remark'] + '"></td>';
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

    function ceksaldo(i) {
        if (parseFloat($('#nquantity' + i).val()) > parseFloat($('#sisa' + i).val())) {
            swal('Qty terima tidak boleh lebih dari qty kirim!!!');
            $('#nquantity' + i).val($('#sisa' + i).val());
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);

    });

    function konfirm() {
        var jml = $('#jml').val();
        if ($('#ireffeks').val() != '') {
            if (jml == 0) {
                swal('Isi data item minimal 1 !!!');
                return false;
            } else {
                var jumlah = 0;
                for (i = 1; i <= jml; i++) {

                    if ($('#nquantity' + i).val() != '' && $('#nquantity' + i).val() != null) {
                        jumlah = jumlah + parseFloat($('#nquantity' + i).val());
                    }

                }

                if (jumlah == 0) {
                    swal('Data item masih ada yang salah !!!');
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    }

    /**
     * Input Kode Manual
     */

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
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'itype': $('#itype').val(),
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


    $("#isj").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
                'itype': $('#itype').val(),
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
</script>