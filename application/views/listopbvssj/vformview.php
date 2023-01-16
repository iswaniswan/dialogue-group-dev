<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>No Order</th>
							<th>Tgl Order</th>
							<th>Pelanggan</th>
							<th>SPC</th>
							<th>Jumlah Order</th>
							<th>SPMB</th>
							<th>Tgl SPMB</th>
							<th>SJ</th>
							<th>Tgl SJ</th>
							<th>Jumlah Kirim</th>
							<th>Pemenuhan</th>
							<th>Tgl SJ Receive</th>
							<th>No BAPB</th>
							<th>Tgl BAPB</th>
							<th>Order > SPMB</th>
							<th>SPMB > SJ</th>
							<th>SJ > BAPB</th>
							<th>BAPB > SJ Receive</th>
							<th>Order > SJ Receive</th>
                            <th>Status Batal</th>
							<th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        var tabel = $('#tabledata').dataTable( {
            "columnDefs": [{
                "targets": [ 1, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18 ],
                "className": "text-center",
            },
            {
                "targets": [ 10 ],
                "className": "text-right",
            }],
            "ajax": {
                "url": base_url + "<?php echo $folder; ?>/Cform/data/<?php echo $dfrom;?>/<?php echo $dto;?>",
                "type": "POST"
            },
        });
    });

    function cancel(iorderpb,icustomer) {
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
                        'iorderpb' : iorderpb,
                        'icustomer': icustomer
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/view/<?= $dfrom."/".$dto;?>','#main');     
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