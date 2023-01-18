<style type="text/css">
    .dropify-wrapper {
        height: 155px !important;
    }
</style>
<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <!-- <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a> -->
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
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="text" name="ibonk" id="bon_jahit" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br> -->
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<? echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->id_company.'|'.$row->i_bagian; ?>">
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
                                        <option value="<?= $row->id; ?>">
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
                            <textarea placeholder="Isi Keterangan Jika Ada!!!" id="eremark" name="eremark" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class=""></i><?= "Upload STB Jahit"; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> <?= $title_list; ?></a>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-6">Upload File (Optional)</label>
                            <label class="col-md-6 text-right notekode">Formatnya .xls</label>
                            <div class="col-sm-12">
                                <input type="file" id="input-file-now" name="userfile" class="dropify" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload</button>
                            </div>
                            <div class="col-md-6">
                                <a id="href" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Template</button> </a>
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
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
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
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    $(document).ready(function() {
        // $('#bon_jahit').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date', null, 0);
        $('.dropify').dropify();
        number();
    });

    $("#upload").on("click", function() {
        tampungProd = [];
        var itujuan = $('#itujuan').val();
        var ibagian = $('#ibagian').val();
        if (itujuan.length > 0) {
            var formData = new FormData();
            formData.append('userfile', $('input[type=file]')[0].files[0]);
            formData.append('itujuan', itujuan);
            formData.append('ibagian', ibagian);
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder . '/cform/load'); ?>",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(data) {
                    var json = JSON.parse(data);
                    var sama = json.sama;
                    var status = json.status;
                    var detail = json.datadetail;
                    if (sama == true) {
                        if (status == 'berhasil') {
                            swal({
                                title: "Success!",
                                text: "File Success Diupload :)",
                                type: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            if (json.detail.length > 0) {
                                clear_table('tabledatax');
                                $('#jml').val(json.detail.length);
                                var group = '';
                                var no = 1;
                                // var newRow = $("<tr>");
                                for (let i = 0; i < json.detail.length; i++) {
                                    var cols = "";
                                    var stock = 0;
                                    if($('#ijenisbarang').val()=='1') {
                                        stock = json.detail[i].n_stock.saldo_akhir;
                                    } else {
                                        stock = json.detail[i].n_stock.saldo_akhir_repair;
                                    }
                                    // var n_quantity_sisa = parseFloat(data['detail'][i]['n_quantity']) - parseFloat(data['detail'][i]['n_quantity_uraian']);
                                    cols += `<tr>
                                            <td class="text-center"><spanx id="snum${no}">${no}</spanx></td>';
                                            <td><input type="hidden" readonly id="idproduct${no}" class="form-control input-sm" value="${json.detail[i].id}" name="idproduct[]"><input type="text" readonly id="iproduct${no}" class="form-control input-sm" name="iproduct${no}" value="${json.detail[i].i_product_base}"></td>';
                                            <td><select type="text" id="eproduct${no}" class="form-control" name="eproduct${no}" onchange="getproduct(${no}); getstok(${no});">
                                                <option value="${json.detail[i].id}" selected>${json.detail[i].e_product_basename}</option>
                                            </select><input type="hidden" id="stok${no}" name="stok${no}"></td>';
                                            <td><input type="hidden" id="idcolorproduct${no}" class="form-control" value="${json.detail[i].id_color}" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct${no}" class="form-control input-sm" name="ecolorproduct${no}" value="${json.detail[i].e_color_name}"></td>
                                            <td>
                                            <input type="text" readonly id="nstock${no}" value="${stock}" class="form-control input-sm text-right" onkeypress="return hanyaAngka(event);" name="nstock[]" onblur=\"if(this.value==''){this.value='1';}\" onfocus=\"if(this.value=='1'){this.value='';}\">
                                            </td>
                                            <td>
                                            <input type="text" autocomplete="off" class="form-control input-sm text-right inputitem" id="nquantity${no}" value="${json.detail[i].n_quantity}" onkeydown="nexttab(this, event,'inputitem')" onkeypress="return hanyaAngka(event);" onkeyup="validasi(${no})" name="nquantity[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\" >
                                            </td>
                                            <td><input type="text" id="edesc${no}" value="${json.detail[i].keterangan}" class="form-control input-sm" name="edesc[]"></td>
                                            <td class="text-center"><button type="button" data-i="${no}" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                        </tr>`;
                                    // newRow.append(cols);
                                    $("#tabledatax tbody").append(cols);
                                    if(parseInt($(`#nquantity${no}`).val()) > parseInt($(`#nstock${no}`).val())) {
                                        swal('Maaf :(',`Quantity Kirim = ${json.detail[i].n_quantity} Tidak Boleh Melebihi Saldo akhir = ${stock}`);
                                        $(`#nquantity${no}`).val(0);
                                    }
                                    fixedtable($('#tabledatax'));
                                    $('#jml').val(no)
                                    $(`#eproduct${no}`).select2({
                                        placeholder: 'Cari Berdasarkan Nama / Kode',
                                        width: "100%",
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
                                    });
                                    if(!tampungProd.includes(parseInt(json.detail[i].id))) {
                                        tampungProd.push(parseInt(json.detail[i].id));
                                    }
                                    no++;
                                }
                            }
                        } else {
                            swal({
                                title: "Gagal!",
                                text: "File Gagal Diupload :)",
                                type: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    } else {
                        swal({
                            title: "Maaf!",
                            text: "Tujuan yang dipilih tidak sama dengan tujuan yang di download :)",
                            type: "info",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
            });
        } else {
            swal({
                title: "Maaf!",
                text: "Tujuan tidak boleh kosong :)",
                type: "info",
                showConfirmButton: false,
                timer: 1500
            });
        }
    });

    $('#ibagian, #dbonk, #itujuan').change(function(event) {
        number();
        clear_table('tabledatax');
        // check_stock();
    });

    $("#bon_jahit").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#dbonk').val(),
                'ibagian': $('#ibagian').val(),
                'itujuan' : $('#itujuan').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#bon_jahit').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#bon_jahit").attr("readonly", false);
        } else {
            $("#bon_jahit").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    var counter = 0;
    let tampungProd = [];
    $("#addrow").on("click", function() {
        var counter = $('#jml').val();
        var counterx = counter - 1;
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var idproduct = $('#idproduct' + counterx).val();
        // console.log(counterx);
        count = $('#tabledatax tbody tr').length;
        if ((idproduct == '' || idproduct == null) && idproduct != undefined && (count > 1)) {
            swal('Isi dulu yang masih kosong!!');
            counter = counter - 1;
            counterx = counterx - 1;
            return false;
        }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum' + counter + '">' + (count+1) + '</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct' + counter + '" class="form-control input-sm" name="idproduct[]"><input type="text" readonly id="iproduct' + counter + '" class="form-control input-sm" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" id="eproduct' + counter + '" class="form-control" name="eproduct' + counter + '" onchange="getproduct(' + counter + '); getstok('+ counter +');appendproduct(' + counter + ');"></select><input type="hidden" id="stok'+ counter +'" name="stok'+ counter +'"></td>';
        cols += '<td><input type="hidden" id="idcolorproduct' + counter + '" class="form-control" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct' + counter + '" class="form-control input-sm" name="ecolorproduct' + counter + '"></td>';
        /* cols += `<td><input type="text" id="nquantity${counter}" class="form-control input-sm text-right inputitem" name="nquantity[]" value="0" onkeypress="return hanyaAngka(event);" onblur=\"if(this.value==''){this.value='';}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`; */
        cols += `<td>
        <input type="text" readonly class="form-control input-sm text-right" id="nstock${counter}" value="0" onkeypress="return hanyaAngka(event);" name="nstock[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\">
        </td>`;
        cols += `<td>
        <input autocomplete="off" class="form-control input-sm text-right inputitem" id="nquantity${counter}" onkeydown="nexttab(this, event,'inputitem')" value="0" onkeypress="return hanyaAngka(event);" onkeyup="validasi(${counter})" name="nquantity[]" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\" >
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
            width: "100%",
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
        }).change(function() {
            if(!tampungProd.includes(parseInt($(this).val()))) {
                tampungProd.push(parseInt($(this).val()));
            }
        });
    });

    function onlyUnique(value, index, self) {
       return self.indexOf(value) === index;
    }

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
                                    <button type="button" title="Delete" data-i="${x+(i+1)}" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                </td>
                            </tr>
                            `;
                            $("#tabledatax tbody").append(newRow);
                            // getproduct(x+i+1);
                            getstok(x+i+1);
                            $('#eproduct' + (x+i+1)).select2({
                                width: '100%'
                            });
                            $('#jml').val(x+i+1)
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
            if ((a == $('#eproduct' + i).val()) && (i != x)) {
                swal("Barang sudah ada !!!!!");
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
                },
                error: function() {
                    swal('Error :)');
                    $('#idproduct' + id).val('');
                    $('#iproduct' + id).val('');
                    $('#idcolorproduct' + id).val('');
                    $('#ecolorproduct' + id).val('');
                    $('#nquantity' + id).val('');
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

    function getstok(id){
        var idproduct = $('#eproduct'+id).val();
        var ibagian = $('#ibagian').val();
        var itujuan = $('#itujuan').val();
        if(idproduct != null) {
            $.ajax({
                type: "post",
                data: {
                    'idproduct'  : idproduct,
                    'ibagian'    : ibagian,
                    'itujuan'    : itujuan,
                },
                url: '<?= base_url($folder.'/cform/getstok'); ?>',
                dataType: "json",
                success: function (data) {
                    if($('#ijenisbarang').val()=='1'){
                        $('#nstock'+id).val(data.saldo_akhir);
                    }else{
                        $('#nstock'+id).val(data.saldo_akhir_repair);
                    }
                    validasi(id);
                },
                error: function () {
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

    $("#tabledatax tbody").on("click", ".ibtnDel", function(event) {
        let dataI = $(this).attr('data-i');
        let currProduct = parseInt($(`#eproduct${dataI}`).val());
        let index = tampungProd.indexOf(currProduct);
        if (index > -1) { // only splice array when item is found
            tampungProd.splice(index, 1); // 2nd parameter means remove one item only
        }
        $(this).closest("tr").remove();
        // let lengthtr = $('#tabledatax tr').length-1;
        // $('#jml').val(lengthtr);
        del();
    });

    function del() {
        obj = $('#tabledatax tbody tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function validasi(id){
        var jml=document.getElementById("jml").value;
        // for(i=1;i<=jml;i++){
        var nquantity    =document.getElementById("nquantity"+id).value;
        var stok         =document.getElementById("nstock"+id).value;
        if(parseFloat(nquantity)>parseFloat(stok)){
            swal('Maaf :(','Quantity Kirim = '+nquantity+' Tidak Boleh Melebihi Saldo akhir = '+stok);
            document.getElementById("nquantity"+id).value=stok;
            // break;
        }
        // if(parseFloat(nquantity) == 0 && parseFloat(nquantity) == ''){
        //     swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
        //     document.getElementById("nquantity"+id).value=stok;
        //     // break;
        // }
        // }
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
                // $(this).find("td .inputitem").each(function() {
                //     if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                //         swal('Quantity Tidak Boleh Kosong Atau 0!');
                //         ada = true;
                //     }
                // });
            });
            if (!ada) {
                return true;
            } else {
                return false;
            }
        }
    }

    function export_data() {
        var dbonk = $('#dbonk').val();
        var ibagian = $('#ibagian').val();
        var itujuan = $('#itujuan').val();
        var itujuansplit = itujuan.split('|');
        var id_company_tujuan = itujuansplit[0];
        var i_tujuan = itujuansplit[1];
        var dfrom = <?= $dfrom; ?>;
        var dto = <?= $dto; ?>;
        /* if (idforecast == '') {
            swal('Referensi Harus Dipilih!!!');
            return false;
        } else { */
        $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $dfrom . '/' . $dto . '/'); ?>' + i_tujuan + '/' + dbonk + '/' + ibagian + '/' + id_company_tujuan);
        return true;
        /* } */
    }
</script>