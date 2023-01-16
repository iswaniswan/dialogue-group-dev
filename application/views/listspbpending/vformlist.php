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
                            <th>No SPB</th>
			                <th>Tgl SPB</th>
			                <th>Sls</th>
			                <th>Lang</th>
			                <th>Area</th>
			                <th>Spb Lm</th>
			                <th>Kotor</th>
			                <th>Disc</th>
			                <th>Bersih</th>
			                <th>Status</th>
			                <th>Daerah</th>
                            <th>Jenis</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <td colspan='13' align='center'>
                        <a href="#" id="href" onclick = "exportexcel();"><button type="button" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
                    </td>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $area ?>');
    });

    function exportexcel(){
        var abc = "<?php echo site_url($folder.'/cform/export/'.$area); ?>";
        $("#href").attr("href",abc);
    }
</script>