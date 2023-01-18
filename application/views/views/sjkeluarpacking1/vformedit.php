<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group">
                        <label class="col-md-12">No SJ</label>
                        <div class="col-sm-12">
                            <input type="text" id= "isj" name="isj" class="form-control" value="<?= $data->i_sj;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal SJ</label>
                        <div class="col-sm-12">
                            <input type="text" id= "dsj" name="dsj" class="form-control date" value="<?= $data->d_sj;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Unit Packing</label>
                        <div class="col-sm-12">
                            <input type="hidden" id= "ipacking" name="ipacking" class="form-control" value="<?= $data->i_unit_packing;?>" readonly>
                            <input type="text" id= "ipackingfake" name="ipackingfake" class="form-control" value="<?= $data->e_nama_packing;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Periode Forecast</label>
                        <div class="col-sm-6">
                            <input type="hidden" id= "iperiodeforcast" name="iperiodeforcast" class="form-control" value="<?= $data->i_periode_forcast;?>" readonly>
                            <?php $th=substr($data->i_periode_forcast,0,4);
                                  $bl=substr($data->i_periode_forcast,4,2); ?>
                            <select id= "blnforecast" name="blnforecast" class="form-control select2" readonly disabled="">
                                <option value='01' <?php if($bl =='01') { ?> selected <?php } ?> >Januari</option>
                                <option value='02' <?php if($bl =='02') { ?> selected <?php } ?> >Februari</option>
                                <option value='03' <?php if($bl =='03') { ?> selected <?php } ?> >Maret</option>
                                <option value='04' <?php if($bl =='04') { ?> selected <?php } ?> >April</option>
                                <option value='05' <?php if($bl =='05') { ?> selected <?php } ?> >Mei</option>
                                <option value='06' <?php if($bl =='06') { ?> selected <?php } ?> >Juni</option>
                                <option value='07' <?php if($bl =='07') { ?> selected <?php } ?> >Juli</option>
                                <option value='08' <?php if($bl =='08') { ?> selected <?php } ?> >Agustus</option>
                                <option value='09' <?php if($bl =='09') { ?> selected <?php } ?> >September</option>
                                <option value='10' <?php if($bl =='10') { ?> selected <?php } ?> >Oktober</option>
                                <option value='11' <?php if($bl =='11') { ?> selected <?php } ?> >November</option>
                                <option value='12' <?php if($bl =='12') { ?> selected <?php } ?> >Desember</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-6">
                            <select id= "thnforecast" name="thnforecast" class="form-control select2" readonly disabled="">
                                <option>Pilih tahun</option>
                                  <?php
                                    $tahun1 = date('Y')-3;
                                    $tahun2 = date('Y');
                                        
                                    for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                        <option value='<?php echo $i ?>' <?php if($i == $th) { ?> selected <?php } ?> ><?php echo $i ?></option>
                                  <?php  } ?>
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
                    <label class="col-md-12">Jenis Keluar</label>
                    <div class="col-sm-12">
                        <input type="hidden" id= "ijenis" name="ijenis" class="form-control" value="<?= $data->i_jenis;?>" readonly>
                        <input type="text" id= "ijenisfake" name="ijenisfake" class="form-control" value="<?= $data->e_jenis_keluar;?>" readonly>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-md-12">Tujuan</label>
                    <div class="col-sm-12">
                        <input type="hidden" id= "itujuan" name="itujuan" class="form-control" value="<?= $data->i_tujuan;?>" readonly>
                        <input type="text" id= "itujuanfake" name="itujuanfake" class="form-control" value="<?= $data->e_tujuan;?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Tujuan Kirim</label>
                    <div class="col-sm-12">
                        <input type="hidden" id="igudang" name="igudang" class="form-control" value="<?= $data->i_tujuan_kirim;?>" readonly>
                        <input type="text" id= "igudangfake" name="igudangfake" class="form-control" value="<?= $data->tujuan;?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" value="<?= $data->e_remark;?>">
                    </div>
                </div>
            </div>    
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Warna</th>
                                    <th>Qty</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                foreach ($datadetail as $row) {
                                $i++;?>
                                <tr>
                                <td class="col-sm-1">
                                    <input style ="width:40px" class="form-control" type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="iproductt<?=$i;?>" name="iproductt<?=$i;?>"value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px" class="form-control" type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:130px" class="form-control" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly >
                                    <input style ="width:120px" class="form-control" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="eremarkh<?=$i;?>" name="eremarkh<?=$i;?>"value="<?= $row->e_remark; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" type="text" class="ibtnDel btn btn-md btn-danger"  value="Delete">                                 
                                </td>
                                </tr>
                                <?}?>
                                <label class="col-md-12">Jumlah Data</label>
                                    <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </form>
            <div>
        </div>
    </div>
</div>

<script>

// $("form").submit(function(event) {
//      event.preventDefault();
//      $("input").attr("disabled", true);
//      $("select").attr("disabled", true);
//      $("#submit").attr("disabled", true);
//  });

$(document).ready(function () {
    $(".select2").select2();
 });    

$(document).ready(function () {
    $(".select").select();
    showCalendar('.date');
});

//var counter = 0;
var counter = document.getElementById("jml").value;
$("#addrow").on("click", function () {
    // alert("tes");
    counter++;

    document.getElementById("jml").value = counter;

    var newRow = $("<tr>");
    
    var cols = "";
        cols += '<td><input style="width:40px;" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'"></td>';
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