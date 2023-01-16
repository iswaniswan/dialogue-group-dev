<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No PP</th>
                            <th>Tanggal PP</th>
                            <th>No OP</th>
                            <th>Supplier</th>
                            <!-- <th>Kode Material</th>
                            <th>Nama Material</th>
                            <th>Satuan</th>
                            <th>Qty PP</th>
                            <th>Qty OP</th> -->
                            <th>% Realisasi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <br>
                <!-- <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button> -->
                <input type = "hidden" id = "dfrom" name = "dfrom" value = "<?= $dfrom ;?>" readonly>
                <input type = "hidden" id = "dto" name = "dto" value = "<?= $dto ;?>" readonly>
                <input type = "hidden" id = "gudang" name = "gudang" value = "<?= $gudang ;?>" readonly>
                <a href="#" id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export Excel</button></a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        //var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto."/".$gudang; ?>",
                "type": "POST"
            },
            "displayLength": 10,

            "columnDefs": [ {
            type: 'percent',
            "searchable": false,
            "orderable": false,
            "targets": 0
            } ],

            "order": [[ 1, 'asc' ]]
        });

        table.on( 'draw.dt', function () {
            var PageInfo = $('#tabledata').DataTable().page.info();
            table.column(0, {page: 'current'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1+PageInfo.start;
            } );
        } );
        
    });

    $('#example').dataTable( {
     columnDefs: [
       { type: 'percent', targets: 0 }
     ]
  } );

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });

    $("#href").click(function() {
        var dfrom = $('#dfrom').val();
        var dto   = $('#dto').val();
        var gudang= $('#gudang').val();
        if (dfrom=='' || dto=='') {
            swal('Isi form yang masih kosong!');
            return false;
        }
        var abc = "<?= site_url($folder.'/cform/export/'); ?>"+dfrom+'/'+dto+'/'+gudang;
        $("#href").attr("href",abc);
    });
</script>