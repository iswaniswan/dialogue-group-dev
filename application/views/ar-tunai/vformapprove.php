<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check fa-lg mr-2"></i> &nbsp;
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
                            <input type="text" name="e_bagian_name" id="e_bagian_name" value="<?= $data->e_bagian_name; ?>" readonly class="form-control input-sm">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly value="<?= $data->i_dt; ?>">
                                <input type="text" name="i_dt_id" id="i_dt_id" value="<?= $data->i_dt_id; ?>" readonly class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_dt" name="d_dt" class="form-control input-sm" readonly value="<?= $data->d_dt; ?>">
                        </div>
                        <div class="col-sm-3">
                        <input type="text" name="e_area_name" id="e_area_name" value="<?= $data->e_area_name; ?>" readonly class="form-control input-sm" aria-label="Text input with dropdown button">
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

        <div class="white-box" id="detail">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-11">
                        <h3 class="box-title m-b-0">Detail Nota</h3>
                    </div>
                    <div class="col-sm-1" style="text-align: right;">
                        -
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabledatax" class="table color-table inverse-table table-bordered class"
                                cellpadding="8" cellspacing="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%;">No</th>
                                        <th>No. Nota</th>
                                        <th>Tgl. Nota</th>
                                        <th>Tgl. Jatuh Tempo</th>
                                        <th>Pelanggan</th>
                                        <th class="text-right">Jumlah</th>
                                        <th class="text-right">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    if ($datadetail) {
                                        foreach ($datadetail as $key) {
                                            $i++; ?>
                                            <tr id="tr<?= $i; ?>">
                                                <td class="text-center">
                                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                </td>
                                                <td><?= $key->i_document;?></td>
                                                <td><input type="hidden" name="d_nota_<?= $i; ?>" id="d_nota_<?= $i; ?>" value="<?= $key->d_document;?>"><span
                                                        class="d_nota_<?= $i; ?>"><?= $key->d_nota;?></span></td>
                                                <td><input type="hidden" name="d_jatuh_tempo_<?= $i; ?>" id="d_jatuh_tempo_<?= $i; ?>"
                                                        value="<?= $key->d_jatuh_tempo;?>"><span class="d_jatuh_tempo_<?= $i; ?>"><?= $key->d_jatuh_tempo;?></span></td>
                                                <td><input type="hidden" name="e_customer_name_<?= $i; ?>" id="e_customer_name_<?= $i; ?>"
                                                        value="<?= $key->e_customer_name;?>"><span class="e_customer_name_<?= $i; ?>"><?= $key->e_customer_name;?></span></td>
                                                <td class="text-right"><input type="hidden" name="v_nota_<?= $i; ?>" id="v_nota_<?= $i; ?>"
                                                        value="<?= $key->v_bayar;?>"><span class="text-right v_nota_<?= $i; ?>"><?= number_format($key->v_bayar);?></span></td>
                                                <td class="text-right"><input type="hidden" class="v_sisa" name="v_sisa_<?= $i; ?>"
                                                        id="v_sisa_<?= $i; ?>" value="<?= $key->v_sisa;?>"><span class="text-right v_sisa_<?= $i; ?>"><?= number_format($key->v_sisa);?></span>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="5">Total</th>
                                        <th class="text-right"><span id="jumlah"></span><input type="hidden"
                                                class="form-control form-control-sm text-right" name="v_jumlah"
                                                id="v_jumlah" value="0" readonly></th>
                                        <th></th>
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