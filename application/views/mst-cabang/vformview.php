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
                    <label class="col-md-6">Kode Cabang</label>
                    <label class="col-md-6">Nama Cabang</label>
                    <div class="col-sm-6">
                        <input type="text" name="ibranch" class="form-control" required="" maxlength="7" onkeyup="gede(this)" value="<?= $data->i_branch; ?>" readonly>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="ebranchname" class="form-control"  value="<?= $data->e_branch_name; ?>" readonly>
                    </div>
                </div>       
                <div class="form-group row">
                    <label class="col-md-6">Kode Area</label>
                    <label class="col-md-6">Inisial</label>
                    <div class="col-sm-6">
                        <input type="text" name="ecodearea" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_code; ?>"readonly>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="einitial" class="form-control" maxlength="30"  value="<?= $data->e_initial; ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-6">Pelanggan</label>
                    <label class="col-md-6">Kota</label>
                    <div class="col-sm-6">
                        <select name="icustomer" id="icustomer" class="form-control select2" disabled="">
                            <option value="">Pilih Kode Pelanggan</option>
                            <?php foreach ($pelanggan as $icustomer):?>
                                <option value="<?php echo $icustomer->i_customer;?>"
                                    <?php if($icustomer->i_customer==$data->i_customer) { ?> selected="selected" <?php } ?>>
                                    <?php echo $icustomer->i_customer.'-'.$icustomer->e_customer_name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                            <input type="text" name="ecity" class="form-control" maxlength="30"  value="<?= $data->e_branch_city; ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Alamat</label>
                    <div class="col-sm-12">
                        <input type="text" name="ebranchaddress" class="form-control" maxlength="30"  value="<?= $data->e_branch_address; ?>" readonly>
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