<?php

$data = $proses->row();
?>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
           
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-4">Supplier</label>
                    <label class="col-md-4">Kategori Barang</label>
                    <label class="col-md-4">Jenis Barang</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="isupplier" id="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->i_supplier;?>" readonly> 
                        <input type="text" name="esuppliername" id="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->i_supplier." - ".$datasup->e_supplier_name;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="hidden" name="ikodekelompok" id="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?php echo $data->i_kode_kelompok;?>" readonly>
                         <input type="text" name="ekodekelompok" id="ekodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?php echo $data->i_kode_kelompok." - ".$data->e_nama_kelompok;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                        <input type="text" name="ekodejenis" id="ekodejenis" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_type_code."-".$data->e_type_name; ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return validasi();"> <i class="fa fa-save mr-2"></i>Simpan</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick='show("<?= $folder;?>/cform/tambah","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="white-box">
        <div class="panel-body table-responsive">
            <div class="form-group">
                <span style="color: #8B0000"><b>Note : </b> Harap mengisi harga terlebih dahulu sebelum memilih satuan dari supplier.</span>
            </div>
            <table id="myTable" class="table color-table inverse-table table-bordered" cellspacing="0"  width="100%">
                <thead>
                    <tr>  
                        <th style="text-align:center;">No</th>                          
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">PPN</th>
                        <th style="text-align:center;">Harga</th>
                        <th style="text-align:center;">Satuan dari Supplier</th>                           
                        <th style="text-align:center;">Harga Konversi</th> 
                        <th style="text-align:center;">Satuan Konversi</th>                           
                        <th style="text-align:center;">Minimal Order</th>
                        <th style="text-align:center;">Tgl Berlaku</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    foreach ($proses->result() as $row) {
                    $i++;?>
                    <tr>
                    <td style="text-align: center;"><?= $i;?>
                        <input type="hidden" class="form-control input-sm" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                    </td>
                    <td class="col-sm-1">
                        <input style="width:150px;"  type="text" id="kodebrg<?=$i;?>" class="form-control input-sm" name="kodebrg<?=$i;?>"value="<?= $row->i_material; ?>"readonly >
                    </td>
                    <td class="col-sm-1">
                        <input style="width:400px;"  type="text" id="namabrg<?=$i;?>" class="form-control input-sm" name="namabrg<?=$i;?>"value="<?= $row->e_material_name; ?>"readonly>
                    </td>
                    <td class="col-sm-1">                          
                        <input style="width:150px;" type="hidden" id="ippn<?=$i;?>" class="form-control input-sm" name="ippn<?=$i;?>" value="<?= $datasup->i_type_pajak; ?>" readonly>
                        <input style="width:150px;" type="text" id="eppn<?=$i;?>" class="form-control input-sm" name="eppn<?=$i;?>" value="<?= $datasup->e_type_pajak_name; ?>" readonly>
                    </td>
                    <td class="col-sm-1">
                        <input style="width:200px;"  type="text" id="harga<?=$i;?>" class="form-control input-sm" name="harga<?=$i;?>" value="" placeholder="0" onkeyup="angkahungkul(this);">
                    </td>
                    <td class="col-sm-2">                            
                        <select style="width:200px;" type="text"  class="form-control input-sm select2" name="isatuansupplier<?=$i;?>" id="isatuansupplier<?=$i;?>" onchange="konversi(this.value,<?=$i;?>);">
                            <option value="">Pilih Satuan</option>
                            <?php foreach ($satuan as $key):?>
                                <option value="<?php echo $key->i_satuan_code;?>">
                                    <?php echo $key->e_satuan_name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="col-sm-1">
                      <input style="width:200px;"  type="text" id="hargakonversi<?=$i;?>" class="form-control input-sm" name="hargakonversi<?=$i;?>" value="" readonly>
                            <select name="konversiharga<?=$i;?>" id="konversiharga<?=$i;?>" style="display:none"> 
                            </select>
                            <select name="angkafaktor<?=$i;?>" id="angkafaktor<?=$i;?>" style="display:none"> 
                            </select>
                    </td>
                    <td class="col-sm-1">
                        <input type="hidden" id="isatuanperusahaan<?=$i;?>" class="form-control input-sm" name="isatuanperusahaan<?=$i;?>"value="<?php echo $row->i_satuan_code;?>"readonly>
                        <input type="hidden" id="satuanawal<?=$i;?>" class="form-control input-sm" name="satuanawal<?=$i;?>"value="<?php echo $row->i_satuan_code;?>"readonly>
                       <input style="width:200px;"  type="text" id="esatuankonversi<?=$i;?>" class="form-control input-sm" name="esatuankonversi<?=$i;?>"value="<?php echo $row->e_satuan_name;?>"readonly>
                    </td>
                    <td class="col-sm-1">
                        <input style="width:150px;"  type="text" id="norder<?=$i;?>" class="form-control input-sm" name="norder<?=$i;?>" value="" placeholder="0" >
                    </td>
                    <td class="col-sm-1">
                        <input style="width:150px;"  type="text" id="dberlaku<?=$i;?>" class="form-control input-sm date" name="dberlaku<?=$i;?>"value=""readonly >
                    </td>
                    <td style="width:2%;">
                            <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>">
                        </td> 
                    </tr>

                    <?}?>
                </tbody>
            </table>

            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>" readonly>    
        </div>
        
    </div>
