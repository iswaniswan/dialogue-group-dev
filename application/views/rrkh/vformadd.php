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
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                                <option value="<?= $row->i_bagian;?>">
                                    <?= $row->e_bagian_name;?>
                                </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="dok_rrkh" id="dok_rrkh" readonly="" autocomplete="off"
                                    onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17"
                                    class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="drrkh" name="drrkh" class="form-control input-sm date"
                                onchange="tanggal(this.value); number();" required="" readonly
                                value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <select name="kode_area" id="kode_area" class="form-control select2" required=""
                                onchange="number();">
                                <?php if ($kodearea) {
                                    foreach ($kodearea as $row):?>
                                <option value="<?= $row->i_area;?>">
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
                                <option value="<?= $row->i_sales;?>">
                                    <?= $row->e_sales;?>
                                </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2"><i
                                    class="fa fa-save mr-2"></i>Simpan</button>
                            <button type="button" id="addrow" class="btn btn-rounded btn-info btn-sm mr-2"><i
                                    class="fa fa-plus mr-2"></i>Item</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2"
                                onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i
                                    class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <button type="button" id="send" hidden="true"
                                class="btn btn-primary btn-rounded btn-sm mr-2"><i
                                    class="fa fa-paper-plane-o mr-2"></i>Send</button>
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
                        <th class="text-center" style="width: 10%;">Area Cust.</th>
                        <th class="text-center" style="width: 10%;">Rencana</th>
                        <th class="text-center" style="width: 5%;">Real</th>
                        <th class="text-center" style="width: 5%;">Bukti</th>
                        <th class="text-center" style="width: 25%;">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('#dok_rrkh').mask('SSSS-0000-000000S');
    $('.select2').select2();
    showCalendar('.date');
    number();
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

$('#ceklis').click(function(event) {
    if ($('#ceklis').is(':checked')) {
        $("#dok_rrkh").attr("readonly", false);
    } else {
        $("#dok_rrkh").attr("readonly", true);
        $("#ada").attr("hidden", true);
        number();
    }
});

$('#send').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '2', '<?= $dfrom."','".$dto;?>');
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

var counter = $('#jml').val();
var counterx = counter - 1;
$("#addrow").on("click", function() {
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
    cols += '<td><input type="text" readonly id="idarea' + counter + '" name="idarea' + counter +
        '" class="form-control text-right input-sm inputitem" autocomplete="off"></td>';
    cols += '<td><select data-urut="' + counter + '" id="idrencana' + counter + '" name="idrencana' + counter +
        '" class="form-control input-sm"></td>';
    // cols += '<td><input type="text" id="idarea'+i+'" name="idarea[]" class="form-control text-right input-sm inputitem" autocomplete="off"  onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);cek('+i+')"></td>';
    cols += '<td><input type="checkbox" id="real' + counter + '" name="real' + counter + '"  /></td>';
    cols += '<td><input type="checkbox" id="bukti' + counter + '" name="bukti' + counter + '" /></td>';
    cols += '<td><input type="text" id="eremark' + counter + '" name="eremark' + counter +
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
                $('#idarea' + id).val(data[0].area);
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
        $('#idarea' + id).html('');
        // $('#ecolorproduct'+id).html('');

        $('#idcust' + id).val('');
        $('#icust' + id).val('');
        $('#eproduct' + id).val('');
        $('#idarea' + id).val('');
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
</script>