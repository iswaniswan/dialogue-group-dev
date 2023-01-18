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
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="iarea" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?php echo $data->i_area;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomer" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?php echo $data->i_customer;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Penyetor</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomersetor" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?php echo $data->e_customer_setorname;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No. Rekening</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerrekening" class="form-control" required="" maxlength="30" value="<?php echo $data->e_customer_setorrekening;?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>