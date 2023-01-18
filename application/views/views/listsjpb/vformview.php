<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                <thead>
                    <tr>
                        <th>No SJ</th>
	                    <th>Tanggal SJ</th>
	                    <th>Lang</th>
	                    <th>Tanggal Terima</th>
                        <th>Nilai</th>
 	                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <br>
            <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto."/".$icustomer."/".$iarea; ?>",
                "type": "POST"
            },
        });
    });

    function hapus(isjpb,icustomer) {
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
                    'isjpb'  : isjpb,
                    'icustomer'  : icustomer
                },
                url: '<?= base_url($folder.'/cform/delete'); ?>',
                dataType: "json",
                success: function (data) {
                    swal("Dihapus!", "Data berhasil dihapus :)", "success");
                    show('<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$icustomer;?>',);   
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