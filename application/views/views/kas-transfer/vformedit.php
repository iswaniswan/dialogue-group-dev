<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                 <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-6">Tanggal Dokumen</label>
     
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                              <?php if ($bagian) {
                                            foreach ($bagian as $row):?>
                                                <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $head->i_bagian) {?> selected <?php } ?>>
                                                    <?= $row->e_bagian_name;?>
                                                </option>
                                            <?php endforeach; 
                                        } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $head->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" class="form-control" value="<?= $head->id;?>" readonly="">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $head->i_document;?>">

                                <input type="text" name="idocument" id="i_kas_transfer" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="TF-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $head->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                              <input type="text" name="ddocument" id="ddocument" class="form-control date" value="<?= $head->d_document;?>" readonly="">
                        </div>
                    </div>
                    <div class="form-group row">                     
                        <label class="col-md-4">Kas/Bank Asal</label>
                        <label class="col-md-4">Nilai</label>   
                        <label class="col-md-4">Kas/Bank Tujuan</label>
                        <div class="col-sm-4">
                            <select name="ikasbankaw" id="ikasbankaw" class="form-control select2"> 
                                <option value="<?= $head->id_kas_bankaw;?>"><?= $head->e_kas_nameaw.' ('.$head->e_coa_nameaw.')';?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-right" name="vnilai" id="vnilai" value="<?= number_format($head->v_nilai);?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="angkahungkul(this); reformat(this);">
                        </div>  
                        <div class="col-sm-4">
                            <select name="ikasbankak" id="ikasbankak" class="form-control select2">
                                <option value="<?= $head->id_kas_bankak;?>"><?= $head->e_kas_nameak.' ('.$head->e_coa_nameak.')';?></option>
                            </select>
                        </div>
                                          
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?= $head->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($head->i_status == '1' || $head->i_status == '3' || $head->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($head->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($head->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        /**
        * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
        */
        showCalendar('.date',1830,0);
        
        $('#i_kas_transfer').mask('SS-0000-000000S');
        //memanggil function untuk penomoran dokumen

        $('#ikasbankaw').select2({
            placeholder: 'Pilih Kas/Bank Asal',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kasbank'); ?>',
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
        }).change(function(event) {
            kbtujuan($('#ikasbankaw').val());
        });
    });

    function kbtujuan(ikasbankaw){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/kasbanktujuan');?>",
            data: {
                'ikasbankaw' : ikasbankaw,
            },
            dataType: 'json',
            success: function(data){
                $("#ikasbankak").html(data.kop);
                //getcustomer('ALCUS');
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ikasbankak").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        });
    }

    $( "#i_kas_transfer" ).keyup(function() {
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

    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $( "#ibagian, #ddocument" ).change(function() {
        number();
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#i_kas_transfer").attr("readonly", false);
        }else{
            $("#i_kas_transfer").attr("readonly", true);
        }
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    //untuk me-generate running number
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#i_kas_transfer').val($('#idocumentold').val());
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
                    $('#i_kas_transfer').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }
        
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

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ikasbankaw').val()!='' || $('#ikasbankaw').val()) && ($('#ikasbankak').val()!='' || $('#ikasbankak').val()) && ($('#i_kas_transfer').val()!='' || $('#i_kas_transfer').val()) ) {

            if ($('#vnilai').val() == 0 || $('#vnilai').val() == '0' ) {
                swal('Nominal Tidak Boleh 0!');
                return false;
            } else {
                return true;
            }
            
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });    
</script>