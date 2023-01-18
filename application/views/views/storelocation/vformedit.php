<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Lokasi Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="istorelocation" class="form-control" required="" maxlength="5" value="<?= $data->i_store_location; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Lokasi Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="estorelocationname" class="form-control" required="" maxlength="30" value="<?= $data->e_store_locationname; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Gudang</label>
                        <div class="col-sm-12">
                            <select name="istore" class="form-control select2">
                            <?php foreach ($store as $r):?>
                                <option value="<?php echo $r->i_store;?>" <?= $r->i_store == $data->i_store ? "selected" : "" ?>><?php echo $r->i_store." - ".$r->e_store_name;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                        <input type="hidden" name="istorelocationbin" class="form-control" required="" maxlength="30" value="00">
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
