<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

        
        <div class="panel-body table-responsive">
                <div id="pesan"></div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4">Kode Jenis</label><label class="col-md-8">Nama Jenis Item</label>
                            <div class="col-sm-4">
                                <input type="text" name="itypecode" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="etypename" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_type_name; ?>" readonly>
                            </div> 
                        </div> 
 <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>        
                    </div>
                
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-6">Kode Kategori</label><label class="col-md-6">Kode Group Barang</label>
                            <div class="col-sm-6">
                            <select name="ikelompok" id="ikelompok" class="form-control select2" disabled>
                                <option value="">Pilih Kelompok</option>
                                <?php foreach ($kelompok as $ikelompok):?>
                                    <option value="<?php echo $ikelompok->i_kode_kelompok;?>"
                                        <?php if($ikelompok->i_kode_kelompok==$data->i_kode_kelompok) { ?> selected="selected" <?php } ?>>
                                        <?php echo $ikelompok->e_nama_kelompok;?></option>
                                <?php endforeach; ?>
                            </select>
                            </div>
                            <div class="col-sm-6">
                                <input type = "hidden" name="igroupbrg" id="igroupbrg" class="form-control" value = "<?= $data->i_kode_group_barang ;?>" readonly>
                                <input type = "text" name="egroupbrg" id="egroupbrg" class="form-control" value = "<?= $data->e_nama_group_barang ;?>" readonly>     
                            </div>
                        </div>
        </div>
<script>
 $(document).ready(function () {
    $(".select2").select2();
 });
</script>
