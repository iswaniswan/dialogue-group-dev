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
                            <th>Area</th>
                            <th>Pelanggan</th>
                            <th>Sld Awal</th>
                            <th>Penjualan</th>
                            <th>Alokasi BM</th>
                            <th>Alokasi KN Retur</th>
                            <th>Alokasi KN non Retur</th>
                            <th>Alokasi Hutang lain-lain</th>
                            <th>Pembulatan</th>
                            <th>Sld Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
	                      <tr><th colspan="2" style="text-align: center;">Sub Total </th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        /*datatable('#tabledata', base_url + '<?php #echo $folder; ?>/Cform/data/<?php #echo $dfrom;?>/<?php #echo $dto;?>/<?php #echo $iarea;?>/');*/
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
            var duaTotal = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var tigaTotal = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var empatTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var limaTotal = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var enamTotal = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var tujuhTotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var delapanTotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var sembilanTotal = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 1 ).footer() ).html('Sub Total');
   
          $( api.column( 2 ).footer() ).html(duaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 3 ).footer() ).html(tigaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 4 ).footer() ).html(empatTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 5 ).footer() ).html(limaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 6 ).footer() ).html(enamTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 7 ).footer() ).html(tujuhTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 8 ).footer() ).html(delapanTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 9 ).footer() ).html(sembilanTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            },
            "language": {
                "decimal": ",",
                "thousands": "."
            },
            
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{
                "targets": [2,3,4,5,6,7,8,9],
                "className": "text-right",
            }],
            "ajax": {
                "url": base_url + "<?php echo $folder; ?>/Cform/data/<?php echo $tahun;?>/<?php echo $bulan;?>/<?php echo $iarea;?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });

    
</script>
