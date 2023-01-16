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
                        <label class="col-md-12">Kode Sales</label>
                        <div class="col-sm-12">
                            <input type="text" name="isalesman" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value='<?= $data->i_salesman ?>' readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="iarea" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value='<?= $data->i_area ?> - <?= $data->e_area_name ?>' readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Sales</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanname" class="form-control" maxlength="30" onkeyup="gede(this)" value='<?= $data->e_salesman_name ?>' readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanaddress" class="form-control" maxlength="30" onkeyup="gede(this)" value='<?= $data->e_salesman_address ?>' readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmancity" class="form-control" maxlength="30" onkeyup="gede(this)" value='<?= $data->e_salesman_city ?>' readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Pos</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanpostal" class="form-control" maxlength="30" onkeyup="gede(this)" value='<?= $data->e_salesman_postal ?>' readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nomor Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanphone" class="form-control" maxlength="30" onkeyup="gede(this)" value='<?= $data->e_salesman_phone ?>' readonly> 
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>