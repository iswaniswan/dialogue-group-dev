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
                <div class="col-md-12">
                    <div class="form-group row ">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="iretur" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" value="<?=$data->i_document;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" id="id" name="id" class="form-control" value="<?=$data->id;?>">
                            <input type="hidden" id="idocumentold" name="idocumentold" class="form-control" value="<?=$data->i_document;?>">
                            <input type="text" id="dretur" name="dretur" class="form-control input-sm date" value="<?=$data->d_document;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Nomor Referensi</label><!-- dari Nota faktur jasa makloon -->
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getnota(this.value);">
                                <?php if ($supplier) {
                                    foreach ($supplier->result() as $key) { ?>
                                        <option value="<?= $key->id.'|'.$key->i_supplier;?>"
                                            <?php if ($key->id==$data->id_supplier) {?> selected <?php } ?>>
                                            <?= $key->e_supplier_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>
                        <!-- <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="getdetail(this.value);">
                                <?php if ($referensi) {
                                    foreach ($referensi->result() as $key) { ?>
                                        <option value="<?= $key->id;?>"
                                            <?php if ($key->id==$data->id_referensi_nota) {?> selected <?php } ?>>
                                            <?= $key->i_document;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div> -->

                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="getdetail(this.value);">
                               <option value="<?= $data->id_referensi_nota; ?>"><?= $data->i_document_referensi; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dnota" name="dnota" class="form-control" placeholder="<?=date('d-m-Y');?>" value="<?=$data->d_referensi;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">Satuan</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:center;">Qty Retur</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $("form").submit(function(event) {
        event.preventDefault();
         $("input").attr("disabled", true);
         $("select").attr("disabled", true);
         $("#submit").attr("disabled", true);
         $("#send").attr("hidden", false);
    });

    $(document).ready(function(){
        getdataedit();
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.tgl',0);
        $('#iretur').mask('SS-0000-000000S');

        // $('#isupplier').select2({
        //     placeholder: 'Pilih Supplier',
        //     allowClear: true,
        //     ajax: {
        //     url: '<?= base_url($folder.'/cform/getsupplier/'); ?>'+$('#ibagian').val(),
        //     dataType: 'json',
        //     delay: 250,          
        //     processResults: function (data) {
        //         return {
        //         results: data
        //         };
        //     },

        //     cache: true
        //     }
        // });
        //number();
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $( "#iretur" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $('#ifaktur').select2({
        placeholder: 'Pilih Referensi',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getreferensi'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q         : params.term,
                    ibagian   : $('#ibagian').val(),
                    isupplier : $('#isupplier').val(),
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

    $('#hapus').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#iretur").attr("readonly", false);
        }else{
            $("#iretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $('#ifaktur').on('change',function(){
        $("#submit").prop("disabled",false);
    });

function getstore() {
    var gudang = $('#ibagian').val();
    if (gudang == "") {
        $("#ifaktur").attr("disabled", true);
    } else {
        $('#istore').val(gudang);
    }
}

/*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/   
$('#ibagian').change(function(event) {
    number();
    //$("#tabledatay > tbody").remove();
    //$("#jml").val(0);
});

/*----------  RUNNING NUMBER DOKUMEN  ----------*/    
function number() {
    $.ajax({
        type: "post",
        data: {
            'tgl' : $('#dretur').val(),
            'ibagian' : $('#ibagian').val(),
        },
        url: '<?= base_url($folder.'/cform/number'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iretur').val(data);
        },
        error: function () {
            swal('Error :)');
        }
    });
}

// function getnota(id){
//      $("#ifaktur").attr("disabled", false);
//     $.ajax({
//         type: "POST",
//         url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
//         data:{
//             'isupplier': id,
//             'ibagian'  : $('#ibagian').val(),
//         },
//         dataType: 'json',
//         success: function(data){
//             $("#ifaktur").html(data.kop);
//             $("#esupplier").val(data.esupplier);
//             if (data.kosong=='kopong') {
//                 $("#submit").attr("disabled", true);
//             }else{
//                 $("#submit").attr("disabled", false);
//             }
//         },

//         error:function(XMLHttpRequest){
//             alert(XMLHttpRequest.responseText);
//         }

//     })
// }

function removeBody(){
    var tbl = document.getElementById("tabledatax");    // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}

function getdetail(id) {   
    $("#detail").attr("hidden", false);
    var isupplier = $('#isupplier').val();
    var ibagian = $('#ibagian').val();
    removeBody();       
    $.ajax({
        type: "post",
        data: {
            'id' : id,
            'isupplier' : isupplier,
            'ibagian' : ibagian,
        },
        url: '<?= base_url($folder.'/cform/getreferensidetail'); ?>',
        dataType: "json",
        success: function (data) {
            var dnota               = data['head']['d_document'];
            $('#dnota').val(dnota);
            $('#jml').val(data['detail'].length);
            var group = "";
            for (let a = 0; a < data['detail'].length; a++) {
                var zz = a+1;
                var x = $('#jml').val();
                var newRow = $("<tr>");
                var cols1 = "";

                if(group == ""){
                    cols1 += '<td colspan="7">Nomor SJ : <b>'+data['detail'][a]['i_reff']+'</b></td>';
                }else{
                    if(group != data['detail'][a]['id_reff']){
                        cols1 += '<td colspan="7">Nomor SJ : <b>'+data['detail'][a]['i_reff']+'</b></td>';
                    }
                }
                group = data['detail'][a]['id_reff'];
                newRow.append(cols1);
                $("#tabledatax").append(newRow);

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                cols += '<td><input type="hidden" class="form-control" id="idreffsj'+zz+'" name="idreffsj'+zz+'" value="'+data['detail'][a]['id_reff']+'" readonly><input style="width:100px;" class="form-control" readonly type="text" id="imaterial'+zz+'" name="imaterial'+zz+'" value="'+data['detail'][a]['i_material']+'">';
                cols += '<input type="hidden" class="form-control" id="idmaterial'+zz+'" name="idmaterial'+zz+'" value="'+data['detail'][a]['id_material']+'"></td>';
                cols += '<td><input style="width:400px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+data['detail'][a]['e_material_name']+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="esatuan'+zz+'" name="esatuan'+zz+'" value="'+data['detail'][a]['e_satuan_name']+'"><input readonly style="width:70px;" type="hidden" id="isatuan'+zz+'" name="isatuan'+zz+'" value="'+data['detail'][a]['i_satuan_code']+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="'+data['detail'][a]['n_sisa']+'">';
                cols += '<input type="hidden" class="form-control" id="nsisa'+zz+'" name="nsisa'+zz+'" value="'+data['detail'][a]['n_sisa']+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" type="text" id="nretur'+zz+'" name="nretur'+zz+'" placeholder="0" onkeyup="validasi('+zz+'); reformat(this);gettotal(this);"></td>';
                cols += '<td><input class="form-control" style="width:300px;"  type="text" id="edesc'+zz+'" name="edesc'+zz+'" value="" placeholder="Isi keterangan jika ada!"></td>';
                newRow.append(cols);
                $("#tabledatax").append(newRow);
            }
            // max_tgl();
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
}   

function getdataedit() {   
    $("#detail").attr("hidden", false);
    var isupplier = $('#isupplier').val();
    var ibagian   = $('#ibagian').val();
    removeBody();       
    $.ajax({
        type: "post",
        data: {
            'id'        : $('#id').val(),
            'isupplier' : isupplier,
            'ibagian'   : ibagian,
        },
        url: '<?= base_url($folder.'/cform/getdetailedit'); ?>',
        dataType: "json",
        success: function (data) {
            var dnota               = data['head']['d_document'];
            $('#dnota').val(dnota);
            $('#jml').val(data['detail'].length);
            var group = "";
            for (let a = 0; a < data['detail'].length; a++) {
                var zz = a+1;
                var x = $('#jml').val();
                var newRow = $("<tr>");
                var cols1 = "";

                if(group == ""){
                    cols1 += '<td colspan="7">Nomor SJ : <b>'+data['detail'][a]['i_reff']+'</b></td>';
                }else{
                    if(group != data['detail'][a]['id_reff']){
                        cols1 += '<td colspan="7">Nomor SJ : <b>'+data['detail'][a]['i_reff']+'</b></td>';
                    }
                }
                group = data['detail'][a]['id_reff'];
                newRow.append(cols1);
                $("#tabledatax").append(newRow);

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                cols += '<td><input type="hidden" class="form-control" id="idreffsj'+zz+'" name="idreffsj'+zz+'" value="'+data['detail'][a]['id_reff']+'" readonly><input style="width:100px;" class="form-control" readonly type="text" id="imaterial'+zz+'" name="imaterial'+zz+'" value="'+data['detail'][a]['i_material']+'">';
                cols += '<input type="hidden" class="form-control" id="idmaterial'+zz+'" name="idmaterial'+zz+'" value="'+data['detail'][a]['id_material']+'"></td>';
                cols += '<td><input style="width:400px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+data['detail'][a]['e_material_name']+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="esatuan'+zz+'" name="esatuan'+zz+'" value="'+data['detail'][a]['e_satuan_name']+'"><input readonly style="width:70px;" type="hidden" id="isatuan'+zz+'" name="isatuan'+zz+'" value="'+data['detail'][a]['i_satuan_code']+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="'+data['detail'][a]['n_sisa']+'">';
                cols += '<input type="hidden" class="form-control" id="nsisa'+zz+'" name="nsisa'+zz+'" value="'+data['detail'][a]['n_sisa']+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" type="text" id="nretur'+zz+'" name="nretur'+zz+'" placeholder="0" onkeyup="validasi('+zz+'); reformat(this);gettotal(this);" value="'+data['detail'][a]['n_retur']+'"></td>';
                cols += '<td><input class="form-control" style="width:300px;"  type="text" id="edesc'+zz+'" name="edesc'+zz+'" value="" placeholder="Isi keterangan jika ada!"></td>';
                newRow.append(cols);
                $("#tabledatax").append(newRow);
            }
            // max_tgl();
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
}   

function gettotal(id){
    var hasiltotal = 0;
    jml = document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qty     = $('#qtyretur'+i).val();
        harga   = $('#vunitprice'+i).val();
        vtotal  = qty * harga;
        hasiltotal = hasiltotal + vtotal;
    }
    $('#vtotal').val(hasiltotal);
}


function validasi(id){
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qtysj   =document.getElementById("nsisa"+i).value;
        qtyretur=document.getElementById("nretur"+i).value;
        if(parseFloat(qtyretur)>parseFloat(qtysj)){
            swal('Jumlah Retur Tidak Boleh Lebih dari Sisa Jumlah Faktur. Sisa = '+qtysj);
            document.getElementById("nretur"+i).value=0;
            break;
        }else if(parseFloat(qtyretur)=='0'){
            swal('Jumlah Retur tidak boleh kosong')
            document.getElementById("nretur"+i).value='';
            break;
        }
    }
}

function max_tgl() {
  $('#dretur').datepicker('destroy');
  $('#dretur').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dnota').value,
  });
}
$('#dretur').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dnota').value,
});

function cek() {
    var dretur = $('#dretur').val();ifaktur
    var ibagian = $('#ibagian').val();
    var ifaktur = $('#ifaktur').val();
    var jml = $('#jml').val();
    var qty = []; 

    if (dretur == '' || dretur == null || ibagian == '' || ibagian == null || ifaktur == '' || ifaktur == null) {
        swal('Data Header Belum Lengkap !!');
        return false;
    }else{
        var jumlah = 0;
        for (i=1; i<=jml; i++){
            qty2 = parseInt($('#qtyretur'+i).val());
            jumlah = jumlah + qty2;
        }             
        if (jumlah == 0) {
            swal("Barang Retur Harus Di Isi");
            return false;
        } else {
            return true;
        } 
    }
}   
</script>
