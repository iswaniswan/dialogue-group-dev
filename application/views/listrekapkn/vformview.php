<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a></div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th>Area</th>
                          <th>Lang</th>
                          <th>No KNDN</th>
                          <th>Batal</th>
                          <th>Toko</th>
                          <th>KS</th>
                          <th>NPWP</th>
                          <th>Tgl Dok</th>
                          <th>Kotor</th>
                          <th>Potongan</th>
                          <th>Jumlah</th>
                          <th>DPP</th>
                          <th>PPN</th>
                          <th>Ket</th>
                          <th>Ket PKP</th>
                          <th>Cat</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
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
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
    });
</script>
