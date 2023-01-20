<style type="text/css">
    #table td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
    }

    .dropify-wrapper {
        height: 118px !important;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-plus mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Permintaan ke Gudang</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="idocument" required="" id="imemo" readonly class="form-control input-sm" value="">
                                </div>
                                <input type="hidden" id="id" nama="id" value="">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="id_type" id="id_type" required="" class="form-control select2" onchange="clear_table();">
                                    <?php if ($type) {
                                        $group = "";
                                        foreach ($type as $row) : ?>
                                        <?php if ($group!=$row->name) {?>
                                            </optgroup>
                                            <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                        <?php }
                                        $group = $row->name;
                                        ?>
                                            <option value="<?= $row->id_company . '|' . trim($row->id) ?>"> <?= $row->e_type_name; ?></option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="itujuan_kirim" class="col-md-3">Tujuan Kirim</label>
                            <label for="dkirim" class="col-md-3">Tanggal Kirim</label>
                            <label class="col-md-6">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="itujuan_kirim" id="itujuan_kirim" class="form-control select2" onchange="number();">
                                    <?php if ($tujuan) {
                                        $group = "";
                                        foreach ($tujuan as $row) : ?>
                                        <?php if ($group!=$row->name) {?>
                                            </optgroup>
                                            <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                        <?php }
                                        $group = $row->name;
                                        ?>
                                            <option value="<?= "$row->id"; ?>">
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="dkirim" required="" id="dkirim" class="form-control input-sm date-kirim" value="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                            <div class="col-sm-6">
                                <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm mr-2"><i class="fa fa-lg fa-save mr-2"></i>Simpan</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-info btn-block btn-sm mr-2" onclick="tambah_product(parseInt($('#jml').val()));"> <i class="fa fa-plus fa-lg mr-2"></i>Item</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-lg fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" disabled="true" id="send" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-lg fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class=""></i><?= "Upload " . $title_list; ?> <!-- <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> <?= $title_list; ?></a> -->
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-6">Upload File (Optional)</label>
                            <label class="col-md-6 text-right notekode">Formatnya .xls</label>
                            <div class="col-sm-12">
                                <input type="file" id="input-file-now" name="userfile" class="dropify" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload</button>
                            </div>
                            <div class="col-md-6">
                                <a id="href" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Template</button> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
                </div>
                <div class="col-sm-6 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
            </div>
            <div class="table-responsive">
                <table id="table" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th style="width: 35%;">Nama Barang</th>
                            <th class="text-right" style="width: 10%;">Qty</th>
                            <th colspan="3" style="width: 30%;"></th>
                            <th class="text-center" style="width: 3%;">Act</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="0">
    <input type="hidden" name="jml_item" id="jml_item" value="0">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/

    $(document).ready(function() {
        $('.dropify').dropify();

        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        number();
        $('.select2').select2();
        showCalendar('.date-kirim');
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.tgl', 0);

        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  UPDATE NO DOKUMEN SAAT TANGGAL DOKUMEN DAN BAGIAN PEMBUAT DIRUBAH  ----------*/

        $('#ddocument, #ibagian').change(function(event) {
            number();
        });

        $("#table").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            var obj = $('#table tr:visible').find('spanlistx');
            $.each(obj, function(key, value) {
                id = value.id;
                $('#' + id).html(key + 1);
            });
        });

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                /* if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else { */
                swal({
                    title: "Simpan Data Ini?",
                    text: "Anda Dapat Membatalkannya Nanti",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonColor: 'LightSeaGreen',
                    confirmButtonText: "Ya, Simpan!",
                    closeOnConfirm: false
                }, function() {
                    $.ajax({
                        type: "POST",
                        data: $("form").serialize(),
                        url: '<?= base_url($folder . '/cform/simpan/'); ?>',
                        dataType: "json",
                        success: function(data) {
                            if (data.sukses == true) {
                                $('#id').val(data.id);
                                swal("Sukses!", "No Dokumen : " + data.kode +
                                    ", Berhasil Disimpan :)", "success");
                                $("input").attr("disabled", true);
                                $("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("disabled", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                            } else {
                                swal("Maaf :(", "No Dokumen : " + data.kode +
                                    ", Gagal Disimpan :(", "error");
                            }
                        },
                        error: function() {
                            swal("Maaf", "Data Gagal Disimpan :(", "error");
                        }
                    });
                });
                // }
            }
            return false;
        });


        $("#upload").on("click", function() {
            var idforecast = $('#idforecast').val();
            let id_type = $('#id_type').val();
            var ibagian = $('#ibagian').val();
            var formData = new FormData();
            formData.append('userfile', $('input[type=file]')[0].files[0]);
            formData.append('idforecast', idforecast);
            formData.append('ibagian', ibagian);
            formData.append('id_type', id_type);
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder . '/cform/load'); ?>",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(data) {
                    var json = JSON.parse(data);
                    var sama = json.sama;
                    var status = json.status;
                    var detail = json.datadetail;
                    var detail_material = json.detail_material;
                    if (sama == true) {
                        if (status == 'berhasil') {
                            // console.log(detail);
                            // console.log(detail_material);
                            swal({
                                title: "Success!",
                                text: "File Success Diupload :)",
                                type: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            if (json.datadetail.length > 0) {
                                clear_table();
                                json.datadetail.map((dd, o) => {
                                    i = parseInt($("#jml").val()) + 1;
                                    $("#jml").val(i);
                                    var no = $(`#table .tr`).length;
                                    var newRow = $(`<tr class="tr tr_first${i}">`);
                                    var cols = "";
                                    cols += `
                                        <td class="text-center"><spanlistx id="snum${i}"><b>${(no+1)}</b></spanlistx></td>
                                        <td><select id="id_product_wip${i}" class="form-control input-sm" name="id_product_wip${i}" onchange="cek_product(${i});">
                                            <option value="${dd.id}">${dd.i_product_wip} - ${dd.e_product_name} - ${dd.e_color_name}</option>
                                        </select> <select id="id_marker${i}" class="form-control input-sm" name="id_marker${i}" onchange="cek_product(${i});">
                                            <option value="${dd.id_marker}">${dd.e_marker_name}</option>
                                        </select></td>
                                        <td><input type="number" id="n_quantity${i}" class="form-control text-right input-sm" autocomplete="off" name="n_quantity${i}" onblur="if(this.value==''){this.value=='0';}" onfocus="if(this.value=='0'){this.value='';}" value="${dd.n_quantity}" onkeyup="berhitung(${i});setqtyproduct(${i})"></td>
                                        <td colspan="3"></td>
                                        <td class="text-center"><button type="button" onclick="hapus_detail(${i});" title="Delete" class="btn btn-sm btn-circle btn-danger"><i class="fa fa-close fa-lg" aria-hidden="true"></i></button></td>
                                    `;
                                    newRow.append(cols);
                                    $("#table tr:first").after(newRow);
                                    var newRow1 = $(
                                        `<tr class="table-active tr_second${i}">
                                            <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                            <td><b>LIST BARANG MATERIAL</b></td>
                                            <td class="text-right"><b>Kebutuhan Per Pcs</b></td>
                                            <td class="text-right"><b>Stock Material</b></td>
                                            <td class="text-right"><b>Kebutuhan Material</b></td>
                                            <td><b>Keterangan</b></td>
                                            <td class="text-center"><button data-urut="${i}" type="button" onclick="tambah_material(${i});" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i data-urut="${i}" id="addlist${i}"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></button></td>
                                        </tr>`);
                                    $(newRow1).insertAfter("#table .tr_first" + i);
                                    restart();
                                    $('#id_product_wip' + i).select2({
                                        placeholder: 'Cari Kode / Nama WIP',
                                        allowClear: true,
                                        width: "75%",
                                        type: "POST",
                                        ajax: {
                                            url: '<?= base_url($folder . '/cform/product_wip/'); ?>',
                                            dataType: 'json',
                                            delay: 250,
                                            data: function(params) {
                                                var query = {
                                                    q: params.term,
                                                }
                                                return query;
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: data
                                                };
                                            },
                                            cache: true
                                        }
                                    });

                                    $('#id_marker' + i).select2({
                                        placeholder: 'Cari Nama Marker',
                                        allowClear: true,
                                        width: "25%",
                                        type: "POST",
                                        ajax: {
                                            url: '<?= base_url($folder . '/cform/marker/'); ?>',
                                            dataType: 'json',
                                            delay: 250,
                                            data: function(params) {
                                                var query = {
                                                    q: params.term,
                                                    id_product_wip: $('#id_product_wip' + i).val()
                                                }
                                                return query;
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: data
                                                };
                                            },
                                            cache: true
                                        }
                                    }).change(function(e) {
                                        $.ajax({
                                            type: "post",
                                            data: {
                                                id_product_wip: $('#id_product_wip' + i).val(),
                                                // 'id_material': $('#id_material_' + i + '_' + ii).val(),
                                                id_type: $('#id_type').val(),
                                                id_marker: $(this).val()
                                            },
                                            url: '<?= base_url($folder . '/cform/get_material_onchange_detail'); ?>',
                                            dataType: "json",
                                            success: function(data) {
                                                if (data['detail'].length > 0) {
                                                    data['detail'].map((d) => {
                                                        var ii = parseInt($('#jml_item').val()) + 1;
                                                        var col = "";
                                                        $('#jml_item').val(ii);
                                                        var newRow = $(`<tr class="td_${i}">`);
                                                        col += `
                                                        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
                                                        <td>
                                                            <select data-iter = "${i}_${ii}" class="form-control input-sm material_${i}" id="id_material_${i}_${ii}" name="id_material[]" onchange="get_material_detail(${i},${ii});">
                                                                <option value="${d.id_material}">[${d.i_material}] - ${d.e_material_name} - ${d.e_satuan_name}</option>
                                                            </select>
                                                            <input type="hidden" class="product_${i}" id="id_product_${i}_${ii}" name="id_product[]" value="${d.id_product_wip}">
                                                            <input type="hidden" class="product_${i}" id="id_marker_${i}_${ii}" name="id_marker[]" value="${d.id_marker}">
                                                            <input type="hidden" class="product_${i}" id="n_quantity_product_${i}" name="n_quantity_product[]" value=${dd.n_quantity}>
                                                        </td>
                                                        <td><input type="text" id="n_kebutuhan_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_kebutuhan[]" value="${d.n_kebutuhan}"></td>
                                                        <td><input type="text" id="n_stock_material_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_stock_material[]" value="${d.n_saldo_akhir}"></td>
                                                        <td><input type="number" id="n_kebutuhan_material_${i}_${ii}" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_kebutuhan_material[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="${dd.n_quantity * d.n_kebutuhan}" onkeyup="angkahungkul(this);"></td>
                                                        <td><input type="text" class="form-control input-sm" name="e_note[]" placeholder="Isi keterangan jika ada!"/></td>
                                                        <td class="text-center"><button type="button" title="Delete" data-b="${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
                                                        `;
                                                        newRow.append(col);
                                                        $(newRow).insertAfter("#table .tr_second" + i);
                                                        $(`#id_product_${i}_${ii}`).val($('#id_product_wip' + i).val());
                                                        $(`#id_marker_${i}_${ii}`).val($('#id_marker' + i).val());
                                
                                                        $(`#id_material_${i}_${ii}`).select2({
                                                            placeholder: 'Cari Kode / Nama Material',
                                                            allowClear: true,
                                                            width: "100%",
                                                            type: "POST",
                                                            ajax: {
                                                                url: '<?= base_url($folder . '/cform/material/'); ?>',
                                                                dataType: 'json',
                                                                delay: 250,
                                                                data: function(params) {
                                                                    var query = {
                                                                        q: params.term,
                                                                        id_product: $('#id_product_wip' + i).val(),
                                                                        id_type: $('#id_type').val(),
                                                                        id_marker: $('#id_marker' + i).val()
                                                                    }
                                                                    return query;
                                                                },
                                                                processResults: function(data) {
                                                                    return {
                                                                        results: data,
                                                                    };
                                                                },
                                                                cache: true
                                                            }
                                                        });
                                                    })
                                                }
                                            },
                                            error: function() {
                                                swal('Error :)');
                                            }
                                        });
                                    });
                                    json.detail_material[o].map((d) => {
                                        // console.log(d.id_product_wip)
                                        var ii = parseInt($('#jml_item').val()) + 1;
                                        var col = "";
                                        $('#jml_item').val(ii);
                                        var newRow = $(`<tr class="td_${i}">`);
                                        col += `
                                        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
                                        <td>
                                            <select data-iter = "${i}_${ii}" class="form-control input-sm material_${i}" id="id_material_${i}_${ii}" name="id_material[]" onchange="get_material_detail(${i},${ii});">
                                                <option value="${d.id_material}">[${d.i_material}] - ${d.e_material_name} - ${d.e_satuan_name}</option>
                                            </select>
                                            <input type="hidden" class="product_${i}" id="id_product_${i}_${ii}" name="id_product[]" value="${d.id_product_wip}">
                                            <input type="hidden" class="product_${i}" id="id_marker_${i}_${ii}" name="id_marker[]" value="${d.id_marker}">
                                            <input type="hidden" class="product_${i}" id="n_quantity_product_${i}" name="n_quantity_product[]" value=${dd.n_quantity}>
                                        </td>
                                        <td><input type="text" id="n_kebutuhan_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_kebutuhan[]" value="${d.n_kebutuhan}"></td>
                                        <td><input type="text" id="n_stock_material_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_stock_material[]" value="${d.n_saldo_akhir}"></td>
                                        <td><input type="number" id="n_kebutuhan_material_${i}_${ii}" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_kebutuhan_material[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="${dd.n_quantity * d.n_kebutuhan}" onkeyup="angkahungkul(this);"></td>
                                        <td><input type="text" class="form-control input-sm" name="e_note[]" placeholder="Isi keterangan jika ada!"/></td>
                                        <td class="text-center"><button type="button" title="Delete" data-b="${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
                                        `;
                                        newRow.append(col);
                                        $(newRow).insertAfter("#table .tr_second" + i);
                                        $(`#id_product_${i}_${ii}`).val($('#id_product_wip' + i).val());
                                        $(`#id_marker_${i}_${ii}`).val($('#id_marker' + i).val());
                
                                        $(`#id_material_${i}_${ii}`).select2({
                                            placeholder: 'Cari Kode / Nama Material',
                                            allowClear: true,
                                            width: "100%",
                                            type: "POST",
                                            ajax: {
                                                url: '<?= base_url($folder . '/cform/material/'); ?>',
                                                dataType: 'json',
                                                delay: 250,
                                                data: function(params) {
                                                    var query = {
                                                        q: params.term,
                                                        id_product: $('#id_product_wip' + i).val(),
                                                        id_type: $('#id_type').val(),
                                                        id_marker: $('#id_marker' + i).val()
                                                    }
                                                    return query;
                                                },
                                                processResults: function(data) {
                                                    return {
                                                        results: data,
                                                    };
                                                },
                                                cache: true
                                            }
                                        });
                                    })
                                })
                                $(".toggler").click(function(e) {
                                    e.preventDefault();
                                    $('.' + $(this).attr('data-prod-cat')).toggle();
                                    // $(this).addClass('active');

                                    //Remove the icon class
                                    if ($(this).find('i').hasClass('fa-eye')) {
                                        //then change back to the original one
                                        $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                                    } else {
                                        //Remove the cross from all other icons
                                        $('.faq-links').each(function() {
                                            if ($(this).find('i').hasClass('fa-eye')) {
                                                $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                                            }
                                        });

                                        $(this).find('i').addClass('fa-eye').removeClass($(this).data('icon-name'));
                                    }
                                });
                            }
                        } else {
                            swal({
                                title: "Gagal!",
                                text: "File Gagal Diupload :)",
                                type: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    } else {
                        swal({
                            title: "Maaf!",
                            text: "Referensi yang dipilih tidak sama dengan referensi yang di download :)",
                            type: "info",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
            });
        });

    });

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
                $('#imemo').val(data);
            },
            error: function() {
                swal('Error :(');
            }
        });
    }

    function tambah_product(jml) {
        i = jml + 1;
        $("#jml").val(i);
        var no = $(`#table .tr`).length;
        var newRow = $(`<tr class="tr tr_first${i}">`);
        var cols = "";
        cols += `
            <td class="text-center"><spanlistx id="snum${i}"><b>${(no+1)}</b></spanlistx></td>
            <td><select id="id_product_wip${i}" class="form-control input-sm" name="id_product_wip${i}" onchange="cek_product(${i});"></select> <select id="id_marker${i}" class="form-control input-sm" name="id_marker${i}" onchange="cek_product(${i});"></select></td>
            <td><input type="number" id="n_quantity${i}" class="form-control text-right input-sm" autocomplete="off" name="n_quantity${i}" onblur="if(this.value==''){this.value=='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="berhitung(${i});setqtyproduct(${i})"></td>
            <td colspan="3"></td>
            <td class="text-center"><button type="button" onclick="hapus_detail(${i});" title="Delete" class="btn btn-sm btn-circle btn-danger"><i class="fa fa-close fa-lg" aria-hidden="true"></i></button></td>
        `;
        newRow.append(cols);
        $("#table tr:first").after(newRow);
        var newRow1 = $(
            `<tr class="table-active tr_second${i}">
                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                <td><b>LIST BARANG MATERIAL</b></td>
                <td class="text-right"><b>Kebutuhan Per Pcs</b></td>
                <td class="text-right"><b>Stock Material</b></td>
                <td class="text-right"><b>Kebutuhan Material</b></td>
                <td><b>Keterangan</b></td>
                <td class="text-center"><button data-urut="${i}" type="button" onclick="tambah_material(${i});" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i data-urut="${i}" id="addlist${i}"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></button></td>
            </tr>`);
        $(newRow1).insertAfter("#table .tr_first" + i);
        restart();
        $('#id_product_wip' + i).select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "75%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/product_wip/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#id_marker' + i).select2({
            placeholder: 'Cari Nama Marker',
            allowClear: true,
            width: "25%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/marker/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        id_product_wip: $('#id_product_wip' + i).val()
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(e) {
            $.ajax({
                type: "post",
                data: {
                    id_product_wip: $('#id_product_wip' + i).val(),
                    // 'id_material': $('#id_material_' + i + '_' + ii).val(),
                    id_type: $('#id_type').val(),
                    id_marker: $(this).val()
                },
                url: '<?= base_url($folder . '/cform/get_material_onchange_detail'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['detail'].length > 0) {
                        data['detail'].map((d) => {
                            // console.log(d)
                            var ii = parseInt($('#jml_item').val()) + 1;
                            var col = "";
                            $('#jml_item').val(ii);
                            var newRow = $(`<tr class="td_${i}">`);
                            col += `
                            <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
                            <td>
                                <select data-iter = "${i}_${ii}" class="form-control input-sm material_${i}" id="id_material_${i}_${ii}" name="id_material[]" onchange="get_material_detail(${i},${ii});">
                                    <option value="${d.id_material}">[${d.i_material}] - ${d.e_material_name} - ${d.e_satuan_name}</option>
                                </select>
                                <input type="hidden" class="product_${i}" id="id_product_${i}_${ii}" name="id_product[]">
                                <input type="hidden" class="product_${i}" id="id_marker_${i}_${ii}" name="id_marker[]">
                                <input type="hidden" class="product_${i}" id="n_quantity_product_${i}" name="n_quantity_product[]">
                            </td>
                            <td><input type="text" id="n_kebutuhan_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_kebutuhan[]" value="${d.n_kebutuhan}"></td>
                            <td><input type="text" id="n_stock_material_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_stock_material[]" value="${d.n_saldo_akhir}"></td>
                            <td><input type="number" id="n_kebutuhan_material_${i}_${ii}" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_kebutuhan_material[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>
                            <td><input type="text" class="form-control input-sm" name="e_note[]" placeholder="Isi keterangan jika ada!"/></td>
                            <td class="text-center"><button type="button" title="Delete" data-b="${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
                            `;
                            newRow.append(col);
                            $(newRow).insertAfter("#table .tr_second" + i);
                            $(`#id_product_${i}_${ii}`).val($('#id_product_wip' + i).val());
                            $(`#id_marker_${i}_${ii}`).val($('#id_marker' + i).val());
    
                            $(`#id_material_${i}_${ii}`).select2({
                                placeholder: 'Cari Kode / Nama Material',
                                allowClear: true,
                                width: "100%",
                                type: "POST",
                                ajax: {
                                    url: '<?= base_url($folder . '/cform/material/'); ?>',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        var query = {
                                            q: params.term,
                                            id_product: $('#id_product_wip' + i).val(),
                                            id_type: $('#id_type').val(),
                                            id_marker: $('#id_marker' + i).val()
                                        }
                                        return query;
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data,
                                        };
                                    },
                                    cache: true
                                }
                            });
                        })
                    }
                },
                error: function() {
                    swal('Error :)');
                }
            });
        });
    }

    function cek_product(i) {
        $(".td_" + i).remove();
        var ada = true;
        for (var x = 1; x <= $('#jml').val(); x++) {
            if ($('#id_product_wip' + i).val() != null) {
                if ((($('#id_product_wip' + i).val()) == $('#id_product_wip' + x).val()) && (i != x)) {
                    swal("kode barang tersebut sudah ada !!!!!");
                    ada = false;
                    break;
                }
            }
        }
        if (!ada) {
            $('#id_product_wip' + i).val('');
            $('#id_product_wip' + i).html('');
        }
    }

    function hapus_detail(x) {
        $(".tr_first" + x).remove();
        $(".tr_second" + x).remove();
        $(".td_" + x).remove();
        restart();
    }

    function restart() {
        var obj = $('#table tr:visible').find('spanlistx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function tambah_material(i) {
        var ii = parseInt($('#jml_item').val()) + 1;
        var col = "";
        $('#jml_item').val(ii);
        var newRow = $(`<tr class="td_${i}">`);
        col += `
        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
        <td>
            <select data-iter = "${i}_${ii}" class="form-control input-sm material_${i}" id="id_material_${i}_${ii}" name="id_material[]" onchange="get_material_detail(${i},${ii});"></select>
            <input type="hidden" class="product_${i}" id="id_product_${i}_${ii}" name="id_product[]">
            <input type="hidden" class="product_${i}" id="id_marker_${i}_${ii}" name="id_marker[]">
            <input type="hidden" class="product_${i}" id="n_quantity_product_${i}" name="n_quantity_product[]">
        </td>
        <td><input type="text" id="n_kebutuhan_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_kebutuhan[]" value="0"></td>
        <td><input type="text" id="n_stock_material_${i}_${ii}" class="form-control text-right input-sm" readonly name="n_stock_material[]" value="0"></td>
        <td><input type="number" id="n_kebutuhan_material_${i}_${ii}" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_kebutuhan_material[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>
        <td><input type="text" class="form-control input-sm" name="e_note[]" placeholder="Isi keterangan jika ada!"/></td>
        <td class="text-center"><button type="button" title="Delete" data-b = "${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
        `;
        newRow.append(col);
        $(newRow).insertAfter("#table .tr_second" + i);
        $(`#id_product_${i}_${ii}`).val($('#id_product_wip' + i).val());
        $(`#id_marker_${i}_${ii}`).val($('#id_marker' + i).val());
        $(`#n_quantity_product_${i}`).val($('#n_quantity' + i).val());

        $(`#id_material_${i}_${ii}`).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        id_product: $('#id_product_wip' + i).val(),
                        id_type: $('#id_type').val(),
                        id_marker: $('#id_marker' + i).val()
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: true
            }
        });
    }

    function get_material_detail(i, ii) {
        var ada = true;
        var y = i + '_' + ii;
        $(`#table tr .material_${i}`).each(function() {
            var z = ($(this).data('iter'));
            if ($(this).val() != null) {
                if ((($(this).val()) == $('#id_material_' + i + '_' + ii).val()) && (z != y)) {
                    swal("kode material tersebut sudah ada !!!!!");
                    ada = false;
                    // break;
                    return false;
                }
            }
        });
        if (!ada) {
            $('#id_material_' + i + '_' + ii).val('');
            $('#id_material_' + i + '_' + ii).html('');
        } else {
            $.ajax({
                type: "post",
                data: {
                    'id_product_wip': $('#id_product_wip' + i).val(),
                    'id_material': $('#id_material_' + i + '_' + ii).val()
                },
                url: '<?= base_url($folder . '/cform/get_material_detail'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['detail'].length > 0) {
                        $('#n_kebutuhan_' + i + '_' + ii).val(data['detail'][0]['n_kebutuhan']);
                        $('#n_stock_material_' + i + '_' + ii).val(data['detail'][0]['n_saldo_akhir']);
                        $('#n_quantity' + i).focus();
                        berhitung(i);
                    }
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }
    }

    /**
     * Hapus Detail Item
     */

    function clear_table() {
        $("#table tr:gt(0)").remove();
        $('#jml').val(0);
    }

    function berhitung(i) {
        var n_quantity_product = parseFloat($('#n_quantity' + i).val());
        if (isNaN(n_quantity_product)) {
            n_quantity_product = 0;
        }
        $(`#table .td_${i}`).each(function() {
            var v = parseFloat($(this).find("input[name='n_kebutuhan[]']").val());
            $(this).find("input[name='n_kebutuhan_material[]']").val(n_quantity_product * v);
        })
    }

    function setqtyproduct(i) {
        var n_quantity_product = parseFloat($('#n_quantity' + i).val());
        if (isNaN(n_quantity_product)) {
            n_quantity_product = 0;
        }
        $(`#table .td_${i}`).each(function() {
            // var v = parseFloat($(this).find("input[name='n_kebutuhan[]']").val());
            $(this).find(`#n_quantity_product_${i}`).val(n_quantity_product);
        })
    }

    function export_data() {
        $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/'); ?>');
        return true;
    }
</script>