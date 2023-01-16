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
                        <label class="col-md-12">Kode Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproduct" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_product; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Grade</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductgrade" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_product_grade; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Margin</label>
                        <div class="col-sm-12">
                            <input type="text" name="nproductmargin" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_product_margin; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Harga Pabrik</label>
                        <div class="col-sm-12">
                            <input type="text" name="vproductmill" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->v_product_mill; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>