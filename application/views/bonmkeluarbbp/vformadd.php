<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Gudang</label>
                        <label class="col-md-4">Tanggal BonK</label>
                        <div class="col-sm-8">
                            <select name="ikodemasterr" id="ikodemasterr" class="form-control select2" onchange="getstore();">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_kode_master;?>"> <?= $ikodemaster->e_nama_master;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="ikodemaster" name="ikodemaster" class="form-control" value="">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dbonk" name="dbonk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>  
                        </div>
                    </div>
                <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Jenis Keluar</label>
                        <div class="col-sm-6">
                            <select name="jnskeluar" id="jnskeluar" class="form-control select2" onchange="gettujuan(this.value);" >
                                <option value="">-- Pilih Jenis Keluar --</option>
                                <?php foreach ($jnskeluar as $ijnskeluar):?>
                                <option value="<?php echo $ijnskeluar->i_jenis;?>">
                                    <?= $ijnskeluar->e_jenis_keluar;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Tujuan</label>
                        <label class="col-md-6">Tujuan Kirim</label>
                        <div class="col-sm-6">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="gettujuankirim(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="itujuankirim" id="itujuankirim" class="form-control select2">
                            </select>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="30%">Nama Barang</th>  
                                    <th>Satuan</th>
                                    <th>Satuan Konversi</th>
                                    <th>Qty</th>
                                    <th>Qty Konversi</th>
                                    <th>Keterangan</th>.
                                    <th>Bis Bisan</th>
                                    <th>Action</th>
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
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
    $("#itujuan").attr("disabled", true);
    $("#itujuankirim").attr("disabled", true);
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function getstore() {
        var gudang = $('#ikodemasterr').val();
        //alert(gudang);
        $('#ikodemaster').val(gudang);

        if (gudang == "") {
            $("#addrow").attr("hidden", true);
        } else {
            $("#addrow").attr("hidden", false);
        }
        
    }

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

function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        for(j=1;j<=jml;j++){
           if(document.getElementById("nquantity"+j).value=='')
              document.getElementById("nquantity"+j).value='0';
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
        $('#ikodemasterr').attr("disabled", true);
        $("#tabledata").attr("hidden", false);
        document.getElementById("jml").value = counter;
        //var ikodemaster = $("#ikodemaster").val();
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]"></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan[]" value="" readonly onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan[]" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="esatuankonv'+ counter + '" class="form-control" name="esatuankonv[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantitykonv'+ counter + '" class="form-control" name="nquantitykonv[]" value="" onkeyup="cekqty('+counter+');"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols +='<td><select style="width:80px;" class="form-control select2" type="text" id="fbisbisan'+ counter +'" class="form-control select2" name="fbisbisan[]"><option value="t">Ya</option><option value="f">Tidak</option></select></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
//cols += '<td><input type="checkbox" id="fbisbisan'+ counter + '" name="fbisbisan[]"></td>';
//<input type="text" id="fkonv'+ counter + '" class="form-control" name="fkonv[]" value = "0";>
       
        var ikodemaster = $('#ikodemaster').val();
        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#ematerialname'+ counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+ikodemaster,
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
      //$('.select2').select2();
    });

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        // counter -= 1
        // document.getElementById("jml").value = counter;
    });

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
            $('#isatuan'+id).val(data[0].i_satuan);

            ada=false;
            var a = $('#imaterial'+id).val();
            var e = $('#ematerialname'+id).val();
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
                var imaterial    = $('#imaterial'+id).val();
                $.ajax({
                    type: "post",
                    data: {
                        'imaterial'  : imaterial,
                    },
                    url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
                        $('#esatuan'+id).val(data[0].e_satuan);
                        $('#esatuan'+id).val(data[0].i_satuan);
                    },
                });
            }else{
                $('#imaterial'+id).html('');
                $('#ematerialname'+id).val('');
                $('#esatuan'+id).val('');
                // $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
    
    function gettujuankirim(itujuan) {
        $("#itujuankirim").attr("disabled", false);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/gettujuankirim');?>",
            data:"itujuan="+itujuan,
            dataType: 'json',
            success: function(data){
                $("#itujuankirim").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#itujuankirim").attr("disabled", true);
                }else{
                    $("#itujuankirim").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function gettujuan(jnskeluar) {
        var tes = document.getElementById("jnskeluar").value; 
          if(tes == "1"){
            $('#itujuan').attr("disabled", false);
          }else{
            $('#itujuan').attr("disabled", true);
          }
    }  
</script>