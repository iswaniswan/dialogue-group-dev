<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lg fa-check"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Tujuan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled>
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="text" name="ibonk" id="ibonk" class="form-control input-sm" value="<?= $data->i_keluar_pengadaan; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<?= $data->d_keluar_pengadaan; ?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled>
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= "$row->id_company|$row->i_bagian"; ?>" <?php if ($row->id_company.'|'.$row->i_bagian == $data->id_company_bagian.'|'.$data->i_tujuan) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name.' ['.$row->name.']'; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $i = 0;
        if ($datadetail) { ?>
            <div class="white-box" id="detail">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-11">
                            <h3 class="box-title m-b-0">Detail Barang</h3>
                        </div>
                        <div class="col-sm-1" style="text-align: right;">
                            <?= $doc; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 3%;">No</th>
                                            <th class="text-center" style="width: 55%;">Barang</th>
                                            <th class="text-right" style="width: 10%;">Qty</th>
                                            <th class="text-left" style="width: 10%;">Periode</th>
                                            <th class="text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $group = "";
                                        foreach ($datadetail as $key) { ?>
                                            <tr id="tr<?= $i; ?>">
                                                <?php if ($group == "") {
                                                    $i++; ?>
                                                    <td class="text-center"><?= $i; ?></td>
                                                    <td class="text-left">
                                                        <?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?= $key->n_quantity_wip; ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?= $key->periode; ?>
                                                    </td>
                                                    <td class="text-left"><?= $key->e_remark; ?></td>
                                                    <?php } else {
                                                    if ($group != $key->id_product_wip) {
                                                        $i++; ?>
                                                        <td class="text-center"><?= $i; ?></td>
                                                        <td class="text-left">
                                                            <?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?= $key->n_quantity_wip; ?>
                                                        </td>
                                                        <td class="text-left">
                                                            <?= $key->periode; ?>
                                                        </td>
                                                        <td class="text-left"><?= $key->e_remark; ?></td>
                                                <?php //$i = 1;
                                                    }
                                                } ?>
                                            </tr>
                                            <?php $group = $key->id_product_wip; ?>
                                            <!-- <tr class="del<?= $i; ?>">
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $key->i_material; ?></td>
                                <td><?= $key->e_material_name; ?></td>
                                <td class="text-right"><?= $key->n_quantity_material; ?></td>
                                <td><?= $key->e_remark; ?></td>
                            </tr> -->
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        var $table = $('#tabledatax');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : data,
                search: true,
                showColumns: true,
                // showToggle       : true,
                // clickToSelect    : true,
                fixedColumns: true,
                fixedNumber: 2,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
        })
    });
</script>