<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0"><?= strtoupper($title);?></h3>
                    <p class="text-muted m-b-30">Periode <?= $this->fungsi->mbulan($bulan)." ".$tahun;?></p>
                    <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Beli</th>
                                <th class="text-center">Opname</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($isi) {
                                $no = 0;
                                $grandtotal=0;
                                $gtso=0;
                                $gtbeli=0;
                                foreach ($isi->result() as $row) {
                                    $no++;
                                    $total=$row->n_opname_total*$row->v_harga;
                                    $grandtotal=$grandtotal+$total;
                                    $gtso=$gtso+$row->n_opname_total;
                                    $gtbeli=$gtbeli+$row->n_beli;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no;?></td>
                                        <td class="text-left"><?= $row->i_product;?></td>
                                        <td class="text-left"><?= $row->e_product_name;?></td>
                                        <td class="text-right"><?= number_format($row->n_beli);?></td>
                                        <td class="text-right"><?= number_format($row->n_opname_total);?></td>
                                        <td class="text-right"><?= number_format($row->v_harga);?></td>
                                        <td class="text-right"><?= number_format($total);?></td>
                                    </tr>
                                    <?php 
                                } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th class="text-right"><?= $gtbeli;?></th>
                                    <th class="text-right"><?= $gtso;?></th>
                                    <th></th>
                                    <th class="text-right"><?= $grandtotal;?></th>
                                </tr>
                            </tfoot>
                        <?php } ?>
                        <tr>
                        </tr>
                    </table>
                    <br>
                    <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#clmtable').bootstrapTable('destroy').bootstrapTable();
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#clmtable').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#clmtable').html()) +  '</table>' );
    }); 
</script>