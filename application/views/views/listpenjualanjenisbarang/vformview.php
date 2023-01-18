<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Group</th>
                            <th>Qty Jual</th>
                            <th>Harga Beli</th>
                            <th>Total Rp. Beli</th>
                            <th>Harga Jual</th>
                            <th>Total Rp. Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th colspan="2" style="text-align: center;">GRAND TOTAL</th>
                        <th></th>
                        <th><?= number_format($total->totalqty);?></th>
                        <th></th>
                        <th>Rp. <?= number_format($total->totalnilaibeli);?></th>
                        <th></th>
                        <th>Rp. <?= number_format($total->totalnilaijual);?></th>
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
        var sumqty      = 3;
        var sumjual     = 5;
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
                "targets": [3,4,5,6,7], 
                "className": "text-right",
            }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan; ?>",
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
                            '<tr class="group"><td colspan="7"><b>'+group+'</b></td></tr>'
                            );

                        last = group;
                    }
                } );

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    var vals    = api.row(api.row($(rows).eq(i)).index()).data();
                    var salary  = vals[sumColumn] ? parseFloat(formatulang(vals[sumColumn])) : 0;
                    var qty     = vals[sumqty] ? parseFloat(formatulang(vals[sumqty])) : 0;
                    var jual    = vals[sumjual] ? parseFloat(formatulang(vals[sumjual])) : 0;

                    if (typeof aData[group] == 'undefined') {
                        aData[group] = new Array();
                        aData[group].rows   = [];
                        aData[group].salary = [];
                        aData[group].qty    = [];
                        aData[group].jual   = [];
                    }

                    aData[group].rows.push(i); 
                    aData[group].salary.push(salary); 
                    aData[group].qty.push(qty); 
                    aData[group].jual.push(jual); 

                } );


                var idx= 0;


                for(var office in aData){
                    idx =  Math.max.apply(Math,aData[office].rows);

                    var vqty = 0;
                    $.each(aData[office].qty,function(k,v){
                        vqty = vqty + v;
                    });
                    
                    var sum = 0; 
                    $.each(aData[office].salary,function(k,v){
                        sum = sum + v;
                    });
                    
                    var cum = 0; 
                    $.each(aData[office].jual,function(k,v){
                        cum = cum + v;
                    });
                    $(rows).eq( idx ).after(
                        '<tr class="group"><td colspan="2" style="text-align: center;"><b>TOTAL PER PAGE</b></td><td style="text-align: right"><b>'+formatcemua(vqty)+'</b><td>&nbsp;</td><td style="text-align: right"><b>Rp. '+formatcemua(cum)+'</b></td><td>&nbsp;</td></td><td style="text-align: right"><b>Rp. '+formatcemua(sum)+'</b></td></tr><tr><td colspan="5">&nbsp;</td></tr>'
                        );

                };

            }
        } );

    } );

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>