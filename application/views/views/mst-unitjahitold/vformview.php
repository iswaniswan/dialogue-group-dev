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
                        <label class="col-md-12">Kode Unit Jahit</label>
                        <div class="col-sm-12">
                            <input type="text" name="iunitjahit" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_unit_jahit; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Unit Jahit</label>
                        <div class="col-sm-12">
                            <input type="text" name="eunitjahitname" class="form-control" value="<?= $data->e_unitjahit_name; ?>" readonly>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Nama Perusahaan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eperusahaanname" class="form-control" required="" maxlength="5" value="<?= $data->e_perusahaan_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Lokasi Perusahaan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eunitjahitaddress" class="form-control" maxlength=""  value="<?= $data->e_unitjahit_address; ?>" readonly>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Nama Penanggung Jawab</label>
                        <div class="col-sm-12">
                            <input type="text" name="epenanggungjawabname" class="form-control" required="" maxlength="5" value="<?= $data->e_penanggung_jawab_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Admin Unit Jahit</label>
                        <div class="col-sm-12">
                            <input type="text" name="eadminname" class="form-control" maxlength=""  value="<?= $data->e_admin_name; ?>" readonly>
                        </div>
                    </div> 
        </div>

            </div>
        </div>
    </div>
</div>