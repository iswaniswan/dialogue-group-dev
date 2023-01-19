<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id; ?>">
                            <input type="hidden" id="idocumentold" name="idocumentold" class="form-control" value="<?= $data->i_document; ?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BONM-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document; ?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" value="<?= $data->d_document; ?>" placeholder="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2">
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);">
                                <option value="<?= $data->id_reff . '|' . $data->id_jenis_barang_keluar; ?>"><?= $data->i_reff . ' - ' . $data->e_jenis_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') { ?>
                        <?php } ?>
                        <?php if ($data->i_status == '1') { ?>
                            <div class="col-sm-3">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Update</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash mr-2"></i>Delete</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                        <?php } elseif ($data->i_status == '2') { ?>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0;
if ($datadetail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th style="width: 120px">Kode Barang</th>
                            <th style="width: 350px; max-width: 1000px">Nama Barang</th>
                            <th class="text-center" style="width: 150px">Warna</th>
                            <th class="text-center" style="width: 50px">Qty<br/>Kirim</th>
                            <th class="text-center" style="width: 50px">Qty<br/>Terima</th>
                            <th class="text-center" style="width: 60px">Qty<br/>BS/Tidak<br/>Set</th>
                            <th class="text-center" style="width: 80px">Periode</th>
                            <th class="" style="width: 180px; max-width: 1000px">Keterangan</th>
                            <th class="text-center" style="width: 30px"><input type="checkbox" class="form-control input-sm" id="checkAll"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) {
                            $i++; ?>
                            <tr id="tr<?= $i; ?>">
                                <td class="text-center"><?= $i; ?></td>
                                <td>
                                    <input readonly data-nourut="<?= $i; ?>" id="iproduct<?= $i; ?>" type="text" class="form-control input-sm" name="iproduct<?= $i; ?>" value="<?= $key->i_product_wip; ?>">
                                    <input type="hidden" name="id_product<?= $i; ?>" id="idproduct<?= $i; ?>" class="form-control" value="<?= $key->id_product_wip; ?>">
                                    <input type="hidden" name="id_referensi_item<?= $i; ?>" id="id_referensi_item<?= $i; ?>" value="<?= $key->id_referensi_item; ?>">
                                </td>
                                <td>
                                    <input readonly data-ngurut="<?= $i; ?>" id="e_product_wipname<?= $i; ?>" type="text" class="form-control input-sm" name="e_product_wipname<?= $i; ?>" value="<?= $key->e_product_wipname ?>">
                                </td>
                                <td>
                                    <input readonly data-ngurut="<?= $i; ?>" id="e_product_wipname<?= $i; ?>" type="text" class="form-control input-sm" name="e_color_name<?= $i; ?>" value="<?= $key->e_color_name ?>">
                                </td>
                                <td>
                                    <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywip<?= $i; ?>" id="nquantitywip<?= $i; ?>" value="<?= $key->n_quantity_wip_cutting; ?>">
                                </td>
                                <td hidden>
                                    <input eadonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywipsisa<?= $i; ?>" id="nquantitywipsisa<?= $i; ?>" value="<?= $key->n_quantity_wip_sisa; ?>" readonly>
                                </td>
                                <td>
                                    <input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="n_quantity<?= $i; ?>" id="nquantitywipmasuk<?= $i; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_quantity_wip_masuk; ?>" onkeyup="angkahungkul(this);validasi(<?= $i; ?>)">
                                </td>
                                <td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitybs<?= $i; ?>" name="n_quantity_bs<?= $i; ?>" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}" value="<?= $key->qty_bs; ?>" onkeyup="validasi(<?= $i; ?>);"></td>
                                <td ><input data-urut="<?= $i ?>" class="form-control text-center periode input-sm" readonly type="text" id="periode<?= $i ?>" name="periode$<?= $i ?>" value="<?= $key->i_periode ?>" readonly placeholder="Pilih Periode"></td>
                                <td><input class="form-control input-sm" placeholder="Keterangan jika ada" type="text" value="<?= $key->e_remark; ?>" onkeyup="set_text('<?= $key->i_product_wip; ?>',this.value);" id="remark<?= $i; ?>" name="e_note<?= $i; ?>"></td>
                                <td class="text-center"><input type="checkbox" class="form-control input-sm" checked id="check<?= $i; ?>" name="check<?= $i; ?>"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
            </div>
        </div>
    </div>
    </form>
<?php } ?>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        showCalendar('.date');
        max_tgl();

        // $('#idocument').mask('SSS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        //number();
        $('#ipengirim').select2({
            placeholder: 'Pilih Bagian Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/bagianpengirim'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian:$('#ibagian').val()
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        $('#ireff').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        iasal: $('#ipengirim').val(),
                        ibagian: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $("#ibagian").change(function() {
        $('#ireff').val('');
        $('#ireff').html('');
        $('#jml').val(0);
        $("#tabledatax tbody").remove();
    });

    $("#idocument").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $("#ibagian").val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    $("#ipengirim").change(function() {
        $('#ireff').attr("disabled", false);
    });

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#idocument").attr("readonly", false);
        } else {
            $("#idocument").attr("readonly", true);
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
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

    //untuk me-generate running number
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#idocument').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    function getdataitem(ireff) {
        var idreff = $('#ireff').val();
        var ipengirim = $('#ipengirim').val();
        $.ajax({
            type: "post",
            data: {
                'idreff': idreff,
                'ipengirim': ipengirim,
            },
            url: '<?= base_url($folder . '/cform/getdataitem'); ?>',
            dataType: "json",
            success: function(data) {

                $('#jml').val(data['jmlitem']);
                $("#tabledatax tbody").remove();
                $("#detail").attr("hidden", false);

                var dref = data['datahead']['d_document'];
                $("#dreferensi").val(dref);
                group = "";
                i = 0;
                for (let a = 0; a < data['jmlitem']; a++) {
                    i++;
                    var idproduct = data['dataitem'][a]['id_product_wip'];
                    var kode_wip = "'" + data['dataitem'][a]['i_product_wip'] + "'";
                    var newRow = $("<tr>");
                    var cols = "";
                    var cols1 = "";
                    cols1 += `
                            <td class="text-center">${i}</td>
                            <td>
                                <input type="text" id="iproduct${i}" class="form-control input-sm" name="iproduct${i}" value="${data['dataitem'][a]['i_product_wip']}" readonly>
                                <input type="hidden" id="id_referensi_item${i}" class="form-control" name="id_referensi_item${i}" value="${data['dataitem'][a]['id']}" readonly>
                                <input type="hidden" id="idproduct${i}" class="form-control" name="id_product${i}" value="${data['dataitem'][a]['id_product_wip']}" readonly>
                            </td>
                            <td>
                                <input type="text" id="e_product_wipname${i}" class="form-control input-sm" name="e_product_wipname${i}" value="${data['dataitem'][a]['e_product_wipname']} - ${data['dataitem'][a]['e_color_name']}" readonly>
                            </td>
                            <td>
                                <input class="form-control input-sm text-right" type="text"  id="nquantitywip${i}" name="nquantitywip${i}" value="${data['dataitem'][a]['n_quantity_wip']}" readonly>
                            </td>
                            <td hidden>
                                <input class="form-control input-sm text-right" type="text"  id="nquantitywipsisa${i}" name="nquantitywipsisa${i}" value="${data['dataitem'][a]['n_quantity_wip_sisa']}" onkeyup="angkahungkul(this);" readonly>
                            </td>
                            <td>
                                <input class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitywipmasuk${i}" name="n_quantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="${data['dataitem'][a]['n_quantity_wip_sisa']}" onkeyup="validasi(${i});">
                            </td>
                            <td>
                                <input class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitybs${i}" name="n_quantity_bs${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="validasi(${i});">
                            </td>
                            <td ><input data-urut="${i}" class="form-control text-center periode input-sm" readonly type="text" id="periode${i}" name="periode${i}" value="${data['dataitem'][a]['i_periode']}" disabled placeholder="Pilih Periode"></td>
                            <td>
                                <input class="form-control input-sm" placeholder="Keterangan jika ada" type="text" onkeyup="set_text(${kode_wip},this.value);" id="remark${i}" name="e_note${i}">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="form-control input-sm" id="check${i}" name="check${i}">
                            </td>`;
                    // if (group == "") {
                    //     cols1 += '<td class="text-center">' + i + '</td>';
                    //     cols1 += '<td><input type="text" id="iproduct' + a + '" class="form-control input-sm" name="iproduct' + a + '" value="' + data['dataitem'][a]['i_product_wip'] + '" readonly><input type="hidden" id="idproduct' + a + '" class="form-control" name="idproduct' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '" readonly></td>';
                    //     cols1 += '<td><input type="text" id="e_product_wipname' + a + '" class="form-control input-sm" name="e_product_wipname' + a + '" value="' + data['dataitem'][a]['e_product_wipname'] + ' - ' + data['dataitem'][a]['e_color_name'] + '" readonly><input type="hidden" id="idproduct' + a + '" class="form-control" name="idproduct' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '" readonly></td>';
                    //     cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip' + a + '" name="nquantitywip' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip'] + '" onkeyup="angkahungkul(this);" readonly></td>';
                    //     cols1 += '<td hidden><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa' + a + '" name="nquantitywipsisa' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" onkeyup="angkahungkul(this);" readonly></td>';
                    //     cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitywipmasuk' + a + '" name="nquantitywipmasuk' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" onkeyup="validasi(' + a + ');"></td>';
                    //     cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitybs' + a + '" name="nquantitybs' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" onkeyup="validasi(' + a + ');"></td>';
                    //     cols1 += '<td><input class="form-control input-sm" placeholder="Keterangan jika ada" type="text" onkeyup="set_text(' + kode_wip + ',this.value);" id="remark' + a + '" name="remark' + a + '"></td>';
                    //     cols1 += '<td class="text-center"><input type="checkbox" id="check' + a + '" name="check' + a + '"></td>';
                    // } else {
                    //     if (group != idproduct) {
                    //         cols1 += '<td class="text-center">' + i + '</td>';
                    //         cols1 += '<td><input type="text" id="iproduct' + a + '" class="form-control input-sm" name="iproduct' + a + '" value="' + data['dataitem'][a]['i_product_wip'] + '" readonly><input type="hidden" id="idproduct' + a + '" class="form-control" name="idproduct' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '" readonly></td>';
                    //         cols1 += '<td><input type="text" id="e_product_wipname' + a + '" class="form-control input-sm" name="e_product_wipname' + a + '" value="' + data['dataitem'][a]['e_product_wipname'] + ' - ' + data['dataitem'][a]['e_color_name'] + '" readonly><input type="hidden" id="idproduct' + a + '" class="form-control" name="idproduct' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '" readonly></td>';
                    //         cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip' + a + '" name="nquantitywip' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip'] + '" onkeyup="angkahungkul(this);" readonly></td>';
                    //         cols1 += '<td hidden><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa' + a + '" name="nquantitywipsisa' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" onkeyup="angkahungkul(this);" readonly></td>';
                    //         cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitywipmasuk' + a + '" name="nquantitywipmasuk' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" onkeyup="validasi(' + a + ');"></td>';
                    //         cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitybs' + a + '" name="nquantitybs' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_wip_sisa'] + '" onkeyup="validasi(' + a + ');"></td>';
                    //         cols1 += '<td><input class="form-control input-sm" placeholder="Keterangan jika ada" type="text" onkeyup="set_text(' + kode_wip + ',this.value);" id="remark' + a + '" name="remark' + a + '"></td>';
                    //         cols1 += '<td class="text-center"><input type="checkbox" id="check' + a + '" name="check' + a + '"></td>';
                    //         //i = 1;
                    //     }
                    // }
                    newRow.append(cols1);
                    $("#tabledatax").append(newRow);
                    /* group = idproduct;
                    var newRow = $("<tr hidden>");
                    cols += '<td class="text-center">' + (i) + '</td>';
                    cols += '<td><input type="hidden" name="idproductwip[]" id="idproductwip' + a + '" value="' + data['dataitem'][a]['id_product_wip'] + '">';
                    cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial' + a + '" value="' + data['dataitem'][a]['id_material'] + '">';
                    cols += '<input class="form-control input-sm" readonly type="text" value="' + data['dataitem'][a]['i_material'] + '"></td>';
                    cols += '<td><input class="form-control input-sm" readonly type="text " value="' + data['dataitem'][a]['e_material_name'] + '"></td>';
                    cols += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahan[]" id="nquantitybahan' + a + '" readonly  value="' + data['dataitem'][a]['n_quantity'] + '"></td>';
                    cols += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahansisa[]" id="nquantitybahansisa' + a + '" readonly value="' + data['dataitem'][a]['n_quantity_sisa'] + '"></td>';
                    cols += '<td><input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantitybahanmasuk[]" id="nquantitybahanmasuk' + a + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data['dataitem'][a]['n_quantity_sisa'] + '" onkeyup="validasi(' + a + ');"></td>';
                    cols += '<td colspan="2"><input class="form-control ' + data['dataitem'][a]['i_product_wip'] + ' input-sm" type="text" name="edesc[]" id="edesc' + a + '" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow); */
                }

                function formatSelection(val) {
                    return val.name;
                }

                $("#tabledatax").on("click", ".ibtnDel", function(event) {
                    $(this).closest("tr").remove();
                });
                max_tgl();
            },
            error: function() {
                alert('Error :)');
            }
        });
    }

    function max_tgl() {
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dreferensi').value,
        });
    }

    function validasi(id) {
        nquantityma = $("#nquantitywipsisa" + id).val();
        nquantitymasuk = $("#nquantitywipmasuk" + id).val();
        nquantitybs = $("#nquantitybs" + id).val();
        nquantitymaterial = $("#nquantitybahansisa" + id).val();
        nquantitymasukmaterial = $("#nquantitybahanmasuk" + id).val();

        if (parseFloat(nquantitymasuk) > parseFloat(nquantityma)) {
            swal('Quantity Terima Tidak Boleh Lebih Dari Quantity Kirim');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }

        if (parseFloat(nquantitybs) > parseFloat(nquantityma)) {
            swal('Quantity BS/Tidak Set Tidak Boleh Lebih Dari Quantity Kirim');
            $("#nquantitybs" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) > parseFloat(nquantitymaterial)) {
            swal('Quantity Terima Tidak Boleh Lebih Dari Quantity Kirim');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        }
        /* if (parseFloat(nquantitymasuk) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        } */
    }

    /* function validasi(id) {
        nquantityma = $("#nquantitywipsisa" + id).val();
        nquantitymasuk = $("#nquantitywipmasuk" + id).val();
        nquantitymaterial = $("#nquantitybahansisa" + id).val();
        nquantitymasukmaterial = $("#nquantitybahanmasuk" + id).val();

        if (parseFloat(nquantitymasuk) > parseFloat(nquantityma)) {
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) > parseFloat(nquantitymaterial)) {
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        }
        if (parseFloat(nquantitymasuk) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        }
    } */

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            } else {
                return false;
            }
        }
    }
</script>