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
                            <th>No SJ</th>
                            <th>Tgl SJ</th>
                            <th>Pelanggan</th>
                            <th>Area</th>
                            <th>DKB</th>
                            <th>Tgl DKB</th>
                            <th>BAPB</th>
                            <th>Tgl BAPB</th>
                            <th>Nota</th>
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
        $(document).ready(function () {
            datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom."/".$dto."/".$iarea; ?>');
        });
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>