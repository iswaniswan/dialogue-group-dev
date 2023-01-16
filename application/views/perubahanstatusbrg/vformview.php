<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="text" class="form-control input-sm" name="idocument" id="idocument" value="<?= $data->i_document; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm" required="" readonly value="<?= $data->d_document; ?>">
                        </div>
                        <div class="col-md-3">
                            <textarea id="eremarkh" name="eremarkh" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                <!-- <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-md-10">
                            <textarea id="eremarkh" name="eremarkh" class="form-control" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div> -->
                <div class="row">
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2 fa-lg"></i>Change Requested</button>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2 fa-lg"></i>Reject</button>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" id="approve" class="btn btn-success btn-block btn-sm"> <i class="fa fa-check-square-o mr-2 fa-lg"></i>Approve</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
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
                        <th style="width: 45%;">Nama Barang</th>
                        <th class="text-right" style="width: 10%;">Jumlah</th>
                        <th class="text-center" style="width: 10%;">Grade Awal</th>
                        <th class="text-center" style="width: 10%;">Grade Akhir</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++; ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td><?= $row->i_product_base . " - " . $row->e_product_basename; ?></td>
                                <td class="text-right"><?= $row->n_quantity; ?></td>
                                <td class="text-center"><?= $row->i_grade_awal; ?></td>
                                <td class="text-center"><?= $row->i_grade_akhir; ?></td>
                                <td><?= $row->e_remark; ?></td>
                            </tr>
                    <?php }
                    } ?>
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });
</script>