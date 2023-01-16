<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                <thead>
                    <tr>
                        <th>Kode Pelanggan</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Kode Area</th>
                        <th>Area</th>
                        <th>Kota</th>
                        <th>No SPB</th>
                        <th>Tgl SPB</th>
                        <th>Tgl App SPB</th>
                        <th>SPB->APP(Hari)</th>
                        <th>No SJ</th>
                        <th>Tgl SJ</th>
                        <th>SPB->SJ(Hari)</th>
                        <th>No Nota</th>
                        <th>Tgl Nota</th>
                        <th>SJ->Nota(Hari)</th>
                        <th>SPB->Nota(Hari)</th>
                        <th>Tgl Terima Toko</th>
                        <th>Nota->Terima Toko(Hari)</th>
                        <th>SPB->Terima Toko(Hari)</th>
                        <th>Nilai Penjualan</th>
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
        var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });   

    
</script>