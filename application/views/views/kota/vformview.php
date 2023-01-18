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
                        <label class="col-md-12">Kode Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="icity" class="form-control" required="" maxlength="7" onkeyup="gede(this)" value="<?= $data->i_city; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecityname" class="form-control" required="" maxlength="100" onkeyup="gede(this)" value="<?= $data->e_city_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="iareaname" class="form-control" maxlength="100" onkeyup="gede(this)" value="<?= $data->e_area_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="icitytype" class="form-control" maxlength="100" onkeyup="gede(this)" value="<?= $data->e_city_typename; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Grup Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="icitygroup" class="form-control" maxlength="100" onkeyup="gede(this)" value="<?= $data->e_city_groupname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Status Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="icitystatus" class="form-control" maxlength="100" onkeyup="gede(this)" value="<?= $data->e_city_statusname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Toleransi SJ Pusat</label>
                        <div class="col-sm-12">
                            <input type="text" name="ntoleransipusat" class="form-control" maxlength="100" onkeyup="gede(this)" value="<?= $data->n_toleransi_pusat; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Toleransi SJ Cabang</label>
                        <div class="col-sm-12">
                            <input type="text" name="ntoleransicabang" class="form-control" maxlength="100" onkeyup="gede(this)" value="<?= $data->n_toleransi_cabang; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>