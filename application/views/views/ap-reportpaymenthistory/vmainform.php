<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-info-circle"></i> &nbsp; <?=$title;?> <a href="#"
          onclick="show('<?=$folder;?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
            class="fa fa-list"></i></a>
      </div>
      <div class="panel-body table-responsive">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div id="pesan"></div>
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-md-3">Mulai dari</label><label class="col-md-9">Sampai dengan</label>
            <div class="col-sm-3">
              <input type="text" id="dfrom" name="dfrom" class="form-control date" required
                value="<?='01' . date('-m-Y');?>" readonly>
            </div>
            <div class="col-sm-3">
              <input type="text" id="dto" name="dto" class="form-control date" required value="<?=date('d-m-Y');?>"
                readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-12">Customer</label>
            <div class="col-sm-6">
              <select name="icustomer" id="icustomer" class="form-control select2" required="">
                <option value=""></option>
                <?php if ($iarea == 'NA') {?>
                <option value="NA">NA - Nasional</option>
                <?php }?>
                <?php
                  if ($customer) {
                  foreach ($customer->result() as $key) {?>
                    <option value="<?php echo $key->i_customer; ?>">
                      <?php echo $key->i_customer . " - " . $key->e_customer_name; ?></option>
                <?php }
}?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-offset-5 col-sm-8">
              <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i
                  class="fa fa-table"></i>&nbsp;&nbsp;View</button>
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  $('.select2').select2({
    placeholder: 'Cari Berdasarkan Kode / Nama'
  });
  showCalendar('.date');
});
</script>