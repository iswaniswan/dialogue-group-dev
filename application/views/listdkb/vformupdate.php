<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <?php 
                    if($isi->d_dkb!=''){
                        $tmp=explode("-",$isi->d_dkb);
                        $th =$tmp[0];
                        $bl =$tmp[1];
                        $hr =$tmp[2];
                        $ddkb=$hr."-".$bl."-".$th;
                        $periodedkb = $th.$bl;
                    }
                    if ($iperiode->i_periode<=$periodedkb) {
                        $bisaedit = 't';
                    }else{
                        $bisaedit = 'f';
                    }
                    if($isi->tglentry!=''){
                        $tmp=explode("-",$isi->tglentry);
                        $th =$tmp[0];
                        $bl =$tmp[1];
                        $hr =$tmp[2];
                        $ddkbx=$hr."-".$bl."-".$th;
                    }
                    ?>
                    <input hidden id="bdkb" name="bdkb" value="<?= $bl; ?>">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal DKB</label><label class="col-md-6">No DKB</label>
                        <div class="col-sm-6">
                            <input type="text" readonly="" id= "ddkb" name="ddkb" class="form-control <?php if($isi->i_approve1=='' && strlen($isi->xx)==15 && $bisaedit=='t'){ echo
                                "date";};?>" value="<?= $ddkb; ?>">
                                <input hidden id="tgldkb" name="tgldkb" value="<?php echo $ddkb; ?>">
                                <input type="hidden" id="ddkbx" name="ddkbx" value="<?php echo $ddkbx; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input readonly="" id="idkb" name="idkb" class="form-control" value="<?= $isi->i_dkb;?>">
                                <input id="idkbold" name="idkbold" type="hidden" value="<?= $isi->i_dkb_old; ?>"></td>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6">Via</label><label class="col-md-6">Kirim</label>
                            <div class="col-sm-6">
                                <select name="idkbvia" id="idkbvia" class="form-control select2" onchange="getex(this.value);">
                                    <?php if($isi->i_approve1=='' && strlen($isi->xx)==15 && $bisaedit=='t'){ 
                                        if ($via) {                                 
                                            foreach ($via as $via) { ?>
                                                <option value="<?= $via->i_dkb_via;?>" <?php if ($via->i_dkb_via==$isi->i_dkb_via) {echo "selected";}?>><?= $via->e_dkb_via;?></option>
                                            <?php }; 
                                        } 
                                    }else{?>
                                        <option value="<?= $isi->i_dkb_via;?>"><?= $isi->e_dkb_via;?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="idkbkirim" id="idkbkirim" class="form-control select2">
                                    <?php if($isi->i_approve1=='' && strlen($isi->xx)==15 && $bisaedit=='t'){ 
                                        if ($kirim) {                                 
                                            foreach ($kirim->result() as $kirim) { ?>
                                                <option value="<?= $kirim->i_dkb_kirim;?>" <?php if ($kirim->i_dkb_kirim==$isi->i_dkb_kirim) {echo "selected";}?>><?= $kirim->e_dkb_kirim;?></option>
                                            <?php }; 
                                        } 
                                    }else{?>
                                        <option value="<?= $isi->i_dkb_kirim;?>"><?= $isi->e_dkb_kirim;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-6 col-sm-12">
                                <?php if($isi->i_approve1=='' && strlen($isi->xx)==15 && $bisaedit=='t' && check_role($i_menu,3) && $isi->f_dkb_batal=='f'){?>
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update
                                    </button>
                                    &nbsp;&nbsp;
                                <?php } ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $iarea.'/'.$dfrom.'/'.$dto;?>","#main")'> <i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                                </button>
                                <?php if($isi->i_approve1=='' && strlen($isi->xx)==15 && $bisaedit=='t' && check_role($i_menu,3) && $isi->f_dkb_batal=='f'){?>
                                    &nbsp;&nbsp;
                                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                                    &nbsp;&nbsp;
                                    <button type="button" id="addex" class="btn btn-warning btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Expedisi</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"> 
                        <div class="form-group row">
                            <label class="col-md-12">Area</label>
                            <div class="col-sm-12">
                                <select name="iarea" id="iarea" class="form-control select2" onchange="getarea(this.value);">
                                    <?php if($isi->i_approve1=='' && strlen($isi->xx)==15 && $bisaedit=='t'){ 
                                        if ($area) {                                   
                                            foreach ($area as $iarea) { ?>
                                                <option value="<?= $iarea->i_area;?>" <?php if ($iarea->i_area==$isi->i_area) {echo "selected";}?>><?= $iarea->i_area." - ".$iarea->e_area_name;?></option>
                                            <?php }; 
                                        } 
                                    }else{?>
                                        <option value="<?= $isi->i_area;?>"><?= $isi->e_area_name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Supir</label>
                            <label class="col-md-4">Nomor Polisi</label>
                            <label class="col-md-4">Jumlah</label>
                            <div class="col-sm-4">
                                <input type="text" id="esupirname" name="esupirname" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $isi->e_sopir_name; ?>">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ikendaraan" name="ikendaraan" class="form-control" maxlength="9" value="<?= $isi->i_kendaraan; ?>">
                            </div>
                            <div class="col-sm-4">
                                <input id="vdkb" name="vdkb" class="form-control" required="" 
                                readonly value="<?= number_format($jumlah); ?>">
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
                                        <th class="text-center">Kirim</th>
                                        <th style="text-align: center; width: 5%;">Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php               
                                    $i=0;
                                    if($detail){                
                                        foreach($detail as $row){
                                            $i++;
                                            $qtodetail  = $this->mmaster->customertodetail($row->i_sj,$row->d_sj,$iarea);
                                            if($qtodetail->num_rows()>0){
                                                $rtodetail=$qtodetail->row();
                                                $e_customer_name = $rtodetail->e_customer_name;
                                            }else{
                                                $e_customer_name='';
                                            }
                                            $pangaos=number_format($row->v_jumlah);
                                            $tmp=explode("-",$row->d_sj);
                                            $th =$tmp[0];
                                            $bl =$tmp[1];
                                            $hr =$tmp[2];
                                            $row->d_sj=$hr."-".$bl."-".$th;
                                            $query=$this->db->query("
                                                SELECT
                                                i_nota
                                                FROM
                                                tm_nota
                                                WHERE
                                                i_sj = '$row->i_sj'
                                                AND i_area = '$row->i_area'
                                                ",false);
                                            if ($query->num_rows() > 0){
                                                foreach($query->result() as $tt){
                                                    $inota=$tt->i_nota;
                                                }
                                            }?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $i;?>
                                                    <input class="form-control" readonly type="hidden" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="isj<?= $i;?>" name="isj<?= $i;?>" value="<?= $row->i_sj;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="dsj<?= $i;?>" name="dsj<?= $i;?>" value="<?= $row->d_sj;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="icustomer<?= $i;?>" name="icustomer<?= $i;?>" value="<?= $e_customer_name;?>">
                                                </td>
                                                <td>
                                                    <input readonly="" class="form-control text-right" type="text" id="vsjnetto<?= $i;?>" name="vsjnetto<?= $i;?>" value="<?= $pangaos;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control"  type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                </td>
                                                <td>
                                                    <?php if($row->f_kirim=='t'){?>
                                                        <input class="form-control text-center" readonly type="text" id="fkirim<?= $i;?>" name="fkirim<?= $i;?>" value="Ya">
                                                    <?php }else{ ?>
                                                        <input class="form-control text-center" readonly type="text" id="fkirim<?= $i;?>" name="fkirim<?= $i;?>" value="Batal">
                                                    <?php } ?>
                                                </td>
                                                <?php if($bisaedit=='t' && $isi->i_noata=='' && check_role($i_menu,4)){?>
                                                    <td class="text-center">
                                                        <button type="button" onclick="hapusitem('<?= $row->i_dkb."','".$row->i_area."','".$row->i_sj;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                <?php }else{?>
                                                    <td></td>
                                                <?php }             
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                                <?php $i = 0; if ($detailx) {?>
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
                                            <?php foreach($detailx as $row){
                                                $i++;?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?= $i;?>
                                                        <input readonly type="hidden" id="barisx<?= $i;?>" name="barisx<?= $i;?>" value="<?= $i;?>">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" readonly type="text" id="iekspedisi<?= $i;?>" name="iekspedisi<?= $i;?>" value="<?= $row->i_ekspedisi;?>">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" readonly type="text" id="eekspedisiname<?= $i;?>" name="eekspedisiname<?= $i;?>" value="<?= $row->e_ekspedisi;?>">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" id="eremarkx<?= $i;?>" name="eremarkx<?= $i;?>" value="<?= $row->e_remark;?>">
                                                    </td>
                                                    <?php if($bisaedit=='t' && $isi->i_noata=='' && check_role($i_menu,4)){?>
                                                        <td class="text-center">
                                                            <button type="button" onclick="hapusitemx('<?= $row->i_dkb."','".$row->i_area."','".$row->i_ekspedisi;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    <?php }else{?>
                                                        <td></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                                <input type="hidden" name="jmlx" id="jmlx" value="<?= $i;?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function hapusitem(idkb,iarea,isj) {
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
                        'idkb'  : idkb,
                        'iarea' : iarea,
                        'isj'   : isj,
                    },
                    url: '<?= base_url($folder.'/cform/deleteitem'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $idkb.'/'.$iarea.'/'.$dfrom.'/'.$dto;?>','#main');
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
        cols += '<td><input type="text" id="icustomer'+xx+'" type="text" class="form-control" name="icustomer'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="vsjnetto'+xx+'" class="form-control" name="vsjnetto'+xx+'"/ readonly value="0" style="text-align: right;"></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+'"/></td>';
        cols += '<td><input class="form-control text-center" readonly type="text" id="fkirim'+xx+'" name="fkirim'+xx+'"></td>';
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
                    var ddkb    = $('#ddkb').val();
                    var query   = {
                        q       : params.term,
                        iarea   : iarea,
                        ddkb    : ddkb
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


    function hapusitemx(idkb,iarea,iekspedisi) {
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
                        'idkb'  : idkb,
                        'iarea' : iarea,
                        'iekspedisi'   : iekspedisi,
                    },
                    url: '<?= base_url($folder.'/cform/deleteitemx'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $idkb.'/'.$iarea.'/'.$dfrom.'/'.$dto;?>','#main');
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

    var yy = $('#jmlx').val();
    $("#addex").on("click", function () {
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
                alert ("No SJ : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var ddkb  = $('#ddkb').val();
            var iarea = $('#iarea').val();
            $.ajax({
                type: "post",
                data: {
                    'isj'  : a,
                    'iarea': iarea,
                    'ddkb' : ddkb
                },
                url: '<?= base_url($folder.'/cform/getdetailsj'); ?>',
                dataType: "json",
                success: function (data) {
                    var zz = formatulang($('#vdkb').val());
                    $('#dsjx'+id).val(data[0].dsj);
                    $('#dsj'+id).val(data[0].d_sj);
                    $('#icustomer'+id).val(data[0].e_customer_name);
                    $('#vsjnetto'+id).val(formatcemua(data[0].v_nota_netto));
                    $('#vdkb').val(formatcemua(parseFloat(zz)+parseFloat(formatulang(data[0].v_nota_netto))));
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
            var iarea = $('#iarea').val();
            $.ajax({
                type: "post",
                data: {
                    'iekspedisi' : a,
                    'iarea'      : iarea,
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
        cek='false'; 
        if((document.getElementById("ddkb").value!='') &&
            (document.getElementById("iarea").value!='') &&
            (document.getElementById("idkbkirim").value!='') &&
            (document.getElementById("idkbvia").value!='')) {   
            if(a==0){
                alert('Isi data item minimal 1 !!!');
                return false;
            }else{                
                for(i=1;i<=a;i++){                    
                    if((document.getElementById("isj"+i).value=='') ||
                        (document.getElementById("dsj"+i).value=='') ||
                        (document.getElementById("vsjnetto"+i).value=='')){
                        alert('Data item masih ada yang salah !!!');                    
                    return false;
                    cek='false';
                }else{
                    return true;
                    cek='true'; 
                } 
            }
        }
        if(cek=='true'){
            document.getElementById("submit").disabled=true;
        }else{
            return false;
        }
    }else{
        alert('Data header masih ada yang salah !!!');
        return false;
    }
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