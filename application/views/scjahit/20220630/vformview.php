<style>
    .nowrap {
        white-space:nowrap !important;
        font-size:12px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal</label>
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm" name="idocument" id="idocument" value="<?= $data->i_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm date" name="ddocument" id="ddocument" value="<?= date("d-m-Y", strtotime($data->d_document)); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm date" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <textarea readonly id="keterangan" name="keterangan" class="form-control input-sm" ><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($detail) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table nowrap inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" >No</th>
                            <th class="text-center" >Tanggal</th>
                            <th class="text-center" >Nama Barang</th>
                            <th class="text-center" >Qty</th>
                            <th class="text-center" >Kategori Unit</th></th></th>
                            <th class="text-center" >Unit Jahit</th></th>
                            <th class="text-center" >Group</th></th></th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php 
                            $i = 0;
                            foreach ($detail as $key) {
                                $i++;
                                ?>
                                <tr>
                                    <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                    <td><?= date("d-m-Y", strtotime($key->d_schedule));?></td>
                                    <td><?= $key->i_product_wip.'-'.$key->e_product_wipname.'-'.$key->e_color_name;?></td>
                                    <td><?= $key->n_quantity_wip;?></td>
                                    <td><?= $key->e_nama_kategori; ?></td>
                                    <td><?= $key->e_nama_unit; ?></td>
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