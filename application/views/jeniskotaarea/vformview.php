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
                        <label class="col-md-12">Kode Jenis Kota/Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="icitytypeperarea" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_city_typeperarea; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Jenis Kota/Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecitytyperareaname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_city_typeperareaname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaname" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_area_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecitytypename" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_city_typename; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>