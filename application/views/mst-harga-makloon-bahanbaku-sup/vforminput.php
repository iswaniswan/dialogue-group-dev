<?php

$data = $proses->row();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-12">Supplier</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="idsupplier" id="idsupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->id_supplier;?>" readonly> 
                        <input type="text" name="isupplierr" id="isupplierr" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->i_supplier." - ".$datasup->e_supplier_name;?>" readonly> 
                        <input type="hidden" name="ikodekelompok" id="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?php echo $data->i_kode_kelompok;?>" readonly>
                        <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                        <input type="hidden" name="igroupbrg" name="igroupbrg" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_group_barang; ?>"readonly>
                        <input type="hidden" name="itypemakloon" name="itypemakloon" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->i_type_makloon; ?>"readonly>
                        <input type="hidden" name="idmakloon" name="idmakloon" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->id_type_makloon; ?>"readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                </div>
            </div>
            <div class="panel-body table-responsive">
                <table id="myTable"  class="table color-table inverse-table table-bordered" cellspacing="0"  width="100%">
                    <thead>
                        <tr>  
                            <th>No</th>                          
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Include PPN</th>
                            <th>Harga Eks</th>
                            <th>Satuan Eks</th>   
                            <th>Harga Int</th>
                            <th>Satuan Int</th>   
                            <th>Tanggal Berlaku</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?$i = 0;
                        foreach ($proses->result() as $row) {
                        $i++;?>
                        <tr>
                        <td style="text-align: center;"><?= $i;?>
                            <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                        </td>
                        <td>
                            <input style="width:120px;" type="text" id="kodebrg<?=$i;?>" class="form-control" name="kodebrg<?=$i;?>"value="<?= $row->i_product; ?>"readonly >
                            <input style="width:120px;" type="hidden" id="idkodebrg<?=$i;?>" class="form-control" name="idkodebrg<?=$i;?>"value="<?= $row->id_product; ?>"readonly >
                        </td>
                        <td>
                            <input style="width:350px;" type="text" id="namabrg<?=$i;?>" class="form-control" name="namabrg<?=$i;?>"value="<?= $row->e_product_name; ?>"readonly>
                        </td>
                        <td>    
                            <?php if($datasup->f_pkp == 't'){?>                        
                                <select style="width:150px;" type="text" id="ippn<?=$i;?>" class="form-control select2" name="ippn<?=$i;?>" >
                                    <option value="I" selected="true">Ya</option>
                                </select>
                            <?php }else{?>
                                <select style="width:150px;" type="text" id="ippn<?=$i;?>" class="form-control select2" name="ippn<?=$i;?>" >
                                    <option value="">Pilih Include PPN</option>
                                    <option value="I">Ya</option>
                                    <option value="E">Tidak</option> 
                                </select>
                            <?php }?>
                        </td>
                        <td>
                            <input style="width:120px;" type="text" id="hargaeks<?=$i;?>" class="form-control" name="hargaeks<?=$i;?>" value="" onkeypress="return angkahungkul(event)">
                            <select name="konversiharga<?=$i;?>" id="konversiharga<?=$i;?>" style="display:none"> 
                            </select>
                            <select name="angkafaktor<?=$i;?>" id="angkafaktor<?=$i;?>" style="display:none"> 
                            </select>
                        </td>
                        <td>                             
                            <select type="text" style="width:120px;" class="form-control select2" name="isatuaneks<?=$i;?>" id="isatuaneks<?=$i;?>" onchange="konversi(this.value,<?=$i;?>);">
                                <option value="">Pilih Satuan</option>
                                <?php foreach ($satuan as $isatuan):?>
                                    <option value="<?php echo $isatuan->i_satuan_code;?>">
                                        <?php echo $isatuan->e_satuan_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" style="width:120px;" id="hargaint<?=$i;?>" class="form-control" name="hargaint<?=$i;?>"value="" readonly>
                        </td>
                        <td>                            
                            <input type="hidden" style="width:120px;" id="isatuanint<?=$i;?>" name="isatuanint<?=$i?>" class="form-control" value="<?= $row->i_satuan_code; ?>" readonly>
                            <input type="text" style="width:120px;" id="esatuanint<?=$i;?>" name="esatuanint<?=$i;?>" class="form-control" value="<?= $row->e_satuan_name; ?>" readonly>
                        </td>
                        <td>
                            <input type="text" style="width:120px;" id="dberlaku<?=$i;?>" class="form-control date" name="dberlaku<?=$i;?>"value=""readonly >
                        </td>
                        <td style="width:2%; text-align: center;">
                                <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>">
                            </td> 
                        </tr>
                        <?}?>
                    </tbody>
                </table>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>"readonly>
            </div> 
        </div>
        </form>
    </div>
