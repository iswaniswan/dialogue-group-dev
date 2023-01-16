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
                    <label class="col-md-12">Pelanggan</label>
                    <div class="col-sm-12">
                        <input type="hidden" name="ecustomername" id="ecustomername" class="form-control" value="<?php echo $data->e_customer_name?>" readonly>
                        <select name="icustomer" id="icustomer" class="form-control">
                            <option value="<?php echo $data->i_customer; ?>"><?php echo $data->i_customer." - ".$data->e_customer_name;?></option>
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Kode Group Bayar</label>
                    <div class="col-sm-12">
                        <select name="igroup" id="igroup" class="form-control">
                            <option value="<?php echo $data->i_group; ?>"><?php echo $data->i_group;?></option>
                            <option></option>
                        </select>
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
        }).on("change", function(e) {
            var kode = $('#icustomer option:selected').text();
            kode = kode.split("-");
            $('#ecustomername').val(kode[1]);
        });

        $('#igroup').select2({
            placeholder: 'Pilih Group Bayar',
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