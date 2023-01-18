<div class="row">
    <div class="col-lg-12">
        <!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
        </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Kode Supplier</th>
			            <th>OP</th>
                        <th>Nota Kotor</th>
                        <th>Potongan</th>
                        <th>Nota Bersih</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <th style="text-align: center;">Total :</th>
                    <th>Rp. <?= number_format($total->totop);?></th>
                    <th>Rp. <?= number_format($total->totkor);?></th>
                    <th>Rp. <?= number_format($total->totdis);?></th>
                    <th>Rp. <?= number_format($total->totnet);?></th>
                </tfoot>
            </table>
        </div>
    </div>
</form>
</div>
</div>


<script>
    $(document).ready(function () {
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom ?>/<?= $dto ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
    });
</script>
