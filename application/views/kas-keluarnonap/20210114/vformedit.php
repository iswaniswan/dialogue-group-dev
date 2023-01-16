<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                 <div class="form-group row">
                    <label class="col-md-5">Nomor Dokumen</label>
                    <label class="col-md-3">Bagian</label>
                    <label class="col-md-4">Tanggal</label>
                    <div class="col-sm-5">
                        <input class="form-control" name="ikasbankkeluar" id="ikasbankkeluar" readonly="" value="<?= $data->i_kasbank_keluar_nonap?>">
                    </div>
                    <div class="col-sm-3">
                            <select class="form-control select2" name="ibagian" id="ibagian">
                                <?php foreach ($area as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?= $ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control date" name="dkasbankkeluar" id="dkasbankkeluar" readonly="" value="<?= $data->d_kasbank_keluar?>">
                    </div>
                </div>
                <div class="form-group row">
                    <!-- <label class="col-md-4">Sisa Hutang </label>
                    <label class="col-md-4">Jumlah Bayar</label> -->
                    <label class="col-md-4">Keterangan</label>
                    <!-- <div class="col-sm-4">
                        <input type="text" name="vsisa" id="vsisa" class="form-control" value="<?=$data->v_sisa?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vbayar" id="vbayar" class="form-control" value="<?=$data->v_bayar?>" >
                    </div> -->
                    <div class="col-sm-4">
                        <input type="text" name="eremark" id="eremark" class="form-control" value="<?=$data->e_remark?>" >
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="">
                    </div>
                </div>   
                    <div class="form-group">
                        <?if($data->i_status =='1'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else if($data->i_status =='2'){?>
                         <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else if($data->i_status =='3'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else if($data->i_status =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd"  class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else{?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>                       
                        <?}?>
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
                        <?php if ($data->i_jenis_keluar == 'kasbon') { ?>
                            <option value="kasbon" selected>Kas Bon</option>
                            <option value="kaskeluar">Permintaan Kas Keluar</option>
                        <?php }else { ?>
                            <option value="kasbon">Kas Bon</option>
                            <option value="kaskeluar" selected>Permintaan Kas Keluar</option>
                        <?php }?>
                    </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="irefferensi" id="irefferensi" class="form-control select2"onchange="return getrefferensi(this.value);" >
                            <option value="" selected>Pilih Nomor Refferensi</option>
                            <?php foreach ($refferensi as $irefferensi):?>
                            <?php if ($irefferensi->i_refferensi == $data->i_refferensi) { ?>
                                <option value="<?php echo $irefferensi->i_refferensi;?>" selected><?= $irefferensi->i_refferensi;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $irefferensi->i_refferensi;?>"><?= $irefferensi->i_refferensi;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" >
                            <option value="" selected>Pilih Tujuan</option>
                            <?php foreach ($kasbank as $ikasbank):?>
                            <?php if ($ikasbank->i_kode_kas == $data->i_kasbank) { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>" selected><?= $ikasbank->e_nama_kas;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>"><?= $ikasbank->e_nama_kas;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?if($data->i_bank == null){?>
                    <div class="col-sm-3">
                        <select name="ibank" id="ibank" class="form-control select2" >
                        </select>
                    </div>
                    <?}else{?>
                    <div class="col-md-3">
                        <select name="ibank" id="ibank" class="form-control select2">
                            <option value="" selected>Pilih Bank</option>
                            <?php foreach ($bank as $ibank):?>
                            <?php if ($ibank->i_bank == $data->i_bank) { ?>
                                <option value="<?php echo $ibank->i_bank;?>" selected><?= $ibank->e_bank_name;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ibank->i_bank;?>"><?= $ibank->e_bank_name;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?}?>
                </div>
            </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
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
                                <?php $i = 0;
                                foreach ($datadetail as $row) {
                                $i++;
                                //$checked = !empty($row->kasmasuk)?"checked":"";?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" type="text" class="form-control" id="irefferensi<?=$i;?>" name="irefferensi<?=$i?>" value="<?= $row->i_refferensi; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px" class="form-control" type="text" id="drefferensi<?=$i;?>" name="drefferensi<?=$i;?>" value="<?= $row->d_refferensi; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:100px" class="form-control" type="text" id="vnilai<?=$i;?>" name="vnilai<?=$i;?>" value="<?= number_format($row->v_nilai,0); ?>" readonly>
                                </td>
                                <td>
                                    <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>" value="<?= $row->e_desc; ?>">
                                </td>
                                <td class="col-sm-1">
                                    <!-- <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" <?php echo $checked ?>> -->
                                </td>
                                </tr>
                                <?}?>
                                <!-- <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> -->
                            </tbody>
                        </table>
                    </div>
                </form>
            <div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getenabledcancel() {
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledsend() {
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#addrow').attr("disabled", true);
}

function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    $('#nquantity'+counter).val(vjumlah);

}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var ikasbankkeluar = $("#ikasbankkeluar").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'ikasbankkeluar'  : ikasbankkeluar,
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
        var ikasbankkeluar = $("#ikasbankkeluar").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'ikasbankkeluar'  : ikasbankkeluar,
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

$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
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

// function customer(ikasbank){
//     var icustomer = $('#icustomer').val();
//     var ikasbank  = $('#ikasbank').val();
//     $.ajax({
//         type: "POST",
//         url: "<?php echo site_url($folder.'/Cform/customer');?>",
//         data:"ikasbank="+ikasbank,
//         dataType: 'json',
//         success: function(data){
//             $("#icustomer").html(data.kop);
//             getcustomer('ALCUS');
//             if (data.kosong=='kopong') {
//                 $("#submit").attr("disabled", true);
//             }else{
//                   $("#icustomer").attr("disabled", false);
//                     if(ikasbank == 'KAS0001'){
//                         $("#ibank").attr("disabled", false);
//                         $("#submit").attr("disabled", false);
//                     }
//             }
//         },

//         error:function(XMLHttpRequest){
//             alert(XMLHttpRequest.responseText);
//         }

//     })
// }

// function getcustomer(icustomer) {
   
//     var icustomer = $('#icustomer').val();
//     $.ajax({
//         type: "post",
//         data: {
//             'icustomer': icustomer,
//         },
//         url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
//         dataType: "json",
//         success: function (data) {  
//             $('#jml').val(data['dataitem'].length);
//             $("#tabledata tbody").remove();
//             $("#tabledata").attr("hidden", false);
//             for (let a = 0; a < data['dataitem'].length; a++) {
//                 var no = a+1;
//                 var icustomer  = data['dataitem'][a]['i_customer']
//                 var ecustomer  = data['dataitem'][a]['e_customer_name'];
                
//                 var cols        = "";
//                 var newRow = $("<tr>");
                
//                 cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
//                  cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="icustomer'+no+'" name="icustomer'+no+'" value="'+icustomer+'"></td>';
//                 cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="ecustomer'+no+'" name="ecustomer'+no+'" value="'+ecustomer+'"></td>'; 
//                 cols += '<td><input style="width:200px;" class="form-control" type="text" id="vnilai'+no+'" name="vnilai'+no+'" value=""  onkeyup="cekval(this.value); reformat(this);"></td>'; 
//                 cols += '<td><input style="width:400px;" class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value=""></td>';
//                 cols +='<td><input type="checkbox" name="cek'+no+'" value="cek" id="cek'+no+'"></td>';
               
//             newRow.append(cols);
//             $("#tabledata").append(newRow);
//             }
//         },
//         error: function () {
//             alert('Error :)');
//         }
//     });
// } 

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
   
    // if (textinputs.length == empty.length) {
    //     swal("Pelanggan Belum dipilih !!");
    //     return false;
    // }else if(ikasbank == '' || ikasbank == null){
    //     swal("Data Masih Kosong");
    //     return false;
    // }else{
    //     return true;
    // }
    if(ikasbank == '' || ikasbank == null){
        swal("Data Masih Kosong");
        return false;
    }else{
        return true;
    }
}    


</script>