<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <!-- <h5 class="box-title m-b-0">Total Target&nbsp;:&nbsp;&nbsp;Rp. <?= number_format($nilai->target);?></h5>
                <h5 class="box-title m-b-0">Total Bersih&nbsp;&nbsp;:&nbsp;&nbsp;Rp. <?= number_format($nilai->bersih);?></h5>
                <h5 class="box-title m-b-0">Total Kotor&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;Rp. <?= number_format($nilai->kotor);?></h3><br> -->
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Periode Awal</th>
                            <th>Periode Akhir</th>
                            <th>Area</th>
                            <th>Salesman</th>
                            <th>Target</th>
                            <th>Nilai Kotor</th>
                            <th>Nilai Bersih</th>
                            <th>% Bersih <br>vs Target</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th colspan="4">Grand Total</th>
                        <th>Rp. <?= number_format($nilai->target);?></th>
                        <th>Rp. <?= number_format($nilai->bersih);?></th>
                        <th>Rp. <?= number_format($nilai->kotor);?></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom."/".$dto;?>/');
    });
</script>