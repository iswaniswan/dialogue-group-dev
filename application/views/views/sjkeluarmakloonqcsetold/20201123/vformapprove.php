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
                    <div class="col-md-7">
                         <input type="hidden" id="ndok" name="ndok" class="form-control" value="<?=$data->n_dok;?>" readonly>
                         <div class="form-group row">
                            <label class="col-md-4">Bagian</label>
                            <label class="col-md-4">No SJ</label>
                            <label class="col-md-4">Tanggal SJ</label>
                            <div class="col-sm-4">
                               <select name="ibagian" id="ibagian" class="form-control select2" disabled>
                                    <option value="">Pilih Bagian</option>
                                    <?php foreach ($bagian as $ibagian):?>
                                        <?php if ($ibagian->i_departement == $data->i_bagian) { ?>
                                             <option value="<?php echo $ibagian->i_departement;?>" selected><?= $ibagian->e_departement_name;?></option>
                                        <?php } else { ?>
                                             <option value="<?php echo $ibagian->i_departement;?>"><?= $ibagian->e_departement_name;?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                               </select>
                            </div>
                            <div class="col-sm-4">
                               <input type="text" id="isj" name="isj" class="form-control" value="<?=$data->i_sj;?>" required readonly>
                            </div>
                            <div class="col-sm-3">
                               <input type="text" id="dsjk" name="dsjk" class="form-control" value="<?=$data->d_sj;?>" required readonly onchange="return max_back();">
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-md-8">No Permintaan</label>
                            <label class="col-md-4">Tanggal Permintaan</label>
                            <div class="col-sm-8">
                               <input type="text" id= "ipermintaan" name="ipermintaan" class="form-control" maxlength="18" required value="<?=$data->i_permintaan;?>" readonly>
                               <input type="hidden" id= "fpkp" name="fpkp" class="form-control" value="<?=$data->f_pkp;?>">
                               <input type="hidden" id= "vdiskon" name="vdiskon" class="form-control" value="<?=$data->n_diskon;?>">
                            </div>
                            <div class="col-sm-3">
                               <input readonly type="text" id= "dpermintaan" name="dpermintaan" class="form-control" required value="<?=$data->d_permintaan;?>">
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
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}?>
                    </div>
                </div>
               <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-md-4">Perkiraan Kembali</label>
                        <label class="col-md-4">Partner</label>
                        <label class="col-md-4">Type Makloon</label>
                        <div class="col-sm-4">
                           <input type="text" id="dback" name="dback" class="form-control" required value="<?=$data->d_back;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled>
                                <option value="">Pilih Partner</option>
                                <?php foreach ($partner as $ipartner):?>
                                    <?php if ($ipartner->i_partner == $data->i_partner) { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>" selected><?= $ipartner->e_partner;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>"><?= $ipartner->e_partner;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="itypemakloon" id="itypemakloon" class="form-control select2" onchange="getstore();" disabled>
                                <option value="">Pilih Type Makloon</option>
                                <?php foreach ($typemakloon as $itypemakloon):?>
                                    <?php if ($itypemakloon->i_type_makloon == $data->i_type_makloon) { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>" selected><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>"><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                           <input type="text" id= "eremark" name="eremark" class="form-control" value="<?=$data->e_remark;?>" readonly>
                        </div>
                     </div>
                    </div>
                <div class="panel-body table-responsive">
                 <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 13%;">Kode Barang</th>
                                <th style="text-align: center; width: 35%;">Nama Barang</th>
                                <th style="text-align: center; width: 10%;">Quantity</th>
                                <th style="text-align: center; width: 30%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  $i = 0;
                                    foreach ($detail as $row) {
                                    $i++;
                                ?>
                                    <td class="col-sm-1" style="text-align: center;">
                                        <spanx id="snum<?=$i;?>"><?=$i;?></spanx>
                                    </td>
                                    <td class="col-sm-1">
                                        <input style="width:150px;" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]" value="<?= $row->i_material; ?>" readonly >
                                    </td>
                                    <td class="col-sm-1">
                                        <input style="width:400px;" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]" value="<?= $row->e_namabrg; ?>" readonly onkeyup="validasi('<?=$i;?>');">
                                    </td>
                                    <td class="col-sm-1">
                                        <input style="width:100px;" type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" readonly>
                                    </td>                     
                                    <td class="col-sm-1">
                                        <input style="width:400px;" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc[]" value="<?= $row->e_remark; ?>"  readonly>
                                        <input style="width:400px;" type="hidden" class="form-control" id="vharga<?=$i;?>" name="vharga[]" value="<?= $row->v_price; ?>">
                                    </td>
                                <?php } ?>  
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
        var isj  = $("#isj").val();
        var ndok = $("#ndok").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'isj'  : isj,
                     'ndok' : ndok,
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
        var isj  = $("#isj").val();
        var ndok = $("#ndok").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'isj'  : isj,
                     'ndok' : ndok,
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
        var isj  = $("#isj").val();
        var ndok = $("#ndok").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'isj'  : isj,
                     'ndok' : ndok,
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