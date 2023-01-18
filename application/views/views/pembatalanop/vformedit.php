<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-2">Tanggal Dokumen</label>
                        <label class="col-sm-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required=""
                                onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?>
                                    selected <?php } ?>>
                                    <?= $row->e_bagian_name;?>
                                </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="ibpold" id="ibpold" value="<?= $data->i_document;?>">
                                <input type="text" name="ibp" id="ibp" readonly="" autocomplete="off"
                                    onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15"
                                    class="form-control input-sm" value="<?= $data->i_document;?>"
                                    aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date"
                                onchange="number();" required="" readonly
                                value="<?= date('d-m-Y', strtotime($data->d_document)); ?>">
                        </div>
                        <div class="col-sm-4">
                            <textarea class="form-control input-sm" name="remark"
                                placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i
                                    class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                            <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i
                                    class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                            <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i
                                    class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                            <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i
                                    class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 17%;">No OP</th>
                        <th class="text-center" style="width: 40%;">Barang</th>
                        <th class="text-center" style="width: 10%;">Jml</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                    <tr>
                        <td class="text-center">
                            <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                        </td>
                        <td><select data-i="<?=$i;?>" id="iop<?=$i;?>" class="form-control input-sm" name="iop[]">
                                <option value="<?= $row->id_op;?>"><?= $row->i_op;?></option>
                            </select></td>
                        <td><select data-z="<?=$i;?>" id="imaterial<?=$i;?>" class="form-control input-sm"
                                name="imaterial[]" onchange="getmaterial(<?=$i;?>);">
                                <option value="<?= $row->i_product;?>">
                                    <?= $row->i_product.' - '.$row->e_material_name;?></option>
                            </select></td>
                        <td><input type="text" id="nquantity<?=$i;?>" class="form-control text-right input-sm inputitem"
                                autocomplete="off" name="nquantity[]" onblur="if(this.value=='' ){this.value='0' ;}"
                                onfocus="if(this.value=='0'){this.value='' ;}" value="<?= $row->n_quantity;?>"
                                onkeyup="angkahungkul(this);">
                        </td>
                        <td>
                            <input type="text" id="eremark<?=$i;?>" class="form-control input-sm" name="eremark[]"
                                value="<?= $row->e_remark;?>" />
                            <input type="hidden" id="id_pp<?=$i;?>" name="idpp[]" value="<?= $row->id_pp;?>" />
                        </td>
                        <td>
                            <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i
                                    class="ti-close"></i></button>
                        </td>
                    </tr>
                    <?php } 
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="<?= $i;?>">
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('#ibp').mask('SS-0000-000000S');
    $('.select2').select2();
    showCalendar('.date');
    for (var i = 1; i <= $('#jml').val(); i++) {
        $('#iop' + i).select2({
            placeholder: 'Cari No OP',
            allowClear: true,
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/getop/'); ?>',
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
        }).change(function(event) {
            let z = $(this).data('i');
            $('#imaterial' + z).val('');
            $('#imaterial' + z).html('');
        });

        $('#imaterial' + i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    let z = $(this).data('z');
                    var query = {
                        q: params.term,
                        iop: $('#iop' + z).val(),
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
    }
});

$("#ibp").keyup(function() {
    $.ajax({
        type: "post",
        data: {
            'kode': $(this).val(),
            'ibagian': $('#ibagian').val(),
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function(data) {
            if (data == 1 && ($('#ibp').val() != $('#ibpold').val())) {
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

function number() {
    if (($('#ibagian').val() == $('#ibagianold').val())) {
        $('#ibp').val($('#ibpold').val());
    } else {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#ibp').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }
}

$('#ceklis').click(function(event) {
    if ($('#ceklis').is(':checked')) {
        $("#ibp").attr("readonly", false);
    } else {
        $("#ibp").attr("readonly", true);
        $("#ada").attr("hidden", true);
        $('#ibp').val($('#ibpold').val());
    }
});

$('#ibagian').change(function(event) {
    $('#ikategori').val('');
    $('#ikategori').html('');
    $('#ijenis').val('');
    $('#ijenis').html('');
});

$('#send').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '2', '<?= $dfrom."','".$dto;?>');
});

$('#cancel').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '1', '<?= $dfrom."','".$dto;?>');
});

$('#hapus').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '5', '<?= $dfrom."','".$dto;?>');
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#send").attr("hidden", false);
});

