<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Departement</label>  
                        <label class="col-md-6">Level</label> 
                        <div class="col-sm-6">
                            <select name="idept" id="idept" class="form-control select2" disabled="true">
                            <option value="<?= $data->i_departement;?>"><?= $data->e_departement_name;?></option>
                                <?php foreach ($depart as $idept):?>
                                <option value="<?php echo $idept->i_departement;?>">
                                    <?= $idept->i_departement." - ".$idept->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="ilevel" id="ilevel" class="form-control select2" disabled="true">
                            <option value="<?= $data->i_level;?>"><?= $data->e_level_name;?></option>
                                <?php foreach ($level as $ilevel):?>
                                <option value="<?php echo $ilevel->i_level;?>">
                                    <?= $ilevel->i_level." - ".$ilevel->e_level_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Kode Menu</th>
                            <th width="20%">Nama Menu</th>
                            <th width="15%">User Power</th>
                            <th width="17%">Departement</th>                            
                            <th width="18%">Level</th>
                            <th width="15%">action</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?$i = 0;
                        foreach ($data2 as $row) {
                        $i++;?>
                        <tr>
                            <td><?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="imenu<?=$i;?>" name="imenu<?=$i;?>"value="<?= $row->i_menu; ?>"  readonly >
                            </td>
                            <td>
                                <input type="text" id="emenu<?=$i;?>" name="emenu<?=$i;?>"value="<?= $row->e_menu; ?>" class="form-control" readonly >
                            </td>                   
                            <td>
                                <input class="form-control" type="hidden" id="power<?=$i;?>" name="power<?=$i;?>"value="<?= $row->id_user_power; ?>" >
                                <input class="form-control" type="text" id="epower<?=$i;?>" name="epower<?=$i;?>"value="<?= $row->e_name; ?>" readonly>
                            </td>
                            <td>
                                <input class="form-control" type="hidden" id="idept<?=$i;?>" name="idept<?=$i;?>"value="<?= $row->i_departement; ?>" >
                                <input class="form-control" type="text" id="edept<?=$i;?>" name="edept<?=$i;?>"value="<?= $row->e_departement_name; ?>" readonly >
                            </td>
                            <td>
                                <input class="form-control" type="hidden" id="ilevel<?=$i;?>" name="ilevel<?=$i;?>"value="<?= $row->i_level; ?>" >
                                <input class="form-control" type="elevel" id="epower<?=$i;?>" name="epower<?=$i;?>"value="<?= $row->e_level_name; ?>" readonly >
                            </td>
                            <td style="text-align: center;">
                            <?
                            if($row->i_apps == '2'){?>
                                    <button type="button" onclick="hapusdetail('<?=$i?>');" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    
                                    
                            </td>
                            <?}?>
                        </tr>
                        <?}?>
                        <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>"> 
                    </tbody>          
                    </table>
                    
                </div>
          
            </form>
            <!-- </div>
            </div> -->
        </div>
    </div>
</div>

<script>
    // function formatSelection(val) {
    //     return val.name;
    // }

    // var demo1 = $('select[name="duallistbox_demo1[]"]').bootstrapDualListbox();
    // $("#demoform").submit(function() {
    //   alert($('[name="duallistbox_demo1[]"]').val());
    //   return false;
    // });

    // var demo2 = $('.demo2').bootstrapDualListbox({
    //       nonSelectedListLabel: 'Non-selected',
    //       selectedListLabel: 'Selected',
    //       preserveSelectionOnMove: 'moved',
    //       moveOnSelect: false,
    //       nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
    //     });

    // $(document).ready(function () {
    //     $('.select2').select2();
    //     showCalendar('.date');
    // });

    function hapusdetail(id){
        var imenu = $('#imenu'+id).val();
        var userpower = $('#power'+id).val();
        var dept = $('#idept'+id).val();
        var level = $('#ilevel'+id).val();
        swal({
            title: "Apakah anda yakin ?", 
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
        },  function(isConfirm){
            console.log(isConfirm);
                if (isConfirm) { 
                    
                    $.ajax({
                        type : "post",
                        data : {
                        'imenu'         : imenu,
                        'iuserpower'    : userpower,
                        'ilevel'        : level,
                        'idept'         : dept
                        },
                        url: '<?= base_url($folder.'/cform/deletedetail'); ?>',
                        dataType : "json",
                        success : function(data){
                            swal("Dihapus!", "Data berhasil dihapus :)", "success");
                            show('<?= $folder;?>/cform/edit/<?= $ilevel.'/'.$idept.'/'.$icompany.'/'.$iapps;?>','#main');     
                        },
                                error: function () {
                                    swal("Maaf", "Data gagal dihapus :(", "error");
                                }
                    });
                }else{
                    swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
                }
            }
        );  
    }

//     function hapusdetail2(imenu,userpower,dept,level) {
//     swal({   
//         title: "Apakah anda yakin ?",   
//         text: "Anda tidak akan dapat memulihkan data ini!",   
//         type: "warning",   
//         showCancelButton: true,   
//         confirmButtonColor: "#DD6B55",   
//         confirmButtonText: "Ya, hapus!",   
//         cancelButtonText: "Tidak, batalkan!",   
//         closeOnConfirm: false,   
//         closeOnCancel: false 
//     }, function(isConfirm){   
//         if (isConfirm) { 
//             $.ajax({
//                 type: "post",
//                 data: {
//                     'imenu'         : imenu,
//                     'iuserpower'    : userpower,
//                     'ilevel'        : level,
//                     'idept'         : dept
//                 },
//                 url: '<#?= base_url($folder.'/cform/deletedetail'); ?>',
//                 dataType: "json",
//                 success: function (data) {
//                     swal("Dihapus!", "Data berhasil dihapus :)", "success");
//                     show('<#?= $folder;?>/cform/edit/<#?//= $ilevel.'/'.$idept.'/'.$icompany.'/'.$iapps;?>','#main');     
//                 },
//                 // $ilevel
//                 // $idept
//                 // $icompany
//                 // $iapps
//                 error: function () {
//                     swal("Maaf", "Data gagal dihapus :(", "error");
//                 }
//             });
//         } else {     
//             swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
//         } 
//     });
// }
</script>