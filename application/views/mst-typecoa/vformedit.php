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
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Kode Kategori</label>
                        <label class="col-md-6">Nama Kategori</label>
                        <div class="col-sm-6">
                            <input type="text" name="ikelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="enama" class="form-control" maxlength="30"  value="<?= $data->e_nama; ?>" >
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div> 
                </div> 
               <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-12">Kode Group Barang</label>
                        <div class="col-sm-6">
                           <select name="igroupbrg" class="form-control select2">
                            <option value="">Pilih Kode Group Barang</option>
                           <?php foreach($groupbarang as $igroupbrg): ?>
                            <option value="<?php echo $igroupbrg->i_kode_group_barang;?>" 
                            <?php if($igroupbrg->i_kode_group_barang==$data->i_kode_group_barang) { ?> selected="selected" <?php } ?>>
                            <?php echo $igroupbrg->e_nama_group_barang;?></option>
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

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
