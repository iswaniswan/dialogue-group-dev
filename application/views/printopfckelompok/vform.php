<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/cetak'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="col-md-12">Date From</label>
                            <input readonly id="dfrom" name="dfrom" class="form-control date" required="" onchange="cekdfrom(this.value);" />
                        </div>
                        <div class="col-sm-6">
                            <label class="col-md-12">Date To</label>
                            <input readonly id="dto" name="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>" onchange="cekdto(this.value);"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">OP From</label>
                        <div class="col-sm-12">
                            <select name="opfrom" id="opfrom" class="form-control select2" disabled="">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">OP To</label>
                        <div class="col-sm-12">
                            <select name="opto" id="opto" class="form-control select2" disabled="">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>                
                    <div class="form-group row"></div>   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-print"></i>&nbsp;&nbsp;Cetak</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>

    function cekdfrom(dfrom) {
        var dto = $("#dto").val();
        if (dfrom != '' && dto != '') {
            $("#opfrom").attr("disabled", false);
            $("#opto").attr("disabled", false);
        }else{
            $("#opfrom").attr("disabled", true);
            $("#opto").attr("disabled", true);
        }
        $('#opfrom').html('');
        $('#opto').html('');
        $('#opfrom').val('');
        $('#opto').val('');
    }

    function cekdto(dto) {
        var dfrom = $("#dfrom").val();
        if (dfrom != '' && dto != '') {
            $("#opfrom").attr("disabled", false);
            $("#opto").attr("disabled", false);
        }else{
            $("#opfrom").attr("disabled", true);
            $("#opto").attr("disabled", true);
        }
        $('#opfrom').html('');
        $('#opto').html('');
        $('#opfrom').val('');
        $('#opto').val('');
    }

    $(document).ready(function () {
        showCalendar('.date');

        $('#opfrom').select2({
            placeholder: 'Cari OP From',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getopfrom/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dfrom    = $('#dfrom').val();
                    var dto    = $('#dto').val();
                    var query = {
                        q: params.term,
                        dfrom: dfrom,
                        dto: dto,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        $('#opto').select2({
            placeholder: 'Cari OP To',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getopto/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dfrom    = $('#dfrom').val();
                    var dto    = $('#dto').val();
                    var opfrom    = $('#opfrom').val();
                    var query = {
                        q: params.term,
                        dfrom: dfrom,
                        dto: dto,
                        opfrom     :opfrom
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

    });

</script>