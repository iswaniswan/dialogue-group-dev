<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label>Pembuat Dokumen</label>
                            <input type="hidden" id="idept" name="idept" class="form-control" value="<?php echo $head->i_kode_master;?>" readonly>
                            <input type="text" id="ekodemaster" name="ekodemaster" class="form-control" value="<?php echo $head->e_departement_name;?>" readonly>
                            
                        </div>
                        <div class="col-sm-6">
                            <label >Nomor DO</label>
                            <input type="text" id="ido" name="ido" class="form-control" readonly maxlength="" value="<?php echo $head->i_do;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-sm-12">
                            <label>Keterangan</label>
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" ><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                            <!-- <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> -->
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                    <div class="col-sm-6">
                            <label >Tanggal DO</label>
                            <input type="text" id="dmasuk" name="dmasuk" class="form-control date" value="<?php echo $head->d_do;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <label>Nomor OP</label>
                            <input type="text" id="ireff" name="ireff" class="form-control" maxlength="" value="<?php echo $head->i_reff;?>"readonly>
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml" readonly>
                    
                            <!-- <div class="panel-body table-responsive"> -->
                                <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%" >
                                    <thead>
                                        <tr>
                                            <th width="3%">No</th>
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
                                    <?$i = 0;
                                    foreach ($data2 as $row) {
                                    $i++;?>
                                    </tr>
                                        <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:160px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>"  readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:400px"type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" class="form-control" readonly >
                                        </td>                   
                                        <td class="col-sm-1">
                                            <input style ="width:80px" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" class="form-control" readonly >
                                            <input style ="width:80px" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" class="form-control" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:80px" class="form-control" type="text" id="qtyorder<?=$i;?>" name="qtyorder<?=$i;?>"value="<?= $row->n_order; ?>" >   
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:80px" class="form-control" type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_deliver; ?>" >   
                                            <input style ="width:80px" class="form-control" type="hidden" id="qtyprev<?=$i;?>" name="qtyprev<?=$i;?>"value="<?= $row->n_deliver; ?>" >
                                            <input style ="width:80px" class="form-control" type="hidden" id="vprice<?=$i;?>" name="vprice<?=$i;?>"value="<?= $row->v_price; ?>" >   
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:300px" class="form-control" type="text" id="eremarkh<?=$i;?>" name="eremarkh<?=$i;?>"value="<?= $row->e_remark; ?>" >
                                        </td>
                                        <td>
                                            <input  type="checkbox" id="cek<?=$i;?>" name="cek<?=$i;?>" >
                                            <!-- <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button> -->
                                        </td>
                                        </tr>
                                    <?}?>
                                    <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> 
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

    $("#send").on("click", function () {
        var kode = $("#ido").val();
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

    $('#i_reff').select2({
        placeholder: 'Pilih Reff',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getpartnerreffedit'); ?>',
            dataType: 'json',
            delay: 250,          
            data: function (params) {
                var query = {
                    q: params.term,
                    ipartner : $('#ipartner').val(),
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    })

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
                            cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+counter+'" name="chk[]"></td>';
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

function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    swal('Berhasil Di Send');
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
                cols += '<td><input style="width:100px;"type="number" id="qtymasuk'+ counter + '" class="form-control" name="qtymasuk[]" value="'+n_quantity+'" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="validasi('+counter+');" /></td>';
                cols += '<td><input style="width:300px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'"></td>';
                cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+counter+'" name="chk[]" checked></td>';
                cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);
  
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
}
 $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
    });
</script>