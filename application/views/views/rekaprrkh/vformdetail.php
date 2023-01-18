<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Area</th>
     	                    <th>Salesman</th>
	                        <th>Hari</th>
	                        <th>Tgl Dokumen</th>
     	                    <th>Customer</th>
                            <th>Jenis</th>
	                        <th>Rencana</th>
                            <th>Realisasi</th>
                            <th>Order</th>
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
    var table = $('#tabledata').DataTable({
        serverSide: true,
        processing: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "ajax": {
            "url": "<?= site_url($folder); ?>/Cform/detail/<?= $iperiode ?>/<?= $isalesman ?>",
            "type": "POST"
        },
        "displayLength": 10,
    });
});
</script>