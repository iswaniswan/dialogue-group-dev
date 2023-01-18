<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group row">
                        <label class="col-md-6">No SJ</label><label class="col-md-6">Tanggal SJ</label>
                        <div class="col-sm-6">
                            <input type="text" id= "isj" name="isj" class="form-control" value="<?= $data->i_sj;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "dsj" name="dsj" class="form-control date" value="<?= $data->dsj;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Bagian</label><label class="col-md-6">Periode Forecast</label>
                        <div class="col-sm-6">
                            <!-- <input type="hidden" id= "igudangqc" name="igudangqc" class="form-control" value="<?= $data->i_kode_master;?>" readonly> -->
                            <input type="hidden" id= "idepartement" name="idepartement" class="form-control" value="<?= $data->i_kode_master;?>" readonly>
                            <!-- <input type="text" id= "igudangqcfake" name="igudangqcfake" class="form-control" value="<#?= $data->e_sub_bagian;?>" readonly> -->
                            <input type="text" id= "igudangqcfake" name="igudangqcfake" class="form-control" value="<?= $data->e_departement_name;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "forcast" name="forcast" class="form-control" value="<?= $data->i_forcast;?>" >
                        </div>
                    </div>                                                     
                    <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                       <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                       <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>                 
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-6">Jenis Keluar</label><label class="col-md-6">Tanggal ETD</label>
                    <div class="col-sm-6">
                        <input type="hidden" id= "ijenis" name="ijenis" class="form-control" value="<?= $data->i_unit_packing;?>" readonly>
                        <input type="text" id= "ijenisfake" name="ijenisfake" class="form-control" value="<?= $data->e_supplier_name;?>" readonly>
                    </div>
                    <div class="col-sm-6">
                            <input type="text" id= "detd" name="detd" class="form-control date" value="<?= $data->detd;?>" readonly>
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
                                    <input style ="width:40px" type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:250px" type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_basename; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:130px" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly >
                                    <input style ="width:120px" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:50px" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" onkeyup="cekval(this.value,<?=$i;?>);" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:250px" type="text" id="eremarkh<?=$i;?>" name="eremarkh<?=$i;?>"value="<?= $row->e_remark; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" type="text" class="ibtnDel btn btn-md btn-danger"  value="Delete">                                 
                                </td>
                                </tr>
                                <?}?>
                                <!-- <label class="col-md-12">Jumlah Data</label> -->
                                    <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </form>
            <div>
        </div>
    </div>
</div>

<script>

$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
 });

 function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
}

$(document).ready(function(){
   $("#send").on("click", function () {
        var kode = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
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

$(document).ready(function () {
    $(".select2").select2();
 });    

$(document).ready(function () {
    $(".select").select();
    showCalendar('.date');
});

//var counter = 0;
var counter = document.getElementById("jml").value;
// var counter = 0;

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

function getproduct(id){
    // var iproduct = $('#iproduct'+id).val();
    var eproduct = $('#eproduct'+id).val();
    $.ajax({
    type: "post",
    data: {
        // 'i_product': iproduct
        'e_product': eproduct
    },
    url: '<?= base_url($folder.'/cform/getproduct'); ?>',
    dataType: "json",
    success: function (data) {
        $('#iproduct'+id).val(data[0].i_product_motif);
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