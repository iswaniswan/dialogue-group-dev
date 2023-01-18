<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">User</label>
                            <label class="col-md-3">Perusahaan</label>
                            <label class="col-md-3">Departement</label>
                            <label class="col-md-3">Level</label>
                            <div class="col-sm-3">
                                <select name="iuser" id="iuser" required="" class="form-control select2" data-placheholder="Cari User">
                                    <option value="<?= $username;?>"><?= $username;?></option>
                                </select>
                                <input type="hidden" name="iuserold" value="<?= $username;?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="icompany" required="" id="icompany" class="form-control select2">
                                    <option value="">-- Pilih Perusahaan --</option>
                                    <?php if ($company) {
                                        foreach ($company as $row): ?>
                                            <option value="<?= $row->id; ?>" <?php if ($data->id_company == $row->id) {?> selected <?php } ?>><?= $row->name;?></option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                                <input type="hidden" name="icompanyold" value="<?= $icompany;?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="idept" required="" id="idept" class="form-control select2">
                                    <option value="">-- Pilih Departement --</option>
                                    <?php if ($depart) {
                                        foreach ($depart as $idept): ?>
                                            <option value="<?= $idept->i_departement; ?>" <?php if ($data->i_departement == $idept->i_departement) {?> selected <?php } ?>><?= $idept->e_departement_name;?></option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                                <input type="hidden" name="ideptold" value="<?= $xdept;?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="ilevel" required="" id="ilevel" class="form-control select2">
                                    <option value="">-- Pilih level --</option>
                                    <?php foreach ($level as $ilevel): ?>
                                        <option value="<?php echo $ilevel->i_level; ?>" <?php if ($data->i_level == $ilevel->i_level) {?> selected <?php } ?>><?= $ilevel->e_level_name;?></option>
                                    <?php endforeach;?>
                                </select>
                                <input type="hidden" name="ilevelold" value="<?= $xlevel;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-12">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });

    function formatSelection(val) {
        return val.name;
    }

    var demo1 = $('select[name="duallistbox_demo1[]"]').bootstrapDualListbox();
    $("#demoform").submit(function() {
      alert($('[name="duallistbox_demo1[]"]').val());
      return false;
  });

    var demo2 = $('.demo2').bootstrapDualListbox({
      nonSelectedListLabel: 'Non-selected',
      selectedListLabel: 'Selected',
      preserveSelectionOnMove: 'moved',
      moveOnSelect: false,
      nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
  });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
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

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>