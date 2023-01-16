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
                        <label class="col-md-7">Bagian</label>
                        <label class="col-md-5">Tanggal</label>
                        <div class="col-sm-7">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore(this.value)">
                                <option value="" selected>-- Pilih Bagian --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_departement;?>"> <?= $ikodemaster->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                            <input type="hidden" id="ilokasi" name="ilokasi" class="form-control" value="<?=$ilokasi;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dkonversi" name="dkonversi" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <div class="col-sm-7">
                            <select id="isjkp" name="isjkp" multiple="multiple" class="form-control select2" disabled="" onchange="getdetailsjkp(this.value);">
                            </select>
                            <input type="hidden" id="dbonmk" name="dbonmk" class="form-control" value="" readonly>
                        </div>
                    </div>                
                   
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Partner</label>
                        <div class="col-sm-6">
                            <select name="ipartner" id="ipartner" class="form-control select2" onchange="getcus(this.value)"> 
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
                        <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="35%">Nama Barang</th>
                                    <th>Qty Pinjaman Awal</th>
                                    <th>Qty Outstanding</th>
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
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#send").attr("disabled", false);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
    $("#send").attr("disabled", true);
});

function getenabledsend() {
    swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
    $('#send').attr("disabled", true);
}

$(document).ready(function(){
    $("#send").on("click", function () {
        var kode = $("#kode").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});

function getstore() {
    var gudang = $('#ikodemaster').val();
    //alert(gudang);
    if (gudang == "") {

    } else {
        $('#istore').val(gudang);
        $("#ikodemaster").attr("disabled", true);
    }
}

$(document).ready(function () {
    $('#ipartner').select2({
    placeholder: 'Pilih Partner',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/partner'); ?>',
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

function getcus() {
    var ipartner = $('#ipartner').val();

    if (ipartner == "") {
        $("#isjkp").attr("disabled", true);
    } else {
        $("#isjkp").attr("disabled", false);
    }
    
    $('#isjkp').html('');
    $('#isjkp').val('');
}

$(document).ready(function () {
    $('#isjkp').select2({
        placeholder: 'Cari No. Pengeluaran Pinjaman',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getsjkp/'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var ipartner    = $('#ipartner').val();
                var query = {
                    q: params.term,
                    ipartner: ipartner
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

function getdetailsjkp(isjkp) {
    
    $("#tabledata tr:gt(0)").remove();  
    $("#jml").val(0);     
   var sjkp     = $('#isjkp').val();
    var ipartner = $('#ipartner').val();
   
    $.ajax({
        type: "post",
        data: {
            'isjkp':sjkp,
            'ipartner': ipartner
        },
        url: '<?= base_url($folder.'/cform/getdetailsjkp'); ?>',
        dataType: "json",
        success: function (data) {
            $("#tabledata").attr("hidden", false);
            var dbonmk = data['head']['d_bonmk'];
            $('#dbonmk').val(dbonmk);
            $('#jml').val(data['detail'].length);

            for (let a = 0; a < data['detail'].length; a++) {
                 // alert("tes");
                var zz = a+1;
                var i_material    = data['detail'][a]['i_material'];
                var e_material    = data['detail'][a]['e_material_name'];
                var n_qty         = data['detail'][a]['n_qty'];
                var i_satuan      = data['detail'][a]['i_satuan'];
                var e_satuan      = data['detail'][a]['e_satuan'];

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" class="form-control" style="text-align:left;" id="i_satuan'+zz+'" name="i_satuan[]" value="'+i_satuan+'"></td>';
                cols += '<td><input type="text" class="form-control" readonly id="i_material'+zz+'" name="i_material[]" value="'+i_material+'"></td>';
                cols += '<td><input type="text" class="form-control" readonly id="e_material'+zz+'" name="e_material'+zz+'" value="'+e_material+'"></td>';
                cols += '<td><input readonly class="form-control" style="text-align:left;" id="n_qtyawal'+zz+'" name="n_qtyawal[]" value="'+n_qty+'"></td>';
                cols += '<td><input readonly class="form-control" style="text-align:left;" id="n_qtyout'+zz+'" name="n_qtyout[]" value="'+n_qty+'" ></td>';
                cols += '<td><input class="form-control" style="text-align:left;" id="n_quantity'+zz+'" name="n_quantity[]" value="" onkeyup="validasi('+zz+'); reformat(this);"></td>';
                cols += '<td><input style="width:300px;" class="form-control" style="text-align:left;" id="edesc'+zz+'" name="edesc[]" value=""></td>';

                newRow.append(cols);
                $("#tabledata").append(newRow);
            }
            max_tgl();
        },
        error: function () {
            swal('Data Kosong :)');
        }
    });
    xx = $('#jml').val();
}

function max_tgl() {
  $('#dkonversi').datepicker('destroy');
  $('#dkonversi').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dbonmk').value,
  });
}
$('#dkonversi').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dbonmk').value,
});

function validasi(id){
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qtypp   =document.getElementById("n_qtyawal"+i).value;
        qtypm =document.getElementById("n_quantity"+i).value;
        if(parseFloat(qtypm)>parseFloat(qtypp)){
            swal('Jumlah Quantity Melebihi Quantity Pinjaman');
            document.getElementById("n_quantity"+i).value='';
            break;
        }else if(parseFloat(qtypm)=='0'){
            swal('Jumlah Quantity tidak boleh kosong')
            document.getElementById("n_quantity"+i).value='';
            break;
        }
    }
}

function cek() {
    var dkonversi = $('#dkonversi').val();
    var isjkm = $('#isjkp').val();
    var istore = $('#istore').val();
    var jml = $('#jml').val();

    if (dkonversi == '' || isjkm == null || istore == '' ) {
        swal('Data Header Belum Lengkap !!');
        return false;
    }else{
        for (i=1; i<=jml; i++){  
            if($("#n_quantity"+i).val() == '' || $("#n_quantity"+i).val() == null){
                swal('Quantity Harus Diisi!');
                return false;                    
            } else {
                return true;
            } 
        }
    }
}
</script>