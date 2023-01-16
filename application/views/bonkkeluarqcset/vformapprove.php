<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
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
                                <input type="text" name="ibonk" id="ibonk" class="form-control input-sm" value="<?= $data->i_keluar_qcset; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<?= $data->d_keluar_qcset; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled>
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_tujuan) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2" disabled>
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row) : ?>
                                        <option value="<?= $row->id; ?>" <?php if ($row->id == $data->id_jenis_barang_keluar) {
                                                                                echo "selected";
                                                                            } ?>>
                                            <?= $row->e_jenis_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-9">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2 fa-lg"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2 fa-lg"></i>Reject</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o mr-2 fa-lg"></i>Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $i = 0;
        if ($datadetail) { ?>
            <div class="white-box" id="detail">
                <div class="col-sm-3">
                    <h3 class="box-title m-b-0">Detail Barang</h3>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2" style="width: 3%;">No</th>
                                    <th class="text-center" rowspan="2" style="width: 10%;">Kode</th>
                                    <th class="text-center" rowspan="2" style="width: 45%;">Nama Barang</th>
                                    <th class="text-center" colspan="2" style="width: 10%;">Qty</th>
                                    <th class="text-center" rowspan="2">Keterangan</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="width: 10%;">Penyusun</th>
                                    <th class="text-center" style="width: 10%;">Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $group = "";
                                foreach ($datadetail as $key) {
                                    $i++; ?>
                                    <tr id="tr<?= $i; ?>" class="tdna">
                                        <?php if ($group == "") { ?>
                                            <td colspan="3" class="tdna">
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>
                                                    </span>
                                                    <span><?= $key->e_marker_name ?></span>
                                                </div>
                                            </td>

                                            <td class="text-right" colspan="2">
                                                <?= $key->n_quantity_product_wip; ?>
                                            </td>
                                            <td></td>
                                            <?php } else {
                                            if ($group != $key->id_product_wip) { ?>
                                                <td colspan="3" class="tdna">
                                                    <div class="d-flex justify-content-between">
                                                        <span>
                                                            <?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>
                                                        </span>
                                                        <span><?= $key->e_marker_name ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-right" colspan="2">
                                                    <?= $key->n_quantity_product_wip; ?>
                                                </td>
                                                <td></td>
                                        <?php $i = 1;
                                            }
                                        } ?>
                                    </tr>
                                    <?php $group = $key->id_product_wip; ?>
                                    <tr class="del<?= $i; ?>">
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-left"><?= $key->i_panel . ' - ' . $key->bagian; ?></td>
                                        <td class="text-left"><?= $key->i_material . ' - ' . $key->e_material_name; ?></td>
                                        <td class="text-right"><?= $key->n_quantity_penyusun; ?></td>
                                        <td class="text-right"><?= $key->n_quantity_akhir; ?></td>
                                        <td><?= $key->e_remark; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
                showColumns: false,
                // showToggle       : true,
                // clickToSelect    : true,
                fixedColumns: true,
                // fixedNumber: 3,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
        })
    });
</script>