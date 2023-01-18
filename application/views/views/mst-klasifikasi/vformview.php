<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>        
        <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Kode Klasifikasi</label>
                        <label class="col-md-6">Nama Klasifikasi</label>
                        <div class="col-sm-6">
                            <input type="text" name="iitemgroup" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_klasifikasi; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                             <input type="text" name="egroupname" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_nama; ?>" readonly>
                        </div> 
                    </div>
                </div>
                <div class="form-group row">
                        <label class="col-md-12">Kode Group Barang</label>
                        <div class="col-sm-6">
                           <select name="igroupbrg" class="form-control select2" disabled="">
                                <option value="">Pilih Kode Group Barang</option>
                                <?php foreach($groupbarang as $igroupbrg): ?>
                                <option value="<?php echo $igroupbrg->i_menu_klasifikasi;?>" 
                                <?php if($igroupbrg->i_menu_klasifikasi==$data->i_menu_klasifikasi) { ?> selected="selected" <?php } ?>>
                                <?php echo $igroupbrg->e_menu;?></option>
                                <?php endforeach; ?> 
                            </select>    
                        </div>
                </div> 
                <div class="col-md-6"> 
                <div class="form-group row">
                        <label class="col-md-12">Validasi Menu</label>
                        <div class="col-sm-6">
                           <select name="ivalidasi" class="form-control select2" disabled="">
                            <option value="<?=$data->f_validasi;?>"><?php if($data->f_validasi=='t'){echo 'Ya';}else{ echo 'Tidak';} ?></option>
                            <option value="true">Ya</option>
                            <option value="false">Tidak</option>
                        </select>  
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 $(document).ready(function () {
    $(".select2").select2();
 });
</script>