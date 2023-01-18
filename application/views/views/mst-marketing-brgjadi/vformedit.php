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
                     <div class="form-group">
                        <label class="col-md-12">Kode Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductmotif" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_product_motif; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductmotifname" class="form-control" maxlength="30"  value="<?= $data->e_product_motifname; ?>" >
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Kelompok Barang Jadi</label>
                        <div class="col-sm-12">
                        <select name="ikelbrgjadi" class="form-control select2">
                            <option value="">Pilih Kelompok Barang Jadi</option>
                            <?php foreach($kelompokbrgjadi as $ikelbrgjadi): ?>
                            <option value="<?php echo $ikelbrgjadi->i_kel_brg_jadi;?>" 
                            <?php if($ikelbrgjadi->i_kel_brg_jadi==$data->i_kel_brg_jadi) { ?> selected="selected" <?php } ?>>
                            <?php echo $ikelbrgjadi->i_kel_brg_jadi."-".$ikelbrgjadi->e_nama;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                    </div>               
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
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
</script>
