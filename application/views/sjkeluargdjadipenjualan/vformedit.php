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
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Warna</th>
                                    <th>Qty</th>
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
                                    <input style ="width:130px" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly >
                                    <input style ="width:120px" class="form-control" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" >
                                </td>                               
                                <td class="col-sm-1">
                                    <input style ="width:70px" type="text" class="ibtnDel btn btn-md btn-danger"  value="Delete">                                 
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
        cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select style="width:150px;" type="text" id="iproduct'+ counter + '" class="form-control" readonly name="iproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" readonly id="iproductt'+ counter + '" class="form-control" name="iproductt'+ counter + '"><input type="text" readonly id="eproduct'+ counter + '" type="text" class="form-control" name="eproduct' + counter + '"></td>';
        cols += '<td><input type="text" readonly id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"><input type="text" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor'+ counter + '"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0"></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

    newRow.append(cols);
    $("#tabledata").append(newRow);
   
    $('#iproduct'+ counter).select2({
    placeholder: 'Pilih Kode Product',
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
//alert(iproduct);
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
        $('#iproductt'+id).val(data[0].i_product_wip);
        $('#eproduct'+id).val(data[0].e_product_namewip);
        $('#icolor'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);

        ada=false;
        var a = $('#iproduct'+id).val();
        var e = $('#eproduct'+id).val();
        var c = $('#ecolor'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#iproduct'+i).val()) && (i!=jml) && (c == $('#ecolor'+i).val())){
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