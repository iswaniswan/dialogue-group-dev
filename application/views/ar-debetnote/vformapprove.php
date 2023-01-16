<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve2'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div id="pesan"></div>
            <div class="col-md-6">
                 <div class="form-group row">
                        <label class="col-md-5">No Dokumen</label>
                        <label class="col-md-3">Bagian</label>
                        <label class="col-md-4">Tanggal</label>
                        <div class="col-sm-5">
                            <input class="form-control" name="inotaar" id="inotaar" readonly="" value="<?= $data->i_nota_ar; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="ibagian" id="ibagian" disabled="true">
                                <?php foreach ($area as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?= $ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                     </div>
                     <div class="col-sm-4">
                        <input class="form-control" name="dnota" id="dnota" readonly="" value="<?= $data->d_nota_ar; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">Referensi dari Pelunasan Piutang</label>
                    <label class="col-md-7">Referensi dari Kas/Bank in Other</label>
                    <input type="hidden" class="form-control" name="ireferensi" id="ireferensi" readonly="" value="<?= $data->i_referensi; ?>">
                <?php foreach ($cek1 as $cek1) {
                    if($cek1->i_referensi){?>
                    <div class="col-sm-5">
                       <select name="ireferensipp" id="ireferensipp" class="form-control select2" onchange="return getitemp(this.value);" disabled="true">
                            <option value="">Pilih Referensi</option>
                            <?php foreach ($referensi as $ireferensipp):?>
                            <?php if ($ireferensipp->i_referensi == $data->i_referensi) { ?>
                                <option value="<?php echo $ireferensipp->i_referensi;?>" selected><?= $ireferensipp->i_referensi;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ireferensipp->i_referensi;?>"><?= $ireferensipp->i_referensi;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>                           
                    </div>
                    <div class="col-sm-7">
                        <select name="ireferensikb" id="ireferensikb" class="form-control select2" onchange="return getitemk(this.value);" disabled="true">
                            <option value="" selected>Pilih Referensi</option>
                            <?php foreach ($referensii as $ireferensikb):?>
                            <?php if ($ireferensikb->i_referensi == $data->i_referensi) { ?>
                                <option value="<?php echo $ireferensikb->i_referensi;?>" selected><?= $ireferensikb->i_referensi;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ireferensikb->i_referensi;?>"><?= $ireferensikb->i_referensi;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?}
                }?>
                <?php foreach ($cek2 as $cek2) {
                    if($cek2->i_referensi){?>
                    <div class="col-sm-5">
                       <select name="ireferensipp" id="ireferensipp" class="form-control select2" onchange="return getitemp(this.value);" disabled="true">
                            <option value="">Pilih Referensi</option>
                            <?php foreach ($referensi as $ireferensipp):?>
                            <?php if ($ireferensipp->i_referensi == $data->i_referensi) { ?>
                                <option value="<?php echo $ireferensipp->i_referensi;?>" selected><?= $ireferensipp->i_referensi;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ireferensipp->i_referensi;?>"><?= $ireferensipp->i_referensi;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>                           
                    </div>
                    <div class="col-sm-7">
                        <select name="ireferensikb" id="ireferensikb" class="form-control select2" onchange="return getitemk(this.value);" disabled="true">
                            <option value="" selected>Pilih Referensi</option>
                            <?php foreach ($referensii as $ireferensikb):?>
                            <?php if ($ireferensikb->i_referensi == $data->i_referensi) { ?>
                                <option value="<?php echo $ireferensikb->i_referensi;?>" selected><?= $ireferensikb->i_referensi;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ireferensikb->i_referensi;?>"><?= $ireferensikb->i_referensi;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?}
                }?>
                </div>
                <div class="form-group row">
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
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-6">Partner</label>
                    <label class="col-md-5">Kas/Bank</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-6">
                        <select class="form-control select2" name="ipartner" id="ipartner" onchange="return getreferensi(this.value);" disabled="true">
                            <option value="" selected>Pilih Partner</option>
                            <?php foreach ($partner as $ipartner):?>
                            <?php if ($ipartner->i_customer == $data->i_partner) { ?>
                                <option value="<?php echo $ipartner->i_customer;?>" selected><?= $ipartner->e_customer_name;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ipartner->i_customer;?>"><?= $ipartner->e_customer_name;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <select class="form-control select2" name="ikasbank" id="ikasbank" disabled="true">
                            <option value="" selected>Pilih Kas/Bank</option>
                            <?php foreach ($kasbank as $ikasbank):?>
                            <?php if ($ikasbank->i_kode_kas == $data->i_kasbank) { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>" selected><?= $ikasbank->e_nama_kas;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>"><?= $ikasbank->e_nama_kas;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-11">Keterangan</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <input type="text" name="eremark" id="eremark" class="form-control" value="<?= $data->e_remark;?>" readonly>
                    </div>
                </div>      
            </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Dokumen</th>
                                    <th>Tanggal Dokumen</th>
                                    <th>Pengirim</th>
                                    <th>Jumlah</th>
                                    <th>Jumlah yang dikembalikan</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;
                                    //$checked = !empty($row->krilinggiro)?"checked":"";
                                ?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:350px" class="form-control" type="text" id="nodok<?=$i;?>" name="nodok<?=$i;?>" value="<?= $row->i_referensi; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="ddok<?=$i;?>" name="ddok<?=$i;?>"value="<?= $row->d_referensi; ?>" readonly >
                                </td>                          
                                <td class="col-sm-1">
                                    <input style ="width:250px" class="form-control" type="hidden" id="partner<?=$i;?>" name="partner<?=$i;?>" value="<?= $row->i_customer; ?>" readonly >
                                    <input style ="width:350px" class="form-control" type="text" id="epartner<?=$i;?>" name="epartner<?=$i;?>" value="<?= $row->e_customer_name; ?>" readonly >
                                </td>  
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="jumlah_lebih<?=$i;?>" name="jumlah_lebih<?=$i;?>" value="<?= $row->n_price; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="jumlah<?=$i;?>" name="jumlah<?=$i;?>" value="<?= $row->n_price_back; ?>" onkeyup="validasi(); reformat(this);" readonly>
                                </td>
                                </tr>
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
$(document).ready(function () {
    showCalendar('.date');
    $('.select2').select2();
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#cancel").attr("disabled", true);
     $("#reject").attr("disabled", true);
     $("#change").attr("disabled", true);  
});

function getenabledcancel() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var inotaar = $("#inotaar").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'inotaar'  : inotaar,
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
       var inotaar = $("#inotaar").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'inotaar'  : inotaar,
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
    var inotaar = $("#inotaar").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'inotaar'  : inotaar,
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