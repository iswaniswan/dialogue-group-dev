<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/view2/<?=$dfrom;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-12">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-3">Supplier</label>
                    <label class="col-md-3">Kategori Barang</label>  
                    <label class="col-md-3">Jenis Barang</label>
                    <label class="col-md-3">Kode Barang</label>
                    <div class="col-sm-3">
                        <select name="isupplier"  id="isupplier" class="form-control select2" onchange="getkelompok(this.value);">
                            <option value="">Pilih Supplier</option>
                            <?php foreach ($supplier as $r):?>
                            <option value="<?php echo $r->i_supplier;?>"><?php echo $r->i_supplier." - ".$r->e_supplier_name;?>
                            </option>
                            <?php endforeach; ?>
                        </select>                        
                    </div>
                    <div class="col-sm-3">
                        <select name="ikodekelompok" id ="ikodekelompok" class="form-control select2" disabled onchange="getjenis(this.value);">
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <select name="ikodejenis" id="ikodejenis" class="form-control select2" disabled onchange="getmaterial(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="imaterial" id ="imaterial" class="form-control select2" disabled>                           
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
});

function getkelompok(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkelompok');?>",
        data: "id=" + id,
        dataType: 'json',
        success: function (data) {
            $("#ikodekelompok").html(data.kop);
            getjenis('AKB');
            getmaterial('AJB');
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
    var ikodekelompok = $('#ikodekelompok').val();
    var isupplier = $('#isupplier').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getmaterial');?>",
        data:{
                'ikodejenis': ikodejenis,
                'ikodekelompok':ikodekelompok,
                'isupplier':isupplier
            },
        dataType: 'json',
        success: function(data){
            $("#imaterial").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
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

function cekval(){
    var isupplier = $('#isupplier').val();
    var ikodekelompok = $('#ikodekelompok').val();

    if(isupplier == '' || ikodekelompok == ''){
        swal("Supplier atau Kategori Barang Tidak Boleh Kosong !");

        show('<?= $folder;?>/cform/tambah','#main'); 
    }
}

</script>