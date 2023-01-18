<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Departement</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?= $data->i_bagian;?>"><?= $data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly="" class="form-control" value="<?=$data->id;?>" >   
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BON-2012-000001" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control datedoc" name="ddocument" id="ddocument" readonly="" required="" value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="idepartement" id="idepartement" class="form-control">
                                <option value="<?= $data->i_departement;?>"><?= $data->e_departement_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                        
                        <label class="col-md-3">Karyawan</label>         
                        <label class="col-md-3">Jumlah</label>  
                        <label class="col-md-6">Keperluan</label>    
                        <div class="col-sm-3">
                            <select name="ikaryawan" id="ikaryawan" class="form-control"> 
                                <option value="<?= $data->id_karyawan;?>"><?= $data->e_nama_karyawan;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeyup="reformat(this);" value=<?=number_format($data->v_jumlah);?>>
                        </div>  
                        <div class="col-sm-6">
                            <input name="ekeperluan" id="ekeperluan" class="form-control" value=<?=$data->e_keperluan;?>>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                           <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
        </form>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.datedoc',1800,0);
        $('#idocument').mask('SSS-0000-000000S');

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
                        $(".notekode").attr("hidden", false);
                        $("#submit").attr("disabled", true);
                    }else{
                        $(".notekode").attr("hidden", true);
                        $("#submit").attr("disabled", false);
                    }
                },
                error: function () {
                    swal('Error :)');
                }
            });
        });

       $('#idepartement').select2({
            placeholder: 'Pilih Departement',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/departement'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
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
        }).on("change", function (e) {
             $("#ikaryawan").attr("disabled", false);
        });

        $('#ikaryawan').select2({
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/karyawan'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        idepartement : $('#idepartement').val(),
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

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
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
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
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


    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#idepartement').val()!='' || $('#idepartement').val()) && ($('#ikaryawan').val()!='' || $('#ikaryawan').val())  && ($('#vjumlah').val()!='' || $('#vjumlah').val()) && ($('#ekeperluan').val()!='' || $('#ekeperluan').val())) {
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });   
</script>