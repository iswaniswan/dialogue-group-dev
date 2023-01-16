<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                <input readonly type="hidden" name="dfrom" value = "<?= $dfrom ;?>"><input readonly type="hidden" name="dto" value = "<?= $dto ;?>">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3">Bagian Pembuat</label>
                            <label class="col-sm-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Permintaan</label>
                            <label class="col-md-4">Tujuan</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row):?>
                                            <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                                <?= $row->e_bagian_name;?>
                                            </option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                            </div> 

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                    <input type="hidden" name="ispbbold" id="ispbbpold" value="<?= $data->i_spbb;?>">
                                    <input type="text" name="ispbb" id="ispbb" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="25" class="form-control input-sm" value="<?= $data->i_spbb;?>" aria-label="Text input with dropdown button">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="dspbb" id="dspbb" class="form-control date" disabled="" value="<?php echo $data->d_spbb; ?>" readonly>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="i_type" id="i_type" class="form-control date" disabled="" value="<?php echo $data->gudang; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">No Schedule</label>
                            <label class="col-md-2">Tgl Schedule</label>
                            <label class="col-md-7">Keterangan</label>
                            <div class="col-sm-3">
                               <!--  <select name="ischedule" id="ischedule" class="form-control select2" onchange="get(this.value);"></select> -->
                               <input type="text" name="i_document" id="i_document" class="form-control" disabled="" value="<?php echo $data->i_document; ?>" readonly>
                               <input type="hidden" name="id_schedule" id="id_schedule" class="form-control" value="<?php echo $data->id_schedule; ?>" readonly>
                            </div>

                            <div class="col-sm-2">
                                <input readonly type="text" name="dschedule" id="dschedule" class="form-control" value="<?php echo $data->d_schedule; ?>" readonly>
                            </div>

                            <div class="col-sm-6">
                                <textarea readonly name="eremarkh" class="form-control"><?php echo $data->e_remark; ?></textarea>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-12">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            </div>               
                        </div>   
                    </div> 
            </div>  <!-- panel body -->
        </div>  <!-- panel info -->
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0" readonly>
<div class="white-box" id="detail">
    <h3 class="box-title m-b-0">Detail Barang</h3>
    <div class="panel-body table-responsive">
    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Bahan Baku</th>
                <th>Nama Bahan Baku</th>
                <th>Gelar</th>
                <th>Set</th>
                <th>Jml Gelar</th>
                <th>Panjang Kain</th>
                <th>Bis-bisan</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
$(document).ready(function () {
    $('.select2').select2();
    //$('#ispbb').mask('SSSS-0000-000000S');
    showCalendar('.date');

    $("#submit").attr("disabled", true);
    get($('#id').val());
    // $.ajax({
    //     type: "POST",
    //     url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
    //     // data:{
    //     //     'cari': cari,
    //     // },
    //     dataType: 'json',
    //     success: function(data){
    //         $("#ischedule").html(data.kop);
    //         if (data.kosong=='kopong') {
    //             $("#submit").attr("disabled", true);
    //         }else{
    //             $("#submit").attr("disabled", false);
    //         }
    //     },

    //     error:function(XMLHttpRequest){
    //         alert(XMLHttpRequest.responseText);
    //     }
    // })

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

});

