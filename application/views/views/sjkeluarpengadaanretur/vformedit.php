<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

function samadenganqtyx(qtywip,xi){
    // iih++;
    // alert(qtywip+ '-'+i);
    $('.qtywip'+xi).val(qtywip);
}

function samadenganmarkx(erwip,xi){
    // i++;
    $('.erwip'+xi).val(erwip);
}
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen Retur</label>
                        <label class="col-md-2">Tanggal Dokumen Retur</label>
                        <label class="col-md-4">Partner</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value="<?= $data->id_supplier;?>"><?= $data->e_supplier_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Alasan Retur</label>
                        <div class="col-sm-12">
                            <textarea type="text" name="eremarkh" placeholder="Isi dengan Alasan Retur" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" > <i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang Retur</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 45%;">Nama Barang</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Alasan Retur</th>
                        <th class="text-center" style="width: 5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $z = 0; $group = ""; foreach ($datadetail as $key) { $i++; 
                        if($group!=$key->id_product_wip){
                            $z++;
                        }
                        ?>
                            <?php if($group==""){?>
                                <tr id="tr<?= $z;?>">
                                    <td colspan="3">
                                        <select data-nourut="<?= $z ;?>" id="idproduct<?= $z ;?>" class="form-control input-sm" name="idproduct<?= $z ;?>" >
                                            <option value="<?= $key->id_product_wip;?>"><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" type="text" id="nquantitywip<?= $z ;?>" name="nquantitywip<?= $z ;?>" value="<?= $key->n_quantity_wip;?>"  onkeyup="samadenganqtyx(this.value,<?= $z ;?>);"> 
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" type="text" id="eremarkwip<?= $z ;?>" name="eremarkwip<?= $z ;?>" value="<?= $key->e_remark_wip;?>" placeholder="Isi dengan Alasan Retur" onkeyup="samadenganmarkx(this.value,<?= $z ;?>);"> 
                                    </td>
                                    <td class="text-center">
                                        <button type="button" title="Delete" onclick="hapusdetail(<?= $z;?>);" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                    </td>
                                </tr>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <tr id="tr<?= $z;?>">
                                        <td colspan="3">
                                            <select data-nourut="<?= $z ;?>" id="idproduct<?= $z ;?>" class="form-control input-sm" name="idproduct<?= $z ;?>" >
                                                <option value="<?= $key->id_product_wip;?>"><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></option>
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control input-sm" type="text" id="nquantitywip<?= $z ;?>" name="nquantitywip<?= $z ;?>" value="<?= $key->n_quantity_wip;?>"  onkeyup="samadenganqtyx(this.value,<?= $z ;?>);"> 
                                        </td>
                                        <td>
                                            <input class="form-control input-sm" type="text" id="eremarkwip<?= $z ;?>" name="eremarkwip<?= $z ;?>" value="<?= $key->e_remark_wip;?>" placeholder="Isi dengan Alasan Retur" onkeyup="samadenganmarkx(this.value,<?= $z ;?>);"> 
                                        </td>
                                        <td class="text-center">
                                            <button type="button" title="Delete" onclick="hapusdetail(<?= $z;?>);" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                        </td>
                                    </tr>
                                <?php $i = 1;}
                            }?>
                        <?php $group = $key->id_product_wip; ?>
                        <tr class="del<?= $z;?>">
                            <td class="text-center"><?= $i ;?></td>
                            <td>
                                <input type="hidden" name="idproductwip[]" value="<?= $key->id_product_wip;?>">
                                <input type="hidden" class="idmaterial" name="idmaterial[]" value="<?= $key->id_material;?>">
                                <input class="form-control input-sm" readonly type="text" value="<?= $key->i_material;?>">
                            </td>
                            <td>
                                <input class="form-control input-sm" readonly type="text" value="<?= $key->e_material_name;?>">
                            </td>
                            <td>
                                <input type="hidden" class="form-control qtywip<?= $z ;?>  input-sm" autocomplete="off" name="nquantitywip1[]" id="nquantitywip1[]"  value="<?=$key->n_quantity_wip;?>" onkeyup="angkahungkul(this);">
                                <input type="hidden" class="form-control erwip<?= $z ;?> input-sm" name="eremarkwip1[]" value="<?=$key->e_remark_wip;?>" placeholder="Isi dengan Alasan Retur"/>
                                <input type="text" class="form-control qty text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity;?>" onkeyup="angkahungkul(this);">
                            </td>
                            <td colspan="2"><input type="text" class="form-control input-sm" name="eremark[]" value="<?= $key->e_remark;?>" placeholder="Isi dengan Alasan Retur"/></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $z ;?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /**
     * Load Saat Document Ready
     */

    $(document).ready(function () {
        $('#isj').mask('SSS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.date', 1830, 0);
        
        for (var i = 1; i <= $('#jml').val(); i++) {

            function samadenganqtyx(qtywip,xi){
                // iih++;
                // alert(qtywip+ '-'+i);
                $('.qtywip'+xi).val(qtywip);
            }
            
            function samadenganmarkx(erwip,xi){
                // i++;
                $('.erwip'+xi).val(erwip);
            }

            $('#idproduct'+ i).select2({
                placeholder: 'Cari Kode / Nama WIP',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder.'/cform/product/'); ?>',
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
                 * Get Detail Barang
                 */
                var z = $(this).data('nourut');
                var ada = true;
                for(var y = 1; y <= $('#jml').val(); y++){
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#idproduct'+y).val()) && (z!=y)){
                            swal ("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {                
                    $(this).val('');
                    $(this).html('');
                    $("#tabledatax tbody").each(function() {
                        $("tr.del"+z).remove();
                    });
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
                            var cols1   = "";
                            for (let x = data['detail'].length; x > 0 ; x--) {
                                var newRow1 = $('<tr class="del'+z+'">');
                                cols1 += '<td class="text-center">'+x+'</td>';
                                cols1 += '<td><input type="hidden" name="idproductwip[]" value="'+data['detail'][xx]['id_product_wip']+'">';
                                cols1 += '<input type="hidden" class="idmaterial" name="idmaterial[]" value="'+data['detail'][xx]['id_material']+'">';
                                cols1 += '<input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_material']+'"></td>';
                                cols1 += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['e_material_name']+'"></td>';
                                cols1 += '<td><input type="hidden" class="form-control qtywip'+z+' input-sm" autocomplete="off" name="nquantitywip1[]" id="nquantitywip1[]"  value="">';
                                cols1 += '<input type="hidden" class="form-control erwip'+z+' input-sm" name="eremarkwip1[]" value="" placeholder="Isi dengan Alasan Retur">';
                                cols1 += '<input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
                                cols1 += '<td colspan="2"><input class="form-control input-sm" type="text" name="eremark[]" value="" placeholder="Isi dengan Alasan Retur"></td></tr>';
                                newRow1.append(cols1);
                                $('#nquantity'+z).focus();
                                /*$("#tabledatax #tr"+z).insertAfter(newRow1);*/
                                $(newRow1).insertAfter("#tabledatax #tr"+z);
                                xx ++;
                            }
                        },
                        error: function () {
                            swal('Data kosong : (');
                        }
                    });
                }
            });
        }

        $('#ipartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    $( "#ddocument" ).change(function() {
        number();
    });

    /**
     * Cek Kode Sudah Ada
     */

    $( "#isj" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#isj').val() != $('#isjold').val())) {
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

    /**
     * Input Kode Manual
     */

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#isj").attr("readonly", false);
        }else{
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $('#isj').val($('#isjold').val());
        }
    });

    /**
     * Running Number
     */

    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#isj').val($('#isjold').val());
        }else{
            $.ajax({
                type: "post",
                data: {
                    'tgl' : $('#ddocument').val(),
                    'ibagian' : $('#ibagian').val(),
                },
                url: '<?= base_url($folder.'/cform/number'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#isj').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }
    }

    /**
     * Tambah Item
     */

    var i = $('#jml').val();
    // iih++;
    
    function samadenganqty(qtywip,i){
        // iih++;
        // alert(qtywip+ '-'+i);
        $('.qtywip'+i).val(qtywip);
    }
    
    function samadenganmark(erwip,i){
        // i++;
        $('.erwip'+i).val(erwip);
    }

    $("#addrow").on("click", function () {
        // alert(i);
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $('<tr id="tr'+i+'">');
        var cols   = "";
        cols += `<td colspan="3"><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td>`;
        cols += `<td><input class="form-control input-sm text-right " autocomplete="off" type="text" id="nquantitywip${i}" name="nquantitywip${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); samadenganqty(this.value,${i});"></td>`;
        cols += `<td><input class="form-control input-sm" type="text" id="eremarkwip${i}" name="eremarkwip${i}" value="" placeholder="Isi dengan Alasan Retur" onkeyup="samadenganmark(this.value,${i});"></td>`;
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
                url: '<?= base_url($folder.'/cform/product/'); ?>',
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
             * Get Detail Barang
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
                $("#tabledatax tbody").each(function() {
                    $("tr.del"+z).remove();
                });
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
                            cols += '<td><input type="hidden" name="idproductwip[]" value="'+data['detail'][xx]['id_product_wip']+'">';
                            cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" value="'+data['detail'][xx]['id_material']+'">';
                            cols += '<input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_material']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['e_material_name']+'"></td>';
                            cols += '<td><input type="hidden" class="form-control qtywip'+z+' input-sm" autocomplete="off" name="nquantitywip1[]" id="nquantitywip1[]"  value="">';
                            cols += '<input type="hidden" class="form-control erwip'+z+' input-sm" name="eremarkwip1[]" value="" placeholder="Isi dengan Alasan Retur">';
                            cols += '<input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
                            cols += '<td colspan="2"><input class="form-control input-sm" type="text" name="eremark[]" value="" placeholder="Isi dengan Alasan Retur"></td></tr>';
                            newRow1.append(cols);
                            $('#nquantity'+z).focus();
                            /*$("#tabledatax #tr"+z).insertAfter(newRow1);*/
                            $(newRow1).insertAfter("#tabledatax #tr"+z);
                            xx ++;
                        }
                    },
                    error: function () {
                        swal('Data kosong : (');
                    }
                });
            }
        });
    });

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
        $('#jml').val(i);
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });

    /**
     * Validasi Simpan Data
     */

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang wip tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .idmaterial").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang material tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
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
</script>