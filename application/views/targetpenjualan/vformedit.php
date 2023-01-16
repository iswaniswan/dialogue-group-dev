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
                        <label class="col-sm-3">Bagian</label>
                        <label class="col-sm-3">Bulan</label>
                        <label class="col-sm-3">Tahun</label>
                        <label class="col-sm-3">Area</label>

                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required=""
                                >
                                <?php if ($databagian) {
                                    foreach ($databagian as $row):?>
                                <option value="<?= $row->i_bagian;?>" <?php if($row->e_bagian_name == $bagian){
                                    echo "selected";
                                } ?>>
                                    <?= $row->e_bagian_name;?>
                                </option>
                                <?php endforeach; 
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" id="periodelama" name="periodelama" value="<?= $periode; ?>">
                            <input type="text" id="bulan" name="bulan" class="form-control input-sm date"
                                onchange="tanggal(this.value); " required="" readonly 
                                >
                        </div>
                        <div class="col-sm-3">
                            
                            <input type="text" id="tahun" name="tahun" class="form-control input-sm date"
                                onchange="tanggal(this.value); " required="" value="<?= $tahun; ?>" readonly
                                >
                        </div>
                        <div class="col-sm-3">
                            <select name="kode_area" id="kode_area" class="form-control input-sm" required="">
                                <?php if ($kodearea) {
                                    foreach ($kodearea as $row):?>
                                <option value="<?= $row->id;?>" <?php if($row->e_area == $area){
                                    $periodelama = $row->id;
                                    echo "selected";
                                } ?> >
                                    <?= $row->e_area;?>
                                </option>
                                <?php if($row->e_area == $area){ ?>
                                    
                                <?php } ?>
                                <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" id="idarea" name="idarea" value="<?php echo $periodelama; ?>">
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
                                onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"> <i
                                    class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <!-- <button type="button" id="send" hidden="true"
                                class="btn btn-primary btn-rounded btn-sm mr-2"><i
                                    class="fa fa-paper-plane-o mr-2"></i>Send</button> -->
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
                    <?php 
                    $i = 0;
                    $total = 0;
                        if($data){
                            foreach($data as $row){ ?>
                            <tr>
                                <td style="text-align: center;"><spanx id="snum<?php echo $i; ?>"><?php echo $i; ?></spanx></td>
                                <td><select data-urut="<?php echo $i; ?>" id="kota<?php echo $i; ?>" name="kota[]" class="form-control input-sm select2">
                                    <?php if($kodecity){ 
                                        foreach($kodecity as $rowcity){?>
                                    <option value="<?php echo $rowcity->id ; ?>" 
                                    <?php if($row->e_city_name == $rowcity->e_city_name){
                                        echo "selected";
                                    } ?>
                                    > <?php echo $rowcity->i_city." - ".$rowcity->e_city_name; ?> </option>
                                    <?php }} ?>
                                </select></td>
                                <td><select data-urut="<?php echo $i; ?>" id="sales<?php echo $i; ?>" name="sales[]" class="form-control input-sm">
                                    <?php if($kodesalesman){ 
                                        foreach($kodesalesman as $rowsales){?>
                                    <option value="<?php echo $rowsales->id ; ?>" 
                                    <?php if($row->e_sales == $rowsales->e_sales){
                                        echo "selected";
                                    } ?>
                                    > <?php echo $rowsales->i_sales." - ".$rowsales->e_sales; ?> </option>
                                    <?php }} ?>
                                    </select></td>
                                <td><input type="text" id="target<?php echo $i; ?>" name="target[]" placeholder="Target" class="form-control input-sm"/ value="<?= $row->v_target; ?>"></td>
                                <td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger" onClick="kurangi(<?php echo $i; ?>)"><i class="ti-close"></i></button></td>
                            </tr>
                            <?php
                            $total = $total + $row->v_target;
                            $i++;    
                        }
                        }
                    ?>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value="<?php echo $i; ?>">
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
<script>
$(document).ready(function() {
    $('#dok_rrkh').mask('SSSS-0000-000000S');
    $('.select2').select2();

    $('#bulan').datepicker({
        format: "MM",
     viewMode: "months", 
     minViewMode: "months",
     autoclose:true
    }).datepicker("update", "<?= $bulan; ?>");

    $('#tahun').datepicker({
        format: "yyyy",
     viewMode: "years", 
     minViewMode: "years",
     autoclose:true
    });

    var checktotal = <?php echo $total; ?>;
    checktotal = toCommas(checktotal);
    document.getElementById("total").value = checktotal;
    
});

$('#kode_area').select2();

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

$('#ceklis').click(function(event) {
    if ($('#ceklis').is(':checked')) {
        $("#dok_rrkh").attr("readonly", false);
    } else {
        $("#dok_rrkh").attr("readonly", true);
        $("#ada").attr("hidden", true);
        
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
var i = 0;

var counter = $('#jml').val();

var counterx = counter - 1;

var iarea = '';
var sales = '';

iarea = $('#kode_area').val();

for(i;i<counter;i++){
        $('#kota' + i).select2();
        $('#sales' + i).select2();

        new AutoNumeric('#target' + i, {
        aSep: '.', 
        aDec: ',',
        decimalPlaces:'0',
        aForm: true,
        unformatOnSubmit: true,
        vMax: '999999999999',
        vMin: '-999999999999',

        }); 

        var total = '';

        var check = '';

        var a     = '';

        $('#target'+ i).on('click', function () {
            a = $('#total').val();
            a = a.split(".");
            a = a.join("");
            a = parseInt(a,0);
            check = $(this).val();
            if(check !== ""){
            check = check.split(".");
            check = check.join("");
            check = parseInt(check,0);
            }
        });

        $('#target'+ i).on('keyup', function () {
            var b = $(this).val();
            b = b.split(".");
            b = b.join("");
            b = parseInt(b,0);
            total = a + b - check;
            total = toCommas(total);
            document.getElementById("total").value = total;
        });
    }

$('#kode_area').change(function() {
iarea = $('#kode_area').val();
})

$("#addrow").on("click", function() {
    counter++;
    counterx++;

    $("#tabledatax").attr("hidden", false);
    var icust = $('#icust' + counterx).val();
    count = $('#tabledatax tr').length;


    $('#jml').val(counter);
    var newRow = $("<tr>");
    var cols = "";

    $('#kode_area').change(function() {
        iarea = $(this).val();
        $('#kota' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama Customer',
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
            placeholder: 'Cari Berdasarkan Nama',
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
    cols += '<td><input type="text" id="target' + counter + '" name="target[]" placeholder="Target" class="form-control input-sm"/></td>';
    cols +=
        '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger" onClick="kurangi('+ counter +')"><i class="ti-close"></i></button></td>';
    newRow.append(cols);
    $("#tabledatax").append(newRow);


    $('.dates').datepicker();

    $('#kota' + counter).select2({
        placeholder: 'Cari Berdasarkan Nama Customer',
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
        placeholder: 'Cari Berdasarkan Nama',
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
            a = $('#total').val();
            a = a.split(".");
            a = a.join("");
            a = parseInt(a,0);
            check = $(this).val();
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
            $(this).find("td .inputitem").each(function() {
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

function tanggal(d) {
    $('#dbp').val(maxDate(d));
}
</script>