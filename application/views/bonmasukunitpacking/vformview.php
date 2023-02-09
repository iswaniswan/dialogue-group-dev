<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Bagian Pengirim</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" id="id" name="id" value="<?= $data->id;?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_pengirim;?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen Reff</label>
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_referensi;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2" disabled>
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row):?>
                                        <option value="<?= $row->id;?>" <?php if ($row->id==$data->id_jenis_barang_keluar) {?> selected <?php } ?>>
                                            <?= $row->e_jenis_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea readonly="" type="text" class="form-control input-sm"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" 
                                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;">
                                    <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) { ?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="10%">Kode</th>
                        <th class="text-center" width="30%">Nama Barang</th>
                        <th class="text-center" width="12%">Warna</th>
                        <th class="text-center" width="8%">Qty Kirim</th>
                        <th class="text-center" width="10%">Qty Terima</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $result) { 
                        $product = $result['product'];
                        ?>

                        <?php if ($i >= 1) { ?>
                            <tr class="table-info">
                                <td colspan="8">
                                    <hr class="mb-0 mt-0">
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td class="text-center"><?=$i+1;?></td>
                            <td><?= $product->i_product;?></td>
                            <td><?= $product->e_product;?></td>
                            <td><?= $product->e_color_name;?></td>
                            <td class="text-right"><?= $product->n_quantity_reff;?></td>
                            <td class="text-right"><?= $product->n_quantity;?></td>
                            <td><?= $product->e_remark;?></td>
                        </tr>

                        <?php if (!empty($result['bundling'])) { ?>
                            <tr class="th<?= $i; ?> bold table-active">
                                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                <td colspan="7"><b>Bundling Produk</b></td>
                            </tr>

                            <?php $o = 1; ?>
                            <?php foreach($result['bundling'] as $bundling) { ?>

                            <tr>
                                <td class="text-center"><spanx id="snum<?= $i; ?>"><?= $o; ?></spanx></td>
                                <td><?= $bundling->i_product_base; ?></td>
                                <td  class="d-flex justify-content-between"><span><?= $bundling->e_product_basename; ?></span></td>
                                <td><?= $bundling->e_color_name; ?></td>
                                <td class="text-right"><?= $bundling->n_quantity_bundling; ?></td>
                                <td colspan="2"><?= $bundling->e_remark; ?></td>
                            </tr>

                            <?php $o++; ?>                            
                            <?php } ?>
                            
                        <?php } ?>

                    <?php $i++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>