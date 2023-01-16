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
                    <label class="col-md-3">Kategori Barang</label>     
                    <label class="col-md-3">Jenis Barang</label>
                    <label class="col-md-6">Kode Barang</label>    
                    <div class="col-sm-3">
                        <select name="ikodekelompok" id ="ikodekelompok" class="form-control select2" onchange="getjenisbarang(this.value);">
                        </select>
                    </div>     
                    <div class="col-sm-3">
                        <select name="ikodejenis" id="ikodejenis" class="form-control select2" disabled="true" onchange="getproduct(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="iproduct" id ="iproduct" class="form-control select2" disabled="true">                          
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="return cek();"> <i class="fa fa-spinner"></i>&nbsp;&nbsp;Proses</button>
                        &nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
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

    $('#ikodekelompok').select2({
        placeholder: 'Pilih Kategori Barang',
        allowClear: true,
        ajax: {
        url: '<?= base_url($folder.'/cform/kategoribarang'); ?>',
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

    $('#ikodejenis').select2({
        placeholder: 'Pilih Jenis Barang',
    });

    $('#iproduct').select2({
        placeholder: 'Pilih Barang',
    });
});

function getjenisbarang(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getjenisbarang');?>",
        data:"ikodekelompok="+id,
        dataType: 'json',
        success: function(data){
            $("#ikodejenis").html(data.kop);
            getproduct('AJB');
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

function getproduct(ikodejenis) {
    var ikodekelompok = $('#ikodekelompok').val();
    var ikodejenis = $('#ikodejenis').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getproduct');?>",
        data:{
                'ikodejenis': ikodejenis,
                'ikodekelompok':ikodekelompok,
            },
        dataType: 'json',
        success: function(data){
            $("#iproduct").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
                $("#iproduct").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function cek(){
    //alert("tes");
    var ikodekelompok = $('#ikodekelompok').val();

    if (ikodekelompok == '' || ikodekelompok == null) {
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