<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Tgl Terdaftar</label>
                        <label class="col-md-4">Kode Barang</label>
                        <label class="col-md-4">Nama Barang</label>
                        <div class="col-sm-4">
                            <input type="text" id="dproductregister" name="dproductregister" class="form-control input-sm" value="<?= format_bulan($data->d_daftar); ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="iproductbase" name="iproductbase" class="form-control input-sm" value="<?= $data->i_product_base ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="eproductbasename" id="eproductbasename" class="form-control input-sm" placeholder="Nama Barang Jadi" required="" value="<?= $data->e_product_basename; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Tambahan Barang Jadi Pelengkap (Optional)</label>
                        <label class="col-md-4">Group Barang</label>
                        <label class="col-md-4">Divisi</label>
                        <div class="col-sm-4">
                            <input type="text" id="id_product_base" name="id_product_base" class="form-control input-sm" value="<?= $data->e_product_tambahan; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="i_kode_group_barang" name="i_kode_group_barang" class="form-control input-sm" value="<?= $data->e_nama_group_barang; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="e_nama_divisi" name="e_nama_divisi" class="form-control input-sm" value="<?= $data->e_nama_divisi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Kategori Barang</label>
                        <label class="col-md-4">Sub Kategori Barang</label>
                        <label class="col-md-4">Kategori Penjualan</label>
                        <div class="col-sm-4">
                            <input type="text" id="e_nama_kelompok" name="e_nama_kelompok" class="form-control input-sm" value="<?= $data->e_nama_kelompok; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="e_type_name" name="e_type_name" class="form-control input-sm" value="<?= $data->e_type_name; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <?php if ($class) {
                                foreach ($class as $key) {
                                    if ($key->id == $data->id_class_product) { ?>
                                        <input type="text" id="e_class_name" name="e_class_name" class="form-control input-sm" value="<?= $key->e_class_name; ?>" readonly>
                            <?php }
                                }
                            } ?>
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="form-group row">
                        <label class="col-md-4">Satuan Barang</label>
                        <label class="col-md-4">Status Produksi</label>
                        <label class="col-md-4">Nama Motif/Warna</label>
                        <div class="col-sm-4">
                            <input type="text" id="e_satuan_name" name="e_satuan_name" class="form-control input-sm" value="<?= $data->e_satuan_name; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="e_status_produksi" name="e_status_produksi" class="form-control input-sm" value="<?= $data->e_status_produksi; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="e_color_name" name="e_color_name" class="form-control input-sm" value="<?= $data->e_color_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Style</label>
                        <label class="col-md-4">Brand</label>
                        <label class="col-md-4">Tgl SPH</label>
                        <div class="col-sm-4">
                            <input type="text" id="e_style_name" name="e_style_name" class="form-control input-sm" value="<?= $data->e_style_name; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="e_brand_name" name="e_brand_name" class="form-control input-sm" value="<?= $data->e_brand_name; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dtanggalpenawaran" class="form-control input-sm" value="<?php if ($data->d_surat_penawaran != '') {
                                                                                                                    echo format_bulan($data->d_surat_penawaran);
                                                                                                                }; ?>" readonly>
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="form-group row">
                        <label class="col-md-4">Tgl Launching SPH</label>
                        <label class="col-md-4">Tgl STP</label>
                        <label class="col-md-4">Surat Penawaran</label>
                        <div class="col-sm-4">
                            <input type="text" name="dlaunching" class="form-control input-sm" value="<?= $data->d_launch; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dstp" class="form-control input-sm" value="<?php if ($data->d_stps != '') {
                                                                                                    echo format_bulan($data->d_stps);
                                                                                                }; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="esuratpenawaran" class="form-control input-sm" value="<?= $data->e_surat_penawaran; ?>" placeholder="Nomor Surat Penawaran" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">HJP</label>
                        <label class="col-md-4">Harga Grosir</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-4">
                            <input type="text" name="ehjp" id="ehjp" autocomplete="off" class="form-control input-sm" onkeyup="angkahungkul(this);reformat(this);" value="<?= number_format($data->v_unitprice); ?>" placeholder="0" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="input" name="fhargagrosir" autocomplete="off" class="form-control input-sm" onkeyup="angkahungkul(this);reformat(this);" value="<?= number_format($data->v_grosir); ?>" placeholder="0" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="edeskripsi" class="form-control input-sm" value="" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform","#main")'><i class="fa fa-lg fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');
    });
</script>