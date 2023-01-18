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
                        <label class="col-md-4">Kode Satuan</label>
                        <label class="col-md-8">Nama Satuan</label>
                        <div class="col-sm-4">
                            <input type="text" name="isatuancode" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_satuan_code; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="esatuan" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_satuan_name; ?>" readonly>
                        </div> 
                    </div>       
                    <div class="form-group">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>
                    </div>                             
                </div>
            </div>
        </div>
    </div>
</div>