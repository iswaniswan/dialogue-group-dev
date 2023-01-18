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
                            <?php 
                                if($isi){
                            		$xx=0;
                            		foreach($isi as $raw){
                            			$xx++;
                            			if($xx<2){
                            				echo "<th colspan=10>$raw->i_customer - $raw->e_customer_name</th>";
                            			}
                            		}
                            	}
                            ?>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Kode Sales</th>
	                        <th>No Nota</th>
		                    <th>Tgl Nota</th>
		                    <th>No Seri Pajak</th>
		                    <th>Tgl Pajak</th>
		                    <th>Kode</th>
		                    <th>Nama</th>
		                    <th>Jumlah</th>
		                    <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
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
            $( api.column( 3 ).footer() ).html('Total Semua');
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
                "url": base_url + "<?php echo $folder; ?>/Cform/data/<?php echo $icustomer;?>/<?php echo $iproduct;?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });

    
</script>
