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
            <div class="form-group row">
                        <label class="col-md-6">Kode Pelanggan (Transfer)</label>
                        <label class="col-md-6">Pelanggan</label>
                        <div class="col-sm-6">
                            <input type="text" name="icustomertransfer" class="form-control" maxlength="7"  value="<?= $data->i_customer_transfer; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                        <select name="icustomer" class="form-control select2" disabled="">
                            <option value="">Pilih Pelanggan</option>
                            <?php foreach ($pelanggan as $icustomer):?>
                                <option value="<?php echo $icustomer->i_customer;?>"
                                <?php if($icustomer->i_customer==$data->i_customer) { ?> selected="selected" <?php } ?>>
                                    <?php echo $icustomer->i_customer."-".$icustomer->e_customer_name;?></option>
                            <?php endforeach; ?>
                        </select>                          
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
