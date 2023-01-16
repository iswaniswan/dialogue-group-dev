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
                        <label class="col-md-8">Bagian Pembuat</label>
                        <label class="col-md-4">Tanggal SJ Masuk</label>
                        <div class="col-sm-8">
                            <select name="dept" id="dept" class="form-control select2">
                                <?php foreach ($area as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_departement;?>">
                                    <?= $ikodemaster->i_departement." - ".$ikodemaster->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dmasuk" name="dmasuk" class="form-control date"  required="" readonly value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i
                                    class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <!-- <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm">  -->
                                    <!-- disabled="" -->
                                    <!-- <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> -->
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Customer</label><label class="col-md-6">Nomor OP</label>
                        <div class="col-sm-6">
                            <select name="ipartner" id="ipartner" class="form-control select2" onchange="getpartnerreff(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="i_reff" id="i_reff" class="form-control select2" onchange="get();">
                            </select>
                        </div>
                    </div>

              
                </div>
                    <input type="hidden" name="jml" id="jml" readonly>
                    
                            <!-- <div class="panel-body table-responsive"> -->
                                <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" >
                                    <thead>
                                        <tr>
                                            <th width="3%">No</th>
                                            <!-- <th>SJ Keluar</th> -->
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Warna</th>
                                            <th>Qty OP</th>
                                            <th>Qty SJ</th>
                                            <th>Keterangan</th>
                                            <th>Pilih</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                           <!--  </div> -->
                            </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#addrow").on("click", function () {
        var ireff = $("#i_reff").val();
        if (ireff == null || ireff == "kosong") {
            swal("Refferensi Harus Di Pilih");
        } else {
            
            var jml = Number($('#jml').val());
            var qty = []; 
            for (i=1; i<=jml; i++){
                ireffdata = $('#ireff'+i).val();

                if (ireff == ireffdata) {
                    qty.push("lebih");
                } else {
                    qty.push("ok");
                }
            }
            var found = qty.find(element => element == "lebih");
            if (ireff!="semua" && found == "lebih") {
                swal("Nomor SJ Sudah Ada");
            } else {
                if (ireff=="semua") {
                    $('#jml').val("0");
                    removeBody();
                }
                var isj = ireff;
                var ipartner = $('#ipartner').val();
                $.ajax({
                    type: "post",
                    data: {
                        'isj': isj,
                        'ipartner': ipartner
                    },
                    url: '<?= base_url($folder.'/cform/getreff'); ?>',
                    dataType: "json",
                    success: function (data) {
                        var jml = Number($('#jml').val());
                        var datasekarang = Number(data['detail'].length);
                        $('#jml').val(jml+datasekarang);
                        // console.log($('#jml').val());
                        for (let a = 0; a < data['detail'].length; a++) {
                            var counter = jml+(a+1);
                            count=$('#tabledata tr').length;                   
                            var i_sj         = data['detail'][a]['i_sj'];
                            var d_sj         = data['detail'][a]['d_sj'];                    
                            var i_color         = data['detail'][a]['i_color'];
                            var i_product       = data['detail'][a]['i_product'];
                            var e_namabrg       = data['detail'][a]['e_product_basename'];
                            var e_color_name    = data['detail'][a]['e_color_name'];
                            var n_sisa          = data['detail'][a]['n_sisa'];
                            
                            var gabung = i_sj + " - " + d_sj;
                            var cols        = "";
                            var newRow = $("<tr>");
                            cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:350px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value="'+i_sj+'"></td>'; 
                            cols += '<td><input style="width:300px;" type="text" readonly  id="ereff'+ counter + '" type="text" class="form-control" name="ereff[]" value="'+gabung+'"></td>'
                            cols += '<td><input style="width:120px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"  value="'+i_product+'"></td>';
                            cols += '<td><input style="width:400px;" type="text" readonly  id="ewip'+ counter + '" type="text" class="form-control" name="ewip[]"  value="'+e_namabrg+'"></td>';
                            cols += '<td><input style="width:140px;" type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]"  value="'+e_color_name+'"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]"  value="'+i_color+'"/></td>';
                            cols += '<td><input style="width:100px;"type="text" id="qtysisa'+ counter + '" readonly class="form-control" name="qtysisa[]" value="'+n_sisa+'"/></td>';
                            cols += '<td><input style="width:100px;"type="number" id="qtymasuk'+ counter + '" class="form-control" name="qtymasuk[]" value="0" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="validasi('+counter+');" /></td>';
                            cols += '<td><input style="width:300px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"></td>';
                            // cols += '<td><input type="checkbox" id="chk'+counter+'" name="chk[]"></td>';
                            cols += '<td><input type="checkbox" name="cek'+counter+'" value="chk" id="chk'+counter+'" ></td>';
                            newRow.append(cols);
                            $("#tabledata").append(newRow);
              
                        }
                    },
                    error: function () {
                        swal('Error :)');
                    }
                });
            }    
        }
    });
});

