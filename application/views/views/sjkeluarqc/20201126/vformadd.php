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
                        <label class="col-md-6">Gudang QC</label><label class="col-md-6">Tanggal SJ</label>
                        <div class="col-sm-6">
                            <select name="igudangqc" id="igudangqc" class="form-control select2"> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "dsj "name="dsj" class="form-control date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Gudang QC</label>
                        <div class="col-sm-12">
                            <select name="igudangqc" id="igudangqc" class="form-control select2"> 
                            </select>
                        </div>
                    </div> -->
                    <!-- <div class="form-group row">
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
                    </div> -->
                    <!-- <div class="form-group">
                        <label class="col-md-12">Jenis Keluar</label>
                        <div class="col-sm-12">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <option value="">Pilih Jenis Keluar</option>
                                <option value="1">Bagus Ke Unit Packing</option>
                                <option value="2">Bagus Gudang Jadi</option>
                                <option value="3">Retur Perbaikan Unit Jahit</option>
                            </select>
                        </div>
                    </div>                                   -->
                    <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  

                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>                 
                    </div>
                </div>
            </div>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Tujuan</label><label class="col-md-6">Forecast</label>
                        <div class="col-sm-6">
                            <select name="itujuan" id="itujuan" class="form-control select2"> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "forcast "name="forcast" class="form-control">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Tujuan Kirim</label>
                        <div class="col-sm-12">
                            <select name="igudang" id="igudang" class="form-control select2"> 
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-4">Tujuan Kirim</label><label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4">
                            <select name="itujuankirim" id="itujuankirim" class="form-control select2"> 
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id= "eremark "name="eremark" class="form-control">
                        </div>
                        
                    </div>
                </div>    
                <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                    <thead>
                        <tr>
                            <th width = "10%">No</th>
                            <th width = "15%">Kode</th>
                            <th width = "25%">Nama Barang</th>
                            <th width = "10%">Warna</th>
                            <th width = "10%">Quantity</th>
                            <th width = "30%">Keterangan</th>
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

$(document).ready(function () {
    $('#itujuankirim').select2({
    placeholder: 'Pilih Tujuan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/jenistujuankirim'); ?>',
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

// function get(itujuan) {
//     /*alert(iarea);*/
//     $.ajax({
//         type: "POST",
//         url: "<#?php echo site_url($folder.'/Cform/gettujuan');?>",
//         data:"itujuan="+itujuan,
//         dataType: 'json',
//         success: function(data){
//             $("#igudang").html(data.kop);
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
    
$(document).ready(function () {
    $('#igudangqc').select2({
    placeholder: 'Pilih Sub Bagian',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
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
//           url: '<#?= base_url($folder.'/cform/tujuankirim/'); ?>',
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
        // $("#submit").attr("disabled", false);
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td><input style="width:40px;" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input type="text" id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct' + counter + '" readonly></td>';
        // cols += '<td><select style="width:150px;" type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><select class="form-control" type="text" id="eproduct'+ counter + '" class="form-control" name="eproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        // cols += '<td><input type="text" id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct' + counter + '"></td>';
        // cols += '<td><input type="text" id="eproduct'+ counter + '" type="text" class="form-control" name="eproduct' + counter + '"></td>';
        cols += '<td><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"><input type="text" id="ecolor'+ counter + '" class="form-control" name="ecolor'+ counter + '" readonly></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0"></td>';
        cols += '<td><input type="text" id="eremarkh'+ counter + '" class="form-control" name="eremarkh' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#eproduct'+ counter).select2({
        templateSelection: formatSelection,
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

    function formatSelection(val) {
        return val.name;
    }

function getproduct(id){
    var iproduct = $('#eproduct'+id).val();
    
    $.ajax({
    type: "post",
    data: {
        'i_product': iproduct
    },
    url: '<?= base_url($folder.'/cform/getproduct'); ?>',
    dataType: "json",
    success: function (data) {
        $('#iproduct'+id).val(data[0].i_product);
        $('#eproduct'+id).val(data[0].e_product_namewip);
        $('#icolor'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);

        ada=false;
        var a = $('#iproduct'+id).val();
        var e = $('#eproduct'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#iproduct'+i).val()) && (i!=jml)){
                swal ("Kode : "+a+" sudah ada !!!!!");
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