<form id="cekinputan">
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-md-4">
                            <textarea class="form-control" readonly><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
   <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                    <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 12%;">Kode Barang</th>
                        <th class="text-center" style="width: 35%;">Nama Barang</th>
                        <th class="text-center" style="width: 10%;">Jumlah</th>
                        <th class="text-center" style="width: 20%;">No Inventaris</th>
                        <th class="text-center">Tujuan Penempatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) {
                        $i++;
                        ?>
                        <tr>
                            <td class="text-center"><?= $i ;?></td>
                            <td><?= $key->i_product;?></td>
                            <td><?= $key->e_product;?></td>
                            <td class="text-right"><?= $key->n_jumlah;?></td>
                            <td><?= $key->e_inventaris;?></td>
                            <td><?= $key->e_tujuan;?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>