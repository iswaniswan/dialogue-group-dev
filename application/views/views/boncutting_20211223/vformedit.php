<link href="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
<style type="text/css">
    .font{
        font-size: 16px;
        background-color: #e1f1e4;
    }

    .tdna{
        font-size:16px; background-color: #ddd; font-weight: bold;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="formclose">
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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">No. Dokumen</label>
                        <label class="col-md-2">Tgl. Dokumen</label>
                        <label class="col-md-4">Dokumen Referensi</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>" <?php if ($key->i_bagian==$data->i_bagian) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option>
                                    <?php }
                                } ?> 
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
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
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                            <input type="hidden" name="dreferensi" id="dreferensi" class="form-control input-sm" value="<?= $tanggal;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireferensi[]" id="ireferensi" required="" multiple="multiple" class="form-control input-sm select2">
                                <?php if ($referensi) {
                                    foreach ($referensi->result() as $key) {?>
                                        <option value="<?= $key->id;?>" selected><?= $key->i_document;?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Tujuan</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>" <?php if ($key->i_bagian==$data->i_bagian_tujuan) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option>
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-9">
                            <textarea type="text" id= "eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
                    <span class="notekode"><b>Note : Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!</b></span>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($detail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-12">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Material</th>
                        <th class="text-center" style="width: 8%;">Gelar</th>
                        <th class="text-center" style="width: 8%;">Set</th>
                        <th class="text-center" style="width: 10%;">Jml Gelar</th>
                        <th class="text-center" style="width: 12%;">Jml Pemenuhan</th>
                        <th class="text-center" style="width: 12%;">Sisa</th>
                        <th class="text-center" style="width: 12%;">Jml Lembar</th>
                        <th class="text-center" style="width: 30%;">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0; $group = ''; foreach ($detail as $key) { $no++; ?>
                        <?php if($group==""){?>
                            <tr class="font">
                                <td colspan="5">
                                    <input type="text" readonly class="form-control input-sm" value="<?= $key->i_document.' - '.$key->i_product_wip.' - '.$key->e_product_wipname.' '.$key->e_color_name;?>"/>
                                </td>
                                <td class="text-right">
                                    <input readonly type = "text" class="form-control input-sm text-right" value="Jml WIP">
                                </td>
                                <td>
                                    <input type="text" name="npemenuhanwip" class="form-control text-right input-sm" maxlength="12" value="<?= $key->qtywip_pemenuhan;?>" readonly>
                                </td>
                                <td>
                                    <input type="text" name="nsisawip" class="form-control text-right input-sm" maxlength="12" value="<?= $key->qtysisawip;?>" readonly>
                                </td>
                                <td>
                                    <input type="text" name="qtywip" id="<?= $key->id_schedule.$key->id_product_wip;?>" onkeyup="angkahungkul(this); hetang(this.value,<?= $key->id_schedule.$key->id_product_wip;?>);" class="form-control text-right input-sm" maxlength="12" value="<?= $key->qtywip;?>">
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        <?php }else{ 
                            if($group!=$key->id_schedule.$key->id_product_wip){?>
                                <tr class="font">
                                    <td colspan="5">
                                        <input type="text" readonly class="form-control input-sm" value="<?= $key->i_document.' - '.$key->i_product_wip.' - '.$key->e_product_wipname.' '.$key->e_color_name;?>"/>
                                    </td>
                                    <td class="text-right">
                                        <input readonly type = "text" class="form-control input-sm text-right" value="Jml WIP">
                                    </td>
                                    <td>
                                        <input type="text" name="npemenuhanwip" class="form-control text-right input-sm" maxlength="12" value="<?= $key->qtywip_pemenuhan;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="nsisawip" class="form-control text-right input-sm" maxlength="12" value="<?= $key->qtysisawip;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="qtywip" id="<?= $key->id_schedule.$key->id_product_wip;?>" onkeyup="angkahungkul(this); hetang(this.value,<?= $key->id_schedule.$key->id_product_wip;?>);" class="form-control text-right input-sm" maxlength="12" value="<?= $key->qtywip;?>">
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <?php $no = 1;}
                            }?>
                            <?php $group = $key->id_schedule.$key->id_product_wip; ?>
                            <tr>
                                <td class="text-center"><?= $no;?></td>
                                <td>
                                    <input type="hidden" id="idproduct<?= $i ;?>" name="idproduct<?= $i ;?>" value="<?= $key->id_product_wip;?>">
                                    <input type="hidden" id="idmaterial<?= $i ;?>" name="idmaterial<?= $i ;?>" value="<?= $key->id_material;?>">
                                    <input class="form-control input-sm" readonly type="text" id="imaterial<?= $i ;?>" name="imaterial<?= $i ;?>" value="<?= $key->i_material;?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm" readonly type="text" id="ematerialname<?= $i ;?>" name="ematerialname<?= $i ;?>" value="<?= $key->e_material_name;?>">
                                </td>
                                <td>
                                    <input readonly class="form-control input-sm text-right" type="text" id="gelar<?= $i ;?>" name="gelar<?= $i ;?>" value="<?= $key->n_gelar;?>">
                                </td>
                                <td>
                                    <input readonly class="form-control input-sm text-right" type="text" id="set<?= $i ;?>" name="set<?= $i ;?>" value="<?= $key->n_set;?>">
                                </td>
                                <td>
                                    <input readonly class="form-control input-sm text-right" type="text" id="jmlgelar<?= $i ;?>" name="jmlgelar<?= $i ;?>" value="<?= number_format($key->n_jumlah_gelar,2);?>">
                                </td>
                                <td>
                                    <input readonly class="form-control input-sm text-right" type="text" id="npemenuhanma<?= $i ;?>" name="npemenuhanma<?= $i ;?>" value="<?= $key->qtyma_pemenuhan;?>">
                                </td>
                                <td>
                                    <input readonly class="form-control input-sm text-right" type="text" id="nsisama<?= $i ;?>" name="nsisama<?= $i ;?>" value="<?= $key->qtysisama;?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm text-right" type="text" id="jmllembar<?= $i ;?>" name="jmllembar<?= $i ;?>" placeholder="0" autocomplete="off" onkeyup="angkahungkul(this); cekjml(<?= $i ;?>);" value="<?= $key->qtyma;?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm" type="text" id="eremark<?= $i ;?>" name="eremark<?= $i ;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!">
                                    <input type="hidden" id="idschedule<?= $i ;?>" name="idschedule<?= $i ;?>" value="<?= $key->id_schedule;?>">
                                    <input type="hidden" id="qty<?= $i ;?>" name="qty<?= $i ;?>" value="<?= $key->qtysisawip;?>">
                                    <input type="hidden" id="qtysc<?= $i ;?>" name="qtysc<?= $i ;?>" value="<?= $key->qtysisawip;?>">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="ceklis<?= $i ;?>" id="ceklis<?= $i ;?>" checked class="swit<?= $i ;?>"/>
                                </td>
                            </tr>
                            <?php $i++; 
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
</form>
<script src="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#idocument').val($('#idocumentold').val());
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
                    $('#idocument').val(data);
                },
                error: function () {
                    swal('Error :(');
                }
            });
        }
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
        var d1 = splitdate($('#ddocument').val());
        var d2 = splitdate($('#dreferensi').val());
        if ((d1!=null || d1!='') && (d2!=null || d2!='')) {
            if (d1<d2) {
                swal('Maaf','Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!!!','error');
                $('#ddocument').val($('#dreferensi').val());
                return false;
            }
        }else{
            swal('Maaf','Tanggal Dokumen Tidak Boleh Kosong!!!','error');
            return false;
        }

        if ($("#formclose input:checkbox:checked").length > 0){
            if ($('#ireferensi').val()!='' || $('#ireferensi').val()!=null) {
                if($('#jml').val() == 0){
                    swal('Isi data item minimal 1 !!!');
                    return false;
                }else{
                    for (var i = 0; i < $('#jml').val(); i++) {
                        if($("#jmllembar"+i).val()=='' || $("#jmllembar"+i).val()==null){
                            swal('Maaf :(','Jumlah Lembar Tidak Boleh Kosong!','error');
                            return false;
                        }
                    }
                }
            }else{
                swal('Maaf :(','Referensi Tidak Boleh Kosong!!!','error');
                return false;
            }
            swal({
                title: "Update Data Ini?",   
                text: "Anda Dapat Membatalkannya Nanti",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonColor: 'LightSeaGreen',
                confirmButtonText: "Ya, Update!",   
                closeOnConfirm: false 
            }, function(){
                $.ajax({
                    type: "POST",
                    data: $( "form" ).serialize(),
                    url: '<?= base_url($folder.'/cform/update/'); ?>',
                    dataType: "json",
                    success: function (data) {
                        if (data.sukses==true) {                                
                            swal("Sukses!", "No Dokumen : "+data.kode+", Berhasil Diupdate :)", "success"); 
                            $("input").attr("disabled", true);
                            $("select").attr("disabled", true);
                            $("#submit").attr("disabled", true);
                            $("#addrow").attr("disabled", true);
                            $("#send").attr("hidden", false);
                        }else{
                            swal("Maaf", "Data Gagal Diupdate :(", "error");    
                        }
                    },
                    error: function () {
                        swal("Maaf", "Data Gagal Diupdate :(", "error");
                    }
                });
            });
        }else{
            swal('Maaf :(','Salah satu item harus dipilih!','error');
            return false;
        }
    });

    $(document).ready(function () {
        $('#idocument').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date',0);

        for (var i = 0; i < $('#jml').val(); i++) {
            $('.swit'+i).swit(i);
        }
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
                        $('#jml').val(data['detail'].length);
                        var group = '';
                        var no = 0;
                        for (let x = 0; x < data['detail'].length; x++) {
                            no++;
                            var cols        = "";
                            var cols1       = "";
                            var newRow      = $("<tr class='font'>");
                            if(group==""){
                                cols1 += '<td colspan="5"><input type="text" readonly class="form-control input-sm" value="'+data['detail'][x]['i_document']+' - '+data['detail'][x]['i_product_wip']+' - '+data['detail'][x]['e_product_wipname']+' '+data['detail'][x]['e_color_name']+'"/></td>';
                                cols1 += '<td text-right"><input readonly type = "text" class="form-control input-sm text-right" value="Jml WIP"></td>';
                                cols1 += '<td><input type="text" name="qtywip" id="'+data['detail'][x]['id_schedule']+data['detail'][x]['id_product_wip']+'" onkeyup="angkahungkul(this); hetang(this.value,'+data['detail'][x]['id_schedule']+data['detail'][x]['id_product_wip']+');" class="form-control input-sm text-right" maxlength="12" value="'+data['detail'][x]['qty']+'"></td>';
                                cols1 += '<td colspan="2"></td>';
                            }else{
                                if(group!=data['detail'][x]['id_schedule']+data['detail'][x]['id_product_wip']){
                                    cols1 += '<td colspan="5"><input type="text" readonly class="form-control input-sm" value="'+data['detail'][x]['i_document']+' - '+data['detail'][x]['i_product_wip']+' - '+data['detail'][x]['e_product_wipname']+' '+data['detail'][x]['e_color_name']+'"/></td>';
                                    cols1 += '<td text-right"><input readonly type = "text" class="form-control input-sm text-right" value="Jml WIP"></td>';
                                    cols1 += '<td><input type="text" name="qtywip" id="'+data['detail'][x]['id_schedule']+data['detail'][x]['id_product_wip']+'" onkeyup="angkahungkul(this); hetang(this.value,'+data['detail'][x]['id_schedule']+data['detail'][x]['id_product_wip']+');" class="form-control input-sm text-right" maxlength="12" value="'+data['detail'][x]['qty']+'"></td>';
                                    cols1 += '<td colspan="2"></td>';
                                no = 1; }
                            }
                            newRow.append(cols1);
                            $("#tabledatax").append(newRow);
                            group = data['detail'][x]['id_schedule']+data['detail'][x]['id_product_wip'];
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">'+(no)+'</td>';
                            cols += '<td><input type="hidden" id="idproduct'+x+'" name="idproduct'+x+'" value="'+data['detail'][x]['id_product_wip']+'">';
                            cols += '<input type="hidden" id="idmaterial'+x+'" name="idmaterial'+x+'" value="'+data['detail'][x]['id_material']+'">';
                            cols += '<input class="form-control input-sm" readonly type="text" id="imaterial'+x+'" name="imaterial'+x+'" value="'+data['detail'][x]['i_material']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="ematerialname'+x+'" name="ematerialname'+x+'" value="'+data['detail'][x]['e_material_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="gelar'+x+'" name="gelar'+x+'" value="'+data['detail'][x]['n_gelar']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="set'+x+'" name="set'+x+'" value="'+data['detail'][x]['n_set']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="jmlgelar'+x+'" name="jmlgelar'+x+'" value="'+data['detail'][x]['n_jumlah_gelar']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="jmllembar'+x+'" name="jmllembar'+x+'" onkeyup="angkahungkul(this); cekjml('+x+');" value="'+data['detail'][x]['qty']+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>';
                            cols += '<td><input class="form-control input-sm" type="text" id="eremark'+x+'" name="eremark'+x+'" value="" placeholder="Isi keterangan jika ada!">';
                            cols += '<td class="text-center"><input type="checkbox" name="ceklis'+x+'" id="ceklis'+x+'" class="swit'+x+'"/></td>';
                            cols += '<input type="hidden" id="idschedule'+x+'" name="idschedule'+x+'" value="'+data['detail'][x]['id_schedule']+'">';
                            cols += '<input type="hidden" id="qty'+x+'" name="qty'+x+'" value="'+data['detail'][x]['qty']+'">';
                            cols += '<input type="hidden" id="qtysc'+x+'" name="qtysc'+x+'" value="'+data['detail'][x]['qty']+'"></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                            $('.swit'+x).swit(x);
                        }
                    }
                },
                error: function () {
                    swal('Data kosong : (');
                }
            });
        })
    });

    function cekjml(i) {
        if (parseInt($('#jmllembar'+i).val()) > parseInt($('#qty'+i).val())) {
            swal('Maaf :(','Jumlah lembar tidak boleh lebih dari jumlah wip!','error');
            $('#jmllembar'+i).val($('#qty'+i).val());
        }
        var jmllembar = parseInt($('#jmllembar'+i).val()) / parseInt($('#set'+i).val());
        $('#jmlgelar'+i).val(jmllembar.toFixed(2));
    }

    function hetang(qty,idwip){
        for(var i = 0; i < $('#jml').val(); i++){
            if(idwip == $("#idschedule"+i).val()+$("#idproduct"+i).val()){           
                if(qty==''){
                    qty = 0;
                }

                $('#qty'+i).val(qty);
                $('#jmllembar'+i).val(qty);
                if (parseInt($('#jmllembar'+i).val()) > parseInt($('#qtysc'+i).val())) {
                    swal('Maaf :(','Jumlah WIP tidak boleh lebih dari jumlah Schedule!','error');
                    $('#jmllembar'+i).val($('#qtysc'+i).val());
                    $('#qty'+i).val($('#qtysc'+i).val());
                    $('#'+idwip).val($('#qtysc'+i).val());
                }

                var jmllembar = parseInt($('#jmllembar'+i).val()) / parseInt($('#set'+i).val());
                $('#jmlgelar'+i).val(jmllembar.toFixed(2));
            }
        }
    }

    /*$("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });*/
</script>