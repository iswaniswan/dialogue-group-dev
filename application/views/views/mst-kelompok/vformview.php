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
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Kategori Barang</label>
                        <label class="col-md-6">Nama Kategori Barang</label>
                        <label class="col-md-3">Group Barang</label>
                        <div class="col-sm-3">
                            <input type="text" name="iitemgroup" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                             <input type="text" name="egroupname" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_nama_kelompok; ?>" readonly>
                        </div> 
                        <div class="col-sm-3">
                           <select name="igroupbrg" class="form-control select2" disabled="">
                                <option value="">Pilih Kode Group Barang</option>
                                <?php foreach($groupbarang as $igroupbrg): ?>
                                <option value="<?php echo $igroupbrg->i_kode_group_barang;?>" 
                                <?php if($igroupbrg->i_kode_group_barang==$data->i_kode_group_barang) { ?> selected="selected" <?php } ?>>
                                <?php echo $igroupbrg->e_nama_group_barang;?></option>
                                <?php endforeach; ?> 
                            </select>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">COA</label>
                        <label class="col-md-9">Divisi</label>
                        <div class="col-sm-3">
                            <select name="icoa" id="icoa" class="form-control select2" disabled>
                                <option value="<?=$data->i_coa;?>"><?=$data->e_coa_name;?></option>
                            </select>  
                        </div>
                        <div class="col-sm-6">
                            <select name="idivisi" id="idivisi" class="form-control select2" disabled>
                                <option value="<?=$data->id_divisi;?>"><?=$data->e_nama_divisi;?></option>
                            </select>  
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    
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