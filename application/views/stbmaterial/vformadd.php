<style type="text/css">
    .font {
        font-size: 12px;
    }

    #table td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-plus mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Bagian Penerima</label>
                            <div class="col-sm-3">
                                <select name="i_bagian" id="i_bagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="i_document" required="" id="i_stb_sj" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="">
                                </div>
                                <input type="hidden" id="id" nama="id" value="">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="i_bagian_receive" required="" id="i_bagian_receive" class="form-control select2">
                                    <?php if ($bagian_receive->num_rows()>0) {
                                        foreach ($bagian_receive->result() as $key) { ?>
                                            <option value="<?= $key->id_company ?>|<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?> - <?= $key->name ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm mr-2"><i class="fa fa-lg fa-save mr-2"></i>Simpan</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/tambah_kirim/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-lg fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" disabled="true" id="send" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-lg fa-paper-plane-o mr-2"></i>Send</button>
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
                <div class="col-sm-2">
                    <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th>WIP</th>
                            <th>Nama WIP</th>
                            <th>Warna</th>
                            <th>Material</th>
                            <th>Nama Material</th>
                            <th>Satuan</th>
                            <th class="text-right" width="8%;">Stock</span></th>
                            <th class="text-right" width="8%;">Kebutuhan</th>
                            <th class="text-right" width="8%;"><span class="mr-4">Kirim&nbsp;</span></th>
                            <th width="15%;">Keterangan</th>
                            <!-- <th class="text-center" width="3%;">Act</th> -->
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php $i = 0;
                        if ($data_detail->num_rows()>0) {
                            foreach ($data_detail->result() as $key) {
                                $i++; ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><b><?= $i; ?></b></spanx>
                                    </td>
                                    <td>
                                        <input type="hidden" value="<?= $key->id_product; ?>" name="id_product_wip<?= $i; ?>">
                                        <?= $key->i_product_wip; ?>
                                    </td>
                                    <td>
                                        <?= $key->e_product_wipname; ?>
                                    </td>
                                    <td>
                                        <?= $key->e_color_name; ?>
                                    </td>
                                    <td>
                                        <input type="hidden" value="<?= $key->id_material; ?>" name="id_material<?= $i; ?>">
                                        <?= $key->i_material; ?>
                                    </td>
                                    <td>
                                        <?= $key->e_material_name; ?>
                                    </td>
                                    <td>
                                        <?= $key->e_satuan_name; ?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format($key->n_stock, 2, ".", ",") ?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format($key->n_quantity, 4, ".", ",") ?>
                                        <input value="<?= $key->n_quantity; ?>" type="hidden" id="n_quantity_kebutuhan<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="n_quantity_kebutuhan<?= $i; ?>" readonly>
                                        <input value="<?= $key->n_quantity_sisa; ?>" type="hidden" id="n_quantity_sisa<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="n_quantity_sisa<?= $i; ?>" readonly>
                                    </td>
                                    <td>
                                        <input value="<?= $key->n_sisa; ?>" type="number" id="n_quantity<?= $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_quantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="hetang(<?= $i; ?>);">
                                        <input value="<?= $key->n_stock; ?>" type="hidden" id="n_stock<?= $i; ?>" name="n_stock<?= $i; ?>">
                                    </td>
                                    <td>
                                        <input value="" type="text" class="form-control input-sm" name="e_remark_item<?= $i; ?>" id="e_remark_item<?= $i; ?>" placeholder="Isi keterangan jika ada!" />
                                        <input value="<?= $key->id; ?>" type="hidden" name="id_memo_item<?= $i; ?>" id="id_memo_item<?= $i; ?>"/>
                                        <input value="<?= $key->id_document; ?>" type="hidden" name="id_memo<?= $i; ?>" id="id_memo<?= $i; ?>"/>
                                    </td>
                                    <!-- <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td> -->
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    $(document).ready(function() {
        fixedtable($('#tabel'));
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        number();
        $('.select2').select2({
            width: "100%",
        });
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);

        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });


        $('#i_bagian').change(function(event) {
            clear_table();
        });

        /*----------  UPDATE NO DOKUMEN SAAT TANGGAL DOKUMEN DAN BAGIAN PEMBUAT DIRUBAH  ----------*/
        $('#d_document').change(function(event) {
            number();
        });

        /**
         * Tambah Item Khusus Makloon
         */

        var i = $('#jml').val();
        $("#addrow").on("click", function() {
            i++;
            $("#jml").val(i);
            var no = $('#table .tr').length;
            var newRow = $('<tr>');
            var cols = "";
            var col = "";
            cols += `<td class="text-center"><spanx id="snum${i}"><b>${(no+1)}</b></spanx></td>
            <td><select data-nourut="${i}" id="id_material${i}" class="form-control input-sm" name="id_material${i}" onchange="get_stock(${i});"></select></td>
            <td>
                <input type="number" id="n_quantity${i}" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_quantity${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="hetang(${i});">
                <input value="0" type="hidden" id="n_stock${i}" name="n_stock${i}">
            </td>
            <td><input type="text" class="form-control input-sm" name="e_remark_item${i}" id="e_remark_item${i}" placeholder="Isi keterangan jika ada!"/></td>
            <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
            newRow.append(cols);
            // $("#table").append(newRow);
            $("#table tr:first").after(newRow);
            restart();
            $(`#id_material${i}`).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            dfrom: $('#dfrom').val(),
                            dto: $('#dto').val(),
                            i_bagian: $('#i_bagian').val(),
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
            })
            /* .change(function(event) {
                            var z = $(this).data('nourut');
                            
                        }) */
            ;
        });

        $("#table").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            var obj = $('#table tr:visible').find('spanx');
            $.each(obj, function(key, value) {
                id = value.id;
                $('#' + id).html(key + 1);
            });
        });
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
    $("#submit").click(function(event) {
        var ada = false;
        for (let i = 1; i <= $('#jml').val(); i++) {
            var qty = parseFloat($('#n_quantity' + i).val());
            var stock = parseFloat($('#n_stock' + i).val());
            if (qty > stock) {
                swal("Maaf :(", "Jumlah = " + qty + ", tidak boleh lebih dari stock = " + stock + "", "error");
                $('#n_quantity' + i).val(stock);
                ada = true;
            }         
        }
        if (!ada) {

            var valid = $("#cekinputan").valid();
            if (valid) {
                /* if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else { */
                swal({
                    title: "Simpan Data Ini?",
                    text: "Jika ada Qty yang 0 data tersebut tidak akan tersimpan",
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
        }
        return false;
    });

    /*----------  NOMOR DOKUMEN  ----------*/

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#d_document').val(),
                'i_bagian': $('#i_bagian').val(),
                'i_bagian_receive': $('#i_bagian_receive').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#i_stb_sj').val(data);
            },
            error: function() {
                swal('Error, generate doc number :(');
            }
        });
    }

    function toge(i) {
        $('.cat_' + $(".toggler" + i).attr('data-prod-cat')).toggle();
        //Remove the icon class
        if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
            //then change back to the original one
            $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
        } else {
            //Remove the cross from all other icons
            $('.faq-links').each(function() {
                if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
                    $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
                }
            });
            $(".toggler" + i).find('i').addClass('fa-eye-slash').removeClass($(".toggler" + i).data('icon-name'));
        }
    }

    function get_stock(z) {
        $.ajax({
            type: "post",
            data: {
                'id_material': $('#id_material' + z).val(),
                'dfrom': $('#dfrom').val(),
                'dto': $('#dto').val(),
                'i_bagian': $('#i_bagian').val(),
            },
            url: '<?= base_url($folder . '/cform/get_stock'); ?>',
            dataType: "json",
            success: function(data) {
                if (data['detail'].length > 0) {
                    $('#n_stock' + z).val(data['detail'][0]['n_stock']);
                } else {
                    $('#n_stock' + z).val(0);
                }
                var ada = false;
                for (var x = 1; x <= $('#jml').val(); x++) {
                    if ((($('#id_material' + x).val()) == $('#id_material' + z).val()) && (z != x)) {
                        swal("kode barang sudah ada !!!!!");
                        ada = true;
                        break;
                    }
                }
                if (ada) {
                    $('#id_material' + z).val('');
                    $('#id_material' + z).html('');
                }
                // alert(z);
                $('#n_quantity' + z).focus();
            },
            error: function() {
                swal('Error :(');
            }
        });
    }

    function restart() {
        var obj = $('#table tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function clear_table() {
        // $('#table'),remove();
        // $('#table > tbody').remove();

        // $("#table > tr:eq(1)").remove();
        $("#table tr:gt(0)").remove();
        // $('#tableBody').find('tr').remove();
        $('#jml').val(0);
    }

    function hetang(i) {
        var qty = parseFloat($('#n_quantity' + i).val());
        var qty_sisa = parseFloat($('#n_quantity_sisa' + i).val());
        var stock = parseFloat($('#n_stock' + i).val());
        if (qty > stock) {
            swal("Maaf :(", "Jumlah = " + qty + ", tidak boleh lebih dari stock = " + stock + "", "error");
            $('#n_quantity' + i).val(0);
        }
    }
</script>