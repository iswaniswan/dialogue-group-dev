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
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label>
                        <label class="col-md-3">Brand</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodebrgold" id="ikodebrgold" value="<?= $data->i_product_wip;?>">
                            <input readonly type="text" required="" name="ikodebrg" id="ikodebrg" autocomplete="off" class="form-control input-sm" onkeyup="gede(this); clearcode(this);" maxlength="7" value="<?= $data->i_product_wip;?>" placeholder="Harus 7 Digit">
                            <span class="notekode" hidden="true"><b> * Kode Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-6">
                            <input readonly type="text" placeholder="Harus Diisi!!!" name="enamabrg" class="form-control input-sm" maxlength="300" required="" value="<?= $data->e_product_wipname;?>" onkeyup="gede(this);">
                        </div>
                        <div class="col-sm-3">
                            <select name="ibrand" id="ibrand" class="form-control select2" required="" disabled>
                                <?php if ($brand) {
                                    foreach ($brand as $ibrand):?>
                                        <option value="<?= $ibrand->i_brand;?>" <?php if ($ibrand->i_brand==$data->i_brand) {?> selected <?php } ?>> <?= $ibrand->e_brand_name;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>   
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Group Barang</label>
                        <label class="col-md-6">Kategori Barang</label>
                        <div class="col-sm-6">
                            <select name="igroupbrg" id="igroupbrg" class="form-control select2" required="" disabled>
                                <option value="<?= $data->i_kode_group_barang;?>"><?= $data->e_nama_group_barang;?></option>
                            </select>
                        </div>  
                        <div class="col-sm-6">
                            <select name="ikelompok" id="ikelompok" class="form-control select2" required="" disabled>
                                <option value="<?= $data->i_kode_kelompok;?>"><?= $data->e_nama_kelompok;?></option>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Status Produksi</label>
                        <label class="col-md-6">Jenis Satuan</label>
                        <div class="col-sm-6">
                            <select name="istatusproduksi" id="istatusproduksi" class="form-control select2" disabled>
                                <?php if ($statusproduksi) {
                                    foreach ($statusproduksi as $istatusproduksi):?>
                                        <option value="<?= $istatusproduksi->i_status_produksi;?>"><?= $istatusproduksi->e_status_produksi;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>   
                        <div class="col-sm-6">
                            <select name="isatuan" id="isatuan" class="form-control select2" disabled>
                                <?php if ($satuan_barang) {
                                    foreach ($satuan_barang as $isatuan):?>
                                        <option value="<?= $isatuan->i_satuan_code;?>"><?= $isatuan->e_satuan;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>   
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Supplier Utama</label>
                        <div class="col-sm-12">
                            <select name="isupplier" disabled="" id="isupplier" class="form-control select2" data-placeholder="Supplier Masih Kosong">
                                <option value="<?= $data->i_supplier;?>"><?= $data->e_supplier_name;?></option>
                            </select>        
                        </div> 
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Deskripsi</label>
                        <div class="col-sm-12">
                            <input readonly type="text" name="edeskripsi" class="form-control" placeholder="Isi deskripsi jika ada!!!" value="<?= $data->e_remark;?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>     
                </div>   
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Sub Kategori Barang</label>
                        <label class="col-md-6">Style</label>
                        <div class="col-sm-6">
                            <select name="ijenisbrg" id="ijenisbrg" class="form-control select2" disabled>
                                <option value="<?= $data->i_type_code;?>"><?= $data->e_type_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="istyle" id="istyle" class="form-control select2" disabled>
                                <?php foreach ($style as $istyle):?>
                                    <option value="<?= $istyle->i_style;?>" <?php if ($istyle->i_style==$data->i_style) {?> selected <?php } ?>><?= $istyle->e_style_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Panjang</label>
                        <label class="col-md-3">Lebar</label>
                        <label class="col-md-3">Tinggi</label>
                        <label class="col-md-3">Satuan</label>
                        <div class="col-sm-3">
                            <input readonly type="text" name="npanjang" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_panjang;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <input readonly type="text" name="nlebar" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_lebar;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <input readonly type="text" name="ntinggi" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_tinggi;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <input readonly type="hidden" name="isatuanukuran" id="isatuanukuran" class="form-control input-sm" maxlength="30"  value="<?= $data->i_satuan_ukuran;?>" readonly="">
                            <input readonly type="text" name="esatuanukuran" id="esatuanukuran" class="form-control input-sm" maxlength="30"  value="<?= $data->e_satuan_name;?>" readonly="">
                        </div>       
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Berat</label>
                        <label class="col-md-3">Satuan Berat</label> 
                        <label class="col-md-6">Warna</label> 
                        <div class="col-sm-3">
                            <input readonly type="text" name="nberat" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_berat;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <select name="isatuanberat" id="isatuanberat" class="form-control select2" disabled>
                                <?php if ($satuan_berat) {                                        
                                    foreach ($satuan_berat as $isatuanberat):?>
                                        <option value="<?= $isatuanberat->i_satuan_code;?>" <?php if ($isatuanberat->i_satuan_code==$data->i_satuan_berat) {?> selected <?php } ?>><?= $isatuanberat->e_satuan;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="icolor[]" id="icolor" multiple="multiple" class="form-control select2" data-placeholder="Pilih Warna" disabled>
                                <?php if ($color) {
                                    foreach ($color->result() as $row):?>
                                        <option value="<?= $row->i_color;?>" selected><?= $row->e_color_name;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>     
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:40%;">Material</th>
                        <th class="text-center" style="width:20%;">Bagian</th>
                        <th class="text-center" style="width:20%;">Satuan</th>
                        <th class="text-center" style="width:15%;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) { $i++; ?>
                        <tr>
                            <td class="text-center"><?= $i ;?></td>
                            <td><?= $key->i_material.' - '.$key->e_material_name;?></td>
                            <td><?=$key->bagian;?></td>
                            <td><?=$key->e_satuan_name;?></td>
                            <td class="text-right"><?= $key->n_quantity;?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>
<script>
    $(document).ready(function () {
        $(".select2").select2();
    });
</script>
