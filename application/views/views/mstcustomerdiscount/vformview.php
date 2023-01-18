<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="form-group">
                        <label class="col-md-12">Kode Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomer" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?php echo $data->i_customer;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerdiscount1" class="form-control" required="" maxlength="6" value="<?php echo $data->n_customer_discount1;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerdiscount2" class="form-control" required="" maxlength="6" value="<?php echo $data->n_customer_discount2;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount 3</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerdiscount3" class="form-control" required="" maxlength="6" value="<?php echo $data->n_customer_discount3;?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>