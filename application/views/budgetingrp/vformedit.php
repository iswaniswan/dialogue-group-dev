<!-- <style>
    table {
        border-collapse: collapse !important;
    }

    body .select2-container {
        z-index: 9999 !important;
    }

    thead {
        position: sticky !important;
        top: 0 !important;
        border-bottom: 2px solid #ccc !important;
    }

    tfoot {
        position: sticky !important;
        bottom: 0 !important;
        border-top: 2px solid #ccc !important;
    }
</style> -->
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
                </div>
                <!--  <div class="panel-body table-responsive"> -->
                <div id="pesan"></div>
                <div class="white-box" id="detail">
                    <div class="col-sm-6">
                        <h3 class="box-title m-b-0">Detail Barang Budgeting</h3>
                        <div class="m-b-0">
                            <div class="form-group row">
                                <label class="col-md-12">Data Detail Material Berdasarkan Budgeting</label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="i_budgeting" id="i_budgeting">
                                        <option value="<?= $data->id; ?>"><?= $data->i_document . ' [ ' . $data->periode . ' ]'; ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <?php if ($data->i_status_rp == '1' || $data->i_status_rp == '3' || $data->i_status_rp == '7') { ?>
                                <div class="col-sm-3">
                                    <button type="submit" id="submit" class="btn btn-block btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                </div>
                            <?php } ?>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-block btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            </div>
                            <?php if ($data->i_status_rp == '1' || $data->i_status_rp == '3') { ?>
                                <div class="col-sm-3">
                                    <button type="button" id="send" class="btn btn-block btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="hapus" class="btn btn-block btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                                </div>
                            <?php } elseif ($data->i_status_rp == '2') { ?>
                                <div class="col-sm-3">
                                    <button type="button" id="cancel" class="btn btn-block btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabledatax" class="table color-table success-table table-bordered class" width="100%">
                                <thead>
                                   <tr class="d-flex">
                                        <th class="col-5" colspan="3"></th>
                                        <th class="text-right col-10" colspan="7">Grand Total</th>
                                        <th class="text-left col-3" colspan="3"><span id="grandtotal"></span></th>
                                    </tr>
                                    <tr class="d-flex">
                                        <th class="text-center col-1">No</th>
                                        <th class="text-center col-1">Kode</th>
                                        <th class="text-center col-3">Barang</th>
                                        <th class="text-center col-1">Satuan <br> Pembelian</th>
                                        <!-- <th class="text-center">Sisa</th> -->
                                        <th class="text-center col-1">Jml Kebutuhan <br> Real</th>
                                        <th class="text-center col-4">Supplier</th>
                                        <th class="text-center col-1">Jenis Harga</th>
                                        <th class="text-center col-1">Min Order</th>
                                        <th class="text-center col-1">Jml <br>Adjusment</th>
                                        <th class="text-center col-1">Harga <br>Supplier</th>
                                        <!-- <th class="text-center">Harga Adj</th> -->
                                        <th class="text-center col-1">Sub Total</th>
                                        <th class="text-center col-2">Keterangan</th>
                                        <!-- <th class="text-center">Act</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="jml" id="jml" value="0">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </from>
    <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js"></script>
    <script>
        function grandtotal() {
            var jml = $('#jml').val();
            var total = 0;
            for (var i = 0; i < jml; i++) {
                var x = parseFloat($('#sub_total' + i).val().replaceAll(",", ""));
                total = total + x;
            }
            $('#grandtotal').text(number_format(total, 3, '.', ','));
        }

        function sub_total(i) {
            var nquantity = parseFloat($('#nquantity' + i).val().replaceAll(",", ""));
            var harga_sup = parseFloat($('#harga_sup' + i).val().replaceAll(",", ""));

            var sub_total = nquantity * harga_sup;
            $('#sub_total' + i).val(formatcemua(Math.ceil(sub_total)));
            grandtotal();
        }

        $(document).ready(function() {
            var $table = $('.table');
            $table.floatThead({
                responsiveContainer: function($table) {
                    return $table.closest('.table-responsive');
                }
            });
            //$('#ipp').mask('SS-0000-000000S');
            $('.select2').select2();
            //showCalendar('.date');

            $("#tabledatax tbody").remove();
            $.ajax({
                type: "post",
                data: {
                    'i_budgeting': $('#i_budgeting').val(),
                },
                url: '<?= base_url($folder . '/cform/getmaterialbudget_edit'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data.length > 0) {
                        $('#jml').val(data.length);
                        for (let i = 0; i < data.length; i++) {
                            var no = $('#tabledatax tbody tr').length + 1;
                            var newRow = $("<tr class='d-flex'>");
                            var cols = "";
                            cols += '<td class="col-1" style="text-align: center;"><spanx id="snum' + i + '">' + no + '</spanx></td>';
                            cols += '<td class="col-1"><input type="text" id="i_material' + i + '" class="form-control input-sm" name="imaterial[]" readonly value="' + data[i]['i_material'] + '"></td>';
                            cols += '<td class="col-3"><input type="text" id="e_material_name' + i + '" class="form-control input-sm" name="e_material_name[]" readonly value="' + data[i]['e_material_name'] + '"></td>';
                            cols += '<td class="col-1"><input type="hidden" id="isatuan' + i + '" name="isatuan[]" value="' + data[i]['i_satuan_code'] + '"><input type="text" readonly id="esatuan' + i + '" class="form-control input-sm" name="esatuan[]" value="' + data[i]['e_satuan_name'] + '"></td>';
                            cols += '<td class="col-1"><input type="text" readonly id="nquantity_old' + i + '" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity_old[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data[i]['n_quantity_old'] + '" onkeyup="angkahungkul(this);"></td>';
                                cols += '<td class="col-4"><select data-urut="' + i + '" id="isupplier' + i + '" class="form-control input-sm" name="isupplier[]"><option value="' + data[i]['id_supplier'] + '">' + data[i]['kode_supplier'] + ' - ' + data[i]['nama_supplier'] + '</option></option></select></td>';
                                // cols += '<td><input type="text" id="nsisa'+i+'" class="form-control text-right input-sm" name="nsisa[]" readonly value="0"></td>';
                               

                                cols += '<td class="col-1"><input type="hidden" id="f_ppn' + i + '" name="f_ppn[]" value="' + data[i]['f_ppn'] + '"><input type="hidden" id="n_ppn' + i + '" name="n_ppn[]" value="' + data[i]['n_ppn'] + '"><input type="text" readonly id="e_ppn' + i + '" class="form-control input-sm" name="e_ppn[]" value="' + data[i]['inex'] + '"></td>';

                                cols += '<td class="col-1"><input type="text" readonly id="n_min_order' + i + '" class="form-control text-right input-sm" autocomplete="off" name="n_min_order[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data[i]['n_min_order'] + '" onkeyup="angkahungkul(this);"></td>';
                                 
                                cols += '<td class="col-1"><input type="text" readonly id="nquantity' + i + '" class="form-control text-right input-sm" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="' + data[i]['n_adjusment'] + '" onkeyup="angkahungkul(this);"></td>';

                                cols += '<td class="col-1"><input type="text" readonly id="harga_sup' + i + '" class="form-control text-right input-sm inputitem" autocomplete="off" name="harga_sup[]" value="' + data[i]['harga_supplier'] + '"></td>';
                            // cols += '<td><input type="text" id="harga_adj'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="harga_adj[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data[i]['v_price_adj']+'" onkeyup="angkahungkul(this);sub_total('+i+');"></td>';
                            cols += '<td class="col-1"><input type="text" readonly id="sub_total' + i + '" class="form-control text-right input-sm" autocomplete="off" name="sub_total[]" value="' + number_format(data[i]['sub_total_edit'], '3', '.', ',') + '"></td>';
                            cols += '<td class="col-2"><input type="text" id="eremark' + i + '" class="form-control input-sm" name="eremark[]" value="' + data[i]['e_remark'] + '"/><input type="hidden" id="ikode' + i + '" name="ikode[]"/ value="' + data[i]['i_kode_kelompok'] + '" ><input type="hidden" id="id' + i + '" name="id[]"/ value="' + data[i]['id'] + '" ></td>';
                            // cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                            $('#isupplier' + i).select2({
                                placeholder: 'Cari Kode / Nama Supplier',
                                allowClear: true,
                                width: '100%',
                                type: "POST",
                                // theme: "bootstrap4",
                                ajax: {
                                    url: '<?= base_url($folder . '/cform/supplier/'); ?>',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        var query = {
                                            q: params.term,
                                            i_material : $('#i_material' + i).val(),
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
                            }).change(function(event) {
                                let z = $(this).data('urut');
                                $.ajax({
                                    type: "post",
                                    data: {
                                        'i_supplier': $(this).val(),
                                        'i_material': $('#i_material' + z).val(),
                                        'd_document': $('#d_document').val(),
                                    },
                                    url: '<?= base_url($folder . '/cform/getmaterialprice'); ?>',
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.length > 0) {
                                            var n_min_order = data[0]['n_order'];
                                            var nquantity = parseFloat($('#nquantity_old' + z).val().replaceAll(",", ""));

                                            if(n_min_order > nquantity) {
                                                nquantity = n_min_order;
                                            }
                                            $('#harga_sup' + z).val(data[0]['harga_supplier']);
                                            $('#f_ppn' + z).val(data[0]['f_ppn']); 
                                            $('#n_ppn' + z).val(data[0]['n_ppn']); 
                                            $('#e_ppn' + z).val(data[0]['inex']); 
                                            $('#n_min_order' + z).val(n_min_order); 
                                            $('#nquantity' + z).val(nquantity); 
                                        } else {
                                            $('#harga_sup' + z).val(0);
                                        }
                                        sub_total(z);
                                    },
                                    error: function() {
                                        swal('Ada kesalahan :(');
                                    }
                                });
                            });
                        }
                        grandtotal();
                    }
                },
                error: function() {
                    swal('Ada kesalahan :(');
                }
            });
        });


        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#i_budgeting').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#i_budgeting').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#i_budgeting').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        $("form").submit(function(event) {
            event.preventDefault();
            //$("input").attr("disabled", true);
            //$("select").attr("disabled", true);
            //$("#addrow").attr("disabled", true);
            $("#submit").attr("disabled", true);
            $("#send").attr("disabled", false);
        });

        var i = $('#jml').val();
        $("#addrow").on("click", function() {
            i++;
            $("#jml").val(i);
            var no = $('#tabledatax tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td class="text-center"><spanx id="snum' + i + '">' + no + '</spanx></td>';
            cols += '<td><select id="imaterial' + i + '" class="form-control input-sm" name="imaterial[]" onchange="getmaterial(' + i + ');"></td>';
            cols += '<td><input type="hidden" id="isatuan' + i + '" name="isatuan[]"/><input type="text" readonly id="esatuan' + i + '" class="form-control input-sm" name="esatuan[]"></td>';
            cols += '<td><input type="text" id="nquantity' + i + '" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
            cols += '<td><input type="text" id="eremark' + i + '" class="form-control input-sm" name="eremark[]"/><input type="hidden" id="ikode' + i + '" name="ikode[]"/></td>';
            cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
            newRow.append(cols);
            $("#tabledatax").append(newRow);
            $('#imaterial' + i).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/material/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            ikategori: $('#ikategori').val(),
                            ijenis: $('#ijenis').val(),
                            ibagian: $('#ibagian').val(),
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

        $("#tabledatax").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            /*$('#jml').val(i);*/
            del();
        });

        function del() {
            obj = $('#tabledatax tr:visible').find('spanx');
            $.each(obj, function(key, value) {
                id = value.id;
                $('#' + id).html(key + 1);
            });
        }

        $("#submit").click(function(event) {
            ada = false;
            if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                $("#tabledatax tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val() == '' || $(this).val() == null) {
                            swal('Supplier Harus Di isi');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                            swal('Harga Tidak Boleh Kosong Atau 0!');
                            ada = true;
                        }
                    });
                });
                if (!ada) {
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
                                    $('#id').val(data.id);
                                    swal("Sukses!", "No Dokumen : " + data.kode + ", Berhasil Diupdate :)", "success");
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                } else if (data.sukses == 'ada') {
                                    swal("Maaf :(", "No Dokumen : " + data.kode + ", Sudah Ada :(", "error");
                                } else {
                                    swal("Maaf :(", "No Dokumen : " + data.kode + ", Gagal Diupdate :(", "error");
                                }
                            },
                            error: function() {
                                swal("Maaf", "Data Gagal Disimpan :(", "error");
                            }
                        });
                    });
                } else {
                    return false;
                }
            }
        })
    </script>