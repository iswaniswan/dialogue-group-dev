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
                        <label class="col-md-4">Kode Brand</label>
                        <label class="col-md-8">Nama Brand</label>
                        <div class="col-sm-4">
                            <input type="text" name="ibrand" class="form-control" onkeyup="gede(this)" required=""  value="<?= $data->i_brand; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="ebrandname" class="form-control" required="" value="<?= $data->e_brand_name; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>