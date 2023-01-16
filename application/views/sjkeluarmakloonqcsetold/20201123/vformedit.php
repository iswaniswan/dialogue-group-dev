<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="col-md-7">
                         <input type="hidden" id="ndok" name="ndok" class="form-control" value="<?=$data->n_dok;?>" readonly>
                         <div class="form-group row">
                            <label class="col-md-4">Bagian</label>
                            <label class="col-md-4">No SJ</label>
                            <label class="col-md-4">Tanggal SJ</label>
                            <div class="col-sm-4">
                               <select name="ibagian" id="ibagian" class="form-control select2">
                                    <option value="">Pilih Bagian</option>
                                    <?php foreach ($bagian as $ibagian):?>
                                        <?php if ($ibagian->i_departement == $data->i_bagian) { ?>
                                             <option value="<?php echo $ibagian->i_departement;?>" selected><?= $ibagian->e_departement_name;?></option>
                                        <?php } else { ?>
                                             <option value="<?php echo $ibagian->i_departement;?>"><?= $ibagian->e_departement_name;?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                               </select>
                            </div>
                            <div class="col-sm-4">
                               <input type="text" id="isj" name="isj" class="form-control" value="<?=$data->i_sj;?>" required readonly>
                            </div>
                            <div class="col-sm-3">
                               <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?=$data->d_sj;?>" required readonly onchange="return max_back();">
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-md-8">No Permintaan</label>
                            <label class="col-md-4">Tanggal Permintaan</label>
                            <div class="col-sm-8">
                               <input type="text" id= "ipermintaan" name="ipermintaan" class="form-control" maxlength="18" required value="<?=$data->i_permintaan;?>">
                               <input type="hidden" id= "fpkp" name="fpkp" class="form-control" value="<?=$data->f_pkp;?>">
                               <input type="hidden" id= "vdiskon" name="vdiskon" class="form-control" value="<?=$data->n_diskon;?>">
                            </div>
                            <div class="col-sm-3">
                               <input readonly type="text" id= "dpermintaan" name="dpermintaan" class="form-control date" required value="<?=$data->d_permintaan;?>">
                            </div>
                         </div>
                        <div class="form-group">
                        <?if($data->i_status =='1'){?>
                            <div class="col-sm-offset-5 col-sm-10">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                                <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>
                            <?}else if($data->i_status =='2'){?>
                             <div class="col-sm-offset-5 col-sm-10">
                                <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                                <button type="button" id="addrow" disabled class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                                <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>
                            <?}else if($data->i_status =='3'){?>
                            <div class="col-sm-offset-5 col-sm-10">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                                 <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                                <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>
                            <?}else if($data->i_status =='7'){?>
                            <div class="col-sm-offset-5 col-sm-10">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                                <button type="button" id="sendd"  class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>
                            <?}else{?>
                            <div class="col-sm-offset-5 col-sm-10">
                                <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                                <button type="button" id="addrow" disabled class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                                <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>                       
                            <?}?>
                        </div>
                    </div>
                    <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-md-4">Perkiraan Kembali</label>
                        <label class="col-md-4">Partner</label>
                        <label class="col-md-4">Type Makloon</label>
                        <div class="col-sm-4">
                           <input type="text" id="dback" name="dback" class="form-control date" required value="<?=$data->d_back;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" >
                                <option value="">Pilih Partner</option>
                                <?php foreach ($partner as $ipartner):?>
                                    <?php if ($ipartner->i_partner == $data->i_partner) { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>" selected><?= $ipartner->e_partner;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>"><?= $ipartner->e_partner;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="itypemakloon" id="itypemakloon" class="form-control select2" onchange="getstore();">
                                <option value="">Pilih Type Makloon</option>
                                <?php foreach ($typemakloon as $itypemakloon):?>
                                    <?php if ($itypemakloon->i_type_makloon == $data->i_type_makloon) { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>" selected><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>"><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                           <input type="text" id= "eremark" name="eremark" class="form-control" value="<?=$data->e_remark;?>">
                        </div>
                     </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 5%;">No</th>
                                    <th style="text-align: center; width: 13%;">Kode Barang</th>
                                    <th style="text-align: center; width: 35%;">Nama Barang</th>
                                    <th style="text-align: center; width: 10%;">Quantity</th>
                                    <th style="text-align: center; width: 30%;">Keterangan</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>    
                             <?php  $i = 0;
                                    foreach ($detail as $row) {
                                    $i++;
                                ?>
                                    <td class="col-sm-1" style="text-align: center;">
                                        <spanx id="snum<?=$i;?>"><?=$i;?></spanx>
                                    </td>
                                    <td class="col-sm-1">
                                        <input style="width:150px;" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]" value="<?= $row->i_material; ?>" readonly >
                                    </td>
                                    <td class="col-sm-1">
                                        <input style="width:400px;" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]" value="<?= $row->e_namabrg; ?>" readonly onkeyup="validasi('<?=$i;?>');">
                                    </td>
                                    <td class="col-sm-1">
                                        <input style="width:100px;" type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" readonly>
                                    </td>                     
                                    <td class="col-sm-1">
                                        <input style="width:400px;" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc[]" value="<?= $row->e_remark; ?>"  >
                                        <input style="width:400px;" type="hidden" class="form-control" id="vharga<?=$i;?>" name="vharga[]" value="<?= $row->v_price; ?>">
                                    </td>
                                    <td>
                                        <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                <?php } ?>  
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
   $('.select2').select2();
   showCalendar('.date');
});

