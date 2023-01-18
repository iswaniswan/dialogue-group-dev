<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-2">Departement</label>
                            <label class="col-md-2">Level</label>
                            <label class="col-md-3">Username</label>
                            <label class="col-md-5">Type</label>
                            <div class="col-sm-2">
                                <select name="idept" required="" id="idept" class="form-control select2" data-placheholder="Pilih Departement">
                                    <option value=""></option>  
                                    <?php if ($departement) {
                                        foreach ($departement as $row): ?>
                                            <option value="<?= $row->i_departement; ?>" <?php if ($data->i_departement == $row->i_departement) {?> selected <?php } ?>><?= $row->e_departement_name;?></option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                                <input type="hidden" name="ideptold" value="<?= $data->i_departement;?>">
                            </div>
                            <div class="col-sm-2">
                                <select name="ilevel" required="" id="ilevel" class="form-control select2" data-placheholder="Pilih Level">
                                    <option value=""></option>
                                    <?php if ($level) {
                                        foreach ($level as $row): ?>
                                            <option value="<?= $row->i_level; ?>" <?php if ($data->i_level == $row->i_level) {?> selected <?php } ?>><?= $row->e_level_name;?></option>
                                        <?php endforeach;
                                    } ?>
                                </select>
                                <input type="hidden" name="ilevelold" value="<?= $data->i_level;?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="iuser" id="iuser" required="" class="form-control select2" data-placheholder="Cari User">
                                    <option value="<?= $data->username;?>"><?= $data->username;?></option>
                                </select>
                                <input type="hidden" name="iuserold" value="<?= $data->username;?>">
                            </div>                        
                            <div class="col-sm-5">
                                <select name="ibagian[]" id="ibagian" multiple="multiple" required="" class="form-control select2">
                                    <?php if ($detail) {
                                        foreach($detail as $key): ?>
                                            <option value="<?= $key->i_bagian; ?>" selected="selected"> <?= $key->e_bagian_name;?></option>
                                        <?php endforeach; 
                                    } ?> 
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-12">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select2").select2();
        $('#idept').select2({
            placeholder: 'Pilih Departement',
        });
        $('#ilevel').select2({
            placeholder: 'Pilih Level',
        });
        $('#iuser').select2({
            placeholder: 'Cari User',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/user'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        idept : $('#idept').val(),
                        ilevel : $('#ilevel').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        $('#ibagian').select2({
            placeholder: 'Pilih Type',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        idept : $('#idept').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $( "#idept" ).change(function() {
        $("#ibagian").val("");
        $("#ibagian").html("");
        $("#iuser").val("");
        $("#iuser").html("");
    });

    $( "#ilevel" ).change(function() {
        $("#iuser").val("");
        $("#iuser").html("");
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>
