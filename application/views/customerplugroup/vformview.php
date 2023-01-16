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
                        <label class="col-md-12">Kode Group PLU</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomerplugroup" class="form-control" value="<?= $data->i_customer_plugroup; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerplugroupname" class="form-control" value="<?= $data->e_customer_plugroupname; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>