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
                <div class="form-group">
                        <label class="col-md-12">Kode Barang</label>
                        <div class="col-sm-12">
                            <select name="iproduct" class="form-control select2">
                            <?php foreach ($iproduct as $r):?>
                                <option value="<?php echo $r->i_product;?>" <?= $r->i_product == $data->i_product ? "selected" : "" ?>><?php echo $r->i_product." - ".$r->e_product_name;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    <div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Motif Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductmotif" class="form-control" required="" maxlength="2" onkeyup="gede(this)" value="<?= $data->i_product_motif; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductmotifname" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_product_motifname; ?>">
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