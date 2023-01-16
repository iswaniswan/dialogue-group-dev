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
                        <label class="col-md-4">Kode Kategori</label>
                        <label class="col-md-8">Nama Kategori</label>
                        <div class="col-sm-4">
                            <input type="text" name="ikode" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_kelbrg_wip; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="enama" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_nama_kel; ?>" readonly>
                        </div> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eketerangan" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_keterangan; ?>" readonly>
                        </div>                              
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>