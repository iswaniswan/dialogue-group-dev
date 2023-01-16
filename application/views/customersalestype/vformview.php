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
                        <label class="col-md-12">Kode Cara Penjualan Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomersalestype" class="form-control" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_customer_salestype; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama</label>
                        <div class="col-sm-12">
                    <input type="text" name="icustomersalestypename" class="form-control"  maxlength="25" onkeyup="gede(this)" value="<?= $data->e_customer_salestypename; ?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>