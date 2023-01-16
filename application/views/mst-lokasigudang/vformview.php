<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kode Lokasi Unit</label>
                        <label class="col-md-8">Nama Lokasi Unit Kerja</label>
                        <div class="col-sm-4">
                            <input type="text" name="ikodelokasi" class="form-control" value="<?= $data->i_lokasi; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="enamalokasi" class="form-control" value="<?= $data->e_lokasi_name; ?>" readonly>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>