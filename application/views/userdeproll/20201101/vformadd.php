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
                        <select name="idept" id="idept" class="form-control select2" onchange="getlevel(this.value);">
                         <!-- disabled="true"> -->
                            <option value="">-- Pilih Departement --</option>
                                <?php foreach ($depart as $idept): ?>
                                <option value="<?php echo $idept->i_departement; ?>">
                                <?=$idept->i_departement . " - " . $idept->e_departement_name;?></option>
                                <?php endforeach;?>
                        </select>
                     </div>
                     <div class="col-sm-6">
                     <select name="ilevel" id="ilevel" class="form-control select2" onchange="getcustomer(this.value);">                          
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
            <div class="form-group row">
                        <label class="col-md-6">Menu</label>
                        <label class="col-md-6">Sub Menu</label>
                        <div class="col-sm-6">
                        <select name="imenu" id="imenu" class="form-control select2" onchange="getsubmenu(this.value);getcustomer(this.value);">
                        <!-- onchange="getsubmenu(this.value)"> -->
                            <option value="">-- Pilih Menu --</option>
                                <?php foreach ($menu as $imenu): ?>
                                <option value="<?php echo $imenu->i_menu; ?>">
                                <?=$imenu->i_menu . " - " . $imenu->e_menu;?></option>
                                <?php endforeach;?>
                        </select>
                     </div>
                     <div class="col-sm-6">
                    <select name="isubmenu" id="isubmenu" class="form-control select2" onchange="getcustomer(this.value);">                          
                        </select>
                    </div>
                </div>
            </div>
            <input style ="width:50px"type="text" name="jml" id="jml" value="">
            <input style ="width:50px"type="text" name="jmldetail" id="jmldetail" value="">
                    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Menu</th>
                                <th>Nama Menu</th>
                                <th>User Power</th>
                                <!-- <th>Keterangan</th> -->
                                <th width="5%" colspan="2"><input type="checkbox" name="cekall" id="cekall"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                
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
    // $('#tabledata').DataTable();
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

