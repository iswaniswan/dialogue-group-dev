<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Kode Product</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproduct" class="form-control" required="" maxlength="7"
                            onkeyup="gede(this); ckbrg(this.value);" value="">
                            <div id="confnomor" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Product Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductsupplier" class="form-control" required="" maxlength="7"
                            onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <select name="isupplier" class="form-control select2">
                                <?php foreach ($supplier as $isupplier):?>
                                    <option value="<?php echo $isupplier->i_supplier;?>">
                                        <?php echo $isupplier->e_supplier_name;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Group Produk</label>
                        <div class="col-sm-12">
                            <select name="iproductgroup" id="iproductgroup" class="form-control select2" onchange="jenis();">
                                <?php foreach ($productgroup as $iproductgroup):?>
                                    <option value="<?php echo $iproductgroup->i_product_group;?>">
                                        <?php echo $iproductgroup->e_product_groupname;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kategori</label>
                        <div class="col-sm-12">
                            <select name="iproductclass" id="iproductclass" class="form-control select2" onchange="kategori();">
                                <?php foreach ($productclass as $iproductclass):?>
                                    <option value="<?php echo $iproductclass->i_product_class;?>">
                                        <?php echo $iproductclass->e_product_classname;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Harga Eceran</label>
                        <div class="col-sm-12">
                            <input type="text" name="vproductretail" class="form-control" maxlength="30" onkeypress="return hanyaAngka(this);" onkeyup="reformat(this);" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Stop Produksi</label>
                        <div class="col-sm-12">
                            <input type="text" readonly name="dproductstopproduction" class="form-control date" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Price List</label>
                        <div class="col-sm-12">
                            <input type="checkbox" class="form-check-input" name="fproductpricelist">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Nama Produk</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductname" class="form-control" required="" maxlength="50"
                            value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Produk Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductsuppliername" class="form-control" required=""
                            maxlength="50" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Status Produk</label>
                        <div class="col-sm-12">
                            <select name="iproductstatus" class="form-control select2">
                                <?php foreach ($productstatus as $iproductstatus):?>
                                    <option value="<?php echo $iproductstatus->i_product_status;?>" <?php if ($iproductstatus->i_product_status=='3') { echo "selected";}?>>
                                        <?php echo $iproductstatus->e_product_statusname;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis</label>
                        <div class="col-sm-12">
                            <select name="iproducttype" id="iproducttype" class="form-control" required="">
                                <option value=""></option>
                                <!-- <?php foreach ($producttype as $iproducttype):?>
                                    <option value="<?php echo $iproducttype->i_product_type;?>">
                                        <?php echo $iproducttype->e_product_typename;?>
                                    </option>
                                <?php endforeach; ?> -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Sub Kategori</label>
                        <div class="col-sm-12">
                            <select name="iproductcategory" id="iproductcategory" class="form-control" required="">
                                <option value=""></option>
                                <!-- <?php foreach ($productcategory as $iproductcategory):?>
                                    <option value="<?php echo $iproductcategory->i_product_category;?>">
                                        <?php echo $iproductcategory->e_product_categoryname;?>
                                    </option>
                                <?php endforeach; ?> -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Harga Pabrik</label>
                        <div class="col-sm-12">
                            <input type="text" name="vproductmill" class="form-control" maxlength="30"
                            onkeypress="return hanyaAngka(this);" onkeyup="reformat(this);" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Pendaftaran</label>
                        <div class="col-sm-12">
                            <input type="text" readonly name="dproductregister" id="dproductregister" class="form-control date" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Margin</label>
                        <div class="col-sm-12">
                            <input type="text" required="" name="nproductmargin" class="form-control" maxlength="30"
                            onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Seri</label>
                        <div class="col-sm-12">
                            <select name="iproductseri" class="form-control select2">
                                <?php foreach ($productseri as $iproductseri):?>
                                    <option value="<?php echo $iproductseri->i_product_seri;?>">
                                        <?php echo $iproductseri->e_product_seriname;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function ckbrg(nomor){
        $.ajax({
            type: "POST",
            url: "<?= site_url($folder.'/Cform/cari_barang');?>",
            data:"nbrg="+nomor,
            success: function(data){
                $("#confnomor").html(data);
                if (data=='Maaf, Kode Brg sudah ada.') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },
            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
    };

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