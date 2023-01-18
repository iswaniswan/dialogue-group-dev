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
                        <label class="col-md-12">Kode Kategory</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductcategory" class="form-control" required="" maxlength="4" onkeyup="gede(this)" value="<?= $data->i_product_category; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Kategory</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductcategoryname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_categoryname; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Class</label>
                        <div class="col-sm-12">
                            <select name="iproductclass" class="form-control select2">
                            <?php foreach ($iproductclass as $r):?>
                                <option value="<?php echo $r->i_product_class;?>" <?= $r->i_product_class == $data->i_product_class ? "selected" : "" ?>><?php echo $r->i_product_class." - ".$r->e_product_classname;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    <div>
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