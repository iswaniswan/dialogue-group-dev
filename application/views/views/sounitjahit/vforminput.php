<style type="text/css">
    .tableFixHead {
        white-space: nowrap !important;
    }
</style>

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
                    <div class="form-group row">
                        <label class="col-md-3">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-5">Keterangan</label>

                        <div class="col-sm-3">
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian; ?>" readonly>
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $bagian->e_bagian_name; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" name="idocument" id="i_so" class="form-control input-sm" value="<?= $idocument; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $ddocument; ?>" readonly>
                        </div>

                        <div class="col-sm-5">
                            <textarea name="eremarkh" id="eremarkh" class="form-control" placeholder="Isi keterangan jika ada .."></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-block btn-success btn-sm"><i class="fa fa-save mr-2 fa-lg"></i>Simpan</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="addrow" class="btn btn-block btn-info btn-sm"><i class="fa fa-plus mr-2 fa-lg"></i>Tambah</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" disabled="true" id="send" onclick="changestatus('<?= $folder; ?>',$('#kode').val(),'2');" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2 fa-lg"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatay" class="table color-table tableFixHead success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr class="d-flex">
                        <th class="text-center col-1" width="3%;">No</th>
                        <th class="col-2">Nama Perusahaan</th>
                        <th class="col-4">Nama Barang</th>
                        <th class="col-2">Warna</th>
                        <th class="text-right col-1" width="3%;">Jml SO Bagus</th>
                        <th class="text-right col-1" width="3%;">Jml SO Repair</th>
                        <th class="col-2">Keterangan</th>
                        <th class="col-1 text-center">Action</th>
                    </tr>
                    <tr class="d-flex">
                        <th class="text-center col-9">Total</th>
                        <th class="text-right col-1" width="3%;" id="totalbagus"><?= 0; ?></th>
                        <th class="text-right col-1" width="3%;" id="totalrepair"><?= 0; ?></th>
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
                            <td class="col-2">
                                <input readonly type="text" id="e_company_name<?= $i; ?>" class="form-control input-sm inputitem" autocomplete="off" name="e_company_name<?= $i; ?>" value="<?= $key['e_company_name'] ?>">
                            </td>
                            <td class="col-4">
                                <select data-nourut="<?= $i; ?>" id="idproduct<?= $i; ?>" class="form-control select2 input-sm" name="idproduct<?= $i; ?>">
                                    <option value="<?= $key['id'] . '|' . $key['e_color_name'] ?>"><?= $key["i_product_wip"] . ' - ' . $key["e_product_wipname"] . ' (' . $key["e_color_name"] . ')'; ?></option>
                                </select>
                            </td>
                            <td class="col-2">
                                <input readonly type="hidden" id="idcolor<?= $i; ?>" class="form-control input-sm inputitem" autocomplete="off" name="idcolor<?= $i; ?>" value="<?= $key['id_color'] ?>">
                                <input readonly type="text" id="e_color<?= $i; ?>" class="form-control input-sm inputitem" autocomplete="off" name="e_color<?= $i; ?>" value="<?= $key['e_color_name'] ?>">
                            </td>
                            <td class="text-center col-1"><input type="text" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key["qty_bagus"]; ?>" onkeyup="angkahungkul(this); sumo(<?= $i; ?>);"></td>
                            <td class="text-center col-1"><input type="text" id="nquantityrepair<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantityrepair<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key["qty_repair"]; ?>" onkeyup="angkahungkul(this); sumo(<?= $i; ?>);"></td>
                            <td class="col-2"><input type="text" class="form-control input-sm" name="eremark<?= $i; ?>" id="eremark<?= $i; ?>" value="<?= $key["e_remark"]; ?>" placeholder="Isi keterangan jika ada!" /></td>
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
        sumo();
        
        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idproduct' + i).select2({
                placeholder: 'Cari Kode / Nama Barang',
                minimumInputLength: 3,
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
                        if (($(this).val() == $('#idproduct' + x).val()) && (z != x)) {
                            swal("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        } else {
                            $('#idcolor' + z).val(kode[2]);
                            $('#e_color' + z).val(kode[1]);
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

        $("#send").click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
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
        $("#send").attr("disabled", false);
    });

    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        i++;
        $("#jml").val(i);
        var no = $('#tabledatay tr').length;
        var newRow = $("<tr class='d-flex'>");
        var cols = "";
        cols += `<td class="text-center col-1"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td class="col-2">
            <input readonly type="text" id="e_company_name${i}" class="form-control input-sm inputitem" autocomplete="off" name="e_company_name${i}" value="">
        </td>`;
        cols += `<td class="col-4"><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td>`;
        cols += `<td class="col-2"><input readonly type="hidden" id="idcolor${i}" class="form-control input-sm inputitem" autocomplete="off" name="idcolor${i}"><input type="text" id="e_color${i}" class="form-control input-sm" autocomplete="off" name="e_color${i}" readonly></td>`;
        cols += `<td class="text-center col-1"><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); sumo(${i});"></td>`;
        cols += `<td class="text-center col-1"><input type="text" id="nquantityrepair${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantityrepair${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); sumo(${i});"></td>`;
        cols += `<td class="col-2"><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="col-1 text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $('#idproduct' + i).select2({
            placeholder: 'Cari Kode / Nama Barang',
            minimumInputLength: 3,
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/barangall/'); ?>',
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
                    if (($(this).val() == $('#idproduct' + x).val()) && (z != x)) {
                        swal("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    } else {
                        $('#idcolor' + z).val(kode[2]);
                        $('#e_color' + z).val(kode[1]);
                        $('#e_company_name' + z).val(kode[3])
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

    function sumo(id) {
        // var qty = $("#nquantity"+id).val();
        var jml = $("#jml").val();
        let sumqty = 0;
        let sumqtyrepair = 0;
        var qty = 0;
        var qtyrepair = 0;
        for (n = 1; n <= jml; n++) {
            if ((isFinite($("#nquantity" + n).val()) && isFinite($("#nquantityrepair" + n).val())) && ($("#nquantity" + n).val() !== '' && $("#nquantityrepair" + n).val() !== '')) {
                qty = parseFloat($("#nquantity" + n).val());
                qtyrepair = parseFloat($("#nquantityrepair" + n).val());
            } else {
                qty = 0;
                qtyrepair = 0;
            }
            sumqty += qty;
            sumqtyrepair += qtyrepair;
        }
        $("#totalbagus").html(sumqty);
        $("#totalrepair").html(sumqtyrepair);
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