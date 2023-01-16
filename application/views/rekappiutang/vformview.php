<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a></div>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
			                <th style="text-align: center;" rowspan="2">Area</th>
			                <th style="text-align: center;" rowspan="2">Saldo Awal</th>
			                <th style="text-align: center;" colspan="3">Penjualan</th>
			                <th style="text-align: center;" colspan="3">Retur Jual</th>
                            <th style="text-align: center;" rowspan="2">Penjualan Netto</th>
                            <th style="text-align: center;" rowspan="2">Alokasi BM</th>
							<th style="text-align: center;" rowspan="2">Alokasi KNR</th>
							<th style="text-align: center;" rowspan="2">Alokasi KN</th>
							<th style="text-align: center;" rowspan="2">Alokasi HLL</th>
							<th style="text-align: center;" rowspan="2">Pembulatan</th>
							<th style="text-align: center;" rowspan="2">Saldo Akhir</th>
                        </tr>
						<tr>
							<th>DPP</th>
							<th>PPN</th>
							<th>Sub Total</th>
							<th>DPP</th>
							<th>PPN</th>
							<th>Sub Total</th>
						</tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
	                      <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
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
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 6 ).footer() ).html(enamTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var satuTotal = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 1 ).footer() ).html(satuTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var duaTotal = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 2 ).footer() ).html(duaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var tigaTotal = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 3 ).footer() ).html(tigaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var empatTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 4 ).footer() ).html(empatTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var limaTotal = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 5 ).footer() ).html(limaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var tujuhTotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 7 ).footer() ).html(tujuhTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));


            var delapanTotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 8 ).footer() ).html(delapanTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var sembilanTotal = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 9 ).footer() ).html(sembilanTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var sepuluhTotal = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 10 ).footer() ).html(sepuluhTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var sebelasTotal = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 11 ).footer() ).html(sebelasTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var duabelasTotal = api
                .column( 12 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 12 ).footer() ).html(duabelasTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var tigabelasTotal = api
                .column( 13 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 13 ).footer() ).html(tigabelasTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var empatbelasTotal = api
                .column( 14 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
                  $( api.column( 14 ).footer() ).html(empatbelasTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                  
            },
            "language": {
                "decimal": ",",
                "thousands": "."
            },
            
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{
                "targets": [6],
                "className": "text-right",
            }],
            "ajax": {
                "url": base_url + "<?= $folder; ?>/Cform/data/<?= $tahun;?>/<?= $bulan;?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });
</script>