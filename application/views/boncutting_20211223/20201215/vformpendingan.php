<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/pendingan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-9">Nomor Bon K</label><label class="col-md-3">Tanggal Bon K</label>
                        <div class="col-sm-9">
                            <select name="ibonk" id="ibonk" class="form-control select2" onchange="getmaterial(this.value);" readonly></select> 
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dbonk" name="dbonk" class="form-control date" required value="" readonly >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" ><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>                    
                        </div>
                    </div>
                </div>  
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark" name="eremark" class="form-control"  value="">
                        </div>
                    </div>
                </div>
                   
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="10%">Kode Barang</th>
                                <th width="15%">Nama Barang</th>
                                <th>Kode Material</th>
                                <th>Nama Material</th>
                                <th>Warna</th>
                                <th width="8%">Qty Set</th>
                                <th width="8%">Qty Deliver</th>
                                <th width="8%">Sisa</th>
                                <th>Action</th>
                            <tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>    
                <input type="text" name="jml" id="jml" value="0"> 
            </div>
        </form>
        </div>
    </div>
</div>
<script>
$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});


function getmaterial(id) {
    // removeBody();
    var ibonk = $('#ibonk').val();
    // swal(id); 
    $.ajax({
        type: "post",
        data: {
            'ibonk': id,
        },
        url: '<?= base_url($folder.'/cform/getmateriallpendingan'); ?>',
        dataType: "json",
        success: function (data) {
            console.log(data);
            // return false;
            
            var dbonk = data['head']['d_bonk'];
            
            $('#dbonk').val(dbonk);
            var jml = $('#jml').val(data['detail'].length);
            // swal(jml);

            for (let a = 0; a < data['detail'].length; a++) {
                
                var zz = a+1;
                // swal(zz);
                var produk              = data['detail'][a]['i_product'];
                // swal(produk);
                var eproduk             = data['detail'][a]['e_product_name'];
                var imaterial           = data['detail'][a]['i_material'];
                var ematerial           = data['detail'][a]['e_material_name'];
                var warna               = data['detail'][a]['e_color_name'];
                var color               = data['detail'][a]['i_color_wip'];
                var qtyproduct          = data['detail'][a]['n_quantity_product'];
                var qtymaterial         = data['detail'][a]['n_quantity_material'];
                // var color       = data['detail'][a]['i_color'];
                var x = $('#jml').val();

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="eproduct'+zz+'" name="eproduct'+zz+'" value="'+eproduk+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="imaterial'+zz+'" name="imaterial'+zz+'" value="'+imaterial+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="ematerialname'+zz+'" name="ematerialname'+zz+'" value="'+ematerial+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="warna'+zz+'" name="warna'+zz+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+zz+'" name="icolor'+zz+'" value="'+color+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="qtyproduct'+zz+'" name="qtyproduct'+zz+'" value="'+qtyproduct+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="qtymaterial'+zz+'" name="qtymaterial'+zz+'" value="'+qtymaterial+'"></td>';
                cols += '<td><input  class="form-control" style="width:100px;"  type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="0" onkeyup="validasi('+zz+');"></td>';
                cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" ></td>';
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








function getmaterial2() {
    
    var ibonk = $('#ibonk').val();
    
    $.ajax({
        type: "post",
        data: {
            'ibonkeluar': ibonk 
        },
        url: '<?= base_url($folder.'/cform/getmateriallpendingan'); ?>',
        dataType: "json",

        





        success: function (data) {  
            // $('#jml').val(data['jmlitem']);
            // $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['jmlitem']; a++) {
                alert(ibonk);
                var no = a+1;
                var ibonk       = data['detail'][a]['i_bonk'];
                var iproduct    = data['detail'][a]['i_product'];
                var eproductname= data['detail'][a]['e_product_name'];
                var imaterial   = data['detail'][a]['i_material'];
                var ematerial   = data['detail'][a]['e_material_name'];
                var icolor      = data['detail'][a]['i_color_wip'];
                var ecolor      = data['detail'][a]['e_color_name'];
                var qtyproduct  = data['detail'][a]['n_quantity_product'];
                var qtydeliver  = data['detail'][a]['n_quantity_material'];
               
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input readonly class="form-control" type="hidden" id="ipp'+a+'" name="ipp'+a+'" value="'+ipp+'"><input style="width:100px;" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+isupplier+'"></td>';                
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+iproduct+'"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+eproductname+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+ematerial+'"></td>';
                cols += '<td><input style="width:40px;"  type="hidden" id="icolor'+a+'" name="icolor'+a+'" value="'+icolor+'"><input style="width:90px;" class="form-control" type="text" id="ecolor'+a+'" readonly name="ecolor'+a+'" value="'+ecolor+'"></td>';
                cols += '<td><input style="width:80px;" readonly class="form-control" type="text" id="qtyyy'+a+'" name="qtyyy'+a+'" value="'+qtyproduct+'"></td>';
                cols += '<td><input style="width:80px;" readonly class="form-control" type="text" id="qtyyy'+a+'" name="qtyyy'+a+'" value="'+qtydeliver+'"></td>';
                cols += '<td><input style="width:80px;" readonly class="form-control" type="text" id="qtyyy'+a+'" name="qtyyy'+a+'" value="0"></td>';
                cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'"></td';
           //cols += '<td><input style="width:100px;" readonly type="hidden" id="igudang'+a+'" name="igudang'+a+'" value="'+igudang+'"><input style="width:200px;" readonly type="text" id="egudang'+a+'" name="egudang'+a+'" class="form-control" value="'+egudang+'"></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }

        },
        error: function () {
            alert('Error :)');
        }
    });
}

$(document).ready(function () {
    $(".select2").select2();
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
    $('#ibonk').select2({
    placeholder: 'Pilih Bonk',
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

function validasi(id){
        // alert(id);
        jml=document.getElementById("jml").value;
        
        for(i=1;i<=jml;i++){
            // alert(jml);
            qtyproduct      =document.getElementById("qtyproduct"+i).value;
            qtymaterial     =document.getElementById("qtymaterial"+i).value;
            qty = qtyproduct - qtymaterial;
            // alert(qty);
            qtyretur        =document.getElementById("nquantity"+i).value;
            // alert(qtyretur);
            if(parseFloat(qtyretur)>parseFloat(qty)){
                swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah SJ');
                document.getElementById("nquantity"+i).value=0;
                break;
          }
        }
    }

function gett(dfrom) {
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var igudang = $('#igudang').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getipp');?>",
        data:{
            'dfrom': dfrom,
            'dto':dto,
            'igudang':igudang,
        },
        dataType: 'json',
        success: function(data){
            $("#ipp").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
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

function getsup(ipp) {
    var ipp = $('#ipp').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getsup');?>",
        data:{
            'ipp': ipp,
        },
        dataType: 'json',
        success: function(data){
            $("#isupplier").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
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

// function validasi(){
//     var s=0;
//     var i = document.getElementById("jml").value;
//     var maxpil = 1;
//     var jml = $("input[type=checkbox]:checked").length;
//     var textinputs = document.querySelectorAll('input[type=checkbox]'); 
//     var empty = [].filter.call( textinputs, function( el ) {
//        return !el.checked
//     });
//     if (document.getElementById('dfrom').value=='') {
//         swal("Maaf Tolong Pilih Date From");
//         return false;
//     } else if(document.getElementById('dto').value==''){
//         swal("Maaf Tolong Pilih Date to!");
//         return false;
//     }else if(document.getElementById('isupplier').value==''){
//         swal("Maaf Tolong Pilih Supplier!");
//         return false;
//     }if (textinputs.length == empty.length) {
//         swal("Barang Belum dipilih !!");
//         return false;
//     // }else if (jml > maxpil) {
//     //     swal('Maksimal Pilih 1 PP');
//     //     return false;
//     }else{
//         return true;
//     }
// }    
</script>