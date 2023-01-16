<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label >Nomor SJ Masuk </label>
                            <input type="text" id="isj" name="isj" class="form-control" readonly maxlength="" value="<?php echo $head->i_sj;?>">
                        </div>
                        
                        <div class="col-sm-4">
                            <label >Tanggal SJ Masuk</label>
                            <input type="text" id="dmasuk" name="dmasuk" class="form-control date" value="<?php echo $head->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <label>Pembuat Dokumen</label>
                            <input type="hidden" id="ikodemaster" name="ikodemaster" class="form-control" value="<?php echo $head->i_kode_master;?>" readonly>
                            <input type="text" id="ekodemaster" name="ekodemaster" class="form-control" value="<?php echo $head->e_sub_bagian;?>" readonly>
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Nomor Dokumen Makloon</label>
                            <input type="text" id="nodok" name="nodok" class="form-control" maxlength="" value="<?php echo $head->sj_makloon;?>" readonly>
                        </div>

                        <div class="col-sm-8">
                            <label>Keterangan</label>
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark;?>" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                    
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Partner Packing</label>
                            <input type="hidden" id="ipartner" name="ipartner" class="form-control" maxlength="" value="<?php echo $head->i_partner;?>">
                            <input type="text" id="epartner" readonly name="epartner" class="form-control" maxlength="" value="<?php echo $head->e_supplier_name;?>">
                        </div>

                        <!-- <div class="col-sm-6">
                            <label>Nomor Refferensi</label>
                            <select name="i_reff" id="i_reff" class="form-control select2">
                                <option value="semua">-- Semua Refferensi --</option>
                            </select>
                        </div> -->
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml" readonly>
                    
                            <!-- <div class="panel-body table-responsive"> -->
                                <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" >
                                    <thead>
                                        <tr>
                                            <th width="3%">No</th>
                                            <th>SJ Keluar</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Warna</th>
                                            <th>Qty Belum Kembali</th>
                                            <th>Qty Masuk</th>
                                            <th>Keterangan</th>
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
    getdetaildata();
    $('.select2').select2();
    showCalendar('.date');

    $('#change').attr("disabled", false);
    $("#change").on("click", function () {
        var kode = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
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

   $("#reject").on("click", function () {
        var kode = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
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


    $("form").submit(function(event) {
        event.preventDefault();
        $('#change').attr("disabled", true);
        $('#reject').attr("disabled", true);
        $('#submit').attr("disabled", true);
    });

});

function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Change Request');
}

function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Reject');
}

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

function validasi(id){
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        qtysisa  =document.getElementById("qtysisa"+i).value;
        qtymasuk =document.getElementById("qtymasuk"+i).value;
        if(parseFloat(qtymasuk)>parseFloat(qtysisa)){
            swal('Jumlah Masuk Tidak Boleh Lebih dari Jumlah Keluar');
            document.getElementById("qtymasuk"+i).value=qtysisa;
            break;
      }
    }
}

function getselisih() {
        var jml = $('#jml').val();
        var qty1 = 0;
        var qty2 = 0;
        var qty = []; 
        var jumlah = 0;
        for (i=1; i<=jml; i++){
            qty1 = parseInt($('#qtymasuk'+i).val());
            qty2 = parseInt($('#qtysisa'+i).val());

            if (qty1 > qty2) {
                qty.push("lebih");
            } else {
                qty.push("ok");
            }
            jumlah = jumlah + qty1;
        }
        var found = qty.find(element => element == "lebih");
             
        if (found == "lebih") {
            swal("Jumlah Barang Masuk Melebihi Jumlah Sisa Barang Keluar");
            return false;
        } else if (jumlah == 0) {
            swal("Barang Masuk Minimal 1");
            return false;
        } else {
            return true;
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

function getdetaildata() {
    var isj = $('#isj').val();
    var ipartner = $('#ipartner').val();
    $.ajax({
        type: "post",
        data: {
            'isj': isj,
            'ipartner': ipartner
        },
        url: '<?= base_url($folder.'/cform/getreffedit'); ?>',
        dataType: "json",
        success: function (data) {
            var jml = Number($('#jml').val());
            var datasekarang = Number(data['detail'].length);
            $('#jml').val(jml+datasekarang);
            // console.log($('#jml').val());
            for (let a = 0; a < data['detail'].length; a++) {
                var counter = jml+(a+1);
                count=$('#tabledata tr').length;                   
                var i_sj         = data['detail'][a]['i_reff'];
                var d_sj         = data['detail'][a]['d_sj'];                    
                var i_color         = data['detail'][a]['i_color'];
                var i_product       = data['detail'][a]['i_product'];
                var e_namabrg       = data['detail'][a]['e_product_basename'];
                var e_color_name    = data['detail'][a]['e_color_name'];
                var n_sisa          = data['detail'][a]['n_sisa'];
                var n_quantity          = data['detail'][a]['n_quantity'];
                var e_remark          = data['detail'][a]['e_remark'];
                
                var gabung = i_sj + " - " + d_sj;
                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:350px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value="'+i_sj+'"></td>'; 
                cols += '<td><input style="width:300px;" type="text" readonly  id="ereff'+ counter + '" type="text" class="form-control" name="ereff[]" value="'+gabung+'"></td>'
                cols += '<td><input style="width:120px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"  value="'+i_product+'"></td>';
                cols += '<td><input style="width:400px;" type="text" readonly  id="ewip'+ counter + '" type="text" class="form-control" name="ewip[]"  value="'+e_namabrg+'"></td>';
                cols += '<td><input style="width:140px;" type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]"  value="'+e_color_name+'"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]"  value="'+i_color+'"/></td>';
                cols += '<td><input style="width:100px;"type="text" id="qtysisa'+ counter + '" readonly class="form-control" name="qtysisa[]" value="'+n_sisa+'"/></td>';
                cols += '<td><input style="width:100px;"type="number" id="qtymasuk'+ counter + '" class="form-control" readonly name="qtymasuk[]" value="'+n_quantity+'" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="validasi('+counter+');" /></td>';
                cols += '<td><input style="width:300px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'" readonly></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);
  
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
}
</script>