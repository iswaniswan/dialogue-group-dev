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
                        <input class="form-control date" name="dmasuk" id="dmasuk" readonly="" value="<?php echo date("d-m-Y"); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Customer</label>
                    <label class="col-md-6">Nilai</label>
                    <div class="col-sm-6">
                        <select name="icustomer" id="icustomer" class="form-control select2" onchange="return getcustomer(this.value);" disabled="true">
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="vnilai" id="vnilai" class="form-control">
                    </div>
                </div>   
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
                    <label class="col-md-1"></label>
                    <label class="col-md-5">Kas/Bank</label>
                    <label class="col-md-6">Bank</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" onchange="return customer(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <select name="ibank" id="ibank" class="form-control select2" disabled="true">
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
                <table id="myTable" class="table table-bordered" cellspacing="0"  width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Customer</th>
                                <th>Nama Customer</th>
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
$(document).ready(function () {
    $("#myTable").attr("hidden", true);
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

function customer(ikasbank){
    var icustomer = $('#icustomer').val();
    var ikasbank  = $('#ikasbank').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/customer');?>",
        data:"ikasbank="+ikasbank,
        dataType: 'json',
        success: function(data){
            $("#icustomer").html(data.kop);
            getcustomer('ALCUS');
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                  $("#icustomer").attr("disabled", false);
                  $("#ibank").attr("disabled", false);
                  $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getcustomer(icustomer) {
    //$('#addrow').attr("hidden", false);
    var icustomer = $('#icustomer').val();
    $.ajax({
        type: "post",
        data: {
            'icustomer': icustomer,
        },
        url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
        dataType: "json",
        success: function (data) {  

            $('#jml').val(data['dataitem'].length);
            $("#myTable tbody").remove();
            $("#myTable").attr("hidden", false);
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var icustomer  = data['dataitem'][a]['i_customer']
                var ecustomer  = data['dataitem'][a]['e_customer_name'];
                
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="icustomer'+no+'" name="icustomer'+no+'" value="'+icustomer+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="ecustomer'+no+'" name="ecustomer'+no+'" value="'+ecustomer+'"></td>'; 
               // cols += '<td><input style="width:200px;" class="form-control" type="text" id="vnilai'+no+'" name="vnilai'+no+'" value=""  onkeyup="cekval(this.value); reformat(this);"></td>'; 
                cols += '<td><input style="width:400px;" class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value=""></td>';
                cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
               
            newRow.append(cols);
            $("#myTable").append(newRow);
            }

            $('#myTable').DataTable({
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "displayLength": 10,
                //"paging" : false,
           });

            $('.dataTables_paginate').on('click', function() {
               // $('.select2').select2();
            // alert('toggled');
            });
            
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
   
    if (textinputs.length == empty.length) {
        swal("Pelanggan Belum dipilih !!");
        return false;
    }else if(jml > maxpil){
        swal("Hanya 1 Pelanggan");
        return false;
    }else if(ikasbank == '' || ikasbank == null){
        swal("Data Masih Kosong");
        return false;
    }else{
        return true;
    }
}    
</script>