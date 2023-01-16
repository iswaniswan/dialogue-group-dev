<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                <!-- <div id="pesan"></div>                       -->
                <div class="form-group row">
                        <label class="col-md-12">Jenis Keluar</label>
                        <div class="col-sm-4">
                            <select name="jnskeluar" id="jnskeluar" class="form-control select2">
                                <option value=>----Pilih Jenis Kleuar----</option>
                                <option value="0">Keluar Baru</option>
		  				    	<option value="1">Pendingan</option>
                            </select>
                        </div>
                </div>  
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-8">
                        <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-spinner"></i>&nbsp;&nbsp;Proses</button>
                    </div>               
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
    var ischedule = $('#ischedule').val();
    $.ajax({
        type: "post",
        data: {
            'ischedule': id
        },
        url: '<?= base_url($folder.'/cform/getschedule'); ?>',
        dataType: "json",
        success: function (data) {
            // var i_memo = data['head']['i_memo'];
            var e_remark = data['head']['e_remark'];
            var i_schedule = data['head']['i_schedule'];
            var i_schedule2 = data['detail'][0]['i_schedule'];
           
          
            // var tujuan_keluar = data['head']['tujuan_keluar'];
            // var pic = data['head']['pic'];
            // var department = data['head']['department'];
            
            $('#isched').val(i_schedule);
            $('#eremarkh').val(e_remark);
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
                // var i_material    = data['detail'][a]['i_material'];
                // var e_material    = data['detail'][a]['e_material_name'];
                // var n_qty       = data['detail'][a]['n_qty'];
                // var i_satuan       = data['detail'][a]['i_satuan'];
                // var e_satuan       = data['detail'][a]['e_satuan'];
                // var namabarang  = i_material + ' - ' + e_material;

                var produk      = data['detail'][a]['i_product'];
                var namaproduk  = data['detail'][a]['e_product_name'];
                var material    = data['detail'][a]['i_material'];
                var namamaterial= data['detail'][a]['e_material_name'];
                var warna       = data['detail'][a]['e_color_name'];
                var color       = data['detail'][a]['i_color'];
                var qty         = data['detail'][a]['n_quantity'];
                var set         = data['detail'][a]['n_set'];
                var gelar       = data['detail'][a]['v_jmlgelar'];
                var panjangkain = data['detail'][a]['v_pjngkain'];
                var x = $('#jml').val();

                // alert(produk);

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namaproduk+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="imaterial'+zz+'" name="imaterial'+zz+'" value="'+material+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="ematerialname'+zz+'" name="ematerialname'+zz+'" value="'+namamaterial+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="warna'+zz+'" name="warna'+zz+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+zz+'" name="icolor'+zz+'" value="'+color+'"></td>';
                cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="'+qty+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" type="text" id="npemenuhan'+zz+'" name="npemenuhan'+zz+'" value="0" onkeyup="pembandingnilai('+zz+')"></td>';
                cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nset'+zz+'" name="nset'+zz+'" value="'+set+'"></td>';
                cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="ngelar'+zz+'" name="ngelar'+zz+'" value="'+gelar+'"></td>';
                cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+zz+'" name="nsaldo'+zz+'" value="'+panjangkain+'"></td>';
                // cols += '<td><input class="form-control" style="width:250px;"  type="text" id="eremark'+a+'" name="eremark'+a+'" value=""></td>';
                // cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" ></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);
                
                $('#i_2material'+zz).select2({
                    placeholder: 'Pilih Material',
                    allowClear: true,
                    ajax: {
                        url: '<?= base_url($folder);?>/cform/datamaterial/',
                        dataType: 'json',
                        delay: 250,
                      // processResults: function (data) {
                      //   return {
                      //     results: data
                      //   };
                      // },
                      // cache: true
                        data: function (params) {
                            var query   = {
                                q       : params.term
                            }
                            return query;
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: false
                    }
                });
  
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
}

// function removeBody(){
//     var tbl = document.getElementById("tabledata");   // Get the table
//     tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
//  }








function get2(id) {
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'ischedule': id
            },
            url: '<?= base_url($folder.'/cform/getschedule'); ?>',
            dataType: "json",
            success: function (data) { 
                // $('#dschedule').val(data['data'][0].d_schedule);
                // $('#ispbb').val(data['data'][0].i_spbb);
                $('#jml').val(data['jmlitem']);
                $("#tabledata").attr("hidden", false);
                for (let a = 0; a <= data['jmlitem']; a++) {
                    var no = a+1;
                    var produk      = data['detail'][a]['i_product'];
                    var namaproduk  = data['detail'][a]['e_product_name'];
                    var warna       = data['detail'][a]['e_color_name'];
                    var color       = data['detail'][a]['i_color'];
                    var qty         = data['detail'][a]['n_quantity'];
                    var set         = data['detail'][a]['n_set'];
                    var gelar       = data['detail'][a]['jumlah_gelar'];
                    var panjangkain = data['detail'][a]['panjang_kain'];
                    // var fitemcancel = data['brg'][a]['n_gelar'];
                    var cols        = "";

                    var newRow = $("<tr>");

                        cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input readonly style="width:60px;"  type="text" id="fitemcancel'+a+'" name="fitemcancel'+a+'" value="'+fitemcancel+'"></td>';
                        cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
                        cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
                        cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="warna'+a+'" name="warna'+a+'" value="'+warna+'"><input readonly style="width:70px;"  type="text" id="icolor'+a+'" name="icolor'+a+'" value="'+color+'"></td>';
                        cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+qty+'"></td>';
                        // cols += '<td><input style="width:100px;" class="form-control" type="text" id="npemenuhan'+a+'" name="npemenuhan'+a+'" value="0" onkeyup="pembandingnilai('+a+')"></td>';
                        cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+a+'" name="nsaldo'+a+'" value="'+set+'"></td>';
                        cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+a+'" name="nsaldo'+a+'" value="'+gelar+'"></td>';
                        cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nsaldo'+a+'" name="nsaldo'+a+'" value="'+panjangkain+'"></td>';
                        // cols += '<td><input class="form-control" style="width:250px;"  type="text" id="eremark'+a+'" name="eremark'+a+'" value=""></td>';
                        cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" ></td>';
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
    $('#ischedule').select2({
    placeholder: 'Pilih Schedule',
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