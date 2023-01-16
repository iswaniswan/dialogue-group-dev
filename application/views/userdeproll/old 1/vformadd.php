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
                        <label class="col-md-6">Departement</label>
                        <label class="col-md-6">level</label>
                        <div class="col-sm-6">
                        <select name="idept" id="idept" class="form-control select2">
                         <!-- disabled="true"> -->
                            <option value="">-- Pilih Departement --</option>
                                <?php foreach ($depart as $idept): ?>
                                <option value="<?php echo $idept->i_departement; ?>">
                                <?=$idept->i_departement . " - " . $idept->e_departement_name;?></option>
                                <?php endforeach;?>
                        </select>
                     </div>
                     <div class="col-sm-6">
                     <select name="ilevel" id="ilevel" class="form-control select2" onchange="return getcustomer(this.value);">
                        <option value="">-- Pilih level --</option>
                            <?php foreach ($level as $ilevel): ?>
                            <option value="<?php echo $ilevel->i_level; ?>">
                            <?=$ilevel->i_level . " - " . $ilevel->e_level_name;?></option>
                        <?php endforeach;?>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                        <!-- <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button> -->
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- <div class="form-group row">
                     <label class="col-md-1"></label> 
                    <label class="col-md-6">Kas/Bank</label>
                    <label class="col-md-6">Bank</label>
                     <div class="col-sm-1"></div> 
                    <div class="col-sm-6">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" onchange="return customer(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <select name="ibank" id="ibank" class="form-control select2" disabled="true">
                        </select>
                    </div>
                </div> -->
            </div>
            <!-- <input type ="hidden" id="jml" name="jml" readonly> -->
            <input style ="width:50px"type="text" name="jml" id="jml" value="">
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Menu</th>
                                <th>Nama Menu</th>
                                <th>User_power</th>
                                <!-- <th>Keterangan</th> -->
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
    $('#tabledata').DataTable();
});


// $("form").submit(function(event) {
//     event.preventDefault();
//     $("input").attr("disabled", true);
//     $("select").attr("disabled", true);
//     $("#submit").attr("disabled", true);
//     $("#addrow").attr("disabled", true);
//     $("#send").attr("disabled", false);
// });

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

// $(document).ready(function () {
//     $('#ikasbank').select2({
//     placeholder: 'Pilih Kas/Bank',
//     allowClear: true,
//     ajax: {
//       url: '<#?= base_url($folder.'/cform/kasbank'); ?>',
//       dataType: 'json',
//       delay: 250,          
//       processResults: function (data) {
//         return {
//           results: data
//         };
//       },
//       cache: true
//     }
//   })
// });

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
    var idept   = $('#idept').val();
    var ilevel  = $('#ilevel').val();
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

function getcustomer(ilevel) {
    //$('#addrow').attr("hidden", false);
   
    var idept   = $('#idept').val();
    // var ilevel  = $('#ilevel').val();
    $.ajax({
        type: "post",
        data: {
            'dep': idept,
            'lev': ilevel,
        },
        url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
        dataType: "json",
        success: function (data) {  
            console.log(data);
            // return false;
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var imenu       = data['dataitem'][a]['i_menu']
                var emenu       = data['dataitem'][a]['e_menu'];
                var userpower   = data['dataitem'][a]['id']
                var ename       = data['dataitem'][a]['e_name'];
                var ada       = data['dataitem'][a]['adaaja'];
                // $checked        = !empty(ename)?"checked":"";
                // var imenu  = data['dataitem'][a]['i_menu']
                // var emenu  = data['dataitem'][a]['e_menu'];
                
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imenu'+no+'" name="imenu'+no+'" value="'+imenu+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="emenu'+no+'" name="emenu'+no+'" value="'+emenu+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="hidden" id="userpower'+no+'" name="userpower'+no+'" value="'+userpower+'"><input readonly style="width:350px;" class="form-control" type="text" id="euserpower'+no+'" name="euserpower'+no+'" value="'+ename+'"></td>'; 
                // cols += '<td><input style="width:200px;" class="form-control" type="text" id="vnilai'+no+'" name="vnilai'+no+'" value=""  onkeyup="cekval(this.value); reformat(this);"></td>'; 
                if(ada == 0){
                    cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
                }else{
                    cols +='<td></td>';
                }
               
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }

            // $('#myTable').DataTable({
            //     "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            //     "displayLength": 10,
            //     //"paging" : false,
            // });
            //     $('.dataTables_paginate').on('click', function() {
            //     $('.select2').select2();
            //     // alert('toggled');
            // });
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
    }else if(ikasbank == '' || ikasbank == null){
        swal("Data Masih Kosong");
        return false;
    }else{
        return true;
    }
}    
</script>