<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Customer</label>
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
                                    <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                    <input type="hidden" name="idocumentold" id="iknold" value="<?= $data->i_document;?>">                                    
                                    <input type="text" name="idocument" required="" id="ikn" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $number;?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document;?>">
                            </div>
                            <div class="col-sm-4">
                                <select name="icustomer" id="icustomer" class="form-control select2" required="">
                                    <option value="<?= $data->id_customer;?>"><?= $data->e_customer_name.' ('.$data->i_customer.')';?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Nomor Referensi</label>
                            <label class="col-md-2">Tanggal Referensi</label>
                            <label class="col-md-7">Keterangan</label>                            
                            <div class="col-sm-3">
                                <select name="ireferensi" id="ireferensi" class="form-control select2" required="">
                                    <option value="<?= $data->id_referensi;?>"><?= $data->i_referensi;?></option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="drefrensi" placeholder="dd-mm-yyyy" name="drefrensi" class="form-control input-sm" readonly value="<?= $data->d_referensi;?>">
                            </div>
                            <div class="col-sm-7">
                                <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
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
                    </div>
                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <span class="notekode"><b>Note : </b></span><br>
                                <span class="notekode">* Hanya yang Qty Retur nya lebih dari 0 yang akan tersimpan.</span><br>
                                <span class="notekode">* Mohon untuk diedit kembali Qty Retur apabila Qty Permintaan lebih kecil dari Qty Retur!</span>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0; if ($datadetail) {?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th class="text-center" width="30%;">Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center" width="15%;">Disc 123 (%)</th>
                                <th class="text-center">Disc (Rp.)</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datadetail as $key) {?>
                                <tr>
                                    <td class="text-center"><spanx id="snum<?=$i;?>"><?=$i+1;?></spanx></td>
                                    <td>
                                        <select data-nourut="<?=$i;?>" id="idproduct<?=$i;?>" class="form-control input-sm select2" name="idproduct<?=$i;?>">
                                            <option value="<?= $key->id_product;?>"><?= $key->i_product.' - '.$key->e_product;?></option>
                                        </select>
                                    </td>
                                    <td><input type="text" id="nquantity<?=$i;?>" class="form-control text-right input-sm inputitem" name="nquantity<?=$i;?>" readonly value="<?= $key->n_quantity;?>"></td>
                                    <td><input type="text" readonly class="form-control input-sm text-right" name="vharga<?=$i;?>" id="vharga<?=$i;?>" value="<?= number_format($key->v_price);?>"/></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%1" name="ndisc1<?=$i;?>" id="ndisc1<?=$i;?>" value="<?= $key->n_diskon1;?>"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1<?=$i;?>" id="vdisc1<?=$i;?>" value="<?= $key->v_diskon1;?>"/>
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%2" name="ndisc2<?=$i;?>" id="ndisc2<?=$i;?>" value="<?= $key->n_diskon2;?>"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2<?=$i;?>" id="vdisc2<?=$i;?>" value="<?= $key->v_diskon2;?>"/>
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%3" name="ndisc3<?=$i;?>" id="ndisc3<?=$i;?>" value="<?= $key->n_diskon3;?>"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3<?=$i;?>" id="vdisc3<?=$i;?>" value="<?= $key->v_diskon3;?>"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td><input type="text" class="form-control input-sm text-right" name="vdiscount<?=$i;?>" id="vdiscount<?=$i;?>" readonly value="<?= number_format($key->v_diskon_tambahan);?>"/></td>
                                    <td><input type="text" readonly class="form-control input-sm text-right" name="vtotal<?=$i;?>" id="vtotal<?=$i;?>"  value="<?= number_format($key->v_price * $key->n_quantity);?>"/><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon<?=$i;?>" id="vtotaldiskon<?=$i;?>"  value="<?= $key->v_diskon_total;?>"/></td>
                                    <td><input type="text" class="form-control input-sm" name="eremark<?=$i;?>" id="eremark<?=$i;?>" readonly value="<?= $key->e_remark;?>"></td>
                                </tr>
                                <?php $i++; } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right" colspan="6">Total :</td>
                                <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm text-right" value="<?= $data->v_kotor;?>" readonly></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">Diskon :</td>
                                <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm text-right" readonly value="<?= $data->v_diskon;?>"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">DPP :</td>
                                <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm text-right" value="<?= $data->v_dpp;?>" readonly></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">PPN (10%) :</td>
                                <td><input type="text" id="vppn" name="vppn" class="form-control input-sm text-right" value="<?= $data->v_ppn;?>" readonly></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">Grand Total :</td>
                                <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm text-right" value="<?= $data->v_bersih;?>" readonly></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
