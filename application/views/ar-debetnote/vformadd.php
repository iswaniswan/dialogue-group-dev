<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
            <div class="col-md-6">
                <div class="form-group row">
                        <label class="col-md-6">Bagian</label>
                        <label class="col-md-6">Tanggal</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="ibagian" id="ibagian">
                                <?php foreach ($area as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?= $ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                     </div>
                     <div class="col-sm-4">
                        <input class="form-control date" name="dnota" id="dnota" readonly="" value="<?php echo date("d-m-Y"); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Referensi dari Pelunasan Piutang</label>
                    <label class="col-md-6">Referensi dari Kas/Bank in Other</label>
                    <div class="col-sm-6">
                        <select name="ireferensipp" id="ireferensipp" class="form-control select2" onchange="return getitemp(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <select name="ireferensikb" id="ireferensikb" class="form-control select2" onchange="return getitemk(this.value);">
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                        <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-6">Partner</label>
                    <label class="col-md-5">Kas/Bank</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-6">
                        <select class="form-control select2" name="ipartner" id="ipartner" onchange="return getreferensi(this.value);">
                        </select>
                    </div>
                     <div class="col-sm-5">
                        <select class="form-control select2" name="ikasbank" id="ikasbank">
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-11">Keterangan</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <input type="text" name="eremark" id="eremark" class="form-control">
                    </div>
                </div>      
            </div>
            <input style ="width:50px"type="hidden" name="jml" id="jml" value="">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Dokumen</th>
                                    <th>Tanggal Dokumen</th>
                                    <th>Pengirim</th>
                                    <th>Jumlah</th>
                                    <th>Jumlah yang dikembalikan</th>
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
</div>
<script>
$(document).ready(function () {
    showCalendar('.date');
    $('.select2').select2();
    $("#ireferensipp").attr("disabled", true);
    $("#ireferensikb").attr("disabled", true);
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
    $('#ikasbank').select2({
    placeholder: 'Pilih Kas/Bank',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/kasbank'); ?>',
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

function getreferensi(ipartner){
    $("#ireferensipp").attr("disabled", false);
    getreferensikb(ipartner);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
        data:"ipartner="+ipartner,
        dataType: 'json',
        success: function(data){
            $("#ireferensipp").html(data.kop);
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

function getreferensikb(ipartner){
    $("#ireferensikb").attr("disabled", false);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreferensikb');?>",
        data:"ipartner="+ipartner,
        dataType: 'json',
        success: function(data){
            $("#ireferensikb").html(data.kop);
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

function getitemp(ireferensipp) {
    $("#ireferensikb").attr("disabled", true);   
    var ireferensipp = $('#ireferensipp').val();
    var ipartner     = $('#ipartner').val();

    $.ajax({
        type: "post",
        data: {
            'ireferensipp': ireferensipp,
            'ipartner': ipartner,
        },
        url: '<?= base_url($folder.'/cform/getitemp'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var nodok          = data['dataitem'][a]['nodok']
                var ddok           = data['dataitem'][a]['ddok'];
                var partner        = data['dataitem'][a]['partner'];
                var epartner       = data['dataitem'][a]['epartner'];
                var jumlah_lebih   = data['dataitem'][a]['jumlah_lebih'];
                
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:250px;" class="form-control" type="text" id="nodok'+no+'" name="nodok'+no+'" value="'+nodok+'"></td>';
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="ddok'+no+'" name="ddok'+no+'" value="'+ddok+'"></td>'; 
                cols += '<td><input readonly style="width:350px;" class="form-control" type="hidden" id="partner'+no+'" name="partner'+no+'" value="'+partner+'"><input readonly style="width:350px;" class="form-control" type="text" id="epartner'+no+'" name="epartner'+no+'" value="'+epartner+'"></td>'; 
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="jumlah_lebih'+no+'" name="jumlah_lebih'+no+'" value="'+jumlah_lebih+'"></td>';
                cols += '<td><input style="width:150px;" class="form-control" type="text" id="jumlah'+no+'" name="jumlah'+no+'" value="" onkeyup="validasi('+no+'); reformat(this);"></td>';
                cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
               
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
} 

function getitemk(ireferensikb) {
    $("#ireferensipp").attr("disabled", true);   
    var ireferensikb = $('#ireferensikb').val();
    var ipartner     = $('#ipartner').val();

    $.ajax({
        type: "post",
        data: {
            'ireferensikb': ireferensikb,
            'ipartner': ipartner,
        },
        url: '<?= base_url($folder.'/cform/getitemk'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var nodok          = data['dataitem'][a]['nodok']
                var ddok           = data['dataitem'][a]['ddok'];
                var partner        = data['dataitem'][a]['partner'];
                var epartner       = data['dataitem'][a]['epartner'];
                var jumlah_lebih   = data['dataitem'][a]['jumlah_lebih'];
                //alert(nodok);
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:250px;" class="form-control" type="text" id="nodok'+no+'" name="nodok'+no+'" value="'+nodok+'"></td>';
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="ddok'+no+'" name="ddok'+no+'" value="'+ddok+'"></td>'; 
                cols += '<td><input readonly style="width:350px;" class="form-control" type="hidden" id="partner'+no+'" name="partner'+no+'" value="'+partner+'"><input readonly style="width:350px;" class="form-control" type="text" id="epartner'+no+'" name="epartner'+no+'" value="'+epartner+'"></td>'; 
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="jumlah_lebih'+no+'" name="jumlah_lebih'+no+'" value="'+jumlah_lebih+'"></td>';
                cols += '<td><input style="width:150px;" class="form-control" type="text" id="jumlah'+no+'" name="jumlah'+no+'" value="" onkeyup="validasi('+no+'); reformat(this);"></td>';
                cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
               
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
} 

function validasi(id){
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qty     =document.getElementById("jumlah_lebih"+i).value;
        qtyretur=document.getElementById("jumlah"+i).value;
        if(parseFloat(qtyretur)>parseFloat(qty)){
            swal('Jumlah Tidak Boleh Lebih');
            $('#jumlah'+i).val("");
            break;
        }else if(qtyretur == '0'){
            swal("Jumlah Tidak boleh kosong");
            $('#jumlah'+i).val("");
            break;
        }
    }
}
</script>