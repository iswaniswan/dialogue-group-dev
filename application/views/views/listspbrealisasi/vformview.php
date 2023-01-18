<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>Group</th>
                            <th>Area</th>
                            <th>Kota</th>
                            <th>SPB</th>
                            <th>Promo</th>
                            <th>Tanggal SPB</th>
                            <th>Cust</th>
                            <th>sls</th>
                            <th>Sub Kategori</th>
                            <th>Kode</th>
                            <th>Nama Product</th>
                            <th>Pesan</th>
                            <th>Kirim</th>
                            <th>Harga</th>
                            <th>SJ</th>
                            <th>Nota</th>
                            <th>Tgl Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                <!-- <tfoot>
                    <th colspan="8" style="text-align: center;">Grand Total :</th>
                    <th>Rp. <?= number_format($total->nilaispb);?></th>
                    <th>Rp. <?= number_format($total->nilainota);?></th>
                    <th>Rp. <?= number_format($total->nilaipending);?></th>
                    <th colspan="10"></th>
                </tfoot> -->
            </table>
            <br>
            <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        var groupColumn = 0;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [
            { "visible": false, "targets": groupColumn }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto."/".$iarea; ?>",
                "type": "POST"
            },
            "order": [[ groupColumn, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api  = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last = null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="21" bgcolor="grey"><font color="white"><b>'+group+'</b></font></td></tr>'
                            );

                        last = group;
                    }
                } );
            }
        });
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });   
</script>