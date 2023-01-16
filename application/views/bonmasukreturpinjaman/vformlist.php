<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
               <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/proses/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title_list; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No Dokumen</th> 
                            <th>Tanggal Dokumen</th>
                            <th>Customer </th>
                            <th>No Referensi</th>                            
                            <th>Keterangan</th>  
                            <th>Status</th>                                   
                            <th>Action</th>
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
$(document).ready(function () {
    datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data');
});

function hapus(isj) {
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
                    'isj'  : isj
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
</script>