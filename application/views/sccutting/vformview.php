<style type="text/css">
    .font {
        font-size: 12px;
    }

    #table td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
        white-space:nowrap !important;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-eye mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Keterangan</label>
                            <div class="col-sm-3">
                                <input type="text" readonly="" autocomplete="off" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="i_document" required="" id="i_stb_sj" readonly="" autocomplete="off" class="form-control input-sm" value="<?= $data->i_document; ?>">
                                    <input type="hidden" id="id" name="id" value="<?= $data->id; ?>">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= $data->date_document; ?>" readonly>
                            </div>
                            <div class="col-sm-4">
                            <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="form-group row">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
            </div>
            <div class="col-sm-6 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
        </div>
        <div class="table-responsive">
            <table id="sitabelview" class="table color-table nowrap inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th>Perusahaan</th>
                        <th>Tgl. Schedule</th>
                        <th>Jam</th>
                        <th>WIP</th>
                        <th>Nama WIP</th>
                        <th>Material</th>
                        <th>Nama Material</th>
                        <th>Satuan</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Qty Product<i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="round down(qty material * set / gelar + 0.01)"></i></th>
                        <th class="text-right">Jml Gelar<i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="round down(n quantity product) / set"></th>
                        <th>PIC Cutting</th>
                        <th>PIC Gelar</th>
                        <th>Realisasi Gelar</th>
                        <th>Realisasi Product</th>
                        <!-- <th>Tgl. Cutting</th> -->
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $key) {
                            $i++; ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><b><?= $i; ?></b></spanx>
                                </td>
                                <td><?= $key->name; ?></td>
                                <td><?= formatdmY($key->d_schedule); ?></td>
                                <td><?= $key->jam; ?></td>
                                <td><?= $key->i_product_wip; ?></td>
                                <td><?= $key->e_product_wipname.' - '.$key->e_color_name; ?></td>
                                <td><?= $key->i_material; ?></td>
                                <td><?= $key->e_material_name; ?></td>
                                <td><?= $key->e_satuan_name; ?></td>
                                <td class="text-right"><?= $key->n_quantity; ?></td>
                                <td class="text-right"><?= $key->n_quantity_product; ?></td>
                                <td class="text-right"><?= $key->n_jumlah_gelar; ?></td>
                                <td><?= @$key->e_pic_cutting; ?></td>
                                <td><?= @$key->e_pic_gelar; ?></td>
                                <td><?= ($key->n_realisasi_gelar) ? $key->n_realisasi_gelar : 0; ?></td>
                                <td><?= ($key->n_realisasi_product) ? $key->n_realisasi_product : 0; ?></td>
                                <!-- <td><?= formatdmY($key->d_cutting); ?></td> -->
                                <td><?= $key->e_remark; ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // fixedtable($('#sitabelview'));
        var $table = $('#sitabelview');
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
                fixedNumber: 3,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
            popover();
        });
    });
</script>