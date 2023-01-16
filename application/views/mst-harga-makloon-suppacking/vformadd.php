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
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-6">Supplier</label>
                    <label class="col-md-6">Kategori Barang</label>
                    <!-- <label class="col-md-6">Group Barang</label>-->          
                    <div class="col-sm-6">
                        <select name="isupplier"  id="isupplier" class="form-control select2" tonchange="getid(this.value);">
                            <option value="">Pilih Supplier</option>
                            <?php foreach ($supplier as $r):?>
                            <option value="<?php echo $r->i_supplier;?>"><?php echo $r->i_supplier." - ".$r->e_supplier_name;?>
                            </option>
                            <?php endforeach; ?>
                        </select>                        
                    </div>
                    <div class="col-sm-6">
                    <select name="ikodekelompok" id ="ikodekelompok" class="form-control select2" onchange="get(this.value);">
                     <option value="">-- Pilih Kategori Barang --</option>
                        <?php foreach ($kodekelompok as $r):?>
                        <option value="<?php echo $r->i_kode_kelompok;?>"><?php echo $r->i_kode_kelompok." - ".$r->e_nama;?></option>
                        <?php endforeach; ?>
                       
                    </select>
                </div>
                    <!-- <div class="col-sm-6">
                        <select name="igroupbrg" id ="igroupbrg" class="form-control select2" onchange="getkelompok(this.value);">
                            <option value="">-- Pilih Group Barang --</option>
                            <?php foreach ($groupbarang as $r):?>
                            <option value="<?php echo $r->i_kode_group_barang;?>"><?php echo $r->i_kode_group_barang." - ".$r->e_nama_group_barang;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>       -->             
                </div>
                 <div class="form-group row">                 
                 <label class="col-md-6">Jenis Barang</label>
                 <label class="col-md-6">Kode Barang</label>                    
                    <div class="col-sm-6">
                        <select name="ikodejenis" id="ikodejenis" class="form-control select2"  onchange="getmaterial(this.value);">
                        </select>
                    </div>
                     <div class="col-sm-6">
                            <select name="imaterial" id ="imaterial" class="form-control select2">                           
                            </select>
                      </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="cekval();"> <i class="fa fa-spinner"></i>&nbsp;&nbsp;Proses</button>
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
    //$("#submit").attr("disabled", true);
});

function getjenis(ikodeunit) {
    /*alert(iarea);*/
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getid');?>",
        data: "ikodeunit=" + ikodeunit,
        dataType: 'json',
        success: function (data) {
            $("#idunitjahit").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getid(ikode) {
    /*alert(iarea);*/
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getid');?>",
        data: "ikode2=" + ikode2,
        dataType: 'json',
        success: function (data) {
            $("#ikode2").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }

    })
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

function getkelompok(igroupbrg) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkel');?>",
        data:"igroupbrg="+igroupbrg,
        dataType: 'json',
        success: function(data){
            $("#ikodekelompok").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            get('AKB');
            getmaterial('AJB');
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function get(ikodekelompok) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getjenis');?>",
        data:"ikodekelompok="+ikodekelompok,
        dataType: 'json',
        success: function(data){
            $("#ikodejenis").html(data.kop);
            getmaterial('AJB');
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
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
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

$('#ikodeunit').on('change',function(){
    var id = $("#ikodeunit").val();
    var res = id.split("||");
    $('#idunitjahit').val(res[0]);
})

function cekval(){
    var isupplier     = $('#isupplier').val();
    var ikodekelompok = $('#ikodekelompok').val();

    if(isupplier == '' || isupplier == null &&  ikodekelompok == '' || ikodekelompok == null){
        swal("Supplier atau Kategori Barang Tidak Boleh Kosong !");
        show('<?= $folder;?>/cform/tambah','#main'); 
    //     return false;
    // }else{
    //     return true;
    }
}
</script>