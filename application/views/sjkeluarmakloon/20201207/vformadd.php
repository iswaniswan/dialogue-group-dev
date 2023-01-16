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
                        <label class="col-md-5">Bagian</label>
                        <label class="col-md-5">Tanggal SJ Makloon</label>
                        <div class="col-sm-5">
                            <select name="ibagian" id="ibagian" class="form-control select2" >
                                <?php foreach ($kodemaster as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?=$ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="idepart" name="idepart" class="form-control" value="<?= $departement;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-5">Type Makloon</label> 
                        <label class="col-md-6">Nomor Referensi Pengeluaran</label>
                        <div class="col-sm-5">
                            <select id="itypemakloon" name="itypemakloon" class="form-control select2" onchange="return getreferensi(this.value);" disabled="true">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select required="" id="reff" name="reff" class="form-control select2" disabled="true" onchange="getdetailreff();">
                            </select>
                        </div>
                       
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                            <input type="text" id= "eremark" name="eremark" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-sm-offset-6 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" disabled="disabled" onclick="return cek(this.value);"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Perkiraan Kembali</label>
                        <label class="col-md-8">Partner</label>                       
                        <div class="col-sm-4">
                            <input type="text" id="dback" name="dback" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <select name="ipartner" id="ipartner" class="form-control select2" onchange="return gettypemakloon(this.value);">
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Tanggal Referensi</label>                        
                        <div class="col-sm-4">
                            <input type="text" id="dreff" name="dreff" class="form-control" value="" readonly>
                             <input type="hidden" id="fpkp" name="fpkp" class="form-control" value="" readonly>
                        </div>                       
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="15">Kode Barang</th>
                                    <th width="45%">Nama Barang</th>  
                                    <th>Qty Permintaan</th>
                                    <th>Satuan</th>
                                    <th>Qty Pemenuhan</th>
                                    <th width="15">List Kode Barang</th>
                                    <th width="45%">List Nama Barang</th>
                                    <th>Qty Permintaan</th>
                                    <th>Qty Pemenuhan</th>
                                    <th>Satuan</th>  
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
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
    $("#send").attr("disabled", true);
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#send").attr("disabled", false);
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

$(document).ready(function () {
    $('#ipartner').select2({
        placeholder: 'Pilih Partner',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getpartner/'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
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

function gettypemakloon(ipartner){
    $("#itypemakloon").attr("disabled", false);
    var ipartner     = $('#ipartner').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/gettypemakloon');?>",
        data:{
            'ipartner': ipartner
        },
        dataType: 'json',
        success: function(data){
            $("#itypemakloon").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
                swal("Harga Makloon Masih Kosong","Silahkan Input Harga Makloon Berdasarkan Supplier Tersebut");
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    })
}

function getreferensi(ipartner){
    $("#reff").attr("disabled", false);
    var ipartner     = $('#ipartner').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreff');?>",
        data:{
            'ipartner': ipartner
        },
        dataType: 'json',
        success: function(data){
            $("#reff").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    })
}

function getdetailreff() {
    $("#tabledata").attr("hidden", false);
        //removeBody();
        var reff         = $('#reff').val();
        var ipartner     = $('#ipartner').val();
        var itypemakloon = $('#itypemakloon').val();
        var dsjk         = $('#dsjk').val();

        $.ajax({
            type: "post",
            data: {
                'reff'          : reff,
                'ipartner'      : ipartner,
                'itypemakloon'  : itypemakloon,
                'dsjk'          : dsjk,
            },
            url: '<?= base_url($folder.'/cform/getdetailreff'); ?>',
            dataType: "json",
            success: function (data) {
                 var dreff = data['head']['d_pp'];
                 var fpkp = data['head']['f_pkp'];
                 $('#dreff').val(dreff);
                 $('#fpkp').val(fpkp);

                $('#jml').val(data['detail'].length);
                $("#submit").attr("disabled", false);
                //var gudang = $('#istore').val();
                var lastmaterial = '';
                for (let a = 0; a < data['detail'].length; a++) {
                    var counter = a+1;
                    var i_material    = data['detail'][a]['i_material'];
                    var e_material    = data['detail'][a]['e_material_name'];
                    var n_qty         = data['detail'][a]['n_qty'];
                    var i_satuan      = data['detail'][a]['i_satuan_code'];
                    var e_satuan      = data['detail'][a]['e_satuan'];
                    var i_material2   = data['detail'][a]['i_material2'];
                    var e_material2   = data['detail'][a]['e_material_name2'];
                    var n_qty2        = data['detail'][a]['n_qty2'];
                    var i_satuan2     = data['detail'][a]['i_satuan_code2'];
                    var e_satuan2     = data['detail'][a]['e_satuan2'];
                    var e_remark      = data['detail'][a]['e_remark'];
                    var v_price       = data['detail'][a]['v_price'];

                    var cols        = "";
                    var newRow = $("<tr>");
                   if (lastmaterial == i_material) {
                        cols += '<td colspan="6"><input style="width:100px;" type="hidden" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+lastmaterial+'"><input type="hidden" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/><input type="hidden" style="width:100px;" id="pemenuhan'+ counter + '" value="" class="form-control" placeholder="0" name="pemenuhan[]"/></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]" value="'+i_material2+'" required></td>';
                        cols += '<td><input type="text" style="width:400px;" readonly id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" value="'+e_material2+'"></td>';
                        cols += '<td rowspan="1"><input type="text" style="width:100px;" readonly id="nquantity2'+ counter + '" value="'+n_qty2+'" class="form-control" placeholder="0" name="nquantity2[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
                        cols += '<td rowspan="1"><input type="text" style="width:100px;" id="pemenuhan2'+ counter + '" value="'+n_qty2+'" class="form-control" placeholder="0" name="pemenuhan2[]"/></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="'+e_satuan2+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" value="'+i_satuan2+'"/></td>';
                        cols += '<td><input style="width:200px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'"/><input type="hidden" id="v_price'+ counter + '" class="form-control" name="v_price[]" value="'+v_price+'"/></td>';
                    } else {
                        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+i_material+'" required></td>';
                        cols += '<td><input type="text" style="width:400px;" id="ematerialname2'+ counter + '" readonly class="form-control" name="ematerialname[]" value="'+e_material+'"></td>';
                        cols += '<td rowspan="1"><input type="text" size="2" readonly id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);"/></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="'+e_satuan+'" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';
                        cols += '<td rowspan="1"><input type="text" style="width:100px;" id="pemenuhan'+ counter + '" value="'+n_qty+'" class="form-control" placeholder="0" name="pemenuhan[]" onkeyup="validasi('+counter+'); reformat(this);"></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]" value="'+i_material2+'" required></td>';
                        cols += '<td><input type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" readonly name="ematerialname2[]" value="'+e_material2+'"></td>';
                        cols += '<td rowspan="1"><input type="text" readonly id="nquantity2'+ counter + '" value="'+n_qty2+'" class="form-control" placeholder="0" name="nquantity2[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
                        cols += '<td rowspan="1"><input type="text" style="width:100px;" id="pemenuhan2'+ counter + '" value="'+n_qty2+'" class="form-control" placeholder="0" name="pemenuhan2[]"/></td>';
                        cols += '<td rowspan="1"><input type="text" style="width:100px;" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="'+e_satuan2+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" value="'+i_satuan2+'" onkeyup="cekval(this.value);"/></td>';
                        cols += '<td><input style="width:200px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'"/><input type="hidden" id="v_price'+ counter + '" class="form-control" name="v_price[]" value="'+v_price+'"/></td>';
                    }
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    lastmaterial = i_material;
                }
                max_tgl();
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
}

function max_tgl() {
  $('#dsjk').datepicker('destroy');
  $('#dsjk').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dreff').value,
  });
}
$('#dsjk').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dreff').value,
});

function validasi(id){
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qtypp   =document.getElementById("nquantity"+i).value;
        qtypm =document.getElementById("pemenuhan"+i).value;
        if(parseFloat(qtypm)>parseFloat(qtypp)){
            swal('Jumlah Pemenuhan Melebihi Permintaan');
            document.getElementById("pemenuhan"+i).value='';
            break;
        }else if(parseFloat(qtypm)=='0'){
            swal('Jumlah Pemenuhan tidak boleh kosong')
            document.getElementById("pemenuhan"+i).value='';
            break;
        }
    }
}

function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
     }else{
            swal('input harus numerik !!!');
            input = input.substring(0,input.length-1);
    }
}

function cek() {
    var dsjk         = $('#dsjk').val();
    var partner      = $('#ipartner').val();
    var itypemakloon = $('#itypemakloon').val();

    if (dsjk == ''  || partner == '' || itypemakloon == '') {
        swal('Data Header Belum Lengkap !!');
        return false;
    } else {
        return true;
    }
}
</script>