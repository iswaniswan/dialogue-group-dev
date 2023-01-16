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
                <div class="col-md-12">
                    <div id="pesan"></div>
                        <div class="form-group row">
                            <label class="col-md-6">Level Group</label>
                            <label class="col-md-6">Kepala Group</label>
                            <div class="col-sm-6">
                                <select name="ilevel" id ="ilevel" class="form-control select2" onchange="getpusat();">
                                 <option value="<?=$isi->ilevel;?>"><?=$isi->e_level;?></option>
                                    <?php foreach ($level as $r):?>
                                    <option value="<?php echo $r->i_level;?>"><?php echo $r->e_level_name;?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="ipusat" id ="ipusat" class="form-control select2" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Kode Partner Group</label>
                            <label class="col-md-4">Nama Partner Group</label>
                            <label class="col-md-4">Kategori Partner Group</label>
                            <div class="col-sm-4">
                                <input type="text" name="ipartner"  id="ipartner" class="form-control" value="<?=$isi->i_kepala_pusat;?>" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="epartner"  id="epartner" class="form-control" value="<?=$isi->e_pusat;?>" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="hidden" name="isuppliergroup" id="isuppliergroup" class="form-control" value="<?=$isi->isuppliergroup;?>" readonly>
                                <input type="text" name="esuppliergroupname" id="esuppliergroupname" class="form-control" value="<?=$isi->esuppliergroupname;?>" readonly>
                            </div>
                        </div> 
                        <div class="form-group">
                            <div class="col-sm-offset-8 col-sm-5">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select2").select2();

    $("#ipusat").select2({
        placeholder : "Pilih Kepala Pusat",
    });
});

function getpusat(){
    var isuppliergroup = $('#isuppliergroup').val();
    var ipartner       = $('#ipartner').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getpusat');?>",
        data:{
            'isuppliergroup' : isuppliergroup,
            'ipartner'       : ipartner
        }, 
        dataType: 'json',
        success: function (data) {
            $("#ipusat").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
                swal("Kepala Partner Kosong");
            } else {
                $("#submit").attr("disabled", false);
                $("#ipusat").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
