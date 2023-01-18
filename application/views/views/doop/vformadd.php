<!-- <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Area</label>
                            <div class="col-sm-3">
                                <select name="ibagian" required="" id="ibagian" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->i_bagian; ?>">
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id">
                                    <input type="text" name="isj" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                    <!-- <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span> -->
                                </div>
                                <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="ddocument" required="" name="ddocument" class="form-control input-sm date" value="<?= date("d-m-Y"); ?>" readonly>
                            </div>
                            <div class="col-sm-4">
                                <select name="iarea" id="iarea" class="form-control select2"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Customer</label>
                            <label class="col-md-3">Nomor Referensi Dokumen</label>
                            <label class="col-md-3">Nomor OP Distributor</label>
                            <label class="col-md-3">Tanggal Referensi</label>
                            <div class="col-sm-3">
                                <select name="icustomer" id="icustomer" class="form-control select2">
                                </select>
                                <input type="hidden" id="ncustop" name="ncustop" class="form-control" value="" readonly>
                                <input type="hidden" id="idharga" name="idharga" class="form-control" value="" readonly>
                                <input type="hidden" id="ecustomer" name="ecustomer" class="form-control" value="" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="ireferensi" id="ireferensi" class="form-control select2">
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="irefop" name="irefop" class="form-control input-sm" value="" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="dreferensi" name="dreferensi" class="form-control input-sm" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Keterangan</label>
                            <label class="col-md-9"></label>
                            <div class="col-sm-3">
                                <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi Keterangan Jika Ada!"></textarea>
                            </div>
                            <label class="col-sm-9"></label>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-offset-5 col-sm-10">
                                <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <span class="notekode"><b>Note : </b></span><br>
                                <span class="notekode">* Tanggal Dokument tidak boleh lebih kecil dari tanggal Referensi.</span>
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
            <div class="m-b-0">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="8%;">Kode</th>
                            <th class="text-center" width="23%;">Nama Barang</th>
                            <th class="text-center" width="6%;">Warna</th>
                            <th class="text-center" width="5%;">FC</th>
                            <th class="text-center" width="8%;">Total Order <br>Belum proses</th>
                            <th class="text-center" width="5%;">Stock</th>
                            <th class="text-center" width="8%;">Stock - <br>DO Belum Approve</th>
                            <th class="text-center" width="5%;">Qty <br> Order</th>
                            <th class="text-center" width="5%;">Qty <br> SJ</th>
                            <th class="text-center" width="10%;">Keterangan</th>
                            <th class="text-center" width="10%;">Ket OP</th>
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
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar3('.date',0,0);
        number();

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/area/'); ?>',
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
                        results: data
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $("#tabledatax tbody tr").remove();
            $("#jml").val(0);
            $("#icustomer").val("");
            $("#ireferensi").val("");
            $("#iref").val("");
            $("#iref").html("");
            $("#dreferensi").val("");
            $("#icustomer").html("");
            $("#ireferensi").html("");
            $("#dreferensi").html("");
        });

        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/customer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        iarea: $('#iarea').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $("#tabledatax tbody tr").remove();
            $("#jml").val(0);
            $("#ireferensi").val("");
            $("#dreferensi").val("");
            $("#ireferensi").html("");
            $("#dreferensi").html("");
            $("#iref").val("");
            $("#iref").html("");
        });

        $('#ireferensi').select2({
            placeholder: 'Cari No Referensi',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        icustomer: $('#icustomer').val(),
                        iarea: $('#iarea').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    $("#isj").keyup(function() {
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

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#isj").attr("readonly", false);
        } else {
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#isj').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    /*$("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });*/

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $("#ireferensi").change(function() {
        $("#ireferensi").val($(this).val());
        $("#tabledatax tbody tr").remove();
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'id': $(this).val(),
                'i_referensi': $(this).find(':selected').text(),
                'd_document': $('#ddocument').val(),
                'i_customer': $('#icustomer').val(),
            },
            url: '<?= base_url($folder . '/cform/getdetailrefeks'); ?>',
            dataType: "json",
            success: function(data) {
                var dreferensi = data['head']['d_document'];
                var ncustop = data['head']['n_customer_toplength'];
                $('#irefop').val(data['head']['i_referensi']);
                $('#dreferensi').val(dreferensi);
                $('#ncustop').val(ncustop);
                $('#idharga').val(data['head']['id_harga_kode']);
                $('#ecustomer').val(data['head']['e_customer_name']);
                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                let text = $("#ireferensi").find(":selected").text();
                let resSplit = text.split('~');
                let iref = resSplit[1].trim();
                if(iref == 'Transfer') {
                    let i = 0;
                    let ii = 0;
                    let group = "";
                    if (data['detail'].length > 0) {
                        let no = 0;
                        for (let a = 0; a < data['detail'].length; a++) {
                            no++;
                            ii++;
                            if (group != data['detail'][a]['i_product_base']) {
                                i++;
                                let head = ''
                                head += `<tr class="tr list-item tr_first${i}">
                                    <td class="text-center">
                                        <spanlistx id="snum${i}"><b>${i}</b></spanlistx>
                                    </td>
                                    <td style="text-align: center"><input hidden readonly id="idproduct${i}" name="idproduct${i}" value="${data['detail'][a]['id_product']}"><input class="form-control input-sm" readonly id="iproduct${i}" name="iproduct${i}" value="${data['detail'][a]['i_product_base']}"></td>';
                                    <td><input type="text" class="form-control input-sm" id="eproduct${i}" name="eproduct${i}" value="${data['detail'][a]['e_product_basename']}" readonly></td>';
                                    <td colspan="5"></td>
                                    <td><input type="text" class="form-control text-right input-sm inputitem" id="nquantityspb${i}" name="nquantityspb${i}" value="${data['detail'][a]['n_quantity']}" onkeydown="nexttab2(this, event,\'inputitem\')" readonly></td>
                                    <td colspan="2"></td>
                                    <td><input readonly class="form-control input-sm" id="edescop${i}" placeholder="Isi Keterangan Jika Ada!" name="edescop[]" value="${data['detail'][a]['e_remark']}"></td>
                                </tr>`
                                $("#tabledatax tbody").append(head);
                            }
                            group = data['detail'][a]['i_product_base'];
                            let item = ''
                            item += `
                            <tr class="list-item td_${i}">
                                <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                <td style="text-align: center"><input hidden readonly id="idproduct${i}_${ii}" name="idproduct[]" value="${data['detail'][a]['id_product']}"><input class="form-control input-sm" readonly id="iproduct${i}_${ii}" name="iproduct${i}_${ii}" value="${data['detail'][a]['i_product_base']}"></td>';
                                <td><input type="text" class="form-control input-sm" id="eproduct${i}_${ii}" name="eproduct${i}_${ii}" value="${data['detail'][a]['e_product_basename']}" readonly></td>';
                                <td><input type="text" class="form-control input-sm" id="ecolor${i}_${ii}" name="ecolor${i}_${ii}" value="${data['detail'][a]['e_color_name']}" readonly></td>
                                <td><input type="text" class="form-control text-right input-sm" id="fc${i}_${ii}" name="fc${i}_${ii}" value="${data['detail'][a]['n_quantity_fc']}" readonly></td>
                                <td><input type="text" class="form-control text-right input-sm" id="opsisa${i}_${ii}" name="opsisa${i}_${ii}" value="${data['detail'][a]['n_quantity_sisa_total']}" readonly></td>
                                <td><input type="text" class="form-control text-right input-sm" id="nsaldo${i}_${ii}" name="nsaldo${i}_${ii}" value="${data['detail'][a]['n_stock']}" readonly></td>
                                <td><input type="text" class="form-control text-right input-sm" id="nsaldo_outstanding${i}_${ii}" name="nsaldo_outstanding${i}_${ii}" value="${data['detail'][a]['n_stock_outstanding']}" readonly></td>
                                <td></td>
                                <td hidden><input class="form-control text-right input-sm" readonly id="sisa${i}_${ii}" name="sisa[]" value="${data['detail'][a]['n_quantity_sisa']}"></td>
                                <td><input class="form-control text-right input-sm inputitem kode_${data['detail'][a]['i_product_base']}" autocomplete="off" id="nquantity${i}_${ii}" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeydown="nexttab2(this, event,\'inputitem\')" onkeypress="return event.charCode >= 48 && event.charCode <= 57;" onkeyup="ceksaldo2(${i}, ${ii}, '${data['detail'][a]['i_product_base']}'); angkahungkul(this);"></td>
                                <td><input class="form-control input-sm" id="edesc${i}" placeholder="Isi Keterangan Jika Ada!" name="edesc[]" value=""></td>
                                <input type = "hidden" name="vprice[]" value="${data['detail'][a]['v_price']}">
                                <input type = "hidden" name="n_quantity[]" id="n_quantity${i}_${ii}" value="${data['detail'][a]['n_quantity']}">
                                <input type = "hidden" name="ndiskon1[]" value="${data['detail'][a]['n_diskon1']}">
                                <input type = "hidden" name="ndiskon2[]" value="${data['detail'][a]['n_diskon2']}">
                                <input type = "hidden" name="ndiskon3[]" value="${data['detail'][a]['n_diskon3']}">
                                <input type = "hidden" name="vdiskonplus[]" value="${data['detail'][a]['v_diskon_tambahan']}">
                            </tr>`
                            $(item).insertAfter(`#tabledatax tbody .tr_first${i}`);
                        }
                    }
                } else {
                    for (let a = 0; a < data['detail'].length; a++) {
                        var no = $('#tabledatax tbody tr').length + 1;
                        var cols = "";
                        var newRow = $("<tr>");
                        cols += '<td style="text-align: center">' + no + '</td>';
                        cols += '<td style="text-align: center"><input hidden readonly id="idproduct' + no + '" name="idproduct[]" value="' + data['detail'][a]['id_product'] + '"><input class="form-control input-sm" readonly id="iproduct' + no + '" name="iproduct' + no + '" value="' + data['detail'][a]['i_product_base'] + '"></td>';
                        cols += '<td><input type="text" class="form-control input-sm" id="eproduct' + no + '" name="eproduct' + no + '" value="' + data['detail'][a]['e_product_basename'] + '" readonly></td>';
                        cols += '<td><input type="text" class="form-control input-sm" id="ecolor' + no + '" name="ecolor' + no + '" value="' + data['detail'][a]['e_color_name'] + '" readonly></td>';
                        cols += '<td><input type="text" class="form-control text-right input-sm" id="fc' + no + '" name="fc' + no + '" value="' + data['detail'][a]['n_quantity_fc'] + '" readonly></td>';
                        cols += '<td><input type="text" class="form-control text-right input-sm" id="opsisa' + no + '" name="opsisa' + no + '" value="' + data['detail'][a]['n_quantity_sisa_total'] + '" readonly></td>';
                        cols += '<td><input type="text" class="form-control text-right input-sm" id="nsaldo' + no + '" name="nsaldo' + no + '" value="' + data['detail'][a]['n_stock'] + '" readonly></td>';
                        cols += '<td><input type="text" class="form-control text-right input-sm" id="nsaldo_outstanding' + no + '" name="nsaldo_outstanding' + no + '" value="' + data['detail'][a]['n_stock_outstanding'] + '" readonly></td>';
                        cols += '<td><input type="text" class="form-control text-right input-sm" id="nquantityspb' + no + '" name="nquantityspb[]" value="' + data['detail'][a]['n_quantity'] + '" readonly></td>';
                        cols += '<td hidden><input class="form-control text-right input-sm" readonly id="sisa' + no + '" name="sisa[]" value="' + data['detail'][a]['n_quantity_sisa'] + '"></td>';
                        cols += '<td><input class="form-control text-right input-sm inputitem" autocomplete="off" id="nquantity' + no + '" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeydown="nexttab(this, event,\'inputitem\')" onkeypress="return event.charCode >= 48 && event.charCode <= 57;" onkeyup="ceksaldo(' + no + '); angkahungkul(this);"></td>';
                        cols += '<td><input class="form-control input-sm" id="edesc' + no + '" placeholder="Isi Keterangan Jika Ada!" name="edesc[]" value=""></td>';
                        cols += '<td><input readonly class="form-control input-sm" id="edescop' + no + '" placeholder="Isi Keterangan Jika Ada!" name="edescop[]" value="' + data['detail'][a]['e_remark'] + '"></td>';
                        cols += '<input type = "hidden" name="vprice[]" value="' + data['detail'][a]['v_price'] + '">';
                        cols += '<input type = "hidden" name="ndiskon1[]" value="' + data['detail'][a]['n_diskon1'] + '">';
                        cols += '<input type = "hidden" name="ndiskon2[]" value="' + data['detail'][a]['n_diskon2'] + '">';
                        cols += '<input type = "hidden" name="ndiskon3[]" value="' + data['detail'][a]['n_diskon3'] + '">';
                        cols += '<input type = "hidden" name="vdiskonplus[]" value="' + data['detail'][a]['v_diskon_tambahan'] + '">';
                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
                    }
                }

                maxi();
            },
            error: function() {
                swal('Data kosong :)');
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    // function nexttab(c, event, className){
    //   //console.log(e.className + " " + event.which);
    //   var keyCode = event.which || event.charCode || event.keyCode;
    //   //alert(keyCode);
    //   if (keyCode == 9) { 
    //     event.preventDefault(); 
    //     //$(c).nextAll('.'+c.className+':enabled:first').focus();
    //     //console.log($(c).closest('tr'));
    //     $(c).closest('tr').next().find('.'+className).focus();
    //   } 
    //  }  

    function ceksaldo2(i, ii, kode_brg) {
        if (parseFloat($('#nquantity' + i + '_' + ii).val()) > parseFloat($('#nsaldo' + i + '_' + ii).val())) {
            swal('Maaf :(','Quantity Pemenuhan = '+$('#nquantity' + i + '_' + ii).val()+' tidak boleh lebih dari Quantity Saldo = '+$('#nsaldo' + i + '_' + ii).val()+'!!!','error');
            $('#nquantity' + i + '_' + ii).val(0)
        } else {
            let sum = 0;
            let last = 0;
            $(`.kode_${kode_brg}`).each(function() {
                sum += parseFloat($(this).val());
            })
            let curr = parseFloat($('#nquantity' + i + '_' + ii).val())
            last = sum - curr;
            if (sum > parseFloat($('#nquantityspb' + i).val())) {
                swal('Maaf :(','Quantity Pemenuhan = '+sum+' tidak boleh lebih dari Quantity Permintaan = '+$('#nquantityspb' + i).val()+'!!!','error');
                // if(parseFloat($(this).val()) > 0) {
                set_qty2(i, ii, sum, last);
                // } else {
                //     set_qty2(i, ii, sum, 0);
                // }
            }
        }
    }

    function set_qty2(i, ii, sum, last) {
        /* if (parseFloat($('#nquantityspb' + i).val()) >= parseFloat($('#sisa' + i + '_' + ii).val())) {
            $('#nquantity' + i + '_' + ii).val(parseFloat($('#sisa' + i + '_' + ii).val()));
        } else if (parseFloat($('#nquantityspb' + i).val()) < parseFloat($('#sisa' + i + '_' + ii).val())) {
            $('#nquantity' + i + '_' + ii).val(parseFloat($('#nsaldo' + i + '_' + ii).val()));
        } else  */if (parseFloat($('#nquantityspb' + i).val()) < sum) {
            $('#nquantity' + i + '_' + ii).val(parseFloat($('#nquantityspb' + i).val()) - last);
        }
    }

    function ceksaldo(i) {
        if (parseFloat($('#nquantity' + i).val()) > parseFloat($('#sisa' + i).val())) {
            swal('Maaf :(','Quantity Pemenuhan = '+$('#nquantity' + i).val()+' tidak boleh lebih dari Quantity Permintaan = '+$('#sisa' + i).val()+'!!!','error');
            set_qty(i);
        } else {
            if (parseFloat($('#nquantity' + i).val()) > parseFloat($('#nsaldo' + i).val())) {
                swal('Maaf :(','Quantity Pemenuhan = '+$('#nquantity' + i).val()+' tidak boleh lebih dari Quantity Saldo = '+$('#nsaldo' + i).val()+'!!!','error');
                set_qty(i);
            }
        }
    }

    function set_qty(i) {
        if (parseFloat($('#nsaldo' + i).val()) >= parseFloat($('#sisa' + i).val())) {
            $('#nquantity' + i).val($('#sisa' + i).val());
        } else if (parseFloat($('#nsaldo' + i).val()) < parseFloat($('#sisa' + i).val())) {
            $('#nquantity' + i).val($('#nsaldo' + i).val());
        } else {
            $('#nquantity' + i).val(0);
        }
    }

    function maxi() {
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dreferensi').value,
        });
    }

    $('#ddocument').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dreferensi').value,
    });

    $("#submitx").click(function(event) {
        ada = false;
        if (($('#ibagian').val() != '' || $('#ibagian').val() != null) && ($('#iarea').val() != '' || $('#iarea').val() != null) && ($('#ireferensi').val() != '' || $('#ireferensi').val() != null)) {
            if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                $("#tabledatax tbody tr").each(function() {
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
        } else {
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    });

    /*----------  VALIDASI UPDATE DATA  ----------*/
    $("#submit").click(function(event) {
        ada = false;
        if (($('#ibagian').val() != '' || $('#ibagian').val() != null) && ($('#iarea').val() != '' || $('#iarea').val() != null) && ($('#ireferensi').val() != '' || $('#ireferensi').val() != null)) {
            if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                /* $("#tabledatax tbody tr").each(function() {
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                            swal('Maaf :(', 'Quantity Tidak Boleh Kosong Atau 0!', 'error');
                            ada = true;
                        }
                    });
                });
                if (!ada) { */
                swal({
                    title: "Simpan Data Ini?",
                    text: "Anda Dapat Membatalkannya Nanti",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonColor: 'LightSeaGreen',
                    confirmButtonText: "Ya, Simpan!",
                    closeOnConfirm: false
                }, function() {
                    $.ajax({
                        type: "POST",
                        data: $("form").serialize(),
                        url: '<?= base_url($folder . '/cform/simpan/'); ?>',
                        dataType: "json",
                        success: function(data) {
                            if (data.sukses == true) {
                                $('#id').val(data.id);
                                swal("Sukses!", "No Dokumen : " + data.kode + ", Berhasil Disimpan :)", "success");
                                $("input").attr("disabled", true);
                                $("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("hidden", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "No Dokumen : " + data.kode + ", Sudah Ada :(", "error");
                            } else {
                                swal("Maaf :(", "No Dokumen : " + data.kode + ", Gagal Disimpan :(", "error");
                            }
                        },
                        error: function() {
                            swal("Maaf", "Data Gagal Disimpan :(", "error");
                        }
                    });
                });
                /* } else {
                    return false;
                } */
            }
        } else {
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    });
</script>