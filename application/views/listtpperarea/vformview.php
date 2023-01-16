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
                            <th>Target</th>
                            <th>Penjualan</th>
                            <th>%</th>
                            <th>Reguler</th>
                            <th>%</th>
                            <th>Baby</th>
                            <th>%</th>
                            <th>SPB</th>
                            <th>%</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th style="text-align: center;">GRAND TOTAL</th>
                        <th style="text-align: right;">Rp. <?= number_format($total->target);?></th>
                        <th style="text-align: right;">Rp. <?= number_format($total->penjualan);?></th>
                        <th></th>
                        <th style="text-align: right;">Rp. <?= number_format($total->reguler);?></th>
                        <th></th>
                        <th style="text-align: right;">Rp. <?= number_format($total->baby);?></th>
                        <th></th>
                        <th style="text-align: right;">Rp. <?= number_format($total->spb);?></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>
                <br>
                <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{ 
                "targets": [1,2,3,4,5,6,7,8,9], 
                "className": "text-right",
            }],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan; ?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    } );

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>