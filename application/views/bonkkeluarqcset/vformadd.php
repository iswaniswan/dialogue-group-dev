<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
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
                                    <input type="text" name="ibonk" id="ibonk" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<? echo date("d-m-Y"); ?>">
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
                            <label class="col-md-3">Jenis Barang</label>
                            <label class="col-md-9">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="ijenis" id="ijenis" class="form-control select2">
                                    <?php if ($jenisbarang) {
                                        foreach ($jenisbarang as $row) : ?>
                                            <option value="<?= $row->id; ?>">
                                                <?= $row->e_jenis_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-9">
                                <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-lg fa-save mr-2"></i>Simpan</button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="addrow" class="btn btn-info btn-block btn-sm mr-2" onclick="getproduct($('#jml').val());"><i class="fa fa-lg fa-plus mr-2"></i>Item</button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa-lg ti-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-lg fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
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
                                <th class="text-center" style="width: 25%;">Kode</th>
                                <th class="text-center" style="width: 25%;">Nama Barang</th>
                                <th class="text-right" style="width: 5%;">Qty</th>
                                <th class="text-right" style="width: 10%;">Qty Penyusun</th>
                                <th class="text-right" style="width: 10%;">Qty Stock</th>
                                <th class="text-right" style="width: 10%;">Qty Akhir</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center" style="width: 3%;">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date', null, 0);
        number();
    });

    /**
     * Tambah Item
     */

    function getproduct(i) {
        i = parseInt(i) + 1;
        $("#jml").val(i);
        // console.log(i);
        var no = $('#tabledatax tr').length;
        var newRow = $('<tr id="tr' + i + '">');
        var cols = "";
        cols += `<td colspan="3"><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" onchange="getdetail(${i});"></select> <select data-nourut="${i}" id="idmarker${i}" class="form-control input-sm" name="idmarker${i}" onchange="getdetail(${i});"></select></td>`;
        cols += `<td ><input data-noqty="${i}" class="form-control qty input-sm text-right qty inputitem" autocomplete="off" type="text" name="nquantity${i}" id="nquantity${i}" onblur=\'if(this.value==""){this.value="0";kalikan();}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); kalikan();"></td>`;
        cols += `<td colspan="4"></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" onclick="hapusdetail(${i});" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        cols += `</tr>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct' + i).select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "75%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/product/'); ?>',
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
        })
        $('#idmarker' + i).select2({
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
                        id_product_wip: $('#idproduct' + i).val()
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
        })
    }

    // var i = $('#jml').val();
    // $("#addrow").on("click", function () {
    //     //alert("tes");
    //     i++;
    //     $("#jml").val(i);
    //     var no     = $('#tabledatax tr').length;
    //     var newRow = $('<tr id="tr'+i+'">');
    //     var cols   = "";
    //     cols += `<td colspan="3"><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" onchange="getdetail(${i});"></select></td>`;
    //     cols += `<td ><input data-noqty="${i}" class="form-control qty input-sm text-right qty inputitem" autocomplete="off" type="text" name="nquantity${i}" id="nquantity${i}" onblur=\'if(this.value==""){this.value="0";kalikan();}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); kalikan();"></td>`;
    //     cols += `<td colspan="3"></td>`;
    //     cols += `<td class="text-center"><button type="button" title="Delete" onclick="hapusdetail(${i});" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
    //     cols += `</tr>`;
    //     newRow.append(cols);
    //     $("#tabledatax").append(newRow);
    //     $('#idproduct'+ i).select2({
    //         placeholder: 'Cari Kode / Nama WIP',
    //         allowClear: true,
    //         width: "100%",
    //         type: "POST",
    //         ajax: {
    //             url: '<?= base_url($folder . '/cform/product/'); ?>',
    //             dataType: 'json',
    //             delay: 250,
    //             data: function (params) {
    //                 var query   = {
    //                     q         : params.term,
    //                 }
    //                 return query;
    //             },
    //             processResults: function (data) {
    //                 return {
    //                     results: data
    //                 };
    //             },
    //             cache: true
    //         }
    //     })
    // });

    function getdetail(z) {
        /**
         * Cek Barang Sudah Ada
         * Get Harga Barang
         */
        // var z = $(this).data('nourut');
        var idproduct = $("#idproduct" + z).val();
        var idmarker = $("#idmarker" + z).val();
        var ada = true;
        if(idproduct != null && idmarker != null) {
            for (var x = 1; x <= $('#jml').val(); x++) {
                if (idproduct != null) {
                    if (((idproduct) == $('#idproduct' + x).val()) && (z != x)) {
                        swal("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {
                $("#idproduct" + z).val('');
                $("#idproduct" + z).html('');
            } else {
                $.ajax({
                    type: "post",
                    data: {
                        'id': idproduct,
                        'id_marker': idmarker,
                        'i_bagian': $('#ibagian').val(),
                    },
                    url: '<?= base_url($folder . '/cform/detailproduct'); ?>',
                    dataType: "json",
                    success: function(data) {
                        $("#tabledatax tbody").each(function() {
                            $("tr.del" + z).remove();
                            $("#nquantity"+z).val(0);
                        });
                        var xx = 0;
                        var netr = "";
                        /*for (let x = 0; x < data['detail'].length; x++) {*/
                        for (let x = data['detail'].length; x > 0; x--) {
                            var newRow1 = $('<tr class="del' + z + '">');
                            var cols = "";
                            cols += '<td class="text-center">' + x + '<input type="hidden" name="idpanel[]" value="' + data['detail'][xx]['id'] + '"></td>';
                            cols += '<td><input type="hidden" name="idproductwip[]" value="' + data['detail'][xx]['id_product_wip'] + '">';
                            cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" value="' + data['detail'][xx]['id_material'] + '">';
                            cols += '<input class="form-control input-sm" readonly type="text" value="' + data['detail'][xx]['i_panel'] + '"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" value="' + data['detail'][xx]['i_material'] + ' - ' + htmlEntities(data['detail'][xx]['e_material_name']) + '"></td>';
                            // cols += '<td><input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="hidden" name="nqty" id="nqty'+z+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
                            cols += '<td></td>';
                            cols += '<td><input readonly class="form-control qty input-sm text-right inputqty_' + idproduct + ' material_' + idproduct + '_' + x + '" data-noqty="' + x + '"  autocomplete="off" type="text" id="npenyusun' + x + '" name="npenyusun[]" value="' + data['detail'][xx]['n_qty_penyusun'] + '" onkeyup="angkahungkul(this); kalikan();"></td>';
                            cols += '<td><input readonly class="form-control qty input-sm text-right stock_' + idproduct + '_' + x + '" type="text" id="n_stock' + x + '" name="n_stock[]" value="' + data['detail'][xx]['n_saldo_akhir'] + '"></td>';
                            cols += '<td><input readonly class="form-control qty input-sm text-right akhir_' + idproduct + ' inputitem sisa_' + idproduct + '_' + x + '" autocomplete="off" type="text" id="nqtysisa' + x + '" name="nqtysisa[]" value="0" onkeyup="angkahungkul(this);"></td>';
                            cols += '<td colspan="2"><input class="form-control input-sm" type="text" name="eremark[]" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                            newRow1.append(cols);
                            $('#nquantity' + z).focus();
                            /*$("#tabledatax #tr"+z).insertAfter(newRow1);*/
                            $(newRow1).insertAfter("#tabledatax #tr" + z);
                            xx++;
                        }
                    },
                    error: function() {
                        swal('Data kosong : (');
                    }
                });
            }
        }

    }

    /**
     * Hapus Detail Item
     */
    function hapusdetail(x) {
        $("#tabledatax tbody").each(function() {
            $("tr.del" + x).remove();
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
    });

    //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#dbonk').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#ibonk').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#ibonk").attr("readonly", false);
        } else {
            $("#ibonk").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $("#ibonk").keyup(function() {
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

    function kalikan() {
        /* var id = $("#nquantity"+id).data('noqty');
        var qtyparent = $("#nquantity"+id).val();
        var limiter = $("#limiter"+id).val();
        var penyusun = $(".del"+id+" .npenyusun").val(); */
        // console.log(penyusun);
        // $("#nqty"+id).val(qtyparent)

        var jml = $("#jml").val();
        for (i = 1; i <= jml; i++) {
            var product = parseFloat($("#idproduct" + i).val());
            var qtywip = parseFloat($("#nquantity" + i).val());
            if (isNaN(qtywip)) {
                qtywip = 0;
            }
            // console.log(product + " / " + qtywip);
            var x = 1;
            var n_stock = [];
            $("#tabledatax tbody tr td .inputqty_" + product).each(function() {
                var npenyusun = $(".material_" + product + "_" + x).val();
                var stock = parseFloat($(".stock_" + product + "_" + x).val());
                n_stock.push(stock);
                var kali = qtywip * npenyusun;
                if(kali > stock){
                    swal("Maaf :(", "Qty Akhir tidak boleh lebih besar dari Qty Stock :(","error");
                    $(".akhir_" + product).val(0);
                    $("#nquantity" + i).val(0);
                    return false;
                }
                $(".sisa_" + product + "_" + x).val(kali);
                x++;
            });
            var Numbers = n_stock;
            var l = Numbers.length;
            var min = Infinity;
            var j;
            for (j = 0; l > j; j++) {
                if (Numbers[j] < min) {
                    // console.log(i + ' lowest number' + Numbers[j]);
                    min = Numbers[j];
                }
            }
            if (min <= 0) {
                min = 0;
            }
            if(qtywip > min) {
                $("#nquantity" + i).val(min);
                $(".akhir_" + product).val(min);
            }
            // console.info(max);
        }

    }

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

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
</script>