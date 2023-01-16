<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form class="form-horizontal">
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye fa-lg mr-2"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i>List <?= $title; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12 row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Tanggal Promo</label>
                                <input type="hidden" name="i_promo_code" id="i_promo_code" value="<?= $data->i_promo_code; ?>">
                                <input type="hidden" name="id" id="id" value="<?= $data->id_promo; ?>">
                                <input type="text" readonly id= "d_promo" name="d_promo" class="form-control input-sm date" value="<?= date('d-m-Y', strtotime($data->d_promo)); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Tanggal Berlaku Dari</label>
                                <input type="text" required readonly id="d_promo_start" placeholder="Berlaku Dari Tanggal" name="d_promo_start" class="form-control input-sm date" value="<?= date('d-m-Y', strtotime($data->d_promo_start)); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Tanggal Berlaku Sampai</label>
                                <input type="text" required readonly id="d_promo_finish" placeholder="Berlaku Sampai Tanggal" name="d_promo_finish" class="form-control input-sm date" value="<?= date('d-m-Y', strtotime($data->d_promo_finish)); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Nama Promo</label>
                                <input type="text" id="e_promo_name" name="e_promo_name" class="form-control input-sm text-capitalize" value="<?= $data->e_promo_name; ?>" placeholder="Keterangan Promo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Jenis Promo</label>
                                <select name="id_promo_type" id="id_promo_type" required class="form-control select2" data-placeholder="Pilih Tipe Promo">
                                <option value="<?= $data->id_promo_type; ?>"><?= $data->e_promo_type_name; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Kelompok Harga</label>
                                <select name="id_harga" id="id_harga" required class="form-control select2" data-placeholder="Pilih Harga">
                                <option value="<?= $data->id_harga; ?>"><?= $data->e_harga; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 row">
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="f_all_product" name="f_all_product" class="custom-control-input" <?php if ($data->f_all_product == 't') {?> checked <?php } ?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Product</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="f_all_customer" name="f_all_customer" class="custom-control-input" <?php if ($data->f_all_customer == 't') {?> checked <?php } ?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Pelanggan</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="f_all_area" name="f_all_area" class="custom-control-input" value="on"  <?php if ($data->f_all_area == 't') {?> checked <?php } ?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Area</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Discount 1 (%)</label>
                                <input type="number" readonly id="n_promo_discount1" name="n_promo_discount1" class="form-control input-sm" placeholder="Discount Persen" value="<?= $data->n_promo_discount1; ?>" min="0" max="100" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Discount 2 (%)</label>
                                <input type="number" readonly id="n_promo_discount2" name="n_promo_discount2" class="form-control input-sm" placeholder="Discount Persen" value="<?= $data->n_promo_discount2; ?>" min="0" max="100" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12 row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box tableproduct" <?php if ($data->f_all_product == 't') {?> hidden='true' <?php } ?>>
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tableproduct" class="table tabledatax color-table info-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th width="10%;">Kode Barang</th>
                        <th width="40%;">Nama Barang</th>
                        <th class="text-right" width="10%;">Harga</th>
                        <th class="text-right" width="10%;">Minimum Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $p = 0;
                    if ($detail->num_rows() > 0) {
                        foreach ($detail->result() as $key) {
                            $p++; ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $p; ?>"><?= $p; ?></spanx>
                                </td>
                                <td><?= $key->i_product_base; ?></td>
                                <td><?= $key->e_product_name; ?></td>
                                <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                                <td class="text-right"><?= $key->n_quantity_min; ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
                <input type="hidden" id="jml_product" name="jml_product" value="<?= $p; ?>">
            </table>
        </div>
    </div>
</div>
<div class="white-box tablecustomer" <?php if ($data->f_all_customer == 't') {?> hidden='true' <?php } ?>>
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Customer</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tablecustomer" class="table tabledatax color-table success-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th width="10%;">Kodelang</th>
                        <th width="40%;">Nama Pelanggan</th>
                        <th width="47%;">Alamat Pelanggan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $c = 0;
                    if ($customer->num_rows() > 0) {
                        foreach ($customer->result() as $key) {
                            $c++; ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $c; ?>"><?= $c; ?></spanx>
                                </td>
                                <td><?= $key->i_customer;?></td>
                                <td><?= $key->e_customer_name; ?></td>
                                <td><?= $key->e_customer_address; ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
                <input type="hidden" id="jml_customer" name="jml_customer" value="<?= $c; ?>">
            </table>
        </div>
    </div>
</div>
<div class="white-box tablearea"  <?php if ($data->f_all_area == 't') {?> hidden='true' <?php } ?>>
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Area</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tablearea" class="table tabledatax color-table primary-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th width="10%;">Kode Area</th>
                        <th>Nama Area</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $a = 0;
                    if ($area->num_rows() > 0) {
                        foreach ($area->result() as $key) {
                            $a++; ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $a; ?>"><?= $a; ?></spanx>
                                </td>
                                <td><?= $key->i_area; ?></td>
                                <td><?= $key->e_area_name; ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
                <input type="hidden" id="jml_area" name="jml_area" value="<?= $a; ?>">
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $("#id_promo_type").select2({
            dropdownAutoWidth: true,
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?= base_url($folder.'/cform/get_type/'); ?>",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    };
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });

        $("#id_harga").select2({
            dropdownAutoWidth: true,
            width: "100%",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/get_group/'); ?>',
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    };
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });
    });
</script>