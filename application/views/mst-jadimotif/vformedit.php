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
                            <input type="text" name="iproduct" class="form-control" value="<?= $data->i_product; ?>" readonly>                         
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Kode Barang+Motif</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductmotif" class="form-control" value="<?= $data->i_product_motif; ?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductmotifname" id="eproductmotifname" class="form-control" value="<?= $data->e_product_motifname; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Quantity</label>
                        <div class="col-sm-12">
                            <input type="text" name="nquantity" class="form-control" value="<?= $data->n_quantity; ?>" >
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Nama Motif/Warna</label>
                        <div class="col-sm-12">
                            <select name="icolor" class="form-control select2" >
                            <option value="">Pilih Nama Motif/Warna</option>
                            <?php foreach ($warna as $icolor):?>
                                <option value="<?php echo $icolor->i_kode_color;?>"
                                    <?php if($icolor->i_kode_color==$data->i_kode_color) { ?> selected="selected" <?php } ?>>
                                    <?php echo $icolor->i_kode_color."-".$icolor->e_color_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>                                                                   
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
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
