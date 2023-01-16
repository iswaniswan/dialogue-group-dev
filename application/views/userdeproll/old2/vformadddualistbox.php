<link href="<?=base_url();?>assets/css/bootstrap-duallistbox.min.css" rel="stylesheet" type="text/css" />
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-plus"></i> &nbsp; <?=$title;?> <a href="#"
          onclick="show('<?=$folder;?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
            class="fa fa-list"></i>&nbsp;</a>
      </div>
      <div class="panel-body table-responsive">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div id="pesan"></div>
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-md-6">Departement</label><label class="col-md-6">level</label>
            <div class="col-sm-6">
              <select name="idept" id="idept" class="form-control select2">
                <option value="">-- Pilih Departement --</option>
                <?php foreach ($depart as $idept): ?>
                <option value="<?php echo $idept->i_departement; ?>">
                  <?=$idept->i_departement . " - " . $idept->e_departement_name;?></option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="col-sm-6">
              <select name="ilevel" id="ilevel" class="form-control select2">
                <option value="">-- Pilih level --</option>
                <?php foreach ($level as $ilevel): ?>
                <option value="<?php echo $ilevel->i_level; ?>">
                  <?=$ilevel->i_level . " - " . $ilevel->e_level_name;?></option>
                <?php endforeach;?>
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group row">
            <div class="col-lg-12">
              <h3 class="m-b-0 box-title">Daftar Menu</h3>
              <div class="form-group row">
                <div class="col-lg-12 col-sm-6">
                  <!-- <h5 class="box-title">Public methods</h5> -->
                  <select multiple id="duallistbox_demo1" class="form-control" name="idmenu[]">
                    <?php $qmenu = $this->mmaster->getmenu();
if ($qmenu->num_rows() > 0) {
    foreach ($qmenu->result() as $key) {?>
                    <option value="<?=$key->i_menu . $key->id;?>">
                      <?=$key->i_menu . '.' . $key->e_menu . ' - ' . $key->id . '. ' . $key->e_name;?>
                    </option>
                    <?php }
}?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-5 col-sm-10">
              <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"
                onclick="return validasi();"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
            </div>
          </div>
        </div>
        </form>
      </div>
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

$("#idmenu_helper2").on("change", function() {
  alert('1111111');
})



function formatSelection(val) {
  return val.name;
}

$(document).ready(function() {
  $('.select2').select2();
  showCalendar('.date');
});
</script>