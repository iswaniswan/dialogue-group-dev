<style>
    td {
        white-space: nowrap;
    }
    .withscroll {
        width: 1800px;
        overflow-x: scroll;
        white-space: nowrap;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Permintaan ke Gudang</label>
                        <label class="col-md-5">Tanggal Permintaan</label>
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_kode_master;?>"><?= $ikodemaster->e_nama_master;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                            <input type="hidden" id="ibagian" name="ibagian" class="form-control" value="<?php echo $area->i_bagian;?>">
                            <input type="hidden" id="ikodekelompok" name="ikodekelompok" class="form-control" value="<?php echo $ikelompok;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Tujuan</label>
                        <label class="col-md-5">Department/Partner</label>
                        <div class="col-sm-6">
                            <select name="tujuankeluar" id="tujuankeluar" class="form-control select2" disabled onchange="getpic(this.value)">
                                <option value="">-- Pilih Tujuan --</option>
                                <option value="internal">Internal</option>
                                <option value="external">External</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="edept" id="edept" class="form-control select2" required>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="divpic">
                        <label class="col-md-6">PIC Internal</label>
                        <label class="col-md-6"></label>
                        <div class="col-sm-6">
                            <select name="ppic" id="ppic" class="form-control select2" required>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" id="epic" name="epic" class="form-control" hidden="true" placeholder="Nama PIC External">
                        </div>
                    </div>
                     
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        <button type="button" id="addrowlain" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" disabled onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Jenis Pengeluaran</label>
                        <div class="col-sm-8">
                            <select name="jenis" id="jenis" class="form-control select2" onchange="ketabel();">
                                <option value="" selected>-- Pilih Jenis Pengeluaran --</option>
                                <?php foreach ($jeniskeluar as $jeniskeluar):?>
                                <option value="<?php echo $jeniskeluar->i_jenis;?>"><?= $jeniskeluar->e_nama_jenis;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="jenispengeluaran" name="jenispengeluaran" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8">
                        <label >Nomor Memo (Optional)</label>
                            <input type="text" id="memo" name="memo" class="form-control" maxlength="" value="">
                        </div>
                        <div class="col-sm-4">
                        <label >Tgl Memo (Opt)</label>
                            <input type="text" id="dmemo" name="dmemo" class="form-control date"  readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="255" value="">
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                   <div class="table-responsive">
                        <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Kode Barang</th>
                                    <th>List Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table id="tabledata2" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
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

function ketabel() {
    var pilih =  $("#jenis").val();
    var gudang = $('#istore').val();
    $("#jenispengeluaran").val(pilih);
    $("#tujuankeluar").attr("disabled", false);
    $('#tujuankeluar').val();

    if (pilih == "" && gudang =="") {
        $("#addrow").attr("hidden", true);
    } else {
        $("#addrow").attr("hidden", false);
    }

    if (pilih == "JK00002") {
        $("option[value='internal']").remove();
        $("option[value='external']").remove();
        if (pilih == "" && gudang =="") {
            $("#addrow").attr("hidden", true);
            $("#tabledata").attr("hidden", true);
        } else {
            $("#addrow").attr("hidden", false);
            $("#tabledata").attr("hidden", false);
            $("#tabledata2").attr("hidden", true);
            $("#addrowlain").attr("hidden", true);
        }
        optionText2 = 'External'; 
        optionValue2 = 'external'; 
         $('#tujuankeluar').append(`<option value="${optionValue2}"> 
                                       ${optionText2} 
                                  </option>`); 
    } else if(pilih == "JK00003"){
        $("option[value='internal']").remove();
        $("option[value='external']").remove();
        if (pilih == "" && gudang =="") {
            $("#addrowlain").attr("hidden", true);
            $("#tabledata2").attr("hidden", true);
        } else {
            $("#addrowlain").attr("hidden", false);
            $("#tabledata2").attr("hidden", false);
            $("#tabledata").attr("hidden", true);
            $("#addrow").attr("hidden", true);
        }
        optionText = 'Internal'; 
        optionValue = 'internal'; 
        $('#tujuankeluar').append(`<option value="${optionValue}"> 
                                       ${optionText} 
                                  </option>`); 
    }else{
        $("option[value='internal']").remove();
        $("option[value='external']").remove();
        if (pilih == "" && gudang =="") {
            $("#addrowlain").attr("hidden", true);
            $("#tabledata2").attr("hidden", true);
        } else {
            $("#addrowlain").attr("hidden", false);
            $("#tabledata2").attr("hidden", false);
            $("#tabledata").attr("hidden", true);
            $("#addrow").attr("hidden", true);
        }

        optionText = 'Internal'; 
        optionValue = 'internal'; 
        optionText2 = 'External'; 
        optionValue2 = 'external'; 
        $('#tujuankeluar').append(`<option value="${optionValue}"> 
                                       ${optionText} 
                                  </option>`); 
        $('#tujuankeluar').append(`<option value="${optionValue2}"> 
                                       ${optionText2} 
                                  </option>`); 
    }
}

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $(".ibtnDel").attr("disabled", true);
    $("#addrowlain").attr("disabled", true);
    $("#send").attr("disabled", false);
});

