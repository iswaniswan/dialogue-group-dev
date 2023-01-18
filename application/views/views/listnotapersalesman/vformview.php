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
                            <th colspan=7>Total SJ   : <?= number_format($cetak->v_sj);?></th>
                        </tr>
                        <tr>
                            <th colspan=7>Total Nota : <?= number_format($cetak->v_nota);?></th>
                        </tr>
                        <tr>
                            <th>Periode Awal</th>
			                <th>Periode Akhir</th>
			                <th>Area</th>
			                <th>Kd Sales</th>
			                <th>Salesman</th>
			                <th>Nilai SJ</th>
			                <th>Nilai Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
                            <th style="text-align: center;" colspan="5">TOTAL</th>
                            <th><?= number_format($total->totalsj);?></th>
                            <th><?= number_format($total->totalnota);?></th>
                        </tr>
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
 /*           var tigaTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var empatTotal = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var limaTotal = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
*/            var enamTotal = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 3 ).footer() ).html('Total');
   /*
          $( api.column( 4 ).footer() ).html(tigaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 5 ).footer() ).html(empatTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
          $( api.column( 6 ).footer() ).html(limaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
    */        $( api.column( 6 ).footer() ).html(enamTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            },
            "language": {
                "decimal": ",",
                "thousands": "."
            },
            
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{
                "targets": [5,6],
                "className": "text-right",
            }],
            "ajax": {
                "url": base_url + "<?php echo $folder; ?>/Cform/data/<?php echo $dfrom;?>/<?php echo $dto;?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });

    
</script>