$(document).ready(function () {
    $('#ipartner').select2({
        placeholder: 'Pilih Partner',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/partner'); ?>',
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

    $("#send").attr("disabled", true);
    $("#send").on("click", function () {
        var kode = $("#kode").val();
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

function getpartnerreff(ipartner) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getpartnerreff');?>",
        data:"ipartner="+ipartner,
        dataType: 'json',
        success: function(data){
            $("#i_reff").html(data.kop);
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

function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}



function get() {
    // removeBody();
    var ireff = $('#i_reff').val();
    // swal(ireff);
    $.ajax({
        type: "post",
        data: {
            'ireff': ireff
        },
        url: '<?= base_url($folder.'/cform/getop'); ?>',
        dataType: "json",
        success: function (data) {
            console.log(data);
            // var i_memo = data['head']['i_memo'];
            var e_remark = data['head']['e_remark'];
            var i_schedule = data['head']['i_schedule'];
            // var i_schedule2 = data['detail'][0]['i_schedule'];
           
          
            // var tujuan_keluar = data['head']['tujuan_keluar'];
            // var pic = data['head']['pic'];
            // var department = data['head']['department'];
            
            $('#isched').val(i_schedule);
            $('#eremarkh').val(e_remark);
             $('#jml').val(data['detail'].length);
            for (let a = 0; a < data['detail'].length; a++) {
                var zz = a+1;
        
                var produk      = data['detail'][a]['i_product'];
                var namaproduk  = data['detail'][a]['e_product_name'];
                var warna       = data['detail'][a]['e_color_name'];
                var color       = data['detail'][a]['i_color'];
                var qty         = data['detail'][a]['n_count'];
                var vprice      = data['detail'][a]['v_price'];
                var qtyic       = data['detail'][a]['n_quantity_stock'];
                if(Number(qty)<Number(qtyic)){
                    qtyic=qty;
                }
                var x = $('#jml').val();

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                cols += '<td><input style="width:300px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namaproduk+'"></td>';
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="warna'+zz+'" name="warna'+zz+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+zz+'" name="icolor'+zz+'" value="'+color+'"><input readonly style="width:70px;"  type="hidden" id="vprice'+zz+'" name="vprice'+zz+'" value="'+vprice+'"></td>';
                cols += '<td><input readonly class="form-control" style="width:100px;"  type="text" id="nquantity'+zz+'" name="nquantity'+zz+'" value="'+qty+'"></td>';
                cols += '<td><input style="width:100px;" class="form-control" type="text" id="npemenuhan'+zz+'" name="npemenuhan'+zz+'" value="'+qtyic+'" onkeyup="validasi(this.value)"></td>';
                cols += '<td><input style="width:100px;" class="form-control" style="width:250px;"  type="text" id="eremarkh'+zz+'" name="eremarkh'+zz+'" value=""></td>';
                cols += '<td><input type="checkbox" name="cek'+zz+'"  id="chk'+zz+'" ></td>';
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

function validasi(id){
    // alert(id);
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qtyop  =document.getElementById("nquantity"+i).value;
        qtysj  =document.getElementById("npemenuhan"+i).value;
        if(parseFloat(qtyop)<parseFloat(qtysj)){
            swal('Jumlah Masuk Tidak Boleh Lebih dari Jumlah Keluar');
            document.getElementById("npemenuhan"+i).value="0";
            break;
      }
    }
}


function cek() {
    var i_reff = $('#i_reff').val();
    var dmasuk = $('#dmasuk').val();

    if (i_reff == "" && dmasuk == "") {
        swal("Data Header Belum Lengkap");
        return false;
    } else {
        var jml = Number($('#jml').val());
        var qty = [];
        var jumlah = 0;
        for (i=1; i<=jml; i++){
            check = $('#chk'+i).val();
            qty1 = parseInt($('#qtymasuk'+i).val());

            if ($('#chk1').is(":checked")) {
                qty.push("lebih");
            } else {
                qty.push("kosong");
            }
            jumlah = jumlah + qty1;

        }
        var found = qty.find(element => element == "kosong");
             
        if (found == "kosong") {
            alert("Harus Ada yang di ceklis");
            return false;
        } else if (jumlah == 0) {
            alert("Barang Masuk Minimal 1");
            return false;
        } else {
            return true;
        }
    }
}

$("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("disabled", false);
});


</script>