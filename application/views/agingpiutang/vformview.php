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
                            <th>Tanggal</th>
                            <th>Nomor Nota</th>
                            <th>Customer</th>
                            <th>Salesman</th>
                            <th>Tanggal JT</th>
                            <!--<th>Jumlah</th>-->
                            <th>Belum JT</th>
                            <th>1-7</th>
                            <th>8-30</th>
                            <th>>30</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
	                      <tr><th colspan="5" style="text-align: center;">Sub Total </th><th></th><th></th><th></th><th></th></tr>
	                      <tr>
                          <th colspan="5" style="text-align: center;">Grand Total </th>
                          <th><?= number_format($total->blmjt);?></th>
                          <th><?= number_format($total->tujuh);?></th>
                          <th><?= number_format($total->tiga);?></th>
                          <th><?= number_format($total->lebih);?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        /*datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>/');*/
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
        
            var enamTotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
            var tujuhTotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 4 ).footer() ).html('Sub Total');
                  $( api.column( 5 ).footer() ).html(empatTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                  $( api.column( 6 ).footer() ).html(limaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                  $( api.column( 7 ).footer() ).html(enamTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                  $( api.column( 8 ).footer() ).html(tujuhTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            },
            "language": {
                "decimal": ",",
                "thousands": "."
            },
            
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{
                "targets": [5,6,7,8],
                "className": "text-right",
            }],
            "ajax": {
                "url": base_url + "<?= $folder; ?>/Cform/data/<?= $dfrom;?>/<?= $dto;?>/",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });

    
</script>
