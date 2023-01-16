<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
			                <th>Pcs (OP)</th>
	   	                    <th>RP (OP)</th>
			                <th>Pcs (DO)</th>
			                <th>RP (DO)</th>
			                <th>Persen Pcs</th>
			                <th>Persen Rp</th>
                            <th>supplier</th>
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
    $(document).ready(function() {
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{ 
                "targets": [1,3,4,5,6,7,8], 
                "className": "text-right",
            }],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $tahun."/".$bulan."/".$iarea; ?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    } );
</script>