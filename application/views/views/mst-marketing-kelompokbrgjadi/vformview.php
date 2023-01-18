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
                <div class="form-group">
                        <label class="col-md-12">Kode</label>
                        <div class="col-sm-12">
                            <input type="text" name="ikode" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_kel_brg_jadi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Kelompok</label>
                        <div class="col-sm-12">
                            <input type="text" name="enama" class="form-control" maxlength="30"  value="<?= $data->e_nama; ?>" readonly>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eketerangan" class="form-control" maxlength="30"  value="<?= $data->e_keterangan; ?>" readonly>
                        </div>
                    </div>                         
            </div>
        </div>

            </div>
        </div>
    </div>
</div>