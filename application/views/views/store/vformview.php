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
                        <label class="col-md-12">Kode Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="istore" class="form-control" required="" maxlength="5" value="<?= $data->i_store; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="estorename" class="form-control" required="" maxlength="30" value="<?= $data->e_store_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Register</label>
                        <div class="col-sm-12">
                            <input type="text" name="dstoreregister" class="form-control" maxlength="30" value="<?= date("d-m-Y", strtotime($data->d_store_register)); ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>