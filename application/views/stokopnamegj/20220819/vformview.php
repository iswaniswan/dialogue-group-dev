<style type="text/css">
    .tableFixHead {
        white-space: nowrap !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-2">
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $datahead->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?= $datahead->id; ?>" readonly="">
                            <input type="text" name="idocument" id="idocument" class="form-control input-sm" value="<?= $datahead->i_document; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $datahead->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea name="eremarkh" id="eremarkh" class="form-control input-sm" readonly><?= $datahead->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
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
        <div class="table_fixed" style="width: 100%; max-height: 600px;">
            <table id="tabledatay" class="table color-table tableFixHead inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Warna</th>
                        <th class="text-right">Jumlah SO</th>
                        <th class="text-right">Jumlah SO Repair</th>
                        <th class="text-right">Jumlah SO Grade B</th>
                        <th>Keterangan</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-center">TOTAL</th>
                        <th class="text-right" id="total">
                            <?php $total = 0;
                            $total_repair = 0;
                            $total_gradeb = 0;
                            foreach ($datadetail as $rowtotal) {
                                $total += (float) $rowtotal['n_quantity'];
                                $total_repair += (float) $rowtotal['n_quantity_repair'];
                                $total_gradeb += (float) $rowtotal['n_quantity_gradeb'];
                            }
                            echo $total; ?></th>
                        <th class="text-right" id="total_repair"><?= $total_repair;?></th>
                        <th class="text-right" id="total_gradeb"><?= $total_gradeb;?></th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    foreach ($datadetail as $key) {
                        $i++; ?>
                        <tr>
                            <td class="text-center">
                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                            </td>
                            <td><?= $key['i_product_wip']; ?></td>
                            <td><?= $key['e_product_wipname']; ?></td>
                            <td><?= $key['e_color_name']; ?></td>
                            <td class="text-right"><?= $key["n_quantity"]; ?></td>
                            <td class="text-right"><?= $key["n_quantity_repair"]; ?></td>
                            <td class="text-right"><?= $key["n_quantity_gradeb"]; ?></td>
                            <td><?= $key["e_remark"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".table_fixed").freezeTable({
            'columnNum': 3,
            'scrollable': true,
        });
    });

    function approve() {
        statuschange('<?= $folder . "','" . $id; ?>', '6', '<?= $dfrom . "','" . $dto; ?>');
    }
</script>