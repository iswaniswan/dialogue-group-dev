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
                        <label class="col-md-12">Kode</label>
                        <div class="col-sm-12">
                            <input type="text" name="iekspedisi" class="form-control" required="" maxlength="4" value="<?= $data->i_ekspedisi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Ekspedisi</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisi" class="form-control" required="" maxlength="30" value="<?= $data->e_ekspedisi; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" class="form-control select2">
                            <?php foreach ($area as $r):?>
                                <option value="<?php echo $r->i_area;?>" <?= $r->i_area == $data->i_area ? "selected" : "" ?>><?php echo $r->i_area." - ".$r->e_area_name;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisiaddress" class="form-control" required="" maxlength="30" value="<?= $data->e_ekspedisi_address; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisicity" class="form-control" required="" maxlength="30" value="<?= $data->e_ekspedisi_city; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisiphone" class="form-control" required="" maxlength="30" value="<?= $data->e_ekspedisi_phone; ?>" onkeypress="return hanyaAngka(event)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Fax</label>
                        <div class="col-sm-12">
                            <input type="text" name="eekspedisifax" class="form-control" required="" maxlength="30" value="<?= $data->e_ekspedisi_fax; ?>">
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
