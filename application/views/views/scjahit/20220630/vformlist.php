<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> 
            <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                        <!-- <a href="#" onclick="show('<?= $folder; ?>/cform/upload/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-upload"></i> &nbsp;Upload <?= $title; ?></a> -->
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th>Kode Dokumen</th>
                            <th>Tanggal Dokumen</th>
                            <th>Bagian</th>
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
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data');
    });
</script>