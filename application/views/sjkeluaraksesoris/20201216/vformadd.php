<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>                      
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label>
                        <label class="col-md-6">No Memo</label>
                            <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2"> 
                                <option value="">-- Pilih gudang --</option>
                                <?php foreach ($gudang as $igudang):?>
                                <option value="<?php echo $igudang->i_kode_master;?>">
                                    <?= $igudang->i_kode_master. '-'.$igudang->e_nama_master;?></option>
                                <?php endforeach; ?> 
                            </select>
                                
                        </div>
                        <div class="col-sm-6">
                        <select name="imemo" id="imemo" class="form-control select2" onchange="get(this.value);"> 
                                <option value="">-- Pilih Memo --</option>
                                <?php foreach ($memo as $imemo):?>
                                <option value="<?php echo $imemo->i_op_code;?>">
                                    <?= $imemo->i_op_code;?></option>
                                <?php endforeach; ?>
                                <!-- <input type="hidden" name="dschedule" id="dschedule" class="form-control" value="" > -->
                                </select>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">keterangan</label>
                        <!-- <label class="col-md-4">Total Discount</label>
                        <label class="col-md-4">Netto</label> -->
                        <div class="col-sm-12">
                            <input readonly type="text" id="eremark" name="eremark" class="form-control">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Tanggal Bon Keluar</label>
                        <div class="col-sm-6">
                            <input type="text" name="dbonk" class="form-control date"  value="" readonly>
                        </div>
                    </div>  -->
                </div> 
                <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-9">Nama Customer</label>
                    <label class="col-md-3">Tanggal Memo</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="icustomer" id = "icustomer" class="form-control" value="" readonly>
                        <input type="text" name="ecustomername" id = "ecustomername" class="form-control" value="" readonly>
                    </div>
                    <div class="col-sm-3">
                            <input type="text" name="dmemo" id = 'dmemo' class="form-control date"  value="" readonly>
                        </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-9">Alamat</label>
                    <label class="col-md-3">Tanggal SJ</label>
                    <div class="col-sm-9">
                        <input type="text" name="ecustaddress" id = "ecustaddress" class="form-control" value="" readonly>
                        <!-- <input type="text" name="ecustomername" id = "ecustomername" class="form-control" value="" readonly> -->
                    </div>
                    <div class="col-sm-3">
                            <input type="text" name="dsj" class="form-control date"  value="" readonly >
                        </div>
                </div>  
          
                </div> 
                <div class="form-group">
                    <div class="col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                    </div>               
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>satuan</th>
                            <th>Qty Order</th>
                            <th>Qty Deliver</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>    
          
        </form>
    </div>
</div>
<script>
    
$("form").submit(function (event) {
    event.preventDefault();
});

$(document).ready(function () {
$(".select2").select2();
});

$(document).ready(function () {
  $('.select2').select2();
  showCalendar('.date');
});

