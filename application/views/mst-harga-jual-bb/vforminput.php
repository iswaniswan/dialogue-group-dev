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
                    <label class="col-md-4">Kategori Barang</label>
                    <label class="col-md-8">Jenis Barang</label>
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
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        &nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/tambah","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                    </div>
                </div>
            </div>
            <div class="panel-body table-responsive">
            <div class="form-group">
                <span style="color: #8B0000"><b>Note : </b> Harap mengisi harga terlebih dahulu</span>
            </div>
                <table id="myTable" class="table color-table inverse-table table-bordered" cellspacing="0"  width="100%">
                    <thead>
                        <tr>  
                            <th style="text-align:center;">No</th>                          
                            <th style="text-align:center;">Kode Barang</th>
                            <th style="text-align:center;">Nama Barang</th>
                            <th style="text-align:center;">Kode Harga</th>   
                            <th style="text-align:center;">Harga</th>                        
                            <th style="text-align:center;">Tgl Berlaku</th>
                            <th style="text-align:center;">Action</th>
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
                            <input style="width:150px;" type="hidden" id="kodebrg<?=$i;?>" class="form-control" name="kodebrg<?=$i;?>"value="<?= $row->id_material; ?>"readonly >
                            <input style="width:150px;"  type="text" id="ikodebrg<?=$i;?>" class="form-control" name="ikodebrg<?=$i;?>"value="<?= $row->i_material; ?>"readonly >
                        </td>
                        <td class="col-sm-1">
                            <input style="width:400px;" type="text" id="namabrg<?=$i;?>" class="form-control" name="namabrg<?=$i;?>"value="<?= $row->e_material_name; ?>"readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:150px;" type="hidden" id="ikodeharga<?=$i;?>" class="form-control" name="ikodeharga<?=$i;?>"value="<?= $row->id_harga_kode; ?>"readonly>
                            <input style="width:150px;" type="text" id="ekodeharga<?=$i;?>" class="form-control" name="ekodeharga<?=$i;?>"value="<?= $row->e_harga; ?>"readonly>
                        </td>  
                        <td class="col-sm-1">
                            <input style="width:200px;"  type="text" id="harga<?=$i;?>" class="form-control" name="harga<?=$i;?>" value="" placeholder="0" onkeypress="return hanyaAngka(event)">
                        </td>                                             
                        <td class="col-sm-1">
                            <input style="width:150px;"  type="text" id="dberlaku<?=$i;?>" class="form-control date" name="dberlaku<?=$i;?>"value=""readonly >
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
    showCalendar('.date'); 
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
        if($('#cek'+i).val()== "cek"){
            if($("#dberlaku"+i).val() == '' || $("#dberlaku"+i).val() == null || $("#ikodeharga"+i).val() == '' || $("#ikodeharga"+i).val() == null || $("#harga"+i).val() == '' || $("#harga"+i).val() == null){
                swal('Data Item Belum Lengkap!');
                return false;                    
            } else {
                return true;
            } 
        }
    }
}        
</script>
