<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <!-- <?php  if(check_role($this->i_menu, 1)){ ?><a href="#"
                    onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?> -->
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan Barang</th>
                            <th>Sub Kategori Barang</th>
                            <th>Kategori Barang</th>
                            <th>Group Barang</th>
                            <th>Supplier Utama</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
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
$(document).ready(function() {
    datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data');
});
</script>