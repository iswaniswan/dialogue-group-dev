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
                    <!-- <div class="form-group row">
                        <label class="col-md-12">Tanggal OP</label>
                        
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label>
                        <label class="col-md-6">Tanggal Memo</label>
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2">
                                <option value="">-- Pilih Gudang --</option>
                                <?php if ($gudang) {                                   
                                    foreach ($gudang as $igudang) { ?>
                                        <option value="<?php echo $igudang->i_kode_master;?>"><?= $igudang->i_kode_master." - ".$igudang->e_nama_master;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "dspb" name="dspb" class="form-control date" readonly value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">Discount Pelanggan</label>
                        <div class="col-sm-6">
                        <select name="customer" id="customer" class="form-control select2"onchange="getdetailpel(this.value);">
                                <option value="">-- Pilih pelanggan --</option>
                                <?php if ($customer) {                                   
                                    foreach ($customer as $icustomer) { ?>
                                        <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->i_customer." - ".$icustomer->e_customer_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                                <input readonly id="ndiscc" name="ndiscc" class="form-control" required="" 
                                value="0">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input readonly type="text" id="ecumstomeraddress" name="ecumstomeraddress" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row" hidden="true">
                        <div class="col-md-3">
                            <div class="form-check">
                            <input class="form-control" type="text" value="" id="icustomer" name="icustomer">
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
                        <label class="col-md-12">No Memo</label>
                        <div class="col-sm-12">
                            <input type="text" id="imemo" name="imemo" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Bruto</label>
                        <label class="col-md-4">Total Discount</label>
                        <label class="col-md-4">Netto</label>
                        <div class="col-sm-4">
                            <input readonly type="text" id="vspb" name="vspb" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id="vspbbersih" name="vspbbersih" class="form-control">
                        </div>
                    </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control">
                            </div>
                        </div>
                    </div>
                    <input type="text" name="jml" id="jml" value="0">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 5%;">No</th>
                                    <th style="text-align: center; width: 10%;">Kode Barang</th>
                                    <th style="text-align: center; width: 25%;">Nama Barang</th>
                                    <th style="text-align: center; width: 15%;">Harga</th>
                                    <!-- <th style="text-align: center;">Warna</th> -->
                                    <!-- <th style="text-align: center;">Harga</th> -->
                                    <th style="text-align: center; width: 10%;">Qty Pesan</th>
                                    <th style="text-align: center; width: 15%;">Total</th>
                                    <!-- <th style="text-align: center;">Total</th> -->
                                    <th style="text-align: width : 20% center;">Keterangan</th>
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
        var kodemaster  = $('#ikodemaster').val();
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="iproductstatus'+xx+'" name="iproductstatus'+xx+'" value=""></td>';
        cols += '<td><select  type="text" id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getharga('+xx+');"></td>';
        // cols += '<td><select  type="text" id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getharga('+xx+');getdiscount('+xx+');"></td>';
        cols += '<td><input type="text" id="eproductname'+xx+'" type="text" class="form-control" name="eproductname'+xx+'" readonly></td>';
        cols += '<td><input style : 100px type="text" id="vprice'+xx+'" class="form-control" name="vprice'+xx+'" onkeypress="return hanyaAngka(event)"  onkeyup="hitungnilai(this.value,'+xx+')" autocomplete="off"><input type="hidden" id="nquantitystock'+xx+'" name="nquantitystock'+xx+'" value=""></td>';
        // cols += '<td><input type="text" id="ecolorname'+xx+'" type="text" class="form-control" name="ecolorname'+xx+'" readonly></td>';
        // cols += '<td><input type="text" id="vproductretail'+xx+'" class="form-control" name="vproductretail'+xx+'"/ readonly></td>';
        cols += '<td><input style : 100px type="text" id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event)"  onkeyup="hitungnilai(this.value,'+xx+')" autocomplete="off"><input type="hidden" id="nquantitystock'+xx+'" name="nquantitystock'+xx+'" value=""></td>';
        cols += '<td><input type="text" id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'" onkeyup="cekval(this.value); reformat(this);"/ readonly></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        // cols += '<td><input type="text" id="icolor'+xx+'" type="text" class="form-control" name="icolor'+xx+'" readonly></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            
            placeholder: 'Cari Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrg/'); ?>'+"/"+kodemaster,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var kdharga     = $('#ipricegroup').val();
                    var groupbarang = $('#productgroup').val();
                    var istore      = $('#istore').val();
                    var fstock      = $('#fstock').val();
                    var query   = {
                        q       : params.term,
                        kdharga : kdharga,
                        group   : groupbarang,
                        istore  : istore,
                        fstock  : fstock
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
        var iarea = $('#iarea').val();
        $('.select2').select2();
        showCalendar('.date', 0, 5);

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getpelanggan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var i_area = $('#iarea').val();
                    var query = {
                        q: params.term,
                        i_area: i_area
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


        // $('#isalesman').select2({
        //     placeholder: 'Cari Berdasarkan Kode / Nama',
        //     allowClear: true,
        //     ajax: {
        //         url: '<?= base_url($folder.'/cform/getsales/'); ?>',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             var i_area = $('#iarea').val();
        //             var d_spb  = $('#dspb').val();
        //             var query = {
        //                 q: params.term,
        //                 i_area: i_area,
        //                 d_spb: d_spb
        //             }
        //             return query;
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: false
        //     }
        // });
    });

    // function getarea(iarea) {
    //     $.ajax({
    //         type: "POST",
    //         url: "<#?php echo site_url($folder.'/Cform/getarea');?>",
    //         data:"iarea="+iarea,
    //         dataType: 'json',
    //         success: function(data){
    //             $("#istore").val(data.istore);
    //             // $("#fstock").val(data.fstock);
    //             // $("#eareaname").val(data.earea);
    //             // $('#icustomer').empty();
    //         },

    //         error:function(XMLHttpRequest){
    //             swal(XMLHttpRequest.responseText);
    //         }

    //     })
    // }

    function getdetailpel(icustomer){
        var dspb  = $('#dspb').val();
        $('#addrow').attr("disabled", false);
        // var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': icustomer,
                // 'dspb'     : dspb,
                // 'iarea'    : iarea
            },
            url: '<?= base_url($folder.'/cform/getdetailpel'); ?>',
            dataType: "json",
            success: function (data) {
                // $('#ecustomerpkpnpwp').val(data[0].e_customer_pkpnpwp);
                // $('#ncustomerdiscount1').val(data[0].n_customer_discount1);
                // $('#ncustomerdiscount2').val(data[0].n_customer_discount2);
                // $('#ncustomerdiscount3').val(data[0].n_customer_discount3);
                // $('#epricegroupname').val(data[0].e_price_groupname);
                // $('#ipricegroup').val(data[0].i_price_group);
                $('#ecumstomeraddress').val(data[0].e_customer_address);
                $('#ndiscc').val(data[0].v_customer_discount);
                // $('#fcustomerfirst').val(data[0].f_customer_first);
                // $('#nspbtoplength').val(data[0].n_customer_toplength);
                // $('#select2-isalesman-container').html(data[0].e_salesman_name+'-'+data[0].i_salesman);
                // $('#isalesmanx').val(data[0].i_salesman);
                //  $('#esalesmannamex').val(data[0].e_salesman_name); 
                // $('#fspbplusppn').val(data[0].f_customer_plusppn); 
                // $('#fspbplusdiscount').val(data[0].f_customer_plusdiscount);          
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    // function getsales(isalesman){
    //     var dspb  = $('#dspb').val();
    //     var iarea = $('#iarea').val();
    //     $.ajax({
    //         type: "post",
    //         data: {
    //             'isalesman': isalesman,
    //             'dspb'     : dspb,
    //             'iarea'    : iarea
    //         },
    //         url: '<?= base_url($folder.'/cform/getdetailsal'); ?>',
    //         dataType: "json",
    //         success: function (data) {
    //             $('#isalesmanx').val(data[0].i_salesman);
    //             $('#esalesmannamex').val(data[0].e_salesman_name);
    //         },
    //         error: function () {
    //             swal('Error :)');
    //         }
    //     });
    // }

    // function group(group){
    //     if (group!='') {
    //         $('#addrow').attr("disabled", false);
    //     }else{
    //         $('#addrow').attr("disabled", true);
    //     }
    //     $("#tabledata tr:gt(0)").remove();       
    //     $("#jml").val(0);
    //     xx = 0;
    // }

    // function getharga(id){
    //     ada=false;
    //     var a = $('#iproduct'+id).val();
    //     var e = $('#motif'+id).val();
    //     var x = $('#jml').val();
    //     for(i=1;i<=x;i++){            
    //         if((a == $('#iproduct'+i).val()) && (i!=x)){
    //             swal ("kode : "+a+" sudah ada !!!!!");            
    //             ada=true;            
    //             break;        
    //         }else{            
    //             ada=false;             
    //         }
    //     }
    //     if(!ada){
    //         var iproduct    = $('#iproduct'+id).val();
    //         var kdharga     = $('#ipricegroup').val();
    //         var groupbarang = $('#productgroup').val();
    //         var istore      = $('#istore').val();
    //         var fstock      = $('#fstock').val();
    //         $.ajax({
    //             type: "post",
    //             data: {
    //                 'iproduct'  : iproduct,
    //                 'kdharga'   : kdharga,
    //                 'group'     : groupbarang,
    //                 'istore'    : istore,
    //                 'fstock'    : fstock
    //             },
    //             url: '<#?= base_url($folder.'/cform/getdetailbar'); ?>',
    //             dataType: "json",
    //             success: function (data) {
    //                 $('#eproductname'+id).val(data[0].nama);
    //                 $('#vproductretail'+id).val(formatcemua(data[0].harga));
    //                 $('#emotifname'+id).val(data[0].namamotif);
    //                 $('#motif'+id).val(data[0].motif);
    //             },
    //             error: function () {
    //                 swal('Error :)');
    //             }
    //         });
    //     }else{
    //         $('#iproduct'+id).html('');
    //         $('#iproduct'+id).val('');
    //     }
    // }

    function getdiscount(id){
        var ibranch = $('#ibranch').val();
        $.ajax({
        type: "post",
        data: {
            'i_branch': ibranch
        },
        url: '<?= base_url($folder.'/cform/getdiscount'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ncustomerdiscount1'+id).val(data[0].n_customer_discount1);
            $('#ncustomerdiscount2'+id).val(data[0].n_customer_discount2);
            $('#ncustomerdiscount3'+id).val(data[0].n_customer_discount3);

        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function getharga(id){
        var iproduct = $('#iproduct'+id).val();
        $.ajax({
        type: "post",
        data: {
            'imaterial': iproduct
        },
        url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
        dataType: "json",
        success: function (data) {
            $('#eproductname'+id).val(data[0].e_material_name);
            $('#vprice'+id).val(data[0].v_price);
            // $('#ecolorname'+id).val(data[0].e_color_name);
            // $('#icolor'+id).val(data[0].i_color);

            ada=false;
            var a = $('#iproduct'+id).val();
            var c = $('#icolor'+id).val();
            var e = $('#eproductname'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
	            if((a == $('#iproduct'+i).val()) && (c == $('#icolor'+i).val()) && (i!=jml)){
	            	swal ("kode : "+a+" sudah ada y !!!!!");
	            	ada=true;
	            	break;
	            }else{
	            	ada=false;	   
	            }
            }
            if(!ada){
                var imaterial    = $('#iproduct'+id).val();
                $.ajax({
                    type: "post",
                    data: {
                        'i_product'  : iproduct,
                    },
                    url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#eproductname'+id).val(data[0].e_product_basename);
                        $('#vproductretail'+id).val(data[0].v_unitprice);
                        $('#ecolorname'+id).val(data[0].e_color_name);
                        $('#icolor'+id).val(data[0].i_color);
                    },
                });
            }else{
                $('#iproduct'+id).html('');
                $('#eproductname'+id).val('');
                $('#vproductretail'+id).val('');
                $('#ecolorname'+id).val('');
                $('#icolor'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function getcust(iarea) {
        var customergroup = $('#customergroup').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getcust');?>",
            data: {
            'i_area'            : iarea,
            'group_customer'    : customergroup
            },
            dataType: 'json',
            success: function(data){
                $("#ibranch").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function hitungnilai(isi,jml){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
        
            // dtmp1=parseFloat(formatulang(document.getElementById("ndiscc").value));
            var dtmp1 = $('#ndiscc').val();
            
            // dtmp2=parseFloat(formatulang(document.getElementById("ncustomerdiscount2"+jml).value));
            // dtmp3=parseFloat(formatulang(document.getElementById("ncustomerdiscount3"+jml).value));
            vdis1=0;
            // vdis2=0;
            // vdis3=0;
            vtot =0;
            
            for(i=1;i<=jml;i++){
                
                vhrg=formatulang(document.getElementById("vprice"+i).value);
                
                
                if (isNaN(parseFloat(document.getElementById("norder"+i).value))){
                    nqty=0;
                    
                }else{
                    
                    // if((document.getElementById("fstock").value=='f')){
                    //     nqty=formatulang(document.getElementById("norder"+i).value);
                    //     vhrg=parseFloat(vhrg)*parseFloat(nqty);
                    //     vtot=vtot+vhrg;
                    //     document.getElementById("vtotal"+i).value=formatcemua(vhrg);
                    //}
                    // else{
                        // if(parseFloat(document.getElementById("nquantitystock"+i).value)<parseFloat(document.getElementById("norder"+i).value)){
                        //     swal("Lebih Dari Stock!!!");
                        //     nqty=0;
                        //     vhrg=parseFloat(vhrg)*parseFloat(nqty);
                        //     vtot=vtot+vhrg;
                        //     document.getElementById("norder"+i).value=formatcemua(vhrg);
                        //     document.getElementById("vtotal"+i).value=formatcemua(vhrg);

                        // }
                        // else{

                            nqty=formatulang(document.getElementById("norder"+i).value);
                            vhrg=parseFloat(vhrg)*parseFloat(nqty);
                            vtot=vtot+vhrg;
                            document.getElementById("vtotal"+i).value=formatcemua(vhrg);
                            

                        // }

                    // }
                }////
                
            }

            // swal("dasar cewe");
            
            vdis1=vdis1+((vtot*dtmp1)/100);
            
            // alert("asasa");
            // vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            // vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            // document.getElementById("vcustomerdiscount1"+jml).value=formatcemua(Math.round(vdis1));
            // document.getElementById("vcustomerdiscount2"+jml).value=formatcemua(Math.round(vdis2));
            // document.getElementById("vcustomerdiscount3"+jml).value=formatcemua(Math.round(vdis3));
            vdis1=parseFloat(vdis1);
            // swal(dtmp1);
            // vdis2=parseFloat(vdis2);
            // vdis3=parseFloat(vdis3);
            vtotdis=vdis1+vdis2+vdis3;
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        }
    }

    function dipales(a){   
        cek='false'; 
        if((document.getElementById("dspb").value!='') && 
            (document.getElementById("icustomer").value!='') || 
            // (document.getElementById("iarea").value!='') && 
            // (document.getElementById("ipricegroup").value!='') && 
            // (document.getElementById("esalesmannamex").value!='') && 
            (document.getElementById("isalesmanx").value!=''))
             {    
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{                
                for(i=1;i<=a;i++){                    
                    if((document.getElementById("iproduct"+i).value=='') || 
                        (document.getElementById("eproductname"+i).value=='') || 
                        (document.getElementById("norder"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');                    
                        return false;
                        cek='false';
                    }else{
                        return true;
                        cek='true'; 
                    } 
                }
            }
            if(cek=='true'){
                document.getElementById("submit").disabled=true;
            }else{
                return false;
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }

    function getarea(kode) {
        custgroup=document.getElementById("customergroup").value;
        if(custgroup == 1){
            $("#areaa").attr("hidden", false);
        }else{
            var customergroup = $('#customergroup').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getcust2');?>"+"/"+custgroup,
            data: {
            // 'i_area'            : iarea,
            'group_customer'    : customergroup
            },
            dataType: 'json',
            success: function(data){
                $("#ibranch").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>