<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-6">Kategori Partner Group</label><label class="col-md-6">Kode Partner Group</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="isuppliergroup" id="isuppliergroup" class="form-control" value="<?=$isi->isuppliergroup;?>" readonly>
                            <input type="text" name="esuppliergroupname" id="esuppliergroupname" class="form-control" value="<?=$isi->esuppliergroupname;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="epartner"  id="epartner" class="form-control" value="<?=$isi->e_pusat;?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nama Partner Group</label><label class="col-md-6">Level Group</label>
                        <div class="col-sm-6">
                            <input type="text" name="ipartner"  id="ipartner" class="form-control" value="<?=$isi->i_kepala_pusat;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="elevelgroup" id="elevelgroup" class="form-control" maxlength="100"  value="<?=$isi->e_level;?>" readonly>
                            <input type="hidden" name="ilevelgroup" id="ilevelgroup" class="form-control" maxlength="100"  value="<?=$isi->ilevel;?>" readonly>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Kode Partner Group</th>
                            <th>Nama Partner Group</th> 
                            <th>Level Group</th> 
                            <th>Kategori Partner Group</th> 
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
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/datacabang/<?=$ipusat?>/');
    });
</script>