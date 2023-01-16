<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
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
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="ibonk" id="dokumenbon" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<? echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">
                                <?php if ($tujuan) {
                                    $group = "";
                                    foreach ($tujuan as $row) : ?>
                                    <?php if ($group!=$row->name) {?>
                                        </optgroup>
                                        <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                    <?php }
                                    $group = $row->name;
                                    ?>
                                        <option value="<?= "$row->id_company|$row->i_bagian"; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang Keluar</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row) : ?>
                                        <option value="<?= $row->id; ?>">
                                            <?= $row->e_jenis_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-9">
                            <textarea id="eremarkh" placeholder="Isi Keterangan Jika Ada!!!" name="eremarkh" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return konfirm();"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
                </div>
            </div>
        </div>

        <div class="white-box" id="detail">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-11">
                        <h3 class="box-title m-b-0">Detail Barang</h3>
                    </div>
                    <div class="col-sm-1" style="text-align: right;">
                        <?= $doc; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%;">No</th>
                                        <th style="width: 55%;">Barang</th>
                                        <th class="text-right" style="width: 10%;"><span class="mr-3">Qty</span></th>
                                        <th class="text-center" style="width: 10%;">Periode</th>
                                        <th>Keterangan</th>
                                        <th class="text-center" style="width: 3%;">Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date', null, 0);
        number();
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

    /**
     * Tambah Item
     */
    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        //alert("tes");
        i++;
        $("#jml").val(i);
        var no = $('#tabledatax tr').length;
        var newRow = $('<tr id="tr' + i + '">');
        var cols = "";
        cols += '<td class="text-center"><spanx id="snum' + i + '">' + i + '</spanx></td>';
        cols += '<td ><select data-nourut="' + i + '" id="idproduct' + i + '" class="form-control input-sm" name="idproduct' + i + '" onchange="getstok(' + i + ');"></select><input type="hidden" id="stok' + i + '" name="stok' + i + '"></td>';
        // cols += '<td ><input class="form-control qty input-sm text-right" id="nquantity'+ i +'" autocomplete="off" type="text" name="nquantity'+ i +'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="validasi('+ i +')"></td>';
        cols += '<td><input data-noqty="' + i + '" class="form-control qty input-sm text-right" type="number" id="nquantity' + i + '" name="nquantity' + i + '" value="0" onkeyup="validasi(' + i + ')" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' ></td>';
        cols += `<td ><input data-urut="${i}" class="form-control text-center periode input-sm" readonly type="text" id="periode${i}" name="periode${i}" value="" placeholder="Pilih Periode"></td>`;
        cols += `<td ><input class="form-control input-sm" type="text" name="eremark${i}" value="" placeholder="Isi keterangan jika ada!"></td>`;
        cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        cols += `</tr>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $(".periode").datepicker({
            format: "yyyy-mm",
            startView: "months",
            minViewMode: "months",
            autoclose: true
        }).change(function(event) {
            var z = $(this).data('urut');
            var ada = true;
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if (($('#idproduct' + z).val() == $('#idproduct' + x).val()) && ($('#periode' + z).val() == $('#periode' + x).val()) && (z != x)) {
                        swal("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {
                $('#idproduct' + z).val('');
                $('#idproduct' + z).html('');
                $(this).val('');
                $(this).html('');
            };
        });
        $('#idproduct' + i).select2({
            placeholder: 'Cari Kode / Nama WIP',
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
        }).change(function(event) {
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var z = $(this).data('nourut');
            var ada = true;
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if ((($(this).val()) == $('#idproduct' + x).val()) && ($('#periode' + z).val() == $('#periode' + x).val()) && (z != x)) {
                        swal("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {
                $(this).val('');
                $(this).html('');
            }
            validasi(z);
            // else{
            //     $.ajax({
            //         type: "post",
            //         data: {
            //             'id'       : $(this).val(),
            //         },
            //         url: '<?= base_url($folder . '/cform/detailproduct'); ?>',
            //         dataType: "json",
            //         success: function (data) {
            //             $("#tabledatax tbody").each(function() {
            //                 $("tr.del"+z).remove();
            //             });
            //             var xx = 0;
            //             var netr = "";
            //             /*for (let x = 0; x < data['detail'].length; x++) {*/
            //             for (let x = data['detail'].length; x > 0 ; x--) {
            //                 var newRow1 = $('<tr class="del'+z+'">');
            //                 cols += '<td class="text-center">'+x+'</td>';
            //                 cols += '<td><input type="hidden" name="idproductwip[]" value="'+data['detail'][xx]['id_product_wip']+'">';
            //                 cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" value="'+data['detail'][xx]['id_material']+'">';
            //                 cols += '<input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_material']+'"></td>';
            //                 cols += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['e_material_name']+'"></td>';
            //                 cols += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
            //                 cols += '<td colspan="2"><input class="form-control input-sm" type="text" name="eremark[]" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
            //                 newRow1.append(cols);
            //                 $('#nquantity'+z).focus();
            //                 /*$("#tabledatax #tr"+z).insertAfter(newRow1);*/
            //                 $(newRow1).insertAfter("#tabledatax #tr"+z);
            //                 xx++;
            //             }
            //         },
            //         error: function () {
            //             swal('Data kosong : (');
            //         }
            //     });
            // }
        });


    });

    function gety(i) {

        // $("#nquantity"+ i).keyup(function(event){
        var noqty = $(this).data('noqty');
        validasi(noqty);
        // })
    }

    /**
     * Hapus Detail Item
     */

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        $('#jml').val(i);
        del();
    });

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    //new script
    function number() {
        /* let split = $('#itujuan').val().split('|');
        let i_tujuan_bagian = split[1]; */
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
                $('#dokumenbon').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#dokumenbon").attr("readonly", false);
        } else {
            $("#dokumenbon").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    // $( "#dokumenbon" ).keyup(function() {
    //     $.ajax({
    //         type: "post",
    //         data: {
    //             'kode' : $(this).val(),
    //             'ibagian' : $('#ibagian').val(),
    //         },
    //         url: '<?= base_url($folder . '/cform/cekkode'); ?>',
    //         dataType: "json",
    //         success: function (data) {
    //             if (data==1) {
    //                 $("#ada").attr("hidden", false);
    //                 $("#submit").attr("disabled", true);
    //             }else{
    //                 $("#ada").attr("hidden", true);
    //                 $("#submit").attr("disabled", false);
    //             }
    //         },
    //         error: function () {
    //             swal('Error :)');
    //         }
    //     });
    // });

    function getstok(id) {
        var idproduct = $('#idproduct' + id).val();
        $.ajax({
            type: "post",
            data: {
                'idproduct': idproduct,
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/getstok'); ?>',
            dataType: "json",
            success: function(data) {
                //console.log(data.saldo_akhir);
                $('#stok' + id).val(data.saldo_akhir);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    function validasi(id) {
        var jml = document.getElementById("jml").value;
        if (id === undefined) {
            for (i = 1; i <= jml; i++) {
                var nquantity = document.getElementById("nquantity" + i).value;
                var stok = document.getElementById("stok" + i).value;

                var material = $('#idproduct'+i).val();
                var qty = parseFloat($('#nquantity'+i).val());
                for (let j = 1; j <= $('#jml').val(); j++) {
                    if (material == $('#idproduct'+j).val() && i != j) {
                        qty += parseFloat($('#nquantity'+j).val());
                    }
                }
                if (parseFloat(qty) > parseFloat(stok)) {
                    swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                    document.getElementById("nquantity" + i).value = 0;
                    return true;
                    break;
                }
                /* if (parseFloat(nquantity) == 0 && parseFloat(nquantity) == '') {
                    swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                    document.getElementById("nquantity" + i).value = stok;
                    return true;
                    break;
                } */
            }
            return false;
        } else {
            var material = $('#idproduct'+id).val();
            var nquantity = parseFloat(document.getElementById("nquantity" + id).value);
            var stok = parseFloat(document.getElementById("stok" + id).value);
            var qty = parseFloat($('#nquantity'+id).val());
            for (let i = 1; i <= $('#jml').val(); i++) {
                if (material == $('#idproduct'+i).val() && id != i) {
                    qty += parseFloat($('#nquantity'+i).val());
                }
            }
            // console.log(qty);
            if (parseFloat(qty) > parseFloat(stok)) {
                swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                document.getElementById("nquantity" + id).value = 0;
            }
            /* if (parseFloat(qty) == 0 && parseFloat(qty) == '') {
                swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                document.getElementById("nquantity" + id).value = 0;
            } */
        }
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
                // $(this).find("td input").each(function() {
                //     if ($(this).val()=='' || $(this).val()==null) {
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
</script>