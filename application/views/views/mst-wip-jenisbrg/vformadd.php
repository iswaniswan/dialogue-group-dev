<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>


        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kode Jenis</label>
                        <label class="col-md-8">Nama Jenis</label>
                        <div class="col-sm-4">
                            <input type="text" name="ijenisbrgwip" class="form-control" maxlength="7"  value="" onblur="checklength(this)">
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="enamajenis" class="form-control" maxlength="100"  value="" >
                        </div>
                    </div>               
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-md-6">Kategori</label>
                        <div class="col-sm-6">                            
                            <select name="ikelbrgwip" class="form-control select2">
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($wip_barang as $ikelbrgwip):?>
                                <option value="<?php echo $ikelbrgwip->i_kelbrg_wip;?>">
                                    <?php echo $ikelbrgwip->i_kelbrg_wip."-".$ikelbrgwip->e_nama_kel;?></option>
                            <?php endforeach; ?>                            
                        </select>                        
                        </div>
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

function checklength(el){
    if(el.value.length != 7){
        swal("Kode Harus 7 Karakter");
    }
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