function get(id) {
    // removeBody();
    $("#tabledata tr:gt(0)").remove();
    $("#jml").val(0);
    $("#submit").attr("hidden", false); 
        $.ajax({
            type: "post",
            url: '<?= base_url($folder.'/cform/getscheduleedit'); ?>',
            data: {
                'id_spbb': id
            },
            dataType: "json",
            success: function (data) {
            if(( data['brgop'] != null)){ 
                $("#tabledata tbody").remove();
                $("#tabledata").attr("hidden", false);  
                //$('#ispbb').val(data['data'][0].i_spbb);
                
                var group = '';
                for (let a = 0; a < data['brgop'].length; a++) {
                    var no = a+1;

                    var id_product  = data['brgop'][a]['id_product'];
                    var id_material     = data['brgop'][a]['id_material'];

                    var i_product_wip  = data['brgop'][a]['i_product_wip'];
                    var i_color        = data['brgop'][a]['i_color'];
                    var i_material     = data['brgop'][a]['i_material'];
                    var n_quantity_sisa = data['brgop'][a]['n_quantity_sisa'];
                    var n_quantity = data['brgop'][a]['n_quantity'];


                    var v_set = data['brgop'][a]['v_set'];
                    var v_gelar = parseFloat(data['brgop'][a]['v_gelar']);
                    var total_gelar = parseFloat(data['brgop'][a]['total_gelar']);
                    var panjang_kain = parseFloat(data['brgop'][a]['panjang_kain']);
                    var fbisbisan   = data['brgop'][a]['f_bisbisan'];

                    var e_product_wipname  = data['brgop'][a]['e_product_wipname'];
                    var e_material_name = data['brgop'][a]['e_material_name'];
                    var e_color_name    = data['brgop'][a]['e_color_name'];
                    var i_type          = data['brgop'][a]['i_type'];

                    var product     = i_product_wip+i_color;
                    var product2    = "'"+product+"'";

                    if(fbisbisan == 't'){
                        var bis = 'checked';
                    }else{
                        var bis = '';
                    }

                    var cols        = "";
                    var cols2       = "";
                    
                    var newRow      = $("<tr>");
                    
                    if(group==""){
                        cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+i_product_wip+' ('+e_product_wipname+') - '+e_color_name+'<input style="width:120px;" class="form-control" type="text" readonly id="nquantity'+a+'" name="nquantity'+a+'" value="'+n_quantity+'" onkeyup="hitungnilai3(this.value,'+product2+');"></b><input style="width:60px;" class="form-control" type="hidden" id="nquantitysisa'+a+'" name="nquantitysisa'+a+'" value="'+n_quantity_sisa+'"></td>';
                    }else{
                        if((group!=product)){
                            cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+i_product_wip+' ('+e_product_wipname+') - '+e_color_name+'<input style="width:120px;" class="form-control" type="text" readonly id="nquantity'+a+'" name="nquantity'+a+'" value="'+n_quantity+'" onkeyup="hitungnilai3(this.value,'+product2+');"></b><input style="width:60px;" class="form-control" type="hidden" id="nquantitysisa'+a+'" name="nquantitysisa'+a+'" value="'+n_quantity_sisa+'"></td>';
                        }
                    }
                
                    newRow.append(cols2);
                    $("#tabledata").append(newRow);

                    var newRow      = $("<tr>");

                    group=product;
                        /* HEADERNYA */
                        cols += '<td><input style="width:38px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input readonly style="width:60px;" type="hidden" id="fbisbisan'+a+'" name="fbisbisan'+a+'" value="'+fbisbisan+'"><input readonly style="width:60px;" type="hidden" id="id_product'+a+'" name="id_product'+a+'" value="'+id_product+'"><input readonly style="width:60px;" type="hidden" id="id_material'+a+'" name="id_material'+a+'" value="'+id_material+'"><input readonly style="width:60px;" type="hidden" id="i_product_color'+a+'" name="i_product_color'+a+'" value="'+product+'"></td>';

                        cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="i_material'+a+'" name="i_material'+a+'" value="'+i_material+'"></td>';
                        cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="e_material_name'+a+'" name="e_material_name'+a+'" value="'+e_material_name+'"></td>';
                        
                        /* ITEMNYA */ 
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="sisa'+a+'" name="sisa'+a+'" value ='+n_quantity_sisa+'></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control" type="text" id="v_gelar'+a+'" name="v_gelar'+a+'" value="'+v_gelar+'"></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control" type="text" id="v_set'+a+'" name="v_set'+a+'" value="'+v_set+'"></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control text-right" type="text" id="total_gelar'+a+'" name="total_gelar'+a+'" value="'+total_gelar.toFixed(2)+'"></td>';
                        cols += '<td><input readonly style="width:120px;" class="form-control text-right" type="text" id="panjang_kain'+a+'" name="panjang_kain'+a+'" value="'+panjang_kain.toFixed(2)+'" ></td>';
                        cols += '<td><input disabled type="checkbox" class="form-check-input" id="fbisbisan'+a+'" name="fbisbisan'+a+'" '+bis+'></td>';
                    //console.log(produk);
                                        
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }
                $('#jml').val(no);
            }
            },
            error: function () {
                alert('Error :)');
            }
        });
}



