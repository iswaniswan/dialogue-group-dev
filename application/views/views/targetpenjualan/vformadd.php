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
<?php
$dfrom = '';
$dto   = '';
?>
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
                        <label class="col-sm-3">Bagian</label>
                        <label class="col-sm-3">Bulan</label>
                        <label class="col-sm-3">Tahun</label>
                        <label class="col-sm-3">Area</label>

                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" 
                                >
                                <option>Pilih Bagian</option>
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
                            <input type="text" id="bulan" name="bulan" class="form-control input-sm date"
                                onchange="tanggal(this.value); " required="" readonly
                                >
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="tahun" name="tahun" class="form-control input-sm date"
                                onchange="tanggal(this.value); " required="" readonly
                                >
                        </div>
                        <div class="col-sm-3">
                            <select name="kode_area" id="kode_area" class="form-control input-sm" required="">
                                <option>Pilih Area</option>
                                <?php if ($kodearea) {
                                    foreach ($kodearea as $row):?>
                                <option value="<?= $row->id;?>">
                                    <?= $row->e_area;?>
                                </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Total</label>
                        <div class="col-sm-3">
                            <input type="text" id="total" name="total" class="form-control input-sm" disabled="disabled"
                                >
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
                            <!-- <button type="button" id="send" hidden="true"
                                class="btn btn-primary btn-rounded btn-sm mr-2"><i
                                    class="fa fa-paper-plane-o mr-2" ></i>Send</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Target Penjualan</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 30%;">Kota</th>
                        <th class="text-center" style="width: 30%;">Sales</th>
                        <th class="text-center" style="width: 34%;">Target</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value="0">
        </div>
    </div>
</div>
<!-- <div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Customer</h3>
        <div class="m-b-0">
            <div class="form-group row">
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>
                        Item</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center">Kota</th>
                        <th class="text-center">Sales</th>
                        <th class="text-center">Target</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="1"> -->
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();

    $('#bulan').datepicker({
        format: "MM",
     viewMode: "months", 
     minViewMode: "months",
     autoclose:true
    });

    $('#tahun').datepicker({
        format: "yyyy",
     viewMode: "years", 
     minViewMode: "years",
     autoclose:true
    });
    
});

$('#kode_area').select2();

var bulan   = $('#bulan').val();
var tahun   = $('#tahun').val();
var dfrom   = '01-' + bulan + '-' + tahun;
var dto     = '01-' + (bulan + 1) + '-' + tahun;

$('#send').click(function(event) {
    statuschange('<?= $folder;?>', '<?= $id;?>', '2', dfrom, dto);
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
var i = 0;

var counter = $('#jml').val();
var counterx = counter - 1;

var iarea   = '';
var sales   = '';
var total   = 0;
var itotal  = 1;
var itotalx = 0;

iarea = $('#kode_area').val();

for(i;i<counter;i++){
        $('#kota' + i).select2();
        $('#sales' + i).select2();
    }

$('#kode_area').change(function() {
iarea = $('#kode_area').val();
        $('#kota' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama Area',
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/getcity/'); ?>' + iarea,
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

        $('#sales' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama Sales',
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/getsalesman/'); ?>' + iarea,
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
})

$("#addrow").on("click", function() {
    counter++;
    counterx++;

    alert(counter);

    $("#tabledatax").attr("hidden", false);
    var icust = $('#icust' + counterx).val();
    count = $('#tabledatax tr').length;


    $('#jml').val(counter);
    var newRow = $("<tr>");
    var cols = "";

    $('#kode_area').change(function() {
    iarea = $('#kode_area').val();
        $('#kota' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama Area',
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/getcity/'); ?>' + iarea,
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

        $('#sales' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama Sales',
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/getsalesman/'); ?>' + iarea,
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
        })

    cols += '<td style="text-align: center;"><spanx id="snum' + counter + '">' + count + '</spanx></td>';
    
    cols += '<td><select data-urut="' + counter + '" id="kota' + counter + '" name="kota[]" class="form-control input-sm select2"></td>';
    cols += '<td><select data-urut="' + counter + '" id="sales' + counter + '" name="sales[]" class="form-control input-sm"></td>';
    cols += '<td><input type="text" id="target' + counter + '" name="target[]" placeholder="Target" class="form-control input-sm target"/></td>';
    cols +=
        '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger" onClick="kurangi('+ counter +')"><i class="ti-close"></i></button></td>';
    newRow.append(cols);
    $("#tabledatax").append(newRow);

    $('.dates').datepicker();

    $('#kota' + counter).select2({
        placeholder: 'Cari Berdasarkan Kota',
        allowClear: true,
        width: "100%",
        ajax: {
            url: '<?= base_url($folder.'/cform/getcity/'); ?>' + iarea,
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

    $('#sales' + counter).select2({
        placeholder: 'Cari Berdasarkan Nama Sales',
        allowClear: true,
        width: "100%",
        ajax: {
            url: '<?= base_url($folder.'/cform/getsalesman/'); ?>' + iarea,
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

    var total = '';

    if(counter < 2){
        document.getElementById("total").value = 0;
    }

    var check = '';
    var a     = '';

    $('#target'+ counter).on('click', function () {
        check = $(this).val();
        a = $('#total').val();
        a = a.split(".");
        a = a.join("");
        a = parseInt(a,0);
        if(check !== ""){
        check = check.split(".");
        check = check.join("");
        check = parseInt(check,0);
        }
    });

    $('#target'+ counter).on('keyup', function () {
        var b = $(this).val();
        b = b.split(".");
        b = b.join("");
        b = parseInt(b,0);
        total = a + b - check;
        total = toCommas(total);
        document.getElementById("total").value = total;
    });

    new AutoNumeric('#target' + counter, {
    aSep: '.', 
    aDec: ',',
    decimalPlaces:'0',
    aForm: true,
    unformatOnSubmit: true,
    vMax: '999999999999',
    vMin: '-999999999999',

    }); 

});


$("#tabledatax").on("click", ".ibtnDel", function(event) {
    $(this).closest("tr").remove();

    $('#jml').val(counter);
    del();
});

function kurangi(i){
    var target = $('#target'+i).val();
    target = target.split(".");
    target = target.join("");
    target = parseInt(target,0);
    total = $('#total').val();
    total = total.split(".");
    total = total.join("");
    total = parseInt(total,0);
    total = total - target;
    total = toCommas(total);
    document.getElementById("total").value = total;
}

function toCommas(value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

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
                    swal('Kota atau Sales tidak boleh kosong!');
                    ada = true;
                }
            });
            $(this).find("td input").each(function() {
                if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                    swal('Target Tidak Boleh Kosong Atau 0!');
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
    if(($('#bulan').val() == '') || ($('#bulan').val() == '')){
        swal('Periode Harus diisi!');
        return false;
    }

    if ($('#jml').val() == 0) {
        swal('Isi item minimal 1!');
        return false;
    } else {
        $("#tabledatax tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    swal('Data Target Area tidak boleh kosong!');
                    ada = true;
                }
            });
            $(this).find("td input").each(function() {
                if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                    swal('Jumlah Target Tidak Boleh Kosong Atau 0!');
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
function tanggal(d) {
    $('#dbp').val(maxDate(d));
}


</script>