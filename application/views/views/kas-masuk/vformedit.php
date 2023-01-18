<form>
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
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Kas/Bank</label>
                        <label class="col-md-2">Bank</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?=$data->id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="idocument" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" required="" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="ikasbank" required="" id="ikasbank" class="form-control select2"> 
                                <option value="<?=$data->id_kas_bank.'|'.$data->i_bank;?>"><?=$data->e_kas_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="ibank" id="ibank" class="form-control select2"> 
                                <?php if($data->id_bank != 0 || $data->id_bank != null){?>
                                    <option value="<?=$data->id_bank;?>"><?=$data->e_bank_name;?></option>
                                <?php }?>
                            </select>
                        </div> 
                    </div>
                    <div class="form-group row">            
                        <label class="col-md-4">Partner</label>
                        <label class="col-md-2">Total Nilai</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-4">
                            <select name="icustomer" id="icustomer" class="form-control select2" multiple="multiple">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control text-right input-sm" required="" placeholder="Rp. 0,000,000.00" name="vnilai" id="vnilai" readonly value="<?= number_format($data->n_nilai);?>">
                        </div>                        
                        <div class="col-sm-6">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
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
                        <th class="text-center" width="20%;">Jenis Faktur</th>
                        <th class="text-center" width="10%;">Kode Partner</th>
                        <th class="text-center" width="35%;">Nama Partner</th>
                        <th class="text-center" width="12%;">Nilai</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" width="5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {?>
                            <tr>
                                <td class="text-center"><?= $i+1;?></td>
                                <td>
                                    <input type="text" class="form-control input-sm" readonly name="jenisfaktur<?=$i;?>" value="<?= $row->jenis_faktur;?>">
                                    <input type="hidden" class="form-control input-sm" readonly name="grouppartner<?=$i;?>" value="<?= $row->group_partner;?>">
                                </td>
                                <td>
                                    <input type="hidden" id="idcustomer<?=$i;?>" name="idcustomer<?=$i;?>" value="<?= $row->id_partner;?>">
                                    <input type="text" readonly class="form-control input-sm" value="<?= $row->i_partner;?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control input-sm" readonly value="<?= $row->e_partner;?>">
                                </td>
                                <td>
                                    <input type="text" id="v_nilai<?=$i;?>" class="form-control input-sm text-right" name="v_nilai<?=$i;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this); reformat(this); cek_nilai(<?=$i;?>);" value="<?= number_format($row->n_nilai);?>" >
                                </td>
                                <td>
                                    <input type="text" id="edesc<?=$i;?>" class="form-control input-sm" name="edesc<?=$i;?>" value="<?= $row->e_remark;?>">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="cek<?=$i;?>" id="cek<?=$i;?>" checked>
                                </td>
                            </tr>
                        <?php $i++; } 
                    }?>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
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

         /*----------  Cari Customer  ----------*/        
        $('#icustomer').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer'); ?>',
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
            /* Get Detail Customer */
            $('#vnilai').val(0);
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'icustomer': $(this).val(),
                },
                url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
                dataType: "json",
                success: function (data) {  
                    //alert($("#jml").val());
                    $('#jml').val(data['data'].length);
                    alert($('#jml').val(data['data'].length));
                    $("#tabledatax tbody").remove();
                    for (let i = 0; i < data['data'].length; i++) {
                        var cols   = "";
                        var newRow = $("<tr>");
                        cols += `<td class="text-center">${i+1}</td>`;
                        cols += `<td><input readonly class="form-control input-sm" type="text" name="jenisfaktur${i}" value="${data['data'][i]['jenis_faktur']}"><input readonly class="form-control input-sm" type="hidden" name="grouppartner${i}" value="${data['data'][i]['group_partner']}"></td>`; 
                        cols += `<td><input type="hidden" name="idcustomer${i}" value="${data['data'][i]['id_partner']}"><input readonly class="form-control input-sm" type="text" value="${data['data'][i]['i_partner']}"></td>`;
                        cols += `<td><input readonly class="form-control input-sm" type="text" value="${data['data'][i]['e_partner']}"></td>`; 
                        cols += `<td><input class="form-control input-sm text-right" type="text" id="v_nilai${i}" name="v_nilai${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); reformat(this); cek_nilai(${i});"></td>`; 
                        cols += `<td><input class="form-control input-sm" type="text" name="edesc${i}" placeholder="Jika Ada!"></td>`;
                        cols += `<td class="text-center"><input type="checkbox" name="cek${i}" id="cek${i}"></td>`;
                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
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
                if (data==1 ) {
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

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*----------  untuk me-generate running number  ----------*/
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
                    swal('Error :)');
                }
            });
        }
    }

    /*----------  Hitung Total Nilai  ----------*/      
    function cek_nilai(i){
        total = 0;
        //alert($('#jml'));
        for (var i = 0; i < $('#jml').val(); i++) {
            var jumlah = formatulang($('#v_nilai'+i).val());
            total += parseFloat(jumlah); 
        }
        //alert(total);
        $('#vnilai').val(formatcemua(total));
        //alert($('#vnilai').val());
    }

    /*----------  Validasi Simpan Data  ----------*/    
    $( "#submit" ).click(function(event) {
        if ($("#tabledatax input:checkbox:checked").length > 0){
            ada = false;
            if (($('#ibagian').val()!='' || $('#ibagian').val()!=null) && ($('#ddocument').val()!='' || $('#ddocument').val()!=null) && ($('#ikasbank').val()!='' || $('#ikasbank').val()!=null)) {
                if ($('#jml').val()==0) {
                    swal('Isi item minimal 1!');
                    return false;
                }else{
                    for (var i = 0; i < $('#jml').val(); i++) {
                        if($('#cek'+i).is(':checked')){
                            if (parseInt(formatulang($('#v_nilai'+i).val())) <= 0) {
                                swal('Maaf :(','Nilai Tidak Boleh Kosong Atau 0!','error');
                                $('#cek'+i).attr('checked', false);
                                return false;
                                ada = true;
                            }
                        }
                    }
                    if (!ada) {
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
                                data: $("form").serialize(),
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
                                    }else if (data.sukses=='ada') {
                                        swal("Maaf :(", "No Dokumen : "+data.kode+", Sudah Ada :(", "error");   
                                    }else{
                                        swal("Maaf :(", "No Dokumen : "+data.kode+", Gagal Diupdate :(", "error"); 
                                    }
                                },
                                error: function () {
                                    swal("Maaf", "Data Gagal Diupdate :(", "error");
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
        }else{
            swal('Maaf :(','Pilih data minimal satu!','error');
            return false;
        }
    });
</script>