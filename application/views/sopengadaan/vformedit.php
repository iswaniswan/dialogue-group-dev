<style type="text/css">
    .tableFixHead {
        white-space: nowrap !important;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lg mr-2 fa-pencil"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control input-sm" value="<?= $datahead->i_bagian; ?>" readonly>
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $datahead->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?= $datahead->id; ?>" readonly="">
                            <input type="text" name="idocument" id="idocument" class="form-control input-sm" value="<?= $datahead->i_document; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $datahead->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea name="eremarkh" id="eremarkh" class="form-control input-sm"><?= $datahead->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($datahead->i_status == '1' || $datahead->i_status == '3' || $datahead->i_status == '7') { ?>
                            <div class="col-sm-3">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-lg mr-2 fa-save"></i>Update</button>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="addrow" class="btn btn-info btn-block btn-sm"><i class="fa fa-lg mr-2 fa-plus"></i>Tambah</button>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-lg mr-2 fa-paper-plane-o"></i>Send</button>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-lg mr-2 fa-trash"></i>Delete</button>
                            </div>

                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-lg mr-2 fa-arrow-circle-left"></i>Kembali</button>
                            </div>
                        <?php } elseif ($datahead->i_status == '2') { ?>

                            <div class="col-sm-6">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-lg mr-2 fa-refresh"></i>Cancel</button>
                            </div>

                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-lg mr-2 fa-arrow-circle-left"></i>Kembali</button>
                            </div>
                        <?php } ?>
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
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatay" class="table color-table tableFixHead success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr class="d-flex">
                        <th class="text-center col-1" width="3%;">No</th>
                        <th class="col-5">Nama Barang</th>
                        <th class="col-2">Warna</th>
                        <th class="text-right col-1" width="3%;">Jumlah SO</th>
                        <th class="col-2">Keterangan</th>
                        <th class="col-1 text-center">Action</th>
                    </tr>
                    <tr class="d-flex">
                        <th class="text-center col-8">Total</th>
                        <th class="text-right col-1" width="3%;" id="total"><?php $total = 0;
                                                                            foreach ($datadetail as $rowtotal) {
                                                                                $total += (float) $rowtotal['n_quantity'];
                                                                            }
                                                                            echo $total; ?></th>
                        <th class="col-3">&nbsp; &nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    foreach ($datadetail as $key) {
                        $i++; ?>
                        <tr class="d-flex">
                            <td class="text-center col-1">
                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                            </td>
                            <td class="col-5"><select data-nourut="<?= $i; ?>" id="idmaterial<?= $i; ?>" class="form-control input-sm" name="idmaterial<?= $i; ?>">
                                    <option value="<?= $key['id'] . '|' . $key['e_color_name']; ?>" selected><?= $key['i_product_wip'] . ' - ' . $key['e_product_wipname'] . ' (' . $key['e_color_name'] . ')'; ?></option>
                                </select></td>
                            <td class="col-2"><input type="text" id="e_satuan<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="e_satuan<?= $i; ?>" readonly value="<?= $key['e_color_name']; ?>"></td>
                            <td class="text-center col-1"><input type="text" data-qty="<?= $i; ?>" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i; ?>" onblur="if(this.value==''){this.value=0;}" onfocus="if(this.value=='0'){this.value=0;}" value="<?= $key["n_quantity"]; ?>" onkeyup="angkahungkul(this); sumo();"></td>
                            <td class="col-2"><input type="text" class="form-control input-sm" name="eremark<?= $i; ?>" id="eremark<?= $i; ?>" placeholder="Isi keterangan jika ada!" value="<?= $key["e_remark"]; ?>" /></td>
                            <td class="col-1 text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                        </tr>
                    <?php } ?>
                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function() {
        fixedtable($('.table'));
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
    });

    $("#submit").click(function(event) {
        ada = false;
        if ($('#jml').val() == 0) {
            swal('Isi item minimal 1!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
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
    })

    /**
     * After Submit
     */

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        i++;
        $("#jml").val(i);
        var no = $('#tabledatay tr').length;
        var newRow = $("<tr class='d-flex'>");
        var cols = "";
        cols += `<td class="text-center col-1"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td class="col-5"><select data-nourut="${i}" id="idmaterial${i}" class="form-control input-sm" name="idmaterial${i}" ></select></td>`;
        cols += `<td class="col-2"><input type="text" id="e_satuan${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="e_satuan${i}" readonly></td>`;
        cols += `<td class="text-center col-1"><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); sumo();"></td>`;
        cols += `<td class="col-2"><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="col-1"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td></tr>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $('#idmaterial' + i).select2({
            placeholder: 'Cari Kode / Nama Barang WIP',
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
                        swal("Kode barang yang dipilih sudah ada !!!!!");
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

    function sumo() {
        // var qty = $("#nquantity"+id).val();

        var jml = $("#jml").val();
        let sumqty = 0;
        var qty = 0;
        for (n = 1; n <= jml; n++) {
            if (isFinite($("#nquantity" + n).val()) && $("#nquantity" + n).val() !== '') {
                qty = parseFloat($("#nquantity" + n).val());
            } else {
                qty = 0;
            }
            sumqty += qty
        }
        $("#total").html(sumqty);
    }

    /**
     * Hapus Detail Item
     */

    $("#tabledatay").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        sumo();

        $('#jml').val(i);
        var obj = $('#tabledatay tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });
</script>