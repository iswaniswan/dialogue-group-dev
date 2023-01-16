<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <div class="col-sm-2">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>"<?php if ($key->i_bagian==$data->i_bagian) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option>
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2">
                                <option value="<?= $data->id_reff;?>"><?= $data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreferensi" id="dreferensi" class="form-control input-sm" value="<?= $data->d_referensi;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Terima Dari</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="ipengirim" id="ipengirim" value="<?= $data->i_bagian_pengirim;?>" readonly>
                            <input type="text" name="epengirim" id="epengirim" class="form-control input-sm" value="<?= $data->e_bagian_name_pengirim;?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea type="text" id= "eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
<?php $i = 0; if ($detail) {?>
    <div class="white-box">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Material</th>
                        <th class="text-center" style="width: 10%;">Satuan</th>
                        <th class="text-center" style="width: 10%;">Schedule Cutting</th>
                        <th class="text-center" style="width: 8%;">Quantity Kirim</th>
                        <th class="text-center" style="width: 9%;">Quantity Terima</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $group = ''; foreach ($detail as $key) {?>
                        <tr>
                            <?php if($group==""){?>
                                <td style="font-size:16px; background-color: #ddd;" colspan = "8"><b><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></b></td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <td style="font-size:16px; background-color: #ddd;" colspan = "8"><b><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></b></td>
                                <?php }
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                        <tr>
                            <td class="text-center"><?= $i+1;?></td>
                            <td>
                                <input type="hidden" id="id_reff_item<?= $i ;?>" name="id_reff_item<?= $i ;?>" value="<?= $key->id_reff_item;?>">
                                <input type="hidden" id="idproduct<?= $i ;?>" name="idproduct<?= $i ;?>" value="<?= $key->id_product_wip;?>">
                                <input type="hidden" id="idmaterial<?= $i ;?>" name="idmaterial<?= $i ;?>" value="<?= $key->id_material;?>">
                                <input class="form-control input-sm" readonly type="text" id="imaterial<?= $i ;?>" name="imaterial<?= $i ;?>" value="<?= $key->i_material;?>">
                            </td>
                            <td>
                                <input class="form-control input-sm" readonly type="text" id="ematerialname<?= $i ;?>" name="ematerialname<?= $i ;?>" value="<?= $key->e_material_name;?>">
                            </td>
                            <td>
                                <input readonly class="form-control input-sm" type="text" id="satuan<?= $i ;?>" name="satuan<?= $i ;?>" value="<?= $key->e_satuan_name;?>">
                            </td>
                            <td>
                                <input readonly class="form-control input-sm text-right" type="text" id="d_schedule<?= $i ;?>" name="d_schedule<?= $i ;?>" value="<?= $key->d_schedule;?>">
                            </td>
                            <td>
                                <input class="form-control input-sm text-right" type="text" id="nsisa<?= $i ;?>" name="nsisa<?= $i ;?>" value="<?= $key->n_quantity_sisa;?>" readonly>
                            </td>
                            <td>
                                <input class="form-control input-sm text-right" type="text" id="npemenuhan<?= $i ;?>" name="npemenuhan<?= $i ;?>" placeholder="0" onkeyup="angkahungkul(this); cekvalidasi(<?=$i;?>)" value="<?= $key->n_quantity;?>">
                            </td>
                            <td>
                                <input class="form-control input-sm" type="text" id="eremark<?= $i ;?>" name="eremark<?= $i ;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!">
                            </td>
                        </tr>
                        <?php $i++; 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#ddocument').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :(');
            }
        });
    }

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
                if (data==1 && ($('#idocument').val() != $('#idocumentold').val())) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :(');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#idocument").val($("#idocumentold").val())
        }
    });

    $('#ibagian').change(function(event) {
        number();
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

    $('#submit').click(function(event) {
        if ($('#ireferensi').val()!='' || $('#ireferensi').val()!=null) {
            if($('#jml').val() == 0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for (var i = 0; i < $('#jml').val(); i++) {
                    if($("#npemenuhan"+i).val()=='' || $("#npemenuhan"+i).val()==null || $("#npemenuhan"+i).val()==0){
                        swal('Jumlah Terima Harus Lebih Besar Dari 0!');
                        return false;
                    }
                }
            }
        }else{
            swal('Referensi Tidak Boleh Kosong!!!');
            return false;
        }
    });

    $(document).ready(function () {
        max_tgl();
        $('#idocument').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date',0);

        $('#ireferensi').select2({
            placeholder: 'Cari No Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        ibagian : $('#ibagian').val(),
                        ddocument : $('#ddocument').val(),
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
        }).change(function() {
            $("#tabledatax").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id': $(this).val()
                },
                url: '<?= base_url($folder.'/cform/referensidetail'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['head']!=null && data['detail']!=null) {
                        $('#dreferensi').val(data['head']['d_document']);
                        $('#ipengirim').val(data['head']['i_bagian']);
                        $('#epengirim').val(data['head']['e_bagian_name']);
                        $('#jml').val(data['detail'].length);
                        var group = '';
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols        = "";
                            var cols1       = "";
                            var newRow      = $("<tr>");
                            if(group==""){
                                cols1 += '<td style="font-size:16px; background-color: #ddd;" colspan = "8"><b>'+data['detail'][x]['i_product_wip']+' - '+data['detail'][x]['e_product_wipname']+' '+data['detail'][x]['e_color_name']+'</b></td>';
                            }else{
                                if(group!=data['detail'][x]['id_product_wip']){
                                    cols1 += '<td style="font-size:16px; background-color: #ddd;" colspan = "8"><b>'+data['detail'][x]['i_product_wip']+' - '+data['detail'][x]['e_product_wipname']+' '+data['detail'][x]['e_color_name']+'</b></td>';
                                }
                            }
                            newRow.append(cols1);
                            $("#tabledatax").append(newRow);
                            group = data['detail'][x]['id_product_wip'];
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">'+(x+1)+'</td>';
                            cols += '<td><input type="hidden" id="id_reff_item'+x+'" name="id_reff_item'+x+'" value="'+data['detail'][x]['id']+'"><input type="hidden" id="idproduct'+x+'" name="idproduct'+x+'" value="'+data['detail'][x]['id_product_wip']+'">';
                            cols += '<input type="hidden" id="idmaterial'+x+'" name="idmaterial'+x+'" value="'+data['detail'][x]['id_material']+'">';
                            cols += '<input class="form-control input-sm" readonly type="text" id="imaterial'+x+'" name="imaterial'+x+'" value="'+data['detail'][x]['i_material']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="ematerialname'+x+'" name="ematerialname'+x+'" value="'+data['detail'][x]['e_material_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="satuan'+x+'" name="satuan'+x+'" value="'+data['detail'][x]['e_satuan_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="d_schedule'+x+'" name="d_schedule'+x+'" value="'+data['detail'][x]['d_schedule']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nsisa'+x+'" name="nsisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="text" id="npemenuhan'+x+'" name="npemenuhan'+x+'" placeholder="0" onkeyup="angkahungkul(this); cekvalidasi('+x+')" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                            cols += '<td><input class="form-control input-sm" type="text" id="eremark'+x+'" name="eremark'+x+'" value="" placeholder="Isi keterangan jika ada!"></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                    }
                    max_tgl();
                },
                error: function () {
                    swal('Data kosong : (');
                }
            });
        })
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        // $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    function cekvalidasi(i){
        nquantity = $("#npemenuhan"+i).val();
        nsisa     = $("#nsisa"+i).val();
        //alert("cek");
        if(parseFloat(nquantity)>parseFloat(nsisa)){
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Sisa');
            $("#npemenuhan"+i).val(nsisa);
        }
        if(parseFloat(nquantity) == '0' || parseFloat(nquantity) == '' || parseFloat(nquantity) == null){
            swal('Jumlah Masuk Tidak Boleh Kosong atau 0');
            $("#npemenuhan"+i).val(nsisa);
        }
    }

    function max_tgl(val) {
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
</script>