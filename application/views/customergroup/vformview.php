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
                        <label class="col-md-12">Kode Group Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomergroup" class="form-control" required="" maxlength="2" value="<?= $data->i_customer_group; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomergroupname" class="form-control" required="" maxlength="100" value="<?= $data->e_customer_groupname; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>