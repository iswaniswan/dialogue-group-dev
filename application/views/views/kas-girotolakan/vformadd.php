<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<form id="formclose"> 
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-3">Bagian</label>
                    <label class="col-md-3">Nomor Dokumen</label>
                    <label class="col-md-2">Tanggal Dokumen</label>
                    <label class="col-md-3">Nomor Referensi</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ibagian" id="ibagian">
                            <?php foreach ($bagian as $ibagian):?>
                            <option value="<?php echo $ibagian->i_bagian;?>">
                                <?= $ibagian->e_bagian_name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                            <span class="input-group-addon">
                                <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                            </span>
                        </div>
                        <span class="notekode">Format : (<?= $number;?>)</span><br>
                        <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                    </div>
                    <div class="col-sm-2">
                        <input class="form-control date" name="ddocument" id="ddocument" readonly="" value="<?php echo date("d-m-Y"); ?>">
                    </div>
                    <div class="col-sm-3">
                        <select name="ikriling" id="ikriling" class="form-control select2">
                        </select>
                    </div>
                </div>
                <div class="form-group row">                  
                    <label class="col-md-3">Nomor Referensi Giro</label>
                    <label class="col-md-3">Gunakan Nomor Giro Kembali</label>
                    <label class="col-md-6">Keterangan</label>
                    <div class="col-sm-3">
                        <select name="ireferensigiro" id="ireferensigiro" class="form-control select2" onchange="return getitemgiro(this.value);">
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="usegiro" name="usegiro" value="1">
                        <label for="yes">Ya</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" id="usegiro" name="usegiro" value="2">
                        <label for="no">Tidak</label>
                    </div>
                    <div class="col-sm-6">
                        <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"></textarea>
                    </div>
                </div>   
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
                <input type="hidden" name="jml" id="jml" value ="0">
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
            <table id="tabledata" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Kriling Giro</th>
                        <th>Tanggal Giro</th>
                        <th>Bank</th>
                        <th>Tujuan</th>
                        <th>Penyetor</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
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
        number();
        $("#ireferensigiro").attr("disabled", true);
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

    /*----------  Menyesuaikan periode di running number sesuai dengan tanggal dokumen dan bagian ----------*/
    $( "#ibagian, #ddocument" ).change(function() {
        number();
    });

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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('#ikriling').select2({
            placeholder: 'Pilih Referensi No Kliring Giro',
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder.'/cform/referensikriling'); ?>',
              dataType: 'json',
              delay: 250,          
              processResults: function (data) {
                return {
                  results: data
                };
              },
              cache: true
            }
        }).change(function(event){
            $('#ireferensigiro').attr("disabled", false);
        });

        $('#ireferensigiro').select2({
            placeholder: 'Pilih Nomor Giro',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getgiro'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q         : params.term,
                        ikriling  : $('#ikriling').val(),
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

    // function getgiro(ikriling){
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo site_url($folder.'/Cform/getgiro');?>",
    //         data:"ikriling="+ikriling,
    //         dataType: 'json',
    //         success: function(data){
    //             $("#ireferensigiro").html(data.kop);
    //             if (data.kosong=='kopong') {
    //                 $("#submit").attr("disabled", true);
    //             }else{
    //                 $("#submit").attr("disabled", false);
    //                 $("#ireferensigiro").attr("disabled", false);

    //             }
    //         },

    //         error:function(XMLHttpRequest){
    //             alert(XMLHttpRequest.responseText);
    //         }

    //     })
    // }

    function getitemgiro(ireferensigiro) {   
        var ireferensigiro = $('#ireferensigiro').val();
        var ikriling       = $('#ikriling').val();
        $.ajax({
            type: "post",
            data: {
                'ireferensigiro': ireferensigiro,
                'ikriling': ikriling,
            },
            url: '<?= base_url($folder.'/cform/getitemgiro'); ?>',
            dataType: "json",
            success: function (data) {  
                $('#jml').val(data['dataitem'].length);
                $("#tabledata tbody").remove();
                $("#tabledata").attr("hidden", false);
                for (let a = 0; a < data['dataitem'].length; a++) {
                    var no = a+1;
                    var idkrilinggiro       = data['dataitem'][a]['id_kliring'];
                    var idgiro              = data['dataitem'][a]['id_giro'];
                    var tanggalkrilinggiro  = data['dataitem'][a]['d_kliring'];
                    var tanggalgiro         = data['dataitem'][a]['d_giro'];
                    var ibank               = data['dataitem'][a]['id_bank'];
                    var ebank               = data['dataitem'][a]['e_bank_name'];
                    var itujuan             = data['dataitem'][a]['id_kas_bank'];
                    var etujuan             = data['dataitem'][a]['e_kas_name'];
                    var ipenyetor           = data['dataitem'][a]['id_penyetor'];
                    var epenyetor           = data['dataitem'][a]['e_nama_karyawan'];
                    var jumlah              = formatcemua(data['dataitem'][a]['v_jumlah']);
                    
                    var cols        = "";
                    var newRow = $("<tr>");
                    
                    cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                    cols += '<td><input readonly style="width:150px;" class="form-control" type="hidden" id="idkrilinggiro'+no+'" name="idkrilinggiro'+no+'" value="'+idkrilinggiro+'"><input readonly style="width:150px;" class="form-control" type="text" id="tanggalkrilinggiro'+no+'" name="tanggalkrilinggiro'+no+'" value="'+tanggalkrilinggiro+'"></td>'; 
                    cols += '<td><input readonly style="width:150px;" class="form-control" type="hidden" id="idgiro'+no+'" name="idgiro'+no+'" value="'+idgiro+'"><input readonly style="width:150px;" class="form-control" type="text" id="tanggalgiro'+no+'" name="tanggalgiro'+no+'" value="'+tanggalgiro+'"></td>'; 
                    cols += '<td><input readonly style="width:100px;" class="form-control" type="hidden" id="ibank'+no+'" name="ibank'+no+'" value="'+ibank+'"><input readonly style="width:250px;" class="form-control" type="text" id="ebank'+no+'" name="ebank'+no+'" value="'+ebank+'"></td>';
                    cols += '<td><input readonly style="width:100px;" class="form-control" type="hidden" id="itujuan'+no+'" name="itujuan'+no+'" value="'+itujuan+'"><input readonly style="width:250px;" class="form-control" type="text" id="etujuan'+a+'" name="etujuan'+no+'" value="'+etujuan+'"></td>';
                    cols += '<td><input readonly style="width:100px;" class="form-control" type="hidden" id="ipenyetor'+no+'" name="ipenyetor'+no+'" value="'+ipenyetor+'"><input readonly style="width:250px;" class="form-control" type="text" id="epenyetor'+a+'" name="epenyetor'+no+'" value="'+epenyetor+'"></td>';
                    cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="jumlah'+no+'" name="jumlah'+no+'" value="'+jumlah+'"></td>';
                   
                newRow.append(cols);
                $("#tabledata").append(newRow);
                }
            },
            error: function () {
                alert('Error :)');
            }
        });
    } 

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ikriling').val()!='' || $('#ikriling').val()) && ($('#ireferensigiro').val()!='' || $('#ireferensigiro').val())) {
            if ($('#jml').val()==0) {
                swal('Data Item Masih Kosong!');
                return false;
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });    
</script>
