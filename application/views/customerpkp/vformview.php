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
                        <label class="col-md-12">Kode Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomer" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?php echo $data->i_customer;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama PKP</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerpkpname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?php echo $data->e_customer_pkpname;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat PKP</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerpkpaddress" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?php echo $data->e_customer_pkpaddress;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">NPWP</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerpkpnpwp" class="form-control" required="" maxlength="30" value="<?php echo $data->e_customer_pkpnpwp;?>" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>