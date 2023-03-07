<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
                        <label class="col-md-2">Jenis Barang</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="ibonkold" id="ibonkold" value="<?= $data->i_keluar_jahit; ?>">
                                <input type="text" name="ibonk" id="ibonk" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_keluar_jahit; ?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br> -->
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<?= $data->d_keluar_jahit; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->id_company . '|' . $row->i_bagian; ?>" <?php if ($row->i_bagian . $row->id_company == $data->i_tujuan . $data->id_company_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="ijenisbarang" id="ijenisbarang" class="form-control select2" onchange="check_stock();">
                                <?php if ($jenis) {
                                    foreach ($jenis as $row) : ?>
                                        <option value="<?= $row->id; ?>" <?php if ($row->id == $data->id_jenis_barang_keluar) { ?> selected <?php } ?>>
                                            <?= $row->e_jenis_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();">
                                <i class="fa fa-save mr-2"></i>Update
                            </button>
                        <?php } ?>

                        <?php if ($data->i_status == '2') { ?>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2" hidden="true">
                                <i class="fa fa-plus mr-2"></i>Item
                            </button>
                        <?php } else { ?>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2">
                                <i class="fa fa-plus mr-2"></i>Item
                            </button>
                        <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        <?php if ($data->i_status == '1') { ?>
                            <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm mr-2">
                                <i class="fa fa-paper-plane-o mr-2"></i>Send
                            </button>
                            <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm mr-2">
                                <i class="fa fa-trash mr-2"></i>Delete
                            </button>
                        <?php } elseif ($data->i_status == '2') { ?>
                            <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm mr-2">
                                <i class="fa fa-refresh mr-2"></i>Cancel
                            </button>
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
        <!-- <div class="m-b-0">
            <div class="form-group row">
                <div class="col-sm-1">
                </div>
            </div>
        </div> -->
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">Kode Barang</th>
                        <th class="text-center" style="width: 27%;">Nama Barang Jadi</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-right" style="width: 10%;">Stock Jahit</th>
                        <th class="text-right" style="width: 10%;">Qty Kirim</th>
                        <th class="text-center" style="width: 30%;">Keterangan</th>
                        <th class="text-center" style="width: 5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 0; foreach ($detail as $row) { $i++; ?>
                    <tr>
                        <td class="text-center">
                            <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                        </td>
                        <td>
                            <input type="hidden" value="<?= $row->id_product; ?>" id="idproduct<?= $i; ?>" name="idproduct[]">
                            <input type="text" value="<?= $row->i_product_base; ?>" readonly id="iproduct<?= $i; ?>" name="iproduct[]" class="form-control input-sm">
                        </td>
                        <td>
                            <select id="eproduct<?= $i; ?>" class="form-control select2" name="eproduct[]" onchange="getproduct(<?= $i; ?>);">
                                <option value="<?= $row->id_product; ?>"><?= $row->e_product_basename; ?></option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" value="<?= $row->id_color; ?>" id="idcolorproduct<?= $i; ?>" name="idcolorproduct[]">
                            <input type="text" value="<?= $row->e_color_name; ?>" readonly id="ecolorproduct<?= $i; ?>" name="ecolorproduct[]" class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text" readonly id="stok<?= $i; ?>" class="form-control text-right input-sm" name="stok<?= $i; ?>" value=<?= $row->saldo_akhir; ?>>
                        </td>
                        <td>
                            <input type="text" value="<?= $row->n_quantity_product; ?>" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onkeydown="nexttab(this, event,'inputitem')" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);validasi(<?= $i; ?>)">
                        </td>
                        <td>
                            <input type="text" id="edesc<?= $i; ?>" class="form-control input-sm" value="<?= $row->e_remark; ?>" name="edesc[]">
                        </td>
                        <td class="text-center">
                            <button type="button" title="Delete" data-i="<?= $i; ?>" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    let tampungProd = [];
    $(document).ready(function() {
        for(let i = 1; i<=$(`#jml`).val(); i++) {
            tampungProd.push(parseInt($(`#eproduct${i}`).val()));
        }
        // $('#ibonk').mask('SS-0000-000000S');
        number();
        $('.select2').select2({
            width: '100%',
        });
        showCalendar('.date', null, 0);
        // $('#ibagian').select2({
        //     placeholder: 'Pilih Bagian',
        //     width: '100%',
        //     allowClear: true,
        //     ajax: {
        //         url: '<?= base_url($folder . '/cform/bagian'); ?>',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function(params) {
        //             var query = {
        //                 q: params.term,
        //                 ibagian: $('#xbagian').val(),
        //             }
        //             return query;
        //         },
        //         processResults: function(data) {
        //             return {
        //                 results: data,
        //             };
        //         },
        //         cache: false
        //     }
        // });

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
                    if (data == 1 && ($('#ibonk').val() != $('#ibonkold').val())) {
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

        $('#ceklis').click(function(event) {
            if ($('#ceklis').is(':checked')) {
                $("#ibonk").attr("readonly", false);
            } else {
                $("#ibonk").attr("readonly", true);
                $("#ada").attr("hidden", true);
                $("#ibonk").val($("#ibonkold").val());
                /*number();*/
            }
        });

        $('#ibagian, #dbonk').change(function(event) {
            number();
            check_stock();
        });

        $('#itujuan').change(function(event) {
            number();
            clear_table('tabledatax');
            $(`#jml`).val(0);
        });

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        $("form").submit(function(event) {
            event.preventDefault();
            $("input").attr("disabled", true);
            $("select").attr("disabled", true);
            $("#addrow").attr("disabled", true);
            $("#submit").attr("disabled", true);
            $("#send").attr("disabled", false);
        });
        let counter = 0;
        $("#addrow").on("click", function() {
            var counter = $('#jml').val();
            var counterx = counter-1;
            counter++;
            counterx++;
            $("#tabledatax").attr("hidden", false);
            var iproduct = $('#idproduct'+counterx).val();
            count = $('#tabledatax tbody tr').length;
                if ((iproduct==''||iproduct==null) && iproduct != undefined &&(count>1)) {
                    swal('Isi dulu yang masih kosong!!');
                    counter = counter-1;
                    counterx = counterx-1;
                    return false;
                }
            $('#jml').val(counter);
            var newRow = $("<tr>");
            var cols = "";

            cols += '<td class="text-center"><spanx id="snum' + counter + '">' + (count+1) + '</spanx></td>';
            cols += '<td><input type="hidden" readonly id="idproduct' + counter + '" class="form-control input-sm" name="idproduct[]"><input type="text" readonly id="iproduct' + counter + '" class="form-control input-sm" name="iproduct' + counter + '"></td>';
            cols += '<td><select type="text" data-product="' + counter + '" id="eproduct' + counter + '" class="form-control" name="eproduct' + counter + '" onchange="getstok('+ counter +');appendproduct(' + counter + ');"></select><input type="hidden" id="stok' + counter + '" name="stok' + counter + '"></td>';
            // cols += '<td><select type="text" id="eproduct' + counter + '" class="form-control" name="eproduct' + counter + '" onchange="getproduct(' + counter + '); "></select><input type="hidden" id="stok'+ counter +'" name="stok'+ counter +'"></td>';
            cols += '<td><input type="hidden" id="idcolorproduct' + counter + '" class="form-control" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct' + counter + '" class="form-control input-sm" name="ecolorproduct' + counter + '"></td>';
            /* cols += `<td><input type="text" id="nquantity${counter}" class="form-control input-sm text-right inputitem" name="nquantity[]" value="0" onkeypress="return hanyaAngka(event);" onblur=\"if(this.value==''){this.value='';}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`; */
            cols += `<td>
            <input type="text" readonly class="form-control input-sm text-right inputitem" id="nstock${counter}" value="0" onkeypress="return hanyaAngka(event);" name="nstock[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\">
            </td>`;
            cols += `<td>
                <input type="text" autocomplete="off" data-qty="${counter}" class="form-control input-sm text-right inputitem" id="nquantity${counter}" value="0" onkeypress="return hanyaAngka(event);" onkeyup="validasi(${counter})" name="nquantity[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\" >
                <input type="hidden" id="nstock${counter}" value="0" onkeypress="return hanyaAngka(event);" name="nstock[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\">
                </td>`;
            cols += '<td><input type="text" id="edesc' + counter + '" class="form-control input-sm" name="edesc[]"></td>';
            cols += '<td class="text-center"><button type="button" data-i="' + counter + '" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

            newRow.append(cols);
            if(count > 0) {
                $("#tabledatax tbody tr:last").after(newRow);
            } else {
                $("#tabledatax tbody").append(newRow);
            }

            $('#eproduct' + counter).select2({
                placeholder: 'Cari Berdasarkan Nama / Kode',
                templateSelection: formatSelection,
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder . '/cform/dataproduct'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            'itujuan': $('#itujuan').val()
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
                ada = false;
                var id = $(this).data('product');
                //alert(id);
                var a = $('#eproduct' + id).val();
                var x = $('#jml').val();
                for (i = 1; i <= x; i++) {
                    if ((a == $('#eproduct' + i).val()) && (i != id)) {
                        swal("kode Barang : " + a + " sudah ada !!!!!");
                        ada = true;
                        break;
                    } else {
                        ada = false;
                    }
                }

                if (!ada) {
                    var eproduct = $('#eproduct' + id).val();
                    $.ajax({
                        type: "post",
                        data: {
                            'eproduct': eproduct,
                            'itujuan': $('#itujuan').val(),
                        },
                        url: '<?= base_url($folder . '/cform/getproduct'); ?>',
                        dataType: "json",
                        success: function(data) {
                            $('#idproduct' + id).val(data[0].id_product);
                            $('#iproduct' + id).val(data[0].i_product_base);
                            $('#idcolorproduct' + id).val(data[0].id_color);
                            $('#ecolorproduct' + id).val(data[0].e_color_name);
                            $('#nquantity' + id).focus();
                            getstok(id);
                        },
                        error: function() {
                            swal('Error :)');
                        }
                    });
                } else {
                    $('#idproduct' + id).html('');
                    $('#iproduct' + id).html('');
                    $('#eproduct' + id).html('');
                    $('#idcolorproduct' + id).html('');
                    $('#ecolorproduct' + id).html('');
                    $('#idproduct' + id).val('');
                    $('#iproduct' + id).val('');
                    $('#eproduct' + id).val('');
                    $('#idcolorproduct' + id).val('');
                    $('#ecolorproduct' + id).val('');
                }

                if(!tampungProd.includes(parseInt($(this).val()))) {
                    tampungProd.push(parseInt($(this).val()));
                }
            });

            $("#nquantity" + counter).keyup(function() {
                var id = $(this).data('qty');
                validasi(id);
            });
        });

        $("#tabledatax").on("click", ".ibtnDel", function(event) {
            let dataI = $(this).attr('data-i');
            let currProduct = parseInt($(`#eproduct${dataI}`).val());
            let index = tampungProd.indexOf(currProduct);
            if (index > -1) { // only splice array when item is found
                tampungProd.splice(index, 1); // 2nd parameter means remove one item only
            }
            $(this).closest("tr").remove();
            // $('#jml').val(counter);
            del();
        });

    });

    function formatSelection(val) {
        return val.name;
    }

    function appendproduct(id) {
        var eproduct = $('#eproduct' + id).text();
        var idproduct = $('#eproduct' + id).val();
        var x = parseInt($('#jml').val());
        if(idproduct != null) {
            $.ajax({
                type: "post",
                data: {
                    'eproduct': eproduct,
                    'idproduct': idproduct,
                    'itujuan': $('#itujuan').val(),
                },
                url: '<?= base_url($folder . '/cform/getproduct3'); ?>',
                dataType: "json",
                success: function(data) {
                    for(let i = 0; i<data.length; i++) {
                        if(!tampungProd.includes(parseInt(data[i].id_product))) {
                            let newRow = `
                            <tr>
                                <td class="text-center"><spanx id="snum${x + (i+1)}">${$('#tabledatax tr').length}</spanx></td>
                                <td>
                                    <input type="hidden" readonly id="idproduct${x + (i+1)}" class="form-control input-sm" value="${data[i].id_product}" name="idproduct[]"><input type="text" readonly id="iproduct${x + (i+1)}" class="form-control input-sm" name="iproduct${x + (i+1)}" value="${data[i].i_product_base}"></td>
                                <td>
                                    <select type="text" id="eproduct${x + (i+1)}" class="form-control" name="eproduct${x + (i+1)}" onchange="getproduct(${x + (i+1)}); getstok(${x + (i+1)});">
                                        <option value="${data[i].id_product}" selected>${data[i].e_product_basename}</option>
                                    </select><input type="hidden" id="stok${x + (i+1)}" name="stok${x + (i+1)}"></td>
                                <td>
                                    <input type="hidden" id="idcolorproduct${x + (i+1)}" value="${data[i].id_color}" class="form-control" name="idcolorproduct[]"><input type="text" value="${data[i].e_color_name}" readonly id="ecolorproduct${x + (i+1)}" class="form-control input-sm" name="ecolorproduct${x + (i+1)}">
                                </td>
                                <td>
                                    <input type="text" readonly class="form-control input-sm text-right" id="nstock${x + (i+1)}" value="0" onkeypress="return hanyaAngka(event);" name="nstock[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\">
                                </td>
                                <td>
                                    <input autocomplete="off" class="form-control input-sm text-right inputitem" id="nquantity${x + (i+1)}" value="0" onkeydown="nexttab(this, event,'inputitem')" onkeypress="return hanyaAngka(event);" onkeyup="validasi(${x + (i+1)})" name="nquantity[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\" >
                                </td>
                                <td>
                                    <input type="text" id="edesc${x + (i+1)}" class="form-control input-sm" name="edesc[]">
                                </td>
                                <td class="text-center">
                                    <button type="button" title="Delete" data-i="${x + (i+1)}" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                </td>
                            </tr>
                            `;
                            $("#tabledatax tbody").append(newRow);
                            // getproduct(x+i+1);
                            getstok(x+i+1);
                            $('#eproduct' + (x+i+1)).select2({
                                width: '100%'
                            });
                            $('#jml').val(x+i+1);
                            if(!tampungProd.includes(parseInt(data[i].id_product))) {
                                tampungProd.push(parseInt(data[i].id_product));
                            }
                        }
                    }
                },
                error: function() {
                    swal('Error :)');
                }
            });
        } else {
            swal('Kode tersebut sudah ada di dalam list :)');
        }
    }

    function getproduct(id) {

        ada = false;
        var a = $('#eproduct' + id).val();
        var x = $('#jml').val();
        for (i = 1; i <= x; i++) {
            if ((a === $('#eproduct' + i).val()) && (i != x)) {
                swal("kode Barang : " + a + " sudah ada !!!!!");
                ada = true;
                break;
            } else {
                ada = false;
            }
        }

        if (!ada) {
            var eproduct = $('#eproduct' + id).val();
            $.ajax({
                type: "post",
                data: {
                    'eproduct': eproduct
                },
                url: '<?= base_url($folder . '/cform/getproduct'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#idproduct' + id).val(data[0].id_product);
                    $('#iproduct' + id).val(data[0].i_product_base);
                    $('#idcolorproduct' + id).val(data[0].id_color);
                    $('#ecolorproduct' + id).val(data[0].e_color_name);
                    $('#nquantity' + id).focus();
                },
                error: function() {
                    swal('Error :)');
                }
            });
        } else {
            $('#idproduct' + id).html('');
            $('#iproduct' + id).html('');
            $('#eproduct' + id).html('');
            $('#idcolorproduct' + id).html('');
            $('#ecolorproduct' + id).html('');
            $('#idproduct' + id).val('');
            $('#iproduct' + id).val('');
            $('#eproduct' + id).val('');
            $('#idcolorproduct' + id).val('');
            $('#ecolorproduct' + id).val('');
        }
    }

    function getstok(id) {
        var idproduct = $('#eproduct' + id).val();
        var ibagian = $('#ibagian').val();
        if(idproduct != null) {
            $.ajax({
                type: "post",
                data: {
                    'idproduct': idproduct,
                    'ibagian': ibagian
                },
                url: '<?= base_url($folder . '/cform/getstok'); ?>',
                dataType: "json",
                success: function(data) {
                    // $('#stok'+id).val(data.saldo_akhir);
                    if ($('#ijenisbarang').val() == '1') {
                        $('#stok' + id).val(data.saldo_akhir);
                        $('#nstock' + id).val(data.saldo_akhir);
                    } else {
                        $('#stok' + id).val(data.saldo_akhir_repair);
                        $('#nstock' + id).val(data.saldo_akhir_repair);
                    }
                    validasi(id);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }
    }

    function check_stock() {
        for (let index = 1; index <= $('#jml').val(); index++) {
            getstok(index);
        }
    }

    function del() {
        obj = $('#tabledatax tbody tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function validasi(id) {
        var jml = document.getElementById("jml").value;
        if (id === undefined) {
            for (i = 1; i <= jml; i++) {
                var nquantity = document.getElementById("nquantity" + i).value;
                var stok = document.getElementById("stok" + i).value;
                if (parseFloat(nquantity) > parseFloat(stok)) {
                    swal('Quantity Kirim Tidak Boleh Melebihi Saldo akhir ' + stok);
                    document.getElementById("nquantity" + i).value = stok;
                    return true;
                    break;
                }
                // if (parseFloat(nquantity) == 0 && parseFloat(nquantity) == '') {
                //     swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                //     document.getElementById("nquantity" + i).value = stok;
                //     return true;
                //     break;
                // }
            }
            return false;
        } else {
            var nquantity = document.getElementById("nquantity" + id).value;
            var stok = document.getElementById("stok" + id).value;
            if (parseFloat(nquantity) > parseFloat(stok)) {
                swal('Quantity Kirim Tidak Boleh Melebihi Saldo akhir ' + stok);
                document.getElementById("nquantity" + id).value = stok;

            }
            // if (parseFloat(nquantity) == 0 && parseFloat(nquantity) == '') {
            //     swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
            //     document.getElementById("nquantity" + id).value = stok;

            // }
        }
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        var valid = validasi();
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
                // $(this).find("td .inputitem").each(function() {
                //     if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                //         swal('Quantity Tidak Boleh Kosong Atau 0!');
                //         ada = true;
                //     }
                // });
            });
            if (!ada && valid == false) {
                return true;
            } else {
                return false;
            }
        }
    }




    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#dbonk').val(),
                'ibagian': $('#ibagian').val(),
                'itujuan': $('#itujuan').val(),
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
</script>