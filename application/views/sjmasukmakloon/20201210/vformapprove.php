<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve2'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-5">Bagian</label>
                        <label class="col-md-7">Nomor SJ Masuk</label>                        
                        <div class="col-sm-5">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <?php foreach($kodemaster as $ibagian): ?>
                                <option value="<?php echo $ibagian->i_departement;?>" 
                                <?php if($ibagian->i_departement==$data->i_kode_master) { ?> selected="selected" <?php } ?>>
                                <?php echo $ibagian->e_departement_name;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                        <div class="col-sm-6">
                         <input type="text" id="isjkm" name="isjkm" class="form-control date" value="<?=$data->i_sj;?>" readonly>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                    <label class="col-md-12">Nomor Referensi</label>
                        <div class="col-sm-11">
                            <?php if ($ireferensi) {
                                $ireff = '';
                                foreach ($ireferensi as $kuy) {
                                    $ireff = $ireff."".$kuy->i_sj_reff." - ";
                                }
                            }?>
                            <textarea readonly=""  class="form-control text-left" required><?php if($ireff!=''){ echo substr($ireff, 0, -2);} ?></textarea>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="<?= $data->e_remark;?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <?if($data->i_status =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>                           
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->i_status =='3'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh" onclick="return getenabledchange();"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->i_status =='4'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->i_status =='6'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else{?>
                        <div class="col-sm-offset-5 col-sm-14">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}?>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal SJ Masuk</label>
                        <label class="col-md-8">Supplier</label>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?=$data->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="hidden" id="esupplier" name="esupplier" class="form-control" value="<?=$data->i_supplier;?>" readonly>
                            <input type="text" id="supplier" name="supplier" class="form-control" readonly value="<?=$data->e_supplier_name;?>">
                            <input type="hidden" id="inodoksup" name="inodoksup" class="form-control" value="<?= $data->e_no_dok_supplier;?>">
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Type Makloon</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="itypemakloon" name="itypemakloon" class="form-control" readonly value="<?=$data->i_type_makloon;?>">
                            <input type="text" id="etypemakloon" name="etypemakloon" class="form-control" readonly value="<?=$data->e_type_makloon;?>"> 
                        </div>
                    </div>                  
                </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                           <th>No</th>
                                    <th>Nomor Referensi</th>
                                    <th>Kode Barang ( Keluar )</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th>Kode Barang ( Masuk )</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $counter = 0; 
                                if ($detail){
                                    foreach ($detail as $row) {
                                        $counter++;?>
                                        <tr>
                                            <td style="text-align: center;"><?= $counter;?>
                                                <input type="hidden" class="form-control" readonly id="baris<?= $counter;?>" name="baris<?= $counter;?>" value="<?= $counter;?>">
                                            </td>
                                            <td class="text-center">
                                                <input style ="width:250px" type="text" class="form-control" readonly id="ireferensi<?= $counter;?>" name="ireferensi<?= $counter;?>" value="<?= $row->i_reff;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->material_keluar;?>" readonly="" type="hidden" id="imaterial1<?= $counter;?>" class="form-control" name="imaterial1<?= $counter;?>">
                                                <input style ="width:350px" value="<?= $row->nama_material_keluar;?>" readonly="" type="text" readonly id="ematerial1<?= $counter;?>" class="form-control" name="ematerial1<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->satuan_keluar;?>" readonly="" type="hidden" readonly id="isatuan1<?= $counter;?>" class="form-control" name="isatuan1<?=$counter;?>">
                                                <input style ="width:150px" value="<?= $row->nama_satuan_keluar;?>" readonly="" type="text" readonly id="esatuan1<?= $counter;?>" class="form-control" name="esatuan1<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input style ="width:100px" value="<?= $row->qty_keluar;?>" readonly="" type="text" readonly id="qty1<?= $counter;?>" class="form-control" name="qty1<?= $counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->material_masuk;?>" readonly="" type="hidden" id="imaterial2<?= $counter;?>" class="form-control" name="imaterial2<?= $counter;?>">
                                                <input style ="width:350px" value="<?= $row->nama_material_masuk;?>" readonly="" type="text" readonly id="ematerial2<?= $counter;?>" class="form-control" name="ematerial2<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->satuan_masuk;?>" readonly="" type="hidden" readonly id="isatuan2<?= $counter;?>" class="form-control" name="isatuan2<?=$counter;?>">
                                                <input value="<?= $row->nama_satuan_masuk;?>" readonly="" type="text" style ="width:100px" readonly id="esatuan2<?= $counter;?>" class="form-control" name="esatuan2<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input style ="width:100px" value="<?= $row->qty_masuk;?>" type="text" id="qty2<?= $counter;?>" class="form-control" name="qty2<?= $counter;?>" readonly>
                                            </td>
                                            <td>
                                                <input style ="width:350px" value="<?= $row->e_remark;?>" type="text" id="edesc<?= $counter;?>" class="form-control" name="edesc<?=$counter;?>" readonly>
                                            </td>
                                        </td>
                                    </tr>
                                <?php }  
                            } ?>
                    </tbody>
                </table>
                </div>
                  <input type="hidden" name="jml" id="jml" readonly value="<?= $counter;?>">
                </form>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#cancel").attr("disabled", true);
     $("#reject").attr("disabled", true);
     $("#change").attr("disabled", true);  
});
    
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getenabledcancel() {
    swal("Berhasil", "Cancel Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledchange() {
    swal("Berhasil", "Change Request Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledreject() {
    swal("Berhasil", "Reject Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var isjkm = $("#isjkm").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'isjkm'  : isjkm,
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

$(document).ready(function(){
    $("#change").on("click", function () {
       var isjkm = $("#isjkm").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'isjkm'  : isjkm,
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

$(document).ready(function(){
    $("#reject").on("click", function () {
    var isjkm = $("#isjkm").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'isjkm'  : isjkm,
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
</script>