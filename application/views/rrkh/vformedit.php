<style>
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
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-2">Tanggal Dokumen</label>
                        <label class="col-sm-2">Area</label>
                        <label class="col-sm-2">Salesman</label>

                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required=""
                                onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                <option value="<?= $row->i_bagian;?>" <?= ($row->i_bagian == $data->i_bagian) ? 'selected' : '' ?>>
                                    <?= $row->e_bagian_name;?>
                                </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" name="dok_rrkh" value="<?= $data->i_document ?>" id="dok_rrkh" readonly="" autocomplete="off"
                                    onkeyup="gede(this);" maxlength="17"
                                    class="form-control input-sm" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="drrkh" name="drrkh" class="form-control input-sm date"
                                onchange="tanggal(this.value);" required="" readonly
                                value="<?= date('d-m-Y', strtotime($data->d_document)) ?>">
                        </div>
                        <div class="col-sm-2">
                            <select name="kode_area" id="kode_area" class="form-control select2" required=""
                                onchange="number();">
                                <?php if ($kodearea) {
                                    foreach ($kodearea as $row):?>
                                <option value="<?= $row->i_area;?>" <?= ($row->i_area == $data->i_area) ? 'selected' : '' ?>>
                                    <?= $row->e_area;?>
                                </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="kode_salesman" id="kode_salesman" class="form-control select2" required=""
                                onchange="number();">
                                <?php if ($kodesalesman) {
                                    foreach ($kodesalesman as $row):?>
                                <option value="<?= $row->i_sales;?>" <?= ($row->i_sales == $data->i_sales) ? 'selected' : '' ?>>
                                    <?= $row->e_sales;?>
                                </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2">Salesman Upline</label>
                        <label class="col-sm-4">Keterangan</label>
                        <label class="col-sm-6"></label>
                        <div class="col-sm-2">
                            <input type="hidden" id="id_salesman_upline" name="id_salesman_upline" class="form-control input-sm"
                                required="" readonly
                                value="<?= $data->id_salesman_upline ?>">
                            <input type="hidden" id="i_sales_upline" name="i_sales_upline" class="form-control input-sm"
                                required="" readonly
                                value="<?= $data->i_sales_upline ?>">
                            <input type="text" id="e_sales_upline" name="e_sales_upline" class="form-control input-sm"
                                required="" readonly
                                value="<?= $data->e_sales_upline ?>">
                        </div>
                        <div class="col-sm-4">
                            <textarea class="form-control input-sm" name="eremark" id="eremark" placeholder="Keterangan"><?= $data->e_remark ?></textarea>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2"><i
                                    class="fa fa-save mr-2"></i>Update</button>
                            <?php } ?>
                            <?php if($data->i_status == '2'){?>
                            <button type="button" id="addrow" hidden="true" class="btn btn-rounded btn-info btn-sm mr-2"><i
                                    class="fa fa-plus mr-2"></i>Item</button>
                            <?php }else{?>
                            <button type="button" id="addrow" class="btn btn-rounded btn-info btn-sm mr-2"><i
                                class="fa fa-plus mr-2"></i>Item</button>
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2"
                                onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i
                                    class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <?php if ($data->i_status == '1' || $data->i_status == '3') {?>
                                <button type="button" id="send"
                                    class="btn btn-primary btn-rounded btn-sm mr-2"><i
                                        class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            <?php } else { ?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
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
        <h3 class="box-title m-b-0">Detail Customer</h3>
        <!-- <div class="m-b-0">
            <div class="form-group row">
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>Item</button>
                </div>
            </div>
        </div> -->
    </div>
    <div class="row mb-4">
        <div class="col-sm-2">
            <input type="text" id="dfrom" name="dfrom" class="form-control input-sm date ml-2" readonly placeholder="Pilih Tanggal Item" value="">
        </div>
        <div class="col-sm-2">
            <button type="button" onclick="generateitem()" class="btn btn-info btn-rounded btn-sm mr-2 cari"> <i class="fa fa-gear fa-lg mr-2"></i>Generate</button>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 7%;">Id Cust.</th>
                        <th class="text-center" style="width: 20%;">Nama Cust.</th>
                        <th class="text-center" style="width: 10%;">Waktu</th>
                        <th class="text-center" style="width: 10%;">City</th>
                        <th class="text-center" style="width: 10%;">Rencana</th>
                        <th class="text-center" style="width: 5%;">Real</th>
                        <th class="text-center" style="width: 5%;">Bukti</th>
                        <th class="text-center" style="width: 25%;">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($detail) {
                    $counter = 0;
                    $count = 0;
                    foreach($detail as $d) {
                        $counter++;
                        $count++;
                        ?>
                        <tr>
                            <td style="text-align: center;"><spanx id="snum<?= $counter ?>"><?= $count ?></spanx></td>
                            <td><input type="hidden" readonly id="idcust<?= $counter ?>" name="idcust<?= $counter ?>" value="<?= $d->id_customer ?>" class="form-control input-sm"><input type="text" readonly id="icust<?= $counter ?>" name="icust<?= $counter ?>" value="<?= $d->i_customer ?>" class="form-control input-sm"></td>
                            <td><select data-urut="<?= $counter ?>" id="ecust<?= $counter ?>" name="ecust<?= $counter ?>" onchange="getcustomer(<?= $counter ?>);" class="form-control input-sm">
                                <option value="<?= $d->id_customer ?>" selected><?= $d->i_customer ?> - <?= $d->e_customer_name ?> - <?= $d->e_area ?></option>
                            </select></td>
                            <td><input type="text" readonly id="waktu<?= $counter ?>" name="waktu<?= $counter ?>" value="<?= formatdmY($d->waktu) ?>" placeholder="klik untuk pilih" class="form-control input-sm dates" onchange="tanggal(this.value); number();" required="" readonly></td>
                            <td><input type="text" readonly id="idcity<?= $counter ?>" name="idcity<?= $counter ?>" value="<?= $d->e_city_name ?>" class="form-control text-right input-sm inputitem" autocomplete="off"></td>
                            <td><select data-urut="<?= $counter ?>" id="idrencana<?= $counter ?>" name="idrencana<?= $counter ?>" class="form-control input-sm">
                                <option value="<?= $d->id_rencana ?>" selected><?= $d->nama_rencana ?></option>
                            </select></td>
                            <td><input type="checkbox" id="real<?= $counter ?>" <?= ($d->f_real == 't') ? 'checked' : '' ?> name="real<?= $counter ?>"  /></td>
                            <td><input type="checkbox" id="bukti<?= $counter ?>" <?= ($d->f_bukti == 't') ? 'checked' : '' ?> name="bukti<?= $counter ?>" /></td>
                            <td><input type="text" id="e_remark<?= $counter ?>" name="e_remark<?= $counter ?>" value="<?= $d->keterangan ?>" placeholder="Keterangan Detail" class="form-control input-sm"/></td>
                            <td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                        </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="<?= $count ?>">
</from>
<script>
$(document).ready(function() {
    $('.select2').select2();
    showCalendar('.date');
    for(let i = 1; i<=$('#jml').val();i++) {
        $('#ecust' + i).select2({
            placeholder: 'Cari Berdasarkan Nama Customer',
            // templateSelection: formatSelection,
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/datacustomer'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        iarea: $('#kode_area').val(),
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

        $('#idrencana' + i).select2({
            placeholder: 'Cari Berdasarkan Nama',
            // templateSelection: formatSelection,
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/datarencana'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }
    $('#kode_salesman').change(function() {
        $.ajax({
            type: 'post',
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/getsalesmanupline') ?>',
            dataType: 'json',
            success: (data) => {
                if(data) {
                    $('#id_salesman_upline').val(data.id_upline);
                    $('#i_sales_upline').val(data.i_sales_upline);
                    $('#e_sales_upline').val(data.e_sales_upline);
                } else {
                    $('#id_salesman_upline').val('');
                    $('#i_sales_upline').val('');
                    $('#e_sales_upline').val('');
                }
            }
        })
    });
});

$("#dok_rrkh").keyup(function() {
    $.ajax({
        type: "post",
        data: {
            'kode': $(this).val(),
            'ibagian': $('#ibagian').val(),
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
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

function number() {
    $.ajax({
        type: "post",
        data: {
            'tgl': $('#drrkh').val(),
            'ibagian': $('#ibagian').val(),
        },
        url: '<?= base_url($folder.'/cform/number'); ?>',
        dataType: "json",
        success: function(data) {
            $('#dok_rrkh').val(data);
        },
        error: function() {
            swal('Error :)');
        }
    });
}

// $('#ceklis').click(function(event) {
//     console.log('teing');
//     if ($('#ceklis').is(':checked')) {
//         $("#dok_rrkh").attr("readonly", false);
//     } else {
//         $("#dok_rrkh").attr("readonly", true);
//         $("#ada").attr("hidden", true);
//         number();
//     }
// });

$('#send').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '2', '<?= $dfrom."','".$dto;?>');
});

$('#cancel').click(function(event) {
    statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    // $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#send").attr("hidden", false);
});

var counter = 0;

$("#addrow").on("click", function() {
    var counter = $('#jml').val();
    var counterx = counter - 1;
    counter++;
    counterx++;
    
    $("#tabledatax").attr("hidden", false);
    var icust = $('#icust' + counterx).val();
    count = $('#tabledatax tr').length;

    if ((icust == '' || icust == null) && (count > 1)) {
        swal('Isi dulu yang masih kosong!!');
        counter = counter - 1;
        counterx = counterx - 1;
        return false;
    }

    $('#jml').val(counter);
    var newRow = $("<tr>");
    var cols = "";

    cols += '<td style="text-align: center;"><spanx id="snum' + counter + '">' + count + '</spanx></td>';
    // cols += '<td><select data-urut="'+i+'" id="idscheduleitem'+i+ '" class="form-control input-sm" name="idscheduleitem[]"></td>';
    cols += '<td><input type="hidden" readonly id="idcust' + counter + '" name="idcust' + counter +
        '" class="form-control input-sm"><input type="text" readonly id="icust' + counter + '" name="icust' +
        counter + '" class="form-control input-sm"></td>';
    cols += '<td><select data-urut="' + counter + '" id="ecust' + counter + '" name="ecust' + counter +
        '" onchange="getcustomer(' + counter + ');" class="form-control input-sm"></td>';
    cols += '<td><input type="text" readonly id="waktu' + counter + '" name="waktu' + counter +
        '" placeholder="klik untuk pilih" class="form-control input-sm dates" onchange="tanggal(this.value); number();" required="" readonly value="<?= date("d-m-Y"); ?>"></td>';
    // cols += '<td><input type="text" readonly id="e_wip_name'+i+'" class="form-control input-sm" name="e_wip_name[]"></td>';
    // cols += '<td><input type="text" readonly id="area'+i+'" name="area[]" class="form-control input-sm"></td>';
    cols += '<td><input type="text" readonly id="idcity' + counter + '" name="idcity' + counter +
        '" class="form-control text-right input-sm inputitem" autocomplete="off"></td>';
    cols += '<td><select data-urut="' + counter + '" id="idrencana' + counter + '" name="idrencana' + counter +
        '" class="form-control input-sm"></td>';
    // cols += '<td><input type="text" id="idcity'+i+'" name="idcity[]" class="form-control text-right input-sm inputitem" autocomplete="off"  onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);cek('+i+')"></td>';
    cols += '<td><input type="checkbox" id="real' + counter + '" name="real' + counter + '"  /></td>';
    cols += '<td><input type="checkbox" id="bukti' + counter + '" name="bukti' + counter + '" /></td>';
    cols += '<td><input type="text" id="e_remark' + counter + '" name="e_remark' + counter +
        '" placeholder="Keterangan Detail" class="form-control input-sm"/></td>';
    cols +=
        '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
    newRow.append(cols);
    $("#tabledatax").append(newRow);


    showCalendar('.dates');

    $('#ecust' + counter).select2({
        placeholder: 'Cari Berdasarkan Nama Customer',
        templateSelection: formatSelection,
        allowClear: true,
        width: "100%",
        ajax: {
            url: '<?= base_url($folder.'/cform/datacustomer'); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    iarea: $('#kode_area').val(),
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

    $('#idrencana' + counter).select2({
        placeholder: 'Cari Berdasarkan Nama',
        templateSelection: formatSelection,
        allowClear: true,
        width: "100%",
        ajax: {
            url: '<?= base_url($folder.'/cform/datarencana'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

});

function formatSelection(val) {
    return val.name;
}

function getcustomer(id) {
    ada = false;
    var a = $('#ecust' + id).val();
    var x = $('#jml').val();
    for (i = 1; i <= x; i++) {
        if ((a == $('#ecust' + i).val()) && (i != x)) {
            swal("Sudah ada !!!!!");
            ada = true;
            break;
        } else {
            ada = false;
        }
    }

    if (!ada) {
        var ecust = $('#ecust' + id).val();
        $.ajax({
            type: "post",
            data: {
                'ecust': ecust
            },
            url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
            dataType: "json",
            success: function(data) {
                $('#idcust' + id).val(data[0].id_customer);
                $('#icust' + id).val(data[0].i_customer);
                // $('#idcolorproduct'+id).val(data[0].id_color);
                $('#idcity' + id).val(data[0].e_city_name);
                // $('#nquantity'+id).focus();
            },
            error: function() {
                swal('Error :)');
            }
        });
    } else {
        $('#idcust' + id).html('');
        $('#icust' + id).html('');
        $('#eproduct' + id).html('');
        $('#idcity' + id).html('');
        // $('#ecolorproduct'+id).html('');

        $('#idcust' + id).val('');
        $('#icust' + id).val('');
        $('#eproduct' + id).val('');
        $('#idcity' + id).val('');
        // $('#ecolorproduct'+id).val('');
    }
}

$("#tabledatax").on("click", ".ibtnDel", function(event) {
    $(this).closest("tr").remove();

    $('#jml').val(counter);
    del();
});

function del() {
    obj = $('#tabledatax tr:visible').find('spanx');
    $.each(obj, function(key, value) {
        id = value.id;
        $('#' + id).html(key + 1);
    });
}

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


$("#submit").click(function(event) {
    ada = false;
    if ($('#jml').val() == 0) {
        swal('Isi item minimal 1!');
        return false;
    } else {
        $("#tabledatax tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    swal('Data customer dan Data rencana tidak boleh kosong!');
                    ada = true;
                }
            });
            // $(this).find("td .inputitem").each(function() {
            //     if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
            //         swal('Quantity Tidak Boleh Kosong Atau 0!');
            //         ada = true;
            //     }
            // });
        });
        if (!ada) {
            return true;
        } else {
            return false;
        }
    }
})

// function cek(i) {
//     if (parseFloat($('#nquantity_kirim' + i).val()) > parseFloat($('#nquantity' + i).val())) {
//         swal('Maaf Qty Kirim = ' + $('#nquantity_kirim' + i).val() + ', tidak boleh lebih dari Qty Permintaan = ' + $('#nquantity' + i).val());
//         $('#nquantity_kirim' + i).val($('#nquantity' + i).val());
//     }
// }
function tanggal(d) {
    $('#dbp').val(maxDate(d));
}

function generateitem() {
    let date = $('#dfrom').val();
    let area = $('#kode_area').val();
    let salesman = $('#kode_salesman').val();
    $.ajax({
        type: "post",
        data: {
            date: date,
            area: area,
            salesman: salesman,
        },
        url: '<?= base_url($folder.'/cform/getcustomergenerate'); ?>',
        dataType: "json",
        success: function(data) {
            if(data.length > 0) {
                // $("#tabledatax").attr("hidden", false);
                count = $('#tabledatax tr').length;
                var counter = $('#jml').val();
                data.map((d, i) => {
                    counter++;
                    $('#tabledatax tbody').append(`
                        <tr>
                            <td style="text-align: center;"><spanx id="snum${counter}">${count}</spanx></td>
                            <td><input type="hidden" readonly id="idcust${counter}" name="idcust${counter}" class="form-control input-sm"><input type="text" readonly id="icust${counter}" name="icust${counter}" value="${d.i_customer}" class="form-control input-sm"></td>
                            <td><select data-urut="${counter}" id="ecust${counter}" name="ecust${counter}" onchange="getcustomer(${counter});" class="form-control input-sm">
                                <option value="${d.id_customer}" selected>${d.i_customer} - ${d.e_customer_name} - ${d.e_area}</option>
                            </select></td>
                            <td><input type="text" readonly id="waktu${counter}" name="waktu${counter}" value="${date}" placeholder="klik untuk pilih" class="form-control input-sm dates" onchange="tanggal(this.value); number();" required="" readonly></td>
                            <td><input type="text" readonly id="idcity${counter}" name="idcity${counter}" value="${d.e_city_name}" class="form-control text-right input-sm inputitem" autocomplete="off"></td>
                            <td><select data-urut="${counter}" id="idrencana${counter}" name="idrencana${counter}" class="form-control input-sm">
                            <option value="${d.id_rencana}" selected>${d.nama_rencana}</option>
                            </select></td>
                            <td><input type="checkbox" id="real${counter}" name="real${counter}"  /></td>
                            <td><input type="checkbox" id="bukti${counter}" name="bukti${counter}" /></td>
                            <td><input type="text" id="e_remark${counter}" name="e_remark${counter}" placeholder="Keterangan Detail" class="form-control input-sm"/></td>
                            <td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                        </tr>
                    `);
                    $('#ecust' + counter).select2({
                        placeholder: 'Cari Berdasarkan Nama Customer',
                        // templateSelection: formatSelection,
                        allowClear: true,
                        width: "100%",
                        ajax: {
                            url: '<?= base_url($folder.'/cform/datacustomer'); ?>',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                var query = {
                                    q: params.term,
                                    iarea: $('#kode_area').val(),
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
    
                    $('#idrencana' + counter).select2({
                        placeholder: 'Cari Berdasarkan Nama',
                        // templateSelection: formatSelection,
                        allowClear: true,
                        width: "100%",
                        ajax: {
                            url: '<?= base_url($folder.'/cform/datarencana'); ?>',
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });
                    count++;
                    $('#jml').val(counter);
                });
                showCalendar('.dates');
            } else {
                swal('Tidak ada data!');
            }
        },
        error: function() {
            swal('Error :)');
        }
    });
}
</script>