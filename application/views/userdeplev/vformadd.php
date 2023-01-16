<link href="<?=base_url();?>assets/css/bootstrap-duallistbox.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">User</label>
                            <label class="col-md-3">Perusahaan</label>
                            <label class="col-md-3">Departement</label>
                            <label class="col-md-3">Level</label>
                            <div class="col-sm-3">
                                <select name="iuser" id="iuser" required="" class="form-control select2" data-placheholder="Cari User"></select>
                            </div>
                            <div class="col-sm-3">
                                <select name="icompany" required="" id="icompany" class="form-control select2">
                                    <option value="">-- Pilih Perusahaan --</option>
                                    <?php if ($company) {
                                        foreach ($company as $row): ?>
                                            <option value="<?= $row->id; ?>"><?= $row->name;?></option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="idept" required="" id="idept" class="form-control select2">
                                    <option value="">-- Pilih Departement --</option>
                                    <?php if ($depart) {
                                        foreach ($depart as $idept): ?>
                                            <option value="<?php echo $idept->i_departement; ?>"><?= $idept->e_departement_name;?></option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="ilevel" required="" id="ilevel" class="form-control select2">
                                    <option value="">-- Pilih level --</option>
                                    <?php foreach ($level as $ilevel): ?>
                                        <option value="<?php echo $ilevel->i_level; ?>"><?= $ilevel->e_level_name;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-12">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url();?>assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<script>
    $(function() {
        $("#duallistbox_demo1").bootstrapDualListbox({
            filterTextClear: "show all",
            filterPlaceHolder: "Filter",
            moveSelectedLabel: "Move selected",
            moveAllLabel: "Move all",
            removeSelectedLabel: "Remove selected",
            removeAllLabel: "Remove all",
            moveOnSelect: true,
            preserveSelectionOnMove: false,
            selectedListLabel: false,
            nonSelectedListLabel: false,
            helperSelectNamePostfix: "_helper",
            selectorMinimalHeight: 100,
            showFilterInputs: true,
            nonSelectedFilter: "",
            selectedFilter: "",
            infoText: "Showing all {0}",
            infoTextFiltered: '<span class="badge badge-warning">Filtered</span> {0} from {1}',
            infoTextEmpty: "Empty list",
            sortByInputOrder: false,
            filterOnValues: false,
            eventMoveOverride: false,
            eventMoveAllOverride: false,
            eventRemoveOverride: false,
            eventRemoveAllOverride: false,
            btnClass: "btn-outline-secondary",
            btnMoveText: "&gt;",
            btnRemoveText: "&lt;",
            btnMoveAllText: "&gt;&gt;",
            btnRemoveAllText: "&lt;&lt;",
        });
    });

    /*$("#idmenu_helper2").on("change", function() {
        alert('1111111');
    })*/

    function formatSelection(val) {
        return val.name;
    }

    $(document).ready(function() {
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