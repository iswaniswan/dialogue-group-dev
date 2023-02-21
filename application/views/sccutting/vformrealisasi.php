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
                    <i class="fa fa-lg fa-pencil mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="i_bagian" id="i_bagian" required="" class="form-control select2" disabled>
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="i_document" required="" id="i_stb_sj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" readonly>
                                    <input type="hidden" id="id" name="id" value="<?= $data->id; ?>">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= $data->date_document; ?>" readonly disabled>
                            </div>
                            <div class="col-sm-4">
                                <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!" disabled><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($data->i_status == '6') { ?>
                                <div class="col-sm-3">
                                    <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2 fa-lg"></i>Update</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                                </div>
                            <?php } elseif ($data->i_status == '2') { ?>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh mr-2 fa-lg"></i>Cancel</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="row">
            <div class="col-sm-12">
                <!-- <div class="form-group"> -->
                <span class="notekode ml-1"><b>Note : </b></span><span class="notekode">* AM = 12 Malam sampai 12 Siang, PM = 12 Siang sampai 12 Malam.</span>
                <!-- </div> -->
            </div>
        </div>
        <div class="table-responsive">
            <table id="table" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <caption><b>Detail Barang</b></caption>
                <thead>
                    <tr class="d-flex">
                        <th class="col-1 text-center">Priority</th>
                        <th class="col-2">Perusahaan</th>
                        <th class="col-1">Tgl. Schedule</th>
                        <th class="col-1">Jam</th>
                        <th class="col-1">Tgl. <br>Realisasi</th>
                        <th class="col-1">Jam <br>Realisasi</th>
                        <th class="col-1">WIP</th>
                        <th class="col-3">Nama WIP</th>
                        <th class="col-1">Material</th>
                        <th class="col-3">Nama Material</th>
                        <th class="col-1">Satuan</th>
                        <th class="col-1 text-right">Qty</th>
                        <th class="col-1 text-right">Qty Product<i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="round down(qty material * set / gelar + 0.01)"></i></th>
                        <th class="col-2 text-right">Jml Gelar<i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="round down(qty product) / set"></i></th>
                        <!-- <th class="col-1 text-right">Jml Gelar</th> -->
                        <th class="col-1">PIC Cutting</th>
                        <th class="col-1">PIC Gelar</th>
                        <th class="col-1">Realisasi Gelar</th>
                        <th class="col-1">Realisasi Product</th>
                        <!-- <th class="col-1">Tgl. Cutting</th> -->
                        <th class="col-2">Keterangan</th>
                        <!-- <th class="text-center" width="3%;">Act</th> -->
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $key) {
                            $i++; ?>
                            <tr class="d-flex">
                                <td class="col-1 text-center"><b><?= $i; ?></b></td>
                                <td class="col-2">
                                    <input type="hidden" value="<?= $key->id; ?>" name="id_item<?= $i; ?>">
                                    <input type="hidden" value="<?= $key->id_company_referensi; ?>" name="id_company_referensi<?= $i; ?>">
                                    <?= $key->name; ?>
                                </td>
                                <td class="col-1 text-center">
                                    <input disabled readonly value="<?= formatdmY($key->d_schedule); ?>" type="text" class="form-control tgl input-sm" name="d_schedule<?= $i; ?>" id="d_schedule<?= $i; ?>" />
                                </td>
                                <td class="col-1 text-center">
                                    <input disabled value="<?= $key->jam; ?>" type="time" id="input-pickuptime" min="00:00" max="23:59" required class="form-control input-sm" name="jam<?= $i; ?>" id="jam<?= $i; ?>" />
                                </td>
                                <td class="col-1 text-center">
                                    <input readonly value="<?php if ($key->d_schedule_realisasi) echo formatdmY($key->d_schedule_realisasi); ?>" type="text" class="form-control date input-sm" name="d_schedule_real<?= $i; ?>" id="d_schedule_real<?= $i; ?>" />
                                </td>
                                <td class="col-1 text-center">
                                    <input value="<?= $key->jam_realisasi; ?>" type="time" id="input-pickuptime" min="00:00" max="23:59" required class="form-control input-sm" name="jam_real<?= $i; ?>" id="jam_real<?= $i; ?>" />
                                </td>
                                <td class="middle col-1 middle">
                                    <input type="hidden" value="<?= $key->id_product_wip; ?>" name="id_product_wip<?= $i; ?>">
                                    <?= $key->i_product_wip; ?>
                                </td hidden>
                                <td class="col-3">
                                    <?= $key->e_product_wipname . ' - ' . $key->e_color_name; ?>
                                </td>
                                <td class="col-1">
                                    <input type="hidden" value="<?= $key->id_referensi; ?>" name="id_referensi<?= $i; ?>">
                                    <input type="hidden" value="<?= $key->id_material; ?>" name="id_material<?= $i; ?>">
                                    <?= $key->i_material; ?>
                                </td>
                                <td class="col-3">
                                    <?= $key->e_material_name; ?>
                                </td>
                                <td class="col-1">
                                    <?= $key->e_satuan_name; ?>
                                </td>
                                <td class="col-1 text-right">
                                    <?= $key->n_quantity; ?>
                                    <input value="<?= $key->n_quantity; ?>" type="hidden" readonly id="n_quantity<?= $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_quantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="hetang(<?= $i; ?>);">
                                    <input value="<?= $key->n_stock; ?>" type="hidden" id="n_stock<?= $i; ?>" name="n_stock<?= $i; ?>">
                                </td>
                                <td class="col-1 text-right">
                                    <?= $key->n_quantity_product; ?>
                                    <input value="<?= $key->n_quantity_product; ?>" type="hidden" readonly id="n_quantity_product<?= $i; ?>" name="n_quantity_product<?= $i; ?>">
                                </td>
                                <td class="col-2 text-right">
                                    <?= $key->n_jumlah_gelar; ?>
                                    <input value="<?= $key->n_jumlah_gelar; ?>" type="hidden" id="n_jumlah_gelar<?= $i; ?>" name="n_jumlah_gelar<?= $i; ?>">
                                    <input value="<?= $key->v_set; ?>" type="hidden" id="v_set<?= $i; ?>" name="v_set<?= $i; ?>">
                                    <input value="<?= $key->v_gelar; ?>" type="hidden" id="v_gelar<?= $i; ?>" name="v_gelar<?= $i; ?>">
                                </td>
                                <!-- <td class="col-1 text-right">
                                    <?= $key->n_quantity_product / $key->v_set; ?>
                                    <input value="<?= $key->n_quantity_product / $key->v_set; ?>" type="hidden" readonly id="n_jumlah_gelar_round_down<?= $i; ?>" name="n_jumlah_gelar_round_down<?= $i; ?>">
                                </td> -->
                                <td class="col-1">
                                    <!-- <select hidden class="form-control input-sm" name="id_pic_cutting<?= $i; ?>" id="id_pic_cutting<?= $i; ?>" data-placeholder="Pilih PIC">
                                        <option value="<?= $key->id_pic_cutting; ?>"><?= $key->e_pic_name_cutting; ?></option>
                                    </select> -->
                                    <input value="<?= $key->e_pic_cutting; ?>" class="form-control input-sm"  type="text" id="e_pic_cutting<?= $i; ?>" name="e_pic_cutting<?= $i; ?>">
                                </td>
                                <td class="col-1">
                                    <!-- <select hidden class="form-control input-sm" name="id_pic_gelar<?= $i; ?>" id="id_pic_gelar<?= $i; ?>" data-placeholder="Pilih PIC">
                                        <option value="<?= $key->id_pic_gelar; ?>"><?= $key->e_pic_name_gelar; ?></option>
                                    </select> -->
                                    <input value="<?= $key->e_pic_gelar; ?>" class="form-control input-sm"  type="text" id="e_pic_gelar<?= $i; ?>" name="e_pic_gelar<?= $i; ?>">
                                </td>
                                <td class="col-1">
                                    <input class="form-control input-sm text-right" autocomplete="off" type="text" id="n_realisasi_gelar<?= $i; ?>" name="n_realisasi_gelar<?= $i; ?>" onkeyup="angkahungkul(this); hetangproductreal(this.value, <?= $i; ?>)" value="<?= ($key->n_realisasi_gelar) ? $key->n_realisasi_gelar : 0 ?>">
                                </td>
                                <td class="col-1">
                                    <input readonly class="form-control input-sm text-right" autocomplete="off" type="text" id="n_realisasi_product<?= $i; ?>" name="n_realisasi_product<?= $i; ?>" onkeyup="angkahungkul(this);" value="<?= ($key->n_realisasi_product) ? $key->n_realisasi_product : 0 ?>">
                                </td>
                                <td class="col-1 text-center" hidden>
                                    <input readonly value="<?= formatdmY($key->d_cutting); ?>" type="text" class="form-control tgl input-sm" name="d_cutting<?= $i; ?>" id="d_cutting<?= $i; ?>" />
                                </td>
                                <td class="col-2"><input value="<?= $key->e_remark; ?>" type="text" class="form-control input-sm" name="e_remark_item<?= $i; ?>" id="e_remark_item<?= $i; ?>" placeholder="Isi keterangan jika ada!" /></td>
                                <!-- <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td> -->
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    $(document).ready(function() {

        popover();

        fixedtable($('#tabel'));
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);

        showCalendar2('.tgl', 0, 999);

        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        $("#dfrom, #dto").change(function(event) {
            clear_table();
        });

        for (let i = 1; i <= $('#jml').val(); i++) {
            $(`#id_pic_cutting${i}`).select2({
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_pic_cutting/'); ?>',
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
                            results: data,
                        };
                    },
                    cache: true
                }
            });

            $(`#id_pic_gelar${i}`).select2({
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_pic_gelar/'); ?>',
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
                            results: data,
                        };
                    },
                    cache: true
                }
            });
        }

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
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            /* if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else { */
            swal({
                title: "Update Data Ini?",
                text: "Anda Dapat Membatalkannya Nanti",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonColor: 'LightSeaGreen',
                confirmButtonText: "Ya, Update!",
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    type: "POST",
                    data: $("form").serialize(),
                    url: '<?= base_url($folder . '/cform/update_realisasi/'); ?>',
                    dataType: "json",
                    success: function(data) {
                        if (data.sukses == true) {
                            swal("Sukses!", "No Dokumen : " + data.kode +
                                ", Berhasil Diupdate :)", "success");
                            $("input").attr("disabled", true);
                            $("select").attr("disabled", true);
                            $("#submit").attr("disabled", true);
                            $("#addrow").attr("disabled", true);
                            $("#send").attr("disabled", false);
                        } else if (data.sukses == 'ada') {
                            swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                        } else {
                            swal("Maaf :(", "No Dokumen : " + data.kode +
                                ", Gagal Diupdate :(", "error");
                        }
                    },
                    error: function() {
                        swal("Maaf", "Data Gagal Diupdate :(", "error");
                    }
                });
            });
            // }
        }
        return false;
    });

    function hetangproductreal(val, i) {
        let gelar = parseFloat(val);
        let set = parseInt($(`#v_set${i}`).val());
        let resTotal = Math.floor(gelar * set + 0.01 );
        if(gelar) {
            $(`#n_realisasi_product${i}`).val(resTotal);
        } else {
            $(`#n_realisasi_product${i}`).val(0);
        }
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
                $('#n_stock' + z).val(data['detail'][0]['n_stock']);
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
        var stock = parseFloat($('#n_stock' + i).val());
        if (qty > stock) {
            swal("Maaf :(", "Jumlah = " + qty + ", tidak boleh lebih dari stock = " + stock + "", "error");
            $('#n_quantity' + i).val(0);
        }
    }
</script>