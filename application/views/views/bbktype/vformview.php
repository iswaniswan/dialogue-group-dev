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
                        <label class="col-md-12">Kode Jenis BBK</label>
                        <div class="col-sm-12">
                            <input type="text" name="ibbktype" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_bbk_type; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Jenis BBK</label>
                        <div class="col-sm-12">
                            <input type="text" name="ebbktypename" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_bbk_typename; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>