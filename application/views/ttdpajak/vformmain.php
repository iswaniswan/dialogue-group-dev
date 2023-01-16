<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
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
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-3">
                            <select id="iarea" name="iarea" class="form-control select2"></select>
			                <input type="hidden" id="eareaname" name="eareaname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                        <a id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
                        </div>
                    </div>
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
      url: '<?= base_url($folder.'/cform/dataarea'); ?>',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
    }).on("change", function(e) {
      var kode = $('#iarea').text();
      kode = kode.split("-");
      $('#eareaname').val(kode[1]);
    });
});
$('#dfrom, #dto, #iarea').on('change',function(){
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var iarea = $('#iarea').val();
    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+dfrom+ '/'+ dto +'/' +iarea);
});
</script>
