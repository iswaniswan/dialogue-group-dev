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
                        <label class="col-md-12">Kode Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="iarea" class="form-control" required="" maxlength="2" onkeyup="gede(this)" value="<?= $data->i_area; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaname" class="form-control" required="" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_area_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Jenis Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="esuppliergroupnameprint1" class="form-control" maxlength="30" value="<?= $data->e_area_typename; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="estorename" class="form-control" maxlength="30" value="<?= $data->e_store_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaaddress" class="form-control" maxlength="300" onkeyup="gede(this)" value="<?= $data->e_area_address; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareacity" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_area_city; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Inisial</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareashortname" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_area_shortname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaphone" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_area_phone; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Toleransi</label>
                        <div class="col-sm-12">
                            <input type="text" name="nareatoleransi" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_area_toleransi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="earearemark" class="form-control" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_area_remark; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>