function get(id) {
    // removeBody();

    var imemo = $('#imemo').val();
     
    $.ajax({
        type: "post",
        data: {
            'imemo': id
        },
        // swal(imemo);
        url: '<?= base_url($folder.'/cform/getmemo'); ?>',
        dataType: "json",
        success: function (data) {
            // var i_memo = data['head']['i_memo'];
            // var e_remark = data['head']['e_remark'];
            var icust               = data['head']['i_customer'];
            var ecustname           = data['head']['e_customer_name'];
            var ecustaddres         = data['head']['e_customer_address'];
            var ndisc               = data['head']['v_customer_discount'];
            var dmemo               = data['head']['d_op'];
            var vspbdiscounttotal   = data['head']['v_discount_total'];
            var vspb                = data['head']['v_total_gross'];
            var vspbbersih          = data['head']['v_total_netto'];
        
            
            $('#icustomer').val(icust);
            $('#ecustomername').val(ecustname);
            $('#ecustaddress').val(ecustaddres);
            $('#ndiscc').val(ndisc);
            $('#dmemo').val(dmemo);
            $('#vspbdiscounttotal').val(vspbdiscounttotal);
            $('#vspbbersih').val(vspbbersih);
            $('#vspb').val(vspb);
            // $('#eremarkh').val(e_remark);
            // alert(i_schedule2);
            // $('#eremarkh').val(e_remark);
            // $('#dbonk').val(d_bonk);
            // $('#tujuankeluar').val(tujuan_keluar);
            // $('#pic').val(pic);
            // $('#dept').val(department);

            // var jum =

             $('#jml').val(data['detail'].length);
            // var gudang = $('#istore').val();
            // alert(jum);
            for (let a = 0; a < data['detail'].length; a++) {
                var zz = a+1;
                var produk              = data['detail'][a]['i_product'];
                var namaproduk          = data['detail'][a]['e_product_name'];
                var qtyorder            = data['detail'][a]['n_order'];
                var qtydeliver          = data['detail'][a]['n_delivery'];
                var esatuan             = data['detail'][a]['e_satuan'];
                var isatuan             = data['detail'][a]['i_satuan_code'];
                var x = $('#jml').val();
                // alert (zz);
                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namaproduk+'"></td>';
                // cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="imaterial'+zz+'" name="imaterial'+zz+'" value="'+material+'"></td>';
                // cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="ematerialname'+zz+'" name="ematerialname'+zz+'" value="'+namamateriavar esatuan          = data['detail'][a]['e_satuan'];l+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="esatuan'+zz+'" name="esatuan'+zz+'" value="'+esatuan+'"><input readonly style="width:70px;"  type="hidden" id="isatuan'+zz+'" name="isatuan'+zz+'" value="'+isatuan+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="qty'+zz+'" name="qty'+zz+'" value="'+qtyorder+'"><input readonly style="width:70px;"  type="hidden" id="qtydeliver'+zz+'" name="qtydeliver'+zz+'" value="'+qtydeliver+'"></td>';
                // cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="'+qty+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" type="text" id="npemenuhan'+zz+'" name="npemenuhan'+zz+'" value="0" onkeyup="validasi('+zz+');"></td>';
                // cols += '<td><input style="width:100px;" class="form-control" type="text" id="npemenuhan'+zz+'" name="npemenuhan'+zz+'" value="0" onkeyup="pembandingnilai('+zz+')"></td>';
                // cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nset'+zz+'" name="nset'+zz+'" value="'+set+'"></td>';
                // cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="ngelar'+zz+'" name="ngelar'+zz+'" value="'+gelar+'"></td>';
                // cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+zz+'" name="nsaldo'+zz+'" value="'+panjangkain+'"></td>';
                cols += '<td><input class="form-control" style="width:250px;"  type="text" id="eremark'+a+'" name="eremark'+a+'" value=""></td>';
                // cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" ></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
}

function validasi(id){
    swal(id);
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qtysj   =document.getElementById("qty"+i).value;
            qtyretur=document.getElementById("npemenuhan"+i).value;
            if(parseFloat(qtyretur)>parseFloat(qtysj)){
                swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah SJ');
                document.getElementById("npemenuhan"+i).value=0;
                break;
          }
        }
    }

// function removeBody(){
//     var tbl = document.getElementById("tabledata");   // Get the table
//     tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
//  }








// function get2(id) {
//         $("#tabledata tr:gt(0)").remove();       
//         $("#jml").val(0);
//         $.ajax({
//             type: "post",
//             data: {
//                 'ischedule': id
//             },
//             url: '<#?= base_url($folder.'/cform/getschedule'); ?>',
//             dataType: "json",
//             success: function (data) { 
//                 // $('#dschedule').val(data['data'][0].d_schedule);
//                 // $('#ispbb').val(data['data'][0].i_spbb);
//                 $('#jml').val(data['jmlitem']);
//                 $("#tabledata").attr("hidden", false);
//                 for (let a = 0; a <= data['jmlitem']; a++) {
//                     var no = a+1;
//                     var produk      = data['detail'][a]['i_product'];
//                     var namaproduk  = data['detail'][a]['e_product_name'];
//                     var warna       = data['detail'][a]['e_color_name'];
//                     var color       = data['detail'][a]['i_color'];
//                     var qty         = data['detail'][a]['n_quantity'];
//                     var set         = data['detail'][a]['n_set'];
//                     var gelar       = data['detail'][a]['jumlah_gelar'];
//                     var panjangkain = data['detail'][a]['panjang_kain'];
//                     // var fitemcancel = data['brg'][a]['n_gelar'];
//                     var cols        = "";

//                     var newRow = $("<tr>");

//                         cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input readonly style="width:60px;"  type="text" id="fitemcancel'+a+'" name="fitemcancel'+a+'" value="'+fitemcancel+'"></td>';
//                         cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
//                         cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
//                         cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="warna'+a+'" name="warna'+a+'" value="'+warna+'"><input readonly style="width:70px;"  type="text" id="icolor'+a+'" name="icolor'+a+'" value="'+color+'"></td>';
//                         cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+qty+'"></td>';
//                         // cols += '<td><input style="width:100px;" class="form-control" type="text" id="npemenuhan'+a+'" name="npemenuhan'+a+'" value="0" onkeyup="pembandingnilai('+a+')"></td>';
//                         cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+a+'" name="nsaldo'+a+'" value="'+set+'"></td>';
//                         cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+a+'" name="nsaldo'+a+'" value="'+gelar+'"></td>';
//                         cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+a+'" name="nsaldo'+a+'" value="'+panjangkain+'"></td>';
//                         // cols += '<td><input class="form-control" style="width:250px;"  type="text" id="eremark'+a+'" name="eremark'+a+'" value=""></td>';
//                         cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" ></td>';
//                 newRow.append(cols);
//                 $("#tabledata").append(newRow);
//                 }
              
//             },
//             error: function () {
//                 alert('Error :)');
//             }
//         });
//     }

$(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

$(document).ready(function () {
    $('#ischedule').select2({
    placeholder: 'Pilih Memo',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/schedule'); ?>',
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
    $('#igudang').select2({
    placeholder: 'Pilih Gudang',
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


    function pembandingnilai(a){
    var n_pemenuhan =  $("#npemenuhan"+a).val();
    var n_qty =  $("#nquantity"+a).val();
    //alert(a);
    //var n_pemenuhan   = document.getElementById('npemenuhan'+a).value;
    //var n_qty = document.getElementById('nquantity'+a).value;
    if(parseInt(n_pemenuhan) > parseInt(n_qty)) {
        swal('Jml kirim ( '+n_pemenuhan+' item ) tdk dpt melebihi Order ('+n_qty+' item)');
        document.getElementById('npemenuhan'+a).value   = n_qty;
        document.getElementById('npemenuhan'+a).focus();
        return false;   
        }
    }
</script>