</div>
<script>


$(document).ready( function () {
    $('#myTable').DataTable();
    $('.select2').select2();
    showCalendar('.date');    
});

$('.dataTables_paginate').on('click', function() {
    $('.select2').select2();
     showCalendar('.date');
// alert('toggled');
});


$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function hanyaAngka(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))

    return false;
  return true;
}

function konversi(a,b){
    var i = b;
    var satuan_awal    = $('#isatuaneks'+i).val();
    var satuan_akhir   = $('#isatuanint'+i).val();
    var harga          = $('#hargaeks'+i).val();
    var konversiharga  = $('#konversiharga'+i).val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getrumus');?>",
        data:{
            'satuan_awal': satuan_awal,
            'satuan_akhir': satuan_akhir,
            'harga': harga,
        },
        dataType: 'json',
        success: function(data){
            $('#konversiharga'+i).html(data.kop);
            if (data.kosong=='kopong') {
                swal("konversi satuan kosong");
            }else {
                ngetangfaktor(b);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
}

function ngetangfaktor(b) {
    var i = b;
    var satuan_awal = $('#isatuaneks'+i).val();
    var satuan_akhir = $('#isatuanint'+i).val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getrumusfaktor');?>",
        data:{
            'satuan_awal': satuan_awal,
            'satuan_akhir': satuan_akhir,
        },
        dataType: 'json',
        success: function(data){
            $('#angkafaktor'+i).html(data.kop);
            if (data.kosong=='kopong') {
                swal("Rumus Tidak Tersedia");
            }else {
                hitunghargakonversi(b);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
}

function hitunghargakonversi(b){
    var i = b;
    var satuan_awal   = $('#isatuaneks'+i).val();
    var satuan_akhir  = $('#isatuanint'+i).val();
    var harga         = $('#hargaeks'+i).val();
    var konversiharga = $('#konversiharga'+i).val();
    var angkafaktor   = $('#angkafaktor'+i).val();

    if(konversiharga == '1'){
        total=harga*angkafaktor;
        document.getElementById('hargaint'+i).value = (total);
    }else if(konversiharga == '2'){
        total=harga/angkafaktor;
        document.getElementById('hargaint'+i).value = (total);
    }else if(konversiharga == '3'){
        total=harga+angkafaktor;
        document.getElementById('hargaint'+i).value = (total);
    }else if(konversiharga == '4'){
        total=harga-angkafaktor;
        document.getElementById('hargaint'+i).value = (total);
    }else{
        swal("Rumus Tidak Tersedia");
    }
}

function validasi(){
    var s=0;
    var i = document.getElementById("jml").value;
    var maxpil = 1;
    var jml = $("input[type=checkbox]:checked").length;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
   
    if (textinputs.length == empty.length) {
        swal("Barang Belum dipilih !!");
        return false;
    }else{
        return true;
    }

    for (i=1; i<=jml; i++){  
        if($('#cek'+i).val()== "cek"){
            if($("#dberlaku"+i).val() == '' || $("#dberlaku"+i).val() == null || $("#ippn"+i).val() == '' || $("#ippn"+i).val() == null || $("#hargaeks"+i).val() == '' || $("#hargaeks"+i).val() == null){
                swal('Data Item Belum Lengkap!');
                return false;                    
            } else {
                return true;
            } 
        }
    }
}    
</script>
