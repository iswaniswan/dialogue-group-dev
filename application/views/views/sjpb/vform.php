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
                        <label class="col-md-6">Tanggal SJPB</label>
                        <div class="col-sm-6">
                            <input required="" readonly id= "dsj" name="dsj" class="form-control date" value="<?= date('d-m-Y');?>">
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-md-6">Kode Area - Area - Kode Store</label><label class="col-md-6">No. SPMB</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" class="form-control select2" onchange="cekarea(this.value);">
                                <option value=""></option>
                                <?php if($area){ 
                                    foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name." - ".$key->i_store;?></option>
                                    <?php } 
                                } ?>
                            </select>
                            <input id="istore" name="istore" type="hidden">
                        </div>
                        <div class="col-sm-6">
                            <select required="" id= "ispmb" name="ispmb" class="form-control" disabled="" onchange="getdetailspmb(this.value);"></select>
                        </div>
                    </div>               
                    <div class="form-group row">
                        <label class="col-md-12">Kodelang - Nama Customer - Kode SPG</label>
                        <div class="col-sm-12">
                            <select required="" id= "icustomer" name="icustomer" class="form-control" disabled="" onchange="getspb();"></select>
                            <input type="hidden" name="ispg" id="ispg">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nilai</label><label class="col-md-6">Tanggal SPMB</label>
                        <div class="col-sm-6">
                            <input name="vsj" id="vsj" value="0" readonly="" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input required="" readonly id= "dspmb" name="dspmb" class="form-control" value="">
                        </div>
                    </div>                         
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales($('jml').val());"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
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
                                <th style="text-align: center; width: 40%;">Nama Barang</th>
                                <th style="text-align: center;">Motif</th>
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
    function getspb() {
        var ispg  = $('#icustomer option:selected').text();
        var spg   = ispg.substr(-4);
        $('#ispg').val(spg);
    }

    function cekarea(kode) {
        var iarea  = $('#iarea option:selected').text();
        var istore = iarea.substr(-2);
        if (kode!='') {
            $("#istore").val(istore);
            $("#ispmb").attr("disabled", false);
            $("#icustomer").attr("disabled", false);
        }else{
            $("#ispmb").attr("disabled", true);
            $("#icustomer").attr("disabled", true);
        }
        $('#ispmb').html('');
        $('#ispmb').val('');
        $('#icustomer').html('');
        $('#icustomer').val('');
    }

    function getdetailspmb(kode) {
        var iarea = $('#iarea').val();
        var ispmb = $('#ispmb option:selected').text();
        var dspmb = ispmb.substr(-10);
        if (kode!='') {
            $('#dspmb').val(dspmb);
            $("#tabledata").attr("hidden", false);
            $("#cek").attr("hidden", false);
        }else{
            $("#tabledata").attr("hidden", true);
            $("#cek").attr("hidden", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'ispmb': kode,
                'iarea': iarea
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
                    var qty         = data['detail'][a]['n_order'];
                    var harga       = data['detail'][a]['harga'];
                    var stock       = data['detail'][a]['n_deliver'];
                    var namabarang  = namaproduk +' ('+motif+')';
                    var vtot        = harga * stock;
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" readonly id="motif'+zz+'" name="motif'+zz+'" value="'+imotif+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                    /*cols += '<td><select class="form-control select2" readonly id="iproduct'+zz+'" name="iproduct'+zz+'" onclick="getdetailbarang('+zz+');"><option value="'+produk+'">'+produk+'</option></select></td>';*/

                    cols += '<td><input class="form-control" readonly id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namabarang+'"></td>';
                    cols += '<td><input readonly class="form-control" id="emotifname'+zz+'" name="emotifname'+zz+'" value="'+motif+'"><input type="hidden" class="form-control" style="text-align:right;" id="vproductmill'+zz+'" name="vproductmill'+zz+'" value="'+harga+'"></td>';

                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="norder'+zz+'" name="norder'+zz+'" value="'+qty+'"></td>';

                    cols += '<td><input onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiall();pembandingnilai('+zz+');" class="form-control" style="text-align:right;" id="ndeliver'+zz+'" name="ndeliver'+zz+'" value="'+stock+'"><input type="hidden" id="ndeliverhidden'+zz+'" name="ndeliverhidden'+zz+'" value="'+stock+'"><input type="hidden" id="vtotal'+zz+'" name="vtotal'+zz+'" value="'+vtot+'"></td>';
                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" onclick="hitungnilaiall();pembandingnilai('+zz+');"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    /*$('#iproduct'+zz).select2();*/
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function pembandingnilai(a){
        var n_qty = document.getElementById('norder'+a).value;
        var n_deliver   = document.getElementById('ndeliver'+a).value;
        var deliverasal = document.getElementById('ndeliverhidden'+a).value;
        if(parseInt(n_deliver) > parseInt(n_qty)) {
            swal('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+n_qty+' item )');
            document.getElementById('ndeliver'+a).value   = deliverasal;
            hitungnilai();
            document.getElementById('ndeliver'+a).focus();
            return false;
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
        var jml = parseFloat($('#jml').val());
        for(brs=1;brs<=jml;brs++){
            if($("#chk"+brs).is(':checked')){    
                psn = $("#norder"+brs).val();
                $("#ndeliver"+brs).val(psn);
            }else{
                $("#ndeliver"+brs).val(0);
            }
        }
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
        $('#iarea').select2({
            placeholder: 'Pilih Area'
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
                    var query = {
                        q: params.term,
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

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kodelang / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea           = $('#iarea').val();
                    var query = {
                        q: params.term,
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

    function dipales(a){
        var dspmb = document.getElementById('dspmb').value;
        var dsj   = document.getElementById('dsj').value;
        dtmpspmb  = dspmb.split('-');
        perspmb   = dtmpspmb[2]+dtmpspmb[1]+dtmpspmb[0];
        dtmpsj    = dsj.split('-');
        persj     = dtmpsj[2]+dtmpsj[1]+dtmpsj[0];
        if (persj<perspmb) {
            swal("Tanggal SJPB tidak boleh lebih kecil dari tanggal SPMB !!!");
            return false;
        }else{
            return true;
        }
        var a = $('#jml').val();
        if((document.getElementById("icustomer").value!='') && (document.getElementById("dsj").value!='') && (document.getElementById("iarea").value!='') && (document.getElementById("ispmb").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
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