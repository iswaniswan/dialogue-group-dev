<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i><?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Pembuat Dokumen</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Keterangan</label>

                            <div class="col-sm-3">
                                <input type="hidden" name="ibagian" id="ibagian" class="form-control input-sm" value="<?= $datahead->i_bagian;?>" readonly>   
                                <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $datahead->e_bagian_name;?>" readonly>
                            </div>

                            <div class="col-sm-3"> 
                                <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?= $datahead->id;?>" readonly="">
                                <input type="text" name="idocument" id="idocument" class="form-control input-sm" value="<?= $datahead->i_document;?>" readonly>
                            </div>

                            <div class="col-sm-3"> 
                                 <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $datahead->d_document;?>" readonly>   
                            </div>

                            <div class="col-sm-3">
                                <textarea name="eremarkh" id="eremarkh" class="form-control input-sm" placeholder="Isi keterangan jika ada .."><?= $datahead->e_remark;?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <?php if ($datahead->i_status == '1' || $datahead->i_status == '3' || $datahead->i_status == '7') { ?>
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;

                                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                                <?php } ?>
                                <?php if ($datahead->i_status == '1') { ?>
                                    <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                    <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                                <?php } elseif ($datahead->i_status == '2') { ?>
                                    <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                                <?php } ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
            </div>
            <div class="col-sm-6 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
        </div>
        <div class="table-responsive">
            <table id="sitabel" class="table color-table success-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th width="47%">Nama Barang</th>
                        <th width="10%">Satuan</th>
                        <th class="text-right" width="10%">Jumlah SO</th>
                        <th width="20%">Keterangan</th>
                        <th class="text-center" width="3%">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $key) {
                            $i++;
                    ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td>
                                    <select data-nourut="<?= $i; ?>" id="idmaterial<?= $i; ?>" class="form-control select2 input-sm" name="idmaterial<?= $i; ?>">
                                        <option value="<?= $key['id'] . '|' . $key['e_satuan_name'] ?>"><?= $key["i_material"] . ' - ' . $key["e_material_name"] . ' (' . $key["e_satuan_name"] . ')'; ?></option>
                                    </select>
                                </td>
                                <td><input readonly type="text" id="e_satuan<?= $i; ?>" class="form-control input-sm inputitem" autocomplete="off" name="e_satuan<?= $i; ?>" value="<?= $key['e_satuan_name'] ?>"></td>
                                <td><input type="number" min="0" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key["n_quantity"]; ?>" onkeyup="angkahungkul(this);"></td>
                                <td><input type="text" class="form-control input-sm" name="eremark<?= $i; ?>" id="eremark<?= $i; ?>" value="<?= $key["e_remark"]; ?>" placeholder="Isi keterangan jika ada!" /></td>
                                <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                            </tr>
                    <?php
                        }
                    }

                    ?>
                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        fixedtable($('#sitabel'));
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idmaterial' + i).select2({
                placeholder: 'Cari Kode / Nama Bahan Baku',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/barang/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            ibagian: $('#ibagian').val(),
                            ddocument: $('#ddocument').val(),
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
                /**
                 * Cek Barang Sudah Ada
                 * Get Harga Barang
                 */
                var kode = $(this).val().split("|");

                var z = $(this).data('nourut');
                var ada = true;
                for (var x = 1; x <= $('#jml').val(); x++) {
                    if ($(this).val() != null) {
                        if (($(this).val() == $('#idmaterial' + x).val()) && (z != x)) {
                            swal("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        } else {
                            $('#e_satuan' + z).val(kode[1]);
                        }
                    }
                }
                if (!ada) {
                    $(this).val('');
                    $(this).html('');
                } else {
                    $('#nquantity' + z).focus();
                }
            });
        }

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else {
                    $("#sitabel tbody tr").each(function() {
                        $(this).find("td select").each(function() {
                            if ($(this).val() == '' || $(this).val() == null) {
                                swal('Kode barang tidak boleh kosong!');
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
                    } else {
                        return false;
                    }
                }
            }
            return false;
        });
    });

    /* $("#submit").click(function(event) {
        ada = false;
        if ($('#jml').val() == 0) {
            swal('Isi item minimal 1!');
            return false;
        } else {
            $("#sitabel tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
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
    }) */

    /**
     * After Submit
     */

    /* $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    }); */

    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        i++;
        $("#jml").val(i);
        var no = $('#sitabel tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idmaterial${i}" class="form-control input-sm" name="idmaterial${i}" ></select></td>`;
        cols += `<td><input type="text" id="e_satuan${i}" class="form-control input-sm inputitem" autocomplete="off" name="e_satuan${i}" readonly></td>`;
        cols += `<td><input type="number" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#sitabel").append(newRow);
        $("#sitabel tr:first").after(newRow);
        restart();
        $('#idmaterial' + i).select2({
            placeholder: 'Cari Kode / Nama Bahan Baku',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/barang/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#ibagian').val(),
                        ddocument: $('#ddocument').val(),
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
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var kode = $(this).val().split("|");

            var z = $(this).data('nourut');
            var ada = true;
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if (($(this).val() == $('#idmaterial' + x).val()) && (z != x)) {
                        swal("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    } else {
                        $('#e_satuan' + z).val(kode[1]);
                    }
                }
            }
            if (!ada) {
                $(this).val('');
                $(this).html('');
            } else {
                $('#nquantity' + z).focus();
            }
        });
    });

    /**
     * Hapus Detail Item
     */

    $("#sitabel").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        $('#jml').val(i);
        restart();
    });

    function restart() {
        var obj = $('#sitabel tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }
</script>