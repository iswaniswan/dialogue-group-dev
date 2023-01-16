<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
              <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-3">Bagian</label>
                        <label class="col-md-3">Bulan</label 
                        <label class="col-md-3">Tahun</label>
                        <div class="col-sm-3">
                           <select id="ibagian" name="ibagian" class="form-control select2"></select>
                        </div>
                        <div class="col-sm-3">
                          <select id="bulan" name="bulan" class="form-control select2"></select>
                        </div>
                        <civ class="col-sm-3">
                          <select id="tahun" name="tahun" class="form-control select2"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-table"></i>&nbsp;&nbsp;View</button>
                        </div>
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
   
   $("#bulan").select2({
    placeholder : "Cari Bulan",
   });

   $("#tahun").select2({
    placeholder : "Cari Tahun",
   });
});

$(document).ready(function () {
    $('#ibagian').select2({
    placeholder: 'Pilih Bagian',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/bagian'); ?>',
      dataType: 'json',
      delay: 250,
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
