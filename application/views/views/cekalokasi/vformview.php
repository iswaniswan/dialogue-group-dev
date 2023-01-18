<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th>No Alokasi</th>
                            <th>Tgl Alokasi</th>
                            <th>Bank</th>
                            <th>Customer</th>
                            <th>Jumlah</th>
                            <th>Lebih</th>
                            <th>No Bank Masuk</th>
                            <th>Di Cek</th>
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
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom."/".$dto."/".$iarea;?>/');
    });
</script>