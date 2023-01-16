<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>


            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/index2'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-12">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select class="form-control select2" name="iarea">
                                <?php foreach($area as $r){ ?>
                                    <option value='<?= $r->i_area ?>'><?= $r->i_area.' - '.$r->e_area_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-hand-o-right"></i>&nbsp;&nbsp;Lanjutkan</button>
                        </div>
                    </div>
                    </form>
                </div>
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
</script>