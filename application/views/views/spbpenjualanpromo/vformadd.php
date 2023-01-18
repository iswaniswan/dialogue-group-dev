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
                            <label class="col-md-4">Promo</label>
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
                                    <input type="text" name="idocument" id="i_spb" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
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
                                <select name="ipromo" id="ipromo" class="form-control select2" required="" data-placeholder="Pilih Promo">
                                    <option value=""></option>
                                </select>
                                <input type="hidden" id="f_all_product" name="f_all_product">
                                <input type="hidden" id="f_all_customer" name="f_all_customer">
                                <input type="hidden" id="f_all_area" name="f_all_area">
                                <input type="hidden" id="f_plus_discount" name="f_plus_discount" value="t">
                                <input type="hidden" id="n_promo_discount1" name="n_promo_discount1" value="0">
                                <input type="hidden" id="n_promo_discount2" name="n_promo_discount2" value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Area</label>
                            <label class="col-md-5">Customer</label>
                            <label class="col-md-3">Kelompok Harga</label>   
                            <div class="col-sm-4">
                                <select name="iarea" id="iarea" class="form-control select2" required="">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <select name="icustomer" id="icustomer" class="form-control select2" required="">
                                    <option value=""></option>
                                </select>
                                <input type="hidden" id="ecustomer" name="ecustomer" class="form-control" readonly value="0">
                                <input type="hidden" id="ndiskon1" name="ndiskon1" class="form-control" readonly value="0">
                                <input type="hidden" id="ndiskon2" name="ndiskon2" class="form-control" readonly value="0">
                                <input type="hidden" id="ndiskon3" name="ndiskon3" class="form-control" readonly value="0">
                                <input type="hidden" id="ndiskon4" name="ndiskon4" class="form-control" readonly value="0">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" id="idkodeharga" name="idkodeharga" class="form-control input-sm">
                                <input type="text" readonly id="ekodeharga" name="ekodeharga" class="form-control input-sm" placeholder="Harga Per Pelanggan">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Salesman</label>                           
                            <label class="col-md-3">Nomor Referensi</label>
                            <label class="col-md-6">Keterangan</label>    
                            <div class="col-sm-3">
                                <select name="isales" id="isales" class="form-control select2" required="">
                                    <?php if ($salesman) {
                                        foreach ($salesman as $row):?>
                                            <option value="<?= $row->id;?>">
                                                <?= $row->e_sales. ' ('.$row->i_sales.')';?>
                                            </option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ireferensi" name="ireferensi" class="form-control input-sm" onkeyup="gede(this);" maxlength="20" placeholder="No Referensi Pelanggan">
                                <input type="hidden" id="etypespb" name="etypespb" class="form-control input-sm" value="Manual" readonly>
                            </div>                       
                            <div class="col-sm-6">
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
                            <span class="notekode">* Harga sesuai dengan yang di master promo!</span><br>
                            <span class="notekode">* Tanggal Berlaku Promo Berdasarkan tanggal dokumen.</span>
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
            <div class="m-b-0">
                <div class="form-group row">
                    <label class="col-md-5">Kategori Barang</label>
                    <label class="col-md-6">Jenis Barang</label>
                    <label class="col-md-1"></label>
                    <div class="col-sm-5">
                        <select class="form-control select2" name="ikategori" id="ikategori">
                            <option value="all">Semua Kategori</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <select class="form-control select2" name="ijenis" id="ijenis">
                            <option value="all">Semua Jenis</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" id="addrow" hidden="true" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                    </div>
                </div>
            </div>
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
                            <th class="text-center" width="20%;">Disc 1234 (%)</th>
                            <th class="text-center">Disc (Rp.)</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center" width="3%">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="6">Total :</td>
                            <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm text-right clear" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">Diskon :</td>
                            <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm text-right clear" readonly value="0"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">DPP :</td>
                            <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm text-right clear" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">PPN (10%) :</td>
                            <td><input type="text" id="vppn" name="vppn" class="form-control input-sm text-right clear" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="6">Grand Total :</td>
                            <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm text-right clear" value="0" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        $('#i_spb').mask('SSS-0000-000000S');
        $('.select2').select2();

        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);
        number();

        $("#ipromo").select2({
            dropdownAutoWidth: true,
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?= base_url($folder.'/cform/get_promo');?>",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        tanggal: $("#ddocument").val(),
                    };
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        }).change(function(event) {
            $.ajax({
                type: "post",
                data: {
                    i_promo: $(this).val(),
                },
                url: "<?= base_url($folder.'/cform/get_promo_detail');?>",
                dataType: "json",
                success: function(data) {
                    $("#iarea").val("");
                    $("#iarea").html("");
                    $("#icustomer").val("");
                    $("#icustomer").html("");
                    $("#ekodeharga").val("");
                    clear_tabel();
                    if (data["promo"] != null) {
                        $("#f_all_product").val(data["promo"][0]["f_all_product"]);
                        $("#f_all_customer").val(data["promo"][0]["f_all_customer"]);
                        $("#f_all_area").val(data["promo"][0]["f_all_area"]);
                        $("#n_promo_discount1").val(data["promo"][0]["n_promo_discount1"]);
                        $("#n_promo_discount2").val(data["promo"][0]["n_promo_discount2"]);
                        $("#f_plus_discount").val(data["promo"][0]["f_plus_discount"]);
                    } else {
                        swal("Non-existent data : (");
                    }
                },
                error: function() {
                    swal("500 internal server error : (");
                },
            });
        });

        /*----------  GANTI AREA ----------*/    
        $("#iarea").select2({
            placeholder: 'Pilih Area',
            dropdownAutoWidth: true,
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?= base_url($folder.'/cform/get_area');?>",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_promo: $('#ipromo').val(),
                        f_all_area: $('#f_all_area').val(),
                    };
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        }).change(function(event) {
            $("#icustomer").val("");
            $("#icustomer").html("");
            $("#ekodeharga").val("");
            clear_tabel();
        });

        /*----------  Cari Pelanggan  ----------*/        
        $('#icustomer').select2({
            placeholder: 'Pilih Pelanggan',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/get_customer'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        i_area : $('#iarea').val(),
                        i_promo: $('#ipromo').val(),
                        f_all_customer: $('#f_all_customer').val(),
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
            $("#addrow").attr('hidden', false);
            $("#tabledatay > tbody").remove();
            $("#jml").val(0);
            hitungtotal();
            $.ajax({
                type: "post",
                data: {
                    'idcustomer' : $(this).val()
                },
                url: '<?= base_url($folder.'/cform/get_customer_detail'); ?>',
                dataType: "json",
                success: function (data) {
                    clear_tabel();
                    if (data["header"] != null) {
                        let diskon1 = data["header"][0]["n_customer_discount1"];
                        let diskon2 = data["header"][0]["n_customer_discount2"];
                        let diskon3 = $('#n_promo_discount1').val();
                        let diskon4 = $('#n_promo_discount2').val();
                        let f_plus = $("#f_plus_discount").val();
                        if (parseInt(diskon1) > 0 && parseInt(diskon2) > 0 && f_plus == 't') {
                            $("#ndiskon1").val(diskon1);
                            $("#ndiskon2").val(diskon2);
                            $("#ndiskon3").val(diskon3);
                            $("#ndiskon4").val(diskon4);
                        } else if (parseInt(diskon1) > 0 && parseInt(diskon2) <= 0 && f_plus == 't') {
                            $("#ndiskon1").val(diskon1);
                            $("#ndiskon2").val(diskon3);
                            $("#ndiskon3").val(diskon4);
                            $("#ndiskon4").val(0);
                        } else if (parseInt(diskon1) <= 0 && parseInt(diskon2) <= 0 && f_plus == 't') {
                            $("#ndiskon1").val(diskon3);
                            $("#ndiskon2").val(diskon4);
                            $("#ndiskon3").val(0);
                            $("#ndiskon4").val(0);
                        } else {
                            $("#ndiskon1").val(0);
                            $("#ndiskon2").val(0);
                            $("#ndiskon3").val(0);
                            $("#ndiskon4").val(0);
                        }
                        $("#idkodeharga").val(data["header"][0]["id_harga_kode"]);
                        $("#ekodeharga").val(data["header"][0]["e_harga_kode"]);
                        $("#ecustomer").val(data["header"][0]["e_customer_name"]);
                    } else {
                        swal("Non-existent data : (");
                    }
                },
                error: function () {
                    swal('Error :)');
                }
            });
            /*$("#iarea").select2("val", "1");*/
        });

        /*----------  Cari Kategori Barang Sesuai Bagiannya  ----------*/        
        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        /*----------  Cari Jenis Barang Sesuai Bagian dan Kategorinya  ----------*/
        $('#ijenis').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikategori : $('#ikategori').val(),
                        ibagian   : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/    
    $('#ibagian, #ddocument').change(function(event) {
        number();
        $("#tabledatay > tbody").remove();
        $("#jml").val(0);
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
                $('#i_spb').val(data);
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
            $("#i_spb").attr("readonly", false);
        }else{
            $("#i_spb").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/    
    $( "#i_spb" ).keyup(function() {
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

    /*----------  TAMBAH ITEM SPBD  ----------*/
    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        $("#jml").val(parseInt(i)+1);
        var no     = $('#tabledatay tbody tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no+1}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" onchange="getproduct(${i});"></select></td>`;
        cols += `<td>
                    <input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); cekqty(${i}); hitungtotal();">
                    <input type="hidden" id="n_quantity_min${i}" name="n_quantity_min${i}" value="0">
                </td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vharga${i}" id="vharga${i}" value="0"/></td>`;
        cols += `<td>
                    <div class="row">
                        <div class="col-sm-3 pudding">
                            <input type="text" readonly class="form-control input-sm text-right" placeholder="%1" name="ndisc1${i}" id="ndisc1${i}"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1${i}" id="vdisc1${i}"/>
                        </div>
                        <div class="col-sm-3 pudding">
                            <input type="text" readonly class="form-control input-sm text-right" placeholder="%2" name="ndisc2${i}" id="ndisc2${i}"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2${i}" id="vdisc2${i}"/>
                        </div>
                        <div class="col-sm-3 pudding">
                            <input type="text" readonly class="form-control input-sm text-right" placeholder="%3" name="ndisc3${i}" id="ndisc3${i}"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3${i}" id="vdisc3${i}"/>
                        </div>
                        <div class="col-sm-3 pudding">
                            <input type="text" readonly class="form-control input-sm text-right" placeholder="%4" name="ndisc4${i}" id="ndisc4${i}"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc4${i}" id="vdisc4${i}"/>
                        </div>
                    </div>
                </td>`;
        cols += `<td><input type="text" class="form-control input-sm text-right" name="vdiscount${i}" id="vdiscount${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);"/></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vtotal${i}" id="vtotal${i}" value="0"/><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon${i}" id="vtotaldiskon${i}" value="0"/></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Jika Ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $('#idproduct'+ i).select2({
            placeholder: 'Cari Kode / Nama Barang',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/get_product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        i_price_group   : $('#idkodeharga').val(),
                        i_promo         : $("#ipromo").val(),
                        f_all_product   : $("#f_all_product").val(),
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
        });
        i++;
    });
    
    /*----------  GET DETAIL PRODUCT  ----------*/    
    function getproduct(id){
        $.ajax({
            type: "post",
            data: {
                'i_product' : $('#idproduct'+id).val(),
                'f_all_product'   : $('#f_all_product').val(),
                'i_price_group' : $('#idkodeharga').val(),
                'i_promo': $('#ipromo').val(),
            },
            url: '<?= base_url($folder.'/cform/get_product_price'); ?>',
            dataType: "json",
            success: function (data) {
                if (parseInt(data.length) < 1) {
                    swal('Maaf :(','Harga Barang Jadi Periode '+$('#ddocument').val()+' Masih Kosong, Silahkan Input di Master Harga Jual Barang Jadi Atau Master Promo!','error');
                    $('#idproduct'+id).html('');
                    $('#idproduct'+id).val('');
                    return false;
                }
                if(typeof data[0] != 'undefined'){
                    ada = false;
                    for(var i = 0; i < $('#jml').val(); i++){
                        if(($('#idproduct'+id).val() == $('#idproduct'+i).val()) && (i!=id)){
                            swal ("kode : "+data[0].i_product_base+" sudah ada !!!!!");
                            ada = true;
                            break;
                        }else{
                            ada = false;     
                        }
                    }
                    if(!ada){
                        $('#vharga'+id).val(formatcemua(data[0].v_price));                   
                        $('#n_quantity_min'+id).val(data[0].n_quantity_min);
                        $('#nquantity'+id).val(data[0].n_quantity_min);
                        $('#nquantity'+id).focus();
                        $('#ndisc1'+id).val($('#ndiskon1').val());
                        $('#ndisc2'+id).val($('#ndiskon2').val());
                        $('#ndisc3'+id).val($('#ndiskon3').val());
                        $('#ndisc4'+id).val($('#ndiskon4').val());
                        hitungtotal();
                    }else{
                        $('#idproduct'+id).html('');
                        $('#iproduct'+id).val('');
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
                var disc4  = formatulang($('#ndisc4'+i).val());
                if (!isNaN(parseFloat($('#vdiscount'+i).val()))){
                    var disc5 = formatulang($('#vdiscount'+i).val());
                }else{
                    var disc5 = 0;
                }
                var ndisc1 = jumlah * (disc1/100);
                var ndisc2 = (jumlah - ndisc1) * (disc2/100);
                var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3/100);
                var ndisc4 = (jumlah - ndisc1 - ndisc2 - ndisc3) * (disc4/100);

                var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + ndisc4 + parseFloat(disc5));

                var vtotal  = jumlah - vtotaldis;

                $('#vdisc1'+i).val(ndisc1);
                $('#vdisc2'+i).val(ndisc2);
                $('#vdisc3'+i).val(ndisc3);
                $('#vdisc4'+i).val(ndisc4);
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
    
    /*----------  HAPUS TR  ----------*/    
    $("#tabledatay").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        hitungtotal();
        obj = $('#tabledatay tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });        
    });

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

    function clear_tabel() {
        $("#tabledatay tbody").empty();
        $(".clear").val(0);
        $("#jml").val("0");
    }

    function cekqty(i) {
        let qty = parseFloat($("#nquantity"+i).val());
        let min = parseFloat($("#n_quantity_min"+i).val());
        if(qty < min){
            swal('Maaf','Jumlah Order Minimum = '+min+'!', 'error');
            $("#nquantity"+i).val(min);
        }
    }
</script>