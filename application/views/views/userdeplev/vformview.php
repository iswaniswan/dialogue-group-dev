<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nama User</label>  
                        <label class="col-md-6">Nama Perusahaan</label> 
                        <div class="col-sm-6">
                            <select name="iuser" id="iuser" class="form-control select2">
                                <option value="<?= $data->username;?>"><?= $data->username;?></option>
                                <?php foreach ($user as $iuser):?>
                                <option value="<?php echo $iuser->username;?>">
                                    <?= $iuser->username." - ".$iuser->e_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="icompany" id="icompany" class="form-control select2">
                            <option value="<?= $data->id_company;?>"><?= $data->name;?></option>
                                <?php foreach ($company as $icompany):?>
                                <option value="<?php echo $icompany->id;?>">
                                    <?= $icompany->id." - ".$icompany->short;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>
                </div>
                <div class="col-md-6">
                    
                    <div class="form-group row"> 
                    <label class="col-md-6">Departement</label>
                    <label class="col-md-6">level</label>
                    <div class="col-sm-6">
                            <select name="idept" id="idept" class="form-control select2">
                            <option value="<?= $data->i_departement;?>"><?= $data->e_departement_name;?></option>
                                <?php foreach ($depart as $idept):?>
                                <option value="<?php echo $idept->i_departement;?>">
                                    <?= $idept->i_departement." - ".$idept->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="ilevel" id="ilevel" class="form-control select2">
                            <option value="<?= $data->i_level;?>"><?= $data->e_level_name;?></option>
                                <?php foreach ($level as $ilevel):?>
                                <option value="<?php echo $ilevel->i_level;?>">
                                    <?= $ilevel->i_level." - ".$ilevel->e_level_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>  
                </div>    
            </form>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    function formatSelection(val) {
        return val.name;
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });
</script>