<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>Kode Area</th>
                            <th>Area</th>
                            <th>Group</th>
                            <th>Jumlah(BRUTO) Rp.</th>
                            <th>Jumlah(NETTO) Rp.</th>
                            <th>Qty SPB</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th colspan="3" style="text-align: center;">Grand Total :</th>
                        <th>Rp. <?= number_format($total->jumlah);?></th>
                        <th>Rp. <?= number_format($total->netto);?></th>
                        <th><?= number_format($total->n_spb);?></th>
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
            "columnDefs": [{ 
                "visible": false, 
                "targets": groupColumn,
            },
            {
                "targets": 2,
                "className": "text-right",
            }],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto; ?>",
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
                            '<tr class="group"><td colspan="5" bgcolor="grey"><font color="white"><b>'+group+'</b></font></td></tr>'
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
</script>