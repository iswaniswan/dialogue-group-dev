<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>No SJ</th>
                            <th>No SPB</th>
                            <th>No DKB</th>
                            <th>Tgl DKB</th>
                            <th>Pelanggan</th>
                            <th>Tgl SJ</th>
                            <th>Area</th>
                            <th>Jumlah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
                <br>
            </div>
        </div>
    </div>
</div>

<script>
$( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });   
    
$(document).ready(function() {
    var table = $('#tabledata').DataTable({
        serverSide: true,
        processing: true,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "columnDefs": [],
        "ajax": {
            "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto."/".$iarea; ?>",
            "type": "POST"
        },
        "displayLength": 10,
    });
});
</script>