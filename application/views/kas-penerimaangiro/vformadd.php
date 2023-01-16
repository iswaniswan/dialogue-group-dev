<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Giro</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control datedoc" name="ddocument" id="ddocument" readonly="" required="" value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="igiro" id="igiro" maxlength="10" placeholder="Nomor Giro">
                        </div> 
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">Tgl Giro</label>
                        <label class="col-md-2">Tgl Jatuh Tempo</label>
                        <label class="col-md-2">Tgl Setor</label>
                        <label class="col-md-2">Tgl Terima</label>
                        <label class="col-md-3">Penerima</label>
                        <div class="col-sm-2">
                            <input class="form-control date" name="dgiro" id="dgiro" readonly="" required="" value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control date" name="dgiroduedate" id="dgiroduedate" readonly="" required="" value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control date" name="dsetor" id="dsetor" required="" value="<?= date("d-m-Y"); ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control date" name="dgiroterima" id="dgiroterima" readonly="" required="" value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ikaryawan" id="ikaryawan" class="form-control">
                                <?php foreach ($karyawan as $ikaryawan):?>
                                    <option></option>
                                    <option value="<?php echo $ikaryawan->id;?>"><?php echo $ikaryawan->e_nama_karyawan;?></option>
                                <?php endforeach; ?>  
                            </select>
                        </div>      
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-3">Bank</label>            
                        <label class="col-md-2">Jumlah</label>                      
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control">
                                <?php foreach ($area as $iarea):?>
                                    <option></option>
                                    <option value="<?php echo $iarea->id;?>"><?php echo $iarea->e_area;?></option>
                                <?php endforeach; ?>  
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="icustomer" id="icustomer" class="form-control" disabled="true"> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ibank" id="ibank" class="form-control">
                                <?php foreach ($bank as $ibank):?>
                                    <option></option>
                                    <option value="<?php echo $ibank->id;?>"><?php echo $ibank->e_bank_name;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeyup="reformat(this);" placeholder="Jumlah Giro">
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                           <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
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
        showCalendar('.date');
        showCalendar('.datedoc',1800,0);
        $('#idocument').mask('SSS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();

        $('#ibagian, #ddocument').change(function(event) {
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

       $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/area'); ?>',
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
             $("#icustomer").attr("disabled", false);
            // var kode = $('#icustomer option:selected').text();
            // $('#ecustomername').val(kode);
        });

        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iarea : $('#iarea').val(),
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

        $('#ibank').select2({
            placeholder: 'Pilih Bank',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bank'); ?>',
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

    function dipales(){
        if((document.getElementById("igiro").value!='') && (document.getElementById("dgiroterima").value!='') && (document.getElementById("dgiroduedate").value!='') && (document.getElementById("icustomer").value!='') && (document.getElementById("dgiro").value!='') && (document.getElementById("dsetor").value!='') && (document.getElementById("vjumlah").value!='')){
            tes=adaspasi(document.getElementById("igiro").value);
            if(tes){
                swal('Nomor Giro tidak boleh ada spasi !!!!!');
                return false;
            }else{
                return true;
            }
        }else{
            swal('Data header masih ada yang salah !!!!!');
            return true;
        }
    }
</script>