function max_tgl() {
 $('#dsjk').datepicker('destroy');
 $('#dsjk').datepicker({
   autoclose: true,
   todayHighlight: true,
   format: "dd-mm-yyyy",
   todayBtn: "linked",
   daysOfWeekDisabled: [0],
   startDate: document.getElementById('dpermintaan').value,
 });
}
$('#dsjk').datepicker({
 autoclose: true,
 todayHighlight: true,
 format: "dd-mm-yyyy",
 todayBtn: "linked",
 daysOfWeekDisabled: [0],
 startDate: document.getElementById('dpermintaan').value,
});

function max_back() {
 $('#dback').datepicker('destroy');
 $('#dback').datepicker({
   autoclose: true,
   todayHighlight: true,
   format: "dd-mm-yyyy",
   todayBtn: "linked",
   daysOfWeekDisabled: [0],
   startDate: document.getElementById('dsjk').value,
 });
}
$('#dback').datepicker({
 autoclose: true,
 todayHighlight: true,
 format: "dd-mm-yyyy",
 todayBtn: "linked",
 daysOfWeekDisabled: [0],
 startDate: document.getElementById('dsjk').value,
});

function getstore() {

   if (addrow == "") {
       $("#addrow").attr("hidden", true);
       $("#add_row").attr("hidden", true);
       $("#addroww").attr("hidden", true);
   } else {
       $("#addrow").attr("hidden", false);
       $("#add_row").attr("hidden", false);
       $("#addroww").attr("hidden", false);
   }
}
   
var counter = 0;

$("#addrow").on("click", function () {
       var counter = $('#jml').val();
       counter++;
       $("#tabledata").attr("hidden", false);
       document.getElementById("jml").value = counter;
       count=$('#tabledata tr').length;
       var newRow = $("<tr>");
       
       var cols = "";
       cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" class="form-control" name="baris[]" value="'+counter+'"></td>';
       cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
       cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control select2" name="eproduct[]" onchange="get('+ counter + ');"></td>';
       cols += '<td><input style="text-align: right;" type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="0"onkeyup="validasi('+ counter +'); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}" ></td>';
       cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/><input type="hidden" id="vharga'+ counter + '" class="form-control" name="vharga[]" value=""/></td>';
       cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
       newRow.append(cols);
       $("#tabledata").append(newRow);
       
       var dsjk   = $("#dsjk").val();
       var ndok   = $("#ndok").val();
       
      // alert(dsjk);
       $('#eproduct'+counter).select2({
       placeholder: 'Pilih Product',
       templateSelection: formatSelection,
       allowClear: true,
       ajax: {          
          url: '<?= base_url($folder.'/cform/getproductedit/'); ?>'+dsjk+'/'+ndok,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });

function formatSelection(val) {
   return val.name;
}

    $("#tabledata").on("click", ".ibtnDel", function (event) {
       $(this).closest("tr").remove();       
    });
});

function get(id){
       var eproduct     = $('#eproduct'+id).val();
       var ipartner     = $('#ipartner').val();
       var itypemakloon = $('#itypemakloon').val();
       var dsjk         = $('#dsjk').val();
       var ndok         = $('#ndok').val();
       $.ajax({
       type: "post",
       data: {
               'eproduct'     : eproduct,
               'ipartner'     : ipartner,
               'itypemakloon' : itypemakloon,
               'dsjk'         : dsjk,
               'ndok'         : ndok
       },
       url: '<?= base_url($folder.'/cform/getedit'); ?>',
       dataType: "json",
       success: function (data) {
           $('#iproduct'+id).val(data[0].i_material);
           $('#vdiskon').val(data[0].v_diskon);
           $('#vharga'+id).val(data[0].v_price);
           $('#fpkp').val(data[0].f_supplier_pkp);
   
           ada=false;
           var a = $('#iproduct'+id).val();
           var e = $('#eproduct'+id).val();
           var jml = $('#jml').val();
           for(i=1;i<=jml;i++){
               if((a == $('#iproduct'+i).val()) && (i!=id)){
                   swal ("kode : "+a+" sudah ada !!!!!");
                   ada=true;
                   break;
               }else{
                   ada=false;     
               }
           }
           if(!ada){
               $('#iproduct'+id).val(data[0].i_material);
           }else{
               $('#iproduct'+id).html('');
               $('#eproduct'+id).val('');
               $('#iproduct'+id).val('');
               $('#eproduct'+id).html('');
               $('#vharga'+id).val('');
               $('#vharga'+id).html('');
   
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
       qty          =document.getElementById("nquantity"+i).value;
       if(qty == '0'){
           swal("Jumlah Retur Tidak boleh kosong");
           $('#nquantity'+i).val("");
           break;
       }
   }
}
   
function cek() {
   var dsjk          = $('#dsjk').val();
   var ipermintaan   = $('#ipermintaan').val();
   var dpermintaan   = $('#dpermintaan').val();
   var dback         = $('#dback').val();
   var ibagian       = $('#ibagian').val();

   if (dsjk == '' || ipermintaan == '' || dpermintaan == '' || dback == '' || ibagian == '') {
       swal('Data Header Belum Lengkap !!');
       return false;
   } else {
       return true;
   }
}

$("form").submit(function(event) {
   event.preventDefault();
   $("input").attr("disabled", true);
   $("select").attr("disabled", true);
   $("#submit").attr("disabled", true);
   $("#addrow").attr("disabled", true);
   $('#sendd').attr("disabled", false);
});

function getenabledcancel() {
    swal("Berhasil", "Cancel Dokumen", "success");
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#addrow').attr("disabled", true);
}

function getenabledsend() {
    swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#addrow').attr("disabled", true);
}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var isj  = $("#isj").val();
        var ndok = $("#ndok").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'isj'  : isj,
                     'ndok' : ndok,
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

$(document).ready(function(){
    $("#sendd").on("click", function () {
        var isj = $("#isj").val();
        var ndok = $("#ndok").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'isj'  : isj,
                     'ndok' : ndok
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
</script>