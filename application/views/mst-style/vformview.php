<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-3">Kode Series</label>
                        <label class="col-md-4">Nama Series</label>
                        <label class="col-md-5">Brand</label>
                        <div class="col-sm-3">
                            <input type="text" name="istyle" class="form-control" required="" onkeyup="gede(this)"
                                value="<?= $data->i_style; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="estylename" class="form-control" required=""
                                value="<?= $data->e_style_name; ?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="estylename" class="form-control" required=""
                                value="<?= $data->brand; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick="show('<?= $folder;?>/cform','#main')"> <i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>