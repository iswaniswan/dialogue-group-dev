<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal Berlaku</label>
                        <label class="col-md-6">Supplier</label> 
                        <div class="col-sm-6">
                            <input type="hidden" name="dfrom" id="dfrom" class="form-control" readonly value="<?=$dfrom;?>">
                            <input type="text" name="dberlaku" id="dberlaku" class="form-control" readonly value="<?= date("d-m-Y",strtotime($dberlaku));?>">
                            <input type="hidden" name="dberlakusebelum" id="dberlakusebelum" class="form-control date" readonly value="<?= date("d-m-Y",strtotime($dberlaku));?>">
                            <input type="hidden" name="dakhirsebelum" id="dakhirsebelum" class="form-control date" readonly value="<?= date('Y-m-d', strtotime('-1 days', strtotime( $dberlaku )));?>">
                            <input type="hidden" name="id" id="id" class="form-control" value="<?=$data->id;?>">
                        </div>                        
                        <div class="col-sm-6">
                            <input type="hidden" name="isupplier" id="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" name="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_supplier_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nama Barang</label>  
                        <div class="col-sm-12">
                            <input type="hidden" name="id_panel_item" id="id_panel_item" class="form-control" required="" value="<?= $data->id_panel_item; ?>">
                            <input type="text" name="namabrg" id="namabrg" class="form-control" required="" value="<?= $data->i_panel . ' - ' . $data->e_color_name; ?>" readonly>
                        </div>   
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8"> 
                            <!-- <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?> -->
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                                <button type="button" id="save" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return dipales();"> <i class="fa fa-save mr-2"></i>Update</button>
                            <?php } ?>
                            <?php if ($data->i_status == '1' || $data->i_status == '3') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                    <span style="color: #8B0000"><b>Note : </b>Jika akan mengubah tanggal berlaku, maka tanggal berlaku tidak boleh sama dengan tanggal berlaku sebelumnya</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Include PPN</label>
                        <label class="col-md-6">Min Order</label>      
                        <div class="col-sm-6">
                            <?php if($data->f_ppn == 't'){
                                    $fppn = 'Ya';
                                }else{
                                    $fppn = 'Tidak';
                                }
                            ?>
                            <?php if($data->f_pkp == 't'){?>
                                <select name="ippnx" id="ippnx" class="form-control select2" disabled="">
                                    <option value="<?=$data->f_ppn;?>"><?=$fppn;?></option>
                                </select>  
                            <?}else{?>
                                <select id="ippnx" class="form-control select2" name="ippnx" disabled="">
                                    <option value="<?=$data->f_ppn;?>" selected="true"><?=$fppn;?></option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option> 
                                </select>
                            <?}?>
                            <input type="hidden" name="ippn" id="ippn" class="form-control" required="" value="<?= $data->f_ppn; ?>">
                        </div>
                        <div class="col-sm-6">
                             <input type="text" name="norder" id="norder" class="form-control" required="" value="<?= $data->n_order; ?>">
                        </div>       
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Harga Exclude</label>
                        <label class="col-md-6">Marker</label>
                        <div class="col-sm-6">
                            <input type="text" name="harga" id="harga" class="form-control" required="" value="<?= number_format($data->v_price, 4); ?>" autocomplete="off" onkeyup="angkahungkul(this);reformat(this);if(this.value=='0'){this.value='';}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                        </div>
                        <div class="col-sm-6">
                            <!-- <select name="isatuansupplier" id="isatuansupplier" class="form-control select2">
                               <option value="<?=$data->i_satuan_konversi;?>" selected="true"><?=$data->e_satuan_name;?></option>
                               <?php foreach ($satuan as $key):?>
                                    <option value="<?php echo $key->i_satuan_code;?>">
                                        <?php echo $key->e_satuan_name;?></option>
                                <?php endforeach; ?>
                            </select> -->
                            <input type="hidden" name="marker" id="marker" value="<?= $data->id_marker ?>">
                            <input type="text" class="form-control" name="namamarker" id="namamarker" value="<?= $data->e_marker_name ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6" hidden="">Harga Exclude</label>
                        <label class="col-md-6" hidden="">Satuan Konversi</label> 
                        <div class="col-sm-6" hidden="">
                            <input type="text" name="hargakonversi" id="hargakonversi" class="form-control" required="" value="<?= number_format($data->v_harga_konversi,4); ?>" autocomplete="off" onkeyup="angkahungkul(this);reformat(this);if(this.value=='0'){this.value='';}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" readonly>
                            <select name="konversiharga" id="konversiharga" style="display:none;"> 
                            </select>
                            <select name="angkafaktor" id="angkafaktor" style="display:none;"> 
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

