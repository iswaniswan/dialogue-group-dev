<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Teritori</label>
                        <div class="col-sm-12">
                            <input type="text" name="iteritori" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value='<?= @$data->i_teritori ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Teritori</label>
                        <div class="col-sm-12">
                            <input type="text" name="eteritoriname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value='<?= @$data->e_teritori_name ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Negara</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecountryname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value='<?= @$data->e_country_name ?>'>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>