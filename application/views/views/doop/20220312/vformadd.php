<!-- <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Jenis SPB</label>                    
                        <div class="col-sm-3">
                             <select name="ibagian" required="" id="ibagian" class="form-control select2">
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
                                <input type="hidden" name="id" id="id">
                                <input type="text" name="isj" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" required="" name="ddocument" class="form-control input-sm date" value="<?= date("d-m-Y"); ?>" readonly>  
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" required="" id="ijenis" class="form-control select2">
                            </select>
                        </div>                     
                    </div>
                    <div class="form-group row"> 
                        <label class="col-md-3">Area</label> 
                        <label class="col-md-4">Customer</label>                      
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>                 
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="icustomer" id="icustomer" class="form-control select2">
                            </select>
                            <input type="hidden" id="ncustop" name="ncustop" class="form-control" value="" readonly>
                            <input type="hidden" id="idharga" name="idharga" class="form-control" value="" readonly>
                            <input type="hidden" id="ecustomer" name="ecustomer" class="form-control" value="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dreferensi" name="dreferensi" class="form-control input-sm" value="" readonly>
                        </div>                                            
                    </div>  
                    <div class="form-group row"> 
                        <label class="col-md-12">Keterangan</label>      
                        <div class="col-sm-12">
                           <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi Keterangan Jika Ada!"></textarea>
                        </div> 
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" ><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;              
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>Note : </b></span><br>
                            <span class="notekode">* Tanggal Dokument tidak boleh lebih kecil dari tanggal Referensi.</span>
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
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="12%;">Kode</th>
                        <th class="text-center" width="30%;">Nama Barang</th>
                        <th class="text-center">Saldo</th>
                        <th class="text-center">Qty SPB</th>
                        <th class="text-center">Qty Sisa</th>
                        <th class="text-center">Qty SJ</th>
                        <th class="text-center" width="20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
   $(document).ready(function () {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();

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
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#iarea").val("");
            $("#icustomer").val("");
            $("#ireferensi").val("");
            $("#dreferensi").val("");
            $("#iarea").html("");
            $("#icustomer").html("");
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
                        idjenis : $('#ijenis').val(),
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
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#icustomer").val("");
            $("#ireferensi").val("");
            $("#dreferensi").val("");
            $("#icustomer").html("");
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
                        iarea  : $('#iarea').val(),
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
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireferensi").val("");
            $("#dreferensi").val("");
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
                        ijenis    : $('#ijenis').val(),
                        iarea    : $('#iarea').val(),
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

    $('#ibagian, #ddocument').change(function(event) {
        number();
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

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#isj").attr("readonly", false);
        }else{
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
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

    /*$("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });*/

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });
    
    $("#ireferensi").change(function() {
        var ijenis = $('#ijenis').val();
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
                $('#idharga').val(data['head']['id_harga_kode']);
                $('#ecustomer').val(data['head']['e_customer_name']);
                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    var no = $('#tabledatax tbody tr').length+1;
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+no+'</td>';
                    cols += '<td style="text-align: center"><input hidden readonly id="idproduct'+no+'" name="idproduct[]" value="'+data['detail'][a]['id_product']+'"><input class="form-control input-sm" readonly id="iproduct'+no+'" name="iproduct'+no+'" value="'+data['detail'][a]['i_product_base']+'"></td>';
                    cols += '<td><input type="text" class="form-control input-sm" id="eproduct'+no+'" name="eproduct'+no+'" value="'+data['detail'][a]['e_product_basename']+'" readonly></td>';
                     cols += '<td><input type="text" class="form-control text-right input-sm" id="nsaldo'+no+'" name="nsaldo'+no+'" value="0" readonly></td>';
                    cols += '<td><input type="text" class="form-control text-right input-sm" id="nquantityspb'+no+'" name="nquantityspb[]" value="'+data['detail'][a]['n_quantity']+'" readonly></td>';
                    cols += '<td><input class="form-control text-right input-sm" readonly id="sisa'+no+'" name="sisa[]" value="'+data['detail'][a]['n_quantity_sisa']+'"></td>';
                    cols += '<td><input class="form-control text-right input-sm inputitem" autocomplete="off" id="nquantity'+no+'" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="ceksaldo('+no+'); angkahungkul(this);"></td>';
                    cols += '<td><input class="form-control input-sm" id="edesc'+no+'" placeholder="Isi Keterangan Jika Ada!" name="edesc[]" value=""></td>';
                    cols += '<input type = "hidden" name="vprice[]" value="'+data['detail'][a]['v_price']+'">';
                    cols += '<input type = "hidden" name="ndiskon1[]" value="'+data['detail'][a]['n_diskon1']+'">';
                    cols += '<input type = "hidden" name="ndiskon2[]" value="'+data['detail'][a]['n_diskon2']+'">';
                    cols += '<input type = "hidden" name="ndiskon3[]" value="'+data['detail'][a]['n_diskon3']+'">';
                    cols += '<input type = "hidden" name="vdiskonplus[]" value="'+data['detail'][a]['v_diskon_tambahan']+'">';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
                maxi();
            },
            error: function () {
                swal('Data kosong :)');
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    function ceksaldo(i) {
        if (parseFloat($('#nquantity'+i).val()) > parseFloat($('#sisa'+i).val())) {
            swal('Quantity Pemenuhan tidak boleh lebih dari Quantity Permintaan!!!');
            $('#nquantity'+i).val($('#sisa'+i).val());
        }
    }

    function maxi(){
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
    
    $( "#submitx" ).click(function(event) {
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

    /*----------  VALIDASI UPDATE DATA  ----------*/    
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
                            swal('Maaf :(','Quantity Tidak Boleh Kosong Atau 0!','error');
                            ada = true;
                        }
                    });
                });
                if (!ada) {
                    swal({   
                        title: "Simpan Data Ini?",   
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Simpan!",   
                        closeOnConfirm: false 
                    }, function(){
                        $.ajax({
                            type: "POST",
                            data: $("form").serialize(),
                            url: '<?= base_url($folder.'/cform/simpan/'); ?>',
                            dataType: "json",
                            success: function (data) {
                                if (data.sukses==true) {
                                    $('#id').val(data.id);
                                    swal("Sukses!", "No Dokumen : "+data.kode+", Berhasil Disimpan :)", "success"); 
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                }else if (data.sukses=='ada') {
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Sudah Ada :(", "error");   
                                }else{
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Gagal Disimpan :(", "error"); 
                                }
                            },
                            error: function () {
                                swal("Maaf", "Data Gagal Disimpan :(", "error");
                            }
                        });
                    });
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