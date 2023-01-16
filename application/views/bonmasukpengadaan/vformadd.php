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
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Pengirim</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" onchange="number();" required="">
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
                            <div class="input-group">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" onchange="number();" required="" value="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="pengirim" id="pengirim" class="form-control select2" required="" onchange="number();">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4" id="eks">
                            <select name="ireffeks" id="ireffeks" class="form-control select2">
                            </select>
                            <input type="hidden" id="idjenis" name="idjenis">
                        </div>
                        <div class="col-sm-8">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
                    <tr class="d-felx flex-nowrap">
                        <th class="col-1 text-center">No</th>
                        <th class="col-3">Nama Panel</th>
                        <th class="col-1">Warna</th>
                        <th class="col-1 text-right">Qty Kirim</th>
                        <th class="col-1 text-right">Qty Terima</th>
                        <th class="col-1 text-right">Qty BS</th>
                        <th class="col-2">Ket</th>
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
<script type="text/javascript">
    $(document).ready(function() {
        //$('#isj').mask('SSS-0000-0000S');
        $('.select2').select2();
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.date', null, 0);
        number();

        $('#itype').select2({
            placeholder: 'Type Makloon',
        }).change(function() {
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireffeks").val("");
            $("#ireffeks").html("");

        });

        $('#pengirim').select2({
            placeholder: 'Cari No Referensi',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/pengirim'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        pembuat: $('#ibagian').val(),
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
                        pembuat: $('#ibagian').val(),
                        pengirim: $('#pengirim').val()
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
                if (data['detail'].length > 0) {
                    $('#tabledatax').attr('hidden', false);
                    $('#jml').val(data['detail'].length);
                    $('#idjenis').val(data['detail'][0]['id_jenis_barang_keluar']);
                    var group = '';
                    for (let a = 0; a < data['detail'].length; a++) {
                        var kode = "'" + data['detail'][a]['id_product_wip'] + "'";
                        var no = a + 1;
                        var cols = "";
                        var cols1 = "";
                        if (group != data['detail'][a]['id_product_wip']) {
                            var newRow1 = $("<tr class='table-active'>");
                            cols1 += '<td colspan="2">' + data['detail'][a]['i_product_wip'] + ' - ' + data['detail'][a]['e_product_wipname'] + '</td>';
                            cols1 += '<td colspan="2">' + data['detail'][a]['e_color_name'] + '</td>';
                            cols1 += '<td><input autocomplete="off" class="form-control input-sm text-right" id="n_qty_wip' + no + '" placeholder="0" name="n_qty_wip' + no + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeypress="return hanyaAngka(event);" onkeyup="set(' + kode + ',this.value);"></td>';
                            cols1 += '<td colspan="2"></td>';
                        }
                        group = data['detail'][a]['id_product_wip'];
                        var newRow = $("<tr>");
                        cols += '<td style="text-align: center">' + no + '</td>';
                        cols += '<td><input class="form-control input-sm" readonly id="iproduct' + no + '" name="iproduct' + no + '" value="' + data['detail'][a]['i_panel'] + ' - ' + data['detail'][a]['bagian'] + '"><input hidden class="form-control" readonly id="id_document' + no + '" name="id_document' + no + '" value="' + data['detail'][a]['id'] + '"><input hidden class="form-control" readonly id="id_panel_item' + no + '" name="id_panel_item' + no + '" value="' + data['detail'][a]['id_panel'] + '"></td>';
                        cols += '<td><input type="hidden" id="icolor' + no + '" name="icolor' + no + '" value="' + data['detail'][a]['i_color'] + '"><input readonly class="form-control input-sm" id="ecolor' + no + '" name="ecolor' + no + '" value="' + data['detail'][a]['e_color_name'] + '"></td>';
                        cols += '<td><input class="form-control input-sm text-right" readonly id="sisa' + no + '" name="sisa' + no + '" value="' + data['detail'][a]['n_quantity'] + '"></td>';
                        cols += '<td><input class="form-control input-sm text-right ' + data['detail'][a]['id_product_wip'] + '" id="nquantity' + no + '" placeholder="0" name="nquantity' + no + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo(' + no + ');"></td>';
                        cols += '<td><input class="form-control input-sm text-right" autocomplete="off" id="nquantity_bs' + no + '" placeholder="0" name="nquantity_bs' + no + '" readonly value="' + data['detail'][a]['n_quantity'] + '"></td>';
                        cols += '<td><input class="form-control input-sm" placeholder="Keterangan .." id="eremark' + no + '" name="eremark' + no + '" value=""></td>';
                        newRow.append(cols);
                        newRow1.append(cols1);
                        $("#tabledatax").append(newRow1);
                        $("#tabledatax").append(newRow);
                    }
                    var $table = $('#tabledatax');

                    function buildTable(elm) {
                        elm.bootstrapTable('destroy').bootstrapTable({
                            height: 400,
                            // columns          : columns,
                            // data             : data,
                            search: false,
                            showColumns: true,
                            // showToggle       : true,
                            // clickToSelect    : true,
                            fixedColumns: true,
                            // fixedNumber: 2,
                            // fixedRightNumber: 1
                        })
                    }

                    $(function() {
                        buildTable($table)
                    })
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
            swal('Qty terima tidak boleh lebih dari qty Kirim!!!');
            $('#nquantity' + i).val($('#sisa' + i).val());
        }
        // console.log(parseFloat($('#nquantity' + i).val()));
        if (!isNaN(parseFloat($('#nquantity' + i).val()))) {
            $('#nquantity_bs' + i).val(parseFloat($('#sisa' + i).val()) - parseFloat($('#nquantity' + i).val()));
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);

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

    function set(kode, value) {
        /* alert(kode);
        alert(value); */
        $('.' + kode).val(value);
        for (let i = 1; i <= $('#jml').val(); i++) {
            ceksaldo(i);
        }
    }
</script>