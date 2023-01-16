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
                        <label class="col-md-12">Kode Product</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproduct" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_product; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Product Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductsupplier" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_product_supplier; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="esuppliername" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_name; ?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Group Produk</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductgroup" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_product_group; ?>" readonly>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-md-12">Kategori</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductclass" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_classname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Harga Eceran</label>
                        <div class="col-sm-12">
                            <input type="text" name="vproductretail" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->v_product_retail; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Stop Produksi</label>
                        <div class="col-sm-12">
                            <input type="text" name="dproductstopproduction" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->d_product_stopproduction; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">Price List 
                            <?php $check= $data->f_product_pricelist;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked disabled=disabled/>";
                                } else {
                                   echo "<input type=checkbox disabled=disabled/>"; 
                                }
                            ?>
                        </label>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">TOP</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliertoplength" class="form-control" maxlength="30" onkeyup="gede(this)" value="<#?= $data->n_supplier_toplength; ?>" readonly>
                        </div>
                    </div>  -->
            </div>
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Produk</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductname" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_product_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Produk Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductsuppliername" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_suppliername; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Status Produk</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductstatus" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_statusname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproducttype" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_typename; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Sub Kategori</label>
                        <div class="col-sm-12">
                        <input type="text" name="iproductcategory," class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_categoryname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Harga Pabrik</label>
                        <div class="col-sm-12">
                            <input type="text" name="vproductmill" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->v_product_mill; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Pendaftaran</label>
                        <div class="col-sm-12">
                            <input type="text" name="dproductregister" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->d_product_register; ?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Margin</label>
                        <div class="col-sm-12">
                            <input type="text" name="nproductmargin" class="form-control" maxlength="30" onkeyup="gede(this)" value="<#?= $data->n_product_margin; ?>" readonly>
                        </div>
                    </div> -->
                    <!-- <div class="form-group">
                        <label class="col-md-12">Seri</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductseri" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_product_seri; ?>" readonly>
                        </div>
                    </div> -->
                    <div class="form-group">
                        
                    </div>
                    
            </div>
        </div>

            </div>
        </div>
    </div>
</div>