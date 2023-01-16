<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor BTB</label>
                        <label class="col-md-4">Tanggal BTB</label>
                        <!-- <label class="col-md-3">Gudang Penerima</label> -->
                        <div class="col-sm-4">
                            <input type="hidden" name="igudangold" name="igudangold" value="<?= $data->bagian_pembuat; ?>">
                            <select name="igudang" id="igudang" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($data->bagian_pembuat == $row->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                <input type="hidden" name="ibtbold" id="ibtbold" value="<?= $data->i_btb; ?>">
                                <input type="text" name="ibtb" id="ibtb" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_btb; ?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                            <input id="dbtb" name="dbtb" class="form-control input-sm date" value="<?= $data->d_btb; ?>" readonly>
                        </div>
                        <div class="col-sm-3" hidden="true">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select class="form-control select2" id="ibagian" name="ibagian">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-3">SJ Supplier</label>
                        <label class="col-md-2">Tanggal SJ</label>
                        <label class="col-md-2">Nomor OP</label>
                        <label class="col-md-2">Tanggal OP</label>
                        <div class="col-sm-3">
                            <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" value="<?= $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control input-sm" required="" value="<?= $data->i_supplier; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="isj" name="isj" class="form-control input-sm" onkeyup="gede(this);" required="" value="<?= $data->i_sj_supplier; ?>" maxlength="30">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsj" name="dsj" class="form-control input-sm date" required="" value="<?= $data->d_sj; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="iop" name="iop" class="form-control input-sm" required="" value="<?= $data->i_op; ?>" readonly>
                            <input type="hidden" id="idop" name="idop" class="form-control input-sm" required="" value="<?= $data->id_op; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dop" name="dop" class="form-control input-sm" required="" value="<?= $data->d_op; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-8">Keterangan</label>
                        <label class="col-md-2">Nomor PP</label>
                        <label class="col-md-2">Tanggal PP</label>
                        <div class="col-sm-8">
                            <textarea class="form-control input-sm" name="remark" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ipp" name="ipp" class="form-control input-sm" required="" value="<?= $data->i_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dpp" name="dpp" class="form-control input-sm" required="" value="<?= $data->d_pp; ?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-3">Nomor BTB</label>
                        <label class="col-md-2">Tanggal BTB</label>
                        <label class="col-md-3">Supplier</label> 
                        <label class="col-md-2">SJ Supplier</label>
                        <label class="col-md-2">Tanggal SJ</label> 
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                <input type="hidden" name="ibtbold" id="ibtbold" value="<?= $data->i_btb; ?>">
                                <input type="text" name="ibtb" id="ibtb" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $data->i_btb; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_btb; ?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_btb; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input id="dbtb" name="dbtb" class="form-control input-sm date" value="<?= $data->d_btb; ?>" readonly>
                        </div>                        
                        <div class="col-sm-3">
                            <input type="text" id= "esupplier" name="esupplier" class="form-control input-sm" required="" value="<?= $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" id= "isupplier" name="isupplier" class="form-control input-sm" required="" value="<?= $data->i_supplier; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "isj" name="isj" class="form-control input-sm" onkeyup="gede(this);" required="" value="<?= $data->i_sj_supplier; ?>" maxlength="15">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dsj" name="dsj" class="form-control input-sm date" required="" value="<?= $data->d_sj; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-2">Nomor OP</label> 
                        <label class="col-md-2">Tanggal OP</label> 
                        <label class="col-md-2">Nomor PP</label> 
                        <label class="col-md-2">Tanggal PP</label>
                        <label class="col-md-4">Gudang Penerima</label>        
                        <div class="col-sm-2">
                            <input type="text" id= "iop" name="iop" class="form-control input-sm" required="" value="<?= $data->i_op; ?>" readonly>
                            <input type="hidden" id= "idop" name="idop" class="form-control input-sm" required="" value="<?= $data->id_op; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dop" name="dop" class="form-control input-sm" required="" value="<?= $data->d_op; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ipp" name="ipp" class="form-control input-sm" required="" value="<?= $data->i_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dpp" name="dpp" class="form-control input-sm" required="" value="<?= $data->d_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select class="form-control select2" id="ibagian" required="" name="ibagian" data-placeholder="Pilih Gudang">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="remark"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') { ?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php } elseif ($data->i_status == '2') { ?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <!-- <th class="text-center" style="width: 12%;">Gudang</th> -->
                        <th style="width: 10%;">Kode</th>
                        <th style="width: 25%;">Nama Barang</th>
                        <!-- <th class="text-center" style="width: 10%;">Jml Eks</th>
                        <th class="text-center" style="width: 10%;">Sat Eks</th> -->
                        <th class="text-right" style="width: 10%;">Jml OP</th>
                        <th class="text-right" style="width: 10%;">Masuk</th>
                        <th class="text-right" style="width: 10%;">Toleransi</th>
                        <th style="width: 10%;">Satuan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++; ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td>
                                    <input readonly="" type="hidden" name="idpp[]" id="idpp<?= $i; ?>" class="form-control input-sm" value="<?= $row->id_pp; ?>">
                                    <input readonly="" type="hidden" name="xgudang[]" id="igudang<?= $i; ?>" class="form-control input-sm" value="<?= $row->e_bagian_name; ?>">
                                    <input readonly="" type="text" name="imaterial[]" id="imaterial<?= $i; ?>" class="form-control input-sm" value="<?= $row->i_material; ?>">
                                </td>
                                <td>
                                    <input readonly="" type="text" name="ematerial[]" id="ematerial<?= $i; ?>" class="form-control input-sm" value="<?= htmlentities($row->e_material_name); ?>">
                                </td>
                                <td hidden="true">
                                    <input type="text" id="nquantityeks<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantityeks[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $row->n_quantity_eks; ?>" onkeyup="angkahungkul(this);">
                                </td>
                                <td hidden="true">
                                    <select id="isatuaneks<?= $i; ?>" name="isatuaneks[]" class="form-control select2" data-placeholder="Pilih Satuan Sup">
                                        <option value="">Pilih Satuan</option>
                                        <?php if ($satuan) {
                                            foreach ($satuan as $key) { ?>
                                                <option value="<?= $key->i_satuan_code; ?>" <?php if ($key->i_satuan_code == $row->i_satuan_code_eks) { ?> selected <?php } ?>><?= $key->e_satuan_name; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </td>

                                <td>
                                    <input type="text" id="op<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="op[]" value="<?= $row->n_sisa; ?>" readonly>
                                </td>

                                <td>
                                    <input type="hidden" value="<?= $row->v_price; ?>" id="hrgop<?= $i; ?>" name="hrgop[]" />
                                    <input type="hidden" value="<?= $row->n_sisa; ?>" id="nsisa<?= $i; ?>" name="nsisa[]" />
                                    <input type="text" id="nquantity<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $row->n_quantity; ?>" onkeyup="angkahungkul(this); ceksisa(<?= $i; ?>);">
                                </td>

                                <td>
                                    <input type="text" id="toleransi<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="toleransi[]" value="<?= $row->toleransi; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'||this.value=='0.00'){this.value='';}" onkeyup="angkahungkul(this);">
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->i_satuan_code; ?>" id="isatuan<?= $i; ?>" name="isatuan[]" />
                                    <input type="text" value="<?= $row->e_satuan_name; ?>" readonly id="esatuan<?= $i; ?>" class="form-control input-sm" name="esatuan[]">
                                    <input type="hidden" value="<?= $row->e_operator; ?>" id="eoperator<?= $i; ?>" name="eoperator[]" />
                                    <input type="hidden" value="<?= $row->n_faktor; ?>" id="nfaktor<?= $i; ?>" name="nfaktor[]" />
                                    <input type="hidden" value="<?= $row->i_satuan_code_konversi; ?>" id="ikonversi<?= $i; ?>" name="ikonversi[]" />
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->e_remark; ?>" id="e_note<?= $i; ?>" class="form-control input-sm" name="e_note[]">
                                </td>
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
        $('#ibtb').mask('SSS-0000-000000S');
        $('.select2').select2({
            width: '100%',
        });
        fixedtable($('.table'));
        // showCalendar('.date');
        $('#dbtb, #dsj').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dop').value,
        });
        $('#ibagian').select2({
            placeholder: 'Pilih Gudang',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#xbagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });

    $("#ibtb").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#igudang').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#ibtb').val() != $('#ibtbold').val())) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#ibtb").attr("readonly", false);
        } else {
            $("#ibtb").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#ibtb").val($("#ibtbold").val());
            /*number();*/
        }
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#dbtb').val(),
                'ibagian': $('#igudang').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#ibtb').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    $('#igudang, #dbtb').change(function(event) {
        number();
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#submit').click(function(event) {
        for (var i = 1; i <= $('#jml').val(); i++) {
            ceksisa(i);
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    function ceksisa(i) {
        if (parseFloat($('#nquantity' + i).val()) > parseFloat($('#nsisa' + i).val())) {
            swal('Jml tidak boleh lebih dari sisa op = ' + $('#nsisa' + i).val());
            $('#nquantity' + i).val($('#nsisa' + i).val());
            return false;
        }
    }
</script>