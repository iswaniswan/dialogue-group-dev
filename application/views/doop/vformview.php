<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Area</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="text" class="form-control input-sm" value="<?= $data->i_document; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->d_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->e_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-3">Tanggal Referensi</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->e_customer_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->i_referensi; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->d_referensi; ?>">
                        </div>
                        <div class="col-sm-3">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Warna</th>
                        <th class="text-right">FC</th>
                        <!-- <th class="text-right">Stock</th>
                        <th class="text-right">Stock Outstanding</th> -->
                        <th class="text-right">Qty Order</th>
                        <th class="text-right">Qty SJ</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if ($datadetail) {
                        if($data->e_jenis_spb == 'Transfer') { 
                            $i = 0;
                            $ii = 0;
                            $group = "";
                            foreach ($datadetail as $d) {
                                $ii++;
                                if ($group != $d->i_product_base) {
                                    $i++; ?>
                                    <tr class="tr list-item tr_first<?= $i ?>">
                                        <td class="text-center">
                                            <spanlistx id="snum<?= $i ?>"><b><?= $i ?></b></spanlistx>
                                        </td>
                                        <td><?= $d->i_product_base ?></td>
                                        <td><?= $d->e_product_basename ?></td>
                                        <td colspan="2"></td>
                                        <td class="text-right"><?= $d->nquantity_permintaan ?></td>
                                        <td colspan="2"></td>
                                    </tr>
                                <?php }
                                $group = $d->i_product_base; ?>
                                <tr>
                                    <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                    <td><?= $d->i_product_base; ?></td>
                                    <td><?= $d->e_product_basename; ?></td>
                                    <td><?= $d->e_color_name; ?></td>
                                    <td class="text-right"><?= $d->n_quantity_fc; ?></td>
                                    <!-- <td class="text-right"><?= $d->saldo_akhir; ?></td>
                                    <td class="text-right"><?= $d->n_stock_outstanding; ?></td> -->
                                    <td class="text-right"><?= $d->nquantity_permintaan; ?></td>
                                    <td class="text-right"><?= $d->n_quantity; ?></td>
                                    <td><?= $d->e_remark; ?></td>
                                </tr>
                                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                        <?php } ?>
                            <input type="hidden" name="jml" id="jml" value="<?= $ii; ?>">
                        <?php } else {
                        $i = 0;
                        foreach ($datadetail as $row) {
                            $i++;
                    ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $row->i_product_base; ?></td>
                                <td><?= $row->e_product_basename; ?></td>
                                <td><?= $row->e_color_name; ?></td>
                                <td class="text-right"><?= $row->n_quantity_fc; ?></td>
                                <!-- <td class="text-right"><?= $row->saldo_akhir; ?></td>
                                <td class="text-right"><?= $row->n_stock_outstanding; ?></td> -->
                                <td class="text-right"><?= $row->nquantity_permintaan; ?></td>
                                <td class="text-right"><?= $row->n_quantity; ?></td>
                                <td><?= $row->e_remark; ?></td>
                            </tr>
                            
                    <?php }  ?>
                        <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?php }
                    ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        fixedtable($('#tabledatax'));
    });
</script>