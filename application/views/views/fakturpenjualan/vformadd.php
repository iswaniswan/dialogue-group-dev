<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4">Pembuat Dokumen</label>
                    <label class="col-md-4">No DO</label>
                    <label class="col-md-4">Tanggal Faktur</label>
                    <div class="col-sm-4">
                            <select name="dept" id="dept" class="form-control select2" onchange="getdo(this.value);">
                                <option value="">Pilih Bagian Pembuat</option>
                                <?php foreach ($area as $ikodemaster):?>
                                <option value="">
                                    <?= $ikodemaster->i_departement." - ".$ikodemaster->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <div class="col-sm-4">
                       <select name="ido" id="ido" multiple="multiple" class="form-control select2" disabled> 
                       <!-- <select name="ireff" id="ireff" multiple="multiple" class="form-control select2"> -->
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="dfaktur" id="dfaktur" class="form-control date" readonly>
                    </div>
                </div> 
                <div class="form-group row">
                     <label class="col-md-12">Keterangan</label>
                     <div class="col-sm-12">
                        <input type="text" name="eremark" id="eremark" class="form-control">
                    </div>
                </div> 
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>                    
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                 <div class="form-group row">
                    <label class="col-md-6">Customer</label>
                    <label class="col-md-6">Tanggal DO</label>
                    <div class="col-sm-6">                           
                        <input type="hidden" name="icustomer" id="icustomer" class="form-control" readonly>
                        <input type="text" name="ecustomer" id="ecustomer" class="form-control" readonly>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="ddo" id="ddo" class="form-control" readonly>
                    </div>
                </div>  
                 <div class="form-group row">
                    <label class="col-md-4">Nilai Kotor</label>
                    <label class="col-md-4">Total Discount</label>
                    <label class="col-md-4">Nilai Bersih</label>
                    <div class="col-sm-4">                           
                        <input type="text" name="vspb" id="vspb" class="form-control"  value = "0" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vspbdiscounttotal" id="vspbdiscounttotal" class="form-control" value = "0" readonly>  
                    </div>    
                    <div class="col-sm-4">
                        <input type="text" name="vspbbersih" id="vspbbersih" class="form-control" value = "0" readonly>
                    </div>     
                </div>
                <div class="form-group row">
                     <label class="col-md-4">Discount</label>
                     <label class="col-md-4">dpp</label>
                     <label class="col-md-4">ppn</label>
                    <div class="col-sm-4">
                        <input type="text" name="discount" id="discount" class="form-control" value = "0" onkeyup = "hitungnilai2(this.value)" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="dpp" id="dpp" class="form-control" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="ppn" id="ppn" class="form-control" readonly>
                    </div>
                </div> 
            
    </div>
    <input type="hidden" name="jml" id="jml" readonly>
    <!-- <input type="text" name="jmll" id="jmll" readonly> -->
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th>Nomor DO</th>
                                    <th>Kode Product</th>
                                    <th>Nama Product</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
        </form>
    </div>
</div>
<script>
// $("form").submit(function (event) {
//     event.preventDefault();
// });
    
// $(document).ready(function () {
//     $(".select2").select2();
//  });

//  $(document).ready(function () {
//     $('.select2').select2();
//     showCalendar('.date');
// });

$(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    
    $('.select2').select2();
        $('#iop').select2({
        placeholder: 'Pilih DO',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/bacado'); ?>',
          dataType: 'json',
          delay: 250,          
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      })
});

