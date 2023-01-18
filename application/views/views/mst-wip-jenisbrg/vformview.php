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
                    <div class="form-group">
                        <label class="col-md-12">Kode Jenis</label>
                        <div class="col-sm-12">
                            <input type="text" name="ijenisbrgwip" class="form-control" maxlength="7"  value="<?= $data->i_jenisbrg_wip;?>" readonly>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Nama Jenis</label>
                        <div class="col-sm-12">
                            <input type="text" name="enamajenis" class="form-control" maxlength="30"  value="<?= $data->e_nama_jenis; ?>" readonly>
                        </div>
                    </div>      
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-md-12">Kategori</label>
                        <div class="col-sm-12">                            
                            <select name="ikelbrgwip" class="form-control select2" disabled="">
                                <option value="">Pilih Kategori</option>
                                <?php foreach($wip_barang as $ikelbrgwip): ?>
                                <option value="<?php echo $ikelbrgwip->i_kelbrg_wip;?>" 
                                <?php if($ikelbrgwip->i_kelbrg_wip==$data->i_kelbrg_wip) { ?> selected="selected" <?php } ?>>
                                <?php echo $ikelbrgwip->i_kelbrg_wip."-".$ikelbrgwip->e_nama_kel;?></option>
                                <?php endforeach; ?> 
                            </select>                         
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