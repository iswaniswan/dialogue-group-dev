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
                        <label class="col-md-12">Kode Grup Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="icitygroup" class="form-control" required="" maxlength="4" onkeyup="gede(this)" value="<?= $data->i_city_group ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Grup Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecitygroupname" class="form-control" required="" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_city_groupname ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" class="form-control select2">
                            <?php foreach ($area as $r):?>
                                <option value="<?php echo $r->i_area;?>" <?= $data->i_area == $r->i_area ? 'selected' : '' ?>><?php echo $r->i_area." - ".$r->e_area_name;?></option>
                            <?php endforeach; ?>
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
 $(document).ready(function () {
    $(".select2").select2();
 });
</script>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>