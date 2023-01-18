<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label><label class="col-md-9">Date To</label>
                        <div class="col-sm-3">
                            <input readonly name="dfrom" id="dfrom" class="form-control date" required="">
                        </div>
                        <div class="col-sm-3">
                            <input readonly name="dto" id="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3">Area</label><label class="col-md-9">Kota</label>
                        <div class="col-sm-3">
                            <select id="iarea" name="iarea" class="form-control select2">
                            </select>
			                <input type="hidden" id="eareaname" name="eareaname" class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <select id="icity" name="icity" class="form-control select2">
                            </select>
			                <input type="hidden" id="ecityname" name="ecityname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-table"></i>&nbsp;&nbsp;View</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
   $(".select2").select2();
   showCalendar('.date');
});

$(document).ready(function () {
        $('#iarea').select2({
        placeholder: 'Pilih Area',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/bacaarea/'); ?>',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            var icity = $("#icity").val();
            var query   = {
                q       : params.term,
            }
            return query;
          },
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });
});

$(document).ready(function () {
        $('#icity').select2({
        placeholder: 'Pilih Kota',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/bacakota/'); ?>',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            var iarea    = $("#iarea").val();
            var query   = {
                q       : params.term,
                iarea   : iarea
            }
            return query;
          },
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });
});
</script>