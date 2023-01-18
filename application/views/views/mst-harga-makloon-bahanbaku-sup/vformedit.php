<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label> 
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label> 
                        <div class="col-sm-3">
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="hidden" id="idsupplier" name="idsupplier" class="form-control" value="<?= $data->id_supplier; ?>" readonly>
                            <input type="text" name="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" name="dfrom" id="dfrom" class="form-control" value="<?= date("Y-m-d",strtotime($dfrom));; ?>" readonly>
                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->id_harga; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="idkodebrg" id="idkodebrg" class="form-control" required="" value="<?= $data->id_product; ?>" readonly>
                            <input type="text" name="kodebrg" id="kodebrg" class="form-control" required="" value="<?= $data->i_product; ?>" readonly>
                        </div>                         
                        <div class="col-sm-6">
                            <input type="text" name="namabrg" id="namabrg" class="form-control" required="" value="<?= $data->e_product; ?>"readonly>
                        </div>      
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Kategori Barang</label>  
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-3">Tanggal Berlaku</label>
                        <label class="col-md-3">Include PPN</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodekelompok" id="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>"readonly>
                             <input type="text" name="enamakelompok" id="enamakelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_nama_kelompok; ?>"readonly>
                             <input type="hidden" name="igroupbrg" id="igroupbrg" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_group_barang; ?>"readonly>
                             <input type="hidden" id="idmakloon" name="idmakloon" value="<?=$data->id_type_makloon;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ikodejenis" id="ikodejenis" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                             <input type="text" name="dberlaku" id="dberlaku" class="form-control date" readonly value="<?= date("d-m-Y",strtotime($data->d_berlaku)); ?>">
                             <input type="hidden" name="dberlakusebelum" id="dberlakusebelum" class="form-control date" readonly value="<?= date("d-m-Y",strtotime($data->d_berlaku));?>">
                             <input type="hidden" name="dakhirsebelum" id="dakhirsebelum" class="form-control date" readonly value="<?= date('Y-m-d', strtotime('-1 days', strtotime( $dberlaku )));?>">
                        </div>
                        <div class="col-sm-3">
                            <?php if($data->f_status == 'f'){?>
                                <select id="etipe" name="etipe" class="form-control select2" disabled="">
                                    <option value="">Pilih Include PPN</option>
                                    <option value="I" <?php if($data->i_type_pajak =='I') { ?> selected <?php } ?> >Ya</option>
                                    <option value="E" <?php if($data->i_type_pajak =='E') { ?> selected <?php } ?> >Tidak</option> 
                                </select> 
                                <input type="hidden" name="itipe" id="itipe" class="form-control" required="" value="<?= $data->i_type_pajak; ?>" readonly>
                            <?}else{?>
                                <select id="itipe" name="itipe" class="form-control select2">
                                    <option value="I" <?php if($data->i_type_pajak =='I') { ?> selected <?php } ?> >Ya</option>
                                    <option value="E" <?php if($data->i_type_pajak =='E') { ?> selected <?php } ?> >Tidak</option> 
                                </select> 
                            <?}?>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-md-3">Harga Eks</label>
                        <label class="col-md-3">Satuan Eks</label>
                        <label class="col-md-3">Harga Int</label>
                        <label class="col-md-3">Satuan Int</label>
                        <div class="col-sm-3">
                            <?php if($data->f_status == 'f'){?>
                                <input type="text" name="hargaeks" id="hargaeks" class="form-control" required="" value="<?= $data->v_price_eks; ?>" readonly>
                            <?}else{?>
                                <input type="text" name="hargaeks" id="hargaeks" class="form-control" required="" value="<?= $data->v_price_eks; ?>" onkeyup="angkahungkul(this);">
                                <select name="konversiharga" id="konversiharga" style="display:none"> 
                                </select>
                                <select name="angkafaktor" id="angkafaktor" style="display:none"> 
                                </select>
                            <?}?>
                        </div>
                        <div class="col-sm-3">
                            <?php if($data->f_status == 'f'){?>
                                <select name="esatuaneks" id="esatuaneks" class="form-control select2" disabled="">
                                    <option value="<?=$data->i_satuan_code_eks;?>"><?=$data->e_satuan_name_eks;?></option>
                                </select>
                                <input type="hidden" name="isatuaneks" id="isatuaneks" class="form-control" required="" value="<?= $data->i_satuan_code_eks; ?>" readonly>
                            <?}else{?>
                                <select name="isatuaneks" id="isatuaneks" class="form-control select2" onchange="konversi();">
                                    <option value="">Pilih Satuan</option>
                                    <?php foreach($satuan as $satuan): ?>
                                    <option value="<?php echo $satuan->i_satuan_code;?>" 
                                    <?php if($satuan->i_satuan_code==$data->i_satuan_code_eks) { ?> selected="selected" <?php } ?>>
                                    <?php echo $satuan->e_satuan_name;?></option>
                                    <?php endforeach; ?> 
                                </select>
                            <?}?>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="hargaint" id="hargaint" class="form-control" value="<?= $data->v_price_int; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="esatuanint" id="esatuanint" class="form-control" required="" value="<?= $data->e_satuan_name_int; ?>" readonly>
                            <input type="hidden" name="isatuanint" id="isatuanint" class="form-control" required="" value="<?= $data->i_satuan_code_int; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Status</label>
                        <div class="col-sm-3">
                            <select name="status" id="status" class="form-control select2">
                                <option value="t" <?php if($data->f_status =='t') { ?> selected <?php } ?> >Aktif</option>
                                <option value="f" <?php if($data->f_status =='f') { ?> selected <?php } ?> >Tidak Aktif</option>
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" id="save" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000">* Jika akan mengubah tanggal berlaku, maka tanggal berlaku tidak boleh sama dengan tanggal berlaku sebelumnya</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$('#hargaeks').keyup(function(){
    konversi();
});

