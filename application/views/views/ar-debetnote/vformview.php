<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
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
                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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

function getenabledcancel() {
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledsend() {
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#addrow').attr("disabled", true);
}

function getdisabled(){
    $('#ireferensikb').attr("disabled", true);
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
    $("#sendd").on("click", function () {
        var inotaar = $("#inotaar").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
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

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function getreferensi(ipartner){
    $("#ireferensipp").attr("disabled", false);
    getreferensikb(ipartner);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
        data:"ipartner="+ipartner,
        dataType: 'json',
        success: function(data){
            $("#ireferensipp").html(data.kop);
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

function getreferensikb(ipartner){
    $("#ireferensikb").attr("disabled", false);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreferensikb');?>",
        data:"ipartner="+ipartner,
        dataType: 'json',
        success: function(data){
            $("#ireferensikb").html(data.kop);
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

function getitemp(ireferensipp) {
    $("#ireferensikb").attr("disabled", true);   
    var ireferensipp = $('#ireferensipp').val();
    var ipartner     = $('#ipartner').val();

    $.ajax({
        type: "post",
        data: {
            'ireferensipp': ireferensipp,
            'ipartner': ipartner,
        },
        url: '<?= base_url($folder.'/cform/getitemp'); ?>',
        dataType: "json",
        success: function (data) {  
            var ireferensi = ireferensipp;
            $('#ireferensi').val(ireferensi);
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var nodok          = data['dataitem'][a]['nodok']
                var ddok           = data['dataitem'][a]['ddok'];
                var partner        = data['dataitem'][a]['partner'];
                var epartner       = data['dataitem'][a]['epartner'];
                var jumlah_lebih   = data['dataitem'][a]['jumlah_lebih'];
                
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:250px;" class="form-control" type="text" id="nodok'+no+'" name="nodok'+no+'" value="'+nodok+'"></td>';
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="ddok'+no+'" name="ddok'+no+'" value="'+ddok+'"></td>'; 
                cols += '<td><input readonly style="width:350px;" class="form-control" type="hidden" id="partner'+no+'" name="partner'+no+'" value="'+partner+'"><input readonly style="width:350px;" class="form-control" type="text" id="epartner'+no+'" name="epartner'+no+'" value="'+epartner+'"></td>'; 
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="jumlah_lebih'+no+'" name="jumlah_lebih'+no+'" value="'+jumlah_lebih+'"></td>';
                cols += '<td><input style="width:150px;" class="form-control" type="text" id="jumlah'+no+'" name="jumlah'+no+'" value="" onkeyup="validasi(this.value); reformat(this);"></td>';
               
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
} 

function getitemk(ireferensikb) {
    $("#ireferensipp").attr("disabled", true);   
    var ireferensikb = $('#ireferensikb').val();
    var ipartner     = $('#ipartner').val();

    $.ajax({
        type: "post",
        data: {
            'ireferensikb': ireferensikb,
            'ipartner': ipartner,
        },
        url: '<?= base_url($folder.'/cform/getitemk'); ?>',
        dataType: "json",
        success: function (data) {  
             var ireferensi = ireferensikb;
            $('#ireferensi').val(ireferensi);
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['dataitem'].length; a++) {
                var no = a+1;
                var nodok          = data['dataitem'][a]['nodok']
                var ddok           = data['dataitem'][a]['ddok'];
                var partner        = data['dataitem'][a]['partner'];
                var epartner       = data['dataitem'][a]['epartner'];
                var jumlah_lebih   = data['dataitem'][a]['jumlah_lebih'];
                //alert(nodok);
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                 cols += '<td><input readonly style="width:250px;" class="form-control" type="text" id="nodok'+no+'" name="nodok'+no+'" value="'+nodok+'"></td>';
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="ddok'+no+'" name="ddok'+no+'" value="'+ddok+'"></td>'; 
                cols += '<td><input readonly style="width:350px;" class="form-control" type="hidden" id="partner'+no+'" name="partner'+no+'" value="'+partner+'"><input readonly style="width:350px;" class="form-control" type="text" id="epartner'+no+'" name="epartner'+no+'" value="'+epartner+'"></td>'; 
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="jumlah_lebih'+no+'" name="jumlah_lebih'+no+'" value="'+jumlah_lebih+'"></td>';
                cols += '<td><input style="width:150px;" class="form-control" type="text" id="jumlah'+no+'" name="jumlah'+no+'" value="" onkeyup="validasi(this.value); reformat(this);"></td>';
               
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
} 

function validasi(id){

    var jml = $("#jml").val();
    //alert(jml);
    for(i=1;i<=jml;i++){
        qty     =$("#jumlah_lebih"+i).val();
        qtyretur=$("#jumlah"+i).val();
        if(parseFloat(qtyretur)>parseFloat(qty)){
            swal('Jumlah Tidak Boleh Lebih');
            $('#jumlah'+i).val("");
            break;
        }else if(qtyretur == '0'){
            swal("Jumlah Tidak boleh kosong");
            $('#jumlah'+i).val("");
            break;
        }
    }
}
</script>