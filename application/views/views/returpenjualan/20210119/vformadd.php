<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-8">Bagian Pembuat</label>
                        <label class="col-md-4">Tanggal Retur</label>
                        <div class="col-sm-8">
                            <select name="ikodebagian" id="ikodebagian" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                                <input type="text" id= "dttb" name="dttb" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">No Pajak</label>
                        <label class="col-md-6">Alasan Retur</label>
                        <div class="col-sm-6">
                            <input type="text" id= "ipajak" name="ipajak" class="form-control" value="" readonly>
                        </div>
                        <div class="col-sm-6">
                            <select name="ialasanretur" id="ialasanretur" class="form-control select2">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "noreturpelanggan "name="noreturpelanggan" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" hidden id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">No Nota Penjualan</label>
                        <div class="col-sm-6">          
                            <select name="epelanggan" id="epelanggan" class="form-control select2" onchange="getnotapenjualan(this.value);"> 
                            </select>
                            <input type="hidden" name="ipelanggan" id="ipelanggan" class="form-control" value="">
                        </div>
                        <div class="col-sm-6">                            
                            <select name="i_nota" id="i_nota" class="form-control select2" onchange="getdataitem(this.value);" disabled="true"> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Discount Pelanggan</label>
                        <label class="col-md-4">DPP</label>
                        <label class="col-md-4">PPN</label>
                        <div class="col-sm-4">          
                            <input type="text" name="discount" id="discount" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="dpp" id="dpp" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="ppn" id="ppn" class="form-control" value="0" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Nilai Kotor</label>
                        <label class="col-md-4">Total Discount</label>
                        <label class="col-md-4">Nilai Bersih</label>
                        <div class="col-sm-4">          
                            <input type="text" name="vspb" id="vspb" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="vspbdiscounttotal" id="vspbdiscounttotal" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="vspbbersih" id="vspbbersih" class="form-control" value="0" readonly>
                        </div>
                    </div>
                </div>
                 <input style ="width:50px" type="hidden" name="jml" id="jml" value="">
                    <div class="panel-body table-responsive">
                        <!-- <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true"> -->
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode Barang</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="10%">Harga</th>
                                    <th width="8%">Quantity Nota</th>
                                    <!-- <th width="8%">Quantity Sisa</th> -->
                                    <th width="9%">Quantity Retur</th>
                                    <th width="15%">Total</th>
                                    <th width="20%">Keterangan</th>
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

// $("form").submit(function (event) {
//     event.preventDefault();
//     $("input").attr("disabled", true);
//     $("select").attr("disabled", true);
//     $("#addrow").attr("disabled", true);
//     $("#submit").attr("disabled", true);
// });

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});