$("#save").click(function(){
    dberlakunow = $("#dberlaku").val();
    dberlakuold = $("#dberlakusebelum").val();

    var id             = $('#id').val();
    var isupplier      = $('#idsupplier').val();
    var igroupbrg      = $('#igroupbrg').val();
    var itypemakloon   = $('#idmakloon').val();
    var ikodekelompok  = $('#ikodekelompok').val();   
    var ikodejenis     = $('#ikodejenis').val();   
    var idkodebrg 	   = $('#idkodebrg').val();
    var kodebrg 	   = $('#kodebrg').val();
    var hargaint 	   = $('#hargaint').val();
    var isatuanint     = $('#isatuanint').val();
    var hargaeks 	   = $('#hargaeks').val();
    var isatuaneks     = $('#isatuaneks').val();
    var itipe          = $('#itipe').val();
    var dateberlaku    = $('#dberlaku').val();
    var datesebelum    = $('#dberlakusebelum').val();
    var status         = $('#status').val();
    var dfrom          = $('#dfrom').val();
    var dakhirsebelum  = $('#dakhirsebelum').val();

    if(dberlakunow != dberlakuold){
        dipales();
    }else{
        $.ajax({
            type: "post",
            data: {
                'id'            : id,
                'isupplier'     : isupplier, 
                'igroupbrg'     : igroupbrg, 
                'itypemakloon'  : itypemakloon, 
                'ikodekelompok' : ikodekelompok,  
                'ikodejenis'    : ikodejenis, 
                'idkodebrg' 	: idkodebrg,    
                'kodebrg' 	    : kodebrg, 
                'hargaint' 		: hargaint, 
                'isatuanint'    : isatuanint,
                'hargaeks' 		: hargaeks, 
                'isatuaneks'    : isatuaneks,
                'itipe'         : itipe, 
                'dateberlaku'   : dateberlaku,
                'datesebelum'   : datesebelum, 
                'status'        : status,
                'dfrom'         : dfrom,
                'dakhirsebelum' : dakhirsebelum
            },
            url: '<?= base_url($folder.'/cform/ubahtanggalberlaku'); ?>',
            dataType: "json",
            success: function (data) {
                $("input").attr("disabled", true);
                $("select").attr("disabled", true);
                $("#submit").attr("disabled", true);
                swal("Ubah!", "Data berhasil diubah :)", "success");
                show('<?= $folder;?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>','#main');   
            },
            error: function () {
                swal("Maaf", "Data gagal diubah :(", "error");
            }
        });
    }

});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $('#dberlakusebelum').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: "dd-mm-yyyy",
      todayBtn: "linked",
      daysOfWeekDisabled: [0],
      startDate: $('#dberlaku').val(),
    });
   
});


function max_tgl(val) {
  $('#dberlaku').datepicker('destroy');
  $('#dberlaku').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dberlakusebelum').value,
  });
}

$('#dberlaku').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dberlakusebelum').value,
});

