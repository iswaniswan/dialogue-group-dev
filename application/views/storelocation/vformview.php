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
                        <label class="col-md-12">Kode Lokasi Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="istorelocation" class="form-control" required="" maxlength="5" value="<?= $data->i_store_location; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Lokasi Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="estorelocationname" class="form-control" required="" maxlength="30" value="<?= $data->e_store_locationname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="estorename" class="form-control" maxlength="30" value="<?= $data->e_store_name; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
