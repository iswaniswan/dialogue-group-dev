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
                        <label class="col-md-6">Tanggal SJP</label><label class="col-md-6">No. SJP</label>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dsj" name="dsj" class="form-control date" value="<?= date('d-m-Y');?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "isjold" name="isjold" class="form-control" value="">
                            <input type="hidden" id= "isj" name="isj" class="form-control" value="">
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label><label class="col-md-6">No. SPMB</label>
                        <div class="col-sm-6">
                            <select name="istore" id="istore" required="" class="form-control select2" onchange="getstore(this.value);">
                                <option value=""></option>
                                <?php if ($store) {                                 
                                    foreach ($store as $key) { ?>
                                        <option value="<?php echo $key->i_store;?>"><?= $key->i_store." - ".$key->e_store_name." - ".$key->i_store_location;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                            <input type="hidden" name="istorelocation" id="istorelocation" class="form-control">
                            <input type="hidden" name="iarea" id="iarea" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <select required="" id= "ispmb" name="ispmb" class="form-control" disabled="" onchange="getdetailspmb(this.value);"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nilai</label><label class="col-md-6">Tanggal SPMB</label>
                        <div class="col-sm-6">
                            <input name="vsj" id="vsj" value="0" readonly="" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dspmb" name="dspmb" class="form-control" value="">
                        </div>
                    </div>                         
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button hidden="true" type="button" id="addrow" onclick="xxrow($('#jml').val());" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Item
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>&nbsp;&nbsp;
                            <label id="cek" hidden="true" class="custom-control custom-checkbox">
                                <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Check All</span>
                            </label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode</th>
                                <th style="text-align: center; width: 35%;">Nama Barang</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center;">Jumlah Order</th>
                                <th style="text-align: center;">Jumlah Kirim</th>
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
    function getstore(kode) {
        var istore = $('#istore option:selected').text();
        var istorelocation = istore.substr(-2);
        if (kode!='') {
            if (kode=='AA') {
                iarea = '00';
            }else{
                iarea = kode;
            }
            $("#istorelocation").val(istorelocation);
            $("#iarea").val(iarea);
            $("#ispmb").attr("disabled", false);
        }else{
            $("#ispmb").attr("disabled", true);
        }

        $('#ispmb').html('');
        $('#ispmb').val('');
    }

    function xxrow(jml) {
        xx = parseFloat(jml)+1;
        uu = xx-1;        
        var iproduct = $('#iproduct'+uu).val();
        alert(xx);  
        alert(uu);          
        alert(iproduct);
        count=$('#tabledata tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            xx = xx-1;
            uu = uu-1;
            return false;
        }
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center">'+count+'<input type="hidden" id="baris'+xx+'" name="baris'+xx+'" value="'+xx+'"><input type="hidden" readonly id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";></select></td>';
        cols += '<td><input class="form-control" readonly id="eproductname'+xx+'" name="eproductname'+xx+'" value=""><input type="hidden" readonly id="iproductgrade'+xx+'" name="iproductgrade'+xx+'" value=""><input type="hidden" readonly class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'" value=""></td>';
        cols += '<td><input class="form-control" id="eremark'+xx+'" name="eremark'+xx+'" value=""><input type="hidden" class="form-control" style="text-align:right;" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value="0"></td>';
        cols += '<td><input class="form-control" style="text-align:right;" id="norder'+xx+'" name="norder'+xx+'" value=""></td>';
        cols += '<td><input onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiall();" class="form-control" style="text-align:right;" id="ndeliver'+xx+'" name="ndeliver'+xx+'" value=""><input type="hidden" id="nstock'+xx+'" name="nstock'+xx+'" value=""><input type="hidden" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+xx+'" name="chk'+xx+'" onclick="hitungnilaiall();" checked></td>';
        newRow.append(cols);
        
        $("#tabledata").append(newRow);
        /*$('#jml').val(xx+1);*/
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode/Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
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
    }

   /* var xx = $('#jml').val();
    var uu = xx-1;
    $("#addrow").on("click", function () {
        xx++;
        uu++;        
        var iproduct = $('#iproduct'+uu).val();
        alert(xx);  
        alert(uu);          
        alert(iproduct);
        count=$('#tabledata tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            xx = xx-1;
            uu = uu-1;
            return false;
        }
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center">'+count+'<input type="hidden" id="baris'+xx+'" name="baris'+xx+'" value="'+xx+'"><input type="hidden" readonly id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";></select></td>';
        cols += '<td><input class="form-control" readonly id="eproductname'+xx+'" name="eproductname'+xx+'" value=""><input type="hidden" readonly id="iproductgrade'+xx+'" name="iproductgrade'+xx+'" value=""><input type="hidden" readonly class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'" value=""></td>';
        cols += '<td><input class="form-control" id="eremark'+xx+'" name="eremark'+xx+'" value=""><input type="hidden" class="form-control" style="text-align:right;" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value="0"></td>';
        cols += '<td><input readonly class="form-control" style="text-align:right;" id="norder'+xx+'" name="norder'+xx+'" value=""></td>';
        cols += '<td><input onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiall();" class="form-control" style="text-align:right;" id="ndeliver'+xx+'" name="ndeliver'+xx+'" value=""><input type="hidden" id="nstock'+xx+'" name="nstock'+xx+'" value=""><input type="hidden" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+xx+'" name="chk'+xx+'" onclick="hitungnilaiall();"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode/Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
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
    });*/


    function getdetailspmb(kode) {
        var ispmb = $('#ispmb option:selected').text();
        var dspmb = ispmb.substr(-10);
        if (kode!='') {
            $('#dspmb').val(dspmb);
            $("#addrow").attr("hidden", false);
            $("#tabledata").attr("hidden", false);
            $("#cek").attr("hidden", false);
        }else{
            $("#addrow").attr("hidden", true);
            $("#tabledata").attr("hidden", true);
            $("#cek").attr("hidden", true);
        }
        $.ajax({
            type: "post",
            data: {
                'ispmb': kode
            },
            url: '<?= base_url($folder.'/cform/getdetailspmb'); ?>',
            dataType: "json",
            success: function (data) {
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var imotif      = data['detail'][a]['motif'];
                    var produk      = data['detail'][a]['kode'];
                    var namaproduk  = data['detail'][a]['nama'];
                    var motif       = data['detail'][a]['namamotif'];
                    var igrade      = data['detail'][a]['i_product_grade'];
                    var qty         = data['detail'][a]['n_qty'];
                    var harga       = data['detail'][a]['harga'];
                    var stock       = data['detail'][a]['stock'];
                    var namabarang  = namaproduk +' ('+motif+')';
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" readonly id="motif'+zz+'" name="motif'+zz+'" value="'+imotif+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                    cols += '<td><input class="form-control" readonly id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namabarang+'"><input type="hidden" readonly id="iproductgrade'+zz+'" name="iproductgrade'+zz+'" value="'+igrade+'"><input type="hidden" readonly class="form-control" id="emotifname'+zz+'" name="emotifname'+zz+'" value="'+motif+'"></td>';
                    cols += '<td><input class="form-control" id="eremark'+zz+'" name="eremark'+zz+'" value=""><input type="hidden" class="form-control" style="text-align:right;" id="vproductmill'+zz+'" name="vproductmill'+zz+'" value="'+harga+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="norder'+zz+'" name="norder'+zz+'" value="'+qty+'"></td>';
                    cols += '<td><input onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiall();" class="form-control" style="text-align:right;" id="ndeliver'+zz+'" name="ndeliver'+zz+'" value="'+stock+'"><input type="hidden" id="nstock'+zz+'" name="nstock'+zz+'" value="'+stock+'"><input type="hidden" id="vtotal'+zz+'" name="vtotal'+zz+'" value="0"></td>';
                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" onclick="hitungnilaiall();"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
    }

    

    
    function getdetailproduct(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            var iproduct = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#vproductmill'+id).val(data[0].harga);
                    /*hitungnilai(id);*/
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
        hitungnilaiall();
        /*if($('#checkAll').is(':checked')){
        }else{
            hitungnilaiall();
        }*/
    });

    function hitungnilaiall(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#ndeliver"+brs).val();
            psn = $("#norder"+brs).val();
            sto = $("#nstock"+brs).val();
            if(parseFloat(psn)<parseFloat(ord)){
                swal('Jumlah kirim tidak boleh lebih besar dari jumlah pesan !!!');
                $("#ndeliver"+brs).val(0);
            }else{
                hrg  = formatulang($("#vproductmill"+brs).val());
                qty  = formatulang(ord);
                vhrg = parseFloat(hrg)*parseFloat(qty);
                $("#vtotal"+brs).val(formatcemua(vhrg));
                if($("#chk"+brs).is(':checked')){
                    tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
                }
                /*for(i=1;i<=jml;i++){
                    swal(tot);
                }*/
            }
        }
        $("#vsj").val(formatcemua(tot));
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#istore').select2({
            placeholder: 'Pilih Gudang'
        });

        $('#ispmb').select2({
            placeholder: 'Cari Berdasarkan No. SPMB',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getspmb/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea           = $('#iarea').val();
                    var istorelocation  = $('#istorelocation').val();
                    var query = {
                        q: params.term,
                        istorelocation: istorelocation,
                        iarea: iarea
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

    function dipales(){
        var dspmb = document.getElementById('dspmb').value;
        var dsj   = document.getElementById('dsj').value;
        dtmpspmb  = dspmb.split('-');
        perspmb   = dtmpspmb[2]+dtmpspmb[1]+dtmpspmb[0];
        dtmpsj    = dsj.split('-');
        persj     = dtmpsj[2]+dtmpsj[1]+dtmpsj[0];
        alert(perspmb);
        alert(persj);
        if (persj<perspmb) {
            swal("Tanggal SJPB tidak boleh lebih kecil dari tanggal SPMB !!!");
            return false;
        }else{
            return true;
        }
        var a = $('#jml').val();
        if((document.getElementById("dspmb").value!='') && (document.getElementById("iarea").value!='') && (document.getElementById("ispmb").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("norder"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true; 
                    } 
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>