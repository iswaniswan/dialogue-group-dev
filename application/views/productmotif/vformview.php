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
                        <label class="col-md-12">Kode Motif Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductmotif" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_product_motif; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductmotifname" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_motifname; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>