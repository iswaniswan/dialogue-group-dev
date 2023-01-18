<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Nomor PP</th>
                            <th>Tanggal PP</th>
                            <th>Tanggal OP</th>
                            <th>Gudang</th>                            
                            <th>Keterangan</th>
                            <th>Status Dokumen</th>
                            <th>Action</th>
                            <!-- <input type="hidden" id = "ipp "name="ipp" class="form-control date" 
                            value="<#?= $data->i_pp; ?>"> -->
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // $(document).ready(function () {
    //     datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom."/".$dto; ?>');
    // });
//     $(document).ready(function () {
// var table = $('#tabledata').DataTable();
 
// table.buttons( '.dt-buttons' ).remove();
//      });
    // $(document).ready(function () {
    // $('#tabledata').dataTable({
    // "rowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
    //     alert(aData);
    //     switch(aData[0]){
    //         case 'O':
    //             $(nRow).css('backgroundColor', 'yellow');
    //             break;
    //         }
    //     }
    // });
    // });

     function hapus(i_pp) {
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
                        'i_pp'  : i_pp
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/index/','#main');   
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

    function refreshview() {
        show('<?= $folder;?>/cform','#main');
    }

    function printx(b){
        var lebar =1024;
        var tinggi=768;
        eval('window.open("<?php echo site_url($folder); ?>"+"/cform/cetak/"+b,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>