<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> 
                <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                    class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="2%">No</th>
                            <th>Username</th>
                            <th>Nama Perusahaan</th>
                            <th>Departement</th>
                            <th>Level</th>      
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
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data');
    });

    function hapus(username,ilevel,idept,idcompany) {
        swal({   
            title: "Apakah anda yakin?",   
            text: "Hapus Data!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, Hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "POST",
                    data: {
                        'username'  : username,
                        'ilevel' : ilevel,
                        'idept' : idept,
                        'idcompany' : idcompany,
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Sukses!", "Data berhasil di hapus :)", "success");
                        show('<?= $folder;?>/cform/','#main');
                    },
                    error: function () {
                        swal("Maaf", "Data gagal di hapus :(", "error");
                    }
                });
            }else {     
                swal("Dibatalkan", "Anda membatalkan hapus Data :)", "error");
            } 
        });
    }
</script>