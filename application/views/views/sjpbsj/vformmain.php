<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">No SJPB</label>
                        <div class="col-sm-4">
                            <select name="i_sjpb" id="i_sjpb" class="form-control select2"></select>
                            <input type="hidden" name="e_sjpb" id="e_sjpb" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kelompok Harga</label>
                        <div class="col-sm-4">
                            <select name="i_kode_harga" id="i_kode_harga" class="form-control select2"></select>
                            <input type="hidden" name="e_kode_harga" id="e_kode_harga" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12"></label>
                        <div class="col-sm-3">
                            <select name="pilihan" id="pilihan" class="form-control select2" required="">
                                <option value="biasa">Biasa</option>
                                <option value="khusus">Khusus</option>
                            </select>
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
    $('#i_sjpb').select2({
    placeholder: 'Pilih No SJPB',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/datasjpb'); ?>',
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
      var kode = $('#i_sjpb').text();
      kode = kode.split("-");
      $('#e_sjpb').val(kode[1]);
    });
});

$(document).ready(function () {
    $('#i_kode_harga').select2({
    placeholder: 'Pilih Kelompok Harga',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/datakodeharga'); ?>',
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
      var kode = $('#i_kode_harga').text();
      kode = kode.split("-");
      $('#e_kode_harga').val(kode[1]);
    });
});
</script>
