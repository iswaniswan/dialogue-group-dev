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
                            <select name="ibagian" id="ibagian" onchange="number();" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row): ?>
                                        <option value="<?= $row->id; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="i_dt_id" id="i_dt_id" readonly autocomplete="off"
                                        placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value=""
                                        aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_dt" name="d_dt" class="form-control input-sm date" required=""
                                readonly onchange="number();" value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="id_area" id="id_area" class="form-control select2">
                                <?php foreach ($all_area as $area)  { ?>
                                    <option value="<?= $area->id; ?>"><?="[" . $area->i_area . "] - " . $area->e_area; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nama Pelanggan</label>
                        <label class="col-md-3">Nama Sales</label>
                        <label class="col-md-3">No. Daftar Tagihan</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="id_customer" id="id_customer" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="id_sales" id="id_sales" class="form-control select2">                               
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="id_daftar_tagihan" id="id_daftar_tagihan" class="form-control select2">                               
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <textarea name="keterangan" id="keterangan" cols="24" rows="2">
                            </textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"
                                onclick="return konfirm();"><i class="fa fa-lg fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm"><i
                                    class="fa fa-lg fa-plus"></i>&nbsp;&nbsp;Item</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i
                                    class="fa fa-lg fa-arrow-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm"><i
                                    class="fa fa-lg fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
                            <table id="tabledatax" class="table color-table success-table table-bordered class"
                                cellpadding="8" cellspacing="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%;">No</th>
                                        <th style="width: auto;">No. Nota</th>
                                        <th style="width: auto;">Tgl. Nota</th>
                                        <th style="width: 10px;">Jumlah Nota</th>
                                        <th class="text-right" style="width: auto;">Jumlah</th>
                                        <th class="text-center" style="width: auto;">Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="5">Total</th>
                                        <th class="text-right"><span id="jumlah"></span><input type="hidden"
                                                class="form-control form-control-sm text-right" name="v_jumlah"
                                                id="v_jumlah" value="0" readonly></th>
                                    </tr>
                                </tfoot>
                                <input type="hidden" name="jml" id="jml" value="0">
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

        /**
         * Tambah Item
         */
        var i = $('#jml').val();
        $("#addrow").on("click", function () {
            //alert("tes");
            i++;
            $("#jml").val(i);
            var no = parseInt($('#tabledatax > tbody tr').length + 1);
            var newRow = $('<tr id="tr' + i + '">');
            var cols = "";
            cols += '<td class="text-center"><spanx id="snum' + i + '">' + no + '</spanx></td>';
            cols += '<td ><select data-nourut="' + i + '" id="i_nota' + i + '" class="form-control input-sm" name="i_nota' + i + '"></select></td>';
            cols += `<td><input type="hidden" name="d_nota_${i}" id="d_nota_${i}" value=""><span class="d_nota_${i}"></span></td>`;
            cols += `<td><input type="hidden" name="e_customer_name_${i}" id="e_customer_name_${i}" value=""><span class="e_customer_name_${i}"></span></td>`;
            cols += `<td class="text-right"><input type="hidden" name="v_nota_${i}" id="v_nota_${i}" value=""><span class="text-right v_nota_${i}"></span></td>`;
            cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
            cols += `</tr>`;
            newRow.append(cols);
            $("#tabledatax").append(newRow);

            /** dropdown detail nota penjualan */
            $('#i_nota' + i).select2({
                placeholder: 'Cari Nota Penjualan',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_all_nota_penjualan/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term,
                            id_customer: $('#id_customer').val(),
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
                /**
                 * Cek Barang Sudah Ada
                 * Get Harga Barang
                 */
                var z = $(this).data('nourut');
                var ada = true;
                for (var x = 1; x <= $('#jml').val(); x++) {
                    if ($(this).val() != null) {
                        if ((($(this).val()) == $('#i_nota' + x).val()) && (z != x)) {
                            swal("Nota tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {
                    $(this).val('');
                    $(this).html('');
                } else {
                    $.ajax({
                        type: "post",
                        data: {
                            'id': $(this).val(),
                        },
                        url: '<?= base_url($folder . '/cform/detailnota'); ?>',
                        dataType: "json",
                        success: function (data) {
                            $('#d_nota_' + z).val(data['detail'][0]['d_nota']);
                            $('.d_nota_' + z).text(data['detail'][0]['d_document']);
                            $('#d_jatuh_tempo_' + z).val(data['detail'][0]['d_jatuh_tempo']);
                            $('.d_jatuh_tempo_' + z).text(data['detail'][0]['d_jatuh_tempo']);
                            $('#e_customer_name_' + z).val(data['detail'][0]['e_customer_name']);
                            $('.e_customer_name_' + z).text(data['detail'][0]['e_customer_name']);
                            $('#v_nota_' + z).val(data['detail'][0]['v_bersih']);
                            $('.v_nota_' + z).text(formatcemua(data['detail'][0]['v_bersih']));
                            $('#v_sisa_' + z).val(data['detail'][0]['v_sisa']);
                            $('.v_sisa_' + z).text(formatcemua(data['detail'][0]['v_sisa']));
                            hetang()

                        },
                        error: function () {
                            swal('Data kosong : (');
                        }
                    });
                }
            });


        });

        /**
         * Hapus Detail Item
         */

        $("#tabledatax").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();
            $('#jml').val(i);
            del();
        });

        $('#ceklis').click(function (event) {
            if ($('#ceklis').is(':checked')) {
                $("#i_dt_id").attr("readonly", false);
            } else {
                $("#i_dt_id").attr("readonly", true);
                $("#ada").attr("hidden", true);
                number();
            }
        });

        /** dropdown area */
        $('#id_area').select2({
            placeholder: 'Pilih Area',
            allowClear: false,
            width: "100%",
        }).change(function() {
            $('#id_customer').val(null).trigger('change');
            $('#id_sales').val(null).trigger('change');
            $('#id_daftar_tagihan').val(null).trigger('change');
            number();
            clear_table();
        });

        /** dropdown customer */
        $('#id_customer').select2({
            placeholder: 'Pilih Customer',
            allowClear: false,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_all_customer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        id_area: $('#id_area').val(),
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
        
        /** dropdown salesman */
        $('#id_sales').select2({
            placeholder: 'Pilih Sales',
            allowClear: false,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_all_sales/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        id_area: $('#id_area').val(),
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

        /** dropdown daftar tagihan */
        $('#id_daftar_tagihan').select2({
            placeholder: 'Pilih Tagihan',
            allowClear: false,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_all_daftar_tagihan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        id_area: $('#id_area').val(),
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

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function (key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#d_dt').val(),
                'ibagian': $('#ibagian').val(),
                'id_area': $('#id_area').val(),
            },
            url: '<?= base_url($folder . '/cform/generate_nomor_dokumen'); ?>',
            dataType: "json",
            success: function (data) {
                $('#i_dt_id').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getstok(id) {
        var idproduct = $('#idproduct' + id).val();
        $.ajax({
            type: "post",
            data: {
                'idproduct': idproduct,
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/getstok'); ?>',
            dataType: "json",
            success: function (data) {
                //console.log(data.saldo_akhir);
                $('#stok' + id).val(data.saldo_akhir);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function () {
                $(this).find("td select").each(function () {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Nota tidak boleh kosong!');
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

    function clear_table() {
        $("#tabledatax > tbody").remove();
        $("#jml").val(0);
    }

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