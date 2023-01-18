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
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-10">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_kode_master;?>">
                                    <?= $ikodemaster->i_kode_master." - ".$ikodemaster->e_nama_master;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-10">
                            <select required="" id="ibonmk" name="ibonmk" class="form-control" disabled="" onchange="getdetailsjkm();"></select>
                        </div>
                    </div>
                    
                      <div class="form-group row">
                        <div class="col-sm-7">
                            <label >Nomor Memo</label>
                            <input type="text" id="imemo" name="imemo" class="form-control" maxlength="" value="" readonly>
                        </div>
                        
                        <div class="col-sm-4">
                            <label >Tanggal Memo</label>
                            <input type="text" id="dmemo" name="dmemo" class="form-control date" value="" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Tanggal Bon Keluar</label>
                        <div class="col-sm-10">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Tujuan Keluar</label>
                        <div class="col-sm-12">
                            <input type="text" id= "tujuankeluar" name="tujuankeluar" class="form-control" maxlength="30" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label >Nama PIC</label>
                            <input type="text" id="pic" name="pic" class="form-control" readonly>
                        </div>
                        
                        <div class="col-sm-5">
                            <label >Nama Department</label>
                            <input type="text" id="dept" name="dept" class="form-control" readonly>
                        </div>
                    </div>
                     

                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>N0</th>
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

         $('#ibonmk').select2({
            placeholder: 'Cari No. Bon Keluar Pinjaman',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getbonmk/'); ?>',
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
            $("#ibonmk").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
            $("#ibonmk").attr("disabled", false);
            //$("#addrow").attr("hidden", false);
        }
        
        $('#ibonmk').html('');
        $('#ibonmk').val('');
    }

    function getdetailsjkm() {
        // var ibonmk = $('#ibonmk option:selected').text();
        // var dspmb = ibonmk.substr(-10);
        var sjkm = $('#ibonmk').val();
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
                'ibonmk': sjkm,
                'gudang': gudang
            },
            url: '<?= base_url($folder.'/cform/getdetailbonmk'); ?>',
            dataType: "json",
            success: function (data) {
                var i_memo = data['head']['i_memo'];
                var d_memo = data['head']['d_memo'];
                var d_bonk = data['head']['d_bonk'];
                var tujuan_keluar = data['head']['tujuan_keluar'];
                var pic = data['head']['pic'];
                var department = data['head']['department'];
        
                $('#imemo').val(i_memo);
                $('#dmemo').val(d_memo);
                $('#dbonk').val(d_bonk);
                $('#tujuankeluar').val(tujuan_keluar);
                $('#pic').val(pic);
                $('#dept').val(department);

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
            $('#i_2satuan'+id).val("");
            $('#e_2satuan'+id).val("");
            $('#i_2material'+id).val("").change();
            $('#n_2qty'+id).val("");
        }
    }

    function cek() {
        var dsjk = $('#dsjk').val();
        var ibonmk = $('#ibonmk').val();
        var istore = $('#istore').val();

        if (dsjk == '' || ibonmk == null || istore == '') {
            alert('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }
</script>