<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom.'/'.$dto ;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            
            <div class="panel-body table-responsive">
            <div id="pesan"></div>  
                <input readonly type = "hidden" name = "dfrom" id = "dfrom" value = "<?= $dfrom ;?>">
                <input readonly type = "hidden" name = "dto" id = "dto" value = "<?= $dto ;?>">
                <div class="col-md-6">    
                    <div class="form-group row">
                        <label class="col-md-12">Jenis Keluar</label>
                        <div class="col-sm-4">
                            <input type = "hidden" class = "form-control" id = "ijenis" name = "ijenis" value = "" readonly>
                            <select name="jnskeluar" id="jnskeluar" class="form-control select2" onchange = "cekform(this.value)">
                                <option value="" selected disabled>Pilih Jenis Keluar</option>
                                <option value="0">Keluar Baru</option>
                                <option value="1">Pendingan</option>
                            </select>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>   

                    <div class="form-group row" id = "rbk">
                        <label id = "dep" class="col-md-5">Gudang Pembuat</label><label class="col-md-7">Tgl Bon Keluar</label><label id = "nobon" class="col-md-5"></label>
                        <div class="col-sm-5" id = "deplist">
                            <select name="idepartement" id="idepartement" class="form-control">
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_departement);?>"<?php if ($key->i_departement==$this->session->userdata('i_departement')) {
                                            echo "selected";
                                        }?>><?= $key->e_departement_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date"  value="" readonly>
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row" id = "rbk2">
                        <label id = "nobonk" class="col-md-5">Dokumen Referensi</label><label class="col-md-7">Tgl Dok Referensi</label>
                        <div class="col-sm-5" id = "divbonk">
                            <select name="ibonk" id="ibonk" class="form-control select2" value = "" onchange="getbonk(this.value);"></select>
                            <input readonly type = "hidden" id = "itujuanx" name = "itujuanx" value = "">
                            <input readonly type = "hidden" id = "idepartementx" name = "idepartementx" value = "">
                            <input readonly type = "hidden" id = "ischedulex" name = "ischedulex" value = "">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dreferensi" name="dreferensi" class="form-control"  value="" readonly>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>

                    <div class="form-group row" id = "rtujuan">
                        <label class="col-md-12">Gudang Penerima</label>
                        <div class="col-sm-5">
                            <select name="itujuan" id="itujuan" class="form-control">
                                <?php if ($ngadug) {
                                    foreach ($ngadug->result() as $key) { ?>
                                        <option value="<?= trim($key->i_departement);?>"<?php if ($key->i_departement==$this->session->userdata('i_departement')) {
                                            echo "selected";
                                        }?>><?= $key->e_departement_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group row" id = "rschedule">
                        <label class="col-md-5">No Schedule</label><label class="col-md-7">Tgl Schedule</label>
                        <div class="col-sm-5">
                            <select name="ischedule" id="ischedule" class="form-control select2" value = "" onchange="get(this.value);"></select>
                        </div>
                        <div class="col-sm-3">
                            <input readonly type="text" name="dschedule" id="dschedule" class="form-control" value="">
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-7">
                            <button type="submit" hidden="true" id="submit" class="btn btn-success btn-rounded btn-sm" onclick = "return konfirm()"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>               
                    </div>
                </div>
            </div> <!-- Panel body -->
        </div>
    </div>
</div>
<input readonly type="hidden" name="jml" id="jml" value="0">
<input readonly type="hidden" name="jmlwip" id="jmlwip" value="0">
<div class="white-box" id="detail">
    <h3 class="box-title m-b-0">Detail Barang</h3>                
            <div class="panel-body table-responsive" id = "newform">
            <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style = "text-align : center">No</th>
                        <th style = "text-align : center">Kode Material</th>
                        <th style = "text-align : center">Nama Material</th>
                        <th style = "text-align : center">Qty Set</th>
                        <th style = "text-align : center">Kirim (Lembar)</th>
                        <th style = "text-align : center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>    

            <div class="panel-body table-responsive" id = "pendingform">
            <table id="tabledata2" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style = "text-align : center">No</th>
                        <th style = "text-align : center">Kode Material</th>
                        <th style = "text-align : center">Nama Material</th>
                        <th style = "text-align : center">Qty Permintaan</th>
                        <th style = "text-align : center">Qty Pending (Lembar)</th>
                        <th style = "text-align : center">Qty Kirim (Lembar)</th>
                        <th style = "text-align : center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>    
    </div>
</div>
        </form>
<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#rbonk").attr("hidden", false);
    $("#dbonk").attr("disabled", true);
    $("#rtujuan").attr("hidden", true);
    $("#rschedule").attr("hidden", true);
    $("#rbk").attr("hidden", true);
    $("#rbk2").attr("hidden", true);
    $("#detail").attr("hidden", true);
    $("#ibonk").attr("hidden", true);jnskeluar

    var kode = $('#ibonk option:selected').text();
    $('#ecustomername').val(kode);
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#send").attr("hidden", false);
});

