<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Jenis SPB</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian;?>">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document;?>">
                                <input type="text" name="isj" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control date"  required="" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                 <option value="<?=$data->id_type_spb;?>"><?=$data->e_type_name;?></option>
                            </select>
                        </div>                           
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>  
                        <label class="col-md-3">Customer</label>  
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>   
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2">
                                <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                            </select>
                        </div>  
                        <div class="col-sm-3">
                            <select name="icustomer" id="icustomer" class="form-control select2">
                                <option value="<?=$data->id_customer;?>"><?=$data->e_customer_name;?></option>
                            </select>
                            <input type="hidden" id="ncustop" name="ncustop" class="form-control" value="<?=$data->n_customer_toplength;?>" readonly>
                        </div>  
                        <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2">
                                <option value="<?=$data->id_document_reff;?>"><?=$data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dreferensi" name="dreferensi" class="form-control" value="<?= $data->d_referensi; ?>" readonly onchange="return maxi();">
                        </div>                                
                    </div>  
                    <div class="form-group row"> 
                        <label class="col-md-12">Keterangan</label>  
                        <div class="col-sm-12">
                           <textarea id="eremark" name="eremark" class="form-control"><?= $data->e_remark; ?></textarea>
                        </div> 
                    </div>                
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 3%;">No</th>
                        <th style="text-align: center; width: 15%;">Kode Barang</th>
                        <th style="text-align: center; width: 25%;">Nama barang</th>
                        <th style="text-align: center; width: 10%;">Saldo</th>
                        <th style="text-align: center; width: 10%;">Qty Permintaan</th>
                        <th style="text-align: center; width: 10%;">Qty Belum Terpenuhi</th>
                        <th style="text-align: center; width: 10%;">Qty Pemenuhan</th>
                        <th style="text-align: center; width: 30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($datadetail){
                        $i = 0;
                        foreach($datadetail as $row){
                            $i++;                             
                    ?>
                    <tr>   
                        <td style="text-align: center;"><?= $i;?>
                            <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                        </td> 
                        <td>  
                            <input style="width:120px" type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]"value="<?= $row->id_product; ?>" readonly>
                            <input style="width:120px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]"value="<?= $row->i_product_base; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:350px" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->e_product_basename; ?>" readonly>
                        </td>              
                        <td>
                            <input style="width:100px" type="text" class="form-control" id="nsaldo<?=$i;?>" name="nsaldo[]" value="0" readonly> 
                        </td>              
                        <td>
                            <input style="width:100px" type="text" class="form-control" id="nquantitymemo<?=$i;?>" name="nquantitymemo[]" value="<?= $row->nquantity_permintaan; ?>" readonly> 
                        </td>
                        <td>
                            <input style="width:100px" type="text" class="form-control" id="sisa<?=$i;?>" name="sisa[]" value="<?= $row->nquantity_pemenuhan; ?>" readonly> 
                        </td>
                        <td>
                            <input style="width:100px" style="width:5%"style="width:5%"type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" onkeyup="ceksaldo(<?=$i;?>);">
                        </td>                    
                        <td>
                            <input style="width:350px" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>">
                        </td>                                            
                    </tr>                       
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?}
                    }?>        
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
   $(document).ready(function () {
        max();
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2({
            width : '100%',
        });
        showCalendar('.date');
        $('#ibagian').select2({
            placeholder: 'Pilih Bagian',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian : $('#xbagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });
    
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
                if (data==1 && ($('#isj').val()!=$('#isjold').val())) {
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

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#isj").attr("readonly", false);
        }else{
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#isj").val($("#isjold").val());
            /*number();*/
        }
    });

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
                $('#isj').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    $(document).ready(function () {
        $('#ijenis').select2({
            placeholder: 'Pilih Jenis SPB',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenisspb/'); ?>',
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
        }).change(function(event) {
            $("#icustomer").attr("disabled", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#icustomer").val("");
            $("#ncustop").val("");
            $("#iarea").val("");
            $("#ireferensi").val("");
            $("#dreferensi").val("");
            $("#icustomer").html("");
            $("#ncustop").html("");
            $("#iarea").html("");
            $("#ireferensi").html("");
            $("#dreferensi").html("");
        });

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/area/'); ?>',
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
        }).change(function(event) {
            $("#icustomer").attr("disabled", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#icustomer").val("");
            $("#ncustop").val("");
            $("#ireferensi").val("");
            $("#dreferensi").val("");
            $("#icustomer").html("");
            $("#ncustop").html("");
            $("#ireferensi").html("");
            $("#dreferensi").html("");
        });
        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        iarea : $('#iarea').val(),
                        ijenis : $('#ijenis').val(),
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
        }).change(function(event) {
            $("#ireferensi").attr("disabled", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ncustop").val("");
            $("#ireferensi").val("");
            $("#dreferensi").val("");
            $("#ncustop").html("");
            $("#ireferensi").html("");
            $("#dreferensi").html("");
        });

        $('#ireferensi').select2({
            placeholder: 'Cari No Referensi',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        icustomer : $('#icustomer').val(),
                        ijenis : $('#ijenis').val(),
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
    
    $("#ireferensi").change(function() {
        $("#ireferensi").val($(this).val());
        $("#tabledatax tbody tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'id'     : $(this).val(),
                'ijenis' : $('#ijenis').val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailrefeks'); ?>',
            dataType: "json",
            success: function (data) {
                var dreferensi = data['head']['d_document'];
                var ncustop    = data['head']['n_customer_toplength'];
                $('#dreferensi').val(dreferensi);
                $('#ncustop').val(ncustop);

                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    //var no = a+1;
                    var no = $('#tabledatax tbody tr').length+1;
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+no+'</td>';
                    cols += '<td style="text-align: center"><input hidden class="form-control" readonly id="idproduct'+no+'" name="idproduct[]" value="'+data['detail'][a]['id_product']+'"><input class="form-control" readonly id="iproduct'+no+'" name="iproduct'+no+'" value="'+data['detail'][a]['i_product_base']+'"></td>';
                    cols += '<td><input type="text" class="form-control" id="eproduct'+no+'" name="eproduct'+no+'" value="'+data['detail'][a]['e_product_basename']+'" readonly></td>';
                     cols += '<td><input type="text" class="form-control" id="nsaldo'+no+'" name="nsaldo'+no+'" value="0" readonly></td>';
                    cols += '<td><input type="text" class="form-control" id="nquantitymemo'+no+'" name="nquantitymemo[]" value="'+data['detail'][a]['n_quantity']+'" readonly></td>';
                    cols += '<td><input class="form-control text-right" readonly id="sisa'+no+'" name="sisa[]" value="'+data['detail'][a]['n_quantity_sisa']+'"></td>';
                    cols += '<td><input class="form-control text-right" id="nquantity'+no+'" placeholder="0" name="nquantity[]" value="" onkeyup="ceksaldo('+no+'); reformat(this)"></td>';
                    cols += '<td><input class="form-control" id="edesc'+no+'" name="edesc[]" value=""></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
            max();
            },
            error: function () {
                swal('Data kosong :)');
            }
        });
    });

   function max(){
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

    function ceksaldo(i) {
        if (parseFloat($('#nquantity'+i).val()) > parseFloat($('#sisa'+i).val())) {
            swal('Qty terima tidak boleh lebih dari qty sisa!!!');
            $('#nquantity'+i).val($('#sisa'+i).val());
        }
    }

   $( "#submit" ).click(function(event) {
        ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()!=null) && ($('#iarea').val()!='' || $('#iarea').val()!=null) && ($('#ireferensi').val()!='' || $('#ireferensi').val()!=null)) {
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                $("#tabledatax tbody tr").each(function() {
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
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }  
    });
</script>