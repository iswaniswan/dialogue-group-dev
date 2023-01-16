<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-rotate-left"></i>&nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">   
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Periode</th>
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
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom.'/'.$dto;?>');
    });
</script>