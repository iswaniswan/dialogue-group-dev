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
                        <label class="col-md-12">Kode Harga</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductprice" class="form-control" value="<?= $data->i_product_price; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Kode Barang</label>
                        <label class="col-md-6">Kode Barang+Motif</label>
                        <div class="col-sm-6">
                           <select name="iproduct" class="form-control select2" disabled="">
                                <option value="">Pilih Kode Barang</option>
                                <?php foreach($barangjadi as $iproduct): ?>
                                <option value="<?php echo $iproduct->i_product;?>" 
                                <?php if($iproduct->i_product) { ?> selected="selected" <?php } ?>>
                                <?php echo $iproduct->i_product.'-'.$iproduct->e_product_motifname;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                           <input type="text" name="iproductmotif" class="form-control" value="<?= $data->i_product_motif; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Harga</label>
                        <div class="col-sm-12">
                              <input type="text" name="vprice" class="form-control" value="<?= $data->v_price; ?>" readonly>
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