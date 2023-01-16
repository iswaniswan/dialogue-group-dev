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
			                <th>SPB (Rp)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <input type="hidden" id="dfrom" name="dfrom" value= "<?php echo $dfrom; ?>" >
			    <input type="hidden" id="dto" name="dto" value= "<?php echo $dto; ?>" >
			    <input type="hidden" id="iarea" name="iarea" value= "<?php echo $area; ?>" >
                <a href="#" id="href" onclick = "exportexcel();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>&nbsp;&nbsp;
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom ?>/<?= $dto ?>/<?= $area ?>');
    });

    function exportexcel(){
        var abc = "<?php echo site_url($folder.'/cform/export/'.$dfrom.'/'.$dto.'/'.$area); ?>";
        $("#href").attr("href",abc);
    }
</script>