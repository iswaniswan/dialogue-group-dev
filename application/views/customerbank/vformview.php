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
                            <input type="text" name="icustomer" class="form-control" required="" maxlength="5" value="<?= $data->i_customer; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Bank</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerbankname" class="form-control" value="<?= $data->e_customer_bankname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No Account</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerbankaccount" class="form-control" value="<?= $data->e_customer_bankaccount; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>