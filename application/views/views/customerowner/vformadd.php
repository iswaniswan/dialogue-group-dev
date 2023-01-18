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
                        <label class="col-md-12">Kode Pelanggan</label>
                        <div class="col-sm-12">
                            <select name="icustomer" id="icustomer" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Pemilik</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerownername" class="form-control" required="" maxlength="50" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat Pemilik</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerowneraddress" class="form-control" required="" maxlength="100" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerownerphone" class="form-control" required="" maxlength="30" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Penyetor</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomersetor" class="form-control" required="" maxlength="30" onkeyup="gede(this)">
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
        $('#iarea').select2({
            placeholder: "Pilih Area",
            allowClear: true
        }).on("change", function(e) {
        var kode = $('#iarea option:selected').text();
        //kode = kode.split("-");
        $('#eareaname').val(kode);
     });

        $('#icustomer').select2({
        //var iarea = $('#iarea').val();
        placeholder: 'Pilih Pelanggan',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_pelanggan/'); ?>',
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