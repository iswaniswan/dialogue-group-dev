<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No SJPB</th>
                            <th>Tanggal SJPB</th>
                            <th>Pelanggan</th>
                            <th>No SJP</th>
                            <th>Action</th>
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
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/');
    });
</script>