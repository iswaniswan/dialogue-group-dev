<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-md-6">Date From</label><label class="col-md-6">Date To</label>
                        <div class="col-sm-6">
                            <input readonly name="dfrom" id="dfrom" class="form-control date" required="">
                        </div>
                        <div class="col-sm-6">
                            <input readonly name="dto" id="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <select id="isupplier" name="isupplier" required="" class="form-control select2"></select>
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
    $(document).ready(function () {
        $(".select2").select2();
        showCalendar('.date');
    });

    $(document).ready(function () {
        $('#isupplier').select2({
            placeholder: 'Pilih Supplier',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datasupplier'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })/*.on("change", function(e) {
            var kode = $('#isupplier').text();
            kode = kode.split("-");
            $('#esupplier').val(kode[1]);
        })*/;
    });
</script>