$("form").submit(function (event) {
    event.preventDefault();
    // $("input").attr("disabled", true);
    // $("select").attr("disabled", true);
    // $("#submit").attr("disabled", true);
    $("#send").attr("hidden", false);
});

function validasi() {
    if((document.getElementById("dspbb").value == '' || document.getElementById("dspbb").value == null)){
        alert('Tanggal Permintaan Tidak Boleh kosong!');
        return false;
        $("#submit").attr("disabled", false);
    }else if((document.getElementById("dspbb").value < document.getElementById("dschedule").value)){
        var dfrom   = splitdate($('#dspbb').val());
        var dto     = splitdate($('#dschedule').val());
        if (dfrom!=null && dto!=null) {
            if (dfrom<dto) {
                swal('Tanggal SPBB tidak boleh lebih kecil dari tanggal Schedule !');
                $('#dspbb').val(document.getElementById("dschedule").value);
                return false;
                $("#submit").attr("disabled", false);
            }
        }
        //alert('Tanggal SPBB tidak boleh lebih kecil dari tanggal Schedule !');
        //document.getElementById("dspbb").value = document.getElementById("dschedule").value;
    }else{
        for(var a = 0;a < $("#jml").val();a++){
            if ($("#nquantity"+a).length) {
                if(parseInt(document.getElementById("nquantity"+a).value) > parseInt(document.getElementById("nquantitysisa"+a).value)){
                    var x = "lebih";
                    $('#nquantity'+a).val($('#nquantitysisa'+a).val());
                    hitungnilai3($('#nquantitysisa'+a).val(),$('#i_product_color'+a).val())
                } else if (parseInt($("#nquantity"+a)) <= 0) {
                    var x = "nol";
                }

            } 
            
        }
        if(x == "lebih"){
            $("#submit").attr("disabled", false);
            swal('Maaf Jumlah Permintaan Melebihi Sisa !');
            return false;
        } else if (x == "nol") {
            $("#submit").attr("disabled", false);
            swal('Maaf Jumlah Permintaan Tidak Boleh 0 !');
            return false;
        }
    }
}