function getsubmenu(imenu) {
    // $('#isubmenu').attr("disabled", false);
    // $("#addrow").attr("disabled", false);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getsubmenu');?>",
        data:"imenu="+imenu,
        dataType: 'json',
        success: function(data){
            $("#isubmenu").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            // get('KTG');
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

function getlevel(id) {
    // $('#isubmenu').attr("disabled", false);
    // $("#addrow").attr("disabled", false);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getlev');?>",
        data:"ideptart="+id,
        dataType: 'json',
        success: function(data){
            $("#ilevel").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            // get('KTG');
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


function getcustomer2(isubmenu) {
    //$('#addrow').attr("hidden", false);
    var ilevel      = $('#ilevel').val();
    var idept       = $('#idept').val();
    var imenu       = $('#imenu').val();
    var isubmenu    = $('#isubmenu').val();
    $.ajax({
        type: "post",
        data: {
            'dep'       : idept,
            'lev'       : ilevel,
            'imenu'     : imenu,
            'isubmenu'  : isubmenu,
        },
        url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
        dataType: "json",
        success: function (data) {  
            // console.log(data);
            // return false;
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var imenu           = data['dataitem'][a]['i_menu']
                var emenu           = data['dataitem'][a]['e_menu'];
                var userpower       = data['dataitem'][a]['id']
                var userpowername   = data['dataitem'][a]['e_name']
                var ename           = data['dataitem'][a]['e_name'];
                var ada             = data['dataitem'][a]['adaaja'];
                // $checked        = !empty(ename)?"checked":"";
                // var imenu  = data['dataitem'][a]['i_menu']
                // var emenu  = data['dataitem'][a]['e_menu'];
                
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imenu22'+no+'" name="imenu22'+no+'" value="'+imenu+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="emenu'+no+'" name="emenu'+no+'" value="'+emenu+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="hidden" id="userpower'+no+'" name="userpower'+no+'" value="'+userpower+'"><input readonly style="width:350px;" class="form-control" type="text" id="iuserpower'+no+'" name="iuserpower'+no+'" value="'+userpowername+'"></td>'; 
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

function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}
var no = 0;
function getcustomer(isubmenu) {
    // removeBody();
    var ilevel      = $('#ilevel').val();
    var idept       = $('#idept').val();
    var imenu       = $('#imenu').val();
    var isubmenu    = $('#isubmenu').val();
    
    var ireff = $('#i_reff').val();
    // swal(ireff);
    $.ajax({
        type: "post",
        data: {
            'dep'       : idept,
            'lev'       : ilevel,
            'imenu'     : imenu,
            'isubmenu'  : isubmenu,
        },
        url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
        dataType: "json",
        success: function (data) {
            // console.log(data);
            $("#tabledata tbody").remove();
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var imenu           = data['dataitem'][a]['i_menu']
                var emenu           = data['dataitem'][a]['e_menu'];
                // var userpower       = data['dataitem'][a]['id']
                // var userpowername   = data['dataitem'][a]['e_name']
                var ename           = data['dataitem'][a]['e_name'];
                var ada             = data['dataitem'][a]['adaaja'];

                // var x = $('#jml').val();
                document.getElementById("jml").value = no;

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imenu2'+no+'" name="imenu2'+no+'" value="'+imenu+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="emenu'+no+'" name="emenu'+no+'" value="'+emenu+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="hidden" id="userpower'+no+'" name="userpower'+no+'" value=""><input readonly style="width:350px;" class="form-control" type="hidden" id="iuserpower'+no+'" name="iuserpower'+no+'" value=""></td>'; 
                if(ada == 0){
                    cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'" class ="cekit"></td>';
                    cols += '<td style="text-align: center;"><button type="button" id="adddetail"  title="Tambah Item" class="btn btn-default btn-rounded btn-sm" ><i class="fa fa-2x fa-angle-down"></i></button></td>';
                }else{
                    cols +='<td></td>';
                }
                // cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
                // cols += '<td style="text-align: center;"><button type="button" id="adddetail"  title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);
                
                $('#i_2material'+no).select2({
                    placeholder: 'Pilih Material',
                    allowClear: true,
                    ajax: {
                        url: '<#?= base_url($folder);?>/cform/datamaterial/',
                        dataType: 'json',
                        delay: 250,
                      // processResults: function (data) {
                      //   return {
                      //     results: data
                      //   };
                      // },
                      // cache: true
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
  
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
    no = $('#jml').val();
}
 var nodetail = 0;
$("#tabledata").on("click", "#adddetail", function (event) {
        //$(this).closest('td').find('td').attr('rowspan','2');
        var row = $(this).closest("tr");
        // var material = $(this).closest('tr').find('.---').val();
        // var nquantity = $(this).closest('tr').find('.nquantity').val();
        // var isatuan = $(this).closest('tr').find('.isatuan').val();
        no++;
        nodetail = no ;
        var imenu      = $('#imenu2'+no).val();
        document.getElementById("jmldetail").value = nodetail;
        count=$('#tabledata tr').length;
        $.ajax({
        type: "post",
        data: {
            'imenu'       : imenu,
        },
        url: '<?= base_url($folder.'/cform/getmenudetail'); ?>',
        dataType: "json",
        success: function (data) {
            console.log(data);
            // $("#tabledata tbody").remove();
            for (let a = 0; a < data['dataitem'].length; a++) {
                var nom = a+1;
                var imenu           = data['dataitem'][a]['i_menu']
                var emenu           = data['dataitem'][a]['e_menu'];
                var userpower       = data['dataitem'][a]['id']
                var userpowername   = data['dataitem'][a]['e_name']
                var ename           = data['dataitem'][a]['e_name'];
                var ada             = data['dataitem'][a]['adaaja'];

                // var x = $('#jml').val();
                document.getElementById("jmldetail").value = nom;
                count=$('#tabledata tr').length;
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td colspan="3"><input readonly style="width:150px;" class="form-control" type="hidden" id="imenu2'+nom+'" name="imenu2'+nom+'" value="'+imenu+'"></td>';
                // cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="emenu'+nom+'" name="emenu'+nom+'" value="'+emenu+'"></td>';
                cols += '<td><input readonly style="width:200px;" class="form-control" type="text" id="emenu'+no+'" name="emenu'+no+'" value="'+userpowername+'"></td>';
                // if(ada == 0){
                    cols +='<td><input type="checkbox" name="cekdetail'+nom+'" value="cek" id="cekdetail'+nom+'"></td>';
                // }else{
                //     cols +='<td></td>';
                // }
                // cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
                cols += '<td style="text-align: center;"><button type="button" id="adddetail"  title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-trash"></i></button></td>';
                newRow.append(cols);
                newRow.insertAfter(row);
                // $("#tabledata").append(newRow);
                
                $('#i_2material'+no).select2({
                    placeholder: 'Pilih Material',
                    allowClear: true,
                    ajax: {
                        url: '<#?= base_url($folder);?>/cform/datamaterial/',
                        dataType: 'json',
                        delay: 250,
                      // processResults: function (data) {
                      //   return {
                      //     results: data
                      //   };
                      // },
                      // cache: true
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
  
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
    });

$("#tabledata").on("click", "#adddd", function (event) {
        //$(this).closest('td').find('td').attr('rowspan','2');
        var row = $(this).closest("tr");
        var material = $(this).closest('tr').find('.---').val();
        var nquantity = $(this).closest('tr').find('.nquantity').val();
        var isatuan = $(this).closest('tr').find('.isatuan').val();
        no++;
        var imenu      = $('#imenu2'+no).val();
        document.getElementById("jml").value = no;
        count=$('#tabledata tr').length;
        //alert(count);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td colspan="3"><input style="width:100px;" type="hidden" readonly  id="imaterial'+ no + '" type="text" class="imaterial" name="imaterial[]" value="'+material+'"><input type="hidden" size="2" id="nquantity'+ no + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+nquantity+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ no + '" class="isatuan" name="isatuan[]" value="'+isatuan+'"/></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ no + '" type="text" class="form-control" name="imaterial2[]"></td>';
        cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="hidden" readonly  id="imaterial2'+ no + '" type="text" class="form-control" name="imaterial2[]"></td>';
        // cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
        // cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        //$("#tabledata").append(newRow);
        newRow.insertAfter(row);
        var gudang = $('#istore').val();
        $('#ematerialname2'+no).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<#?= base_url($folder);?>/cform/datamaterial/'+gudang,
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

    $(document).on('click','#cekall',function(){
		var jml    = $('#jml').val();
		var status = $(this).prop('checked') 
		if(status == true){
			for(n=1; n<=jml; n++){
				$('#cek'+n).prop('checked',true);
			}
		}else{
			for(n=1; n<=jml; n++){
				$('#cek'+n).prop('checked',false);
			}
		}
		// hitungnilai();
	});

    $(document).on('click','.cekit',function(){
		var jml    = $('#jmldetail').val();
		var status = $(this).prop('checked') 
		if(status == true){
            // alert(jml);
			for(n=jml; n>=1; n--){
				$('#cekdetail'+n).prop('checked',true);
			}
		}else{
			for(n=jml; n>=1; n--){
				$('#cekdetail'+n).prop('checked',false);
			}
		}
		// hitungnilai();
	});
    

    
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