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
                        <label class="col-md-6">No BAPB</label><label class="col-md-6">Tanggal BAPB</label>
                        <div class="col-sm-6">
                            <input class="form-control" readonly id="ibapb" name="ibapb" value="<?= $isi->i_bapb;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly id= "dbapb" name="dbapb" class="form-control date" value="<?= $isi->d_bapb;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input type="text" id="iarea" name="iarea" class="form-control" value="<?= $isi->i_area;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai BAPB</label>
                        <div class="col-sm-6">
                            <input type="text" id="vbapb" name="vbapb" class="form-control" maxlength="5" value="<?php echo number_format($isi->v_bapb);?>">
                        </div>
                    </div>
                    <?php 
                    $areabapb=substr($isi->i_bapb,9,2);
                    ?>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if (check_role($i_menu, 3)) {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $areabapb.'/'.$dfrom.'/'.$dto;?>","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                            &nbsp;&nbsp;
                            <?php if (check_role($i_menu, 3)) {?>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            <?php } ?>
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
                                    <th style="text-align: center; width: 12%;">Nilai</th>
                                    <th style="text-align: center;">Keterangan</th>
                                    <th style="text-align: center; width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) {
                                        $tmp=explode("-",$row->d_sjpb);
									    $th =$tmp[0];
									    $bl =$tmp[1];
									    $hr =$tmp[2];
									    $row->d_sjpb=$hr."-".$bl."-".$th;
									    $row->v_sjpb = number_format($row->v_sjpb);
                                        $i++;?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input type="hidden" readonly type="text" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="isj<?= $i;?>" name="isj<?= $i;?>" value="<?= $row->i_sjpb;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="dsj<?= $i;?>" name="dsj<?= $i;?>" value="<?= $row->d_sjpb;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="vsj<?= $i;?>" name="vsj<?= $i;?>" value="<?= $row->v_sjpb;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                            </td>
                                            <td class="text-center">
                                                <?php if (check_role($i_menu, 4)) {?>
                                                    <button type="button" onclick="hapusdetail('<?= $row->i_bapb."','".$row->i_area."','".$row->i_sjpb;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $jmlitem;?>">
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
var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        
        xx++;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
        cols += '<td><select type="text" id="isj'+xx+ '" class="form-control" name="isj'+xx+'" onchange="getsj('+xx+');"></td>';
        cols += '<td><input type="text" id="dsj'+xx+'" type="text" class="form-control" name="dsj'+xx+'" readonly></td>';
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
                    $('#dsj'+id).val(data[0].d_sjpb);
                    $('#vsj'+id).val(formatcemua(data[0].v_sjpb));
                    $('#vbapb').val(formatcemua(parseFloat(zz)+parseFloat(formatulang(data[0].v_sjpb))));
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

    function hapusdetail(ibapb,iarea,isjpb) {
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
                        'isjpb'   : isjpb,
                    },
                    url: '<?= base_url($folder.'/cform/deletedetail'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $id.'/'.$iarea.'/'.$dfrom.'/'.$dto;?>','#main');     
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

// $("form").submit(function(event) {
//     event.preventDefault();
//     $("input").attr("disabled", true);
//     $("select").attr("disabled", true);
//     $("#submit").attr("disabled", true);
//     $("#addrow").attr("disabled", true);
// });
</script>