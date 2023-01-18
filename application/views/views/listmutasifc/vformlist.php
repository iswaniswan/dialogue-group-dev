<div class="row">
    <div class="col-lg-12">
    <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Saldo Awal</th>
                            <th>Masuk</th>
                            <th>Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tr>
					</tr>
                </table>
            </div>
        </div>
    </form>
    </div>
</div>


<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $periode ?>/');
    });
</script>
