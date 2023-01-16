<style>
        td {
            white-space: nowrap;
        }
        .withscroll {
            width: 1800px;
            overflow-x: scroll;
            white-space: nowrap;
        }
    </style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Pembuat Dokumen</label>
                        <label class="col-md-4">Tanggal Permintaan</label>
                        <div class="col-sm-8">
                            <?php 
                                if($dept == '1'){?>
                                <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                    <option value="" selected>-- Pilih Gudang --</option>
                                    <?php foreach ($kodegudang as $kodegudang):?>
                                        <option value="<?php echo $kodegudang->i_kode_master;?>"><?= $kodegudang->e_nama_master;?></option>
                                    <?php endforeach;?>
                                </select>
                                <?}else{?>
                                    <input readonly type="text" id="ipembuat" name="ipembuat" class="form-control" value="<?=$kodemaster->i_departement.' - '.$kodemaster->e_departement_name;?>">
                                    <input readonly type="hidden" id="ikode" name="ikode" class="form-control" value="<?=$kodemaster->i_departement;?>">
                                    <input readonly type="hidden" id="ikodemaster" name="ikodemaster" class="form-control" value="<?=$kodegudang->i_kode_master;?>">
                                <?}?>
                            
                            <!-- <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_kode_master;?>"><?= $ikodemaster->e_nama_master;?></option>
                                <?php endforeach; ?>
                            </select> -->
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?=$kodegudang->i_kode_master;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date" onchange="max_tgl(this.value)"  value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-8">Jenis Pengeluaran</label>
                        <label class="col-md-4">Tanggal Pengembalian</label>
                        <div class="col-sm-8">
                            <select name="jenis" id="jenis" class="form-control select2" onchange="ketabel(); getreffmemo(this.value);">
                                <option value="" selected>-- Pilih Jenis Pengeluaran --</option>
                                <?php foreach ($jeniskeluar as $jeniskeluar):?>
                                <option value="<?php echo $jeniskeluar->i_jenis;?>"><?= $jeniskeluar->e_nama_jenis;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="jenispengeluaran" name="jenispengeluaran" class="form-control" value="">
                            <input type="hidden" id="ireffmemo" name="ireffmemo" class="form-control" value="">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dback" name="dback" class="form-control date" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row" hidden="">
                        <div class="col-sm-8">
                        <label >Nomor Memo (Optional)</label>
                            <input type="text" id="imemo" name="imemo" class="form-control" maxlength="" value="">
                        </div>
                        <div class="col-sm-4">
                        <label >Tanggal Memo (Optional)</label>
                            <input type="text" id="dmemo" name="dmemo" class="form-control date" value="" readonly>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        <button type="button" id="addrowlain" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" disabled onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tujuan</label>
                        <label class="col-md-6">Partner</label>
                        <div class="col-sm-6">
                            <select name="tujuankeluar" id="tujuankeluar" class="form-control select2" onchange="getpic(this.value)">
                                <option value="">-- Pilih Tujuan --</option>
                                <option value="internal">Internal</option>
                                <option value="external">External</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="edept" id="edept" class="form-control select2" onchange="getppic(this.value);">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="divpic">
                        <label class="col-md-6">PIC</label>
                        <label class="col-md-6">Nama Peminjam</label>
                        <div class="col-sm-6">
                            <select name="ppic" id="ppic" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="epic" name="epic" class="form-control">
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                    <div class="panel-body table-responsive withscroll">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="7%">Kode Barang</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="5%">Qty</th>
                                    <th width="7%">Satuan</th>
                                    <th width="7%">Kode Barang</th>
                                    <th width="20%">List Nama Barang</th>
                                    <th width="5%">Qty</th>
                                    <th width="7%">Satuan</th>
                                    <th width="10%">Keterangan</th>
                                    <th width="5%" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-body table-responsive">
                        <table id="tabledata2" class="table table-bordered" cellspacing="0" width="100%" hidden="">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="7%">Kode Barang</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="5%">Qty</th>
                                    <th width="7%">Satuan</th>
                                    <th width="10%">Keterangan</th>
                                    <th width="5%" colspan="2">Action</th>
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

<script>
function max_tgl(val) {
  $('#dback').datepicker('destroy');
  $('#dback').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dbonk').value,
  });
}
$('#dback').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dbonk').value,
});


function ketabel() {
    var pilih =  $("#jenis").val();
    var gudang = $('#istore').val();
    $("#jenispengeluaran").val(pilih);
        //alert(gudang);

    if (pilih == "" && gudang =="") {
        $("#addrow").attr("hidden", true);
    } else {
        $("#addrow").attr("hidden", false);
    }

    if (pilih == "JK00002") {
        if (pilih == "" && gudang =="") {
            $("#addrow").attr("hidden", true);
            $("#tabledata").attr("hidden", true);
        } else {
            $("#addrow").attr("hidden", false);
            $("#tabledata").attr("hidden", false);
            $("#tabledata2").attr("hidden", true);
            $("#addrowlain").attr("hidden", true);
        }
    } else {
        if (pilih == "" && gudang =="") {
            $("#addrowlain").attr("hidden", true);
            $("#tabledata2").attr("hidden", true);
        } else {
            $("#addrowlain").attr("hidden", false);
            $("#tabledata2").attr("hidden", false);
            $("#tabledata").attr("hidden", true);
            $("#addrow").attr("hidden", true);
        }
    }

    if(pilih == "JK00001"){
        $("#divpic").attr("hidden", false);
    } else {
        $("#divpic").attr("hidden", true);
    }
    //alert(pilih);
    //JK00002
}
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $(".ibtnDel").attr("disabled", true);
    $("#send").attr("disabled", false);
});

