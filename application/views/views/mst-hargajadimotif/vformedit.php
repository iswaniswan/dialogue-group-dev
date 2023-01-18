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
                        <label class="col-md-12">Kode Harga</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductprice" class="form-control" required=""  value="<?= $data->i_product_price; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Kode Barang</label>
                        <label class="col-md-6">Kode Barang+Motif</label>
                        <div class="col-sm-6">
                           <select name="iproduct" class="form-control select2" readonly>
                                <option value="">Pilih Kode Barang</option>
                                <?php foreach($barangjadi as $iproduct): ?>
                                <option value="<?php echo $iproduct->i_product;?>" 
                                <?php if($iproduct->i_product) { ?> selected="selected" <?php } ?>>
                                <?php echo $iproduct->i_product.'-'.$iproduct->e_product_motifname;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                           <input type="text" name="iproductmotif" class="form-control" required=""  value="<?= $data->i_product_motif; ?>" readonly>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Harga</label>
                        <div class="col-sm-12">
                              <input type="text" name="vprice" class="form-control" required=""  value="<?= $data->v_price; ?>">
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
