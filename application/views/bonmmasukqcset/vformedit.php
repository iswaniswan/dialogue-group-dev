<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main');" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
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
                                <input type="hidden" name="idocumentold" id="isjold" value="<?= $data->i_document; ?>">
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
                            <select name="pengirim" id="pengirim" class="form-control select2" required="" onchange="number();">
                                <option value="<?= $data->i_pengirim; ?>">
                                    <?= $data->e_pengirim_name; ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4" id="eks">
                            <select name="ireffeks" id="ireffeks" class="form-control select2">
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_document_reff . ' | ' . $data->d_document_reff . ' | ' . $data->e_jenis_name; ?></option>
                            </select>
                            <input type="hidden" id="idjenis" name="idjenis" value="<?= $data->id_jenis_barang_keluar; ?>">
                        </div>
                        <div class="col-sm-8">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
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
                        <tr class="flex-nowrap">
                            <th class="col-1 text-center">No</th>
                            <th class="col-3">Nama Panel</th>
                            <th class="col-1">Warna</th>
                            <th class="col-1 text-right">Qty Penyusun</th>
                            <th class="col-1 text-right">Qty Kirim</th>
                            <th class="col-1 text-right">Qty Terima</th>
                            <th class="col-1 text-right">Qty BS</th>
                            <th class="col-2">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $group = '';
                        if ($datadetail) {
                            foreach ($datadetail as $key) {
                                $kode = $key->id_product_wip;
                                $i++;
                                if ($group != $key->id_product_wip) { ?>
                                    <tr>
                                        <td><i class="fa fa-check-square-o fa-lg text-success" aria-hidden="true"></i></td>
                                        <td colspan="4"><input class="form-control input-sm" readonly value="<?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>"></td>
                                        <td><input autocomplete="off" class="form-control input-sm text-right" id="n_qty_wip" placeholder="0" name="n_qty_wip" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}" value="0" onkeypress="return hanyaAngka(event);" onkeyup="set('<?= $kode; ?>',this.value);"></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                        <td colspan="5"><input class="form-control input-sm" readonly value="<?= $key->i_material . ' - ' . $key->e_material_name; ?>"></td>
                                        <td colspan="2"></td>
                                    </tr>
                                <?php }
                                $group = $key->id_product_wip;

                                ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" readonly id="iproduct<?= $i; ?>" name="iproduct<?= $i; ?>" value="<?= $key->i_panel . ' - ' . $key->bagian; ?>">
                                        <input hidden class="form-control input-sm" readonly id="id_document<?= $i; ?>" name="id_document<?= $i; ?>" value="<?= $data->id_document_reff; ?>">
                                        <input hidden class="form-control input-sm" readonly id="id_panel_item<?= $i; ?>" name="id_panel_item<?= $i; ?>" value="<?= $key->id_panel; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm" id="ecolor<?= $i; ?>" name="ecolor<?= $i; ?>" value="<?= $key->e_color_name; ?>">
                                    </td>
                                    <td><input class="form-control input-sm text-right" readonly id="qty_penyusun<?= $i; ?>" name="qty_penyusun<?= $i; ?>" value="<?= $key->n_quantity_penyusun; ?>"></td>
                                    <td><input class="form-control input-sm text-right" readonly id="sisa<?= $i; ?>" name="sisa<?= $i; ?>" value="<?= $key->keluar; ?>"></td>
                                    <td><input class="form-control input-sm text-right <?= $key->id_product_wip; ?>" id="nquantity<?= $i; ?>" placeholder="0" name="nquantity<?= $i; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->masuk; ?>" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo(<?= $i; ?>);"></td>
                                    <td><input class="form-control input-sm text-right" id="nquantity_bs<?= $i; ?>" placeholder="0" name="nquantity_bs<?= $i; ?>" readonly value="<?= $key->keluar - $key->masuk; ?>"></td>
                                    <td><input class="form-control input-sm" id="eremark<?= $i; ?>" name="eremark<?= $i; ?>" value="<?= $key->e_remark; ?>"></td>
                                </tr>
                        <?php }
                        } ?>
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
        var $table = $('#tabledatax');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : data,
                search: false,
                showColumns: false,
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

        $('#isj').mask('SSS-0000-0000S');
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
                            cols1 += '<td colspan="3">' + data['detail'][a]['e_color_name'] + '</td>';
                            cols1 += '<td><input autocomplete="off" class="form-control input-sm text-right" id="n_qty_wip' + no + '" placeholder="0" name="n_qty_wip' + no + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeypress="return hanyaAngka(event);" onkeyup="set(' + kode + ',this.value);"></td>';
                            cols1 += '<td colspan="3"></td>';
                        }
                        group = data['detail'][a]['id_product_wip'];
                        var newRow = $("<tr>");
                        cols += '<td style="text-align: center">' + no + '</td>';
                        cols += '<td><input class="form-control input-sm" readonly id="iproduct' + no + '" name="iproduct' + no + '" value="' + data['detail'][a]['i_panel'] + ' - ' + data['detail'][a]['bagian'] + '"><input hidden class="form-control" readonly id="id_document' + no + '" name="id_document' + no + '" value="' + data['detail'][a]['id'] + '"><input hidden class="form-control" readonly id="id_panel_item' + no + '" name="id_panel_item' + no + '" value="' + data['detail'][a]['id_panel'] + '"></td>';
                        cols += '<td><input type="hidden" id="icolor' + no + '" name="icolor' + no + '" value="' + data['detail'][a]['i_color'] + '"><input readonly class="form-control input-sm" id="ecolor' + no + '" name="ecolor' + no + '" value="' + data['detail'][a]['e_color_name'] + '"></td>';
                        cols += '<td><input class="form-control input-sm text-right" readonly id="qty_penyusun' + no + '" name="qty_penyusun' + no + '" value="' + data['detail'][a]['n_quantity_penyusun'] + '"></td>';
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

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });


    /* function ceksaldo(i) {
        if (parseFloat($('#nquantity' + i).val()) > parseFloat($('#sisa' + i).val())) {
            swal('Qty terima tidak boleh lebih dari qty Kirim!!!');
            $('#nquantity' + i).val($('#sisa' + i).val());
        }
    } */
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
        if (($('#ibagian').val() == $('#ibagianold').val()) && ($('#itype').val() == $('#itypeold').val())) {
            $('#isj').val($('#isjold').val());
        } else {
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

    }

    $("#isj").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'kodeold': $('#isjold').val(),
                'ibagian': $('#ibagian').val(),
                'itype': $('#itype').val(),
                'itypeold': $('#itypeold').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkodeedit'); ?>',
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

    function getRawQtyTerima(qtyPenyusun, qtyTerima) {
        return qtyPenyusun*qtyTerima;
    };

    function set(kode, value) {
        console.log(kode, value);
        /* alert(kode);
        alert(value); */        

        let elementTerima = $('.' + kode);

        elementTerima.each(function() {
            let penyusun = $(this).closest('tr').find('input[name*="qty_penyusun"]').val();
            let qty = getRawQtyTerima(penyusun, value);
            $(this).val(qty);
        });

        for (let i = 1; i <= $('#jml').val(); i++) {
            ceksaldo(i);
        }
    }
</script>