function getgudang(igudang) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/gudang');?>",
        data:"igudang="+igudang,
        dataType: 'json',
        success: function(data){
            $("#igudang").val(data.ikategori);
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

    function hitungnilai3(qty,kode){   
        for(var a=0;a<$('#jml').val();a++){
        i=a;  

        if(kode == $("#i_product_color"+i).val()){
            if (qty == "") {qty = 0;} 
            qty = formatulang(qty);
            //sisa    = formatulang(document.getElementById("sisa"+i).value);

            // if (qty > sisa) {
            //     swal("Tidak Boleh melebihi Sisa Schedule ( " + sisa +" )" + qty + i);
            //     $("#nquantity"+i).val(sisa);
            //     qty = sisa;
            // }

                v_gelar    = formatulang(document.getElementById("v_gelar"+i).value);
                v_set      = formatulang(document.getElementById("v_set"+i).value);
                bagibis  = formatulang(document.getElementById("total_gelar"+i).value);
                jmlgelar =parseFloat(qty)/parseFloat(v_set);


                if($("#fbisbisan"+i).val() == 'f'){
                    pjngkain=(parseFloat(qty)/parseFloat(v_set))*parseFloat(v_gelar);
                    $("#total_gelar"+i).val(jmlgelar.toFixed(2));
                }else{
                    pjngkain  = (parseFloat(qty)*parseFloat(v_gelar)*parseFloat(v_set))/parseFloat(bagibis);
                }
                
                $("#sisa"+i).val(qty);
                $("#panjang_kain"+i).val(pjngkain.toFixed(2));    
        }
      }
    } 

//function hitungbisbisan(isi,jml){       
function hitungbisbisan(qty,kode){       
      for(var a=0;a<$('#jml').val();a++){
        i=a;  
        
        if(kode == document.getElementById("iproduct"+i).value){
            //qty       = formatulang(document.getElementById("nquantity"+i).value);
            vgelar    = formatulang(document.getElementById("vgelar"+i).value);
            vset      = formatulang(document.getElementById("vset"+i).value);
            bagibis   = formatulang(document.getElementById("jumgelar"+i).value);
            if(qty=='')qty=0;
            //$('#pjgkain'+i).val((parseFloat(qty)*parseFloat(vgelar)*parseFloat(vset))/parseFloat(bagibis))
            //$('#pjgkain'+i).val((parseFloat(qty)/parseFloat(vset)*parseFloat(vgelar)))
            pjngkain  = (parseFloat(qty)*parseFloat(vgelar)*parseFloat(vset))/parseFloat(bagibis);
            document.getElementById("pjgkain"+i).value=(pjngkain).toFixed(2);
        }
      }
} 

function hitungbisbisan2(isi,jml){    
    i=jml;
    pjgkain=formatulang(document.getElementById("pjgkain"+i).value);
    ngelar=formatulang(document.getElementById("vgelar"+i).value);
    nset=formatulang(document.getElementById("vset"+i).value);
    bagibis=formatulang(document.getElementById("jumgelar"+i).value);
    if(pjgkain=='')pjgkain=0;
    hasil=((parseFloat(pjgkain)*parseFloat(bagibis))/parseFloat(nset))/parseFloat(ngelar);
    document.getElementById("nquantity"+i).value=(hasil);
 } 

 function hitungnilai(isi,jml){   
        i=jml;
        pjgkain=formatulang(document.getElementById("pjgkain"+i).value);
        ngelar=formatulang(document.getElementById("vgelar"+i).value);
        nset=formatulang(document.getElementById("vset"+i).value);
        if(pjgkain=='')pjgkain=0;
        gelar=((parseFloat(pjgkain)*100)/parseFloat(ngelar))/100;
        hasil=(((parseFloat(pjgkain)*100)/parseFloat(ngelar))*parseFloat(nset))/100;
        document.getElementById("jumgelar"+i).value=(gelar).toFixed(2);
        document.getElementById("nquantity"+i).value=(hasil);
  } 

  function hitungnilai2(isi,jml){   
        i=jml;
        jumgelar=formatulang(document.getElementById("jumgelar"+i).value);
        vgelar=formatulang(document.getElementById("vgelar"+i).value);
        vset=formatulang(document.getElementById("vset"+i).value);
        if(jumgelar=='')jumgelar=0;
        pjgkain=parseFloat(jumgelar)*parseFloat(vgelar);
        hasil=parseFloat(jumgelar)*parseFloat(vset);
        document.getElementById("pjgkain"+i).value=(pjgkain).toFixed(2);
        document.getElementById("nquantity"+i).value=(hasil);
  } 
    
//HITUNGNILAI3 OLD
//   function hitungnilai3(isi,jml){   
//       i=jml;     
//       qty=formatulang(document.getElementById("nquantity"+i).value);
//       vgelar=formatulang(document.getElementById("vgelar"+i).value);
//       vset=formatulang(document.getElementById("vset"+i).value);
//       if(qty=='')qty=0;
//       jmlgelar=parseFloat(qty)/parseFloat(vset);
//       pjngkain=(parseFloat(qty)/parseFloat(vset))*parseFloat(vgelar);
//       document.getElementById("jumgelar"+i).value=(jmlgelar).toFixed(2);
//       document.getElementById("pjgkain"+i).value=(pjngkain).toFixed(2);
//   } 

   //new script

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dspbb').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ispbb').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ispbb").attr("readonly", false);
        }else{
            $("#ispbb").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $( "#ispbb" ).keyup(function() {
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
</script>