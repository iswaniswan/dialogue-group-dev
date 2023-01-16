<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
             <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Sub Kategori Barang</label>
                        <label class="col-md-3">Nama Sub Kategori Barang</label>
                        <label class="col-md-3">Kode Kategori Barang</label>
                        <label class="col-md-3">Kode Group Barang</label>
                        <div class="col-sm-3">
                            <input type="text" name="itypecode" id="itypecode" class="form-control" placeholder="Kode Sub Kategori Barang" required="" onkeyup="gede(this);" value="<?= $data->i_type_code; ?>">
                            <input type="hidden" name="itypecodeold" id="itypecodeold" class="form-control" required="" value="<?= $data->i_type_code; ?>" readonly>
                            <input type="hidden" name="id" id="id" class="form-control" required="" value="<?= $data->id; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="etypename" id="etypename" class="form-control" placeholder="Nama Sub Kategori Barang" required="" value="<?= $data->e_type_name; ?>" onkeyup="gede(this);">
                        </div>
                        <div class="col-sm-3">
                            <select name="ikelompok" id="ikelompok" class="form-control select2" onchange="getjenis(this.value)">
                                <?php foreach ($kelompok as $ikelompok):?>
                                    <option value="<?php echo $ikelompok->i_kode_kelompok;?>"
                                        <?php if($ikelompok->i_kode_kelompok==$data->i_kode_kelompok) { ?> selected="selected" <?php } ?>>
                                        <?php echo $ikelompok->e_nama_kelompok;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type = "hidden" name="igroupbrg" id="igroupbrg" class="form-control" value = "<?= $data->i_kode_group_barang ;?>" readonly>
                           <input type = "text" name="egroupbrg" id="egroupbrg" class="form-control" placeholder="Group Barang"value = "<?= $data->e_nama_group_barang ;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>  
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Jenis Barang terdiri dari 7 (tujuh) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama Jenis Barang</span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Jenis Barang sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : MIC0001, PRL0002, HND0003, dst</b></span>
                    </div>
                </div>                                  
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select2").select2();
});

function getjenis(ikelompokbrg) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkelompok');?>",
        data:"ikelompokbrg="+ikelompokbrg,
        dataType: 'json',
        success: function(data){
            $("#igroupbrg").val(data.igroup);
            $("#egroupbrg").val(data.egroupname);
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function validasi(){
    var igroupbrg = $('#igroupbrg').val();
    var ikelompok = $('#ikelompok').val();
    var ikodebrg  = $('#ikodebrg').val();
    var enamabrg  = $('#enamabrg').val();
    var isatuan   = $('#isatuan').val();
    var iwarna    = $('#iwarna').val();
    
    if (igroupbrg == '' || igroupbrg == null) {
        swal('Group Barang Belum dipilih');
        return false;
    }else  if (ikelompok == '' || ikelompok == null) {
        swal('Kategori Barang Belum dipilih');
        return false;
    }else  if (iproductbase == '' || iproductbase == null ) {
        swal('Kode Barang Belum dipilih');
        return false;
    }else  if (eproductbasename == '') {
        swal('Nama Barang Belum diisi');
        return false;
    }else  if (isatuan == '' || isatuan == null) {
        swal('Jenis Satuan Belum dipilih');
        return false;
    }else if (iwarna == '' || iwarna == null){
        swal('Warna/Motif Belum dipilih');
        return false;
    }else{
        return true;
    }
}

$( "#itypecode" ).keyup(function() {
    var kode = $('#itypecode').val();
    var kodeold = $('#itypecodeold').val();
    $.ajax({
        type: "post",
        data: {
            'kode' : kode,
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function (data) {
            if (data==1 && kodeold!=kode) {
                $("#cek").attr("hidden", false);
                $("#submit").attr("disabled", true);
            }else{
                $("#cek").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
});
$( "#etypename" ).keyup(function() {
    var name = $(this).val().replace(/[^\w\s]/gi,'');
    $('#etypename').val(name);
});
$(document).ready(function () {
    $( "#itypecode" ).focus();  
});
</script>
