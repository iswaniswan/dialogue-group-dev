<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approvenextto'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-6">No PP</label>
                        <label class="col-md-6">No OP</label>
                        <div class="col-sm-6">
                            <input type="text" name="ipp" class="form-control" value="<?= $data->i_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="iop" id="iop" class="form-control" value="<?= $data->i_op; ?>" readonly>
                        </div>
                    </div>       
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label>
                        <label class="col-md-6">Supplier</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="ikodemaster" class="form-control" required="" value="<?= $data->i_kode_master; ?>" readonly>
                             <input type="text" name="enamamaster" class="form-control" required="" value="<?= $data->e_nama_master; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                             <input type="hidden" name="isupplier" class="form-control" required="" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" name="esupplier" class="form-control" required="" value="<?= $data->e_supplier_name; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eremark" class="form-control" value="<?= $data->e_remark; ?>" readonly>
                        </div>
                    </div>           
                    <div class="form-group">
                        <?if($data->e_approval =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>                           
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->e_approval =='3'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh" onclick="return getenabledchange();"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->e_approval =='4'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        
                        <?}else if($data->e_approval =='8'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
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
                        <label class="col-md-4">Tanggal OP</label>
                        <label class="col-md-4">Tanggal Kirim</label>
                        <label class="col-md-4">Importance Status</label>
                        <div class="col-sm-4">
                            <input type="text" name="dop" class="form-control date" required="" value="<?= $data->d_op; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="ddelivery" class="form-control date" required=""   value="<?= $data->d_deliv; ?>" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <select name="importantstatus" class="form-control select2" readonly disabled="">
                                <option value="">Pilih Importance Status</option>
                                <option value="1" <?php if( $data->important_status=='1')echo 'selected';?>> Urgent</option>                      
                                <option value="2" <?php if( $data->important_status=='2')echo 'selected';?>> Prioritas</option>  
                                <option value="3" <?php if( $data->important_status=='3')echo 'selected';?>> Reguler</option>      
                        </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-4">Jenis Pembelian</label>
                        <label class="col-md-8">Status Dokumen</label>
                        <div class="col-sm-4">                            
                            <select name="ipaymenttype" class="form-control select2" readonly disabled="">
                                <option value="">Pilih Jenis Pembelian</option>
                                <option value="0" <?php if( $data->i_payment_type=='0')echo 'selected';?>> Cash</option>                      
                                <option value="1" <?php if( $data->i_payment_type=='1')echo 'selected';?>> Kredit</option>      
                        </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="istatus" class="form-control" value="<?= $data->e_status; ?>" readonly>
                        </div>
                    </div>                     
                </div>                
                    <div class="panel-body table-responsive">
                       <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="15%">Kode Barang</th>
                                    <th width="40%">Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?$i = 0;
                                foreach ($data2 as $row) {
                                $i++;?>
                                <tr>
                                 <td>
                                    <input style ="width:40px"; class="form-control" type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:160px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:450px" class="form-control" type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control"type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control" type="text" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:300px" class="form-control"type="text" id="eremark<?=$i;?>" name="e_remark<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
                                </td>
                                </tr>
                                
                                <?}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#cancel").attr("disabled", true);
     $("#reject").attr("disabled", true);
     $("#change").attr("disabled", true);
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

function getenabledappr2() {
    swal("Berhasil", "Approve Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#submit2').attr("disabled", true);
}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var iop = $("#iop").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'iop'  : iop,
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
       var iop = $("#iop").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'iop'  : iop,
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
    var iop = $("#iop").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'iop'  : iop,
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
    $("#submit2").on("click", function () {
    var iop = $("#iop").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/appr2'); ?>",
            data: {
                     'iop'  : iop,
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

$(document).ready(function () {
    $(".select").select();
    $(".select2").select2();
    showCalendar('.date');
});
</script>