<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>Kode Area</th>
                            <th>Nama Area</th>
			                <th>Uang Masuk</th>
	   	                    <th>Pelunasan</th>
			                <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="text-align: center;" colspan="2">GRAND TOTAL</th>
                            <th><?= number_format($total->bank);?></th>
                            <th><?= number_format($total->alokasi);?></th>
                            <th><?= number_format($total->selisih);?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
    });
</script>