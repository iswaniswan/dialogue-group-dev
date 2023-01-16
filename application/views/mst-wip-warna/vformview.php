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
                        <label class="col-md-4">Kode Warna</label>
                        <label class="col-md-8">Nama Warna</label>
                        <div class="col-sm-4">
                            <input type="text" name="icolorcode" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_color; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="ecolorname" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_color_name; ?>" readonly>
                        </div>  
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>