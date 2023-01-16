<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                <thead>
                    <tr>
                        <th>No SJP</th>
                        <th>Tgl SJP</th>
			            <th>Area</th>
			            <th>Terima</th>
			            <th>Tgl Terima</th>
			            <th>konsinyasi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
                <a href="#" id="href" onclick = "exportexcel();"><button type="button" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export to Excel</button>&nbsp;&nbsp;
            </div>
        </div>
    </div>
</div>
</div>

<script>
   $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom.'/'.$dto;?>');
    });

    function exportexcel(){
        var abc = "<?php echo site_url($folder.'/cform/export/'.$dfrom.'/'.$dto); ?>";
        $("#href").attr("href",abc);
    }
</script>