<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<form id="formclose"> 
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-3">Bagian Pembuat</label>
                    <label class="col-md-3">Nomor Dokumen</label>
                    <label class="col-md-2">Tanggal Dokumen</label>
                    <label class="col-md-4">Kas/Bank</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ibagian" id="ibagian" disabled>
                            <?php foreach ($bagian as $ibagian):?>
                            <option value="<?php echo $ibagian->i_bagian;?>">
                                <?= $ibagian->e_bagian_name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <input type="hidden" name="id" id="id" value="<?= $id;?>">
                        <input type="text" readonly name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <input class="form-control" name="ddocument" id="ddocument" readonly="" value="<?=$data->d_document; ?>">
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="ikasbank" id="ikasbank" disabled>
                            <option value="<?=$data->id_kas_bank;?>"><?=$data->e_kas_name;?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">                    
                    <label class="col-md-3">Penyetor</label>
                    <label class="col-md-3">Bank</label>
                    <label class="col-md-6">No Giro</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ipenyetor" id="ipenyetor" disabled>
                            <option value="<?=$data->id_penyetor;?>"><?=$data->e_nama_karyawan;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ibank" id="ibank" class="form-control select2" disabled>
                            <option value="<?=$data->id_bank;?>"><?=$data->e_bank_name;?></option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="ireferensigiro" id="ireferensigiro" multiple="multiple" class="form-control select2" onchange="return getitemgiro(this.value);" disabled>
                            <?php if ($giro) {
                                foreach ($giro as $kuy) {?>
                                    <option value="<?= $kuy->id_document_reff;?>" selected><?= $kuy->i_giro;?></option>
                                <?php }
                            }?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea type="text" readonly id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">                        
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Giro</th>
                        <th>Tanggal Giro</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Penerima</th>
                        <th>Pelanggan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <?= $row->i_giro;?>
                                </td>
                                <td>
                                    <?= $row->d_giro;?>
                                </td>
                                <td>
                                    <?= $row->d_giro_duedate;?>
                                </td>
                                <td>
                                    <?= $row->e_nama_karyawan;?>
                                </td>
                                <td>
                                    <?= $row->e_customer_name;?>
                                </td>
                                <td>
                                    <?= number_format($row->v_jumlah);?>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        showCalendar('.date');
        $('.select2').select2();
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

    $(document).ready(function () {
        $('#ikasbank').select2({
            placeholder: 'Pilih Kas/Bank',
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder.'/cform/kasbank'); ?>',
              dataType: 'json',
              delay: 250,          
              processResults: function (data) {
                return {
                  results: data
                };
              },
              cache: true
            }
        })

        $('#ipenyetor').select2({
            placeholder: 'Pilih Penyetor',
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder.'/cform/penyetor'); ?>',
              dataType: 'json',
              delay: 250,          
              processResults: function (data) {
                return {
                  results: data
                };
              },
              cache: true
            }
        })

        $('#ibank').select2({
            placeholder: 'Pilih Bank',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bank/'); ?>',
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
            $("#ireferensigiro").attr("disabled", false);
            $("#ireferensigiro").val("");
            $("#ireferensigiro").html("");
        });

        $('#ireferensigiro').select2({
            placeholder: 'Cari No Giro',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getgiro'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibank : $('#ibank').val(),
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

    function getitemgiro(ireferensigiro) {
        var ireferensigiro = $('#ireferensigiro').val();
        var ibank          = $('#ibank').val();
        $.ajax({
            type: "post",
            data: {
                    'ireferensigiro': ireferensigiro,
                    'ibank': ibank,
            },
            url: '<?= base_url($folder.'/cform/getitemgiro'); ?>',
            dataType: "json",
            success: function (data) {  
                $('#jml').val(data['dataitem'].length);
                $("#tabledatax tbody").remove();
                $("#tabledatax").attr("hidden", false);
                for (let a = 0; a < data['dataitem'].length; a++) {
                    var no = a+1;
                    var id                  = data['dataitem'][a]['id']
                    var giro                = data['dataitem'][a]['giro'];
                    var tanggalgiro         = data['dataitem'][a]['d_giro'];
                    var tanggaljatuhtempo   = data['dataitem'][a]['d_jatuhtempo'];
                    var penerima            = data['dataitem'][a]['penerima'];
                    var namapenerima        = data['dataitem'][a]['namapenerima'];
                    var pelanggan           = data['dataitem'][a]['pelanggan'];
                    var epelanggan          = data['dataitem'][a]['e_customer_name'];
                    var jumlah              = data['dataitem'][a]['v_jumlah'];
                    var cols                = "";
                    var newRow              = $("<tr>");
                    
                    cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                    cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="igiro'+no+'" name="igiro'+no+'" value="'+giro+'"><input readonly style="width:150px;" class="form-control" type="text" id="idgiro'+no+'" name="idgiro'+no+'" value="'+id+'"></td>';
                    cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="dgiro'+no+'" name="dgiro'+no+'" value="'+tanggalgiro+'"></td>'; 
                    cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="djatuhtempo'+no+'" name="djatuhtempo'+no+'" value="'+tanggaljatuhtempo+'"></td>'; 
                    cols += '<td><input readonly style="width:100px;" class="form-control" type="hidden" id="penerima'+no+'" name="penerima'+no+'" value="'+penerima+'"><input readonly style="width:250px;" class="form-control" type="text" id="epenerima'+no+'" name="epenerima'+no+'" value="'+namapenerima+'"></td>';
                    cols += '<td><input readonly style="width:100px;" class="form-control" type="hidden" id="pelanggan'+no+'" name="pelanggan'+no+'" value="'+pelanggan+'"><input readonly style="width:250px;" class="form-control" type="text" id="epelanggan'+no+'" name="epelanggan'+no+'" value="'+epelanggan+'"></td>';
                    cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="jumlah'+no+'" name="jumlah'+no+'" value="'+jumlah+'"></td>';
                    cols +='<td><input type="checkbox" name="cek'+no+'" value="checked" id="cek'+no+'"></td>';
                   
                newRow.append(cols);
                $("#tabledatax").append(newRow);
                }
            },
            error: function () {
                alert('Error :)');
            }
        });
    } 

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ikasbank').val()!='' || $('#ikasbank').val()) && ($('#ipenyetor').val()!='' || $('#ipenyetor').val())  && ($('#ibank').val()!='' || $('#ibank').val()) && ($('#ireferensigiro').val()!='' || $('#ireferensigiro').val())) {
            if ($('#jml').val()==0) {
                swal('Data Item Masih Kosong!');
                return false;
            }else{
                if ($("#tabledatax input:checkbox:checked").length > 0){
                    return true;
                }else{
                    swal('Pilih data minimal satu!');
                    return false;
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });    
</script>