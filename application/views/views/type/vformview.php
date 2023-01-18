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
                        <label class="col-md-12">Kode Jenis</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproducttype" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_product_type; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Jenis</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproducttypename" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_typename; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Group</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductgroupname" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_groupname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Judul Print 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproducttypenameprint1" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_typenameprint1; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Judul Print 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproducttypenameprint2" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_typenameprint2; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>