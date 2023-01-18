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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Partner</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>" <?php if ($key->i_bagian == $data->i_bagian) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                            <input type="hidden" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" id="id" name="id" value="<?= $data->id;?>">
                                <input type="hidden" id="isjold" value="<?= $data->i_document;?>">  
                                <input type="text" name="idocument" required="" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
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
                        <div class="col-sm-4">
                            <select name="ipartner" required="" id="ipartner" class="form-control select2">
                                <option value="<?= $data->id_partner.'|'.$data->e_partner_type;?>"><?= $data->e_partner_name;?></option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Dokumen Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <label class="col-md-7">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" required="" class="form-control input-sm select2">
                                <option value="<?= $data->id_document_reff.'|'.$data->e_type_reff;?>"><?= $data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreff" required="" id="dreff" class="form-control input-sm" placeholder="Tanggal Ref" readonly value="<?= $data->d_referensi;?>">
                        </div>
                        <div class="col-sm-7">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
                    <span class="notekode"><b>Note : Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!</b></span>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-12">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="9%">Kode</th>
                        <th class="text-center" width="30%">Nama Barang</th>
                        <th class="text-center" width="10%">Satuan</th>
                        <th class="text-center" width="8%">Jml</th>
                        <th class="text-center" width="10%">Jml Sisa</th>
                        <th class="text-center" width="10%">Jml Kirim</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) {
                        ?>
                        <tr>
                            <td class="text-center"><?=$i+1;?></td>
                            <td>
                                <input class="form-control input-sm" readonly type="text" value="<?= $key->i_material;?>">
                                <input type="hidden" name="idproduct<?=$i;?>" value="<?= $key->id_material;?>">
                            </td>
                            <td><input class="form-control input-sm" readonly type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>" value="<?= $key->e_material_name;?>"></td>
                            <td><input readonly class="form-control input-sm" type="text" value="<?= $key->e_satuan_name;?>"></td>
                            <td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua<?=$i;?>" value="<?= $key->n_quantity_reff;?>"></td>
                            <td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa<?=$i;?>" value="<?= $key->n_quantity_sisa_reff;?>"></td>
                            <td><input class="form-control input-sm text-right" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $key->n_quantity;?>" placeholder="0" onkeyup="angkahungkul(this); cek(<?=$i;?>);"></td>
                            <td><input class="form-control input-sm" placeholder="Isi keterangan jika ada!" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $key->e_remark;?>"></td>
                        </tr>
                    <?php $i++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    
    $(document).ready(function () {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);

        $('#ipartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
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
            $('#ireff').val('');
            $('#dreff').val('');
            $('#ireff').html('');
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#ireff').select2({
            placeholder: 'Cari Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ipartner  : $('#ipartner').val(),
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
        }).change(function() {

            /*----------  GET DATA DETAIL AFTER CHANGE REFERENSI  ----------*/
            
            $("#tabledatax").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id' : $(this).val(),
                    'ipartner' : $('#ipartner').val(),
                },
                url: '<?= base_url($folder.'/cform/detailreferensi'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['detail']!=null) {
                        $('#dreff').val(data['detail'][0]['d_document']);
                        $('#jml').val(data['detail'].length);
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols   = "";
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">'+(x+1)+'</td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][x]['i_material']+'"><input type="hidden" name="idproduct'+x+'" value="'+data['detail'][x]['id_material']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][x]['e_material_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" value="'+data['detail'][x]['e_satuan_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua'+x+'" value="'+data['detail'][x]['n_quantity']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="text" id="nquantity'+x+'" name="nquantity'+x+'" autocomplete="off" value="'+data['detail'][x]['n_quantity_sisa']+'" placeholder="0" onkeyup="angkahungkul(this); cek('+x+')"></td>';
                            cols += '<td><input type="text" class="form-control input-sm" placeholder="Isi keterangan jika ada!" name="eremark'+x+'"></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                    }
                },
                error: function () {
                    swal('Ada kesalahan :(');
                }
            })
        });
    });

    /*----------  CEK JML TIDAK BOLEH LEBIH  ----------*/
    
    function cek(i) {
        if (parseFloat($('#nquantitysisa'+i).val()) < parseFloat($('#nquantity'+i).val())) {
            swal('Maaf :(', 'Jumlah Kirim Tidak Boleh Lebih Besar Dari Sisa = '+$('#nquantitysisa'+i).val()+' !', 'error');
            $('#nquantity'+i).val($('#nquantitysisa'+i).val());
        }
    }

    /*----------  NOMOR DOKUMEN  ----------*/    

    function number() {
        if ($('#ibagian').val() == $('#ibagianold').val()) {
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
                    swal('Error :(');
                }
            });
        }
    }

    /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/
    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#isj").attr("readonly", false);
        }else{
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/    

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
                swal('Error :(');
            }
        });
    });

    /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/    

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*----------  UPDATE NO DOKUMEN SAAT BAGIAN PEMBUAT DAN TANGGAL DOKUMEN DIRUBAH  ----------*/
    
    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
    
    $('#submit').click(function(event) {
        var d1 = splitdate($('#ddocument').val());
        var d2 = splitdate($('#dreff').val());
        if ((d1!=null || d1!='') && (d2!=null || d2!='')) {
            if (d1<d2) {
                swal('Maaf','Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!!!','error');
                $('#ddocument').val($('#dreff').val());
                return false;
            }
        }else{
            swal('Maaf','Tanggal Dokumen Tidak Boleh Kosong!!!','error');
            return false;
        }
        if($("#jml").val()==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            for (var i = 0; i < $("#jml").val(); i++) {
                if($("#nquantity"+i).val()=='' || $("#nquantity"+i).val()==null || $("#nquantity"+i).val()==0){
                    swal('Maaf','Jumlah Kirim Harus Lebih Besar Dari 0!','error');
                    return false;
                }
            }
        }
    });

    /*----------  KONDISI SETELAH MENEKAN TOMBOL SIMPAN  ----------*/    

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });
</script>