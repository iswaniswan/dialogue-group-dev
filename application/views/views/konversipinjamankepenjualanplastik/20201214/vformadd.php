<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Gudang</label>
                        <label class="col-md-4">Tanggal</label>
                        <div class="col-sm-8">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore(this.value)">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_kode_master;?>"> <?= $ikodemaster->e_nama_master;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dkonversi" name="dkonversi" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <div class="col-sm-8">
                            <select required="" id="isjkp" name="isjkp" class="form-control" disabled="" onchange="getdetailsjkp();">
                            </select>
                        </div>
                    </div>                
                   
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Customer</label>
                        <div class="col-sm-6">
                            <select name="icustomer" id="icustomer" class="form-control select2" onchange="getcus(this.value)"> 
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>                  
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="35%">Nama Barang</th>
                                    <th>Qty Pinjaman Awal</th>
                                    <th>Qty Belum Kembali</th>
                                    <th>Qty Konversi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function getstore() {
    var gudang = $('#ikodemaster').val();
    //alert(gudang);

    if (gudang == "") {

    } else {
        $('#istore').val(gudang);
        $("#ikodemaster").attr("disabled", true);
    }
}

function getcus() {
    var icustomer = $('#icustomer').val();

    if (icustomer == "") {
        $("#isjkp").attr("disabled", true);
    } else {
        $("#isjkp").attr("disabled", false);
    }
    
    $('#isjkp').html('');
    $('#isjkp').val('');
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
});
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $('#isjkp').select2({
        placeholder: 'Cari No. Pengeluaran Pinjaman',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getsjkp/'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var gudang      = $('#istore').val();
                var customer    = $('#icustomer').val();
                var query = {
                    q: params.term,
                    gudang: gudang,
                    customer: customer
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        }
    });
});

function getdetailsjkp() {

    var sjkp     = $('#isjkp').val();
    var gudang   = $('#istore').val();
    var customer = $('#icustomer').val();
   
    $.ajax({
        type: "post",
        data: {
            'isjkp': sjkp,
            'gudang': gudang,
            'customer': customer
        },
        url: '<?= base_url($folder.'/cform/getdetailsjkp'); ?>',
        dataType: "json",
        success: function (data) {
            $('#jml').val(data['detail'].length);
            var gudang = $('#istore').val();
            for (let a = 0; a < data['detail'].length; a++) {
                 // alert("tes");
                var zz = a+1;
                var i_material    = data['detail'][a]['i_material'];
                var e_material    = data['detail'][a]['e_material_name'];
                var n_qty         = data['detail'][a]['n_qty'];
                var i_satuan      = data['detail'][a]['i_satuan'];
                var e_satuan      = data['detail'][a]['e_satuan'];
                var sisa          = data['detail'][a]['sisa'];

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" class="form-control" style="text-align:left;" id="i_satuan'+zz+'" name="i_satuan[]" value="'+i_satuan+'"></td>';
                cols += '<td><input type="text" class="form-control" readonly id="i_material'+zz+'" name="i_material[]" value="'+i_material+'"></td>';
                cols += '<td><input type="text" class="form-control" readonly id="e_material'+zz+'" name="e_material'+zz+'" value="'+e_material+'"></td>';
                cols += '<td><input readonly class="form-control" style="text-align:left;" id="n_qtyawal'+zz+'" name="n_qtyawal[]" value="'+n_qty+'"></td>';
                cols += '<td><input readonly class="form-control" style="text-align:left;" id="n_qtyout'+zz+'" name="n_qtyout[]" value="'+sisa+'"></td>';
                 cols += '<td><input class="form-control" style="text-align:left;" id="n_quantity'+zz+'" name="n_quantity[]" value=""></td>';
                  cols += '<td><input class="form-control" style="text-align:left;" id="edesc'+zz+'" name="edesc[]" value=""></td>';

                newRow.append(cols);
                $("#tabledata").append(newRow);
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
    //xx = $('#jml').val();
}

$(document).ready(function () {
    $('#icustomer').select2({
    placeholder: 'Pilih Customer',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/customer'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

function cek() {
    var dsjk = $('#dsjk').val();
    var isjkm = $('#isjkp').val();
    var istore = $('#istore').val();

    if (dsjk == '' || isjkm == null || istore == '') {
        swal('Data Header Belum Lengkap !!');
        return false;
    } else {
        return true;
    }
}
</script>