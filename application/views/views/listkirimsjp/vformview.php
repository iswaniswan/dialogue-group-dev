<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                <thead>
                    <tr>
                        <th>Area</th>
                        <th>No SPB</th>
                        <th>Tgl SPB</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Pesan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary btn-rounded btn-sm" name="cmdreset" id="cmdreset"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>&nbsp;&nbsp;
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom.'/'.$dto.'/'.$iarea;?>');
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>