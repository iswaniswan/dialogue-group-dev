<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">No BAPB</label><label class="col-md-4">Tanggal BAPB</label><label class="col-md-4">No BAPB</label>
                        <div class="col-sm-4">
                            <input class="form-control" readonly id="ibapb" name="ibapb" value="<?= $isi->i_bapb;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" readonly id= "dbapb" name="dbapb" class="form-control date" value="<?= $isi->d_bapb;?>">
                            <input hidden id="bbapb" name="bbapb" value="<?php echo $isi->bl; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input id="ibapbold" name="ibapbold" type="text" class="form-control" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" id="iarea" class="form-control select2" onchange="getarea(this.value);">
                                <option value="<?= $isi->i_area;?>"><?= $isi->e_area_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Jumlah Bal</label><label class="col-md-6">Biaya Kirim</label>
                        <div class="col-sm-6">
                            <input type="text" id="nbal" name="nbal" class="form-control" maxlength="5" value="<?php echo $isi->n_bal;?>" onkeypress="return hanyaAngka(event);">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="vkirim" name="vkirim" class="form-control" value="<?= number_format($isi->v_kirim); ?>" maxlength="12" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <?php 
                    $areabapb=substr($isi->i_bapb,9,2);
                    ?>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if (check_role($i_menu, 3)) {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $areabapb.'/'.$dfrom.'/'.$dto;?>","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                            &nbsp;&nbsp;
                            <?php if (check_role($i_menu, 3)) {?>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                                &nbsp;&nbsp;
                                <button type="button" id="addex" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Expedisi</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-12">Kirim</label>
                        <div class="col-sm-12">
                            <select name="idkbkirim" id="idkbkirim" class="form-control">
                                <?php if ($kirim) {                                 
                                    foreach ($kirim->result() as $kirim) { ?>
                                        <option value="<?php echo $kirim->i_dkb_kirim;?>" <?php if ($isi->i_dkb_kirim==$kirim->i_dkb_kirim) {
                                            echo "selected";
                                        }?>><?= $kirim->e_dkb_kirim;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <select name="icustomer" id="icustomer" class="form-control select2" onchange="disable(this.value);">
                                <option value="<?= $isi->i_customer; ?>"><?= $isi->e_customer_name; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai BAPB</label>
                        <div class="col-sm-12">
                            <input id="vbapb" name="vbapb" class="form-control" required="" 
                            readonly value="<?= number_format($isi->v_bapb); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 15%;">No SJ</th>
                                    <th style="text-align: center; width: 10%;">Tanggal SJ</th>
                                    <th style="text-align: center; width: 30%;">Pelanggan</th>
                                    <th style="text-align: center; width: 10%;">Jml</th>
                                    <th style="text-align: center;">Keterangan</th>
                                    <th style="text-align: center; width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 0;
                                if ($detail) {
                                    
                                    foreach ($detail as $row) {
                                        $qtodetail  = $this->mmaster->customertodetail($row->i_sj,$row->d_sj,$iarea);
                                        $e_customer_name = '';
                                        if($qtodetail->num_rows()>0){
                                            $rtodetail=$qtodetail->row();
                                            $e_customer_name = $rtodetail->e_customer_name;
                                        }
                                        $tmp=explode("-",$row->d_sj);
                                        $th =$tmp[0];
                                        $bl =$tmp[1];
                                        $hr =$tmp[2];
                                        $row->d_sj=$hr."-".$bl."-".$th;
                                        $i++;?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input type="hidden" readonly type="text" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="isj<?= $i;?>" name="isj<?= $i;?>" value="<?= $row->i_sj;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="dsj<?= $i;?>" name="dsj<?= $i;?>" value="<?= $row->d_sj;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="icustomerx<?= $i;?>" name="icustomerx<?= $i;?>" value="<?= $e_customer_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" readonly type="text" id="vsj<?= $i;?>" name="vsj<?= $i;?>" value="<?= $row->v_sj;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                            </td>
                                            <td class="text-center">
                                                <?php if (check_role($i_menu, 4)) {?>
                                                    <button type="button" onclick="hapusdetail('<?= $row->i_bapb."','".$row->i_area."','".$row->i_sj;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                            </tbody>
                        </table>
                        <table id="tableex" class="table color-table success-table table-bordered" cellspacing="0" width="100%">
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
                                <?php
                                $x = 0; 
                                if ($detailx) {
                                    
                                    foreach ($detailx as $row) {
                                        $x++;?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $x;?>
                                                <input class="form-control" readonly type="hidden" id="barisx<?= $x;?>" name="barisx<?= $x;?>" value="<?= $x;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iekspedisi<?= $x;?>" name="iekspedisi<?= $x;?>" value="<?= $row->i_ekspedisi;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eekspedisiname<?= $x;?>" name="eekspedisiname<?= $x;?>" value="<?= $row->e_ekspedisi;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremarkx<?= $x;?>" name="eremarkx<?= $x;?>" value="<?= $row->e_remark;?>">
                                            </td>
                                            <td class="text-center">
                                                <?php if (check_role($i_menu, 4)) {?>
                                                    <button type="button" onclick="hapusdetailx('<?= $row->i_bapb."','".$row->i_area."','".$row->i_ekspedisi;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <input type="hidden" name="jmlx" id="jmlx" value="<?= $x;?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function hapusdetail(ibapb,iarea,isj) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ibapb' : ibapb,
                        'iarea' : iarea,
                        'isj'   : isj,
                    },
                    url: '<?= base_url($folder.'/cform/deletedetail'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $id.'/'.$iarea.'/'.$dfrom.'/'.$dto.'/'.$icustomer;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }

    function hapusdetailx(ibapb,iarea,iekspedisi) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ibapb' : ibapb,
                        'iarea'     : iarea,
                        'iekspedisi'   : iekspedisi,
                    },
                    url: '<?= base_url($folder.'/cform/deletedetailx'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $id.'/'.$iarea.'/'.$dfrom.'/'.$dto.'/'.$icustomer;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }

    var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
        cols += '<td><select  type="text" id="isj'+xx+ '" class="form-control" name="isj'+xx+'" onchange="getsj('+xx+');"></td>';
        cols += '<td><input type="text" id="dsjx'+xx+'" type="text" class="form-control" name="dsjx'+xx+'" readonly><input type="hidden" id="dsj'+xx+'" type="text" class="form-control" name="dsj'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="icustomerx'+xx+'" type="text" class="form-control" name="icustomerx'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="vsj'+xx+'" class="form-control" name="vsj'+xx+'" readonly value="0" style="text-align: right;"></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        cols += '<td class="text-center"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#isj'+xx).select2({
            placeholder: 'Cari No SJ',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datasj/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea   = $('#iarea').val();
                    var icus    = $('#icustomer').val();
                    var query   = {
                        q       : params.term,
                        iarea   : iarea,
                        icus    : icus
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

    var yy = $('#jmlx').val();
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
        cols += '<td class="text-center"><button type="button" id="addex" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
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

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/dataarea'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getpelanggan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var i_area = $('#iarea').val();
                    var query = {
                        q: params.term,
                        i_area: i_area
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

    function getarea(iarea) {
        /*if (iarea!='') {*/
            $("#icustomer").val("");
            $("#icustomer").html("");
            $("#tabledata tr:gt(0)").remove();       
            $("#jml").val(0);
            xx = 0;
        /*}else{
            $("#icustomer").attr("disabled", true);
        }*/
    }

    function disable(icustomer) {
        if (icustomer!='') {
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
                alert ("No SJ : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var icus  = $('#icustomer').val();
            var iarea = $('#iarea').val();
            $.ajax({
                type: "post",
                data: {
                    'isj'  : a,
                    'iarea': iarea,
                    'icus' : icus
                },
                url: '<?= base_url($folder.'/cform/getdetailsj'); ?>',
                dataType: "json",
                success: function (data) {
                    var zz = formatulang($('#vbapb').val());
                    $('#dsjx'+id).val(data[0].dsj);
                    $('#dsj'+id).val(data[0].d_sj);
                    $('#icustomerx'+id).val(data[0].e_customer_name);
                    $('#vsj'+id).val(formatcemua(data[0].v_nota_netto));
                    $('#vbapb').val(formatcemua(parseFloat(zz)+parseFloat(formatulang(data[0].v_nota_netto))));
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
            (document.getElementById("icustomer").value!='') &&
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