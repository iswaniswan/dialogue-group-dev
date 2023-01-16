<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal BAPB</label><label class="col-md-6">No BAPB</label>
                        <div class="col-sm-6">
                            <input type="text" id= "dbapb" name="dbapb" class="form-control date" value="<?= date('d-m-Y');?>" readonly>
                            <input id="ibapb" name="ibapb" type="hidden">
                        </div>
                        <div class="col-sm-6">
                            <input id="ibapbold" name="ibapbold" type="text" class="form-control" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" id="iarea" class="form-control select2" onchange="getarea(this.value);">
                                <option value="">-- Pilih Area --</option>
                                <?php if ($area) {                                   
                                    foreach ($area as $iarea) { ?>
                                        <option value="<?php echo $iarea->i_area;?>"><?= $iarea->i_area." - ".$iarea->e_area_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kirim</label>
                        <div class="col-sm-12">
                            <select name="idkbkirim" id="idkbkirim" class="form-control">
                                <?php if ($kirim) {                                 
                                    foreach ($kirim->result() as $kirim) { ?>
                                        <option value="<?php echo $kirim->i_dkb_kirim;?>"><?= $kirim->e_dkb_kirim;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" disabled=""><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            &nbsp;&nbsp;
                            <button type="button" id="addex" class="btn btn-warning btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Expedisi</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah Bal</label>
                        <div class="col-sm-12">
                            <input type="text" id="nbal" name="nbal" class="form-control" maxlength="5" value="0" onkeypress="return hanyaAngka(event);">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Biaya Kirim</label>
                        <div class="col-sm-12">
                            <input type="text" id="vkirim" name="vkirim" class="form-control" value="0" maxlength="12" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai BAPB</label>
                        <div class="col-sm-12">
                            <input id="vbapb" name="vbapb" class="form-control" required="" 
                            readonly value="0">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <input type="hidden" name="jmlx" id="jmlx" value="0">
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 20%;">No SJP</th>
                                <th style="text-align: center; width: 15%;">Tanggal SJP</th>
                                <th style="text-align: center; width: 15%;">Jml</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center; width: 5%;">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <table id="tableex" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 20%;">Kode</th>
                                <th style="text-align: center; width: 40%;">Nama</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center; width: 5%;">Act</th>
                            </tr>
                        </thead>
                        <tbody>
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
    var xx = 0;
    $("#addrow").on("click", function () {
        xx++;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
        cols += '<td><select  type="text" id="isj'+xx+ '" class="form-control" name="isj'+xx+'" onchange="getsj('+xx+');"></td>';
        cols += '<td><input type="text" id="dsjx'+xx+'" type="text" class="form-control" name="dsjx'+xx+'" readonly><input type="hidden" id="dsj'+xx+'" type="text" class="form-control" name="dsj'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="vsj'+xx+'" class="form-control" name="vsj'+xx+'" readonly value="0" style="text-align: right;"></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#isj'+xx).select2({
            placeholder: 'Cari No SJP',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datasj/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea   = $('#iarea').val();
                    var query   = {
                        q       : params.term,
                        iarea   : iarea
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });

    var yy = 0;
    $("#addex").on("click", function () {
        $("#tableex").attr("hidden", false);
        yy++;
        $('#jmlx').val(yy);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+yy+'<input type="hidden" id="baris'+yy+'" type="text" class="form-control" name="baris'+yy+'" value="'+yy+'"></td>';
        cols += '<td><select  type="text" id="iekspedisi'+yy+ '" class="form-control" name="iekspedisi'+yy+'" onchange="getdetailex('+yy+');"></td>';
        cols += '<td><input type="text" id="eekspedisiname'+yy+'" type="text" class="form-control" name="eekspedisiname'+yy+'" readonly></td>';
        cols += '<td><input type="text" id="eremarkx'+yy+'" class="form-control" name="eremarkx'+yy+ '"/></td>';
        cols += '<td><button type="button" id="addex" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tableex").append(newRow);
        $('#iekspedisi'+yy).select2({
            placeholder: 'Cari Expedisi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/dataex/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $("#tableex").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        yy -= 1
        $('#jmlx').val(yy);
    });

    
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 0, 5);
    });

    function getarea(iarea) {
        if (iarea!='') {
            $("#addrow").attr("disabled", false);
        }else{
            $("#addrow").attr("disabled", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        xx = 0;
    }

    function getsj(id){
        ada=false;
        var a = $('#isj'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#isj'+i).val()) && (i!=x)){
                alert ("No SJP : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var iarea = $('#iarea').val();
            $.ajax({
                type: "post",
                data: {
                    'isj'  : a,
                    'iarea': iarea
                },
                url: '<?= base_url($folder.'/cform/getdetailsj'); ?>',
                dataType: "json",
                success: function (data) {
                    var zz = formatulang($('#vbapb').val());
                    $('#dsjx'+id).val(data[0].dsjp);
                    $('#dsj'+id).val(data[0].d_sjp);
                    $('#vsj'+id).val(formatcemua(data[0].v_sjp));
                    $('#vbapb').val(formatcemua(parseFloat(zz)+parseFloat(formatulang(data[0].v_sjp))));
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#isj'+id).html('');
            $('#isj'+id).val('');
        }
    }

    function getex(via) {
        if (via!='') {
            if(via!='1'){
                $("#addex").attr("hidden", true);
                $("#tableex").attr("hidden", true);
                $("#tableex tr:gt(0)").remove();       
                $("#jmlx").val(0);
                yy = 0;
            }else{
                $("#addex").attr("hidden", false);
                $("#tableex").attr("hidden", false);
            }
        }else{
            $("#addex").attr("hidden", true);
            $("#tableex").attr("hidden", true);
            $("#tableex tr:gt(0)").remove();       
            $("#jmlx").val(0);
            yy = 0;
        }
    }

    function getdetailex(id){
        ada=false;
        var a = $('#iekspedisi'+id).val();
        var x = $('#jmlx').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iekspedisi'+i).val()) && (i!=x)){
                alert ("Kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'iekspedisi' : a
                },
                url: '<?= base_url($folder.'/cform/getdetailex'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#iekspedisi'+id).val(data[0].i_ekspedisi);
                    $('#eekspedisiname'+id).val(data[0].e_ekspedisi);
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#iekspedisi'+id).html('');
            $('#iekspedisi'+id).val('');
        }
    }   

    function dipales(a){ 
        if((document.getElementById("dbapb").value!='') &&
            (document.getElementById("iarea").value!='') &&
            (document.getElementById("idkbkirim").value!='') &&
            (document.getElementById("jmlx").value!='') &&
            (document.getElementById("jml").value!='') &&
            (document.getElementById("nbal").value!='')
            ){   
            if(a==0){
                alert('Isi data item minimal 1 !!!');
                return false;
            }else{                
                for(i=1;i<=a;i++){                    
                    if((document.getElementById("isj"+i).value=='') ||
                        (document.getElementById("dsj"+i).value=='') ||
                        (document.getElementById("vsj"+i).value=='')){
                        alert('Data item masih ada yang salah !!!');                    
                    return false;
                }else{
                    return true;
                } 
            }
        }
    }else{
        alert('Data header masih ada yang salah !!!');
        return false;
    }
}

function hanyaAngka(evt) {      
    var charCode = (evt.which) ? evt.which : event.keyCode      
    if (charCode > 31 && (charCode < 48 || charCode > 57))        
        return false;    
    return true;
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#addex").attr("disabled", true);
});
</script>