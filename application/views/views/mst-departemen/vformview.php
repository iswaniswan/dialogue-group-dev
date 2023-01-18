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
                        <label class="col-md-12">Kode</label>
                        <div class="col-sm-12">
                            <input type="text" name="kode" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" name="nama" class="form-control" value="<?= $data->e_nama; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                          <select name="igudang" class="form-control select2" readonly>
                            <option value="">Pilih Gudang</option>
                            <?php foreach ($gudang as $igudang):?>
                                <option value="<?php echo $igudang->i_kode_master;?>"
                                    <?php if($igudang->i_kode_master==$data->i_kode_master) { ?> selected="selected" <?php } ?>>
                                    <?php echo $igudang->e_nama_master;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>                                 
                </div>
            </div>
        </div>
    </div>
</div>