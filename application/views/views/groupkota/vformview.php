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
                        <label class="col-md-12">Kode Grup Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="icitygroup" class="form-control" required="" maxlength="4" onkeyup="gede(this)" value="<?= $data->i_city_group ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Grup Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecitygroupname" class="form-control" required="" maxlength="100" onkeyup="gede(this)" value="<?= $data->e_city_groupname ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaname" class="form-control" required="" maxlength="100" onekeyup="gede(this)" value="<?= $data->e_area_name ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tgl Register</label>
                        <div class="col-sm-12">
                            <input type="text" name="dcitygroupentry" class="form-control" required="" maxlength="100" onekeyup="gede(this)" value="<?= $data->d_city_groupentry ?>"readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>