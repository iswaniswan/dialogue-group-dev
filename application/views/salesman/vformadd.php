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
                        <label class="col-md-12">Kode Sales</label>
                        <div class="col-sm-12">
                            <input type="text" name="isalesman" class="form-control" required="" maxlength="2" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" class="form-control select2">
                                <?php foreach ($area as $r):?>
                                    <option value="<?php echo $r->i_area;?>"><?php echo $r->i_area." - ".$r->e_area_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Sales</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanname" class="form-control" maxlength="50" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanaddress" class="form-control" maxlength="50" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmancity" class="form-control" maxlength="50" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Pos</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanpostal" class="form-control" maxlength="5" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nomor Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="esalesmanphone" class="form-control" maxlength="20" onkeyup="gede(this)">
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
</script>