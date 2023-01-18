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
                        <label class="col-md-12">Jenis Keluar</label>
                        <div class="col-sm-12">
                            <select name="ijenis" id="ijenis" class="form-control select2">
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
                        <label class="col-md-12">Nama Penerima</label>
                        <div class="col-sm-12">
                            <select name="ipenerima" class="form-control select2">
                            <option value="">Pilih Nama Penerima</option>
                            <?php foreach ($penerima as $ipenerima):?>
                                <option value="<?php echo $ipenerima->i_nik;?>">
                                    <?php echo $ipenerima->i_nik.'-'.$ipenerima->e_nama_karyawan;?></option>
                            <?php endforeach; ?>
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
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Warna</th>
                            <th>Qty</th>
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
    $('#ijenis').select2({
    placeholder: 'Pilih Jenis',
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

    var counter = 0;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        $("#tabledata").attr("hidden", false);
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select style="width:150px;" type="text" id="iproduct'+ counter + '" class="form-control" readonly name="iproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" readonly id="iproductt'+ counter + '" class="form-control" name="iproductt'+ counter + '"><input type="text" readonly id="eproduct'+ counter + '" type="text" class="form-control" name="eproduct' + counter + '"></td>';
        cols += '<td><input type="hidden" readonly id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"><input type="text" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor'+ counter + '"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0" onkeyup="valstock('+ counter + ');"><input type="hidden" id="nstock'+ counter + '" class="form-control" name="nstock'+ counter + '" value="0"></td>';
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
        $('#nstock'+id).val(data[0].n_quantity_stock);

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

function valstock(id){
    // alert("Jumlah Stock Kurang");
    var pemenuhan   = document.getElementById("nquantity"+id).value;
    var stock       = document.getElementById("nstock"+id).value;
    
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        if(parseFloat(pemenuhan)>parseFloat(stock)){
                // alert('Jumlah sTOCK kURANG');
                swal ("Jumlah stock kurang, stock saat ini "+stock+"");
                document.getElementById("nquantity"+id).value=0;
                break;

        // 
        }
    }
}
</script>