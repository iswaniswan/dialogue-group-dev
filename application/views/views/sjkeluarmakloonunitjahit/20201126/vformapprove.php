<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Unit jahit</label><label class="col-md-4">No SJ Keluar Makloon</label><label class="col-md-4">Tanggal SJ Keluar Makloon</label>
                        <div class="col-sm-4">
                            <select name="isubbagian" id="isubbagian" class="form-control select2" disabled>
                                <option value="<?=$isi->i_kode_master;?>"><?=$isi->e_departement_name;?></option>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?= $isi->i_kode_master;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="isj" name="isj" class="form-control" value="<?= $isi->i_sj;?>" readonly>
                        </div>
                         <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control" value="<?= date('d-m-Y', strtotime($isi->d_sj));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-8">No Forecast</label><label class="col-md-4">Tanggal Forecast</label>
                        <div class="col-sm-8">
                            <input type="text" id= "iforecast" name="iforecast" class="form-control" maxlength="16" readonly value="<?=$isi->i_forecast;?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id= "dforecast" name="dforecast" class="form-control date" readonly value="<?= date('d-m-Y', strtotime($isi->d_forecast));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledapprove();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Makloon Unit Jahit</label><label class="col-md-4">Tanggal Pengembalian</label>
                        <div class="col-sm-8">
                            <select name="iunitjahit" id="iunitjahit" class="form-control select2" disabled>
                                <option value="<?=$isi->i_unit_jahit;?>"><?=$isi->e_supplier_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dback" name="dback" class="form-control" readonly value="<?= date('d-m-Y', strtotime($isi->d_back));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" readonly value="<?=$isi->e_remark;?>">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode Barang WIP</th>
                                <th style="text-align: center; width: 20%;">Nama Barang WIP</th>  
                                <th style="text-align: center; width: 10%;">Warna</th>  
                                <th style="text-align: center; width: 7%;">Qty WIP</th>
                                <th style="text-align: center; width: 10%;">Kode Barang Material</th>  
                                <th style="text-align: center; width: 20%;">Nama Barang Material</th> 
                                <th style="text-align: center; width: 7%;">Qty Material</th>
                                <th style="text-align: center; width: 20%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <input style ="width:50px"type="hidden" name="jml" id="jml" value="">
                        </tbody>
                    </table>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
getdetailsj();
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});
function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal("Data Berhasil dikembalikan")
}

function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal("Data Berhasil direject");
}

$(document).ready(function () {
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
    function getdetailsj() {
        removeBody();
        var isj = $('#isj').val();
        var gudang = $('#istore').val();
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
                var lastproduct ='';
                var lastcolor ='';
                for (let a = 0; a < data['detail'].length; a++) {
                    var counter = a+1;
                    count=$('#tabledata tr').length;                   
                    var i_product           = data['detail'][a]['i_product'];
                    var e_product_name      = data['detail'][a]['e_namabrg'];
                    var i_color             = data['detail'][a]['i_color'];
                    var e_color             = data['detail'][a]['e_color_name'];
                    var n_quantity_product  = data['detail'][a]['n_quantity_wip'];
                    var i_material          = data['detail'][a]['i_material'];
                    var e_material_name     = data['detail'][a]['e_material_name'];
                    var n_quantity_material = data['detail'][a]['n_quantity'];
                    var e_remark = data['detail'][a]['e_remark'];

                    var cols        = "";
                    var newRow = $("<tr>");

                    if (lastproduct == i_product && lastcolor == i_color) {
                        cols += '<td style="text-align: center;" colspan="5"><spanx id="snum'+counter+'"></spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]" value="'+i_product+'"><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" value="'+i_color+'" /><input style="width:100px;" readonly type="hidden" id="qtybarang'+ counter + '" class="form-control" name="qtybarang[]" value="'+n_quantity_product+'" onkeyup="cekval(this.value); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}"/></td>';
                    } else {
                        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                        cols += '<td><input type="text" readonly  id="iproductwip'+ counter + '" type="text" class="form-control" name="iproductwip[]" value="'+i_product+'"></td>';
                        cols += '<td><input type="text" id="eproductwip'+ counter + '" class="form-control" name="eproductwip[]" value="'+e_product_name+'" readonly></td>';
                        cols += '<td><input type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]" value="'+e_color+'"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" value="'+i_color+'" /></td>';
                        cols += '<td><input style="text-align: right;"" type="text" id="qtybarang'+ counter + '" class="form-control" name="qtybarang[]" value="'+n_quantity_product+'" readonly onfocus="if(this.value==\'0\'){this.value=\'\';}"/></td>';
                    }
                    
                    cols += '<td><input type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]" value="'+i_material+'"></td>';
                    cols += '<td><input type="text" readonly id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" value="'+e_material_name+'"></td>';
                    cols += '<td><input style="text-align: right;" type="text" id="qtybahan'+ counter + '" class="form-control" name="qtybahan[]" readonly onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="cekval(this.value); reformat(this); " value="'+n_quantity_material+'"/></td>';    
                    cols += '<td><input type="text"  id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'" readonly></td>';

                    lastcolor = i_color;
                    lastproduct = i_product;
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