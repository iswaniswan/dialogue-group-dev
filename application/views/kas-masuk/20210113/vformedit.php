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
                        <input class="form-control" name="ikasmasuk" id="ikasmasuk" readonly="" value="<?= $data->i_kas_masuk?>">
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
                        <input class="form-control date" name="dmasuk" id="dmasuk" readonly="" value="<?= $data->d_kas_masuk?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">Customer</label>
                    <label class="col-md-7">Nilai</label>
                    <div class="col-sm-5">
                        <select name="icustomer" id="icustomer" class="form-control select2" onchange="return getcustomer(this.value);">
                             <option value="" selected>Pilih Customer</option>
                            <?php foreach ($customer as $icustomer):?>
                            <?php if ($icustomer->i_customer == $data->i_customer) { ?>
                                <option value="<?php echo $icustomer->i_customer;?>" selected><?= $icustomer->e_customer_name;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" name="vnilai" id="vnilai" class="form-control" value="<?= number_format($data->n_nilai,0); ?>">
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
                    <label class="col-md-1"></label>
                    <label class="col-md-5">Kas/Bank</label>
                    <label class="col-md-6">Bank</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" onchange="return customer(this.value);">
                            <option value="" selected>Pilih Tujuan</option>
                            <?php foreach ($kasbank as $ikasbank):?>
                            <?php if ($ikasbank->i_kode_kas == $data->i_kas_bank) { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>" selected><?= $ikasbank->e_nama_kas;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>"><?= $ikasbank->e_nama_kas;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?if($data->i_bank == null){?>
                    <div class="col-sm-5">
                        <select name="ibank" id="ibank" class="form-control select2"  disabled="true">
                        </select>
                    </div>
                    <?}else{?>
                    <div class="col-md-6">
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
                <div class="from-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-11">Keterangan</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <input type="text" name="eremark" id="eremark" class="form-control" value="<?=$data->e_remark?>">
                    </div>
                </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
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
                                <?php $i = 0;
                                foreach ($datadetail as $row) {
                                $i++;
                                $checked = !empty($row->kasmasuk)?"checked":"";?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" type="text" class="form-control" id="icustomer<?=$i;?>" name="icustomer<?=$i?>" value="<?= $row->i_customer; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px" class="form-control" type="text" id="ecustomer<?=$i;?>" name="ecustomer<?=$i;?>" value="<?= $row->e_customer_name; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>" value="<?= $row->e_remark; ?>">
                                </td>
                                <td class="col-sm-1">
                                    <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" <?php echo $checked ?>>
                                </td>
                                </tr>
                                <?}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var ikasmasuk = $("#ikasmasuk").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'ikasmasuk'  : ikasmasuk,
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
        var ikasmasuk = $("#ikasmasuk").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'ikasmasuk'  : ikasmasuk,
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
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
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