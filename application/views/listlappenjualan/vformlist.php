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
                        <th>No Nota</th>
                        <th>Tanggal Nota</th>
                        <th>No Seri Pajak</th>
                        <th>Tanggal Pajak</th>
                        <th>Area</th>
                        <th>customer</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tr>
                  <td colspan='5' align='center'>
                    <!-- <a href="#" id="href" onclick = "exportexcel();"><i class="fa fa-download"></i>&nbsp;&nbsp;<input class="btn btn-inverse btn-rounded btn-sm" type="button" value ="Export Excel"/></a> -->
                    <a href="#" id="href" onclick = "exportexcel();"><button type="button" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
</div>
</div>


<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom ?>/<?= $dto ?>/');
    });


    function exportexcel(){
     var abc = "<?php echo site_url($folder.'/cform/export/'.$dfrom.'/'.$dto); ?>";
     $("#href").attr("href",abc);
 }
</script>
