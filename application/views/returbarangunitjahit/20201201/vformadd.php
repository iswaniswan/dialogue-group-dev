<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-7">Gudang</label>
                        <label class="col-md-5">Tanggal Retur</label>
                        <div class="col-sm-7">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dretur" name="dretur" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden="true"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tujuan</label>
                        <label class="col-md-8">Referensi</label>
                        <div class="col-sm-4">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="return changereferensi(this.value);" >
                            </select>
                        </div>
                        <!-- <div class="col-sm-2">
                            <select name="noreff" id="noreff" class="form-control select2" onchange="return changereferensi(this.value);" >
                                <option value="">Pilih</option>
                                <option value="1">Ya</option>
                                <option value="2">Tidak</option> 
                            </select>
                        </div>  -->
                        <div class="col-sm-8">
                            <select name="ireffo" id="ireffo" class="form-control select2" disabled="" onchange="return getdatareferensi();">
                            </select>
                           <!-- <input type="text" id="ireffm" name="ireffm" class="form-control" value="" disabled="" placeholder="Isi referensi" onkeyup="change();">  -->
                        </div>
                    </div>   
                </div>
                <input type="hidden" name="jml" id="jml" readonly>              
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang WIP</th>
                                    <th>Nama barang WIP</th>
                                    <th>Warna</th>
                                    <th>Kode Barang Jadi</th>
                                    <th>Quantity Retur</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});


$(document).ready(function () {
    $('#ibagian').select2({
    placeholder: 'Pilih Gudang',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
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
    $('#itujuan').select2({
    placeholder: 'Pilih Tujuan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/tujuan'); ?>',
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
    $('#ireffo').select2({
    placeholder: 'Pilih Referensi',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/getreferensi'); ?>',
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

function changereferensi(){
    var adareferensi   = $('#noreff').val();

    // if(adareferensi == '1'){
        $("#ireffo").attr("disabled", false);
        $("#ireffm").attr("disabled", true);
        $("#addrow").attr("hidden", true);
    // }else{
    //     $("#ireffm").attr("disabled", false);
    //     $("#ireffo").attr("disabled", true);
    //     $("#addrow").attr("hidden", false);
    // }
}

function change(){
     $("#addrow").attr("hidden", false);
}

function getdatareferensi(){
    var referensi   = $('#ireffo').val();
    $.ajax({
        type: "post",
        data: {
            'referensi': referensi,
        },
        url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            for (let no = 0; no < data['dataitem'].length; no++) {
                var a = no+1;
                var isj          = data['dataitem'][no]['i_sj'];
                var iproductwip  = data['dataitem'][no]['i_wip'];
                var eproduct     = data['dataitem'][no]['e_namabrg'];
                var brgjadi      = data['dataitem'][no]['i_product'];
                var icolor       = data['dataitem'][no]['i_color'];
                var ecolor       = data['dataitem'][no]['e_color_name'];
                var quantity     = data['dataitem'][no]['n_quantity'];
                var cols         = "";

                 var x = $('#jml').val();
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+a+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+a+'"></td>';     
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="iproductwip'+a+'" name="iproductwip'+a+'" value="'+iproductwip+'"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproduct'+a+'" name="eproduct'+a+'" value="'+eproduct+'"></td>'; 
                cols += '<td><input style="width:40px;"  type="hidden" id="icolor'+a+'" name="icolor'+a+'" value="'+icolor+'"><input style="width:140px;" class="form-control" type="text" id="ecolor'+a+'" readonly name="ecolor'+a+'" value="'+ecolor+'"></td>';
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="brgjadi'+a+'" name="brgjadi'+a+'" value="'+brgjadi+'"></td>';
                cols += '<td><input type="text" id="nquantity'+a+ '" style="width:100px;"class="form-control" name="nquantity'+a+ '" value="0" onkeyup="cekquantity('+a+')"><input type="text" id="quantity'+a+ '" style="width:100px;"class="form-control" name="quantity'+a+ '" value="'+quantity+'"></td>';
                cols += '<td><input style="width:200px;" type="text" id="edesc'+a+ '" class="form-control" name="edesc' + a + '"></td>';
                cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" ></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        }
    });
     xx = $('#jml').val();
}  

function validasi(){
    var gudang   = $('#ibagian').val();
    var itujuan  = $('#itujuan').val();
    var noreff   = $('#noreff').val();
    var ireffo   = $('#ireffo').val();
    var ireffm   = $('#ireffm').val();
    var jml = $('#jml').val();
    //alert(jml);

    for (i=0; i<=jml; i++){
        edesc = $('#edesc'+i).val();
        cek   = $('#cek'+i).val();
        if (gudang == '' || gudang == null || itujuan == '' || itujuan == null) {
            swal('Data header Belum Lengkap');
            return false;
        }else if(ireffo == '' || ireffo == null){
            swal('No Referensi Belum Dipilih');
            return false;
        }else if(cek.length == empty.length){
            swal("Barang Belum dipilih");
            return false;
            if(cek == 'cek'){
                if(edesc == '' || edesc == null){
                    swal("Keterangan harus diisi");
                    return false; 
                }
            } 
        }else {
            return true;
        }
    }
}

function cekquantity(id){
    var nquantity = $('#nquantity'+id).val();
    var quantity  = $('#quantity'+id).val();
//alert(quantity);
    if(nquantity > quantity){
        swal("Quantity lebih dari Quantity Terima");
        document.getElementById("nquantity"+id).value="";
    }
}
</script>