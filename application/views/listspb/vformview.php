<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                <thead>
                    <tr>
                        <th>No SPB</th>
                        <th>Tgl SPB</th>
                        <th>Group</th>
                        <th>App Sls</th>
                        <th>App Ar</th>
                        <th>Sls</th>
                        <th>Customer</th>
                        <th>Area</th>
                        <th>SPB (Rp)</th>
                        <th>Nota (Rp)</th>
                        <th>Pending (Rp)</th>
                        <th>%</th>
                        <th>Stat</th>
                        <th>SJ</th>
                        <th>Tgl SJ</th>
                        <th>Tgl DKB</th>
                        <th>Nota</th>
                        <th>Tgl Nota</th>
                        <th>Tgl Trm Tk</th>
                        <th>Daerah</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <th colspan="8" style="text-align: center;">Grand Total :</th>
                    <th>Rp. <?= number_format($total->nilaispb);?></th>
                    <th>Rp. <?= number_format($total->nilainota);?></th>
                    <th>Rp. <?= number_format($total->nilaipending);?></th>
                    <th colspan="10"></th>
                </tfoot>
            </table>
            <br>
            <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [
            { "visible": false, "targets": groupColumn }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto."/".$iarea; ?>",
                "type": "POST"
            },
            "order": [[ groupColumn, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api  = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last = null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="21" bgcolor="grey"><font color="white"><b>'+group+'</b></font></td></tr>'
                            );

                        last = group;
                    }
                } );
            }
        });
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });   

    function cancel(ispb,iarea) {
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
                        'ispb' : ispb,
                        'iarea': iarea
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/view/<?= $iarea."/".$dfrom."/".$dto;?>','#main');     
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

    function balik(ispb,iarea) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Data ini akan dikembalikan ke status sales?",
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, kembalikan!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ispb' : ispb,
                        'iarea': iarea
                    },
                    url: '<?= base_url($folder.'/cform/balik'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dikembalikan!", "Data berhasil dikembalikan ke status sales :)", "success");
                        show('<?= $folder;?>/cform/view/<?= $iarea."/".$dfrom."/".$dto;?>','#main');   
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dikembalikan :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan pengembalian :)", "error");
            } 
        });
    }
</script>