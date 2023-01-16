<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal SPB</label>
                        <div class="col-sm-12">
                            <input type="text" id= "dspb" name="dspb" class="form-control date"  value="<?= date('d-m-Y');?>" readonly>
                            <input id="ispb" name="ispb" type="hidden">
                            <input id="iperiode" name="iperiode" type="hidden" value="">
                            <input id="dspbsys" name="dspbsys" type="hidden" value=""></td>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Group Barang</label>
                        <div class="col-sm-12">
                            <select name="productgroup" id="productgroup" class="form-control" onchange="group(this.value);">
                                <option value="">-- Pilih Group Barang --</option>
                                <?php if ($group) {
                                    foreach ($group as $key) { ?>
                                        <option value="<?= $key->i_product_group;?>"><?= $key->e_product_groupname;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input id="istore" name="istore" type="hidden">
                            <input id="fstock" name="fstock" type="hidden">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Kode Promo</label>
                        <div class="col-sm-12">
                            <select name="ipromo" id="ipromo" class="form-control" onchange="promo(this.value);" disabled="">
                                <option value="">-- Pilih Kode Promo --</option>
                                <?php if ($promo) {
                                    foreach ($promo as $key) { ?>
                                        <option value="<?= $key->i_promo;?>"><?= $key->i_promo." - ".$key->e_promo_name;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input id="istore" name="istore" type="hidden">
                            <input id="fstock" name="fstock" type="hidden">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" id="iarea" class="form-control select2" onchange="getarea(this.value);" disabled=""></select>
                            <input id="eareaname" name="eareaname" type="hidden">
                            <input id="istore" name="istore" type="hidden">
                            <input id="fstock" name="fstock" type="hidden">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <select name="icustomer" id="icustomer" class="form-control select2" onchange="getdetailpel(this.value);" disabled=""></select>
                            <input id="icustomergroup" name="icustomergroup" type="hidden">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input readonly type="text" id="ecumstomeraddress" name="ecumstomeraddress" class="form-control" maxlength="100" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-12">
                            <input type="text" id="ispbpo" name="ispbpo" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group row" hidden="true">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Konsinyasi</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">SPB Lama</label>
                            <div class="col-12">
                                <input class="form-control" type="text" value="" id="ispbold" name="ispbold">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Stock Daerah</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nspbtoplength" class="col-3 col-form-label">TOP</label>
                            <div class="col-12">
                                <input class="form-control" name="nspbtoplength" id="nspbtoplength" type="text" readonly="" value="">
                            </div>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <select class="form-control select2" name="isalesman" id="isalesman" onchange="getsales(this.value);"></select>
                            <input type="hidden" readonly id="isalesmanx" name="isalesmanx" class="form-control" maxlength="30" value="">
                            <input type="hidden" readonly id="esalesmannamex" name="esalesmannamex" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Stock Daerah</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" maxlength="7" value="">
                            <input type="text" id="isj" name="isj" class="form-control" maxlength="15" value="" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="dsj" name="dsj" class="form-control date" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PKP</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" class="form-control" maxlength="30" value="">
                            <input id="fspbplusppn" name="fspbplusppn" type="hidden">
                            <input id="fspbplusdiscount" name="fspbplusdiscount" type="hidden">
                            <input id="fspbpkp" name="fspbpkp" type="hidden">
                            <input id="fcustomerfirst" name="fcustomerfirst" type="hidden">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-5">
                            &nbsp;
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                &nbsp;&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" disabled=""><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"> 
                        <div class="form-group row">
                            <label class="col-md-12">Kelompok Harga</label>
                            <div class="col-sm-12">
                                <input type="text" id="epricegroupname" name="epricegroupname" class="form-control" required="" readonly>
                                <input id="ipricegroup" name="ipricegroup" type="hidden">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Nilai Kotor</label>
                            <div class="col-sm-12">
                                <input type="text" id="vspb" name="vspb" class="form-control" required="" readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Discount 1</label><label class="col-md-6">Nilai Discount 1</label>
                            <div class="col-sm-6">
                                <input id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control" required="" readonly value="0">
                            </div>
                            <div class="col-sm-6">
                                <input id= "vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" required="" readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Discount 2</label><label class="col-md-6">Nilai Discount 2</label>
                            <div class="col-sm-6">
                                <input id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" required="" readonly value="0">
                            </div>
                            <div class="col-sm-6">
                                <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" required=""
                                readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Discount 3</label><label class="col-md-6">Nilai Discount 3</label>
                            <div class="col-sm-6">
                                <input id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" required="" readonly value="0">
                            </div>
                            <div class="col-sm-6">
                                <input id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" required=""
                                readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Discount 4</label><label class="col-md-6">Nilai Discount 4</label>
                            <div class="col-sm-6">
                                <input id="ncustomerdiscount4" name="ncustomerdiscount4" class="form-control" required="" readonly value="0">
                            </div>
                            <div class="col-sm-6">
                                <input id="vcustomerdiscount4" name="vcustomerdiscount4" class="form-control" required=""
                                readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Discount Total</label>
                            <div class="col-sm-12">
                                <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" required="" 
                                value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Nilai Bersih</label>
                            <div class="col-sm-12">
                                <input id="vspbbersih" name="vspbbersih" class="form-control" required="" 
                                readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Discount Total (Realisasi)</label>
                            <div class="col-sm-12">
                                <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" required="" 
                                readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Nilai SPB (Realisasi)</label>
                            <div class="col-sm-12">
                                <input id="vspbafter" name="vspbafter" class="form-control" required="" 
                                readonly value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 10%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center;">Motif</th>
                                    <th style="text-align: center;">Harga</th>
                                    <th style="text-align: center;">Qty Pesan</th>
                                    <th style="text-align: center;">Total</th>
                                    <th style="text-align: center;">Keterangan</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    var xx = 0;
    $("#addrow").on("click", function () {
        xx++;
        /*document.getElementById("jml").value = xx;*/
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="iproductstatus'+xx+'" name="iproductstatus'+xx+'" value=""></td>';
        cols += '<td><select type="text" id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getharga('+xx+');" value=""></td>';
        cols += '<td><input type="text" id="eproductname'+xx+'" type="text" class="form-control" name="eproductname'+xx+'" value="" readonly></td>';
        cols += '<td><input type="text" id="emotifname'+xx+'" type="text" class="form-control" name="emotifname'+xx+'" value="" readonly></td>';

        cols += '<td><input type="text" id="vproductretail'+xx+'" class="form-control" name="vproductretail'+xx+'"/ readonly><input readonly type="hidden" id="v_product_min'+xx+'" name="v_product_min'+xx+'" value="" readonly></td>';
        cols += '<td><input type="text" value="" id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event)" onblur="cekminimal('+xx+');" onkeyup="hitungnilai(this.value,'+xx+')" autocomplete="off"><input type="hidden" id="nquantitystock'+xx+'" name="nquantitystock'+xx+'" value=""></td>';
        cols += '<td><input type="text" id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'"/ readonly></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrg/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var kdharga     = $('#ipricegroup').val();
                    var groupbarang = $('#productgroup').val();
                    var ipromo      = $('#ipromo').val();
                    var kdgroup     = $('#icustomergroup').val();
                    var query   = {
                        q       : params.term,
                        kdharga : kdharga,
                        group   : groupbarang,
                        ipromo  : ipromo,
                        kdgroup : kdgroup
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        document.getElementById("jml").value = xx;
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 0, 2);

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getpelanggan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dspb = $('#dspb').val();
                    var i_area = $('#iarea').val();
                    var i_promo = $('#ipromo').val();
                    var query = {
                        q: params.term,
                        dspb: dspb,
                        i_area: i_area,
                        i_promo: i_promo
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


        $('#isalesman').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsales/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var i_area = $('#iarea').val();
                    var d_spb  = $('#dspb').val();
                    var query = {
                        q: params.term,
                        i_area: i_area,
                        d_spb: d_spb
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

    function getarea(iarea){
        if (iarea!='') {
            $("#icustomer").attr("disabled", false);
        }else{
            $("#icustomer").attr("disabled", true);
        }
    }

    function getdetailpel(icustomer){
        var dspb  = $('#dspb').val();
        var iarea = $('#iarea').val();
        var ipromo = $('#ipromo').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': icustomer,
                'dspb'     : dspb,
                'iarea'    : iarea,
                'ipromo'   : ipromo
            },
            url: '<?= base_url($folder.'/cform/getdetailpel'); ?>',
            dataType: "json",
            success: function (data) {
                var type = data[0].type;
                if (type=='1') {
                    $('#ncustomerdiscount1').val(data[0].disc1);
                    $('#ncustomerdiscount2').val(data[0].disc2);
                    $('#ncustomerdiscount3').val('0.00');
                    $('#ncustomerdiscount4').val('0.00');
                }else if(type=='2'){
                    $('#ncustomerdiscount1').val('0.00');
                    $('#ncustomerdiscount2').val('0.00');
                    $('#ncustomerdiscount3').val('0.00');
                    $('#ncustomerdiscount4').val('0.00');
                }else if(type=='3'){
                    if(data[0].n_customer_discount1=='0.00'){
                        d1=data[0].disc1;
                        d2=data[0].disc2;
                        d3='0.00';
                        d4='0.00';
                    }else if(data[0].n_customer_discount2=='0.00'){
                        d1=data[0].n_customer_discount1;
                        d2=data[0].disc1;
                        d3=data[0].disc2;
                        d4='0.00';
                    }else{
                        d1=data[0].n_customer_discount1;
                        d2=data[0].n_customer_discount2;
                        d3=data[0].disc1;
                        d4=data[0].disc2;
                    }
                    $('#ncustomerdiscount1').val(d1);
                    $('#ncustomerdiscount2').val(d2);
                    $('#ncustomerdiscount3').val(d3);
                    $('#ncustomerdiscount4').val(d4);
                }else if(type=='4'){
                    if(data[0].n_customer_discount1=='0.00'){
                        d1='0.00';
                        d2='0.00';
                        d3='0.00';
                        d4='0.00';
                    }else if(data[0].n_customer_discount2=='0.00'){
                        d1=data[0].n_customer_discount1;
                        d2='0.00';
                        d3='0.00';
                        d4='0.00';
                    }else{
                        d1=data[0].n_customer_discount1;
                        d2=data[0].n_customer_discount2;
                        d3='0.00';
                        d4='0.00';
                    }
                    $('#ncustomerdiscount1').val(d1);
                    $('#ncustomerdiscount2').val(d2);
                    $('#ncustomerdiscount3').val(d3);
                    $('#ncustomerdiscount4').val(d4);
                }else if(type=='5'){
                    if(data[0].n_customer_discount1=='0.00'){
                        d1=data[0].disc1;
                        d2='0.00';
                        d3='0.00';
                        d4='0.00';
                    }else if(data[0].n_customer_discount2=='0.00'){
                        d1=data[0].n_customer_discount1;
                        d2=data[0].disc1;
                        d3='0.00';
                        d4='0.00';
                    }else{
                        d1=data[0].n_customer_discount1;
                        d2=data[0].n_customer_discount2;
                        d3=data[0].disc1;
                        d4='0.00';
                    }
                }else if(type=='6'){
                    $('#ncustomerdiscount1').val(data[0].disc1);
                    $('#ncustomerdiscount2').val('0.00');
                    $('#ncustomerdiscount3').val('0.00');
                    $('#ncustomerdiscount4').val('0.00');
                }
                $('#ecustomerpkpnpwp').val(data[0].e_customer_pkpnpwp);
                $('#epricegroupname').val(data[0].e_price_groupname);
                $('#ipricegroup').val(data[0].i_price_group);
                $('#ecumstomeraddress').val(data[0].e_customer_address);
                $('#fcustomerfirst').val(data[0].f_customer_first);
                $('#nspbtoplength').val(data[0].n_customer_toplength);
                $('#select2-isalesman-container').html(data[0].e_salesman_name+'-'+data[0].i_salesman);
                $('#isalesmanx').val(data[0].i_salesman);
                $('#esalesmannamex').val(data[0].e_salesman_name); 
                $('#fspbplusppn').val(data[0].f_customer_plusppn); 
                $('#fspbplusdiscount').val(data[0].f_customer_plusdiscount);
                $('#icustomergroup').val(data[0].i_customer_group);
                hitungnilai(0,0);          
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function getsales(isalesman){
        var dspb  = $('#dspb').val();
        var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'isalesman': isalesman,
                'dspb'     : dspb,
                'iarea'    : iarea
            },
            url: '<?= base_url($folder.'/cform/getdetailsal'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isalesmanx').val(data[0].i_salesman);
                $('#esalesmannamex').val(data[0].e_salesman_name);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function group(group){
        if (group!='') {
            $('#addrow').attr("disabled", false);
            $("#ipromo").attr("disabled", false);
            $("#iarea").attr("disabled", false);
        }else{
            $('#addrow').attr("disabled", true);
            $("#ipromo").attr("disabled", true);
            $("#iarea").attr("disabled", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        xx = 0;
    }

    function promo(ipromo) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getarea');?>",
            data:"ipromo="+ipromo,
            dataType: 'json',
            success: function(data){
                $("#iarea").html(data.kop);
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
        if (promo!='') {
            $("#iarea").attr("disabled", false);
        }else{
            $("#iarea").attr("disabled", true);
        }
    }

    function getharga(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var e = $('#motif'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                alert ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var iproduct    = $('#iproduct'+id).val();
            var kdharga     = $('#ipricegroup').val();
            var groupbarang = $('#productgroup').val();
            var ipromo      = $('#ipromo').val();
            var kdgroup     = $('#icustomergroup').val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct,
                    'kdharga'   : kdharga,
                    'group'     : groupbarang,
                    'ipromo'    : ipromo,
                    'kdgroup'   : kdgroup
                },
                url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#vproductretail'+id).val(formatcemua(data[0].harga));
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#motif'+id).val(data[0].motif);
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    function hitungnilai(isi,jml){      
        jml=document.getElementById("jml").value;      
        if (isNaN(parseFloat(isi))){          
            /*alert("Input harus numerik");*/      
        }else{          
            dtmp1=parseFloat(formatulang(document.getElementById("ncustomerdiscount1").value));          
            dtmp2=parseFloat(formatulang(document.getElementById("ncustomerdiscount2").value));          
            dtmp3=parseFloat(formatulang(document.getElementById("ncustomerdiscount3").value));
            dtmp4=parseFloat(formatulang(document.getElementById("ncustomerdiscount4").value));
            vdis1=0;
            vdis2=0;
            vdis3=0;
            vdis4=0;
            vtot =0;        
            for(i=1;i<=jml;i++){              
                vhrg=formatulang(document.getElementById("vproductretail"+i).value);              
                if (isNaN(parseFloat(document.getElementById("norder"+i).value))){                  
                    nqty=0;              
                }else{                
                    nqty=formatulang(document.getElementById("norder"+i).value);                
                    jumm = parseFloat(document.getElementById("norder"+i).value);                
                    min = parseFloat(document.getElementById("v_product_min"+i).value);
                }  
                hrg =parseFloat(vhrg)*parseFloat(nqty);  
                vtot=vtot+hrg;  
                document.getElementById("vtotal"+i).value=formatcemua(hrg);
            }
            vdis1=vdis1+((vtot*dtmp1)/100);
            vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            vdis4=vdis4+(((vtot-(vdis1+vdis2+vdis3))*dtmp4)/100);
            document.getElementById("vcustomerdiscount1").value=formatcemua(Math.round(vdis1));
            document.getElementById("vcustomerdiscount2").value=formatcemua(Math.round(vdis2));
            document.getElementById("vcustomerdiscount3").value=formatcemua(Math.round(vdis3));
            document.getElementById("vcustomerdiscount4").value=formatcemua(Math.round(vdis4));
            vtotdis=vdis1+vdis2+vdis3+vdis4;
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        }
    }

    function dipales(a){    
        cek='false';    
        if((document.getElementById("dspb").value!='') &&        
            (document.getElementById("icustomer").value!='') &&        
            (document.getElementById("iarea").value!='') &&          
            (document.getElementById("ipricegroup").value!='')&&          
            (document.getElementById("esalesmanname").value!='')&&          
            (document.getElementById("isalesman").value!='')) {        
            if(a==0){            
                alert('Isi data item minimal 1 !!!');        
            }else{                
                for(i=1;i<=a;i++){            
                    var min = parseFloat(document.getElementById("v_product_min"+i).value);            
                    var norder = parseFloat(document.getElementById("norder"+i).value);            
                    if(norder < min){                    
                        alert('Jumlah Pesan Tidak Boleh Kurang Dari '+min);                    
                        document.getElementById('norder'+i).value = min;                    
                        cek='false';                    
                        exit();                
                    }else{                    
                        if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("norder"+i).value=='')){
                            alert('Data item masih ada yang salah !!!');                        
                            exit();                                                                        
                            cek='false';                                        
                        }else{                        
                            cek='true';                     
                        }                                   
                    }            
                }        
            }        
            if(cek=='true'){            
                document.getElementById("login").disabled=true;            
                document.getElementById("cmdtambahitem").disabled=true;    
            }else{            
                document.getElementById("login").disabled=false;        
            }    
        }else{        
            alert('Data header masih ada yang salah !!!');    
        }  
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function cekminimal(jml){
        var min = parseFloat(document.getElementById("v_product_min"+jml).value);
        var norder = parseFloat(document.getElementById("norder"+jml).value);

        if(norder < min){
            alert('Jumlah Pesan Tidak Boleh Kurang Dari '+min);
            document.getElementById('norder'+jml).value = min;
        }
    }

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
            return true;
    }
</script>