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
                        <label class="col-md-6">Kode Klasifikasi</label>
                        <label class="col-md-6">Nama Klasifikasi</label>
                        <div class="col-sm-6">
                            <input type="text" name="ikelompok" class="form-control" required="" maxlength="7" onkeyup="gede(this)" value="" onblur="checklength(this)" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                           <input type="text" name="enama" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>                               
                <div class="col-md-6"> 
                <div class="form-group row">
                        <label class="col-md-12">Menu Program</label>
                        <div class="col-sm-6">
                            <select name="igroupbrg" class="form-control select2">
                                <option value="">Pilih Menu</option>
                                <?php foreach ($groupbarang as $igroupbrg):?>
                                <option value="<?php echo $igroupbrg->i_menu_klasifikasi;?>">
                                    <?php echo $igroupbrg->i_menu_klasifikasi."-".$igroupbrg->e_menu;?></option>
                                <?php endforeach; ?>
                            </select>  
                        </div>
                    </div> 
                </div> 
                <div class="col-md-6"> 
                <div class="form-group row">
                        <label class="col-md-12">Validasi Menu</label>
                        <div class="col-sm-6">
                           <select name="ivalidasi" class="form-control select2">
                            <option value="true">Ya</option>
                            <option value="false">Tidak</option>
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
