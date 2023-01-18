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
                        <label class="col-md-12">Segmen 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="segmen1" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->segmen1; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No Urut Awal</label>
                        <div class="col-sm-12">
                            <input type="text" name="nourutawal" class="form-control" value="<?= $data->nourut_awal; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">No Urut Akhir</label>
                        <div class="col-sm-12">
                            <input type="text" name="nourutakhir" class="form-control" value="<?= $data->nourut_akhir; ?>" readonly>
                        </div>
                    </div>                                 
                </div>
            </div>
        </div>
    </div>
</div>