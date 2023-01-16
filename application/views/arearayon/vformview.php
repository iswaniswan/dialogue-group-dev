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
                        <label class="col-md-12">Kode Customer</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" required="" maxlength="20" onkeyup="gede(this)" value="<?= $data->i_customer; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Customer</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" required="" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_customer_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" maxlength="50" value="<?= $data->e_area_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Rayon</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" maxlength="50" value="<?= $data->e_area_rayon_name; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>