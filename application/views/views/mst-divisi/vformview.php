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
                        <label class="col-md-4">Kode Divisi</label>
                        <label class="col-md-8">Nama Divisi</label>
                        <div class="col-sm-4">
                            <input type="text" name="idivisi" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_divisi; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="edivisi" class="form-control" required=""   value="<?= $data->e_nama_divisi; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>         
                </div>
            </div>
        </div>
    </div>
</div>