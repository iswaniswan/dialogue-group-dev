<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="iretur" id="i_retur_wip" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dretur" name="dretur" class="form-control input-sm date" required="" readonly value="<? echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
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
        <!-- <div class="m-b-0">
            <div class="form-group row">
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>Item</button>
                </div>
            </div>
        </div> -->
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">Kode Barang</th>
                        <th class="text-center" style="width: 27%;">Nama Barang Jadi</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Quantity</th>
                        <th class="text-center" colspan="3" style="width: 30%;">Keterangan</th>
                        <th class="text-center" style="width: 5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    $(document).ready(function() {
        // $('#i_retur_wip').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();
    });

    $('#ibagian, #dretur').change(function(event) {
        number();
    });

    $("#i_retur_wip").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#dretur').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#i_retur_wip').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#i_retur_wip").attr("readonly", false);
        } else {
            $("#i_retur_wip").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    var counter = 0;

    var counter = $('#jml').val();
    var counterx = counter - 1;
    $("#addrow").on("click", function() {
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var iproduct = $('#iproduct' + counterx).val();
        count = $('#tabledatax .no').length + 1;
        if ((iproduct == '' || iproduct == null) && (count > 1)) {
            swal('Isi dulu yang masih kosong!!');
            counter = counter - 1;
            counterx = counterx - 1;
            return false;
        }
        $('#jml').val(counter);
        var newRow = $("<tr class='no tr" + counter + "'>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum' + counter + '">' + count + '</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct' + counter + '" class="form-control" name="idproduct[]"> <input type="text" readonly id="iproduct' + counter + '" class="form-control input-sm" name="iproduct' + counter + '"> <input type="hidden" readonly id="idmarker' + counter + '" class="form-control" name="idmarker[]"></td>';
        cols += '<td><select type="text" id="eproduct' + counter + '" class="form-control select2" name="eproduct' + counter + '" onchange="getproduct(' + counter + ');"></select> <select type="text" id="emarker' + counter + '" class="form-control select2" name="emarker' + counter + '" onchange="getproduct(' + counter + ');"></select></td>';
        cols += '<td><input type="hidden" id="idcolorproduct' + counter + '" class="form-control" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct' + counter + '" class="form-control input-sm" name="ecolorproduct' + counter + '"></td>';
        cols += '<td><input type="hidden" id="nquantity_stok' + counter + '" value="0"><input type="text" id="nquantity' + counter + '" class="form-control text-right input-sm" name="nquantity[]" value="" placeholder="0" onblur=\"if(this.value==""){this.value="0";}\" onfocus=\"if(this.value=="0"){this.value="";}\" onkeypress="return hanyaAngka(event);" onkeyup="hetang(' + counter + '); berhitung(' + counter + ')"></td>';
        cols += '<td colspan="3"><input type="text" id="edesc' + counter + '" class="form-control input-sm" name="edesc[]" placeholder="Isi keterangan jika ada!"></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" data-i = "' + counter + '"  class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $('#eproduct' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            width: "70%",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/dataproduct'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change((e) => {
            // let id_product_wip = e.target.value;
            // let elmarker = $(`#emarker${counter}`);
            // // elmarker.prop('disabled', false);
            // elmarker.removeAttr('disabled');
        });

        $('#emarker' + counter).select2({
            placeholder: 'Cari Nama Marker',
            allowClear: true,
            type: 'POST',
            width: "30%",
            ajax: {
                url: '<?= base_url($folder . '/cform/marker/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        id_product_wip: $('#eproduct' + counter).val()
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
    });

    function hetang(params) {
        var qty = parseFloat($('#nquantity' + params).val());
        var stok = parseFloat($('#nquantity_stok' + params).val());
        if (qty > stok) {
            swal("Maaf :(", "Quantity Kirim tidak boleh lebih besar dari Stok = " + stok, "error");
            $('#nquantity' + params).val(stok);
        }
        berhitung(params);
    }

    function formatSelection(val) {
        return val.name;
    }

    function getproduct(id) {
        ada = false;
        var a = $('#eproduct' + id).val();
        var b = $('#emarker' + id);
        var x = $('#jml').val();
        if(a.length > 0 /* && b != null */) {
            for (i = 1; i <= x; i++) {
                if ((a == $('#eproduct' + i).val()) && (i != x)) {
                    swal("kode Barang : " + a + " sudah ada !!!!!");
                    ada = true;
                    break;
                } else {
                    ada = false;
                }
            }
    
            if (!ada) {
                var eproduct = $('#eproduct' + id).val();
                $.ajax({
                    type: "post",
                    data: {
                        'eproduct': eproduct,
                        'ibagian' : $('#ibagian').val(),
                    },
                    url: '<?= base_url($folder . '/cform/getproduct'); ?>',
                    dataType: "json",
                    success: function(data) {
                        $('#idproduct' + id).val(data['data'][0].id_product);
                        $('#idmarker' + id).val(data['data'][0].id_marker);
                        $('#iproduct' + id).val(data['data'][0].i_product_base);
                        $('#emarker' + id).html((data['data'][0].id_marker) ? `<option value="${data['data'][0].id_marker}" selected>${data['data'][0].e_marker_name}</option>` : '');
                        $('#idcolorproduct' + id).val(data['data'][0].id_color);
                        $('#ecolorproduct' + id).val(data['data'][0].e_color_name);
                        $('#nquantity_stok' + id).val(data['data'][0].n_saldo_akhir);
                        $('#nquantity' + id).focus();
                        if (data['detail'].length > 0) {
                            hapus_tr(id);
                            $(`
                            <tr class="th${id} bold">
                                <td class="text-center"><i class="fa fa-hashtag fa-lg" aria-hidden="true"></i></td>
                                <td>Kode Material</td>
                                <td>Nama Material</td>
                                <td>Satuan</td>
                                <td class="text-right">Kebutuhan<br>Per PCs</td>
                                <td class="text-right">Qty Total</td>
                                <td class="text-right">Qty Rusak</td>
                                <td class="text-right">Qty Bagus</td>
                                <td class="text-center"><i class="fa fa-list-ul fa-lg" aria-hidden="true"></i></td>
                            </tr>
                            `).insertAfter("#tabledatax .tr" + id);
                            for (let j = 0; j < data['detail'].length; j++) {
                                var newRow = $(`<tr class="td${id}">`);
                                var cols = "";
                                cols += `
                                    <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                    <td>${data['detail'][j]['i_material']}</td>
                                    <td>${data['detail'][j]['e_material_name']}</td>
                                    <td>${data['detail'][j]['e_satuan_name']}</td>
                                    <td class="text-right"><span id="n_kebutuhan_perpcs${id}_${j}">${data['detail'][j]['n_kebutuhan']}</span></td>
                                    <td class="text-right"><input type="text" class="form-control input-sm text-right" value="0" readonly id="n_quantity_total${id}_${j}" name="n_quantity_total[]"></td>
                                    <td class="text-right"><input type="number" class="form-control input-sm text-right" autocomplete="off" value="" min="0" onkeyup="berhitung(${id});" onchange="berhitung(${id});" onblur="berhitung(${id});" onkeypress="berhitung(${id});" id="n_quantity_rusak${id}_${j}" name="n_quantity_rusak[]"></td>
                                    <td class="text-right">
                                        <input type="hidden" value="${data['detail'][j]['id_product_base']}" readonly id="id_product${id}_${j}" name="id_product[]">
                                        <input type="hidden" value="${data['detail'][j]['id_material']}" readonly id="id_material${id}_${j}" name="id_material[]">
                                        <input type="text" class="form-control input-sm text-right" value="0" readonly id="n_quantity_bagus${id}_${j}" name="n_quantity_bagus[]">
                                    </td>
                                    <td class="text-center"><i class="fa fa-thumbs-o-up fa-lg text-success" aria-hidden="true"></i></td>
                                `;
                                newRow.append(cols);
                                $(newRow).insertAfter("#tabledatax .th" + id);
                                // $("#tabledatax").append(newRow);         
                            }
                        }
                    },
                    error: function() {
                        swal('Error :)');
                    }
                });
            } else {
                $('#idproduct' + id).html('');
                $('#iproduct' + id).html('');
                $('#eproduct' + id).html('');
                $('#idcolorproduct' + id).html('');
                $('#ecolorproduct' + id).html('');
                $('#idproduct' + id).val('');
                $('#iproduct' + id).val('');
                $('#eproduct' + id).val('');
                $('#idcolorproduct' + id).val('');
                $('#ecolorproduct' + id).val('');
            }
        }
    }

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        $('#jml').val(counter);
        del();
        hapus_tr($(this).data('i'));
    });

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function hapus_tr(i) {
        $(`.th${i}`).closest("tr").remove();
        $(`.td${i}`).closest("tr").remove();
    }

     function berhitung(i) {
        var n_quantity_product = parseFloat($('#nquantity' + i).val());

        
        if (isNaN(n_quantity_product)) {
            n_quantity_product = 0;
        }

        for (let j = 0; j < $(`#tabledatax .td${i}`).length; j++) {
            var n_quantity_total = parseFloat($('#n_quantity_total' + i + '_' + j).val());
            var n_kebutuhan_perpcs = parseFloat($('#n_kebutuhan_perpcs' + i + '_' + j).text());
            var n_quantity_rusak = parseFloat($('#n_quantity_rusak' + i + '_' + j).val());
            if (isNaN(n_quantity_rusak)) {
                n_quantity_rusak = 0;
            }
            if (n_quantity_rusak > n_quantity_total) {
                n_quantity_rusak = n_quantity_total;
                $('#n_quantity_rusak' + i + '_' + j).val(n_quantity_rusak);
            }
            var n_quantity_total = (n_quantity_product * n_kebutuhan_perpcs);
            $('#n_quantity_total' + i + '_' + j).val(n_quantity_total);
            var n_quantity_bagus = (n_quantity_product * n_kebutuhan_perpcs) - n_quantity_rusak;
            $('#n_quantity_bagus' + i + '_' + j).val(n_quantity_bagus);
        }
    }

    function konfirm() {
        var jml = $('#jml').val();
        if (($('#ibagian').val() != '' || $('#ibagian').val()) && ($('#itujuan').val() != '' || $('#itujuan').val())) {
            if (jml == 0) {
                swal('Isi data item minimal 1 !!!');
                return false;
            } else {
                for (i = 1; i <= jml; i++) {
                    if ($("#iproduct" + i).val() == '' || $("#eproductname" + i).val() == '' || $("#nquantity" + i).val() == '') {
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        } else {
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    }
</script>