$(document).ready(function () {
    $('#ikodebagian').select2({
    placeholder: 'Pilih Bagian',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/bagian'); ?>',
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

    $('#ialasanretur').select2({
    placeholder: 'Pilih Alasan Retur',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/alasanretur'); ?>',
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

$(document).ready(function () {
    $('#itujuan').select2({
    placeholder: 'Pilih Tujuan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/tujuan'); ?>',
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

$(document).ready(function () {
    $('#epelanggan').select2({
    placeholder: 'Pilih Pelanggan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/getpelanggan'); ?>',
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

function getnotapenjualan(ipelanggan) {
    $("#i_nota").attr("disabled", false);
    var epelanggan = $('#epelanggan').val();
    var i_nota = $('#i_nota').val();
        //alert(epelanggan);
        if (epelanggan == "") {
            $("#i_nota").attr("disabled", true);

        } else {
            $('#ipelanggan').val(epelanggan);
            $("#epelanggan").attr("disabled", true);
            $("#i_nota").attr("disabled", false);
        }
        
        $('#i_nota').html('');
        $('#i_nota').val('');
           
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getnotapenjualan');?>",
        data:{
            'ipelanggan': ipelanggan,
        },
        dataType: 'json',
        success: function(data){
            $("#i_nota").html(data.kop);
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

function getdataitem(i_nota) {
    $("#epelanggan").attr("disabled", true);
    $('#addrow').attr("hidden", false);
    var i_nota = $('#i_nota').val();
    var ipelanggan = $('#ipelanggan').val();

    $.ajax({
        type: "post",
        data: {
            'inota': i_nota,
        },
        url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
        dataType: "json",
        success: function (data) { 
            // console.log(data); 

            // return false;
            var ipajak  = data['dataheader']['i_faktur_pajak'];
            var disc    = data['dataheader']['n_discount'];
            $('#ipajak').val(ipajak);
            $('#discount').val(disc);
            $('#jml').val(data['jmlitem']);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['jmlitem']; a++) {
                var no = a+1;
                var iproduct            = data['dataitem'][a]['i_product'];
                var eproduct            = data['dataitem'][a]['e_product_name'];
                var nquantityfaktur     = data['dataitem'][a]['n_quantity_faktur'];
                var nquantitytlahretur  = data['dataitem'][a]['n_quantity_tlah_retur'];
                var nquantitysisa       = data['dataitem'][a]['n_quantity_sisa'];
                var harga               = data['dataitem'][a]['harga'];
                /*var eproduct     = data['dataitem'][a]['e_namabrg'];
                var imaterial    = data['dataitem'][a]['i_material'];
                var ematerial    = data['dataitem'][a]['e_material_name'];
                var icolor       = data['dataitem'][a]['i_color'];
                var ecolor       = data['dataitem'][a]['e_color_name'];
                var nquantitypro = data['dataitem'][a]['n_quantity'];
                var nquantityma  = data['dataitem'][a]['qtyma'];
                var edesc        = data['dataitem'][a]['e_remark'];*/

                
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';     
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="iproduct'+a+'" name="iproduct[]" value="'+iproduct+'"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproduct'+a+'" name="eproduct[]" value="'+eproduct+'"></td>'; 
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="vprice'+a+'" name="vprice[]" value="'+harga+'"></td>'; 
                cols += '<td><input readonly style="width:80px;" class="form-control"  type="text" id="nquantityfaktur'+a+'" name="nquantityfaktur[]" value="'+nquantityfaktur+'"><input  readonly style="width:80px;" class="form-control"  type="hidden" id="nquantitysisa'+a+'" name="nquantitysisa[]" value="'+nquantitysisa+'" ></td>';
                cols += '<td><input style="width:80px;" class="form-control"  type="text" id="nquantity'+a+'" name="nquantity[]" value=""  onkeyup="hitungnilai(this.value,'+a+')";></td>';
                cols += '<td><input  readonly style="width:80px;" class="form-control"  type="text" id="total'+a+'" name="total[]" value="0" ></td>';
                cols += '<td><input style="width:300px;" class="form-control" type="text" id="edesc'+a+'" name="edesc[]" value=""></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }

            var a = $('#jml').val();
           /* $("#addrow").on("click", function () {
                a++;
                var i_nota = $('#i_nota').val();
                $('#jml').val(a);
                count=$('#tabledata tr').length;
                $("#tabledata").attr("hidden", false);
                var newRow = $("<tr>");
                
                var cols = "";
                cols += '<td style="text-align: center;"><spanx id="snum'+a+'">'+count+'</spanx><input type="hidden" id="baris'+a+'" type="text" class="form-control" name="baris'+a+'" value="'+a+'"></td>';
                cols += '<td><input style="width:100px;" type="hidden" id="iproduct'+ a + '" class="form-control" name="iproduct[] value=""></td>';
                cols += '<td><input style="width:100px;" type="hidden" id="icolorpro'+ a + '" type="text" class="form-control" name="icolorpro[]"></td>';
                cols += '<td><input style="width:100px;" type="hidden" id="nquantitypro'+ a + '" type="text" class="form-control" name="nquantitypro[]" value="0"></td>';
                cols += '<td><input style="width:100px;" type="text" readonly  id="imaterial'+ a + '" type="text" class="form-control" name="imaterial[]"></td>';
                cols += '<td><select type="text" id="ematerial'+ a + '" class="form-control" name="ematerial'+ a + '"" onchange="getmaterial('+ a + ');"></td></td>';
                cols += '<td><input type="hidden" id="icolorma'+ a + '" class="form-control" name="icolorma[]"/><input type="text" style="width:150px;" id="ecolor'+ a + '" readonly class="form-control" name="ecolor' + a + '"/></td>';
                cols += '<td><input style="width:80px;" type="text" readonly  id="nquantityma'+ a + '" type="text" class="form-control" name="nquantityma[]" value="0"></td>';
                cols += '<td><input style="width:80px;" type="text"  id="nquantitymasuk'+ a + '" type="text" class="form-control" name="nquantitymasuk[]"></td>';
                cols += '<td><input style="width:100px;" type="text" id="edesc'+ a + '" type="text" class="form-control" name="edesc[]"><input style="width:40px;"  type="hidden" id="isatuan'+a+'" name="isatuan[]" value=""></td>';
                cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

                newRow.append(cols);
                $("#tabledata").append(newRow);

                $('#ematerial'+ a).select2({
                    placeholder: 'Pilih Material',
                    templateSelection: formatSelection,
                    allowClear: true,
                    ajax: {
                      url: '<?= base_url($folder.'/cform/datamaterial/'); ?>',
                      
                      dataType: 'json',
                      delay: 250,
                      processResults: function (data) {
                        return {
                          results: data
                        };
                      },
                      cache: true
                    }
                });
            });*/

            function formatSelection(val) {
                return val.name;
            }

            $("#tabledata").on("click", ".ibtnDel", function (event) {
                $(this).closest("tr").remove();       
            });
        },
        error: function () {
            alert('Error :)');
        }
    });

} 

function getmaterial(id){
    var ematerial = $('#ematerial'+id).val();
    $.ajax({
    type: "post",
    data: {
        'ematerial': ematerial
    },
    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
    dataType: "json",
    success: function (data) {
        $('#imaterial'+id).val(data[0].i_material);
        $('#icolorma'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);
        $('#iproduct'+id).val(data[0].i_material);
        $('#icolorpro'+id).val(data[0].i_color);

        ada=false;
        var a = $('#imaterial'+id).val();
        var e = $('#ematerial'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#imaterial'+i).val()) && (i!=jml)){
                swal ("kode : "+a+" sudah ada !!!!!");
                ada=true;
                break;
            }else{
                ada=false;     
            }
        }
        if(!ada){
            var ematerial    = $('#ematerial'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'ematerial'  : ematerial,
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#imaterial'+id).val(data[0].i_material);
                    $('#icolorma'+id).val(data[0].i_color);
                    $('#ecolor'+id).val(data[0].e_color_name);
                    $('#iproduct'+id).val(data[0].i_material);
                    $('#icolorpro'+id).val(data[0].i_color);
                },
            });
        }else{
            $('#imaterial'+id).html('');
            //$('#iproduct'+id).val('');
           // $('#eproduct'+id).html('');
            $('#ematerial'+id).val('');
            $('#icolorma'+id).val('');
            $('#ecolor'+id).val('');
            $('#iproduct'+id).val('');
            $('#icolorpro'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}

function hitungnilai(isi,jml){
        jml=document.getElementById("jml").value;
        // jml=document.getElementById("jml").value;
    for(i=0;i<jml;i++){
        qtysj   =document.getElementById("nquantitysisa"+i).value;
        qtyretur=document.getElementById("nquantity"+i).value;
        if(parseFloat(qtyretur)>parseFloat(qtysj)){
            swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah SJ');
            document.getElementById("nquantity"+i).value=0;
            break;
      }
    }
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            dtmp1=parseFloat(formatulang(document.getElementById("discount").value));
            vdis1   =0;
            vtot    =0;
            dpp     =0;
            ppn     =0;
            for(i=0;i<jml;i++){
                
                vhrg=formatulang(document.getElementById("vprice"+i).value);
                // swal("dtmp1");
                if (isNaN(parseFloat(document.getElementById("nquantity"+i).value))){
                    nqty=0;
                    // swal("dtmp1");
                }else{
                    // swal(vhrg);
                    nqty=formatulang(document.getElementById("nquantity"+i).value);
                    vhrg=parseFloat(vhrg)*parseFloat(nqty);
                    vtot=vtot+vhrg;
                    document.getElementById("total"+i).value=formatcemua(vhrg);
                    // alert(vdis1);
                }    
            }
            // alert(vdis1);
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
            dpp=vtotbersih/1.1;
            ppn=dpp*(10/100);
            // alert(ppn);
            document.getElementById("dpp").value=formatcemua(dpp);
            document.getElementById("ppn").value=formatcemua(ppn);

        }
    }

 function getmaterialaks(id){
    var ematerial = $('#ematerial'+id).val();
    //alert(eproduct);
    $.ajax({
    type: "post",
    data: {
        'ematerial': ematerial
    },
    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
    dataType: "json",
    success: function (data) {
        $('#imaterial'+id).val(data[0].i_material);
        $('#isatuan'+id).val(data[0].i_satuan_code);
        $('#esatuan'+id).val(data[0].e_satuan);
        $('#icolorma'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);
        $('#iproduct'+id).val(data[0].i_material);
        $('#icolorpro'+id).val(data[0].i_color);

        ada=false;
        var a = $('#imaterial'+id).val();
        var e = $('#ematerial'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#imaterial'+i).val()) && (i!=jml)){
                swal ("kode : "+a+" sudah ada !!!!!");
                ada=true;
                break;
            }else{
                ada=false;     
            }
        }
        if(!ada){
            var ematerial    = $('#ematerial'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'ematerial'  : ematerial,
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#imaterial'+id).val(data[0].i_material);
                    $('#icolorma'+id).val(data[0].i_color);
                    $('#ecolor'+id).val(data[0].e_color_name);
                    $('#iproduct'+id).val(data[0].i_material);
                    $('#icolorpro'+id).val(data[0].i_color);
                },
            });
        }else{
            $('#imaterial'+id).html('');
            //$('#iproduct'+id).val('');
           // $('#eproduct'+id).html('');
            $('#ematerial'+id).val('');
            $('#icolorma'+id).val('');
            $('#ecolor'+id).val('');
            $('#iproduct'+id).val('');
            $('#icolorpro'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}

function validasi(id){
    if (parseInt($('#nquantitysisa'+id).val()) < $('#nquantityretur'+id).val()) {
        swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah Sisa');
    }
}
</script>