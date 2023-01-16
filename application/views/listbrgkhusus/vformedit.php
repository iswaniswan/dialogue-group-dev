<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Kode Product</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproduct" class="form-control" required="" maxlength="7"
                            onkeyup="gede(this)" value="<?= $data->i_product; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Product Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductsupplier" class="form-control" required="" maxlength="7"
                            onkeyup="gede(this)" value="<?= $data->i_product_supplier; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <select name="isupplier" class="form-control select2">
                                <?php foreach ($isupplier as $r):?>
                                    <option value="<?php echo $r->i_supplier;?>"
                                        <?= $r->i_supplier == $data->i_supplier ? "selected" : "" ?>>
                                        <?php echo $r->i_supplier." - ".$r->e_supplier_name;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Group Produk</label>
                        <div class="col-sm-12">
                            <select name="iproductgroup" id="iproductgroup" class="form-control select2">
                                <?php foreach ($iproductgroup as $r):?>
                                    <option value="<?php echo $r->i_product_group;?>"
                                        <?= $r->i_product_group == $data->i_product_group ? "selected" : "" ?>>
                                        <?php echo $r->i_product_group." - ".$r->e_product_groupname;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kategori</label>
                        <div class="col-sm-12">
                            <select name="iproductclass" id="iproductclass" class="form-control select2">
                                <?php foreach ($iproductclass as $r):?>
                                    <option value="<?php echo $r->i_product_class;?>"
                                        <?= $r->i_product_class == $data->i_product_class ? "selected" : "" ?>>
                                        <?php echo $r->i_product_class." - ".$r->e_product_classname;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Harga Eceran</label>
                        <div class="col-sm-12">
                            <input type="text" name="vproductretail" class="form-control" maxlength="30"
                            value="<?= $data->v_product_retail; ?>">
                        </div>
                    </div>
                    <?php if ($data->d_product_stopproduction!='') {
                        $dstp = date('d-m-Y', strtotime($data->d_product_stopproduction));
                    }else{
                        $dstp = null;
                    }?>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Stop Produksi</label>
                        <div class="col-sm-12">
                            <input type="text" readonly name="dproductstopproduction" class="form-control date"
                            value="<?= $dstp; ?>">
                        </div>
                    </div>
                    <div>&nbsp;</div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> 
                                <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12">Nama Produk</label>
                            <div class="col-sm-12">
                                <input type="text" name="eproductname" class="form-control" required=""
                                maxlength="50" value="<?= $data->e_product_name; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Nama Produk Supplier</label>
                            <div class="col-sm-12">
                                <input type="text" name="eproductsuppliername" class="form-control" required=""
                                maxlength="50" value="<?= $data->e_product_suppliername; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Status Produk</label>
                            <div class="col-sm-12">
                                <select name="iproductstatus" class="form-control select2">
                                    <?php foreach ($iproductstatus as $r):?>
                                        <option value="<?php echo $r->i_product_status;?>"
                                            <?= $r->i_product_status == $data->i_product_status ? "selected" : "" ?>>
                                            <?php echo $r->i_product_status." - ".$r->e_product_statusname;?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Jenis</label>
                            <div class="col-sm-12">
                                <select name="iproducttype" id="iproducttype" class="form-control select2">
                                    <option value="<?= $data->i_product_type;?>"><?= $data->e_product_typename;?></option>
                                    <!-- <?php foreach ($iproducttype as $r):?>
                                        <option value="<?php echo $r->i_product_type;?>"
                                            <?= $r->i_product_type == $data->i_product_type ? "selected" : "" ?>>
                                            <?php echo $r->i_product_type." - ".$r->e_product_typename;?>
                                        </option>
                                    <?php endforeach; ?> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Sub Kategori</label>
                            <div class="col-sm-12">
                                <select name="iproductcategory" id="iproductcategory" class="form-control select2">
                                    <option value="<?= $data->i_product_category;?>"><?= $data->e_product_categoryname;?></option>
                                    <!-- <?php foreach ($iproductcategory as $r):?>
                                        <option value="<?php echo $r->i_product_category;?>"
                                            <?= $r->i_product_category == $data->i_product_category ? "selected" : "" ?>>
                                            <?php echo $r->i_product_category." - ".$r->e_product_categoryname;?>
                                        </option>
                                    <?php endforeach; ?> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Harga Pabrik</label>
                            <div class="col-sm-12">
                                <input type="text" name="vproductmill" class="form-control" maxlength="30"
                                onkeyup="gede(this)" value="<?= $data->v_product_mill; ?>">
                            </div>
                        </div>
                        <?php if ($data->d_product_register!='') {
                            $ddf = date('d-m-Y', strtotime($data->d_product_register));
                        }else{
                            $ddf = null;
                        }?>
                        <div class="form-group">
                            <label class="col-md-12">Tanggal Pendaftaran</label>
                            <div class="col-sm-12">
                                <input type="text" name="dproductregister" value="<?= $ddf;?>" class="form-control date" readonly>
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
                    </div>  
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
    
    $(document).ready(function () {
        $(".select2").select2();
        showCalendar('.date');

        $('#iproducttype').select2({
            placeholder: 'Pilih Jenis',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datatype'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iproductgroup: $('#iproductgroup').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })

        $('#iproductcategory').select2({
            placeholder: 'Pilih Sub Kategori',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datakategori'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iproductclass: $('#iproductclass').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })
    });

    function jenis(){
        $('#iproducttype').html('');
        $('#iproducttype').val('');
    }

    function kategori(){
        $('#iproductcategory').html('');
        $('#iproductcategory').val('');
    }
</script>