function getdo(id){
    $.ajax({
        placeholder: 'Cari No. DO',
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/bacado2');?>",
        data:{
            'isupplier' : id,
        }, 
        dataType: 'json',
        success: function (data) {
            $("#ido").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $("#ido").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
}

function getdpp(){
    var bersih = $('#vspbbersih').val();
    var dpp =0;
    var pnn =0;
    dpp =  bersih/1.1;
    ppn =  (dpp*10)/100;
    document.getElementById("dpp").value=formatcemua(dpp);
    document.getElementById("ppn").value=formatcemua(ppn);
}

function getdata() {
    // removeBody();
    
    var ido = $('#ido').val();
    // var ibtb = $('#ibtb').val();
    // var isjsupp = $('#isjsupp').val();
    // swal(ireff);
    $.ajax({
        type: "post",
        data: {
            'ido'       : ido,
            // 'ibtb'      : ibtb,
            // 'isjmanual' : isjsupp
        },
        url: '<?= base_url($folder.'/cform/getdo'); ?>',
        dataType: "json",
        success: function (data) {
            console.log(data);
            
            var dsj             = data['head']['d_do'];
            var customer        = data['head']['i_customer'];
            var customername    = data['head']['e_customer_name'];
            var gross        = data['head']['v_total_gross'];
            var discount        = data['head']['v_total_discount'];
            var netto        = data['head']['v_total_netto'];
            var discc        = data['head']['n_discount'];
            
            $('#ddo').val(dsj);
            $('#icustomer').val(customer);
            $('#ecustomer').val(customername);
            // $('#vspb').val(gross);
            // $('#vspbdiscounttotal').val(discount);
            // $('#vspbbersih').val(netto);
            $('#discount').val(discc);
             $('#jml').val(data['detail'].length);
            for (let a = 0; a < data['detail'].length; a++) {
                var zz = a+1;
                var produk      = data['detail'][a]['i_product'];
                var namaproduk  = data['detail'][a]['e_product_name'];
                // var esatuan     = data['detail'][a]['e_satuan'];
                // var isatuan     = data['detail'][a]['i_satuan'];
                var qty         = data['detail'][a]['n_deliver'];
                var vprice      = data['detail'][a]['v_price'];
                var vdogross      = data['detail'][a]['v_do_gross'];
                // var qtyic       = data['detail'][a]['n_quantity_stock'];
                // if(Number(qty)<Number(qtyic)){
                //     qtyic=qty;
                // }
                var x = $('#jml').val();

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                cols += '<td><input style="width:200px;" class="form-control" readonly type="text" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                cols += '<td><input style="width:200px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namaproduk+'"></td>';
                // cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="esatuan'+zz+'" name="esatuan'+zz+'" value="'+esatuan+'"><input readonly style="width:70px;"  type="hidden" id="isatuan'+zz+'" name="isatuan'+zz+'" value="'+isatuan+'"></td>';
                cols += '<td><input readonly class="form-control" style="width:100px;"  readonly type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="'+qty+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" type="text" readonly id="vprice'+zz+'" name="vprice'+zz+'" value="'+vprice+'" onkeyup="hitungnilai(this.value,'+zz+')"></td>';
                cols += '<td><input readonly class="form-control" style="width:100px;"  readonly type="text" id="total'+zz+'" name="total'+zz+'" value="'+vdogross+'"></td>';
                // cols += '<td><input style="width:100px;" class="form-control" style="width:250px;"  type="text" id="eremarkh'+zz+'" name="eremarkh'+zz+'" value=""></td>';
                // cols += '<td><input type="checkbox" name="cek'+zz+'"  id="chk'+zz+'" ></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);
                
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
            getdpp();
            // hitungnilai2(0);
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
    $('#vprice').focus();
    
}

$("#ido").on('change',function() {
    var jml = 0;
    var ido = $('#ido').val();
    $("#tabledata tr:gt(0)").remove(); 
    $("#jml").val(0);
    $.ajax({
        type: "post",
        data: {
            'ido'       : ido,
        },
        url: '<?= base_url($folder.'/cform/getdo'); ?>',
        dataType: "json",
            success: function (data) {
                console.log(data);
                $('#jml').val(data['detail'].length);
                var dsj             = data['head']['d_do'];
                var customer        = data['head']['i_customer'];
                var customername    = data['head']['e_customer_name'];
                var gross        = data['head']['v_total_gross'];
                var discount        = data['head']['v_total_discount'];
                var netto        = data['head']['v_total_netto'];
                var discc        = data['head']['n_discount'];
                
                $('#ddo').val(dsj);
                $('#icustomer').val(customer);
                $('#ecustomer').val(customername);
                $('#discount').val(discc);

                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var idoo      = data['detail'][a]['i_do'];
                    var produk      = data['detail'][a]['i_product'];
                    var namaproduk  = data['detail'][a]['e_product_name'];
                    var qty         = data['detail'][a]['n_deliver'];
                    var vprice      = data['detail'][a]['v_price'];
                    var vdogross      = data['detail'][a]['v_do_gross'];
                    
                    var x = $('#jml').val();
                    var cols        = "";
                    var newRow = $("<tr>");
                        cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                        cols += '<td><input style="width:200px;" class="form-control" readonly type="text" id="idoo'+zz+'" name="idoo'+zz+'" value="'+idoo+'"></td>';
                        cols += '<td><input style="width:200px;" class="form-control" readonly type="text" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                        cols += '<td><input style="width:400px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namaproduk+'"></td>';
                        // cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="esatuan'+zz+'" name="esatuan'+zz+'" value="'+esatuan+'"><input readonly style="width:70px;"  type="hidden" id="isatuan'+zz+'" name="isatuan'+zz+'" value="'+isatuan+'"></td>';
                        cols += '<td><input readonly class="form-control" style="width:100px;"  readonly type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="'+qty+'"></td>';
                        cols += '<td><input style="width:150px;" class="form-control" type="text" readonly id="vprice'+zz+'" name="vprice'+zz+'" value="'+vprice+'" onkeyup="hitungnilai(this.value,'+zz+')"></td>';
                        cols += '<td><input readonly class="form-control" style="width:200px;"  readonly type="text" id="total'+zz+'" name="total'+zz+'" value="'+vdogross+'"></td>';
                        // cols += '<td><input style="width:100px;" class="form-control" style="width:250px;"  type="text" id="eremarkh'+zz+'" name="eremarkh'+zz+'" value=""></td>';
                        // cols += '<td><input type="checkbox" name="cek'+zz+'"  id="chk'+zz+'" ></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    var i_material = $('#i_material'+zz).val();
                    var ireferensi = $('#ireferensi'+zz).val();
                    var kelompokbrg= $('#kelompokbrg').val();
                    $('#i_2material'+zz).select2({
                        placeholder: 'Pilih Material',
                        allowClear: true,
                        ajax: {
                            url: '<?= base_url($folder);?>/cform/datamaterial/'+i_material+'/'+ireferensi+'/'+kelompokbrg,
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
                //getdpp();
                hitungnilai2(0);
            },
            error: function () {
                swal('Data Kosong :)');
            }
        });
        xx = $('#jml').val();
    });

function hitungnilai(isi,jml){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            dtmp1=parseFloat(formatulang(document.getElementById("discount").value));
            vdis1   =0;
            vtot    =0;
            
            for(i=1;i<=jml;i++){
                vhrg=formatulang(document.getElementById("vprice"+i).value);
                if (isNaN(parseFloat(document.getElementById("nquantity"+i).value))){
                    nqty=0;
                }else{
                    nqty=formatulang(document.getElementById("nquantity"+i).value);
                    vhrg=parseFloat(vhrg)*parseFloat(nqty);
                    vtot=vtot+vhrg;
                    document.getElementById("total"+i).value=formatcemua(vhrg);
                    // alert(vtot);
                }    
            }
            vdis1=vdis1+((vtot*dtmp1)/100);
            // alert("asasa");
            // vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            // vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            vdis1=parseFloat(vdis1);
            // vdis2=parseFloat(vdis2);
            // vdis3=parseFloat(vdis3);
            vtotdis=vdis1
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        }
    }

    function hitungnilai2(isi){
        
        jml=document.getElementById("jml").value;

            dtmp1=parseFloat(formatulang(document.getElementById("discount").value));
            vdis1   =0;
            vtot    =0;
            dpp     =0;
            ppn     =0;
            alert(jml);
            for(i=1;i<=jml;i++){
                
                vhrg=formatulang(document.getElementById("vprice"+i).value); 
                nqty=formatulang(document.getElementById("nquantity"+i).value);
                vhrg=parseFloat(vhrg)*parseFloat(nqty);
                vtot=vtot+vhrg;    
                document.getElementById("total"+i).value=formatcemua(vhrg);
            }
            vdis1=vdis1+((vtot*dtmp1)/100);
            vdis1=parseFloat(vdis1);
            vtotdis=vdis1
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            dpp =  vtotbersih/1.1;
            ppn =  (dpp*10)/100;
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
            document.getElementById("dpp").value=formatcemua(dpp);
            document.getElementById("ppn").value=formatcemua(ppn);
        // }
    }

// function get(isupplier) {
//         var isupplier = $('#isupplier').val();
//         $.ajax({
//             type: "post",
//             data: {
//                 'isupplier': isupplier
//             },
//             url: '<?= base_url($folder.'/cform/getipayment'); ?>',
//             dataType: "json",
//             success: function (data) {
//                 $('#ipaymenttype').val(data[0].i_jenis_pembelian);
//                 $('#epaymenttype').val(data[0].epayment);
//             },
//             error: function () {
//                 alert('Error :)');
//             }
//         });
// }

// function getiop(isupplier) {
// var isupplier = $('#isupplier').val();
//     $.ajax({
//         type: "POST",
//         url: "<?php echo site_url($folder.'/Cform/getiop');?>",
//         data:"isupplier="+isupplier,
//         dataType: 'json',
//         success: function(data){
//             $("#isj").html(data.kop);

//             // getibtb('ISJ');
//             if (data.kosong=='kopong') {
//                 $("#submit").attr("disabled", true);
//             }else{
//                 $("#submit").attr("disabled", false);
//             }
//         },

//         error:function(XMLHttpRequest){
//             alert(XMLHttpRequest.responseText);
//         }

//     })
// }

// function getibtb(iop) {
// var isupplier = $('#isupplier').val();
// var isj = $('#isj').val();

//     $.ajax({
//         type: "POST",
//         url: "<?php echo site_url($folder.'/Cform/getibtb');?>",
//         data:{
//             'isupplier' : isupplier,
//             'isj'       :isj,
//         },
//         dataType: 'json',
//         success: function(data){
//             $("#ibtb").html(data.kop);

//             getidoksup('IBTB');
//             if (data.kosong=='kopong') {
//                 $("#submit").attr("disabled", true);
//             }else{
//                 $("#submit").attr("disabled", false);
//             }
//         },

//         error:function(XMLHttpRequest){
//             alert(XMLHttpRequest.responseText);
//         }

//     })
// }

// function getidoksup(ibtb) {
// var isupplier = $('#isupplier').val();
// var isj = $('#isj').val();
// var ibtb = $('#ibtb').val();
//     $.ajax({
//         type: "POST",
//         url: "<?php echo site_url($folder.'/Cform/getidoksup');?>",
//         data:{
//             'isupplier' : isupplier,
//             'isj'       :isj,
//             'ibtb'      :ibtb,
//         },
//         dataType: 'json',
//         success: function(data){
//             $("#isjsupp").html(data.kop);

//             if (data.kosong=='kopong') {
//                 $("#submit").attr("disabled", true);
//             }else{
//                 $("#submit").attr("disabled", false);
//             }
//         },

//         error:function(XMLHttpRequest){
//             alert(XMLHttpRequest.responseText);
//         }

//     })
// }

function validasi(){
var s=0;
    var textinputs = document.querySelectorAll('input[type=select2]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });

    if (document.getElementById('isupplier').value=='') {
        swal("Pilih Supplier!");
        return false;
    }if (document.getElementById('ipaymenttype').value=='') {
        swal("Pilih Jenis Pembelian!");
        return false;
    }else {
        return true
    }
}    
</script>