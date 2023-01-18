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
                        <label class="col-md-12">Kode Kelas</label>
                        <div class="col-sm-12">
                            <input type="text" name="ikelas" class="form-control"  value="<?= $data->i_class; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Kelas</label>
                        <div class="col-sm-12">
                            <input type="text" name="enamakelas" class="form-control" value="<?= $data->e_class_name; ?>" readonly>
                        </div>
                    </div>                                   
            </div>
        </div>

            </div>
        </div>
    </div>
</div>