function konversi(){
    var satuan_awal    = $('#isatuaneks').val();
    var satuan_akhir   = $('#isatuanint').val();
    var harga          = $('#hargaeks').val();
    var konversiharga  = $('#konversiharga').val();
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
            $('#konversiharga').html(data.kop);
            if (data.kosong=='kopong') {
                swal("konversi satuan kosong");
            }else {
                ngetangfaktor();
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
}

function ngetangfaktor() {
    var satuan_awal = $('#isatuaneks').val();
    var satuan_akhir = $('#isatuanint').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getrumusfaktor');?>",
        data:{
            'satuan_awal': satuan_awal,
            'satuan_akhir': satuan_akhir,
        },
        dataType: 'json',
        success: function(data){
            $('#angkafaktor').html(data.kop);
            if (data.kosong=='kopong') {
                swal("Rumus Tidak Tersedia");
            }else {
                hitunghargakonversi();
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
}

function hitunghargakonversi(){
    var satuan_awal   = $('#isatuaneks').val();
    var satuan_akhir  = $('#isatuanint').val();
    var harga         = $('#hargaeks').val();
    var konversiharga = $('#konversiharga').val();
    var angkafaktor   = $('#angkafaktor').val();

    if(konversiharga == '1'){
        total=harga*angkafaktor;
        document.getElementById('hargaint').value = (total);
    }else if(konversiharga == '2'){
        total=harga/angkafaktor;
        document.getElementById('hargaint').value = (total);
    }else if(konversiharga == '3'){
        total=harga+angkafaktor;
        document.getElementById('hargaint').value = (total);
    }else if(konversiharga == '4'){
        total=harga-angkafaktor;
        document.getElementById('hargaint').value = (total);
    }else{
        swal("Rumus Tidak Tersedia");
    }
}

function dipales(){
    var id             = $('#id').val();
    var isupplier      = $('#idsupplier').val();
    var igroupbrg      = $('#igroupbrg').val();
    var itypemakloon   = $('#idmakloon').val();
    var ikodekelompok  = $('#ikodekelompok').val();   
    var ikodejenis     = $('#ikodejenis').val();   
    var idkodebrg 	   = $('#idkodebrg').val();
    var kodebrg 	   = $('#kodebrg').val();
    var hargaint 	   = $('#hargaint').val();
    var isatuanint     = $('#isatuanint').val();
    var hargaeks 	   = $('#hargaeks').val();
    var isatuaneks     = $('#isatuaneks').val();
    var itipe          = $('#itipe').val();
    var dateberlaku    = $('#dberlaku').val();
    var datesebelum    = $('#dberlakusebelum').val();
    var status         = $('#status').val();
    var dfrom          = $('#dfrom').val();
    var dakhirsebelum  = $('#dakhirsebelum').val();
    swal({   
            title: "Ubah Tanggal Berlaku ?",   
            text: "Mengubah tanggal berlaku!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ubah, data yang sudah ada!",   
            cancelButtonText: "Simpan, sebagai data baru!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'id'            : id,
                        'isupplier'     : isupplier, 
                        'igroupbrg'     : igroupbrg, 
                        'itypemakloon'  : itypemakloon, 
                        'ikodekelompok' : ikodekelompok,  
                        'ikodejenis'    : ikodejenis, 
                        'idkodebrg' 	: idkodebrg,    
                        'kodebrg' 	    : kodebrg, 
                        'hargaint' 		: hargaint, 
                        'isatuanint'    : isatuanint,
                        'hargaeks' 		: hargaeks, 
                        'isatuaneks'    : isatuaneks,
                        'itipe'         : itipe, 
                        'dateberlaku'   : dateberlaku,
                        'datesebelum'   : datesebelum, 
                        'status'        : status,
                        'dfrom'         : dfrom,
                        'dakhirsebelum' : dakhirsebelum
                    },
                    url: '<?= base_url($folder.'/cform/ubahtanggalberlaku'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#submit").attr("disabled", true);
                        swal("Ubah!", "Data berhasil diubah :)", "success");
                        show('<?= $folder;?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>','#main');   
                    },
                    error: function () {
                        swal("Maaf", "Data gagal diubah :(", "error");
                    }
                });
            } else {     
                $.ajax({
                    type: "post",
                    data: {
                        'id'            : id,
                        'isupplier'     : isupplier, 
                        'igroupbrg'     : igroupbrg, 
                        'itypemakloon'  : itypemakloon, 
                        'ikodekelompok' : ikodekelompok,  
                        'ikodejenis'    : ikodejenis,
                        'idkodebrg' 	: idkodebrg,      
                        'kodebrg' 	    : kodebrg, 
                        'hargaint' 		: hargaint, 
                        'isatuanint'    : isatuanint,
                        'hargaeks' 		: hargaeks, 
                        'isatuaneks'    : isatuaneks,
                        'itipe'         : itipe, 
                        'dateberlaku'   : dateberlaku,
                        'datesebelum'   : datesebelum, 
                        'status'        : status,
                        'dfrom'         : dfrom,
                        'dakhirsebelum' : dakhirsebelum
                    },
                    url: '<?= base_url($folder.'/cform/inserttanggalberlaku'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#submit").attr("disabled", true);
                        swal("Ubah!", "Data berhasil disimpan :)", "success");
                        show('<?= $folder;?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>','#main');   
                    },
                    error: function () {
                        swal("Maaf", "Data gagal disimpan :(", "error");
                    }
                });
            } 
    });
}
$('#dberlaku').on('change',function(){
    swal("Tanggal berlaku telah diubah!");
});

</script>