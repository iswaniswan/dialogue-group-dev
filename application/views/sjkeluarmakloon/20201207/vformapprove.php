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
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-5">Bagian</label>
                        <label class="col-md-6">Nomor SJ Makloon</label>                        
                        <div class="col-sm-5">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <option value="" selected>-- Pilih Bagian --</option>
                                <?php foreach ($kodemaster as $ibagian):?>
                                    <?php if ($ibagian->i_departement == $head->i_kode_master) { ?>
                                    <option value="<?php echo $ibagian->i_departement;?>" selected><?= $ibagian->e_departement_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ibagian->i_departement;?>"><?= $ibagian->e_departement_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="isj" name="isj" class="form-control" value="<?php echo $head->i_sj?>" readonly>
                        </div>                        
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-6">Partner</label>
                        <label class="col-md-6">Type Makloon</label>
                        <div class="col-sm-6">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled="">
                                <option value="">-- Pilih Partner --</option>
                                <?php foreach ($partner as $ipartner):?>
                                    <?php if ($ipartner->i_partner == $head->partner) { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>" selected><?= $ipartner->e_partner;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>"><?= $ipartner->e_partner;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="itypemakloon" id="itypemakloon" class="form-control select2" disabled="">
                                <option value="">-- Pilih Type Makloon --</option>
                                <?php foreach ($typemakloon as $itypemakloon):?>
                                    <?php if ($itypemakloon->i_type_makloon == $head->i_type_makloon) { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>" selected><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>"><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="<?php echo $head->e_remark?>" readonly>
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
                        <label class="col-md-5">Tanggal SJ Makloon</label>  
                        <label class="col-md-1"></label>        
                        <label class="col-md-4">Perkiraan Kembali</label>                        
                        <div class="col-sm-5">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo date('d-m-Y', strtotime($head->d_sj))?>" readonly>
                        </div>
                        <div class="col-sm-1    "></div>
                        <div class="col-sm-4">
                            <input type="text" id="dback" name="dback" class="form-control date" value="<?php echo date('d-m-Y', strtotime($head->d_kembali));?>" readonly>
                        </div>                       
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Referensi Pengeluaran</label>
                        <label class="col-md-5">Tanggal Referensi</label>
                        <div class="col-sm-6">
                             <input type="text" id= "reff" name="reff" class="form-control"  value="<?php echo $head->i_reff?>" readonly>
                        </div>  
                        <div class="col-sm-4">
                             <input type="text" id= "dreff" name="dreff" class="form-control"  value="<?php echo $head->d_referensi?>" readonly>
                        </div>  
                    </div>
                </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="15">Kode Barang</th>
                            <th width="45%">Nama Barang</th>  
                            <th>Qty Permintaan</th>
                            <th>Satuan</th>
                            <th>Qty Pemenuhan</th>
                            <th width="15">List Kode Barang</th>
                            <th width="45%">List Nama Barang</th>
                            <th>Qty Permintaan</th>
                            <th>Qty Pemenuhan</th>
                            <th>Satuan</th>  
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                                    $lastmaterial = '';
                                    foreach ($detail as $row) {
                                    $i++;
                                ?>
                                <?php if ($lastmaterial == $row->i_material) { ?>
                                    <td colspan="6">
                                        <input style="width:100px;" type="hidden" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                    </td>
                                <?php } else { ?>
                                        <td class="col-sm-1" style="text-align: center;">
                                            <spanx id="snum<?=$i;?>"><?=$i;?></spanx>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style="width:100px;" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style="width:400px;" type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style="width:80px;" type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_permintaan; ?>" readonly>
                                        </td>                                
                                        <td class="col-sm-1">
                                            <input style="width:100px;" type="text" class="form-control" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>" value="<?= $row->e_satuan; ?>" readonly>
                                            <input style="width:100px;" type="hidden" class="form-control" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>" value="<?= $row->i_satuan; ?>" readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style="width:80px;" type="text" class="form-control" id="pemenuhan<?=$i;?>" name="pemenuhan<?=$i;?>" value="<?= $row->n_pemenuhan; ?>" onkeyup="validasi('<?=$i;?>');" readonly>
                                <?php } ?>
                                

                                <td class="col-sm-1">
                                    <input style="width:100px;" type="text" class="form-control" id="imaterial2<?=$i;?>" name="imaterial2<?=$i;?>"value="<?= $row->i_material2; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:400px;" type="text" class="form-control" id="ematerialname2<?=$i;?>" name="ematerialname2<?=$i;?>"value="<?= $row->e_material_name2; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:80px;" type="text" class="form-control" id="nquantity2<?=$i;?>" name="nquantity2<?=$i;?>" value="<?= $row->n_permintaan2; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:80px;" type="text" class="form-control" id="pemenuhan2<?=$i;?>" name="pemenuhan2<?=$i;?>" value="<?= $row->n_pemenuhan2; ?>" readonly>
                                </td>                               
                                <td class="col-sm-1">
                                    <input style="width:100px;" type="text" class="form-control" id="esatuan2<?=$i;?>" name="esatuan2<?=$i;?>" value="<?= $row->e_satuan2; ?>" readonly>
                                    <input style="width:100px;" type="hidden" class="form-control" id="isatuan2<?=$i;?>" name="isatuan2<?=$i;?>" value="<?= $row->i_satuan2; ?>" readonly>
                                </td>
                               

                                <td class="col-sm-1">
                                    <input style="width:200px;"  type="text" class="form-control" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
                                    <input style="width:100px;" type="hidden" class="form-control" id="vprice<?=$i;?>" name="vprice<?=$i;?>" value="<?= $row->v_price; ?>" readonly>
                                </td>

                                </tr>
                                <?php 
                                $lastmaterial = $row->i_material;
                                } ?>
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
</script>