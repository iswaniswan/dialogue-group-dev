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
                            <label >Nomor SJ Masuk</label>
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
                    <!-- <div class="form-group">
                        
                    </div> -->
                    
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                       <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                                <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return getselisih();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
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
                </div>
                    <input style ="width:50px"type="hidden" name="jml" id="jml" value="">
                            <!-- <div class="panel-body table-responsive"> -->
                                <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" >
                                    <thead>
                                        <tr>
                                            <th width="3%">No</th>
                                            <th>SJ Keluar</th>
                                            <th>Kode Barang WIP</th>
                                            <th>Nama Barang</th>
                                            <th>Warna</th>
                                            <th>Kode Barang Jadi</th>
                                            <th>Qty Belum Kembali</th>
                                            <th>Qty Masuk</th>
                                            <th>Keterangan</th>
                                            <th>Pilih</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                           <!--  </div> -->
                </form>
            <div>
        </div>
    </div>
</div>

<script>
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
            alert("Jumlah Barang Masuk Melebihi Jumlah Sisa Barang Keluar");
            return false;
        } else if (jumlah == 0) {
            alert("Barang Masuk Minimal 1");
            return false;
        } else {
            return true;
        }
        
}

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

$(document).ready(function () {
    
    // var counter = 0;
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

getdetailsj();
function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    swal('Berhasil Di Send');
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

 $("form").submit(function(event) {
     event.preventDefault();
     // $("input").attr("disabled", true);
     // $("select").attr("disabled", true);
     // $("#submit").attr("disabled", true);
     // $("#addrow").attr("disabled", true);
 });
$(document).ready(function () {
     $("#addrow").on("click", function () {
        var counter = $('#jml').val();
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");        
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value=""></td>';
        cols += '<td><select style="width:350px;" type="text" id="ereff'+ counter + '" class="form-control" name="ereff[]" onchange="getreff('+ counter + ');"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]" value=""></td>';
        cols += '<td><input style="width:400px;" type="text" readonly  id="ewip'+ counter + '" type="text" class="form-control" name="ewip[]" value=""></td>';
        cols += '<td><input style="width:140px;" type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" /></td>';

        cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
        cols += '<td><input style="width:100px;"type="text" id="qtysisa'+ counter + '" readonly class="form-control" name="qtysisa[]" value=""/></td>';
        cols += '<td><input style="width:100px;"type="text" id="qtymasuk'+ counter + '" class="form-control" name="qtymasuk[]" value="0" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="cekval(this.value); reformat(this);validasi('+counter+'); "/></td>';        
        cols += '<td><input style="width:400px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ereff'+ counter).select2({
            placeholder: 'Pilih WIP',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {          
              url: '<?= base_url($folder.'/cform/datareff/'); ?>',
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

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
    });

    

});

    function getreff(id){
            var ereff = $('#ereff'+id).val();

            // var fields = ewip.split('|');
            // var iwip = fields[0];
            // var icolor = fields[1];
            $.ajax({
            type: "post",
            data: {
                'ereff': ereff
            },
            url: '<?= base_url($folder.'/cform/getreff'); ?>',
            dataType: "json",
            success: function (data) {
                var ireff = data['head']['i_sj'];
                //swal(i_product+ e_color_name+ i_color);
                $('#ireff'+id).val(ireff);
                
                ada=false;
                var a = $('#ireff'+id).val();
                var jml = $('#jml').val();
                for(i=1;i<=jml;i++){
                    if((a == $('#ireff'+i).val()) && (i!=id)){
                        swal("Nomor Refferensi SJ : "+a+" sudah ada !!!!!");
                        ada=true;
                        break;
                    }else{
                        ada=false;     
                    }
                }

                if(!ada){
                    $('#ireff'+id).val(ireff);
                    $('#ereff'+id).attr("disabled", true);
                    var counter = $('#jml').val();
                    var jmldetail = data['detail'].length;
                    $('#jml').val((jml-1)+jmldetail);
                    for (let a = 0; a < data['detail'].length; a++) {
                        var zz = a+1;
                        var i_sj          = data['detail'][a]['i_sj'];                    
                        var i_wip           = data['detail'][a]['i_wip'];
                        var i_color         = data['detail'][a]['i_color'];
                        var i_product       = data['detail'][a]['i_product'];
                        var e_namabrg       = data['detail'][a]['e_namabrg'];
                        var e_color_name    = data['detail'][a]['e_color_name'];
                        var n_sisa          = data['detail'][a]['n_sisa'];

                        if (zz==1) {
                            $('#iwip'+id).val(i_wip);
                            $('#ewip'+id).val(e_namabrg);
                            $('#icolor'+id).val(i_color);
                            $('#ecolor'+id).val(e_color_name);
                            $('#iproduct'+id).val(i_product);
                            $('#qtysisa'+id).val(n_sisa);
                            $('#qtymasuk'+id).val(n_sisa);
                        } else {
                            var cols        = "";
                            var newRow = $("<tr>");        
                            cols += '<td style="text-align: center;" colspan="2"><spanx id="snum'+counter+'"></spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value="'+i_sj+'"></td>';
                            cols += '<td><input style="width:100px;" type="text" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]"  value="'+i_wip+'"></td>';
                            cols += '<td><input style="width:400px;" type="text" readonly  id="ewip'+ counter + '" type="text" class="form-control" name="ewip[]"  value="'+e_namabrg+'"></td>';
                            cols += '<td><input style="width:140px;" type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]"  value="'+e_color_name+'"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]"  value="'+i_color+'"/></td>';

                            cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"  value="'+i_product+'"></td>';
                            cols += '<td><input style="width:100px;"type="text" id="qtysisa'+ counter + '" readonly class="form-control" name="qtysisa[]" value="'+n_sisa+'"/></td>';
                            cols += '<td><input style="width:100px;"type="text" readonly id="qtymasuk'+ counter + '" class="form-control" name="qtymasuk[]" value="'+n_sisa+'" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="cekval(this.value); reformat(this);validasi('+counter+');" /></td>';
                            cols += '<td><input style="width:400px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" readonly></td>';
                            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
                            newRow.append(cols);
                            $("#tabledata").append(newRow);
                        }
                        counter++;
          
                    }
                }else{
                    $('#ireff'+id).val('');
                    $('#ereff'+id).val('');
                    $('#ireff'+id).html('');
                    $('#ereff'+id).html('');
                }

                
            },
            error: function () {
                alert('Error :)');
            }
        });
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

    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
    });

    function getdetailsj() {
        removeBody();
        var isj = $('#isj').val();
        var gudang = $('#ikodemaster').val();
        //alert(isj);
        $.ajax({
            type: "post",
            data: {
                'isj': isj,
                'gudang': gudang
            },
            url: '<?= base_url($folder.'/cform/getdetailsj'); ?>',
            dataType: "json",
            success: function (data) {
                $('#jml').val(data['detail'].length);
                var lastreff = '';
                for (let a = 0; a < data['detail'].length; a++) {
                    var counter = a+1;
                    count=$('#tabledata tr').length;                   
                    var i_reff         = data['detail'][a]['i_reff'];
                    var e_unitjahit_name         = data['detail'][a]['e_unitjahit_name'];                    
                    var i_wip           = data['detail'][a]['i_wip'];
                    var i_color         = data['detail'][a]['i_color'];
                    var i_product       = data['detail'][a]['i_product'];
                    var e_namabrg       = data['detail'][a]['e_namabrg'];
                    var e_color_name    = data['detail'][a]['e_color_name'];
                    var n_sisa          = data['detail'][a]['n_sisa'];
                    var n_quantity          = data['detail'][a]['n_quantity'];
                    
                    var gabung = i_reff + " - " + e_unitjahit_name;
                    var cols        = "";
                    var newRow = $("<tr>");

                    if (lastreff == i_reff) {
                        cols += '<td style="text-align: center;" colspan="2"><spanx id="snum'+counter+'"></spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value="'+i_reff+'"></td>';
                    } else {
                        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:350px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value="'+i_reff+'"></td>';
                        cols += '<td><input style="width:350px;" type="text" readonly  id="ereff'+ counter + '" type="text" class="form-control" name="ereff[]" value="'+gabung+'"></td>'
                    }
                    
                            cols += '<td><input style="width:100px;" type="text" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]"  value="'+i_wip+'"></td>';
                            cols += '<td><input style="width:400px;" type="text" readonly  id="ewip'+ counter + '" type="text" class="form-control" name="ewip[]"  value="'+e_namabrg+'"></td>';
                            cols += '<td><input style="width:140px;" type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]"  value="'+e_color_name+'"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]"  value="'+i_color+'"/></td>';

                            cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"  value="'+i_product+'"></td>';
                            cols += '<td><input style="width:100px;"type="text" id="qtysisa'+ counter + '" readonly class="form-control" name="qtysisa[]" value="'+n_sisa+'"/></td>';
                            cols += '<td><input style="width:100px;"type="text" id="qtymasuk'+ counter + '" class="form-control" name="qtymasuk[]" value="'+n_quantity+'" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="cekval(this.value); reformat(this);validasi('+counter+');" readonly /></td>';
                            cols += '<td><input style="width:400px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" readonly></td>';
                            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

                    lastreff = i_reff;
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

    function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
    }
</script>