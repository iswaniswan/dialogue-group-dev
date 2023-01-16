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
                    <label class="col-md-5">Nomor Dokumen</label>
                    <label class="col-md-3">Bagian</label>
                    <label class="col-md-4">Tanggal</label>
                    <div class="col-sm-5">
                        <input class="form-control" name="ikasmasuk" id="ikasmasuk" readonly="" value="<?= $data->i_kas_masuk?>">
                    </div>
                    <div class="col-sm-3">
                            <select class="form-control select2" name="ibagian" id="ibagian" disabled="">
                                <?php foreach ($area as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?= $ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" name="dmasuk" id="dmasuk" readonly="" value="<?= $data->d_kas_masuk?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">Customer</label>
                    <label class="col-md-7">Nilai</label>
                    <div class="col-sm-5">
                        <select name="icustomer" id="icustomer" class="form-control select2" onchange="return getcustomer(this.value);" disabled="">
                             <option value="" selected>Pilih Customer</option>
                            <?php foreach ($customer as $icustomer):?>
                            <?php if ($icustomer->i_customer == $data->i_customer) { ?>
                                <option value="<?php echo $icustomer->i_customer;?>" selected><?= $icustomer->e_customer_name;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" name="vnilai" id="vnilai" class="form-control" value="<?=$data->n_nilai?>" readonly>
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
               <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-5">Kas/Bank</label>
                    <label class="col-md-6">Bank</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" onchange="return customer(this.value);" disabled="">
                            <option value="" selected>Pilih Tujuan</option>
                            <?php foreach ($kasbank as $ikasbank):?>
                            <?php if ($ikasbank->i_kode_kas == $data->i_kas_bank) { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>" selected><?= $ikasbank->e_nama_kas;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>"><?= $ikasbank->e_nama_kas;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?if($data->i_bank == null){?>
                    <div class="col-sm-5">
                        <select name="ibank" id="ibank" class="form-control select2"  disabled="true">
                        </select>
                    </div>
                    <?}else{?>
                    <div class="col-md-6">
                        <select name="ibank" id="ibank" class="form-control select2" disabled="">
                            <option value="" selected>Pilih Bank</option>
                            <?php foreach ($bank as $ibank):?>
                            <?php if ($ibank->i_bank == $data->i_bank) { ?>
                                <option value="<?php echo $ibank->i_bank;?>" selected><?= $ibank->e_bank_name;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ibank->i_bank;?>"><?= $ibank->e_bank_name;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?}?>
                </div>
                <div class="from-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-11">Keterangan</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <input type="text" name="eremark" id="eremark" class="form-control" value="<?=$data->e_remark?>" readonly>
                    </div>
                </div>
            </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Customer</th>
                                    <th>Nama Customer</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php $i = 0;
                                foreach ($datadetail as $row) {
                                $i++;
                                $checked = !empty($row->kasmasuk)?"checked":"";?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" type="text" class="form-control" id="icustomer<?=$i;?>" name="icustomer<?=$i?>" value="<?= $row->i_customer; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px" class="form-control" type="text" id="ecustomer<?=$i;?>" name="ecustomer<?=$i;?>" value="<?= $row->e_customer_name; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:100px" class="form-control" type="text" id="vnilai<?=$i;?>" name="vnilai<?=$i;?>" value="<?= number_format($row->n_nilai,0); ?>" readonly>
                                </td>
                                <td>
                                    <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>" value="<?= $row->e_remark; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" <?php echo $checked ?> readonly>
                                </td>
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
        var ikasmasuk = $("#ikasmasuk").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'ikasmasuk'  : ikasmasuk,
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
       var ikasmasuk = $("#ikasmasuk").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'ikasmasuk'  : ikasmasuk,
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
    var ikasmasuk = $("#ikasmasuk").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'ikasmasuk'  : ikasmasuk,
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
    $("#cancel").on("click", function () {
        var ikasmasuk = $("#ikasmasuk").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'ikasmasuk'  : ikasmasuk,
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
    $("#sendd").on("click", function () {
        var ikasmasuk = $("#ikasmasuk").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'ikasmasuk'  : ikasmasuk,
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
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
 });
</script>