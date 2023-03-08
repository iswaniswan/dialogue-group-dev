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
                <i class="fa fa-pencil mr-2"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-6">Keterangan</label>

                        <div class="col-sm-2">
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control input-sm" value="<?= $bagian->i_bagian; ?>" readonly>
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $bagian->e_bagian_name; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" name="idocument" id="i_so" class="form-control input-sm" value="<?= $idocument; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $ddocument; ?>" readonly>
                        </div>

                        <div class="col-sm-6">
                            <textarea name="eremarkh" id="eremarkh" placeholder="Keterangan ..." class="form-control input-sm"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm"><i class="fa fa-plus mr-2"></i>Tambah</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" disabled="true" id="send" onclick="changestatus('<?= $folder; ?>',$('#kode').val(),'2');" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <!-- <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div> -->
    <div class="col-sm-12">
        <!-- <div class="table_fixed" style="width: 100%; max-height: 600px;"> -->
        <div class="table-responsive">
            <table id="tabledatay" class="table color-table tableFixHead success-table table-bordered" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th width="25%">Asal</th>
                        <th width="25%">Nama Barang</th>
                        <th width="5%">Warna</th>
                        <th class="text-right" width="5%;">SO (Bagus)</th>
                        <th class="text-right" width="5%;">SO (Repair)</th>
                        <th width="auto">Keterangan</th>
                        <th class="text-center" width="3%" ;>Act</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-center">TOTAL</th>
                        <th class="text-right" width="3%;" id="total" data-id="total"><?= 0; ?></th>
                        <th class="text-right" width="3%;" id="total_repair" data-id="total_repair"><?= 0; ?></th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    foreach ($datadetail as $row) {
                        $i++; ?>
                        <tr>
                            <td class="text-center">
                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                            </td>
                            <td class="text-center">
                                <input type="hidden" class="form-control input-sm" id="id_company<?= $i ?>" value="<?= $row['id_company'] ?>" readonly>
                                <input type="text" class="form-control input-sm" value="<?= $row['company'] ?>" readonly>
                            </td>
                            <td>
                                <select data-nourut="<?= $i; ?>" id="idmaterial<?= $i; ?>" class="form-control input-sm" name="idmaterial<?= $i; ?>">
                                    <option value="<?= $row['id'] . '|' . $row['e_color_name']; ?>" selected><?= $row['i_product_wip'] . ' - ' . $row['e_product_wipname'] . ' (' . $row['e_color_name'] . ')'; ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="text" id="e_color<?= $i; ?>" class="form-control input-sm" autocomplete="off" name="e_color<?= $i; ?>" readonly value="<?= $row['e_color_name']; ?>">
                            </td>
                            <td class="text-center">
                                <input type="number" data-qty="<?= $i; ?>" 
                                        id="nquantity<?= $i; ?>" 
                                        class="form-control text-right input-sm inputitem" 
                                        autocomplete="off" 
                                        name="nquantity<?= $i; ?>" 
                                        value="<?= $row['qty']; ?>" 
                                        onkeyup="sumo(<?= $i; ?>);">
                            </td>
                            <td class="text-center">
                                <input type="number" data-qty-repair="<?= $i; ?>" 
                                        id="nquantity_repair<?= $i; ?>" 
                                        class="form-control text-right input-sm inputitem-repair" 
                                        autocomplete="off" 
                                        name="nquantity_repair<?= $i; ?>" 
                                        value="<?= $row['qty_repair']; ?>" 
                                        onkeyup="sumo_repair(<?= $i; ?>);">
                            </td>
                            <td><input type="text" class="form-control input-sm" name="eremark<?= $i; ?>" id="eremark<?= $i; ?>" placeholder="Isi keterangan jika ada!" value="<?= $row['e_remark']; ?>"/></td>
                            <td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="xml" id="jml" value="<?= $i; ?>">
</form>
<script>
    $(document).ready(function() {
        // fixedtable($('.table'));
        /* $(".table_fixed").freezeTable({
            'columnNum': 3,
            'scrollable': true,
        }); */

        sumo();
        sumo_repair();
        
        var $table = $('#tabledatay');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : data,
                // search: true,
                showColumns: true,
                // showToggle       : true,
                // clickToSelect    : true,
                fixedColumns: true,
                // fixedNumber: 3,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            // buildTable($table)
        })

        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idmaterial' + i).select2({
                placeholder: 'Cari Kode / Nama Barang',
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
        var no = $('#tabledatay tbody tr').length + 1;
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;        
        cols += `<td>
                    <input type="hidden" id="id_company${i}" class="form-control input-sm" value="">
                    <input type="text" id="e_company${i}" class="form-control input-sm" value="" readonly>
                </td>`;
        cols += `<td><select data-nourut="${i}" id="idmaterial${i}" class="form-control input-sm" name="idmaterial${i}" data-index="${i}"></select></td>`;
        cols += `<td><input type="text" id="e_color${i}" class="form-control input-sm inputitem" autocomplete="off" name="e_color${i}" readonly></td>`;
        cols += `<td class="text-center">
                    <input type="number" id="nquantity${i}" 
                            class="form-control text-right input-sm inputitem" autocomplete="off" 
                            name="nquantity${i}" value="0" onkeyup="sumo();">
                </td>`;
        cols += `<td class="text-center">
                    <input type="number" id="nquantity_repair${i}" 
                            class="form-control text-right input-sm inputitem-repair" autocomplete="off" 
                            name="nquantity_repair${i}" value="0" onkeyup="sumo_repair();">
                </td>`;
        cols += `<td>
                    <input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/>
                </td>`;
        cols += `<td class="text-center">
                    <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger text-center"><i class="ti-close"></i></button>
                </td></tr>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $('#idmaterial' + i).select2({
            placeholder: 'Cari Kode / Nama Barang Jadi',
            // allowClear: true,
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

            const index = ($(this).attr('data-index'));
            const id_product =kode[0];
            applyCompany(index, id_product);
        });
    });

    async function applyCompany(index, id_product) {
        const data = await getCompany(id_product);
        $(`#e_company${index}`).val(data?.data?.name)
        $(`#id_company${index}`).val(data?.data?.id_company)
    }



    // function sumo() {
    //     // var qty = $("#nquantity"+id).val();
    //     var jml = $("#jml").val();
    //     var sumqty = 0;
    //     var qty = 0;
    //     for (n = 1; n <= jml; n++) {
    //         // alert($("#nquantity" + n).val());
    //         if (typeof $("#nquantity" + n).val() !== "undefined") {
    //             if ($("#nquantity" + n).val() !== '') {
    //                 qty = parseFloat($("#nquantity" + n).val());
    //             } else {
    //                 qty = 0;
    //             }
    //         } else {
    //             qty = 0;
    //         }
    //         sumqty += qty
    //     }
    //     $("#total").html(sumqty);
    // }

    function sumo(){
        
        let total = 0;

        let allQuantity = $('html .inputitem');

        allQuantity.each(function() {
            let value = $(this).val();

            if (isNaN(value) || value == '' || value === undefined) {
                value = 0;
            }

            total += parseFloat(value);
        })
        
        console.log(total);

        $('#total').text(total);
    }

    function sumo_repair(){
        
        let total = 0;

        let allQuantity = $('html .inputitem-repair');

        allQuantity.each(function() {
            let value = $(this).val();

            if (isNaN(value) || value == '' || value === undefined) {
                value = 0;
            }

            total += parseFloat(value);
        })
        
        console.log(total);

        $('#total_repair').text(total);
    }
    
    /**
     * Hapus Detail Item
     */

    $("#tabledatay").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        $('#jml').val(i);
        var obj = $('#tabledatay tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
        sumo();
    });

    async function getCompany(idProduct) {
        return $.ajax({
            url: '<?= base_url($folder . '/cform/get_company_by_product?id_product='); ?>' + idProduct,
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                // console.log(result);
                return result;
            }
        })
    }

</script>
