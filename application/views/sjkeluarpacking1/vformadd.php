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
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal SJ</label>
                        <div class="col-sm-12">
                            <input type="text" id= "dsj "name="dsj" class="form-control date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Unit Packing</label>
                        <div class="col-sm-12">
                            <select name="ipacking" id="ipacking" class="form-control select2"> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Periode Forecast</label>
                        <div class="col-sm-6">
                            <select id= "blnforecast" name="blnforecast" class="form-control select2">
                                 <option value="">Pilih bulan</option>
                                 <option value='01'>Januari</option>
                                 <option value='02'>Februari</option>
                                 <option value='03'>Maret</option>
                                 <option value='04'>April</option>
                                 <option value='05'>Mei</option>
                                 <option value='06'>Juni</option>
                                 <option value='07'>Juli</option>
                                 <option value='08'>Agustus</option>
                                 <option value='09'>September</option>
                                 <option value='10'>Oktober</option>
                                 <option value='11'>November</option>
                                 <option value='12'>Desember</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-6">
                            <select id= "thnforecast" name="thnforecast" class="form-control select2">
                             <option>Pilih tahun</option>
                             <?php
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++)
                                {
                                   echo "<option value='$i'>$i</option>";
                                }
                             ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis Keluar</label>
                        <div class="col-sm-12">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <option value="">Pilih Jenis Keluar</option>
                                <option value="1" >Bagus ke Gudang Jadi</option>
                                <option value="2" >Retur</option>
                            </select>
                        </div>
                    </div>                                  
                    <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                       <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  

                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>                 
                    </div>
                </div>
            </div>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Tujuan</label>
                        <div class="col-sm-12">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="get(this.value);"> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tujuan Kirim</label>
                        <div class="col-sm-12">
                            <select name="igudang" id="igudang" class="form-control select2"> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control">
                        </div>
                    </div>
                </div>    
                <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                    </thead>
                </table>
                </div>    
                <input type="text" name="jml" id="jml" value="0"> 
                </form>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select2").select2();
 });

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
    $('#itujuan').select2({
    placeholder: 'Pilih Tujuan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/jenistujuan'); ?>',
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

function get(itujuan) {
    /*alert(iarea);*/
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/gettujuan');?>",
        data:"itujuan="+itujuan,
        dataType: 'json',
        success: function(data){
            $("#igudang").html(data.kop);
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
    
$(document).ready(function () {
    $('#ipacking').select2({
    placeholder: 'Pilih Unit Packing',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/unitpacking'); ?>',
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
// $(document).ready(function () {
//         $('#igudang').select2({
//         placeholder: 'Pilih Tujuan Kirim',
//         allowClear: true,
//         ajax: {
//           url: '<?= base_url($folder.'/cform/tujuankirim/'); ?>',
//           dataType: 'json',
//           delay: 250,          
//           processResults: function (data) {
//             return {
//               results: data
//             };
//           },
//           cache: true
//         }
//       })
// });

    var counter = 0;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        $("#tabledata").attr("hidden", false);
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select style="width:150px;" type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" readonly id="iproductt'+ counter + '" class="form-control" name="iproductt'+ counter + '"><input type="text" readonly id="eproduct'+ counter + '" type="text" class="form-control" name="eproduct' + counter + '"></td>';
        cols += '<td><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"><input type="text" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor'+ counter + '"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0"></td>';
        cols += '<td><input type="text" id="eremarkh'+ counter + '" class="form-control" name="eremarkh' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#iproduct'+ counter).select2({
        placeholder: 'Pilih Kode Barang',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/dataproduct'); ?>',
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
      
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });

function getproduct(id){
    var iproduct = $('#iproduct'+id).val();
    var strArray = iproduct.split("-");        
        // Display array values on page
        for(var i = 0; i < strArray.length; i++){
            var kdproduct = strArray[0];
            var color = strArray[1];
        }

    $.ajax({
    type: "post",
    data: {
        'i_product': iproduct,
        'kdproduct': kdproduct,
        'color': color,
    },
    url: '<?= base_url($folder.'/cform/getproduct'); ?>',
    dataType: "json",
    success: function (data) {
        $('#iproductt'+id).val(data[0].i_product);
        $('#eproduct'+id).val(data[0].e_product_namewip);
        $('#icolor'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);

        ada=false;
        var a = $('#iproductt'+id).val();
        var e = $('#eproduct'+id).val();
        var c = $('#ecolor'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
             if((a == $('#iproductt'+i).val()) && (i!=jml) && (c == $('#ecolor'+i).val())){
                swal ("Kode : "+a+" dan warna "+c+" sudah ada !!!!!");
                ada=true;
                break;
            }else{
                ada=false;     
            }
        }
        if(!ada){
            var iproduct    = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'i_product'  : iproduct,
                },
                url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                dataType: "json",
                success: function (data) {
                     $('#eproduct'+id).val(data[0].e_product_namewip);
                     $('#icolor'+id).val(data[0].i_color);
                     $('#ecolor'+id).val(data[0].e_color_name);
                },
            });
        }else{
            $('#iproduct'+id).html('');
            $('#eproduct'+id).val('');
            $('#icolor'+id).val('');
            $('#ecolor'+id).val('');
        }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
</script>