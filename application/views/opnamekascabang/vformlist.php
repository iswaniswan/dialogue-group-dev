<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            <?php  if(check_role($this->i_menu, 1)){ if ($hariini->i_opname == 0){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp;<?= $title; ?></a><?php } ?>
        <?php } ?>
    </div>
    <div class="panel-body table-responsive">
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No. Opname</th>
                    <th>Tanggal</th>
                    <th>Area</th>
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
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data');
    });

    function detail(iopname) {
        lebar =450;
        tinggi=400;
        eval('window.open("<?php echo site_url(); ?>"+"/<?= $folder;?>/cform/printopname/"+iopname,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>