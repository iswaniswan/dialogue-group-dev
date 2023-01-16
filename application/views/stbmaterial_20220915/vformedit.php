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
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Bagian Penerima</label>
                            <div class="col-sm-3">
                                <select name="i_bagian" id="i_bagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="i_document" required="" id="i_stb_sj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>">
                                    <input type="hidden" id="id" name="id" value="<?= $data->id; ?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= $data->date_document; ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="i_bagian_receive" required="" id="i_bagian_receive" class="form-control select2">
                                    <?php if ($bagian_receive) {
                                        foreach ($bagian_receive->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian_receive) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <div class="col-sm-3">
                                    <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2 fa-lg"></i>Update</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2 fa-lg"></i>Send</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash mr-2 fa-lg"></i>Delete</button>
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
        <div class="form-group row">
            <div class="col-sm-2">
                <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
            </div>
            <div class="col-sm-2">
                <h3 class="box-title m-b-0 ml-1 text-right">Tanggal Schedule Jahit</h3>
            </div>
            <div class="col-sm-2">
                <input type="text" id="dfrom" name="dfrom" class="form-control input-sm date" readonly value="<?= date("d-m-Y"); ?>">
            </div>
            <div class="col-sm-2">
                <input type="text" id="dto" name="dto" class="form-control input-sm date" readonly value="<?= date("d-m-Y"); ?>">
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-info btn-block btn-sm mr-2" id="addrow"> <i class="fa fa-plus fa-lg mr-2"></i>Item</button>
            </div>
            <!-- <div class="col-sm-1"></div> -->
            <div class="col-sm-2 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
        </div>
        <div class="table-responsive">
            <table id="table" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="50%;">Material</th>
                        <th class="text-center" width="17%;">Qty</th>
                        <th class="text-center" width="25%;">Keterangan</th>
                        <th class="text-center" width="3%;">Act</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $key) {
                            $i++; ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><b><?= $i; ?></b></spanx>
                                </td>
                                <td>
                                    <select data-nourut="<?= $i; ?>" id="id_material<?= $i; ?>" class="form-control input-sm" name="id_material<?= $i; ?>" onchange="get_stock(<?= $i; ?>);">
                                        <option value="<?= $key->id_material; ?>"><?= '[' . $key->i_material . '] - ' . $key->e_material_name . ' - ' . $key->e_satuan_name; ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input value="<?= $key->n_quantity; ?>" type="number" id="n_quantity<?= $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_quantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="hetang(<?= $i; ?>);">
                                    <input value="<?= $key->n_stock; ?>" type="hidden" id="n_stock<?= $i; ?>" name="n_stock<?= $i; ?>">
                                </td>
                                <td><input value="<?= $key->e_remark; ?>" type="text" class="form-control input-sm" name="e_remark_item<?= $i; ?>" id="e_remark_item<?= $i; ?>" placeholder="Isi keterangan jika ada!" /></td>
                                <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>
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
        fixedtable($('#tabel'));
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);

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
                    url: '<?= base_url($folder . '/cform/update/'); ?>',
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