<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil fa-lg mr-2"></i> &nbsp;
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
                            <select name="i_bagian" id="i_bagian" onchange="number();" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row): ?>
                                                                                <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) {
                                                                                      echo 'selected';
                                                                                  } ?>>
                                                                                    <?= $row->e_bagian_name; ?>
                                                                                </option>
                                                        <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                            <input type="hidden" name="id" id="id" readonly value="<?= $data->i_kum; ?>">
                                <input type="text" name="i_kum_id" id="i_kum_id" readonly autocomplete="off" maxlength="17" class="form-control input-sm" value="<?= $data->i_kum_id; ?>"
                                    aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_kum" name="d_kum" class="form-control input-sm date" required=""
                                readonly onchange="number();" value="<?= format_indo($data->d_kum); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="i_area" id="i_area" class="form-control select2" onchange="number();">
                                <?php if ($area) {
                                    foreach ($area as $row): ?>
                                                                                <option value="<?= $row->id; ?>" <?php if ($row->id == $data->i_area) {
                                                                                      echo 'selected';
                                                                                  } ?>><?="[" . $row->i_area . "] - " . $row->e_area; ?></option>
                                                        <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nama Customer</label>
                        <label class="col-md-3">Nama Sales</label>
                        <label class="col-md-3">No. Daftar Tagihan</label>
                        <label class="col-md-3">Bank</label>
                        <div class="col-sm-3">
                            <select name="i_customer" id="i_customer" class="form-control select2">
                                <option value="<?= $data->i_customer; ?>"><?='[' . $data->i_customer_code . '] - ' . $data->e_customer_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_salesman" id="i_salesman" class="form-control select2">
                                <option value="<?= $data->i_salesman; ?>"><?='[' . $data->i_sales_code . '] - ' . $data->e_sales; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_dt" id="i_dt" class="form-control select2">
                                <option value="<?= $data->i_dt; ?>"><?= $data->i_dt_id . ' - ' . $data->d_dt; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_bank" id="i_bank" class="form-control select2" onchange="number();">
                                <?php if ($bank) {
                                    foreach ($bank as $row): ?>
                                                                                <option value="<?= $row->id; ?>" <?php if ($row->id == $data->i_bank) {
                                                                                      echo 'selected';
                                                                                  } ?>><?="[" . $row->i_bank . "] - " . $row->e_bank_name; ?></option>
                                                        <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jumlah</label>
                        <label class="col-md-3">Atas Nama</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="v_jumlah" id="v_jumlah" autocomplete="off"
                                    class="form-control input-sm" placeholder="Input Jumlah Transfer" required value="<?= number_format($data->v_jumlah); ?>"
                                    onkeyup="angkahungkul(this); reformat(this);">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="e_send_name" name="e_send_name" class="form-control input-sm"
                                placeholder="Nama Pengirim" maxlength="150" value="<?= $data->e_send_name; ?>">
                        </div>
                        <div class="col-sm-6">
                            <textarea class="form-control input-sm" name="e_remark" placeholder="Note ..."><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"
                                                        onclick="return konfirm();"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <?php if ($data->i_status == '1') { ?>
                                                    <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i
                                                            class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                                    <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i
                                                            class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php } elseif ($data->i_status == '2') { ?>
                                                    <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i
                                                            class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
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
        $('.select2').select2();
        showCalendar('.date', null, 0);
        $("form").submit(function (event) {
            event.preventDefault();
            $("input").attr("disabled", true);
            $("select").attr("disabled", true);
            $("#submit").attr("disabled", true);
            $("#addrow").attr("disabled", true);
            $("#send").attr("disabled", false);
        });

        $('#send').click(function (event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function (event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function (event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#i_area').change(function (event) {
            $('#i_customer').val('');
            $('#i_customer').html('');
            $('#i_salesman').val('');
            $('#i_salesman').html('');
            $('#i_dt').val('');
            $('#i_dt').html('');
        });

        $('#i_customer').select2({
            placeholder: 'Cari Customer',
            minimumInputLength: 1,
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/customer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        i_area: $('#i_area').val(),
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
        }).change(function (event) {
            $('#i_salesman').val('');
            $('#i_salesman').html('');
            $('#i_dt').val('');
            $('#i_dt').html('');
        });

        $('#i_salesman').select2({
            placeholder: 'Cari Salesman',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/salesman/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        i_area: $('#i_area').val(),
                        i_customer: $('#i_customer').val(),
                        d_kum: $('#d_kum').val(),
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

        $('#i_dt').select2({
            placeholder: 'Cari Daftar Tagihan',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/dt/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        i_area: $('#i_area').val(),
                        i_customer: $('#i_customer').val(),
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
    });

    //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#d_kum').val(),
                'i_bagian': $('#i_bagian').val(),
                'i_area': $('#i_area').val(),
                'id': $('#id').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#i_kum_id').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }
</script>