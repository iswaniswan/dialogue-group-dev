<style type="text/css">
    .select2-results__options{
        font-size:14px !important;
    }
    .select2-selection__rendered {
      font-size: 12px;
  }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-2">Nomor Referensi</label>
                            <label class="col-md-2">Tanggal Referensi</label>
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
                                    <input type="hidden" name="id" id="id">
                                    <input type="text" name="idocument" required="" id="ialokasi" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $number;?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= date("d-m-Y"); ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="hidden" id="idkn" name="idkn" required="" value="<?= $data->id; ?>">
                                <input type="text" id="ireferensi" name="ireferensi" class="form-control input-sm" required="" readonly value="<?= $data->i_document; ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="drefrensi" name="drefrensi" class="form-control input-sm" readonly value="<?= $data->d_document; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Nama Customer</label>
                            <label class="col-md-3">Alamat</label>
                            <label class="col-md-3">Kota</label>
                            <label class="col-md-3">Keterangan</label>                            
                            <div class="col-sm-3">
                                <input type="hidden" id="idcustomer" required="" name="idcustomer" value="<?= $data->id_customer;?>">
                                <input type="hidden" id="ecustomer" required="" name="ecustomer" value="<?= $data->e_customer_name;?>">
                                <input type="text" required="" class="form-control input-sm" readonly value="<?= $data->e_customer_name.' ('.$data->i_customer.')';?>">
                            </div>
                            <div class="col-sm-3">
                                <textarea type="text" class="form-control input-sm" readonly><?= $data->e_customer_address;?></textarea>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->e_city_name;?>">
                            </div>
                            <div class="col-sm-3">
                                <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/indexkn/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;
                                <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="jml" id="jml" value ="0">
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Nota</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="15%;">No. Nota</th>
                            <th class="text-center" width="11%;">Tgl. Nota</th>
                            <th class="text-center" width="12%;">Nilai</th>
                            <th class="text-center" width="12%;">Bayar</th>
                            <th class="text-center" width="12%;">Sisa</th>
                            <th class="text-center" width="12%;">Lebih</th>
                            <th class="text-center" width="17%;">Keterangan</th>
                            <th class="text-center" width="3%">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="6">Jumlah KN :</td>
                            <td>
                                <input type="text" id="vjumlah" name="vjumlah" class="form-control input-sm text-right" value="<?= number_format($data->v_sisa);?>" readonly>
                                <input type="hidden" id="vjumlahsisa" name="vjumlahsisa" class="form-control input-sm text-right" value="<?= number_format($data->v_sisa);?>" readonly>
                                <input type="hidden" id="vjumlahlebih" name="vjumlahlebih" class="form-control input-sm text-right" value="0" readonly>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        /*----------  Load Form Validation  ----------*/        
        $('#cekinputan').validate({
            /*rules : {
                ddocument : {
                    australianDate : true
                }
            },*/
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('#ialokasi').mask('SSS-0000-000000S');        
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);
        number();    
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/    
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/    
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
                $('#ialokasi').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }   

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ialokasi").attr("readonly", false);
        }else{
            $("#ialokasi").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/    
    $( "#ialokasi" ).keyup(function() {
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

    /*----------  TAMBAH NOTA  ----------*/
    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatay tbody tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no+1}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idnota${i}" class="form-control input-sm" name="idnota${i}" onchange="getdetail(${i});"></select></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm" name="dnota${i}" id="dnota${i}"/></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vnilai${i}" id="vnilai${i}" value="0"/></td>`;
        cols += `<td><input type="text" id="vbayar${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="vbayar${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); reformat(this); hitung();"></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vsisa${i}" id="vsisa${i}" value="0"/></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vlebih${i}" id="vlebih${i}" value="0"/></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Jika Ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $('#idnota'+ i).select2({
            placeholder: 'Cari Nota',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        idcustomer : $('#idcustomer').val(),
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
        })
    });    
    
    /*----------  HAPUS TR  ----------*/    
    $("#tabledatay").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        obj = $('#tabledatay tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });        
        hitung();
    })

    /*----------  GET DETAIL  ----------*/    
    function getdetail(id){
        $.ajax({
            type: "post",
            data: {
                'idnota'    : $('#idnota'+id).val(),
                'idcustomer': $('#idcustomer').val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailref'); ?>',
            dataType: "json",
            success: function (data) {
                if(typeof data[0] != 'undefined'){
                    ada = false;
                    for(var i = 1; i <= $('#jml').val(); i++){
                        if(($('#idnota'+id).val() == $('#idnota'+i).val()) && (i!=id)){
                            swal ("Maaf :(","No. Nota : "+data[0].i_document+" sudah ada !!!!!","error");
                            $("#tabledatay tbody tr td #idnota"+id).each(function() {
                                $(this).closest("tr").remove();
                            });
                            ada = true;
                            break;
                        }else{
                            ada = false;     
                        }
                    }
                    if(!ada){
                        $('#dnota'+id).val(data[0].d_document);
                        $('#vnilai'+id).val(formatcemua(data[0].v_sisa));
                        if (parseInt(formatulang($('#vjumlahsisa').val())) > parseInt(formatulang(data[0].v_sisa))) {
                            $('#vbayar'+id).val(formatcemua(data[0].v_sisa));
                        }else if ((parseInt(formatulang($('#vjumlahsisa').val())) < parseInt(formatulang(data[0].v_sisa))) && (parseInt(formatulang($('#vjumlahsisa').val())) > 0)) {
                            $('#vbayar'+id).val(formatcemua($('#vjumlahsisa').val()));
                        }else{
                            $('#vbayar'+id).val(0);
                        }
                        $('#vbayar'+id).focus();
                        hitung();
                    }else{
                        $('#idnota'+id).html('');
                        $('#idnota'+id).val('');
                    }
                }else{
                    swal('Data tidak ada!');
                }
            },
            error: function () {
                swal('Ada kesalahan :(');
            }
        });
    }

    /*----------  HITUNG NILAI  ----------*/
    function hitung(){
        var vjmlbyr     = parseFloat(formatulang($('#vjumlah').val()));
        var vlebihitem  = vjmlbyr;
        var vjmlsisa    = 0;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if(typeof $('#idnota'+i).val() != 'undefined'){
                vnota = parseFloat(formatulang($('#vnilai'+i).val()));
                if (!isNaN(parseFloat($('#vbayar'+i).val()))){
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                }else{
                    vjmlitem = 0;
                }
                vsisaitem = vnota - vjmlitem;
                if (vsisaitem < 0) {
                    swal("Maaf :(","Jumlah bayar tidak bisa lebih besar dari nilai nota !!!!!","error");
                    $('#vbayar'+i).val(0);
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                    vsisaitem = parseFloat(formatulang($('#vnilai'+i).val()));
                }
                vlebihitem = vlebihitem - vjmlitem;
                if (vlebihitem < 0) {
                    vlebihitem = vlebihitem + vjmlitem;
                    vsisaitem = vnota - vlebihitem;
                    swal("Maaf :(","Jumlah item tidak bisa lebih besar dari nilai bayar !!!!!","error");
                    $('#vbayar'+i).val(formatcemua(vlebihitem));
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                    vlebihitem = 0;
                }
                vjmlsisa += vjmlitem; 
                $('#vsisa'+i).val(formatcemua(vsisaitem));
                $('#vlebih'+i).val(formatcemua(vlebihitem));
            }
        }
        $("#vjumlahsisa").val(formatcemua(vjmlbyr-vjmlsisa));
        $("#vjumlahlebih").val(formatcemua(vlebihitem));
    }
    /*----------  END HITUNG NILAI  ----------*/    

    /*----------  VALIDASI UPDATE DATA  ----------*/    
    $( "#submit" ).click(function(event) {
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                $("#tabledatay tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val()=='' || $(this).val()==null) {
                            swal('Maaf :(','No. Nota tidak boleh kosong!','error');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                            swal('Maaf :(','Jumlah Bayar Tidak Boleh Kosong Atau 0!','error');
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
        }
        return false;     
    })
</script>