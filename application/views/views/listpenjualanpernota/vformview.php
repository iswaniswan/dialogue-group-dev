<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata" cellpadding="0">
                    <thead>
                        <tr>
                            <th>K-LANG</th>
                            <th>NAMA LANG</th>
                            <th>NO. NOTA</th>
                            <th>TGL NOTA</th>
                            <th>TGL JTH TEMPO</th>
                            <th>KOTA</th>
                            <th>KS</th>
                            <th>JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th colspan="7" style="text-align: center;">GRAND TOTAL</th>
                        <th>Rp. <?= number_format($total->jumlah);?></th>
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
            /*"rowGroup": {
                "dataSrc": [1]
            },*/
            "columnDefs": [{ 
                "visible": false, 
                "targets": [1] 
            },
            { 
                "targets": 7,
                "className": "text-right",
            }],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan."/".$iarea; ?>",
                "type": "POST"
            },
            "order": [1, 'asc'],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api      = this.api();
                var rows     = api.rows( {page:'current'} ).nodes();
                var last     = null;
                var subTotal = new Array();
                var groupID  = -1;
                var aData    = new Array();
                var index    = 0;

                api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="7"><b>'+group+'</b></td></tr>'
                            );

                        last = group;
                    }
                } );

                api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                    var vals   = api.row(api.row($(rows).eq(i)).index()).data();
                    var netto  = vals[7] ? parseFloat(formatulang(vals[7])) : 0;

                    if (typeof aData[group] == 'undefined') {
                        aData[group]        = new Array();
                        aData[group].rows   = [];
                        aData[group].netto  = [];
                    }

                    aData[group].rows.push(i); 
                    aData[group].netto.push(netto); 

                } );

                var idx= 0;

                for(var office in aData){
                    idx =  Math.max.apply(Math,aData[office].rows);

                    var nota = 0; 
                    $.each(aData[office].netto,function(k,v){
                        nota = nota + v;
                    });

                    $(rows).eq( idx ).after(
                        '<tr class="group">'+
                        '<td colspan="6" style="text-align: center;"><b>TOTAL '+office+' per pages</b></td>'+
                        '<td style="text-align: right"><b>Rp. '+formatcemua(nota)+'</b></td>'+
                        '</tr><tr><td colspan="7">&nbsp;</td>'+
                        '</tr>'
                        );
                };

            }
        } );

    } );

    /*$(document).ready(function() {
        $('#tabledata').DataTable( {
            order: [[4, 'asc'], [1, 'asc']],
            rowGroup: {
                dataSrc: [ 4, 1 ]
            },
            columnDefs: [ {
                targets: [ 1, 4 ],
                visible: false
            } ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan."/".$iarea; ?>",
                "type": "POST"
            },
        } );
    } );*/

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>