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
                        <label class="col-md-12">Kode Group PLU</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomerplugroup" class="form-control" required="" maxlength="3" value="<?= $data->i_customer_plugroup; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode PLU</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomerplu" class="form-control" value="<?= $data->i_customer_plu; ?>" maxlength="10" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Produk</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproduct" class="form-control" value="<?= $data->i_product; ?>" maxlength="7" onkeyup="gede(this)" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-1" style="width:4%">Aktif</label>
                        <div class="col-md-3">
                            <input type="checkbox" name="fcustomerpluaktif" <?php if($data->f_customer_pluaktif=='t') echo "checked"; ?>>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>