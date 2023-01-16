<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="grid" cellspacing="0" width="100%" id="tabledata">
                <thead>
                    <tr>
                        <th>Kode</th>
		                <th>Nama Barang</th>
                        <th>group</th>
                        <th>Qty Penjualan</th>
                        <th>Qty Sisa Saldo</th>
                        <th>Qty Git Stock </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <th colspan="3" style="text-align: center;">Grand Total :</th>
                    <th><?= number_format($total->jumlah);?></th>
                    <th><?= number_format($total->sisasaldo);?></th>
                    <th><?= number_format($total->git);?></th>
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
        var groupColumn = 2;
        var table = $('.grid').not('.initialized').addClass('initialized').show().DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [
                { "visible": false, "targets": groupColumn }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $bulan."/".$tahun."/".$iarea; ?>",
                "type": "POST"
            },
            "order": [[ groupColumn, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;
                var colonne = api.row(0).data().length;
                var totale = new Array();
                totale['Totale']= new Array();
                var groupid = -1;
                var subtotale = new Array();

                
                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {     
                    if ( last !== group ) {
                        groupid++;
                        $(rows).eq( i ).before(
                            '<tr class="group group-start"><td colspan="2" bgcolor="grey"><font color="white"><b>'+group+'</b></font></td></tr>'
                        );
                        last = group;
                    }


                    val = api.row(api.row($(rows).eq( i )).index()).data();      //current order index
                    $.each(val,function(index2,val2){
                            if (typeof subtotale[groupid] =='undefined'){
                                subtotale[groupid] = new Array();
                            }
                            if (typeof subtotale[groupid][index2] =='undefined'){
                                subtotale[groupid][index2] = 0;
                            }
                            if (typeof totale['Totale'][index2] =='undefined'){ 
                                totale['Totale'][index2] = 0; 
                            }

                            valore = Number(val2.replace('€',"").replace('.',"").replace(',',"."));
                            subtotale[groupid][index2] += valore;
                            totale['Totale'][index2] += valore;
                    });   
                } );                
		        $('tbody').find('.group').each(function (i,v) {
                    var rowCount = $(this).nextUntil('.group').length;
                    $(this).find('td:first').append($('<span />', { 'class': 'rowCount-grid' }).append($('<b />', { 'text': ' ('+rowCount+')' })));
                             var subtd = '';
                            for (var a=3;a<colonne;a++){ 
                                subtd += '<td class="group group-end" bgcolor="grey"><font color="white"><b>'+(formatNumber(subtotale[i][a]))+'</td>';
                            }
                            $(this).append(subtd);
                });
	        }
        } );
        // Order by the grouping
        $('.grid tbody').on( 'click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 0 && currentOrder[1] === 'asc' ) {
                table.order( [ 0, 'desc' ] ).draw();
            }
            else {
                table.order( [ 0, 'asc' ] ).draw();
            }
        });
    } );

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });   

    
</script>
