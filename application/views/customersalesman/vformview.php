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
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="iarea" class="form-control" required="" maxlength="2" value="<?= $data->e_area_name; ?>" readonly>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomer" class="form-control" required="" maxlength="100" value="<?= $data->i_customer." - ".$data->e_customer_name; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <input type="text" name="isalesman" class="form-control" required="" maxlength="100" value="<?= $data->i_salesman." - ".$data->e_salesman_name; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Jenis</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductgroup" class="form-control" required="" maxlength="100" value="<?= $data->e_product_groupname; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Periode</label>
                        <div class="col-sm-12">
                            <input type="text" name="eperiode" class="form-control" required="" maxlength="100" value="<?= $data->e_periode; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>