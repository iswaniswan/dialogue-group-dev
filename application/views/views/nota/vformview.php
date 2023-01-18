<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No DKB</th>
                            <th>Tgl DKB</th>
                            <th>No SJ</th>
                            <th>Tgl SJ</th>
                            <th>Tgl SJ Receive</th>
                            <th>No SPB</th>
                            <th>Tgl SPB</th>
                            <th>Customer</th>
                            <th>Area</th>
                            <th>Nota Saja</th>
                            <th>Nm Pemilik</th>
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
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>/');
    });
</script>