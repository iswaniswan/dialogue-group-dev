<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-5">Tanggal Dokumen</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if($row->i_bagian == "PST01"){ echo "selected";} ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idoc" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddoc" name="ddoc" class="form-control date"  required="" readonly value="<? echo date("d-m-Y");?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <!-- <label class="col-md-3">Nomor Referensi</label> -->
                        <label class="col-md-12">Keterangan</label>
                        <!-- <div class="col-md-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2">
                            </select>
                        </div> -->
                        <div class="col-sm-11">
                            <textarea id= "eremarkh" name="eremarkh" class="form-control"></textarea>
                        </div>
                    </div>                   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;              
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                    <input type="hidden" name="jml_item" id="jml_item" value ="0">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
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
                            <th class="text-center" style="width: 20%;">Kode</th>
                            <th class="text-center" style="width: 35%;">Nama Barang</th>
                            <th class="text-center" style="width: 10%;">SO Bagus</th>
                            <th class="text-center">Keterangan</th>
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
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('#idocument').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();

        // $('#ireferensi').select2({
        //     placeholder: 'Pilih Referensi',
        //     allowClear: true,
        //     ajax: {
        //     url: '<?= base_url($folder.'/cform/referensi'); ?>',
        //     dataType: 'json',
        //     delay: 250,          
        //     processResults: function (data) {
        //         return {
        //         results: data
        //         };
        //     },
        //     cache: true
        //     }
        // });
    });

    $('#ibagian, #ddoc').change(function(event) {
        number();
    });
    
    $( "#idocument" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#ddoc').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
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
        $("#send").attr("hidden", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });
    
    var i = $('#jml').val();
    function samadenganqty(qtyset,i){
        // iih++;
        // alert(qtywip+ '-'+i);
        $('.qtyset'+i).val(qtyset);
    }
    
    function samadenganmark(erset,i){
        // i++;
        $('.erset'+i).val(erset);
    }

    $("#addrow").on("click", function () {
        //alert("tes");
        var ireferensi = $('#ireferensi').val();
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $('<tr id="tr'+i+'">');
        var cols   = "";
        // cols += `<td class="text-center">${i}</td>`;
        cols += `<td colspan="5"><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td>`;
        // cols += `<td><input class="form-control input-sm text-right " autocomplete="off" type="text" id="nquantityset${i}" name="nquantityset${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); samadenganqty(this.value,${i});"></td>`;
        // cols += `<td><select data-nourut="${i}" id="ialasan${i}" class="form-control input-sm" name="ialasan${i}" ></select></td>`;
        // cols += `<td><input class="form-control input-sm" type="text" id="eremarkset${i}" name="eremarkset${i}" value="" placeholder="Isi dengan Alasan Retur" onkeyup="samadenganmark(this.value,${i});"></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" onclick="hapusdetail(${i});" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        cols += `</tr>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct'+ i).select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>'+ireferensi,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q         : params.term,
                        ipartner  : $('#ipartner').val(),
                        ddocument : $('#ddocument').val(),
                    }
                    return query;
                },
                processResults: function (data) {
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
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#idproduct'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
                $.ajax({
                    type: "post",
                    data: {
                        'id'       : $(this).val(),
                    },
                    url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $("#tabledatax tbody").each(function() {
                            $("tr.del"+z).remove();
                        });
                        var xx = 0;
                        var netr = "";
                        /*for (let x = 0; x < data['detail'].length; x++) {*/
                        for (let x = data['detail'].length; x > 0 ; x--) {
                            var newRow1 = $('<tr class="del'+z+'">');
                            cols += '<td class="text-center">'+x+'</td>';
                            cols += `<td><input type="hidden" name="id_panel_item[]" id="id_panel_item_${data['detail'][xx]['id_product_wip']}_${x}" value="${data['detail'][xx]['id']}"><input type="hidden" name="idproductwip[]" value="${data['detail'][xx]['id_product_wip']}"><input type="hidden" id="nquantity_tmp_${data['detail'][xx]['id_product_wip']}_${x}" name="nquantity_tmp_${data['detail'][xx]['id_product_wip']}[]" value="0">`;
                            cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" value="'+data['detail'][xx]['id_material']+'">';
                            cols += '<input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_panel']+ ' - ' +data['detail'][xx]['bagian']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_material']+ ' - ' +data['detail'][xx]['e_material_name']+'"></td>';
                            // cols += '<td><input  class="form-control qtyset'+z+' input-sm" autocomplete="off" name="nquantityset1[]" id="nquantityset1[]"  value="">';
                            // cols += '<input  class="form-control erset'+z+' input-sm" name="eremarkset1[]" value="" placeholder="Isi dengan Alasan Retur">';
                            cols += `<td><input type="text" id="n_qty_retur_${data['detail'][xx]['id_product_wip']}_${x}" class="form-control text-right input-sm" autocomplete="off" name="n_so_bagus[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="hetang('${x}', '${data['detail'][xx]['id_product_wip']}');angkahungkul(this);"></td>`;
                            cols += '<td colspan="2"><input class="form-control input-sm" type="text" name="eremark[]" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                            newRow1.append(cols);
                            $('#n_qty_retur'+z).focus();
                            /*$("#tabledatax #tr"+z).insertAfter(newRow1);*/
                            $(newRow1).insertAfter("#tabledatax #tr"+z);
                            xx++;
                        }

                    },
                    error: function () {
                        swal('Data kosong : (');
                    }
                });
            }
        });

        $('#ialasan' + i).select2({
        placeholder: 'Pilih alasan',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/alasanretur'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q         : params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })
    });

    function hetang(x, idproductwip) {

        var n_max = 0;

        var n_qty_retur = parseFloat($('#n_qty_retur_'+idproductwip+'_'+x).val());
        var n_qty_penyusun = parseFloat($('#n_qty_penyusun_'+idproductwip+'_'+x).val());

        var n_quantity_akhir = n_qty_retur / n_qty_penyusun;

        if (isFinite(n_quantity_akhir)  ) {
            $('#nquantity_'+idproductwip+'_'+x).val(n_quantity_akhir);
            $('#nquantity_tmp_'+idproductwip+'_'+x).val(n_quantity_akhir);
        }else{
            $('#nquantity_'+idproductwip+'_'+x).val(0);
            $('#nquantity_tmp_'+idproductwip+'_'+x).val(0);
        }

        n_max = Math.ceil(Math.max.apply(Math,$("input[name='nquantity_tmp_"+idproductwip+"[]']").map(function(){return parseFloat($(this).val());}).get()));
        $('#n_qty_kekurangan_'+idproductwip+'_'+x).val(n_max * n_qty_penyusun - n_qty_retur );


        for(var i = 1; i<= $("input[name='nquantity_tmp_"+idproductwip+"[]']").length ; i++) {
            var n_qty_retur = parseFloat($('#n_qty_retur_'+idproductwip+'_'+i).val());
            var n_qty_penyusun = parseFloat($('#n_qty_penyusun_'+idproductwip+'_'+i).val());

            var n_quantity_akhir = n_qty_retur / n_qty_penyusun;

            if (isFinite(n_quantity_akhir)  ) {
                $('#nquantity_'+idproductwip+'_'+i).val(n_quantity_akhir);
                $('#nquantity_tmp_'+idproductwip+'_'+i).val(n_quantity_akhir);
            }else{
                $('#nquantity_'+idproductwip+'_'+i).val(0);
                $('#nquantity_tmp_'+idproductwip+'_'+i).val(0);
            }

            n_max = Math.ceil(Math.max.apply(Math,$("input[name='nquantity_tmp_"+idproductwip+"[]']").map(function(){return parseFloat($(this).val());}).get()));
            $('#n_qty_kekurangan_'+idproductwip+'_'+i).val(n_max * n_qty_penyusun - n_qty_retur );
        }

        console.log(n_max + " " + n_qty_penyusun + " " + n_qty_retur + " " + $("input[name='nquantity_tmp_"+idproductwip+"[]']").length );
    }

    /**
     * Hapus Detail Item
     */
    
    function hapusdetail(x) {
        $("#tabledatax tbody").each(function() {
            $("tr.del"+x).remove();
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
    });

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td nquantityset").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
        
    }
</script>