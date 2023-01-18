<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
        <?
	        include ("php/fungsi.php");
        ?><h3>&nbsp;&nbsp;&nbsp;<? echo 'Periode : '.substr($dfrom,0,2).' '.mbulan(substr($dfrom,3,2)).' '.substr($dfrom,6,4).' s/d '.substr($dto,0,2).' '.mbulan(substr($dto,3,2)).' '.substr($dto,6,4); ?></h3>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%" >
        <?
		if($total){
            ?>
	    <th>Supplier</th>
		<th>Nama Supplier</th>
        <?                         
            if($dfrom!=''){
	        	  $tmp=explode("-",$dfrom);
	        	  $blasal=$tmp[1];
              settype($bl,'integer');
              }
            $interval = $interfall->inter;
            $bl=$blasal;
            for($i=0;$i<=$interval;$i++){
              switch ($bl){
              case '1' :
                echo '<th>Jan</th>';
                break;
              case '2' :
                echo '<th>Feb</th>';
                break;
              case '3' :
                echo '<th>Mar</th>';
                break;
              case '4' :
                echo '<th>Apr</th>';
                break;
              case '5' :
                echo '<th>Mei</th>';
                break;
              case '6' :
                echo '<th>Jun</th>';
                break;
              case '7' :
                echo '<th>Jul</th>';
                break;
              case '8' :
                echo '<th>Agu</th>';
                break;
              case '9' :
                echo '<th>Sep</th>';
                break;
              case '10' :
                echo '<th>Okt</th>';
                break;
              case '11' :
                echo '<th>Nov</th>';
                break;
              case '12' :
                echo '<th>Des</th>';
                break;
              }
              $bl++;
              if($bl==13)$bl=1;
            }
        ?>
        <tbody>
        <?
		foreach($total as $row){
            $rata=0;
  	        echo "<tr>
                    <td>$row->supplier</td>
                    <td>$row->esupplier</td>";
            $bl=$blasal;
            $interval = $interfall->inter;
            for($i=0;$i<=$interval;$i++){
              switch ($bl){
              case '1' :
                $rata=$rata+$row->jan;
                echo '<th align=right>'.number_format($row->jan).'</th>';
                break;
              case '2' :
                $rata=$rata+$row->feb;
                echo '<th align=right>'.number_format($row->feb).'</th>';
                break;
              case '3' :
                $rata=$rata+$row->mar;
                echo '<th align=right>'.number_format($row->mar).'</th>';
                break;
              case '4' :
                $rata=$rata+$row->apr;
                echo '<th align=right>'.number_format($row->apr).'</th>';
                break;
              case '5' :
                $rata=$rata+$row->may;
                echo '<th align=right>'.number_format($row->may).'</th>';
                break;
              case '6' :
                $rata=$rata+$row->jun;
                echo '<th align=right>'.number_format($row->jun).'</th>';
                break;
              case '7' :
                $rata=$rata+$row->jul;
                echo '<th align=right>'.number_format($row->jul).'</th>';
                break;
              case '8' :
                $rata=$rata+$row->aug;
                echo '<th align=right>'.number_format($row->aug).'</th>';
                break;
              case '9' :
                $rata=$rata+$row->sep;
                echo '<th align=right>'.number_format($row->sep).'</th>';
                break;
              case '10' :
                $rata=$rata+$row->oct;
                echo '<th align=right>'.number_format($row->oct).'</th>';
                break;
              case '11' :
                $rata=$rata+$row->nov;
                echo '<th align=right>'.number_format($row->nov).'</th>';
                break;
              case '12' :
                $rata=$rata+$row->des;
                echo '<th align=right>'.number_format($row->des).'</th>';
                break;
              }
              $bl++;
              if($bl==13)$bl=1;
            }
            echo "</tr>";
            }
        }
        ?>
            </tbody>
        </table>
        </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // getdetaildata();
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