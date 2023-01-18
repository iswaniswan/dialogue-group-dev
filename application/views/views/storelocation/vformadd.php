<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Lokasi Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="istorelocation" class="form-control" required="" maxlength="2" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Lokasi Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="estorelocationname" class="form-control" required="" maxlength="30">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Gudang</label>
                        <div class="col-sm-12">
                        <select name="istore" id="istore" class="form-control">
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
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
 });

 $(document).ready(function () {
        $('#istore').select2({
        placeholder: 'Pilih Gudang',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_store'); ?>',
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
        var kode = $('#istore').text();
        kode = kode.split("-");
        $('#estorename').val(kode[1]);
     });
    });
</script>