var i = $('#jml').val();
$("#addrow").on("click", function() {
    i++;
    $("#jml").val(i);
    var no = $('#tabledatax tr').length;
    var newRow = $("<tr>");
    var cols = "";
    cols += '<td style="text-align: center;"><spanx id="snum' + i + '">' + no + '</spanx></td>';
    cols += '<td><select data-i="' + i + '" id="iop' + i + '" class="form-control input-sm" name="iop[]"></td>';
    cols += '<td><select data-z="' + i + '" id="imaterial' + i +
        '" class="form-control input-sm" name="imaterial[]" onchange="getmaterial(' + i + ');"></td>';
    /* cols += '<td><input type="hidden" id="isatuan'+i+ '" name="isatuan[]"/><input type="text" readonly id="esatuan'+i+'" class="form-control input-sm" name="esatuan[]"></td>'; */
    cols += '<td><input type="text" id="nquantity' + i +
        '" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
    cols += '<td><input type="text" id="eremark' + i +
        '" class="form-control input-sm" name="eremark[]"/><input type="hidden" id="id_pp' + i +
        '" name="idpp[]"/></td>';
    cols +=
        '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
    newRow.append(cols);
    $("#tabledatax").append(newRow);
    $('#iop' + i).select2({
        placeholder: 'Cari No OP',
        allowClear: true,
        type: "POST",
        ajax: {
            url: '<?= base_url($folder.'/cform/getop/'); ?>',
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
    }).change(function(event) {
        let z = $(this).data('i');
        $('#imaterial' + z).val('');
        $('#imaterial' + z).html('');
    });

    $('#imaterial' + i).select2({
        placeholder: 'Cari Kode / Nama Material',
        allowClear: true,
        type: "POST",
        ajax: {
            url: '<?= base_url($folder.'/cform/material/'); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                let z = $(this).data('z');
                var query = {
                    q: params.term,
                    iop: $('#iop' + z).val(),
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
});

$("#tabledatax").on("click", ".ibtnDel", function(event) {
    $(this).closest("tr").remove();

    $('#jml').val(i);
    del();
});

function del() {
    obj = $('#tabledatax tr:visible').find('spanx');
    $.each(obj, function(key, value) {
        id = value.id;
        $('#' + id).html(key + 1);
    });
}

function getmaterial(id) {
    $.ajax({
        type: "post",
        data: {
            'iop': $('#iop' + id).val(),
            'imaterial': $('#imaterial' + id).val(),
        },
        url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
        dataType: "json",
        success: function(data) {
            ada = false;
            for (var i = 1; i <= $('#jml').val(); i++) {
                if (($('#iop' + id).val() == $('#iop' + i).val()) && ($('#imaterial' + id).val() == $(
                        '#imaterial' + i).val()) && (i != id)) {
                    swal("kode : " + $('#imaterial' + id).val() + " sudah ada !!!!!");
                    ada = true;
                    break;
                } else {
                    ada = false;
                }
            }
            if (!ada) {
                $('#nquantity' + id).val(data[0].n_sisa);
                $('#id_pp' + id).val(data[0].id_pp);
                $('#nquantity' + id).focus();
            } else {
                $('#imaterial' + id).html('');
                $('#imaterial' + id).val('');
                $('#nquantity' + id).val('');
                $('#id_pp' + id).val('');
            }
        },
        error: function() {
            swal('Ada kesalahan :(');
        }
    });
}

$("#submit").click(function(event) {
    ada = false;
    if ($('#jml').val() == 0) {
        swal('Isi item minimal 1!');
        return false;
    } else {
        $("#tabledatax tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    swal('No OP atau Kode barang tidak boleh kosong!');
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
})
</script>