<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve2'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label>
                        <label class="col-md-6">No Dokumen</label>
                      
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();" disabled="">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_departement == $head->i_kode_master) { ?>
                                    <option value="<?php echo $ikodemaster->i_departement;?>" selected><?= $ikodemaster->e_departement_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_departement;?>"><?= $ikodemaster->e_departement_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master?>">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" id="isj" name="isj" class="form-control" value="<?php echo $head->i_sj?>" readonly>
                        </div>
                       
                    </div>
                     <div class="form-group row">
                        <div class="col-sm-6">    
                        <label >Nomor Memo</label>                        
                            <select name="i_memo" id="i_memo" class="form-control select2" onchange="getdataitemmemo(this.value);" disabled=""> 
                                <option value="<?php echo $head->i_memo?>" selected><?php echo $head->i_memo?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                        <label >Tanggal Memo</label>
                            <input type="text" id="dmemo" name="dmemo" class="form-control" value="<?php echo $head->d_memo?>" readonly>
                        </div>
                    </div>
                   <div class="form-group">
                        <?if($head->i_status =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>                           
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($head->i_status =='3'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh" onclick="return getenabledchange();"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($head->i_status =='4'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($head->i_status =='6'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else{?>
                        <div class="col-sm-offset-5 col-sm-10">  
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
                        <label class="col-md-4">Tanggal SJ</label>
                        <label class="col-md-8">Department/Partner</label>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo $head->d_sj?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <select name="edept" id="edept" class="form-control select2" onchange="getmemobaru(this.value);" disabled="">
                                <option value="<?php echo $head->i_customer?>" selected><?php echo $head->e_partner?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark?>" readonly>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="35%">Nama Barang</th>  
                                    <th>Quantity Permintaan</th>
                                    <th>Quantity</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                foreach ($detail as $row) {
                                $i++;?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]"value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px" class="form-control" type="text" id="eproduct<?=$i;?>" name="eproduct[]" value="<?= $row->e_material_name; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:100px" class="form-control" type="text" id="nquantityp<?=$i;?>" name="nquantityp[]" value="<?= number_format($row->n_quantity_permintaan,0); ?>" readonly>
                                </td>
                                <td>
                                    <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= number_format($row->n_quantity,0); ?>" readonly>
                                </td>
                                <td>
                                    <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]" value="<?= $row->e_remark; ?>" readonly>
                                     <input class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan[]" value="<?= $row->i_satuan; ?>" >
                                </td>
                                <!-- <td align="center">
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td> -->
                                </tr>
                                <?}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </form>
            <div>
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
    swal("Berhasil", "Change Requested Dokumen", "success");
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
        var isj = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'isj'  : isj,
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
       var isj = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'isj'  : isj,
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
    var isj = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data:   {
                     'isj'  : isj,
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