<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus fa-lg mr-2"></i> &nbsp;
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
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="i_kum_id" id="i_kum_id" readonly autocomplete="off"
                                    placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value=""
                                    aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_kum" name="d_kum" class="form-control input-sm date" required=""
                                readonly onchange="number();" value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="i_area" id="i_area" class="form-control select2" onchange="number();">
                                <?php if ($area) {
                                    foreach ($area as $row): ?>
                                        <option value="<?= $row->id; ?>"><?="[" . $row->i_area . "] - " . $row->e_area; ?>
                                        </option>
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
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_salesman" id="i_salesman" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_dt" id="i_dt" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_bank" id="i_bank" class="form-control select2" onchange="number();">
                                <?php if ($bank) {
                                    foreach ($bank as $row): ?>
                                        <option value="<?= $row->id; ?>"><?="[" . $row->i_bank . "] - " . $row->e_bank_name; ?>
                                        </option>
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
                                    class="form-control input-sm" placeholder="Input Jumlah Transfer" required value=""
                                    onkeyup="angkahungkul(this); reformat(this);">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="e_send_name" name="e_send_name" class="form-control input-sm"
                                placeholder="Nama Pengirim" maxlength="150">
                        </div>
                        <div class="col-sm-6">
                            <textarea class="form-control input-sm" name="e_remark" placeholder="Note ..."></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i
                                    class="fa fa-lg fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i
                                    class="fa fa-lg fa-arrow-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-4">
                            <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm"><i
                                    class="fa fa-lg fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
        number();

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