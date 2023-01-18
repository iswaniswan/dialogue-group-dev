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
                            <input type="text" name="icustomer" class="form-control" required="" maxlength="7" value="<?= $data->i_customer; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Pemilik</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerownername" class="form-control" required="" maxlength="50" value="<?= $data->e_customer_ownername; ?>" onkeyup="gede(this)" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat Pemilik</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerowneraddress" class="form-control" required="" maxlength="100" value="<?= $data->e_customer_owneraddress; ?>" onkeyup="gede(this)" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerownerphone" class="form-control" required="" maxlength="30" value="<?= $data->e_customer_ownerphone; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Penyetor</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomersetor" class="form-control" required="" maxlength="30" value="<?= $data->e_customer_setor; ?>" onkeyup="gede(this)" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>