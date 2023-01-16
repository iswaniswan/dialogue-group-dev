<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                        <label class="col-md-3">Tujuan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
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
                                <input type="text" name="iretur" id="iretur" readonly="" class="form-control input-sm" value="<?= $data->i_retur; ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dretur" name="dretur" class="form-control input-sm" required="" readonly value="<?= $data->d_retur; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled="">
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
                        <!-- <label class="col-md-3">Nomor Referensi</label> -->
                        <label class="col-md-12">Keterangan</label>
                        <!-- <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" disabled="">
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_document; ?></option>
                            </select>
                        </div> -->
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
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
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-right">Qty Retur<br><i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Untuk Qty panel yang tersedia"></i></th>
                                    <th class="text-right">Qty Penyusun</th>
                                    <th class="text-right">Qty Akhir</th>
                                    <th class="text-center" style="width: 10%;">Qty Kekurangan</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $group = "";
                                $no = 1;
                                foreach ($datadetail as $key) {
                                    $i++; ?>
                                    <tr id="tr<?= $i; ?>" class="tdna">
                                        <?php if ($group == "") { ?>
                                            <td class="text-center"><?= $no; ?></td>
                                            <td class="text-left"><?= $key->i_product_wip; ?> [ <?= $key->e_marker_name; ?> ]</td>
                                            <td class="text-left" colspan="3">
                                                <?= $key->e_product_wipname . ' - ' . $key->e_color_name; ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $key->max; ?>
                                            </td>
                                            <td colspan="2">
                                                <?= $key->e_remark; ?>
                                            </td>
                                            <?php } else {
                                            if ($group != $key->id_product_wip) { 
                                                $no ++;?>
                                                <td class="text-center"><?= $no; ?></td>
                                                <td class="text-left"><?= $key->i_product_wip; ?> [ <?= $key->e_marker_name; ?> ]</td>
                                                <td class="text-left" colspan="3">
                                                    <?= $key->e_product_wipname . ' - ' . $key->e_color_name; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= $key->max; ?>
                                                </td>
                                                <td colspan="2">
                                                    <?= $key->e_remark; ?>
                                                </td>
                                        <?php }
                                        } ?>
                                    </tr>
                                    <?php $group = $key->id_product_wip; ?>
                                    <tr class="del<?= $i; ?>">
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-left"><?= $key->i_panel . ' - ' . $key->bagian; ?></td>
                                        <td class="text-left"><?= $key->i_material . ' - ' . $key->e_material_name; ?></td>
                                        <td class="text-right"><?= $key->n_qty_retur; ?></td>
                                        <td class="text-right"><?= $key->n_qty_penyusun; ?></td>
                                        <td class="text-right"><?= $key->n_quantity_set_return; ?></td>
                                        <td class="text-right"><?= $key->n_qty_kekurangan; ?></td>
                                        <td><?= $key->e_alasan_retur_repair; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        popover();
        showCalendar('.date');
        $('.select2').select2({
            width: '100%',
        });

        var $table = $('#tabledatax');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : data,
                search: true,
                // showColumns: true,
                // showToggle       : true,
                // clickToSelect    : true,
                fixedColumns: true,
                fixedNumber: 3,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
        })
    });
</script>