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
                        <label class="col-md-12">Kode Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="icity" class="form-control" required="" maxlength="7" onkeyup="gede(this)" value="<?= $data->i_city ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecityname" class="form-control" required="" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_city_name ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" class="form-control select2">
                            <?php foreach ($area as $r):?>
                                <option value="<?php echo $r->i_area;?>" <?= $r->i_area == $data->i_area ? 'selected' : '' ?>><?php echo $r->i_area." - ".$r->e_area_name;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis Kota</label>
                        <div class="col-sm-12">
                            <select name="icitytype" class="form-control select2">
                            <?php foreach ($jeniskota as $r):?>
                                <option value="<?php echo $r->i_city_type;?>" <?= $r->i_city_type == $data->i_city_type ? 'selected' : '' ?>><?php echo $r->i_city_type." - ".$r->e_city_typename;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Grup Kota</label>
                        <div class="col-sm-12">
                            <select name="icitygroup" class="form-control select2">
                            <?php foreach ($grupkota as $r):?>
                                <option value="<?php echo $r->i_city_group;?>" <?= $r->i_city_group == $data->i_city_group ? 'selected' : '' ?>><?php echo $r->i_city_group." - ".$r->e_city_groupname;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Status Kota</label>
                        <div class="col-sm-12">
                            <select name="icitystatus" class="form-control select2">
                            <?php foreach ($statuskota as $r):?>
                                <option value="<?php echo $r->i_city_status;?>" <?= $r->i_city_status == $data->i_city_status ? 'selected' : '' ?>><?php echo $r->i_city_status." - ".$r->e_city_statusname;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Toleransi SJ Pusat</label>
                        <div class="col-sm-12">
                            <input type="text" name="ntoleransipusat" class="form-control" maxlength="2" onkeyup="gede(this)" value="<?= $data->n_toleransi_pusat ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Toleransi SJ Cabang</label>
                        <div class="col-sm-12">
                            <input type="text" name="ntoleransicabang" class="form-control" maxlength="2" onkeyup="gede(this)" value="<?= $data->n_toleransi_cabang ?>">
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
</script>