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
            <div id="pesan"></div>
            <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Bagian</label><label class="col-md-4">Tanggal SJ </label>
                        <div class="col-sm-8">
                             <!-- <select name="igudangqc" id="igudangqc" class="form-control select2">
                            </select> -->
                            <select name="idepartement" id="idepartement" class="form-control select2" onchange="getmakloonpacking(this.value);">
                                <option value="" selected>-- Pilih Departemen Pembuat --</option>
                                <?php foreach ($departement as $key):?>
                                <option value="<?php echo $key->i_departement;?>"><?=$key->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dsj "name="dsj" class="form-control date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-8">Keterangan</label><label class="col-md-4">Tanggal etd</label>
                        <div class="col-sm-8">
                            <input type="text" id= "eremark "name="eremark" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "detd "name="detd" class="form-control date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                             <!-- <?php
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
                        <!-- <select name="itujuankirim" id="itujuankirim" class="form-control select2"> 
                            </select> -->
                            <select name="iunitpacking" id="iunitpacking" class="form-control select2" disabled>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "forcast "name="forcast" class="form-control">
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
                <input type="hidden" name="jml" id="jml" value="0"> 
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
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0" onkeyup="cekval(this.value,'+ counter + ');"></td>';
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

    function getkategori(ikodemaster) {
    $('#ikategori').attr("disabled", false);
    $("#addrow").attr("disabled", false);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getgudang');?>",
        data:"ikodemaster="+ikodemaster,
        dataType: 'json',
        success: function(data){
            $("#ikategori").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            get('KTG');
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

function getproduct(id){
    var eproduct = $('#eproduct'+id).val();
    
    $.ajax({
    type: "post",
    data: {
        'e_product': eproduct
    },
    url: '<?= base_url($folder.'/cform/getproduct'); ?>',
    dataType: "json",
    success: function (data) {
        
        $('#iproduct'+id).val(data[0].i_product_motif);
        $('#eproduct'+id).val(data[0].e_product_basename);
        $('#icolor'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);
        // swal("id");

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
                url: '<?= base_url($folder.'/cform/getproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#iproduct'+id).val(data[0].i_product_motif);
                    $('#eproduct'+id).val(data[0].e_product_basename);
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

function getmakloonpacking(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getmakloonpacking');?>",
            data: "id=" + id,
            dataType: 'json',
            success: function (data) {
                $("#iunitpacking").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                    $("#addrow").attr("hidden", false);
                    $("#iunitpacking").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }

        });
    }

function cekval(input,id){
        var jml   = counter;
        var num = input.replace(/\,/g,'');
        var nul = 0;
        if(!isNaN(num)){
        }else{
            swal('input harus numerik !!!');
            // input = input.substring(0,input.length-1);
            $('#nquantity'+id).val(0);
        }
}
</script>