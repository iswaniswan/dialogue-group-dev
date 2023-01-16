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
                        <label class="col-md-12">Kode Group Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliergroup" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_supplier_group; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="esuppliergroupname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_groupname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Judul 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="esuppliergroupnameprint1" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_groupnameprint1; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Judul 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="esuppliergroupnameprint2" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_groupnameprint2; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>