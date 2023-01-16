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
                            <th>Nomor Alokasi</th>
                            <th>Tanggal</th>
                            <th>KN</th>
                            <th>Kode Cust</th>
                            <th>Nama Customer</th>
                            <th>Jumlah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
	                      <tr><th colspan="6" style="text-align: center;">Sub Total </th><th></th><th></th></tr>
	                      <tr>
                          <th colspan="6" style="text-align: center;">Grand Total </th>
                          <th><?= number_format($total->jml);?></th><th></th>
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
            var enamTotal = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 5 ).footer() ).html('Sub Total');
                  $( api.column( 6 ).footer() ).html(enamTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
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
                "url": base_url + "<?= $folder; ?>/Cform/data/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });

    
</script>
