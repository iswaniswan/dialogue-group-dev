<style>
    .nowrap {
        white-space: nowrap !important;
        font-size: 12px;
    }

    .form-group {
        margin-bottom: 10px !important;
    }

    .table>thead>tr>th {
        padding: 6px 6px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Referensi</label>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm date" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm" name="idocument" id="idocument" value="<?= $data->i_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm date" name="ddocument" id="ddocument" value="<?= date("d-m-Y", strtotime($data->d_document)); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm date" name="idreferensi" id="idreferensi" value="<?= $data->i_document_referensi . ' - [' . $data->periode . ']'; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Group Jahit</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-6">
                            <textarea readonly id="group_jahit" name="group_jahit" class="form-control input-sm"><?= $data->e_group_jahit; ?></textarea>
                        </div>
                        <div class="col-sm-6">
                            <textarea readonly id="keterangan" name="keterangan" class="form-control input-sm"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($detail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table nowrap inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th>Tgl. Schedule</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th class="text-right">Uraian Jahit</th>
                            <!-- <th class="text-right">Uraian Jahit Sisa</th> -->
                            <th class="text-right">Schedule</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($detail as $key) {
                            $d_schedule = $key->d_schedule;
                            if ($d_schedule != '') {
                                $d_schedule = formatdmY($key->d_schedule);
                            }
                            $i++;
                        ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $d_schedule; ?></td>
                                <td><?= $key->i_product_wip; ?></td>
                                <td><?= $key->e_product_wipname; ?></td>
                                <td><?= $key->e_color_name; ?></td>
                                <td class="text-right"><?= $key->n_quantity_uraian; ?></td>
                                <!-- <td class="text-right"><?= $key->n_quantity_uraian_sisa; ?></td> -->
                                <td class="text-right"><?= $key->n_quantity; ?></td>
                                <td><?= $key->e_remark; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<?php } ?>
<script>
    fixedtable($('#tabledatax'));
</script>