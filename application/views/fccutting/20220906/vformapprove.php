<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Periode Forecast Jahit</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                                <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                    <input type="hidden" name="idocumentold" id="ifccuttingold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" required="" id="ifccutting" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="25" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" id="idforecast" name="idforecast" required="" value="<?= $data->id_referensi; ?>">
                                <input type="hidden" id="iperiode" name="iperiode" required="" value="<?= $data->tahun . $data->bulan; ?>">
                                <input type="text" class="form-control input-sm" readonly value="<?= $this->fungsi->mbulan($data->bulan) . ' ' . $data->tahun; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','3','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
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
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Item</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table inverse-table table-bordered" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th width="3%;" class="text-center">No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Warna</th>
                                <th class="text-right">Sisa Schedule<br>Berjalan</th>
                                <th class="text-right">Stock<br>Pengadaan</th>
                                <th class="text-right">Stock<br>Pengesetan</th>
                                <th class="text-right">Sisa Permintaan<br>Cutting</th>
                                <th class="text-right">Kondisi Stock<br>Persiapan Cutting</th>
                                <th class="text-right">Schedule Jahit</th>
                                <th class="text-right">Total Sisa</th>
                                <th class="text-right">Up Qty</th>
                                <th class="text-right">FC Cutting</th>
                                <th class="text-right">Set</th>
                                <th class="text-right">Jumlah Gelar</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            $group = "";
                            foreach ($datadetail as $key) {
                                $i++;
                                $no++;
                                $sisa_schedule = $key->n_sisa_schedule_berjalan;
                                $stok_pengadaan = $key->n_stock_pengadaan;
                                $stok_pengesetan = $key->n_stock_pengesetan;
                                $sisa_permintaan = $key->n_sisa_permintaan_cutting;
                                $kondisi_stock = ($stok_pengadaan + $stok_pengesetan + $sisa_permintaan) - $sisa_schedule;
                                $schedule_jahit = $key->n_schedule_jahit;
                                $total_sisa = $schedule_jahit - ($kondisi_stock);
                                $up_qty = $key->n_up_cutting;
                                $fc_cutting = $total_sisa + $up_qty;
                                $v_set = $key->v_set;
                                $v_gelar = 0;
                                if ($v_set > 0) {
                                    $v_gelar = $fc_cutting / $v_set;
                                }
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no; ?></td>
                                    <td><?= $key->i_product_wip; ?></td>
                                    <td><?= ucwords(strtolower($key->e_product_wipname)); ?></td>
                                    <td><?= ucwords(strtolower($key->e_color_name)); ?></td>
                                    <td class="text-right"><?= $sisa_schedule; ?></td>
                                    <td class="text-right"><?= $stok_pengadaan; ?></td>
                                    <td class="text-right"><?= $stok_pengesetan; ?></td>
                                    <td class="text-right"><?= $sisa_permintaan; ?></td>
                                    <td class="text-right"><?= $kondisi_stock; ?></td>
                                    <td class="text-right"><?= $schedule_jahit; ?></td>
                                    <td class="text-right"><?= $total_sisa; ?></td>
                                    <td class="text-right"><?= $up_qty; ?></td>
                                    <td class="text-right"><?= $fc_cutting; ?></td>
                                    <td class="text-right"><?= $v_set; ?></td>
                                    <td class="text-right"><?= $v_gelar; ?></td>
                                    <td><?= $key->e_remark; ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">

</form>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        $('.select2').select2();
        /* fixedtable($('#tabledatay')); */
        var $table = $('#tabledatay');

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
                fixedNumber: 4,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
        })
    });
</script>