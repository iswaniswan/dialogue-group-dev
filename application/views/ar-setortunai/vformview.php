<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye fa-lg mr-2"></i> &nbsp;
                <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                    <?= $title_list; ?>
                </a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Area</label>
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->e_bagian_name ?>" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" value="<?= $data->i_st_id ?>" class="form-control input-sm" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->d_st ?>" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->e_area; ?>" class="form-control input-sm" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-6">Nama Bank</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-6">
                            <input type="text" value="<?= $data->e_bank_name; ?>" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea name="keterangan" id="keterangan" rows="2" class="form-control text-left"><?= $data->e_remark ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="white-box" id="detail">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-11">
                        <h3 class="box-title m-b-0">Detail Nota</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabledatax" class="table color-table inverse-table table-bordered class"
                                cellpadding="8" cellspacing="1" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 35px;">No</th>
                                        <th style="width: 200px;">No. Tunai Item</th>
                                        <th style="width: 200px;">Tgl. Tunai Item</th>
                                        <th style="width: auto;">Nama Pelanggan</th>
                                        <th style="width: 200px;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $grand_total_jumlah_nota = 0; $grand_total_jumlah_tunai = 0; ?>
                                    <?php $i = 0; foreach ($datadetail as $item) { $i++; ?>
                                        <tr id="tr<?= $i; ?>">
                                            <td class="text-center">
                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                            </td>
                                            <td><?= $item->i_tunai_id;?></td>
                                            <td><span><?= $item->d_tunai;?></span></td>
                                            <td><span><?= $item->e_customer_name;?></span></td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                                    </div>
                                                    <span><?= number_format($item->v_jumlah, 0, ",", ".") ?></span>
                                                </div>
                                            <?php $grand_total_jumlah_tunai += $item->v_jumlah; ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="4">Total</th>
                                        <th class="text-right">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                                </div>
                                                <span><?= number_format($grand_total_jumlah_tunai, 0, ",", ".") ?></span>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function () {
        hetang();
    });

    function hetang() {
        let v_sisa = 0;
        $("#tabledatax tbody tr td .v_sisa").each(function () {
            let nilai = parseFloat(formatulang($(this).val()));
            if (isNaN(nilai)) {
                nilai = 0;
            }
            v_sisa += nilai;
        });
        $('#jumlah').text(formatcemua(v_sisa));
        $('#v_jumlah').val(v_sisa);
    }
</script>