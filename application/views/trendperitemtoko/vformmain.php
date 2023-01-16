<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
        <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>

            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/export'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                            <select id="iarea" name="iarea" class="form-control select2">
                                <option value=""></option>
                                <?php if ($area) {
                                    foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option> 
                                    <?php }
                                } ?>
                            </select>
                            <input type="hidden" id="eareaname" name="eareaname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                        <a id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
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
   showCalendar('.date');
});

$(document).ready(function () {
    $('.select2').select2({
        placeholder: 'Pilih Area'
    });
    showCalendar('.date');
});

$('#dfrom, #dto, #iarea').on('change',function(){
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var iarea = $('#iarea').val();
    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+dfrom+'/'+dto+'/'+iarea);
});
</script>
