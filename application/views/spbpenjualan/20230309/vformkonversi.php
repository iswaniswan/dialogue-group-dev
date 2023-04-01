<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<form>
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
                            <label class="col-md-4">Distributor</label>
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
                                    <input type="text" name="idocument" id="ispbd" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                   <!--  <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span> -->
                                </div>
                               <!--  <span class="notekode">Format : (<?= $number;?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= date("d-m-Y"); ?>">
                            </div>
                            <div class="col-sm-4">
                                <select name="icustomer" id="icustomer" class="form-control select2" required="">
                                </select>
                                <input type="hidden" id="ecustomer" name="ecustomer" class="form-control" readonly>
                                <input type="hidden" id="ndiskon1" name="ndiskon1" class="form-control" readonly>
                                <input type="hidden" id="ndiskon2" name="ndiskon2" class="form-control" readonly>
                                <input type="hidden" id="ndiskon3" name="ndiskon3" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2">Kelompok Harga</label>
                            <label class="col-md-3">Area</label>
                            <label class="col-md-3">Periode Forecast Distributor</label>
                            <label class="col-md-4">Keterangan</label>                            
                            <div class="col-sm-2">
                                <input type="hidden" id="idkodeharga" name="idkodeharga" class="form-control input-sm">
                                <input type="text" readonly="" id="ekodeharga" name="ekodeharga" class="form-control input-sm" placeholder="Harga Per Pelanggan">
                            </div>              
                            <div class="col-sm-3">
                                <select name="iarea" id="iarea" class="form-control select2" required="">
                                    <?php if ($area) {
                                        foreach ($area as $row):?>
                                            <option value="<?= $row->id;?>">
                                                <?= $row->e_area. ' ('.$row->i_area.')';?>
                                            </option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="ireferensi" id="ireferensi" class="form-control select2" required=""></select>
                                <!-- <input type="text" id="ireferensi" name="ireferensi" class="form-control input-sm" onkeyup="gede(this);" maxlength="20" placeholder="No Referensi Pelanggan"> -->
                                <input type="hidden" id="etypespb" name="etypespb" class="form-control input-sm" value="FC" readonly>
                                <input type="hidden" id="id_jenis_barang_keluar" name="id_jenis_barang_keluar" class="form-control input-sm" value="1" readonly>
                                <input type="hidden" id="isales" name="isales" class="form-control input-sm" value="1" readonly>
                            </div>                                            
                            <div class="col-sm-4">
                                <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>Note : </b></span><br>
                            <span class="notekode">* Harga barang jadi yang digunakan adalah harga exclude.</span><br>
                            <span class="notekode">* Harga sesuai dengan yang di master harga jual barang jadi dan sesuai kelompok harga barang distributornya.</span><br>
                            <span class="notekode">* Tanggal Berlaku master harga jual barang jadi sesuai tanggal dokumen.</span><br>
                            <span class="notekode">* Area bisa disesuaikan dengan pelanggan yang ada areanya!</span><br>
                            <span class="notekode">* Periode Forecast Berdasarkan Tanggal Dokumen.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="jml" id="jml" value ="0">
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
                            <th class="text-center" width="43%;">Barang</th>
                            <th class="text-center" width="10%;">Qty</th>
                            <th class="text-center" width="13%;">Harga</th>
                            <th class="text-center" width="15%;">Disc 123 (%)</th>
                            <th class="text-center" width="7%;">Disc (Rp.)</th>
                            <th class="text-center" width="10%;">Total</th>
                            <th class="text-center" width="10%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="6">Total :</td>
                            <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm text-right" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">Diskon :</td>
                            <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm text-right" readonly value="0"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">DPP :</td>
                            <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm text-right" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">PPN (<span id="xppn">10</span>%) :</td>
                            <td>
                                <input type="text" id="vppn" name="vppn" class="form-control input-sm text-right" value="0" readonly>
                                <input type="hidden" id="nppn" name="nppn" class="form-control input-sm text-right" value="0" readonly>
                            </td>

                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">Grand Total :</td>
                            <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm text-right" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        //$('#ispbd').mask('SSSS-0000-000000S');        
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);

        //showCalendarPeriode('.dateperiode',0)
        number();
        set_ppn();
        //noreferensi();

         $('#ireferensi').select2({
            placeholder: 'Pilih Referensi Forecast',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getforecast'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        idcustomer : $('#icustomer').val(),
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
            getdetailref();
        });            
        /*----------  Cari Pelanggan  ----------*/        
        $('#icustomer').select2({
            placeholder: 'Pilih Pelanggan',
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
            $("#tabledatay > tbody").remove();
            $("#jml").val(0);
            hitungtotal();
            number();
            getdetailref();
            /*$("#iarea").select2("val", "1");*/
        });
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/    
    $('#ibagian, #ddocument').change(function(event) {
        number();
        set_ppn();
        //noreferensi();
    });


    function getdetailref() {
        $("#tabledatay > tbody").remove();
        $("#jml").val(0);

        $.ajax({
            type: "post",
            data: {
                'idcustomer' : $("#icustomer").val(),
                'ireferensi'  : $('#ireferensi').val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailref'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ndiskon1').val(data['head']['v_customer_discount']);
                $('#ndiskon2').val(data['head']['v_customer_discount2']);
                $('#ndiskon3').val(data['head']['v_customer_discount3']);
                $('#ekodeharga').val(data['head']['e_harga_kode']);
                $('#idkodeharga').val(data['head']['id_harga_kode']);
                $('#ecustomer').val(data['head']['e_customer_name']);
                $('#isales').val(data['head']['id_sales']);
                
                $('#jml').val(data['detail'].length);
                for (let i = 0; i < data['detail'].length; i++) {
                    var no     = $('#tabledatay tbody tr').length;
                    var newRow = $("<tr>");
                    var cols   = "";
                    cols += `<td class="text-center"><spanx id="snum${i}">${no+1}</spanx></td>`;
                    cols += `<td>
                                <select data-nourut="${i}" id="idproduct${i}" class="form-control select2 input-sm" name="idproduct${i}">
                                    <option value="${data['detail'][i]['id_product']}">${data['detail'][i]['i_product']} - ${data['detail'][i]['e_product']} - ${data['detail'][i]['e_color_name']}</option>
                                </select>
                            </td>`;
                    cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="${data['detail'][i]['n_quantity_fc']}" onkeyup="angkahungkul(this); hitungtotal();"></td>`;
                    cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vharga${i}" id="vharga${i}" value="${data['detail'][i]['v_price']}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="angkahungkul(this);hitungtotal();"/></td>`;
                    cols += `<td>
                                <div class="row">
                                    <div class="col-sm-4 pudding">
                                        <input type="text" class="form-control input-sm text-right" placeholder="%1" name="ndisc1${i}" id="ndisc1${i}" value="${data['head']['v_customer_discount']}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungtotal();"/>
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1${i}" id="vdisc1${i}"/>
                                    </div>
                                    <div class="col-sm-4 pudding">
                                        <input type="text" class="form-control input-sm text-right" placeholder="%2" name="ndisc2${i}" id="ndisc2${i}" value="${data['head']['v_customer_discount2']}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungtotal();" />
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2${i}" id="vdisc2${i}"/>
                                    </div>
                                    <div class="col-sm-4 pudding">
                                        <input type="text" class="form-control input-sm text-right" placeholder="%3" name="ndisc3${i}" id="ndisc3${i}" value="${data['head']['v_customer_discount3']}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungtotal();"/>
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3${i}" id="vdisc3${i}"/>
                                    </div>
                                </div>
                            </td>`;
                    cols += `<td><input type="text" class="form-control input-sm text-right" name="vdiscount${i}" id="vdiscount${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);"/></td>`;
                    cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vtotal${i}" id="vtotal${i}" value="0"/><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon${i}" id="vtotaldiskon${i}" value="0"/></td>`;
                    cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Jika Ada!"/></td>`;
                    newRow.append(cols);
                    $("#tabledatay").append(newRow);
                    $('#idproduct'+i).select2();
                }
                hitungtotal();
            },
            error: function () {
                swal('Error :)');
            }
        });
    }
    /*----------  RUNNING NUMBER DOKUMEN  ----------*/    
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#ddocument').val(),
                'ibagian' : $('#ibagian').val(),
                'idcustomer' : $('#icustomer').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ispbd').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    /*----------  NO REFERENSI FORCAST  ----------*/
    function noreferensi() {
        var d = $('#ddocument').val();
        var dd = d.split("-")[0];
        var mm = d.split("-")[1];
        var yy = d.split("-")[2];
        var ireferensi = 'FC-'+yy+mm;
        $('#ireferensi').val(ireferensi);
    }    

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ispbd").attr("readonly", false);
        }else{
            $("#ispbd").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/    
    $( "#ispbd" ).keyup(function() {
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

    function set_ppn() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
            },
            url: '<?= base_url($folder . '/cform/get_ppn'); ?>',
            dataType: "json",
            success: function(data) {
                $('#nppn').val(data);
                $('#xppn').text(data);
                hitungtotal();
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

     /*----------  HITUNG NILAI  ----------*/
    function hitungtotal() {
        var total = 0;
        var totaldis = 0;
        var vjumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand = 0;
        for (var i = 0; i < $('#jml').val(); i++) {
            if (typeof $('#idproduct' + i).val() != 'undefined') {
                if (!isNaN(parseFloat($('#nquantity' + i).val()))) {
                    var qty = parseFloat($('#nquantity' + i).val());
                } else {
                    var qty = 0;
                }
                var jumlah = formatulang($('#vharga' + i).val()) * qty;
                var disc1 = formatulang($('#ndisc1' + i).val());
                var disc2 = formatulang($('#ndisc2' + i).val());
                var disc3 = formatulang($('#ndisc3' + i).val());
                if (!isNaN(parseFloat($('#vdiscount' + i).val()))) {
                    var disc4 = formatulang($('#vdiscount' + i).val());
                } else {
                    var disc4 = 0;
                }
                var ndisc1 = jumlah * (disc1 / 100);
                var ndisc2 = (jumlah - ndisc1) * (disc2 / 100);
                var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3 / 100);

                var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseFloat(disc4));

                var vtotal = jumlah - vtotaldis;

                $('#vdisc1' + i).val(ndisc1);
                $('#vdisc2' + i).val(ndisc2);
                $('#vdisc3' + i).val(ndisc3);
                $('#vtotaldiskon' + i).val(formatcemua(vtotaldis));
                $('#vtotal' + i).val(formatcemua(jumlah));
                $('#vtotalnet' + i).val(formatcemua(vtotal));
                totaldis += vtotaldis;
                vjumlah += jumlah;
                total += vtotal;
            }
        }
        $('#nkotor').val(formatcemua(vjumlah));
        $('#ndiskontotal').val(formatcemua(totaldis));

        dpp = vjumlah - totaldis;
        ppn = dpp * (parseFloat($('#nppn').val()) / 100);
        grand = dpp + ppn;

        $('#nbersih').val(formatcemua(grand));
        $('#vdpp').val(formatcemua(dpp));
        $('#vppn').val(formatcemua(ppn));
    }

    /*----------  HITUNG NILAI  ----------*/
    function hitungtotal_old(){
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
        ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()!=null) && ($('#iarea').val()!='' || $('#iarea').val()!=null) && ($('#icustomer').val()!='' || $('#icustomer').val()!=null)) {
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                $("#tabledatay tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val()=='' || $(this).val()==null) {
                            swal('Barang tidak boleh kosong!');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                            swal('Quantity Tidak Boleh Kosong Atau 0!');
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
    })
</script>