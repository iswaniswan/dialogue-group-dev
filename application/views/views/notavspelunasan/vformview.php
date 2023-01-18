<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a></div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>K-LANG</th>
		                    <th>NAMA LANG</th>
		                    <th>ALAMAT</th>
		                    <th>KS</th>
                            <th>Konsinyasi</th>
		                    <th>N.Nota</th>
                            <th>N.Sisa</th>
                            <th>N.Alokasi BM</th>
                            <th>N.Alokasi KN</th>
                            <th>N.Alokasi KN R</th>
                            <th>N.Alokasi HLL</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
	                    <tr>
                            <th colspan='5'>Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function () {
        var table = $('#tabledata').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var enamTotal = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 5 ).footer() ).html(enamTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var satuTotal = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 6 ).footer() ).html(satuTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var duaTotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 7 ).footer() ).html(duaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var tigaTotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column(8 ).footer() ).html(tigaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var empatTotal = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 9 ).footer() ).html(empatTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var limaTotal = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 10 ).footer() ).html(limaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                  
            },
            "language": {
                "decimal": ",",
                "thousands": "."
            },
            
            serverSide: true,
            processing: true,
            dom: "lBfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{
                "targets": [6],
                "className": "text-right",
            }],
            "ajax": {
                "url": base_url + "<?= $folder; ?>/Cform/data/<?= $bulan;?>/<?= $tahun;?>/<?= $iarea;?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });
</script>
