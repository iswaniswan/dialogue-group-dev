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
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
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
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="ibonkold" id="ibonkold" value="<?= $data->i_document; ?>">
                                <input type="text" name="ibonk" id="istbreturjahit" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<?= $data->d_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->id_bagian_company.'|'.$data->i_tujuan) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
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
                        <!-- <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Update</button>
                        <?php } ?> -->

                        <!-- <?php if ($data->i_status == '2') { ?>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2" hidden="true"><i class="fa fa-plus mr-2"></i>Item</button>
                        <? } else { ?>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                        <? } ?> -->
                        <?php if ($data->i_status == '1' || $data->i_status == '3') { ?>
                            <div class="col-sm-3">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Update</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                        <?php } elseif ($data->i_status == '2') { ?>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
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
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-2">
            <input type="text" id="dfrom" name="dfrom" class="form-control input-sm date" readonly value="<?= date("d-m-Y"); ?>">
        </div>
        <div class="col-sm-2">
            <input type="text" id="dto" name="dto" class="form-control input-sm date" readonly value="<?= date("d-m-Y"); ?>">
        </div>
        <div class="col-sm-3">
            <button type="button" class="btn btn-info btn-block btn-sm mr-2 cari"> <i class="fa fa-search fa-lg mr-2"></i>Cari</button>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-2"><input class="form-control input-sm" readonly value="<?= $this->doc_qe; ?>"></div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 8%;">Kode</th>
                            <th class="text-center" style="width: 25%;">Nama Barang Jadi</th>
                            <th class="text-center" style="width: 7%;">Warna</th>
                            <th class="text-center" style="width: 7%;">QTY Kirim</th>
                            <th class="text-center" style="width: 10%;">Bagian (Optional)</th>
                            <th class="text-center" style="width: 10%;">Penyebab Reject</th>
                            <th class="text-center" style="width: 15%;">Detail Reject</th>
                            <th class="text-center" style="width: 30%;">Referensi</th>
                            <!-- <th class="text-center" style="width: 5%;">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        if ($detail) {
                            foreach ($detail as $row) {
                                $i++; ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                    </td>
                                    <td>
                                        <input value="<?= $row->id_reference; ?>" type="hidden" readonly id="idreference<?= $i; ?>" class="form-control input-sm" name="idreference[]">
                                        <input value="<?= $row->id_product; ?>" type="hidden" readonly id="idproduct<?= $i; ?>" class="form-control input-sm" name="idproduct[]">
                                        <input value="<?= $row->i_product_base; ?>" type="text" readonly id="iproduct<?= $i; ?>" class="form-control input-sm" name="iproduct[]">
                                    </td>
                                    <td><input value="<?= $row->e_product_basename; ?>" type="text" readonly id="eproduct<?= $i; ?>" class="form-control input-sm" name="eproduct[]"></td>
                                    <td>
                                        <input value="<?= $row->id_color; ?>" type="hidden" readonly id="idcolorproduct<?= $i; ?>" class="form-control input-sm" name="idcolorproduct[]">
                                        <input value="<?= $row->e_color_name; ?>" type="text" readonly id="ecolorproduct<?= $i; ?>" class="form-control input-sm" name="ecolorproduct[]">
                                    </td>
                                    <td>
                                        <input value="<?= $row->n_qty_awal; ?>" type="hidden" id="nquantityawal<?= $i; ?>" name="nquantityawal[]">
                                        <input value="<?= $row->n_quantity_product; ?>" type="text" autocomplete="off" class="form-control input-sm text-right inputitem" id="nquantity<?= $i; ?>" value="1" onkeypress="return hanyaAngka(event);" onkeyup="cekqty(<?= $i; ?>);" name="nquantity[]" onblur=\"if(this.value=='' ){this.value='1' ;}\" onfocus=\"if(this.value=='1' ){this.value='' ;}\">
                                    </td>
                                    <td><input value="<?= $row->bagian; ?>" type="text" id="bagian<?= $i; ?>" class="form-control input-sm" name="bagian[]"></td>
                                    <td><select type="text" id="id_reject<?= $i; ?>" class="form-control select2" name="id_reject[]">
                                            <option value="<?= $row->id_reject; ?>"><?= $row->e_reject_name; ?></option>
                                        </select>
                                    </td>
                                    <td><input value="<?= $row->detail_reject; ?>" type="text" id="detail_reject<?= $i; ?>" class="form-control input-sm" name="detail_reject[]"></td>
                                    <td><input value="<?= $row->e_remark; ?>" type="text" readonly id="edesc<?= $i; ?>" class="form-control input-sm" name="edesc[]"></td>
                                    <!-- <td class="text-center">
                                    <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                </td> -->
                                </tr>
                        <?php }
                        } ?>
                        <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    $(document).ready(function() {
        // $('#istbreturjahit').mask('SS-0000-000000S');
        $('.select2').select2({
            width: '100%',
        });
        showCalendar('.date', 1830, 0);
        $('#ibagian').select2({
            placeholder: 'Pilih Bagian',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#xbagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });

        for (let i = 1; i <= $('#jml').val(); i++) {
            $('#id_reject' + i).select2({
                placeholder: 'Pilih Penyebab Reject',
                allowClear: true,
                width: "100%",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_reject'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q : params.term,
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
        }

        $(".cari").click(function() {
            $.ajax({
                type: "post",
                data: {
                    'ibagian': $('#ibagian').val(),
                    'dfrom': $('#dfrom').val(),
                    'dto': $('#dto').val(),
                },
                url: '<?= base_url($folder . '/cform/getdataitem'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['dataitem'].length > 0) {
                        $("#tabledatax tbody").remove();
                        $('#jml').val(data['dataitem'].length);
                        i = 0;
                        for (let a = 0; a < data['dataitem'].length; a++) {
                            i++;
                            var newRow = $("<tr>");
                            var cols = "";
                            cols += `<td class="text-center"><spanx id="snum${i}">${i}</spanx></td>`;
                            cols += `<td>
                            <input value="${data['dataitem'][a]['id_document']}" type="hidden" readonly id="idreference${i}" class="form-control input-sm" name="idreference[]">
                            <input value="${data['dataitem'][a]['id_product_wip']}" type="hidden" readonly id="idproduct${i}" class="form-control input-sm" name="idproduct[]">
                            <input value="${data['dataitem'][a]['i_product_wip']}" type="text" readonly id="iproduct${i}" class="form-control input-sm" name="iproduct[]">
                            </td>`;
                            cols += `<td><input value="${data['dataitem'][a]['e_product_wipname']}" type="text" readonly id="eproduct${i}" class="form-control input-sm" name="eproduct[]"></td>`;
                            cols += `<td>
                            <input value="${data['dataitem'][a]['id_color']}" type="hidden" readonly id="idcolorproduct${i}" class="form-control input-sm" name="idcolorproduct[]">
                            <input value="${data['dataitem'][a]['e_color_name']}" type="text" readonly id="ecolorproduct${i}" class="form-control input-sm" name="ecolorproduct[]">
                            </td>`;
                            cols += `<td><input value="${data['dataitem'][a]['qty_sisa']}" type="hidden" id="nquantityawal${i}" name="nquantityawal[]"><input value="${data['dataitem'][a]['qty_sisa']}" type="text" autocomplete="off" class="form-control input-sm text-right inputitem" id="nquantity${i}" value="1" onkeypress="return hanyaAngka(event);" onkeyup="cekqty(${i});" name="nquantity[]" onblur=\"if(this.value==''){this.value='1';}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
                            cols += `<td><input type="text" id="bagian${i}" class="form-control input-sm" name="bagian[]"></td>`;
                            cols += `<td><select type="text" id="id_reject${i}" class="form-control" name="id_reject[]"><option value=""></option></select></td>`;
                            cols += `<td><input type="text" id="detail_reject${i}" class="form-control input-sm" name="detail_reject[]"></td>`;
                            cols += `<td><input type="text" readonly value="${data['dataitem'][a]['e_remark']} [${data['dataitem'][a]['reff']}]" id="edesc${i}" class="form-control input-sm" name="edesc[]"></td>`;
                            // cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                            $('#id_reject' + i).select2({
                                placeholder: 'Pilih Penyebab Reject',
                                allowClear: true,
                                width: "100%",
                                ajax: {
                                    url: '<?= base_url($folder . '/cform/get_reject'); ?>',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        var query = {
                                            q : params.term,
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
                        }
                        $("#tabledatax").on("click", ".ibtnDel", function(event) {
                            $(this).closest("tr").remove();
                        });
                    } else {
                        swal("Maaf, tidak ada data pada periode tersebut :(");
                    }
                },
                error: function() {
                    alert('Error :)');
                }
            });
        });
    });

    $("#istbreturjahit").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#istbreturjahit').val() != $('#istbreturjahitold').val())) {
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
            $("#istbreturjahit").attr("readonly", false);
        } else {
            $("#istbreturjahit").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#istbreturjahit").val($("#istbreturjahitold").val());
            /*number();*/
        }
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#dbonk').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#istbreturjahit').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    function cekqty(id){
        var qtyawal = $("#nquantityawal"+id).val();
        var qty = $("#nquantity"+id).val();

        if(qty > qtyawal){
            swal('Jumlah QTY Kirim tidak boleh melebihi '+ qtyawal);
            $("#nquantity"+id).val(qtyawal);
        }

    }

    $('#ibagian, #dbonk').change(function(event) {
        //number();
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
        //$("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    var counter = $('#jml').val();
    //var counterx = counter-1;
    $("#addrow").on("click", function() {
        counter++;
        $("#tabledatax").attr("hidden", false);
        //  var iproduct = $('#iproduct'+counterx).val();
        count = $('#tabledatax tr').length;
        // if ((iproduct==''||iproduct==null)&&(count>1)) {
        //     swal('Isi dulu yang masih kosong!!');
        //     counter = counter-1;
        //     counterx = counterx-1;
        //     return false;
        // }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum' + counter + '">' + count + '</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct' + counter + '" class="form-control input-sm" name="idproduct[]"><input type="text" readonly id="iproduct' + counter + '" class="form-control input-sm" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" id="eproduct' + counter + '" class="form-control" name="eproduct' + counter + '" onchange="getproduct(' + counter + ');"</td>';
        cols += '<td><input type="hidden" id="idcolorproduct' + counter + '" class="form-control" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct' + counter + '" class="form-control input-sm" name="ecolorproduct' + counter + '"></td>';
        /* cols += `<td><input type="text" id="nquantity${counter}" class="form-control input-sm text-right inputitem" name="nquantity[]" value="0" onkeypress="return hanyaAngka(event);" onblur=\"if(this.value==''){this.value='';}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`; */
        cols += `<td><input type="text" autocomplete="off" class="form-control input-sm text-right inputitem" id="nquantity${counter}" value="1" onkeypress="return hanyaAngka(event);" name="nquantity[]" onblur=\"if(this.value==''){this.value='1';}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
        cols += '<td><input type="text" id="bagian' + counter + '" class="form-control input-sm" name="bagian[]"></td>';
        cols += '<td><select type="text" id="id_reject' + counter + '" class="form-control" name="id_reject[]"><option value=""></option></select></td>';
        cols += '<td><input type="text" id="detail_reject' + counter + '" class="form-control input-sm" name="detail_reject[]"></td>';
        cols += '<td><input type="text" id="edesc' + counter + '" class="form-control input-sm" name="edesc[]"></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $('#eproduct' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder . '/cform/dataproduct'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#id_reject' + counter).select2({
            placeholder: 'Pilih Penyebab Reject',
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_reject'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    function formatSelection(val) {
        return val.name;
    }

    function getproduct(id) {
        ada = false;
        var a = $('#eproduct' + id).val();
        var x = $('#jml').val();
        for (i = 1; i <= x; i++) {
            if ((a == $('#eproduct' + i).val()) && (i != x)) {
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

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        $('#jml').val(counter);
        del();
    });

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
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
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
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
</script>