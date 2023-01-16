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
                        <label class="col-md-12">Kode Ekspedisi</label>
                        <div class="col-sm-12">
                            <input type="text" name="iekspedisi" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_ekspedisi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Ekspedisi</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisi" class="form-control" required="" maxlength="30" value="<?= $data->e_ekspedisi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                        <input type="text" name="eareaname" class="form-control" maxlength="30" value="<?= $data->e_area_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisiaddress" class="form-control" maxlength="30" value="<?= $data->e_ekspedisi_address; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisicity" class="form-control" maxlength="30" value="<?= $data->e_ekspedisi_city; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisicity" class="form-control" maxlength="30" value="<?= $data->e_ekspedisi_phone; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Fax</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisifax" class="form-control" maxlength="30" value="<?= $data->e_ekspedisi_fax; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