/*----------  UPDATE STATUS DOKUMEN  ----------*/
$('#send').click(function(event) {
    statuschange('<?= $folder;?>',$('#id').val(),'2','','');
});

$('#cancel').click(function(event) {
    statuschange('<?= $folder;?>',$('#id').val(),'1','','');
});

$('#hapus').click(function(event) {
    statuschange('<?= $folder;?>',$('#id').val(),'5','','');
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

$('#dberlaku').on('change',function(){
    swal("Tanggal berlaku telah diubah!");
});

$('#harga').keyup(function(){
    //konversi();
});

function konversi(){
    var satuan_awal    = $('#isatuansupplier').val();
    var satuan_akhir   = $('#isatuanperusahaan').val();
    var harga          = formatulang($('#harga').val());

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
                swal("Konversi Satuan Kosong");
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
    var satuan_awal = $('#isatuansupplier').val();
    var satuan_akhir = $('#isatuanperusahaan').val();
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
    var satuan_awal   = $('#isatuansupplier').val();
    var satuan_akhir  = $('#isatuanperusahaan').val();
    var harga         = formatulang($('#harga').val());
    var konversiharga = $('#konversiharga').val();
    var angkafaktor   = $('#angkafaktor').val();
    var ppn           = $('#ippn').val();

    if(ppn == 't'){
        ppn = '1';
    }else{
        ppn = '0';
    }

    if(ppn == '1'){
        if(konversiharga == '1'){
            total=harga*angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else if(konversiharga == '2'){
            total=harga/angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else if(konversiharga == '3'){
            total=harga+angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else if(konversiharga == '4'){
            total=harga-angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else{
            swal("Rumus Tidak Tersedia");
        }
    }else if(ppn == '0'){
        if(konversiharga == '1'){
            total=harga*angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else if(konversiharga == '2'){
            total=harga/angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else if(konversiharga == '3'){
            total=harga+angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else if(konversiharga == '4'){
            total=harga-angkafaktor;
            document.getElementById('hargakonversi').value = (total);
        }else{
            swal("Rumus Tidak Tersedia");
        } 
    }
}

function dipales(){
    var isupplier       = $('#isupplier').val();
    var id_panel_item   = $('#id_panel_item').val();
    var marker          = $('#marker').val();
    var harga           = $('#harga').val();
    var norder          = $('#norder').val();
    var hargakonversi   = $('#hargakonversi').val();
    var fppn            = $('#ippn').val();
    var dberlaku        = $('#dberlaku').val();
    var dberlakusebelum = $('#dberlakusebelum').val();
    var dakhirsebelum   = $('#dakhirsebelum').val();
    var dfrom           = $('#dfrom').val();
    var id              = $('#id').val();
    
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
                        'isupplier'     : isupplier, 
                        'id_panel_item' : id_panel_item,
                        'marker'        : marker,
                        'harga'         : harga,
                        'norder'        : norder,
                        'hargakonversi' : hargakonversi,
                        'fppn'          : fppn, 
                        'dberlaku'      : dberlaku,
                        'dakhirsebelum' : dakhirsebelum,
                        'dfrom'         : dfrom,
                        'dberlakusebelum': dberlakusebelum,
                        'id'            : id
                    },
                    url: '<?= base_url($folder.'/cform/ubahtanggalberlaku'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#submit").attr("disabled", true);
                        swal("Ubah!", "Data berhasil diubah :)", "success");
                    },
                    error: function () {
                        swal("Maaf", "Data gagal diubah :(", "error");
                    }
                });
            } else {     
                $.ajax({
                    type: "post",
                    data: {
                        'isupplier'     : isupplier, 
                        'id_panel_item' : id_panel_item,
                        'marker'        : marker,
                        'harga'         : harga,
                        'norder'        : norder,
                        'hargakonversi' : hargakonversi,
                        'fppn'          : fppn, 
                        'dberlaku'      : dberlaku,
                        'dakhirsebelum' : dakhirsebelum,
                        'dfrom'         : dfrom,
                        'dberlakusebelum': dberlakusebelum,
                        'id'            : id
                    },
                    url: '<?= base_url($folder.'/cform/inserttanggalberlaku'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#submit").attr("disabled", true);
                        swal("Ubah!", "Data baru berhasil disimpan :)", "success");
                        show("<?= $folder;?>/cform/","#main");
                    },
                    error: function () {
                        swal("Maaf", "Data gagal disimpan :(", "error");
                    }
                });
            } 
    });
}
</script>