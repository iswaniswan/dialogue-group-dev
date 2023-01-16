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
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-sm-3">Tujuan Makloon</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name;?>">
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>                        
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm date" value="<?= $data->e_nama_kategori;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Type Makloon</label>
                        <label class="col-md-3">Partner Makloon</label>
                        <label class="col-md-6">Keterangan</label>                        
                        
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="itype" id="itype" value="<?= $data->e_type_makloon_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ipartner" id="ipartner" value="<?= $data->e_supplier_name;?>">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="eremarkh" readonly="" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                        <th class="text-center">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th class="text-right">QTY Kirim</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) {
                        $i++;
                        ?>
                        <tr>
                            <td class="text-center"><?= $i ;?></td>
                            <td class="d-flex justify-content-between">
                                <span>
                                    <?= $key->i_panel;?>
                                </span>
                                <span>
                                    <?= $key->e_marker_name ?>
                                </span>
                            </td>
                            <td><?= $key->i_material.' - '.$key->e_material_name;?></td>
                            <td class="text-right"><?= $key->n_quantity;?></td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>