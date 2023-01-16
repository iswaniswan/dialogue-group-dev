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
                        <label class="col-md-12">Kode Status Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomerstatus" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?php echo $data->i_customer_status;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerstatusname" class="form-control" required="" maxlength="20" value="<?php echo $data->e_customer_statusname;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Batas Bawah</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerstatusdown" class="form-control" required="" maxlength="2" value="<?php echo $data->n_customer_statusdown;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Batas Atas</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerstatusup" class="form-control" required="" maxlength="2" value="<?php echo $data->n_customer_statusup;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Index</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerstatusindex" class="form-control" required="" maxlength="3" value="<?php echo $data->n_customer_statusindex;?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>