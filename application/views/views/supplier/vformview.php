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
                        <label class="col-md-12">Kode Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplier" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Group Pemasok</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliergroup" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_supplier_group; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliercity" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_city; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierphone" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_phone; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Pemilik</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierfownername" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_ownername; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">NPWP</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliernpwp" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_npwp; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kontak</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliercontact" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_contact; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Diskon 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierdiscount" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_supplier_discount; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">TOP</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliertoplength" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_supplier_toplength; ?>" readonly>
                        </div>
                    </div>
            </div>

            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Nama </label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliername" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_supplier_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplieraddres" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_address; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Pos</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierpostalcode" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_postalcode; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">FAX</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierfax" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_fax; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat Pemilik</label>
                        <div class="col-sm-12">
                        <input type="text" name="isupplierfowneraddress," class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_owneraddress; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierphone2" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_phone2; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Email</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplieremail" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_email; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Diskon 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierdiscount2" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_supplier_discount2; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">PPN 
                            <?php $check= $data->f_supplier_ppn;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked disabled=disabled/>";
                                } else {
                                   echo "<input type=checkbox disabled=disabled/>"; 
                                }
                            ?>
                        </label>
                        <label class="col-md-3">PKP
                            <?php $check= $data->f_supplier_pkp;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked disabled=disabled/>";
                                } else {
                                   echo "<input type=checkbox disabled=disabled/>"; 
                                }
                            ?>
                        </label>
                    </div>
                    
            </div>
        </div>

            </div>
        </div>
    </div>
</div>