<input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        /*----------  Load Form Validation  ----------*/        
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('#ikn').mask('SSS-0000-000000S');        
        $('.select2').select2();
        hitungtotal();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);

        for (var i = 0; i < $('#jml').val(); i++) {
            $('#idproductbbm'+i).select2({
                placeholder: 'Cari Barang',
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/product'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term,
                            ireferensi : $('#ireferensi').val(),
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
            })
        }

        /*----------  Cari Pelanggan  ----------*/        
        $('#icustomer').select2({
            placeholder: 'Cari Customer, Kode / Nama',
            width: '100%',
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
            $("#ireferensi").val('');
            $("#ireferensi").html('');
            $("#tabledatay > tbody").remove();
            $("#jml").val(0);
        });

        /*----------  Cari Referensi  ----------*/        
        $('#ireferensi').select2({
            placeholder: 'Cari Nota Referensi',
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
            $("#tabledatay > tbody").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id' : $(this).val(),
                },
                url: '<?= base_url($folder.'/cform/getdetailref'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['detail']!=null) {
                        $('#drefrensi').val(data['head'].d_document)
                        $('#jml').val(data['detail'].length);
                        var group = '';
                        var no    = 0;
                        for (let i = 0; i < data['detail'].length; i++) {
                            no++;
                            var newRow = $("<tr>");
                            var cols   = "";
                            cols += `<td class="text-center">${no}</td>`;
                            cols += `<td>

                                    <select data-nourut="${i}" id="idproduct${i}" class="form-control select2 input-sm" name="idproduct${i}">
                                        <option value="${data['detail'][i]['id_product']}">${data['detail'][i]['i_product']} - ${data['detail'][i]['e_product']}</option>
                                    </select>
                                    </td>`;
                            cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" name="nquantity${i}" value="${data['detail'][i]['n_quantity']}" readonly></td>`;
                            cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vharga${i}" id="vharga${i}" value="${data['detail'][i]['v_price']}"/></td>`;
                            cols += `<td>
                                        <div class="row">
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%1" name="ndisc1${i}" id="ndisc1${i}" value="${data['detail'][i]['n_diskon1']}"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1${i}" id="vdisc1${i}" value="${data['detail'][i]['v_diskon1']}"/>
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%2" name="ndisc2${i}" id="ndisc2${i}" value="${data['detail'][i]['n_diskon2']}"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2${i}" id="vdisc2${i}" value="${data['detail'][i]['n_diskon2']}"/>
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%3" name="ndisc3${i}" id="ndisc3${i}" value="${data['detail'][i]['n_diskon3']}"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3${i}" id="vdisc3${i}" value="${data['detail'][i]['n_diskon3']}"/>
                                            </div>
                                        </div>
                                    </td>`;
                            cols += `<td><input type="text" class="form-control input-sm text-right" name="vdiscount${i}" id="vdiscount${i}" readonly value="${data['detail'][i]['v_diskon3']}"/></td>`;
                            cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vtotal${i}" id="vtotal${i}" value="0"/><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon${i}" id="vtotaldiskon${i}" value="0"/></td>`;
                            cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" value="${data['detail'][i]['e_remark']}" readonly/></td>`;
                            newRow.append(cols);
                            $("#tabledatay").append(newRow);
                            $('#idproduct'+i).select2();
                        }
                        hitungtotal();
                    }
                },
                error: function () {
                    swal('Error :)');
                }
            });
        });
        /*----------  End Referensi  ----------*/        
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/    
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/    
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#ikn').val($('#iknold').val());
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
                    $('#ikn').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }
    }   

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ikn").attr("readonly", false);
        }else{
            $("#ikn").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/    
    $( "#ikn" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#ikn').val() != $('#iknold').val())) {
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

    /*----------  HITUNG NILAI  ----------*/
    function hitungtotal(){
        var total    = 0;
        var totaldis = 0;
        var vjumlah  = 0;
        var dpp      = 0;
        var ppn      = 0;
        var grand    = 0;
        for (var i = 0; i < $('#jml').val(); i++) {
            if(typeof $('#idproduct'+i).val() != 'undefined'){
                if (!isNaN(parseFloat($('#nquantity'+i).val()))){
                    var qty = parseFloat($('#nquantity'+i).val());
                }else{
                    var qty = 0;
                }
                var jumlah = formatulang($('#vharga'+i).val()) * qty;
                var disc1  = formatulang($('#ndisc1'+i).val());
                var disc2  = formatulang($('#ndisc2'+i).val());
                var disc3  = formatulang($('#ndisc3'+i).val());
                if (!isNaN(parseFloat($('#vdiscount'+i).val()))){
                    var disc4 = formatulang($('#vdiscount'+i).val());
                }else{
                    var disc4 = 0;
                }
                var ndisc1 = jumlah * (disc1/100);
                var ndisc2 = (jumlah - ndisc1) * (disc2/100);
                var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3/100);

                var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseFloat(disc4));

                var vtotal  = jumlah - vtotaldis;

                $('#vdisc1'+i).val(ndisc1);
                $('#vdisc2'+i).val(ndisc2);
                $('#vdisc3'+i).val(ndisc3);
                $('#vtotaldiskon'+i).val(formatcemua(vtotaldis));
                $('#vtotal'+i).val(formatcemua(jumlah));
                $('#vtotalnet'+i).val(formatcemua(vtotal));
                totaldis += vtotaldis;
                vjumlah += jumlah;
                total += vtotal;
            }
        }
        $('#nkotor').val(formatcemua(vjumlah));
        $('#ndiskontotal').val(formatcemua(totaldis));

        dpp     = vjumlah - totaldis;
        ppn     = dpp * 0.1;
        grand   = dpp + ppn;

        $('#nbersih').val(formatcemua(grand));
        $('#vdpp').val(formatcemua(dpp));
        $('#vppn').val(formatcemua(ppn));
    }

    /*----------  VALIDASI UPDATE DATA  ----------*/    
    $( "#submit" ).click(function(event) {
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                var qty = 0;
                $("#tabledatay tbody tr td .inputitem").each(function() {
                    qty += parseInt($(this).val());
                });
                $("#tabledatay tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val()=='' || $(this).val()==null) {
                            swal('Barang tidak boleh kosong!');
                            ada = true;
                        }
                    });
                });
                if (parseInt(qty)==0) {
                    swal('Maaf','Total Qty Retur Tidak Boleh 0!','error');
                    return false;
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
        }
        return false;     
    })
</script>