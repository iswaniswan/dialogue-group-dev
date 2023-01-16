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
                            <?php 
                                if($isi){
                            		$xx=0;
                            		foreach($isi as $raw){
                            			$xx++;
                            			if($xx<2){
                            				echo "<th colspan=10>$raw->i_supplier - $raw->e_supplier_name</th>";
                            			}
                            		}
                            	}
                            ?>
                        </tr>
                        <tr>
                            <th>No</th>
	                        <th>No Nota</th>
		                    <th>Tgl Nota</th>
		                    <th>No Seri Pajak</th>
		                    <th>Tgl Pajak</th>
		                    <th>Kode</th>
		                    <th>Nama</th>
		                    <th>Jumlah</th>
		                    <th>Harga</th>
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
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $isupplier."/".$iproduct; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
    });
    
</script>
