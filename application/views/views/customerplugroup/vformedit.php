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
                    <label class="col-md-12">Kode Pelanggan</label>
                    <div class="col-sm-12">
                        <input type="text" name="icustomer" class="form-control" value="<?= $data->i_customer; ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Kode Group PLU</label>
                    <div class="col-sm-12">
                        <input type="text" name="icustomerplugroup" class="form-control" value="<?= $data->i_customer_plugroup; ?>">
                    </div>
                </div>
                <div class="form-group">
                        <label class="col-md-12">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerplugroupname" class="form-control" onkeyup="gede(this)" value="<?= $data->e_customer_plugroupname; ?>">
                        </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('#icustomer').select2({
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