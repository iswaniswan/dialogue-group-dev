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
                        <input type="hidden" name="isupplier" id="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->i_supplier;?>" readonly> 
                        <input type="text" name="isupplierr" id="isupplierr" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->i_supplier." - ".$datasup->e_supplier_name;?>" readonly> 

                        <input type="hidden" name="ikodekelompok" id="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?php echo $data->i_kelbrg_wip;?>" readonly>
                         <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_jenisbrg_wip; ?>" readonly>
                         <input type="hidden" name="igroupbrg" id="igroupbrg" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_group_barang; ?>" readonly>
                         <input type="hidden" name="itypemakloon" id="itypemakloon" class="form-control" required="" onkeyup="gede(this)" value="<?= $datasup->i_type_makloon; ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                </div>
            </div>
            <div class="panel-body table-responsive">
                <table id="myTable" class="table table-bordered" cellspacing="0"  width="100%">
                    <thead>
                        <tr>  
                            <th>No</th>                          
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Include PPN</th>
                            <th>Harga</th>
                            <th>Satuan</th>   
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
                        <td class="col-sm-1">
                            <input style="width:100px;"  type="text" id="kodebrg<?=$i;?>" class="form-control" name="kodebrg<?=$i;?>"value="<?= $row->i_kodebrg; ?>"readonly >
                        </td>
                        <td class="col-sm-1">
                            <input style="width:350px;"  type="text" id="namabrg<?=$i;?>" class="form-control" name="namabrg<?=$i;?>"value="<?= $row->e_namabrg; ?>"readonly>
                        </td>
                        <td class="col-sm-1">                            
                            <select style="width:100px;" type="text" id="itipe<?=$i;?>" class="form-control select2" name="itipe<?=$i;?>" >
                                <option value="">Pilih Include PPN</option>
                                <option value="I">Ya</option>
                                <option value="E">Tidak</option> 
                            </select>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px;"  type="text" id="harga<?=$i;?>" class="form-control" name="harga<?=$i;?>"value="" onkeypress="return hanyaAngka(event)">
                        </td>
                        <td class="col-sm-1">                            
                            <select style="width:100px;" type="text"  class="form-control select2" name="isatuan<?=$i;?>" id="isatuan<?=$i;?>">
                                <option value="">Pilih Satuan</option>
                                <?php foreach ($satuan as $isatuan):?>
                                    <option value="<?php echo $isatuan->i_satuan_code;?>">
                                        <?php echo $isatuan->i_satuan_code.'-'.$isatuan->e_satuan;?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:120px;"  type="text" id="dberlaku<?=$i;?>" class="form-control date" name="dberlaku<?=$i;?>"value=""readonly >
                        </td>
                        <td style="width:2%;">
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
// alert('toggled');
});


$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

// $(document).ready(function () {
//     var i = $('#jml').val();
//     $('#isatuan'+i).select2({

//     placeholder: 'Pilih Satuan',
//     allowClear: true,
//     type: "POST",
//     ajax: {
//       url: '<?= base_url($folder.'/cform/satuan'); ?>',
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

function hanyaAngka(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))

    return false;
  return true;
}

function konversi(a,b){
     //var i = $('#jml').val();
     var i = b;
     var satuan_awal = $('#isatuan'+i).val();
     var satuan_akhir = $('#satuan'+i).val();
     var harga = $('#harga'+i).val();
     var konversiharga = $('#konversiharga'+i).val();
   //alert(satuan_awal);
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
                    getangfaktor(b);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
    
}

function getangfaktor(b) {
        //var i = $('#jml').val();
        var i = b;
        var satuan_awal = $('#isatuan'+i).val();
        var satuan_akhir = $('#satuan'+i).val();
       $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getrumuss');?>",
            data:{
                'satuan_awal': satuan_awal,
                'satuan_akhir': satuan_akhir,
            },
            dataType: 'json',
            success: function(data){
                $('#angkafaktor'+i).html(data.kop);
                if (data.kosong=='kopong') {
                    alert("rumus kosong");
                }else {
                    cekkonversi(b);
                }
            },
            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
}

function cekkonversi(b){
    //var i = $('#jml').val();
    var i = b;
    var satuan_awal = $('#isatuan'+i).val();
    var satuan_akhir = $('#satuan'+i).val();
    var harga = $('#harga'+i).val();
    var konversiharga = $('#konversiharga'+i).val();
    var angkafaktor = $('#angkafaktor'+i).val();
    var tipe = $('#itipe'+i).val();
    // alert(tipe);

    if(tipe=='I'){
// alert(satuan_awal);
// alert(tipe);
        if(konversiharga == '1'){
            total=harga/1.1*angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else if(konversiharga == '2'){
            total=harga/1.1/angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else if(konversiharga == '3'){
            total=harga/1.1+angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else if(konversiharga == '4'){
            total=harga/1.1-angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else{
            swal("rumus kosong");
        }
    }else if(tipe=='E'){
        // alert(tipe);
        if(konversiharga == '1'){
            total=harga*angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else if(konversiharga == '2'){
            total=harga/angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else if(konversiharga == '3'){
            total=harga+angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else if(konversiharga == '4'){
            total=harga-angkafaktor;
            document.getElementById('hargakonversi'+i).value = formatcemua(total);
        }else{
            swal("rumus kosong");
        } 
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
}    
</script>
