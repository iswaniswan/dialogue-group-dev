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
                            <label class="col-md-4">Customer</label>
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
                                    <input type="text" name="idocument" required="" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
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
                            <div class="col-sm-4">
                                <select name="icustomer" id="icustomer" class="form-control select2" required="">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Nomor Referensi</label>
                            <label class="col-md-2">Tanggal Referensi</label>
                            <label class="col-md-7">Keterangan</label>                            
                            <div class="col-sm-3">
                                <select name="ireferensi" id="ireferensi" class="form-control select2" required="">
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="drefrensi" placeholder="dd-mm-yyyy" name="drefrensi" class="form-control input-sm" readonly value="">
                            </div>
                            <div class="col-sm-7">
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
                            <div class="col-sm-12">
                                <span class="notekode"><b>Note : </b></span><br>
                                <span class="notekode">* Hanya yang Qty Retur nya lebih dari 0 yang akan tersimpan.</span>
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
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th class="text-center" width="30%;">Barang Permintaan</th>
                            <th class="text-center">Qty TTB</th>
                            <th class="text-center" width="30%;">Barang Pemenuhan</th>
                            <th class="text-center">Qty BBM</th>
                            <th class="text-center" width="15%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot hidden="true">
                        <tr>
                            <td class="text-right" colspan="7">Total :</td>
                            <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm text-right" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="7">Diskon :</td>
                            <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm text-right" readonly value="0"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="7">DPP :</td>
                            <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm text-right" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="7">PPN (10%) :</td>
                            <td><input type="text" id="vppn" name="vppn" class="form-control input-sm text-right" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="7">Grand Total :</td>
                            <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm text-right" value="0" readonly></td>
                            <td colspan="2"></td>
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

        $('#iretur').mask('SSS-0000-000000S');        
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);
        number();

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
            placeholder: 'Cari Referensi',
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
                                <input type="hidden" name="iddocument${i}" value="${data['detail'][i]['id_document']}">
                                <select id="idproductttb${i}" name="idproductttb${i}" class="form-control select2">
                                    <option value="${data['detail'][i]['id_product']}">${data['detail'][i]['i_product']} - ${data['detail'][i]['e_product']}</option>
                                </select>
                                </td>`;
                            cols += `<td><input type="text" id="nquantitysisa${i}" class="form-control text-right input-sm" value="${data['detail'][i]['n_quantity_sisa']}" readonly></td>`;
                            cols += `<td>
                                <select id="idproductbbm${i}" name="idproductbbm${i}" class="form-control select2" onchange="getdetailproduct(${i});">
                                    <option value="${data['detail'][i]['id_product']}">${data['detail'][i]['i_product']} - ${data['detail'][i]['e_product']}</option>
                                </select>
                                </td>`;
                            cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); cekqty(${i}); hitungtotal();"></td>`;
                            cols += `<td>
                                <input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Jika Ada!"/>
                                <input type="hidden" name="vharga${i}" id="vharga${i}" value="${data['detail'][i]['v_price']}"/>
                                <input type="hidden" name="ndisc1${i}" id="ndisc1${i}" value="${data['detail'][i]['n_diskon1']}"/>
                                <input type="hidden" name="vdisc1${i}" id="vdisc1${i}" value="${data['detail'][i]['v_diskon1']}"/>
                                <input type="hidden" name="ndisc2${i}" id="ndisc2${i}" value="${data['detail'][i]['n_diskon2']}"/>
                                <input type="hidden" name="vdisc2${i}" id="vdisc2${i}" value="${data['detail'][i]['v_diskon2']}"/>
                                <input type="hidden" name="ndisc3${i}" id="ndisc3${i}" value="${data['detail'][i]['n_diskon3']}"/>
                                <input type="hidden" name="vdisc3${i}" id="vdisc3${i}" value="${data['detail'][i]['v_diskon3']}"/>
                                <input type="hidden" name="vdiscount${i}" id="vdiscount${i}" value="${data['detail'][i]['v_diskon_tambahan']}"/>
                                <input type="hidden" name="vtotal${i}" id="vtotal${i}" value="0"/>
                                <input type="hidden" name="vtotaldiskon${i}" id="vtotaldiskon${i}" value="0"/>
                                </td>`;
                            newRow.append(cols);
                            $("#tabledatay").append(newRow);
                            $('#idproductttb'+i).select2();
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
                        hitungtotal();
                    }
                },
                error: function () {
                    swal('Error :)');
                }
            });
        });
    });

    /*----------  CEK QTY  ----------*/
    function cekqty(i) {
        if (parseInt($('#nquantity'+i).val()) > parseInt($('#nquantitysisa'+i).val())) {
            swal('Maaf :(','Qty Retur Tidak Boleh Lebih Besar Dari Qty Permintaan = '+$('#nquantitysisa'+i).val()+'!','error');
            $('#nquantity'+i).val($('#nquantitysisa'+i).val());
            hitungtotal();
        }
    }

    /*----------  GET DETAIL PRODUCT NOTA  ----------*/    
    function getdetailproduct(i) {
        $.ajax({
            type: "post",
            data: {
                'idproduct' : $('#idproductbbm'+i).val(),
                'id'        : $('#ireferensi').val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
            dataType: "json",
            success: function (data) {
                if(typeof data[0] != 'undefined'){
                    $('#vharga'+i).val(data[0].v_price);
                    $('#nquantity'+i).focus();
                }
            },
            error: function () {
                swal('Ada kesalahan :(');
            }
        });
    }    

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
                $('#iretur').val(data);
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
            $("#iretur").attr("readonly", false);
        }else{
            $("#iretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/    
    $( "#iretur" ).keyup(function() {
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