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
                    <label class="col-md-6">Kode</label>
                    <label class="col-md-6">Supplier</label>
                    <div class="col-sm-6">
                        <input type="text" name="id" class="form-control" value="<?= $data->i_harga_bisbisan; ?>" readonly>
                        </div>
                        <div class="col-sm-6">                          
                        <select name="isupplier" class="form-control select2" disabled="">
                            <option value="">Pilih Supplier</option>
                            <?php foreach($supplier as $isupplier): ?>
                            <option value="<?php echo $isupplier->i_supplier;?>" 
                            <?php if($isupplier->i_supplier==$data->i_supplier) { ?> selected="selected" <?php } ?>>
                            <?php echo $isupplier->i_supplier."-".$isupplier->e_supplier_name;?></option>
                            <?php endforeach; ?> 
                        </select>                 
                        </div> 
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Jenis Potong</label>
                        <label class="col-md-6">Harga</label>
                        <div class="col-sm-6">
                            <select name="ejenispotong" class="form-control select2" disabled="">
                                <option value="1" <?php if($data->e_jenis_potong =='1') { ?> selected <?php } ?> >Potong Serong</option>
                                <option value="2" <?php if($data->e_jenis_potong =='2') { ?> selected <?php } ?> >Potong Lurus</option>
                                <option value="3" <?php if($data->e_jenis_potong =='3') { ?> selected <?php } ?> >Potong Spiral</option> 
                            </select>    
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="eharga" class="form-control" value="<?= $data->v_price; ?>" readonly>
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