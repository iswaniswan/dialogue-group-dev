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
                        <label class="col-md-12">Kode Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="iarea" class="form-control" required="" maxlength="2" onkeyup="gede(this)" value="<?= $data->i_area; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaname" class="form-control" required="" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_area_name; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Jenis Area</label>
                        <div class="col-sm-12">
                            <select name="iareatype" class="form-control select2">
                            <?php foreach ($areatype as $r):?>
                                <option value="<?php echo $r->i_area_type;?>" <?= $r->i_area_type == $data->i_area_type? "selected" : "" ?>><?php echo $r->i_area_type." - ".$r->e_area_typename;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <select name="istore" class="form-control select2">
                            <?php foreach ($store as $r):?>
                                <option value="<?php echo $r->i_store;?>" <?= $r->i_store == $data->i_store ? "selected" : "" ?>><?php echo $r->i_store." - ".$r->e_store_name;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaaddress" class="form-control" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_area_address; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareacity" class="form-control" maxlength="50" onkeyup="gede(this)" value="<?= $data->e_area_city; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Inisial</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareashortname" class="form-control" maxlength="3" onkeyup="gede(this)" value="<?= $data->e_area_shortname; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="eareaphone" class="form-control" maxlength="20" onkeyup="gede(this)" value="<?= $data->e_area_phone; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Toleransi</label>
                        <div class="col-sm-12">
                            <input type="text" name="nareatoleransi" class="form-control" maxlength="2" onkeyup="gede(this)" value="<?= $data->n_area_toleransi; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="earearemark" class="form-control" maxlength="100" onkeyup="gede(this)" value="<?= $data->e_area_remark; ?>" >
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