</div>
</form>
<script>


$(document).ready( function () {
    $('#myTable').DataTable({
         columnDefs: [{
            orderable: false,
            targets: [1,2,3]
        }]
    });

    $('.select2').select2();
    showCalendar('.date');    

    $('#isatuanawal').select2({
        placeholder: 'Pilih Satuan',
    });
});

$('.dataTables_paginate').on('click', function() {
    $('.select2').select2();
    showCalendar('.date');
});

$("#harga").blur(function(){
    $("#isatuansupplier").attr("disabled",false);
});


$("form").submit(function (event) {
    event.preventDefault();
    //$("input").attr("disabled", true);
    // $("select").attr("disabled", true);
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
    var satuan_awal    = $('#isatuansupplier'+i).val();
    var satuan_akhir   = $('#isatuanperusahaan'+i).val();
    var harga          = formatulang($('#harga'+i).val());
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
    var satuan_awal = $('#isatuansupplier'+i).val();
    var satuan_akhir = $('#isatuanperusahaan'+i).val();
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
    var satuan_awal   = $('#isatuansupplier'+i).val();
    var satuan_akhir  = $('#isatuanperusahaan'+i).val();
    var harga         = formatulang($('#harga'+i).val());
    var konversiharga = $('#konversiharga'+i).val();
    var angkafaktor   = $('#angkafaktor'+i).val();
    var ppn           = $('#ippn'+i).val();
    // alert(formatcemua(harga/5));
    if(ppn == 'I'){
        if(konversiharga == '1'){
            total=harga*angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else if(konversiharga == '2'){
            total=harga/angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else if(konversiharga == '3'){
            total=harga+angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else if(konversiharga == '4'){
            total=harga-angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else{
            swal("Rumus Tidak Tersedia");
        }
    }else if(ppn == 'E'){
        if(konversiharga == '1'){
            total=harga*angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else if(konversiharga == '2'){
            total=harga/angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else if(konversiharga == '3'){
            total=harga+angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else if(konversiharga == '4'){
            total=harga-angkafaktor;
            document.getElementById('hargakonversi'+i).value = (total);
        }else{
            swal("Rumus Tidak Tersedia");
        } 
    }
}

function validasi(){
    var s=0;
    var jml = document.getElementById("jml").value;
    var maxpil = 1;
    var jml = $("input[type=checkbox]:checked").length;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
   
    if (textinputs.length == empty.length) {
        swal("Barang Belum dipilih !!");
        return false;
    }

    for (i=1; i<=jml; i++){  
        if($('#cek'+i).val().checked== on){
            if($("#dberlaku"+i).val() == '' || $("#dberlaku"+i).val() == null || $("#isatuansupplier"+i).val() == '' || $("#isatuansupplier"+i).val() == null || $("#harga"+i).val() == '' || $("#harga"+i).val() == null){
                swal('Data Item Belum Lengkap!');
                return false;                    
            } else {
                return true;
            } 
        }
    }
}    
</script>