$(document).ready(function () {
//     $('#ppic').select2({
//     placeholder: 'Pilih PIC',
//     allowClear: true,
//     ajax: {
//       url: '<?= base_url($folder.'/cform/getppic'); ?>',
//       dataType: 'json',
//       delay: 250,          
//       processResults: function (data) {
//         return {
//           results: data
//         };
//       },
//       cache: true
//     }
//   })

    $("#send").on("click", function () {
        var kode = $("#kode").val();
        // var arr = awal.split("gudang");
        // alert(arr[1]);
        var gudang = $("#istore").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                     'gudang'  : gudang
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });

});

function getenabledsend() {
    $('#send').attr("disabled", true);
}

    function getppic(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getppic');?>",
            data: "ipartner=" + id,
            dataType: 'json',
            success: function (data) {
                $("#ppic").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                    $("#ppic").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }
        });
    }

function getreffmemo(ireff){
    var id = $('#jenis').val();
    $.ajax({
        type: "post",
        data: {
            'id': id
        },
        url: '<?= base_url($folder.'/cform/getreffmemo'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ireffmemo').val(data[0].jeniskeluar);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getpic(tujuankeluar){
    //alert(tujuankeluar);
    var ikodemaster = $('#ikode').val();
    $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getpic/');?>"+ikodemaster,
            data:"tujuankeluar="+tujuankeluar,
            dataType: 'json',
            success: function(data){
                $("#edept").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#edept").attr("disabled", true);
                }else{
                    $("#edept").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
        // if(tujuankeluar == 'external'){
        //     $("#epic").attr("hidden", false);
        // }else{
        //     $("#epic").attr("hidden", true);
        // }
}

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
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]"></td>';
        cols += '<td rowspan="1"><select style="width:400px;" type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]"/></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]"></td>';
        cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" id="nquantity2'+ counter + '" class="form-control" placeholder="0" name="nquantity2[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
        cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ikodemaster').attr("disabled", true);
        $('#jenis').attr("disabled", true);
        var gudang = $('#istore').val();

        $('#ematerialname'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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

        $('#ematerialname2'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
    });

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        // counter -= 1
        // document.getElementById("jml").value = counter;
        del();
    });

    $("#tabledata").on("click", "#addrow2", function (event) {
        //$(this).closest('td').find('td').attr('rowspan','2');
        var row = $(this).closest("tr");
        var material = $(this).closest('tr').find('.imaterial').val();
        var nquantity = $(this).closest('tr').find('.nquantity').val();
        var isatuan = $(this).closest('tr').find('.isatuan').val();
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        //alert(count);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td colspan="5"><input style="width:100px;" type="hidden" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+material+'"><input type="hidden" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+nquantity+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+isatuan+'"/></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]"></td>';
        cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" id="nquantity2'+ counter + '" class="form-control" placeholder="0" name="nquantity2[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value=""/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
        cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        //$("#tabledata").append(newRow);
        newRow.insertAfter(row);
        var gudang = $('#istore').val();
        $('#ematerialname2'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getmaterial(id){
        var ematerialname = $('#ematerialname'+id).val();
        $.ajax({
            type: "post",
            data: {
                'ematerialname': ematerialname
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#imaterial'+id).val(data[0].i_material);
                $('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan_code);
           ada=false;
            var a = $('#imaterial'+id).val();
            var e = $('#ematerialname'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
                if((a == $('#imaterial'+i).val()) && (i!=id)){
                    swal ("kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            if(!ada){
                $('#imaterial'+id).val(data[0].i_material);
                $('#ematerialname'+id).val(data[0].e_material_name);
                $('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan_code);
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
                $('#ematerialname'+id).val('');
                $('#isatuan'+id).val('');
                // $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function getmaterial2(id){
        var ematerialname = $('#ematerialname2'+id).val();
        $.ajax({
                type: "post",
                data: {
                    'ematerialname': ematerialname
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                $('#imaterial2'+id).val(data[0].i_material);
                $('#ematerialname2'+id).val(data[0].e_material_name);
                $('#esatuan2'+id).val(data[0].e_satuan);
                $('#isatuan2'+id).val(data[0].i_satuan_code);
                    
            },
            error: function () {
                alert('Error :)');
            }
        });
    }
    
    function getstore() {
        var gudang = $('#ikodemaster').val();
        //alert(gudang);
        $('#istore').val(gudang);
        
    }

    function cek() {
        var dbonk = $('#dbonk').val();
        var imemo = $('#imemo').val();
        var dmemo = $('#dmemo').val();
        var istore = $('#istore').val();
        var tujuankeluar = $('#tujuankeluar').val();
        var pic = $('#pic').val(); 
        var dept = $('#dept').val();
        var jenispengeluaran = $('#jenispengeluaran').val();

        if (dbonk == ''  || istore == '' || tujuankeluar == '' || dept == '' || jenispengeluaran == '') {
            alert('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }

    ///////////////////////////UNTUK JENIS PENGELAURAN SELAIN MAKLOON///

$(document).ready(function () {
    $("#addrowlain").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata2 tr').length;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]"></td>';
        cols += '<td rowspan="1"><select style="width:400px;" type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata2").append(newRow);
        
        $('#ikodemaster').attr("disabled", true);
        $('#jenis').attr("disabled", true);
        var gudang = $('#istore').val();

        $('#ematerialname'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
    });


    $("#tabledata2").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        // counter -= 1
        // document.getElementById("jml").value = counter;
        del2();
    });

    function del2() {
        obj=$('#tabledata2 tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

});
</script>