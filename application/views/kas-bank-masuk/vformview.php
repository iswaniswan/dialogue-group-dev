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
                            <input type="text" id="e_bagian_name" name="e_bagian_name" class="form-control input-sm"
                                readonly value="<?= $data->i_bagian . ' - ' . $data->e_bagian_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->i_rv; ?>">
                                <input type="text" name="i_rv_id" id="i_rv_id" readonly autocomplete="off"
                                    maxlength="17" class="form-control input-sm" value="<?= $data->i_rv_id; ?>"
                                    aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_rv" name="d_rv" class="form-control input-sm date" required=""
                                readonly value="<?= formatdmY($data->d_rv); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="e_area" name="e_area" class="form-control input-sm" readonly
                                value="<?= $data->i_area_id . ' - ' . $data->e_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis</label>
                        <label class="col-md-3">CoA</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="i_rv_type" id="i_rv_type" class="form-control select2">
                                <?php if ($rvtype) {
                                    foreach ($rvtype as $row): ?>
                                        <option data-type="<?= $row->i_rv_type_id; ?>" value="<?= $row->i_rv_type; ?>" <?php if ($row->i_rv_type == $data->i_rv_type) { ?> selected <?php } ?>><?="[" . $row->i_rv_type_id . "] - " . $row->e_rv_type_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="e_area" name="e_area" class="form-control input-sm" readonly
                                value="<?= $data->i_coa_id . " - " . $data->e_coa_name; ?>">
                        </div>
                        <div class="col-sm-6">
                            <textarea name="e_remark" class="form-control input-sm"
                                readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <hr class="mt-0">
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
                        <h3 class="box-title m-b-0">Detail Transaksi</h3>
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
                                        <th style="width: 15%;">CoA</th>
                                        <th style="width: 7%;">Tgl. Bukti</th>
                                        <th style="width: 10%;">Area</th>
                                        <th class="clear" style="width: 10%;">TF/GR/TN</th>
                                        <th class="clear" style="width: 15%;">Referensi</th>
                                        <th>Keterangan</th>
                                        <th class="text-right" width="10%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    if ($datadetail) {
                                        foreach ($datadetail as $key) {
                                            $i++; ?>
                                            <tr>
                                                <td class="text-center">
                                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                </td>
                                                <td>
                                                    <?= $key->i_coa_id . ' - ' . $key->e_coa_name; ?>
                                                </td>
                                                <td>
                                                    <?= formatdmY($key->d_bukti); ?>
                                                </td>
                                                <td>
                                                    <?= $key->i_area_id . ' - ' . $key->e_area; ?>
                                                </td>
                                                <td class="clear">
                                                    <?= $key->e_rv_refference_type_name; ?>
                                                </td>
                                                <td class="clear">
                                                    <?= $key->i_referensi; ?>
                                                </td>
                                                <td>
                                                    <?= $key->e_remark; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= number_format($key->v_rv); ?>
                                                    <input type="hidden" class="form-control input-sm text-right v_rv"
                                                        placeholder="Nilai Transaksi" name="v_rv_item_<?= $i; ?>"
                                                        id="v_rv_item_<?= $i; ?>" required
                                                        value="<?= number_format($key->v_rv); ?>"
                                                        onkeyup="angkahungkul(this);hetang();reformat(this);">
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right colay" colspan="7">
                                            Sub Total
                                        </th>
                                        <th class="text-right"><span id="v_rv" class="text-right"></spam>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-right colay" colspan="7">
                                            Saldo
                                        </th>
                                        <th class="text-right"><span id="v_saldo" class="text-right">0</span></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right colay" colspan="7">
                                            Sisa Saldo
                                        </th>
                                        <th class="text-right"><span class="text-right" id="v_sisa_saldo"></span></th>
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
        set();
        hetang();
        $('.select2').select2();
        $('#i_coa').select2({
            placeholder: 'Cari CoA',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/coa_type/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        i_rv_type: $('#i_rv_type').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        $('#i_rv_type').change(function (event) {
            let type = $(this).find(':selected').data('type');
            if (type != 'BM') {
                $('.clear').attr("hidden", true);
                $('.colay').attr('colspan', 5);
            } else {
                $('.clear').attr("hidden", false);
                $('.colay').attr('colspan', 7);
            }
            number();
            clear_table();
            $('#i_coa').val('');
            $('#i_coa').html('');
        });
    });

    function hetang() {
        let v_rv = 0;
        $("#tabledatax tbody tr td .v_rv").each(function () {
            let v = parseFloat(formatulang($(this).val()));
            if (isNaN(v)) {
                v = 0;
            }
            v_rv += v;
        });
        $('#v_rv').text(formatcemua(v_rv));
        $('#v_sisa_saldo').text(formatcemua(parseFloat(formatulang($('#v_saldo').text())) + v_rv));
    }

    function set() {
        let type = $('#i_rv_type').find(':selected').data('type');
        if (type != 'BM') {
            $('.clear').attr("hidden", true);
            $('.colay').attr('colspan', 5);
        } else {
            $('.clear').attr("hidden", false);
            $('.colay').attr('colspan', 7);
        }
    }
</script>