<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group row">
                      <label class="col-md-12">Group</label>
                      <div class="col-sm-6">
                          <select id="igroup" name="igroup" class="form-control select2" onchange="get(this.value);">
                          </select>
			                    <input type="hidden" id="egroupname" name="egroupname" class="form-control">
                      </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                          <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-table"></i>&nbsp;&nbsp;View</button>
                          &nbsp;&nbsp;
                          <a id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
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
        $('#igroup').select2({
        placeholder: 'Pilih Group',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/bacagroup/'); ?>',
          dataType: 'json',
          delay: 250,
          data: function (params) {
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

function get(id) {
  $.ajax({
      type: "post",
      data: {
          'i_price_groupco': id
      },
      url: '<?= base_url($folder.'/cform/getgroup'); ?>',
      dataType: "json",
      success: function (data) {
          $('#igroup').val(data[0].i_price_groupco);
          $('#egroupname').val(data[0].e_price_groupconame);
      },
      error: function () {
          alert('Error :)');
      }
  });
}

$('#igroup, #egroupname').on('change',function(){
    var igroup = $('#igroup').val();
    var egroupname = $('#egroupname').val();
    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+igroup+'/'+egroupname);
});
</script>