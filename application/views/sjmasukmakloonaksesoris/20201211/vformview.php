<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label>
                        <label class="col-md-6">Nomor SJ Masuk</label>
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_kode_master == $head->i_kode_master) { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>" selected><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>"><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="isjmm" name="isjmm" class="form-control" value="<?php echo $head->i_sj;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Referensi SJ Keluar</label>
                        <div class="col-sm-6">
                            <select required="" id="isjkm" name="isjkm" class="form-control" onchange="getdetailsjkm();" disabled="">
                                <option value="<?php echo $head->i_sj_reff;?>" selected><?= $head->i_sj_reff." || ".$head->d_sjkm;?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" id="itujuan">
                        <label class="col-md-4">Tanggal SJ Masuk</label>
                        <label class="col-md-8">Supplier</label>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo $head->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="esupplier" name="esupplier" class="form-control" value="<?php echo $head->e_supplier_name;?>" readonly>
                            <input type="hidden" id="supplier" name="supplier" class="form-control" value="<?php echo $head->i_supplier;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark;?>" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="40%">Kode Barang ( Keluar )</th>
                                    <th width="6%">Qty</th>
                                    <th width="9%">Satuan</th>
                                    <th width="30%">Kode Barang ( Masuk )</th>
                                    <th width="6%">Qty</th>
                                    <th width="9%">Satuan</th>
                                    <th width="10%">Keterangan</th>.
                                    <th width="3%">Action</th>
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

<script type="text/javascript">
getdetailsjmm();

function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        for(j=1;j<=jml;j++){
           if(document.getElementById("nquantity"+j).value=='')
             //document.getElementById("nquantity"+j).value='0';
             var jml    = counter;
             var totdis    = 0;
             var totnil = 0;
             var hrg    = 0;
             var ndis1  = parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
             var ndis2  = parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
             var ndis3  = parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
             
             var vdis1  = 0;
             var vdis2  = 0;
             var vdis3  = 0;
             for(i=1;i<=jml;i++){
            document.getElementById("ndeliver"+i).value=document.getElementById("nquantity"+i).value;
                vprod=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
                nquan=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
               var hrgtmp  = vprod*nquan;
                hrg        = hrg+hrgtmp;
             }
             
             vdis1=vdis1+((hrg*ndis1)/100);
             vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
             vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
             vdistot = vdis1+vdis2+vdis3;
             vhrgreal= hrg-vdistot;
             
             document.getElementById("vttbdiscount1").value=formatcemua(vdis1);
             
             document.getElementById("vttbdiscount2").value=formatcemua(vdis2);
             
             document.getElementById("vttbdiscount3").value=formatcemua(vdis3);
             document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
             document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
             document.getElementById("vttbgross").value=formatcemua(hrg);
          }
    }else{
        alert('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
  }
  function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }
    var counter = 0;
    
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td><input readonly style=width:30px; id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select type="text" id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '" value="" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="ematerialname'+ counter + '" type="text" class="form-control" name="ematerialname' + counter + '" value=""></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" placeholder="0" name="nquantity'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        // cols += '<td><input type="text" id="esatuankonv'+ counter + '" class="form-control" name="esatuankonv'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        // cols += '<td><input type="text" id="nquantitykonv'+ counter + '" class="form-control" name="nquantitykonv'+ counter + '" value="" onkeyup="cekqty('+counter+');"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '" value=""/></td>';
        cols += '<td><input type="checkbox" checked id="bisbisan'+ counter + '" name="bisbisan' + counter + '" onclick="return false;"></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        cols += '<td><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="hidden" id="fkonv'+ counter + '" class="form-control" name="fkonv'+ counter + '" value = "0";></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ikodemaster').attr("disabled", true);
        var gudang = $('#istore').val();

        $('#imaterial'+counter).select2({
        placeholder: 'Pilih Material',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
      
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        // counter -= 1
        // document.getElementById("jml").value = counter;
        del();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    // SJ Masuk Makloon

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');

        $('#isjkm').select2({
            placeholder: 'Cari No. SJ Keluar Makloon',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsjkm/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var gudang           = $('#istore').val();
                    var query = {
                        q: params.term,
                        gudang: gudang
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

    function getstore() {
        var gudang = $('#ikodemaster').val();
        //alert(gudang);

        if (gudang == "") {
            //$("#addrow").attr("hidden", true);
            $("#isjkm").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
            $("#isjkm").attr("disabled", false);
            //$("#addrow").attr("hidden", false);
        }
        
        $('#isjkm').html('');
        $('#isjkm').val('');
    }

    function getdetailsjkm() {
        // var isjkm = $('#isjkm option:selected').text();
        // var dspmb = isjkm.substr(-10);
        var sjkm = $('#isjkm').val();
        var gudang = $('#istore').val();
        if (sjkm!='') {
            //$('#dspmb').val(dspmb);
            //$("#addrow").attr("hidden", false);
            // $("#tabledata").attr("hidden", false);
            // $("#cek").attr("hidden", false);
        }else{
            //$("#addrow").attr("hidden", false);
            // $("#addrow").attr("hidden", true);
            // $("#tabledata").attr("hidden", true);
            // $("#cek").attr("hidden", true);
        }
        $.ajax({
            type: "post",
            data: {
                'isjkm': sjkm,
                'gudang': gudang
            },
            url: '<?= base_url($folder.'/cform/getdetailsjkm'); ?>',
            dataType: "json",
            success: function (data) {
                var isupplier = data['head']['i_supplier'];
                var esupplier = data['head']['e_supplier_name'];
                var ijeniskeluar = data['head']['i_jenis_keluar'];
                var ejeniskeluar = data['head']['e_jenis_keluar'];
                $('#supplier').val(isupplier);
                $('#esupplier').val(esupplier);
                //$('#jnskeluar').val(ijeniskeluar);
                //$('#ejnskeluar').val(ejeniskeluar);
                $('#jml').val(data['detail'].length);
                var gudang = $('#istore').val();
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var i_material    = data['detail'][a]['i_material'];
                    var e_material    = data['detail'][a]['e_material_name'];
                    var n_qty       = data['detail'][a]['n_qty'];
                    var i_satuan       = data['detail'][a]['i_satuan'];
                    var e_satuan       = data['detail'][a]['e_satuan'];
                    var namabarang  = i_material + ' - ' + e_material;

                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" readonly id="i_material'+zz+'" name="i_material'+zz+'" value="'+i_material+'"></td>';
                    cols += '<td><input class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+namabarang+'" value="'+namabarang+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="n_qty'+zz+'" name="n_qty'+zz+'" value="'+n_qty+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:right;" id="i_satuan'+zz+'" name="i_satuan'+zz+'" value="'+i_satuan+'"><input readonly class="form-control" style="text-align:right;" id="e_satuan'+zz+'" name="e_satuan'+zz+'" value="'+e_satuan+'"></td>';

                    cols += '<td><select type="text" style=width:320px id="i_2material'+zz+'" class="form-control" name="i_2material'+ zz + '" value="" onchange="getmaterial('+ zz + ');"></td>';
                    cols += '<td><input type="text" id="n_2qty'+ zz + '" class="form-control" placeholder="0" name="n_2qty'+ zz + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:right;" id="i_2satuan'+zz+'" name="i_2satuan'+zz+'" value=""><input readonly class="form-control" style="text-align:right;" id="e_2satuan'+zz+'" name="e_2satuan'+zz+'" value=""></td>';
                    cols += '<td><input type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';
                     // cols += '<td><input onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiall();" class="form-control" style="text-align:right;" id="ndeliver'+zz+'" name="ndeliver'+zz+'" value="'+stock+'"><input type="hidden" id="nstock'+zz+'" name="nstock'+zz+'" value="'+stock+'"><input type="hidden" id="vtotal'+zz+'" name="vtotal'+zz+'" value="0"></td>';
                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" onclick="setaction('+zz+');"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    $('#i_2material'+zz).select2({
                        placeholder: 'Pilih Material',
                        allowClear: true,
                        ajax: {
                            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
        xx = $('#jml').val();
    }
    
    function getmaterial(id){
        var imaterial = $('#i_2material'+id).val();
        $.ajax({
            type: "post",
            data: {
                'i_material': imaterial
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#e_2satuan'+id).val(data[0].e_satuan);
                $('#i_2satuan'+id).val(data[0].i_satuan);
                $('#chk'+id).prop("checked", true);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function setaction(id) {
        if($('#chk'+id).prop('checked')) {

        } else {
            // $('#i_2satuan'+id).val("");
            // $('#e_2satuan'+id).val("");
            // $('#i_2material'+id).val("").change();
            // $('#n_2qty'+id).val("");
        }
    }

    function cek() {
        var dsjk = $('#dsjk').val();
        var isjkm = $('#isjkm').val();
        var istore = $('#istore').val();

        if (dsjk == '' || isjkm == null || istore == '') {
            alert('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }

    function getdetailsjmm() {
        // var isjkm = $('#isjkm option:selected').text();
        // var dspmb = isjkm.substr(-10);
        var sjkm = $('#isjkm').val();
        var sjmm = $('#isjmm').val();
        var gudang = $('#istore').val();
        if (sjmm!='') {
            //$('#dspmb').val(dspmb);
            //$("#addrow").attr("hidden", false);
            // $("#tabledata").attr("hidden", false);
            // $("#cek").attr("hidden", false);
        }else{
            //$("#addrow").attr("hidden", false);
            // $("#addrow").attr("hidden", true);
            // $("#tabledata").attr("hidden", true);
            // $("#cek").attr("hidden", true);
        }
        //alert(sjkm+sjmm+gudang);
        $.ajax({
            type: "post",
            data: {
                'isjkm': sjkm,
                'isjmm': sjmm,
                'gudang': gudang
            },
            url: '<?= base_url($folder.'/cform/getdetailsjmm'); ?>',
            dataType: "json",
            success: function (data) {
                // var isupplier = data['head']['i_supplier'];
                // var esupplier = data['head']['e_supplier_name'];
                // var ijeniskeluar = data['head']['i_jenis_keluar'];
                // var ejeniskeluar = data['head']['e_jenis_keluar'];
                // $('#supplier').val(isupplier);
                // $('#esupplier').val(esupplier);
                // $('#jnskeluar').val(ijeniskeluar);
                // $('#ejnskeluar').val(ejeniskeluar);
                $('#jml').val(data['detail'].length);
                var gudang = $('#istore').val();
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var i_material    = data['detail'][a]['i_material'];
                    var e_material    = data['detail'][a]['e_material_name'];
                    var n_qty         = data['detail'][a]['n_qty'];
                    var i_satuan      = data['detail'][a]['i_satuan'];
                    var e_satuan      = data['detail'][a]['e_satuan'];
                    var namabarang    = i_material + ' - ' + e_material;

                    var i_2material    = data['detail'][a]['i_2material'];
                    var e_2material    = data['detail'][a]['e_2material_name'];
                    var n_2qty         = data['detail'][a]['n_2qty'];
                    var i_2satuan      = data['detail'][a]['i_2satuan'];
                    var e_2satuan      = data['detail'][a]['e_2satuan'];
                    var e_remark       = data['detail'][a]['e_remark'];
                    var namabarang2;

                    if (i_2material == null) {
                        i_2material = ''
                    }
                    if (e_2material == null) {
                        e_2material = ''
                    }
                    if (n_2qty == null) {
                        n_2qty = ''
                    }
                    if (i_2satuan == null) {
                        i_2satuan = ''
                    }
                    if (e_2satuan == null) {
                        e_2satuan = ''
                    }
                    if (e_remark == null) {
                        e_remark = ''
                    }

                    if (i_2material == '' && e_2material == '') {
                        namabarang2    = '';
                    }else {
                        namabarang2    = i_2material + ' - ' + e_2material;
                    }

                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" readonly id="i_material'+zz+'" name="i_material'+zz+'" value="'+i_material+'"></td>';
                    cols += '<td><input class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+namabarang+'" value="'+namabarang+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="n_qty'+zz+'" name="n_qty'+zz+'" value="'+n_qty+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:right;" id="i_satuan'+zz+'" name="i_satuan'+zz+'" value="'+i_satuan+'"><input readonly class="form-control" style="text-align:right;" id="e_satuan'+zz+'" name="e_satuan'+zz+'" value="'+e_satuan+'"></td>';

                    cols += '<td><select disabled type="text" style=width:320px id="i_2material'+zz+'" class="form-control" name="i_2material'+ zz + '" value="" onchange="getmaterial('+ zz + ');"> <option value="'+i_2material+'" selected>'+i_2material+' - '+e_2material+'</option></td>';
                    cols += '<td><input readonly type="text" id="n_2qty'+ zz + '" class="form-control" placeholder="0" name="n_2qty'+ zz + '" value="'+n_2qty+'" onkeyup="cekval(this.value); reformat(this);"/></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:right;" id="i_2satuan'+zz+'" name="i_2satuan'+zz+'" value="'+i_2satuan+'"><input readonly class="form-control" style="text-align:right;" id="e_2satuan'+zz+'" name="e_2satuan'+zz+'" value="'+e_2satuan+'"></td>';
                    cols += '<td><input readonly type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value="'+e_remark+'"/></td>';
                     // cols += '<td><input onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiall();" class="form-control" style="text-align:right;" id="ndeliver'+zz+'" name="ndeliver'+zz+'" value="'+stock+'"><input type="hidden" id="nstock'+zz+'" name="nstock'+zz+'" value="'+stock+'"><input type="hidden" id="vtotal'+zz+'" name="vtotal'+zz+'" value="0"></td>';
                     if (i_2material != ''){
                        cols += '<td style="text-align: center;"><input readonly type="checkbox" checked id="chk'+zz+'" name="chk'+zz+'" onclick="setaction('+zz+');"></td>';
                     } else {
                        cols += '<td style="text-align: center;"><input readonly type="checkbox" id="chk'+zz+'" name="chk'+zz+'" onclick="setaction('+zz+');"></td>';
                     }
                    
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    $('#i_2material'+zz).select2({
                        placeholder: 'Pilih Material',
                        allowClear: true,
                        ajax: {
                            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
        xx = $('#jml').val();
    }
</script>