function cekform(form) {
    if(form == '0'){
        $("#dbonk").attr("disabled", false);
        $("#rtujuan").attr("hidden", false);
        $("#rschedule").attr("hidden", false);
        $("#detail").attr("hidden", false);
        $("#newform").attr("hidden", false);
        $("#rbk").attr("hidden", false);
        $("#rbk2").attr("hidden", true);
        $("#nobonk").attr("hidden", true);
        $("#nobon").attr("hidden", true);
        $("#dep").attr("hidden", false);
        $("#deplist").attr("hidden", false);
        $("#pendingform").attr("hidden", true);
        $("#divbonk").attr("hidden", true);
        $("#dfererensi").attr("disabled", true);
        $("#submit").attr("hidden", true);
        
        $("#dbonk").val('');
        $("#divbonk").val('');
        $("#ischedule").val('');
        $("#dschedule").val('');
        $("#dreferensi").val('');
        $("#ijenis").val($("#jnskeluar").val());
        
        $("#tabledata2 tbody").remove();
        $("#jnskeluar").attr("disabled", true);
        document.getElementById("jml").value = '0';

        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
            // data:{
            //     'cari': cari,
            // },
            dataType: 'json',
            success: function(data){
                $("#ischedule").html(data.kop);
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
    }else{
        $("#dbonk").attr("disabled", false);
        $("#rtujuan").attr("hidden", true);
        $("#rschedule").attr("hidden", true);
        $("#newform").attr("hidden", true);
        $("#nobon").attr("hidden", false);
        $("#detail").attr("hidden", false);
        $("#rbk").attr("hidden", false);
        $("#rbk2").attr("hidden", false);
        $("#nobonk").attr("hidden", false);
        $("#dep").attr("hidden", true);
        $("#deplist").attr("hidden", true);
        $("#pendingform").attr("hidden", false);
        $("#divbonk").attr("hidden", false);
        $("#submit").attr("hidden", true);
        
        $("#dbonk").val('');
        $("#itujuan").val('');
        $("#idepartement").val('');
        $("#ischedule").val('');
        $("#dschedule").val('');
        $("#dreferensi").val('');
        $("#tabledata tbody").remove();
        $("#jnskeluar").attr("disabled", true);
        $("#ijenis").val($("#jnskeluar").val());
        document.getElementById("jml").value = '0';

        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getbonk');?>",
            dataType: 'json',
            success: function(data){
                $("#ibonk").html(data.kop);
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

function konfirm() {
    if($('#dbonk').val()=='' || $('#dbonk').val()==null){ 
        swal('Isi tanggal bon keluar !');
        return false;
    }else if(document.getElementById("jnskeluar").value == "0"){
            if($('#ischedule').val()=='' || $('#ischedule').val()==null){
                swal('Pilih Schedule !!!');
                return false;
            }

            if((document.getElementById("dbonk").value < document.getElementById("dschedule").value)){
                var dfrom   = splitdate($('#dbonk').val());
                var dto     = splitdate($('#dschedule').val());
            
                if (dfrom!=null && dto!=null) {
                    if (dfrom<dto) {
                        swal('Tanggal Bon Keluar tidak boleh lebih kecil dari tanggal Schedule !');
                        $('#dbonk').val(document.getElementById("dschedule").value);
                        return false;
                        $("#submit").attr("disabled", false);
                    }
                }
            }

            for(i=0;i<$('#jml').val();i++){
                ndeliver    = document.getElementById("ndeliver"+i).value;
                if(ndeliver=='' ||ndeliver==null){
                    var x = false;
                }
            }
            if(x == false){
                swal ('Isi data minimal 1 !');
                return false;
            }
    }else if(document.getElementById("jnskeluar").value == "1"){
        if($('#ibonk').val()=='' || $('#ibonk').val()==null){
            swal('Pilih Dokumen Referensi !!!');
            return false;
        }

        if((document.getElementById("dbonk").value < document.getElementById("dreferensi").value)){
        var dfrom   = splitdate($('#dbonk').val());
        var dto     = splitdate($('#dreferensi').val());
        
            if (dfrom!=null && dto!=null) {
                if (dfrom<dto) {
                    swal('Tanggal Bon Keluar tidak boleh lebih kecil dari tanggal Referensi !');
                    $('#dbonk').val(document.getElementById("dreferensi").value);
                    return false;
                    $("#submit").attr("disabled", false);
                }
            }
        }
            
        for(i=0;i<$('#jml').val();i++){
            ndeliver    = document.getElementById("ndeliver"+i).value;
            if(ndeliver=='' ||ndeliver==null){
                var x = false;
            }
        }
        
        if(x == false){
            swal ('Qty masih kosong !');
            return false;
        }
    }
}

function get(id) {
    $("#tabledata tr:gt(0)").remove();
    $("#jml").val(0);
    $("#submit").attr("hidden", false); 

        $.ajax({
            type: "post",
            url: '<?= base_url($folder.'/cform/getschedule'); ?>',
            data: {
                'ischedule': id
            },
            dataType: "json",
            success: function (data) {
            if((data['data'][0]!=null && data['brgop'] != null)){ 
                $("#tabledata tbody").remove();
                $("#tabledata").attr("hidden", false);  
                $('#dschedule').val(data['data'][0].d_schedule);
                
                var group   = '';
                var b       = 0
                for (let a = 0; a < data['brgop'].length; a++) {
                    var no          = a+1;
                    var nowip       = b+1;
                    var produk      = data['brgop'][a]['i_product'];
                    var namaproduk  = data['brgop'][a]['e_product_name'];
                    var warna       = data['brgop'][a]['warna'];
                    var color       = data['brgop'][a]['i_color'];
                    var material    = data['brgop'][a]['e_material_name'];
                    var imaterial   = data['brgop'][a]['i_material'];
                    var qty         = data['brgop'][a]['n_quantity'];
                    var pemenuhan   = data['brgop'][a]['n_pemenuhan'];
                    var nqtytmp     = data['brgop'][a]['n_qtytmp']; //Dari query controller
                    var sisa        = qty-nqtytmp;
                    var set         = data['brgop'][a]['n_set'];
                    var gelar       = data['brgop'][a]['n_gelar'];
                    var toset       = data['brgop'][a]['v_toset'];
                    var nmaterialtmp= (parseFloat(sisa)/parseFloat(set))*parseFloat(toset);
                    var product     = produk+color;
                    var product2    = "'"+product+"'";

                    var cols        = "";
                    var cols2       = "";
                    
                    var newRow      = $("<tr>");

                    if(group==""){
                        cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+produk+' ('+namaproduk+') - '+warna+' ('+qty+')<input style="width:60px;" class="form-control" type="text" id="nquantity'+b+'" name="nquantity'+b+'" value="'+sisa+'" onkeyup="hitungnilai3(this.value,'+product2+');"><input readonly style="width:60px;" class="form-control" type="hidden" id="nquantityheadertmp'+b+'" name="nquantityheadertmp'+b+'" value="'+sisa+'"></b></td>';//hitungnilai3(this.value,'+product2+');
                    }else{
                        if((group!=product)){
                            b++;
                            cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+produk+' ('+namaproduk+') - '+warna+' ('+qty+')<input style="width:60px;" class="form-control" type="text" id="nquantity'+b+'" name="nquantity'+b+'" value="'+sisa+'" onkeyup="hitungnilai3(this.value,'+product2+');"><input readonly style="width:60px;" class="form-control" type="hidden" id="nquantityheadertmp'+b+'" name="nquantityheadertmp'+b+'" value="'+sisa+'"></b></td>';//hitungnilai3(this.value,'+product2+');<input style="width:60px;" class="form-control" type="hidden" id="nquantitysisaheader'+b+'" name="nquantitysisaheader'+b+'" value="'+sisa+'">
                        }
                    }
                
                    newRow.append(cols2);
                    $("#tabledata").append(newRow);

                    var newRow      = $("<tr>");

                    group=product;
                        /* HEADERNYA */
                        cols += '<td><input style="width:38px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                        cols += '<td hidden><input style="width:85px;" class="form-control" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
                        cols += '<td hidden><input style="width:85px;" class="form-control" readonly type="text" id="iproductcolor'+a+'" name="iproductcolor'+a+'" value="'+product+'"></td>';
                        cols += '<td hidden><input style="width:250px;" class="form-control" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
                        cols += '<td hidden><input readonly style="width:100px;" class="form-control" type="text" id="warna'+a+'" name="warna'+a+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+a+'" name="icolor'+a+'" value="'+color+'"></td>';
                        cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                        cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+material+'"></td>';
                        
                        /* ITEMNYA */
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="nqtyitemtmp'+a+'" name="nqtyitemtmp'+a+'" value ='+qty+'></td>';
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="set'+a+'" name="set'+a+'" value ='+set+'></td>';
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="toset'+a+'" name="toset'+a+'" value ='+toset+'></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control text-right" type="text" id="nmaterial'+a+'" name="nmaterial'+a+'" value="'+nmaterialtmp.toFixed(2)+'"></td>';
                        cols += '<td><input style="width:80px;" class="form-control text-right" type="text" id="ndeliver'+a+'" name="ndeliver'+a+'" value="'+nmaterialtmp.toFixed(2)+'" onkeyup = "cekjml();" placeholder="0"></td>';//;angkahungkul(this)
                        cols += '<td><input style="width:125px;" class="form-control" type="text" id="eremark'+a+'" name="eremark'+a+'" value=""></td>';
                        /******************************************************* */

                    console.log(produk); /* LOG ERROR DI CONSOLE */
                                        
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    //document.getElementById("ndeliver"+a).placeholder = "0"; 
                }
                $('#jml').val(no);
                $('#jmlwip').val(nowip);
            }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function cekjml(){
        if(document.getElementById("jnskeluar").value == "0"){
            for(i=0;i<=$('#jml').val();i++){
                document.getElementById("ndeliver"+i).value = document.getElementById("ndeliver"+i).value.replace(/[^\d.-]/g,'');
                ndeliver    = document.getElementById("ndeliver"+i).value;
                nmaterial   = document.getElementById("nmaterial"+i).value;

                if(parseInt(ndeliver) > parseInt(nmaterial)){
                    swal ('Jumlah Kirim melebihi Schedule !');
                    document.getElementById("ndeliver"+i).value = 0;
                    return false;
                }
            }
        }else if(document.getElementById("jnskeluar").value == "1"){
            for(i=0;i<=$('#jml').val();i++){
                document.getElementById("ndeliver"+i).value = document.getElementById("ndeliver"+i).value.replace(/[^\d.-]/g,'');
                ndeliver    = document.getElementById("ndeliver"+i).value;
                nmaterial  = document.getElementById("nmaterial"+i).value;

                if(parseInt(ndeliver) > parseInt(nmaterial)){
                    swal ('Jumlah Kirim melebihi Sisa !');
                    document.getElementById("ndeliver"+i).value = 0;
                    return false;
                }
            }
        }
    }

    function getbonk(id) {
    $("#tabledata2 tr:gt(0)").remove();
    $("#jml").val(0);
    $("#submit").attr("hidden", false); 

        $.ajax({
            type: "post",
            url: '<?= base_url($folder.'/cform/getbonkdetail'); ?>',
            data: {
                'ibonk': id
            },
            dataType: "json",
            success: function (data) {
            if((data['data'][0]!=null && data['bonkitem'] != null)){ 
                $("#tabledata2 tbody").remove();
                $("#tabledata2").attr("hidden", false);  
                $('#dreferensi').val(data['data'][0].d_bonk);
                $('#itujuanx').val(data['data'][0].tujuan);
                $('#idepartementx').val(data['data'][0].i_departement);
                $('#ischedulex').val(data['data'][0].i_schedule);
                
                var group = '';
                var b     = 0
                for (let a = 0; a < data['bonkitem'].length; a++) {
                    var no          = a+1;
                    var nowip       = b+1;
                    var produk      = data['bonkitem'][a]['i_product'];
                    var namaproduk  = data['bonkitem'][a]['e_product_name'];
                    var color       = data['bonkitem'][a]['i_color_wip'];
                    var warna       = data['bonkitem'][a]['e_color_name'];
                    var material    = data['bonkitem'][a]['e_material_name'];
                    var imaterial   = data['bonkitem'][a]['i_material'];
                    var qty         = data['bonkitem'][a]['qtywip'];
                    var qtymaterial = data['bonkitem'][a]['qtymaterial'];
                    var materialsisa= data['bonkitem'][a]['n_material_sisa'];
                    var sisa        = qtymaterial-materialsisa;
                    var set         = data['bonkitem'][a]['n_set'];
                    var gelar       = data['bonkitem'][a]['n_gelar'];
                    var toset       = data['bonkitem'][a]['v_toset'];
                    var keterangan  = data['bonkitem'][a]['e_remark'];
                    var product     = produk+color;
                    var product2    = "'"+product+"'";

                    var cols        = "";
                    var cols2       = "";
                    
                    var newRow      = $("<tr>");

                    if(group==""){
                        cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+produk+' ('+namaproduk+') - '+warna+' ('+qty+')</b></td>'; //<input style="width:60px;" class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+sisa+'" onkeyup="hitungnilai3(this.value,'+product2+');">
                    }else{
                        if((group!=product)){
                            cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+produk+' ('+namaproduk+') - '+warna+' ('+qty+')</b></td>'; //<input style="width:60px;" class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+sisa+'" onkeyup="hitungnilai3(this.value,'+product2+');">
                        }
                    }
                
                    newRow.append(cols2);
                    $("#tabledata2").append(newRow);

                    var newRow      = $("<tr>");

                    group=product;
                        /* HEADERNYA */
                        cols += '<td><input style="width:38px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                        cols += '<td hidden><input style="width:85px;" class="form-control" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
                        cols += '<td hidden><input style="width:85px;" class="form-control" readonly type="text" id="iproductcolor'+a+'" name="iproductcolor'+a+'" value="'+product+'"></td>';
                        cols += '<td hidden><input style="width:250px;" class="form-control" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
                        cols += '<td hidden><input readonly style="width:100px;" class="form-control" type="text" id="warna'+a+'" name="warna'+a+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+a+'" name="icolor'+a+'" value="'+color+'"></td>';
                        cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                        cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+material+'"></td>';
                        
                        /* ITEMNYA */
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="nqtyitemtmp'+a+'" name="nqtyitemtmp'+a+'" value ='+qty+'></td>';
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="set'+a+'" name="set'+a+'" value ='+set+'></td>';
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="toset'+a+'" name="toset'+a+'" value ='+toset+'></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control text-right" type="text" id="nmaterialasa'+a+'" name="nmaterialasal'+a+'" value="'+qtymaterial+'"></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control text-right" type="text" id="nmaterial'+a+'" name="nmaterial'+a+'" value="'+sisa+'"></td>';//;angkahungkul(this)
                        cols += '<td><input style="width:80px;" class="form-control text-right" type="text" id="ndeliver'+a+'" name="ndeliver'+a+'" value="" onkeyup = "cekjml();" placeholder="0"></td>';//;angkahungkul(this)
                        cols += '<td><input style="width:125px;" class="form-control" type="text" id="eremark'+a+'" name="eremark'+a+'" value="'+keterangan+'"></td>';
                        /******************************************************* */

                    console.log(produk); /* LOG ERROR DI CONSOLE */
                                        
                    newRow.append(cols);
                    $("#tabledata2").append(newRow);
                    //document.getElementById("ndeliver"+a).placeholder = "0"; 
                }
                $('#jml').val(no);
            }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function hitungnilai3(qty,kode){   
        for(var a=0;a<$('#jml').val();a++){
        var i = a;  
        
        if(kode == document.getElementById("iproductcolor"+i).value){
            //alert (qty+'|'+i+'|'+$('#jml').val()+'|'+document.getElementById("sisa"+i).value);
                //sisa     = formatulang(document.getElementById("sisa"+i).value);
                toset    = formatulang(document.getElementById("toset"+i).value);
                set      = formatulang(document.getElementById("set"+i).value);
                if(qty=='')qty=0;
                document.getElementById("nqtyitemtmp"+i).value = qty;

                jmllembar = (parseFloat(qty)/parseFloat(set))*parseFloat(toset);
                document.getElementById("nmaterial"+i).value=(jmllembar).toFixed(2);
                document.getElementById("ndeliver"+i).value=(jmllembar).toFixed(2);

                /* CEK QTY HEADER APA MELEBIHI QTY WIP DI SCHEDULE / TIDAK */
                for(var x=0;x<$('#jmlwip').val();x++){
                    if(parseInt(document.getElementById("nquantity"+x).value) > parseInt(document.getElementById("nquantityheadertmp"+x).value)){
                        swal("Jumlah Qty melebihi Schedule");
                        document.getElementById("nquantity"+x).value = document.getElementById("nquantityheadertmp"+x).value;
                        return false;
                    }
                }
                /* ****************************************************** */
               
                //jmlgelar=parseFloat(qty)/parseFloat(vset);
            
            // if(document.getElementById("fbisbisan"+i).value == 'f'){
            //     pjngkain=(parseFloat(qty)/parseFloat(vset))*parseFloat(vgelar);
            //     document.getElementById("jumgelar"+i).value=(jmlgelar).toFixed(2);
            // }else{
            //     pjngkain  = (parseFloat(qty)*parseFloat(vgelar)*parseFloat(vset))/parseFloat(bagibis);
            // }
            //     document.getElementById("pjgkain"+i).value=(pjngkain).toFixed(2);
        }
      }
    } 

// var counter = 0;
// $("#addrow").on("click", function () {
        
//         counter++;
//         document.getElementById("jml").value = counter;
//         var ikodemaster = $("#ikodemaster").val();
//         var ijenisbrg   = $("#ijenisbrg").val();
//         var ikategori   = $("#ikategori").val();
//         count=$('#tabledata tr').length;
//         var newRow = $("<tr>");
                
//         var cols = "";
//         cols += '<td class="text-center"><spanx class="text-center" id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
//         cols += '<td><input  type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct'+ counter + '"></td>';
//         cols += '<td><select style="width:100px;" type="text" id="eproductname'+ counter + '" class="form-control" name="eproductname'+ counter + '" onchange="getmaterial('+ counter + ');"></td>';
//         cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark"/><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan[]" onkeyup="cekval(this.value);"/></td>';
//         cols += '<td><button type="button" id="delete" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

//         newRow.append(cols);
//         $("#tabledata").append(newRow);
       
//         $('#eproductname'+ counter).select2({
        
//         placeholder: 'Pilih Material',
//         templateSelection: formatSelection,
//         allowClear: true,
//         type: "POST",
//         ajax: {          
//           url: '<?= base_url($folder.'/cform/datamaterial/'); ?>',
//           dataType: 'json',
//           delay: 250,
//           processResults: function (data) {
//             return {
//               results: data
//             };
//           },
//           cache: true
//         }
//       });
//     });

    // function formatSelection(val) {
    //     return val.name;
    // }

    // $("#tabledata").on("click", ".ibtnDel", function (event) {
    //     $(this).closest("tr").remove();
    //     counter -= 1
    //     document.getElementById("jml").value = counter;
    // }); 

/* function getmaterial(id) {
    // removeBody();
    var iproduct = $('#eproductname'+counter).val();
    // alert(ischedule);
    $.ajax({
        type: "post",
        data: {
            'iproductwip': iproduct
        },
        url: '<?= base_url($folder.'/cform/getmateriall'); ?>',
        dataType: "json",
        success: function (data) {
            var iproduct = data['head']['i_kodebrg'];
            
            $('#iproduct'+counter).val(iproduct);
        
             $('#jmldetail').val(data['detail'].length);
            for (let a = 0; a < data['detail'].length; a++) {
                var zz = a+1;
                

                var produk      = data['detail'][a]['i_product'];
                var namaproduk  = data['detail'][a]['e_namabrg'];
                var material    = data['detail'][a]['i_material'];
                var namamaterial= data['detail'][a]['e_material_name'];
                var warna       = data['detail'][a]['e_color_name'];
                var color       = data['detail'][a]['i_color'];
                var x = $('#jmldetail').val();

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input style="width:100px;" class="form-control" readonly type="hidden" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"><input style="width:300px;" class="form-control" readonly type="hidden" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namaproduk+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="imaterial'+zz+'" name="imaterial'+zz+'" value="'+material+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="ematerialname'+zz+'" name="ematerialname'+zz+'" value="'+namamaterial+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="warna'+zz+'" name="warna'+zz+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+zz+'" name="icolor'+zz+'" value="'+color+'"></td>';
                cols += '<td><input  class="form-control" style="width:100px;"  type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="0"></td>';
                cols += '<td><input  class="form-control" style="width:100px;"  type="text" id="nquantitym'+zz+'" name="nquantitym'+zz+'" value="0" onkeyup="validasi('+zz+');"><input  class="form-control" style="width:100px;"  type="hidden" id="nquantitya'+zz+'" name="nquantitya'+zz+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="keterangan'+zz+'" name="keterangan'+zz+'" value=""></td>';
                cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" ></td>';
                newRow.append(cols);
                $("#tabledatadetail").append(newRow);
                
                $('#i_2material'+zz).select2({
                    placeholder: 'Pilih Material',
                    allowClear: true,
                    ajax: {
                        url: '<?= base_url($folder);?>/cform/datamaterial/',
                        dataType: 'json',
                        delay: 250,
                      // processResults: function (data) {
                      //   return {
                      //     results: data
                      //   };
                      // },
                      // cache: true
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
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jmldetail').val();
} */

    /* function pembandingnilai(a){
    var n_pemenuhan =  $("#npemenuhan"+a).val();
    var n_qty =  $("#nquantity"+a).val();
    //alert(a);
    //var n_pemenuhan   = document.getElementById('npemenuhan'+a).value;
    //var n_qty = document.getElementById('nquantity'+a).value;
    if(parseInt(n_pemenuhan) > parseInt(n_qty)) {
        swal('Jml kirim ( '+n_pemenuhan+' item ) tdk dpt melebihi Order ('+n_qty+' item)');
        document.getElementById('npemenuhan'+a).value   = n_qty;
        document.getElementById('npemenuhan'+a).focus();
        return false;   
        }
    } */

    /* function validasi(id){
        // alert(id);
        jml=document.getElementById("jmldetail").value;
        for(i=1;i<=jml;i++){
            qtysj   =document.getElementById("nquantity"+i).value;
            qtyretur=document.getElementById("nquantitym"+i).value;
            if(parseFloat(qtyretur)>parseFloat(qtysj)){
                swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah SJ');
                document.getElementById("nquantitym"+i).value=0;
                break;
          }
        }
    } */

</script>