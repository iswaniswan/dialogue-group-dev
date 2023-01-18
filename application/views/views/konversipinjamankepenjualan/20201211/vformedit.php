<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Bagian</label>
                        <label class="col-md-6">No Konversi </label>                        
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="" onchange="getstore();">
                                <option value="" selected>-- Pilih Bagian --</option>
                                <?php foreach ($gudang as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_departement == $head->i_bagian) { ?>
                                    <option value="<?php echo $ikodemaster->i_departement;?>" selected><?= $ikodemaster->e_departement_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_departement;?>"><?= $ikodemaster->e_departement_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_bagian?>">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" id="nokonversi" name="nokonversi" class="form-control" value="<?php echo $head->i_konversi?>" readonly>
                        </div>
                       
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">No Pengeluaran Pinjaman</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="isjkp" name="isjkp" class="form-control" value="<?php echo $head->i_referensi?>" readonly>
                              <select name="isjkp" id="isjkp" class="form-control select2" disabled="">
                                <option value="" selected>Pilih No Pengeluaran Pinjaman</option>
                                <?php foreach ($referensi as $isjkp):?>
                                    <?php if ($isjkp->i_bonmk == $head->i_referensi) { ?>
                                    <option value="<?php echo $isjkp->i_bonmk;?>" selected><?= $isjkp->i_bonmk." || ".$isjkp->d_bonmk;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $isjkp->i_bonmk;?>"><?= $isjkp->i_bonmk." || ".$isjkp->d_bonmk;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="dreferensi" name="dreferensi" class="form-control" value="<?php echo $head->d_referensi?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                    <?if($head->i_status =='1'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>
                        <?}else if($head->i_status =='2'){?>
                         <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>
                        <?}else if($head->i_status =='3'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>
                        <?}else if($head->i_status =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd"  class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>
                        <?}else{?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>                       
                        <?}?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal</label>
                        <label class="col-md-8">Partner</label>
                         <div class="col-sm-4">
                            <input type="text" id="dkonversi" name="dkonversi" class="form-control date" value="<?php echo $head->d_konversi?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <select name="ipartner" id="ipartner" class="form-control select2">
                                <option value="" selected>-- Pilih Partner --</option>
                                <?php foreach ($partner as $ipartner):?>
                                    <?php if ($ipartner->i_supplier == $head->i_partner) { ?>
                                    <option value="<?php echo $ipartner->i_supplier;?>" selected><?= $ipartner->e_supplier_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ipartner->i_supplier;?>"><?= $ipartner->e_supplier_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark?>">
                        </div>
                    </div>                  
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="35%">Nama Barang</th>
                                    <th>Qty Pinjaman Awal</th>
                                    <th>Qty Outstanding</th>
                                    <th>Qty Konversi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?$i = 0;
                                foreach ($datadetail as $row) {
                                    $i++;?>
                                    <tr>
                                    <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                    </td>
                                    <td class="col-sm-1">
                                        <input style ="width:160px" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_product; ?>"  readonly >
                                    </td>
                                    <td class="col-sm-1">
                                        <input style ="width:400px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" class="form-control" readonly >
                                    </td>                   
                                    <td class="col-sm-1">
                                        <input style ="width:80px" class="form-control" type="text" id="nqtyawal<?=$i;?>" name="nqtyawal[]"value="<?= number_format($row->n_quantity_awal,0); ?>" readonly>
                                    </td>
                                    <td class="col-sm-1">
                                        <input style ="width:80px" class="form-control" type="text" id="nqtyout<?=$i;?>" name="nqtyout[]"value="<?= number_format($row->n_quantity_outstanding,0); ?>" readonly>
                                    </td>
                                    <td class="col-sm-1">
                                        <input style ="width:80px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]"value="<?= number_format($row->n_quantity_konversi,0); ?>"onkeyup="validasi(); reformat(this);">
                                    </td>
                                    <td class="col-sm-1">
                                        <input class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan[]"value="<?= $row->i_satuan; ?>" >
                                        <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]"value="<?= $row->e_remark; ?>" >
                                    </td>
                                    </tr>
                                <?}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> 
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
    max_tgl();
});

function getstore() {
    var gudang = $('#ikodemaster').val();
    //alert(gudang);
    if (gudang == "") {

    } else {
        $('#istore').val(gudang);
        $("#ikodemaster").attr("disabled", true);
    }
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#cancel").attr("disabled", true);
    $("#sendd").attr("disabled", true);
});

function getenabledcancel() {
    swal("Berhasil", "Cancel Dokumen", "success");
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
}

function getenabledsend() {
    swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var nokonversi = $("#nokonversi").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'nokonversi'  : nokonversi,
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
        var nokonversi = $("#nokonversi").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'nokonversi'  : nokonversi,
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

function max_tgl() {
  $('#dkonversi').datepicker('destroy');
  $('#dkonversi').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dreferensi').value,
  });
}
$('#dkonversi').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dreferensi').value,
});

function validasi(){
    //alert("tes");
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qtypp   =document.getElementById("nqtyawal"+i).value;
        qtypm =document.getElementById("nquantity"+i).value;
        if(parseFloat(qtypm)>parseFloat(qtypp)){
            swal('Jumlah Quantity Melebihi Quantity Pinjaman');
            document.getElementById("nquantity"+i).value='';
            break;
        }else if(parseFloat(qtypm)=='0'){
            swal('Jumlah Quantity tidak boleh kosong')
            document.getElementById("nquantity"+i).value='';
            break;
        }
    }
}

function cek() {
    var dkonversi = $('#dkonversi').val();
    var isjkm = $('#isjkp').val();
    var istore = $('#istore').val();
    var jml = $('#jml').val();

    if (dkonversi == '' || isjkm == null || istore == '' ) {
        swal('Data Header Belum Lengkap !!');
        return false;
    }else{
        for (i=1; i<=jml; i++){  
            if($("#nquantity"+i).val() == '' || $("#nquantity"+i).val() == null){
                swal('Quantity Harus Diisi!');
                return false;                    
            } else {
                return true;
            } 
        }
    }
}
</script>