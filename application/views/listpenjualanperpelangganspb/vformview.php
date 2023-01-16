<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>K-LANG</th>
                            <th>NAMA LANG</th>
                            <th>ALAMAT</th>
                            <th>KOTA</th>
                            <th>KS</th>
                            <th>PENJUALAN</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th colspan="5" style="text-align: center;">GRAND TOTAL</th>
                        <!-- <th></th> -->
                        <th>Rp. <?= number_format($total->nilaispb);?></th>
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
            "columnDefs": [
            { "visible": false, "targets": 3 },
            { 

                "targets": 5,
                "className": "text-right",
            }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan."/".$iarea; ?>",
                "type": "POST"
            },
            "order": [[ 3, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;
                var subTotal = new Array();
                var groupID = -1;
                var aData = new Array();
                var index = 0;

                api.column(3, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="5"><b>'+group+'</b></td></tr>'
                            );

                        last = group;
                    }
                } );

                api.column(3, {page:'current'} ).data().each( function ( group, i ) {
                    var vals = api.row(api.row($(rows).eq(i)).index()).data();
                    var salary = vals[5] ? parseFloat(formatulang(vals[5])) : 0;
                    

                    if (typeof aData[group] == 'undefined') {
                        aData[group] = new Array();
                        aData[group].rows = [];
                        aData[group].salary = [];
                    }

                    aData[group].rows.push(i); 
                    aData[group].salary.push(salary); 

                } );


                var idx= 0;


                for(var office in aData){
                    idx =  Math.max.apply(Math,aData[office].rows);

                    var sum = 0; 
                    $.each(aData[office].salary,function(k,v){
                        sum = sum + v;
                    });
                    console.log(aData[office].salary);
                    $(rows).eq( idx ).after(
                        '<tr class="group"><td colspan="4" style="text-align: center;"><b>TOTAL '+office+' per pages</b></td>'+
                        '<td style="text-align: right"><b>Rp. '+formatcemua(sum)+'</b></td></tr><tr><td colspan="5">&nbsp;</td></tr>'
                        );

                };

            }
        } );

    } );
    /*$(document).ready(function () {
        var groupColumn = 3;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{ 
                "visible": false, 
                "targets": groupColumn,
            },
            { 

                "targets": 5,
                "className": "text-right",
            }],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan."/".$iarea; ?>",
                "type": "POST"
            },
            "order": [[ groupColumn, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api  = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last = null;
                var total= 0;
                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group) {
                        if (last==null) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="5"><b>'+group+'</b></td></tr>'
                                );    
                        }else{
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="4" style="text-align: center;"><b>TOTAL</b></td><td style="text-align: right;"><b>Rp. '+formatcemua(total)+'</b></td></tr><tr><td colspan="5">&nbsp;</td></tr><tr class="group"><td colspan="5"><b>'+group+'</b></td></tr>'
                                );
                        }
                        total = 0;
                        total = (parseInt(formatulang(api.column(5, {page:'current'} ).data()[i]))+parseInt(total));
                        last  = group;
                    }else{
                        total = (parseInt(formatulang(api.column(5, {page:'current'} ).data()[i]))+parseInt(total));

                    }
                } );
            }
        });
    });*/

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>