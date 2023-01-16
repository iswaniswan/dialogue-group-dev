<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <!--             <div class="panel-body table-responsive"> -->
                <div class="col-sm-12">
                    <div class="white-box">
                        <?php 
                        $a=substr($tgl,8,2);
                        $b=substr($tgl,5,2);
                        $c=substr($tgl,0,4);
                        $periode=$a.' '.mbulan($b).' '.$c;
                        $periode1 = $c.$b;
                        ?>
                        <h3 class="box-title m-b-0">LAPORAN STOCK ALL AREA</h3>
                        <p class="text-muted m-b-30">Periode <?= $periode;?></p>
                        <!-- <table data-toggle="table" data-show-columns="true" id="clmtable" data-sort-name="kode" data-height="500" data-mobile-responsive="true" data-sort-order="asc" class="table"> -->
                        <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Nama</th>
                                    <?php if ($store) {
                                        foreach ($store as $key) {?>
                                            <th class="text-center"><?= $key->i_store;?></th>
                                        <?php }
                                    }?>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($isi) {
                                    $no = 0;
                                    foreach ($isi as $row) {
                                        $no++;
                                        if($periode1 == '201901'){
                                            $row->tilubelas = 0;
                                        }
                                        $total=$row->hiji+$row->dua+$row->tilu+$row->opat+$row->lima+$row->tujuh+$row->salapan+$row->sapuluh+$row->sabelas+$row->duabelas+$row->tilubelas+$row->tujuhbelas+$row->duatilu+$row->tiluhiji+$row->aa+$row->pb;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no;?></td>
                                            <td class="text-left"><?= $row->i_product;?></td>
                                            <td class="text-left"><?= $row->e_product_name;?></td>
                                            <td class="text-right"><?= $row->hiji;?></td>
                                            <td class="text-right"><?= $row->dua;?></td>
                                            <td class="text-right"><?= $row->tilu;?></td>
                                            <td class="text-right"><?= $row->opat;?></td>
                                            <td class="text-right"><?= $row->lima;?></td>
                                            <td class="text-right"><?= $row->tujuh;?></td>
                                            <td class="text-right"><?= $row->salapan;?></td>
                                            <td class="text-right"><?= $row->sapuluh;?></td>
                                            <td class="text-right"><?= $row->sabelas;?></td>
                                            <td class="text-right"><?= $row->duabelas;?></td>
                                            <?php if ($periode1 >= '201901') {?>
                                                <td class="text-right"><?= $row->tilubelas;?></td>
                                            <?php }?>
                                            <td class="text-right"><?= $row->tujuhbelas;?></td>
                                            <td class="text-right"><?= $row->duatilu;?></td>
                                            <td class="text-right"><?= $row->tiluhiji;?></td>
                                            <td class="text-right"><?= $row->aa;?></td>
                                            <td class="text-right"><?= $row->pb;?></td>
                                            <td class="text-right"><?= $total;?></td>
                                        </tr>
                                    <?php }
                                }?>
                                <tr>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js"></script>
    <script type="text/javascript">
    /*function buildTable($el, cells, rows) {
        var i, j, row,
        columns = [],
        data = [];

        for (i = 0; i < cells; i++) {
            columns.push({
                field: 'field' + i,
                title: 'Cell' + i
            });
        }
        for (i = 0; i < rows; i++) {
            row = {};
            for (j = 0; j < cells; j++) {
                row['field' + j] = 'Row-' + i + '-' + j;
            }
            data.push(row);
        }
        $el.bootstrapTable('destroy').bootstrapTable({
            columns: columns,
            data: data
        });
    }*/

    $(function () {
        $('#clmtable').bootstrapTable('destroy').bootstrapTable();
        /*buildTable($('#clmtable'), 50, 50);*/
    });
</script>