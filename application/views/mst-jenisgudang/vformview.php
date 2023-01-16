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
                        <label class="col-md-5">Kode Jenis Gudang</label>
                        <label class="col-md-7">Nama Jenis Gudang</label>
                        <div class="col-sm-5">
                            <input type="text" name="ikodejenis" class="form-control" value="<?= $data->i_kode_jenis; ?>" readonly>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" name="enamajenis" class="form-control" value="<?= $data->e_nama_jenis; ?>" readonly>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>