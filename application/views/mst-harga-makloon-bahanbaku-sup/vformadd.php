<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-12">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-4">Supplier</label>
                    <label class="col-md-8">Jenis Makloon</label>
                    <div class="col-sm-4">
                        <select name="isupplier"  id="isupplier" class="form-control select2" onchange="getmakloon(this.value);getkelompokbarang(this.value);">
                        </select>                        
                        <input name="idsupp" id="idsupp" value="" type="hidden">
                    </div>
                    <div class=col-sm-3>
                        <select name="itypemakloon" id="itypemakloon" class="form-control select2" disabled>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4">Kategori Barang</label>  
                    <label class="col-md-3">Jenis Barang</label>
                    <label class="col-md-5">Kode Barang</label>
                    <div class="col-sm-4">
                        <select name="ikodekelompok" id ="ikodekelompok" class="form-control select2" disabled onchange="getjenis(this.value);">
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <select name="ikodejenis" id="ikodejenis" class="form-control select2" disabled onchange="getmaterial(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="iproduct" id ="iproduct" class="form-control select2" disabled>                           
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="cekval();"> <i class="fa fa-spinner"></i>&nbsp;&nbsp;Proses</button>
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
$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $(".select2").select2();

    $("#ikodekelompok").select2({
        placeholder : "Pilih Kategori Barang",
    });

    $("#itypemakloon").select2({
        placeholder : "Pilih Jenis Makloon",
    });

    $("#ikodejenis").select2({
        placeholder : "Pilih Jenis Barang",
    });

    $("#iproduct").select2({
        placeholder : "Pilih Barang",
    });

    $("#isupplier").select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getsupplieradd'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        }
    });
});

function getmakloon(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/gettypemakloon');?>",
        data:{
            'id' : id,
        },
        dataType: 'json',
        success: function (data) {
            $("#itypemakloon").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $("#itypemakloon").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }

    });
}

function getidsupp(id) {
    $.ajax({
        type: "post",
        data: {
            'id': id
        },
        url: '<?= base_url($folder.'/cform/getidsupp'); ?>',
        dataType: "json",
        success: function (data) {
            $('#idsupp').val(data[0].id);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getkelompokbarang(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkelompokbarang');?>",
        data: {
            'id' : id,
        },
        dataType: 'json',
        success: function (data) {
            $("#ikodekelompok").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $("#ikodekelompok").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }

    });
    getidsupp(id);
}

function getgroupbarang(id) {
    var ikodekelompok = $('#ikodekelompok' + id).val();
    $.ajax({
        type: "post",
        data: {
            'ikodekelompok': ikodekelompok
        },
        url: '<?= base_url($folder.'/cform/getgroupbarang'); ?>',
        dataType: "json",
        success: function (data) {
            $('#igroupkodebarang' + id).val(data[0].i_kode_group_barang);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getproductname(id) {
    var iproduct = $('#iproduct' + id).val();
    $.ajax({
        type: "post",
        data: {
            'iproduct': iproduct
        },
        url: '<?= base_url($folder.'/cform/getproductname'); ?>',
        dataType: "json",
        success: function (data) {
            $('#eproductbasename' + id).val(data[0].nama_brg);
            $('#idprod'+id).val(data[0].id);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getjenis(ikodekelompok) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getjenis');?>",
        data:"ikodekelompok="+ikodekelompok,
        dataType: 'json',
        success: function(data){
            $("#ikodejenis").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            getmaterial('AJB');
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
                $("#ikodejenis").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getmaterial(ikodejenis) {
    var ikodekelompok   = $('#ikodekelompok').val();
    var isupplier       = $('#idsupp').val();
    var itypemakloon    = $('#itypemakloon').val();

    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getmaterial');?>",
        data:{
                'ikodejenis': ikodejenis,
                'ikodekelompok':ikodekelompok,
                'idsupp':isupplier,
                'itypemakloon' : itypemakloon
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

function cekval(){
    var isupplier = $('#isupplier').val();
    var ikodekelompok = $('#ikodekelompok').val();

    if(isupplier == '' || ikodekelompok == ''){
        swal("Supplier atau Kategori Barang Tidak Boleh Kosong !");

        show('<?= $folder;?>/cform/tambah','#main'); 
    }
}

</script>