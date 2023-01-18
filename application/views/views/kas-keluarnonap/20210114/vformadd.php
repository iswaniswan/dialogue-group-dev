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
                        <input class="form-control date" name="dkasbankkeluar" id="dkasbankkeluar" readonly="" value="<?php echo date("d-m-Y"); ?>">
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label class="col-md-6">Sisa Hutang</label>
                    <label class="col-md-6">Jumlah Bayar</label>
                    <div class="col-sm-6">
                        <input type="text" name="vsisa" id="vsisa" class="form-control">
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="vbayar" id="vbayar" class="form-control">
                    </div>
                </div>    -->
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                        <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-3">Jenis Keluar</label>
                    <label class="col-md-3">No Ref</label>
                    <label class="col-md-3">Kas/Bank</label>
                    <label class="col-md-3">Bank</label>
                    <div class="col-sm-3">
                    <select name="jeniskeluar" id="jeniskeluar" class="form-control select2" onchange="getjenis(this.value)">
                        <option value="">-- Jenis Keluar --</option>
                        <option value="kasbon">Kas Bon</option>
                        <option value="kaskeluar">Permintaan Kas Keluar</option>
                    </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="irefferensi" id="irefferensi" class="form-control select2"onchange="return getrefferensi(this.value);" >
                        </select>
                        <input type="hidden" name="partner" id="partner" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" >
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ibank" id="ibank" class="form-control select2">
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="col-md-6">Keterangan</label>
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
                                <th>Kode Kas</th>
                                <th>Tanggal Kas</th>
                                <th>Nilai Kas</th>
                                <th>Keterangan Kas</th>
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
    $("#addrow").attr("disabled", true);
    $("#send").attr("disabled", false);
});

function getenabledsend() {
    $('#send').attr("disabled", true);
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
    $('#ibank').select2({
    placeholder: 'Pilih Bank',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/bank'); ?>',
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

function refferensi(irefferensi){
    var irefferensi = $('#irefferensi').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/refferensi');?>",
        data:"irefferensi="+irefferensi,
        dataType: 'json',
        success: function(data){
            $("#irefferensi").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                  $("#irefferensi").attr("disabled", false);
                    if(ikasbank == 'KAS0001'){
                        $("#ibank").attr("disabled", false);
                        $("#submit").attr("disabled", false);
                    }
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getrefferensi(irefferensi) {
   
   var irefferensi = $('#irefferensi').val();
   var jeniskeluar = $('#jeniskeluar').val();
   $.ajax({
       type: "post",
       data: {
           'irefferensi': irefferensi,
           'jeniskeluar': jeniskeluar,
       },
       url: '<?= base_url($folder.'/cform/getrefferensi'); ?>',
       dataType: "json",
       success: function (data) {  
           $('#jml').val(data['dataitem'].length);
           $("#tabledata tbody").remove();
           $("#tabledata").attr("hidden", false);
           
           for (let a = 0; a < data['dataitem'].length; a++) {
               var no = a+1;
               var irefferensi  = data['dataitem'][a]['i_refferensi'];
               var vnilai  = data['dataitem'][a]['v_nilai'];
               var drefferensi  = data['dataitem'][a]['d_refferensi'];
               var eremark = data['dataitem'][a]['e_remark'];
               
               var cols        = "";
               var newRow = $("<tr>");

               cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
               cols += '<td><input readonly style="width:200px;" class="form-control" type="text" id="irefferensi'+no+'" name="irefferensi'+no+'" value="'+irefferensi+'"></td>';
               cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="drefferensi'+no+'" name="drefferensi'+no+'" value="'+drefferensi+'"></td>'; 
               cols += '<td><input readonly style="width:200px;" class="form-control" type="text" id="vnilai'+no+'" name="vnilai'+no+'" value="'+vnilai+'"  onkeyup="cekval(this.value); reformat(this);"></td>'; 
               cols += '<td><input style="width:400px;" class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value="'+eremark+'"></td>';
               cols +='<td></td>';
              
           newRow.append(cols);
           $("#tabledata").append(newRow);
           }
       },
       error: function () {
           alert('Error :)');
       }
   });
} 

function validasi(){
   var s=0;
   var ikasbank = $('#ikasbank').val();
   var i = document.getElementById("jml").value;
   var maxpil = 1;
   var jml = $("input[type=checkbox]:checked").length;
   var textinputs = document.querySelectorAll('input[type=checkbox]'); 
   var empty = [].filter.call( textinputs, function( el ) {
      return !el.checked
   });
  
   if(ikasbank == '' || ikasbank == null){
       swal("Data Masih Kosong");
       return false;
   }else{
       return true;
   }
}

function getjenis(jeniskeluar){
   $.ajax({
           type: "POST",
           url: "<?php echo site_url($folder.'/Cform/getjenis');?>",
           data:"jeniskeluar="+jeniskeluar,
           dataType: 'json',
           success: function(data){
               $("#irefferensi").html(data.kop);
               if (data.kosong=='kopong') {
                   $("#irefferensi").attr("disabled", true);
               }else{
                   $("#irefferensi").attr("disabled", false);
               }
           },

           error:function(XMLHttpRequest){
               alert(XMLHttpRequest.responseText);
           }

       })
       // if(tujuankeluar == 'external'){
       //     $("#epic").attr("hidden", false);
       // }else{
       //     $("#epic").attr("hidden", true);
       // }
}



</script>