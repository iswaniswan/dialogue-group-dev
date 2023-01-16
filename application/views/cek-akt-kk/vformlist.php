<div class="row">
    <div class="col-lg-12">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
        </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Area</th>
                        <th>No KK</th>
                        <th>Tgl KK</th>
                        <th>CoA</th>
                        <th>Keterangan</th>
                        <th>Nilai</th>
                        <th>di Cek</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tr>
                  <td colspan='5' align='center'>
                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-circle"></i>&nbsp;&nbsp;Diperiksa</button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                </td>
            </tr>
        </table>
    </div>
</form>
</div>
</div>
</div>

<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom ?>/<?= $dto ?>/<?= $area ?>/');
    });
</script>