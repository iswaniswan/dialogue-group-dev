<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-3">Supplier</label>
                    <label class="col-md-3">Kategori Barang</label>     
                    <label class="col-md-3">Jenis Barang</label>
                    <label class="col-md-3">Kode Barang</label>      
                    <div class="col-sm-3">
                        <select name="isupplier"  id="isupplier" class="form-control select2" onchange="getkelompokbarang(this.value);">
                        </select>                      
                    </div>
                    <div class="col-sm-3">
                        <select name="ikodekelompok" id ="ikodekelompok" class="form-control select2" disabled="true" onchange="getjenisbarang(this.value);">
                        </select>
                    </div>     
                    <div class="col-sm-3">
                        <select name="ikodejenis" id="ikodejenis" class="form-control select2" disabled="true" onchange="getmaterial(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="imaterial" id ="imaterial" class="form-control select2" disabled="true">                           
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm mr-2" onclick="return cek();"> <i class="fa fa-spin fa-spinner mr-2"></i>Proses</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button> 
                    </div>
                </div>
            </div>
                <input type="hidden" name="jml" id="jml">
            </div>
        </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function () {
    $(".select2").select2();

    $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
        ajax: {
        url: '<?= base_url($folder.'/cform/supplier'); ?>',
        dataType: 'json',
        delay: 250,          
        processResults: function (data) {
            return {
            results: data
            };
        },
        cache: true
        }
    });

    $('#ikodekelompok').select2({
        placeholder: 'Pilih Kategori Barang',
    });

    $('#ikodejenis').select2({
        placeholder: 'Pilih Jenis Barang',
    });

    $('#imaterial').select2({
        placeholder: 'Pilih Barang',
    });
});

function getkelompokbarang(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkelompokbarang');?>",
        data: "id=" + id,
        dataType: 'json',
        success: function (data) {
            $("#ikodekelompok").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#ikodekelompok").attr("disabled",false);
                $("#submit").attr("disabled", false);
            }
        },
        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
}

function getjenisbarang(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getjenisbarang');?>",
        data:"ikodekelompok="+id,
        dataType: 'json',
        success: function(data){
            $("#ikodejenis").html(data.kop);
            getmaterial('AJB');
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#ikodejenis").attr("disabled", false);
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    });
}

function getmaterial(ikodejenis) {
    var ikodekelompok = $('#ikodekelompok').val();
    var isupplier = $('#isupplier').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getmaterial');?>",
        data:{
                'ikodejenis': ikodejenis,
                'ikodekelompok':ikodekelompok,
                'isupplier':isupplier,
            },
        dataType: 'json',
        success: function(data){
            $("#imaterial").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
                $("#imaterial").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function cek(){
    //alert("tes");
    var isupplier = $('#isupplier').val();
    var ikodekelompok = $('#ikodekelompok').val();

    if (isupplier == '' || isupplier == null || ikodekelompok == '' || ikodekelompok == null) {
        swal('Data Belum Lengkap !!');
        return false;
    } else {
        return true;
    }
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>