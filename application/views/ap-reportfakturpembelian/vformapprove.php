<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
                <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve/'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div id="pesan"></div>  
            <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">No Faktur</label> 
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Tanggal Nota</label>
                        <div class="col-sm-4">
                            <input type="text" name="inota" id="inota" class="form-control" value="<?= $data->i_nota;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="isupplier" class="form-control" value="<?= $data->i_supplier;?>" readonly>
                            <input type="text" name="isupplierfake" class="form-control" value="<?= $data->e_supplier_name;?>"
                            readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dnota" class="form-control" value="<?= $data->d_nota;?>"
                            readonly>
                        </div>
                  </div>
                  <div class="form-group row">                    
                    <label class="col-md-8">Keterangan</label>
                    <label class="col-md-4">Diskon</label>
                    <div class="col-sm-8">
                      <input type="text" name="dfsupp" id="dfsupp" class="form-control date" value="<?= $data->e_remark;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="vdiskon" id="vdiskon" class="form-control" value="<?= $data->n_discount;?>" maxlength="3"
                        onkeypress="return angka(event)" onkeyup="hitungdiskon()">
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <label class="col-md-9">Keterangan</label>
                    <div class="col-sm-9">
                      <input type="text" name="eremark" id="eremark" class="form-control" value="<#?= $data->e_remark;?>">
                    </div>
                  </div> --->
                    <div class="form-group">
                    <div class="col-sm-offset-6 col-sm-12">
                        <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return getselisih();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                    
                        </div>
                  </div>
            </div>
                <div class="col-md-6">  
                  <div class="form-group row">
                    <label class="col-md-4">Nilai Kotor</label>
                    <label class="col-md-4">Total Discount</label>
                    <label class="col-md-4">Nilai Bersih</label>
                    <div class="col-sm-4">
                       <input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control" value="<?= $data->v_gross;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vtotalnet" id="vtotalnet" class="form-control" value="<?= $data->v_discount;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                       <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="<?= $data->v_netto;?>" readonly>
                    </div>
                  </div>                  
                </div>        
                <div class="panel-body table-responsive">      
                <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%" >
                        <thead>
                            <tr>
                                <th style width="5%">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Jumlah Total (Rp.)</th>                      
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($data1){
                                $i = 0;
                                foreach($data1 as $row){
                                // $checked = !empty($row->sjnota)?"checked":"";
                                    $i++;
                            ?>
                            <tr>   
                            <td>
                               <!--  -->
                               <input style="width:40px" class="form-control" type="text" id="no<?=$i;?>" name="no<?=$i;?>" value="<?php echo $i; ?>" readonly>
                            </td>                      
                             <td >  
                                <input style="width:100px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly>
                            </td>
                            <td >  
                                <input style="width:400px" class="form-control" type="text" id="ematerial<?=$i;?>" name="ematerial<?=$i;?>" value="<?= $row->e_material_name; ?>" readonly>
                            </td>
                            <td>
                                <input style="width:100px;" type="hidden" id="isatuaneks<?=$i;?>" class="form-control" name="isatuaneks<?=$i;?>" value="<?=$row->i_satuan_code;?>" readonly>
                                <input style="width:100px;" type="text" id="esatuaneks<?=$i;?>" class="form-control" name="esatuaneks<?=$i;?>" value="<?=$row->e_satuan;?>" readonly>
                            </td>
                            <td >  
                                <input style="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>"name="nquantity<?=$i;?>" value="<?php echo number_format($row->n_quantity,2); ?>" readonly>
                            </td>
                            <td>
                                <input style="width:100px" class="form-control" type="text" id="vharga<?=$i;?>" name="vharga<?=$i;?>" value="<?= $row->v_price; ?>" readonly>
                            </td>
                            <td>
                                <input style="width:100px" class="form-control" type="text" id="vdpp<?=$i;?>" name="vdpp<?=$i;?>" value="<?= $row->v_tot?>" readonly>
                            </td>
                            <!-- <td style="width:2%;">
                                <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" onclick="hitungnilai(<?php echo $i ?>)" <?php echo $checked ?>>
                            </td>   -->                   
                            </tr>    
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            <?}
                            }?>           
                        </tbody>                         
                    </table>
                </form>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<script>
 $(document).ready(function () {
    $(".select2").select2();
 });

 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#sendd").attr("disabled", false);
});

function getenabledsend() {
    $('#sendd').attr("disabled", true);
    $('#submit').attr("disabled", true);
}
function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Change Request');
}

function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Reject');
}


$(document).ready(function(){
    $("#sendd").on("click", function () {
        var inota = $("#inota").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'inota'  : inota,
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

$('#change').attr("disabled", false);
    $("#change").on("click", function () {
        var kode = $("#inota").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
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

    $("#reject").on("click", function () {
        var kode = $("#inota").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
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


 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function max_tgl(val) {
  $('#dpajak').datepicker('destroy');
  $('#dpajak').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dnota').value,
  });
}
$('#dpajak').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dnota').value,
});

function hitungnilai(i){
    var totfak = formatulang(document.getElementById('vtotalfa').value);
    if(document.getElementById('cek'+i).checked==true){
        var nilaisj = document.getElementById('vtotal'+i).value;
        totakhir = parseFloat(totfak)+parseFloat(nilaisj);
        
    } else {
        var nilaisj = document.getElementById('vtotal'+i).value;
        totakhir = parseFloat(totfak)-parseFloat(nilaisj);
    }
    document.getElementById('vtotalfa').value = formatcemua(totakhir);
}

function validasi(){
    var s=0;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
    if (textinputs.length == empty.length) {
        alert("Maaf Tolong Pilih Minimal 1 SJ!");
        return false;
    } else if(document.getElementById('dnota').value==''){
        alert("Maaf Tolong Pilih Tanggal Faktur");
        return false;
    } else if(document.getElementById('dpajak').value==''){
        alert("Maaf Tolong Pilih Tanggal Pajak");
        return false;
    }else {
        return true
    }
  }
</script>
