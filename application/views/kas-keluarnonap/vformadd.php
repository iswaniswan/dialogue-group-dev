<form>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Kas/Bank</label>
                        <label class="col-md-2">Bank</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
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
                                <input type="hidden" name="id" id="id" value="">
                                <input type="text" name="idocument" id="idocument" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?= date("d-m-Y"); ?>" required="" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="ikasbank" required="" id="ikasbank" class="form-control select2"> 
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="ibank" id="ibank" class="form-control select2"> 
                            </select>
                        </div> 
                    </div>
                    <div class="form-group row">            
                        <label class="col-md-3">Jenis Keluar</label>
                        <label class="col-md-3">Referensi</label>
                        <label class="col-md-2">Total Nilai</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" required="" class="form-control select2" data-placeholder="Pilih Jenis Keluar">
                                <option value=""></option>
                                <?php if ($jenis) {
                                    foreach ($jenis as $row):?>
                                        <option value="<?= $row->id;?>">
                                            <?= $row->e_jenis_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ireferensi" required="" id="ireferensi" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control text-right input-sm" required="" placeholder="Rp. 0,000,000.00" name="vnilai" id="vnilai" readonly value="">
                        </div>                        
                        <div class="col-sm-4">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>Note : </b></span><br>
                            <span class="notekode">* Bank Harus Diisi Jika Jenis Kas/Bank nya Bank!</span><br>
                            <span class="notekode">* Bank Boleh Dikosongkan Jika Jenis Kas/Bank nya Kas!</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail" >
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Transaksi</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center">No. Referensi</th>
                        <th class="text-center">Tgl. Referensi</th>
                        <th class="text-center">Nilai Referensi</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center" width="30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        /**
        * Tidak boleh lebih dari hari ini
        */
        showCalendar('.date',null,0);
        
        $('#idocument').mask('SSS-0000-000000S');
        /*----------  Memanggil Function Nomor Dokumen  ----------*/
        number();

        /*----------  Cari Kas/Bank  ----------*/        
        $('#ikasbank').select2({
            placeholder: 'Pilih Kas/Bank',
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
        });

        /*----------  Cari Bank Berdasarkan Type  ----------*/
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
                        ikasbank : $('#ikasbank').val(),
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

        $('#ijenis').change(function(event) {            
            $('#ireferensi').val('');
            $('#ireferensi').html('');
            $("#tabledatax > tbody").remove();
            $("#jml").val(0);
        });

        /*----------  Cari Referensi  ----------*/        
        $('#ireferensi').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ijenis: $('#ijenis').val()
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
            /* Get Detail Customer */
            $('#vnilai').val('');
            $.ajax({
                type: "post",
                data: {
                    'ireferensi': $(this).val(),
                    'ijenis'    : $('#ijenis').val(),
                },
                url: '<?= base_url($folder.'/cform/getreferensi'); ?>',
                dataType: "json",
                success: function (data) {  
                    $('#jml').val(data['data'].length);
                    $("#tabledatax tbody").remove();
                    for (let i = 0; i < data['data'].length; i++) {
                        var cols   = "";
                        var newRow = $("<tr>");
                        cols += `<td class="text-center">${i+1}</td>`;
                        cols += `<td><input type="hidden" name="idreferensi${i}" value="${data['data'][i]['id']}"><input readonly class="form-control input-sm" type="text" value="${data['data'][i]['i_document']}"></td>`;
                        cols += `<td><input readonly class="form-control input-sm" type="text" value="${data['data'][i]['d_document']}"></td>`; 
                        cols += `<td><input readonly class="form-control input-sm text-right" type="text" id="v_nilai_reff${i}" value="${formatcemua(data['data'][i]['n_sisa'])}"></td>`; 
                        cols += `<td><input class="form-control input-sm text-right" type="text" id="v_nilai${i}" name="v_nilai${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="${formatcemua(data['data'][i]['n_sisa'])}" onkeyup="angkahungkul(this); reformat(this); hetang(${i}); cek_nilai(${i});"></td>`; 
                        cols += `<td><input class="form-control input-sm" type="text" name="edesc${i}" placeholder="Jika Ada!"></td>`;
                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
                        cek_nilai(i);
                    }                       
                },
                error: function () {
                    swal('Error :(');
                }
            });
        });
    });
    
    /*----------  Cek Dokumen Sudah Ada  ----------*/    
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

    /*----------  Menyesuaikan periode di running number sesuai dengan tanggal dokumen dan bagian ----------*/
    $( "#ibagian, #ddocument" ).change(function() {
        number();
    });

    /*----------  No Dokumen Manual  ----------*/    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
            number();
        }
    });

    /*----------  Kirim Dokumen  ----------*/    
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    /*----------  untuk me-generate running number  ----------*/
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

    /*----------  Cek Nilai Jika Lebih  ----------*/
    function hetang(i) {
        if (parseInt(formatulang($('#v_nilai'+i).val())) > parseInt(formatulang($('#v_nilai_reff'+i).val()))) {
            swal('Maaf','Jumlah Nilai Tidak Boleh Lebih Besar Dari Nilai Referensi = Rp. '+$("#v_nilai_reff"+i).val()+' !','error');
            $('#v_nilai'+i).val($('#v_nilai_reff'+i).val());
            cek_nilai(i);
        }
    }

    /*----------  Hitung Total Nilai  ----------*/      
    function cek_nilai(i){
        total = 0;
        for (var i = 0; i < $('#jml').val(); i++) {
            var jumlah = formatulang($('#v_nilai'+i).val());
            total += parseFloat(jumlah);
        }
        $('#vnilai').val(formatcemua(total));
    }

    /*----------  Validasi Simpan Data  ----------*/    
    $( "#submit" ).click(function(event) {
        ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()!=null) && ($('#ijenis').val()!='' || $('#ijenis').val()!=null) && ($('#ddocument').val()!='' || $('#ddocument').val()!=null) && ($('#ikasbank').val()!='' || $('#ikasbank').val()!=null)) {
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                for (var i = 0; i < $('#jml').val(); i++) {                        
                    if (parseInt(formatulang($('#v_nilai'+i).val())) <= 0) {
                        swal('Maaf :(','Nilai Tidak Boleh Kosong Atau 0!','error');
                        return false;
                        ada = true;
                    }
                }
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