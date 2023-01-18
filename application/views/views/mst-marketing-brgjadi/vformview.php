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
                        <label class="col-md-12">Kode Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductmotif" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_product_motif; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductmotifname" class="form-control" maxlength="30"  value="<?= $data->e_product_motifname; ?>" readonly>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Kelompok Barang Jadi</label>
                        <div class="col-sm-12">
                        <select name="ikelbrgjadi" class="form-control select2" readonly>
                            <option value="">Pilih Kelompok Barang Jadi</option>
                            <?php foreach($kelompokbrgjadi as $ikelbrgjadi): ?>
                            <option value="<?php echo $ikelbrgjadi->i_kel_brg_jadi;?>" 
                            <?php if($ikelbrgjadi->i_kel_brg_jadi==$data->i_kel_brg_jadi) { ?> selected="selected" <?php } ?>>
                            <?php echo $ikelbrgjadi->i_kel_brg_jadi."-".$ikelbrgjadi->e_nama;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                    </div>                            
            </div>
        </div>

            </div>
        </div>
    </div>
</div>