$(document).ready(function () {
    $('#ppic').select2({
    placeholder: 'Pilih PIC',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/getppic'); ?>',
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

$("#send").on("click", function () {
        var kode = $("#kode").val();
        var gudang = $("#istore").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                     'gudang'  : gudang
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

function getenabledsend() { 
    swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
    $('#send').attr("disabled", true);   
}

function getpic(tujuankeluar){
    var jenis = $('#jenis').val();
    $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getpic');?>",
            data:{
                    'tujuankeluar': tujuankeluar,
                    'jenis'       : jenis
            },
            dataType: 'json',
            success: function(data){
                $("#edept").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#edept").attr("disabled", true);
                }else{
                    $("#edept").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
        if(tujuankeluar == 'external'){
            $("#epic").attr("hidden", false);
        }else{
            $("#epic").attr("hidden", true);
        }
}

function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){

    }else{
        alert('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
}

function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    $('#nquantity'+counter).val(vjumlah);

}
var counter = 0;

 $("#addrow").on("click", function () {
    counter++;
    document.getElementById("jml").value = counter;
    count=$('#tabledata tr').length;
    var newRow = $("<tr>");
    var ikodekelompok = $('#ikodekelompok').val();

    var cols = "";
    cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
    cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]"></td>';
    cols += '<td rowspan="1"><select style="width:400px;" type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
    cols += '<td rowspan="1"><input type="text" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
    cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]"/></td>';
    cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]"></td>';
    cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"></td>';
    cols += '<td rowspan="1"><input style="width:100px;" type="text" id="nquantity2'+ counter + '" class="form-control" placeholder="0" name="nquantity2[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
    cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" onkeyup="cekval(this.value);"/></td>';
    cols += '<td><input type="text" style="width:200px;" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
    cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
    cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
    newRow.append(cols);
    $("#tabledata").append(newRow);
    
    $('#ikodemaster').attr("disabled", true);
    $('#jenis').attr("disabled", true);
    var gudang = $('#istore').val();
    
    $('#ematerialname'+counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang+'/'+ikodekelompok,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query   = {
                    q       : params.term
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

    $('#ematerialname2'+counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang+'/'+ikodekelompok,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query   = {
                    q       : params.term
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

function formatSelection(val) {
    return val.name;
}

$("#tabledata").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();       
    // counter -= 1
    // document.getElementById("jml").value = counter;
    del();
});

$("#tabledata").on("click", "#addrow2", function (event) {
    //$(this).closest('td').find('td').attr('rowspan','2');
    var row = $(this).closest("tr");
    var material = $(this).closest('tr').find('.imaterial').val();
    var nquantity = $(this).closest('tr').find('.nquantity').val();
    var isatuan = $(this).closest('tr').find('.isatuan').val();
    counter++;
    document.getElementById("jml").value = counter;
    count=$('#tabledata tr').length;
    //alert(count);
    var newRow = $("<tr>");
    var cols = "";
    cols += '<td colspan="5"><input style="width:100px;" type="hidden" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+material+'"><input type="hidden" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+nquantity+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+isatuan+'"/></td>';
    cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]"></td>';
    cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"></td>';
    cols += '<td rowspan="1"><input type="text" id="nquantity2'+ counter + '" class="form-control" placeholder="0" name="nquantity2[]" value="" onkeyup="validasi('+ counter +'); reformat(this);"></td>';
    cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value=""/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" ></td>';
    cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
    cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
    cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
    newRow.append(cols);
    //$("#tabledata").append(newRow);
    newRow.insertAfter(row);
    var gudang = $('#istore').val();
    var ikodekelompok = $('#ikodekelompok').val();

    $('#ematerialname2'+counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang+'/'+ikodekelompok,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query   = {
                    q       : params.term
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

function del() {
    obj=$('#tabledata tr').find('spanx');
    $.each( obj, function( key, value ) {
        id=value.id;
        $('#'+id).html(key+1);
    });
}

function getmaterial(id){
        var ematerialname = $('#ematerialname'+id).val();
        $.ajax({
            type: "post",
            data: {
                'ematerialname': ematerialname
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#imaterial'+id).val(data[0].i_material);
                $('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan_code);
           ada=false;
            var a = $('#imaterial'+id).val();
            var e = $('#ematerialname'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
                if((a == $('#imaterial'+i).val()) && (i!=id)){
                    swal ("kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            if(!ada){
                $('#imaterial'+id).val(data[0].i_material);
                $('#ematerialname'+id).val(data[0].e_material_name);
                $('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan_code);
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
                $('#ematerialname'+id).val('');
                $('#isatuan'+id).val('');
                // $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getmaterial2(id){
    var ematerialname = $('#ematerialname2'+id).val();
    $.ajax({
            type: "post",
            data: {
                'ematerialname': ematerialname
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
            $('#imaterial2'+id).val(data[0].i_material);
            $('#ematerialname2'+id).val(data[0].e_material_name);
            $('#esatuan2'+id).val(data[0].e_satuan);
            $('#isatuan2'+id).val(data[0].i_satuan_code);
                
        },
        error: function () {
            alert('Error :)');
        }
    });
}
    
function getstore() {
    var gudang = $('#ikodemaster').val();
    //alert(gudang);
    $('#istore').val(gudang);
    
}

function validasi(id){
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qty     =document.getElementById("nquantity2"+i).value;
        if(qty == '0' || qty == null){
            swal("Quantity Tidak boleh kosong");
            $('#nquantity2'+i).val("");
            break;
        }
    }
}

function cek() {
    var dbonk = $('#dbonk').val();
    var istore = $('#istore').val();
    var tujuankeluar = $('#tujuankeluar').val();
    var ppic = $('#ppic').val(); 
    var dept = $('#dept').val();
    var jenispengeluaran = $('#jenispengeluaran').val();
    var jml = $('#jml').val();

    if (dbonk == ''  || istore == '' || tujuankeluar == '' || dept == '' || jenispengeluaran == '' || ppic == '') {
        alert('Data Header Belum Lengkap !!');
        return false;
    } else {
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            return true;
        }
    }
}
    ///////////////////////////UNTUK JENIS PENGELAURAN SELAIN MAKLOON///

$(document).ready(function () {
    $("#addrowlain").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata2 tr').length;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]"></td>';
        cols += '<td rowspan="1"><select style="width:400px;" type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata2").append(newRow);
        
        $('#ikodemaster').attr("disabled", true);
        $('#jenis').attr("disabled", true);
        var gudang = $('#istore').val();
var ikodekelompok = $('#ikodekelompok').val();
        $('#ematerialname'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang+'/'+ikodekelompok,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
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
    $("#tabledata2").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        del2();
    });

    function del2() {
        obj=$('#tabledata2 tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }
});
</script>