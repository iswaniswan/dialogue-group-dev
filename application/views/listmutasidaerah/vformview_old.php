<?php 
$a=substr($tgl,6,2);
$b=substr($tgl,4,2);
$c=substr($tgl,0,4);
$periode=$a.' '.$this->fungsi->mbulan($b).' '.$c;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?> Periode <?= $periode;?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Grade</th>
                            <th>Group</th>
                            <th>Nama Barang</th>
                            <th>Saldo Awal</th>
                            <th>SJ</th>
                            <th>Konv +</th>
                            <th>Konv -</th>
                            <th>SJP</th>
                            <th>BBK</th>
                            <th>SJR</th>
                            <th>BB<</th>
                            <th>DO</th>
                            <th>BBMAP</th>
                            <th>SJBR</th>
                            <th>BBK Retur</th>
                            <th>Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <!-- <tfoot>
                        <th colspan="2" style="text-align: center;">GRAND TOTAL</th>
                        <th></th>
                        <th><?= number_format($total->totalqty);?></th>
                        <th></th>
                        <th>Rp. <?= number_format($total->totalnilaibeli);?></th>
                        <th></th>
                        <th>Rp. <?= number_format($total->totalnilaijual);?></th>
                    </tfoot> -->
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
        var sumsaldo    = 4;
        var sumsj       = 5;
        var sumColumn   = 7;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [
            { 
                "visible": false, 
                "targets": groupColumn 
            },
            { 
                "targets": [4,5,6,7,8,9,10,11,12,13,14,15,16], 
                "className": "text-right",
            }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tgl."/".$iperiode; ?>",
                "type": "POST"
            },
            "order": [[ groupColumn, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;
                var subTotal = new Array();
                var groupID = -1;
                var aData = new Array();
                var index = 0;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="16"><b>'+group+'</b></td></tr>'
                            );

                        last = group;
                    }
                } );

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    var vals    = api.row(api.row($(rows).eq(i)).index()).data();
                    var salary  = vals[sumColumn] ? parseFloat(formatulang(vals[sumColumn])) : 0;
                    var saldo   = vals[sumsaldo] ? parseFloat(formatulang(vals[sumsaldo])) : 0;
                    var sj      = vals[sumsj] ? parseFloat(formatulang(vals[sumsj])) : 0;

                    if (typeof aData[group] == 'undefined') {
                        aData[group] = new Array();
                        aData[group].rows   = [];
                        aData[group].salary = [];
                        aData[group].saldo  = [];
                        aData[group].sj     = [];
                    }

                    aData[group].rows.push(i); 
                    aData[group].salary.push(salary); 
                    aData[group].saldo.push(saldo); 
                    aData[group].sj.push(sj); 

                } );


                var idx= 0;


                for(var office in aData){
                    idx =  Math.max.apply(Math,aData[office].rows);

                    var qsaldo = 0;
                    $.each(aData[office].saldo,function(k,v){
                        qsaldo = qsaldo + v;
                    });

                    var qsj = 0;
                    $.each(aData[office].sj,function(k,v){
                        qsj = qsj + v;
                    });
                    
                    $(rows).eq( idx ).after(
                        '<tr class="group">'+
                        '<td colspan="3" style="text-align: center;"><b>TOTAL PER PAGE</b></td>'+
                        '<td style="text-align: right"><b>'+formatcemua(qsaldo)+'</b></td>'+
                        '<td style="text-align: right"><b>'+formatcemua(qsj)+'</b></td>'+
                        '</tr>'+
                        '<tr><td colspan="18">&nbsp;</td></tr>'
                    );

                };

            }
        } );

    } );

    /*$(document).ready(function() {
        var groupColumn = 2;
        var sumsaldo    = 4;
        var sumsj       = 5;
        var sumColumn   = 7;
        var sum         = [4,5,6,7,8,9,10,11,12,13,14,15,16];
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [
            { 
                "visible": false, 
                "targets": groupColumn 
            },
            { 
                "targets": [4,5,6,7,8,9,10,11,12,13,14,15,16], 
                "className": "text-right",
            }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tgl."/".$iperiode; ?>",
                "type": "POST"
            },
            "order": [[ groupColumn, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;
                var subTotal = new Array();
                var groupID = -1;
                var aData = new Array();
                var index = 0;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="16"><b>'+group+'</b></td></tr>'
                            );

                        last = group;
                    }
                } );

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    var vals    = api.row(api.row($(rows).eq(i)).index()).data();
                    var salary  = vals[sumColumn] ? parseFloat(formatulang(vals[sumColumn])) : 0;
                    var saldo   = 0;
                    for(var s=0; s<=13;s++){
                        saldo   = saldo + vals[sum[s]] ? parseFloat(formatulang(vals[sum[s]])) : 0;
                    var sj      = vals[sumsj] ? parseFloat(formatulang(vals[sumsj])) : 0;

                    if (typeof aData[group] == 'undefined') {
                        aData[group] = new Array();
                        aData[group].rows   = [];
                        aData[group].salary = [];
                        aData[group].saldo  = [];
                        aData[group].sj     = [];
                    }
                    console.log(saldo);
                    aData[group].rows.push(i); 
                    aData[group].salary.push(salary); 
                    aData[group].saldo.push(saldo); 
                    aData[group].sj.push(sj); 
                    }



                } );


                var idx= 0;


                for(var office in aData){
                    idx =  Math.max.apply(Math,aData[office].rows);

                    var qsaldo = 0;
                    $.each(aData[office].saldo,function(k,v){
                        alert(v);
                        qsaldo = qsaldo + v;
                    });

                    var qsj = 0;
                    $.each(aData[office].sj,function(k,v){
                        qsj = qsj + v;
                    });

                    var tbcontent = '';
                    for(var n=0;n<=12;n++){
                        var content = '<td style="text-align: right"><b>'+qsaldo+'</b></td>';
                        console.log(content);
                        tbcontent = tbcontent+content;
                    }
                    
                    $(rows).eq( idx ).after(
                        '<tr class="group">'+
                        '<td colspan="3" style="text-align: center;"><b>TOTAL PER PAGE</b></td>'+    
                        tbcontent+
                        '</tr>'
                        );

                    /*$(rows).eq( idx ).after(
                        '<tr class="group">'+
                        '<td colspan="3" style="text-align: center;"><b>TOTAL PER PAGE</b></td>'+
                        '<td style="text-align: right"><b>'+formatcemua(qsaldo)+'</b></td>'+
                        '<td style="text-align: right"><b>'+formatcemua(qsj)+'</b></td>'+
                        '</tr>'
                        );

                    };

                }
            } );

    } );*/

$( "#cmdreset" ).click(function() {  
    var Contents = $('#tabledata').html();    
    window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
});
</script>