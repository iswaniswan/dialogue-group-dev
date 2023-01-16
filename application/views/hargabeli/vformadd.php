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
                    <!-- <div class="form-group">
                        <label class="col-md-12">Kode barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproduct" class="form-control" required="" maxlength="5" onkeyup="gede(this)">
                        </div>
                    </div> -->


                    <div class="form-group">
                        <label class="col-md-12">Kode barang</label>
                        <div class="col-sm-12">
                            <select name="iproduct" class="form-control select2">
                            <?php foreach ($iproduct as $r):?>
                                <option value="<?php echo $r->i_product." - ".$r->e_product_name;?>"><?php echo $r->i_product." - ".$r->e_product_name;?></option>
                            <?php endforeach; ?>
                            </select>
                            <!-- <input type="hidden" name="iproduct" id = "iproduct" class="form-control" required="" maxlength="7" value="00">
                            <input type="hidden" name="iproduct" id = "eproductname" class="form-control" required="" maxlength="7" value="00"> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Harga</label>
                        <div class="col-sm-12">
                            <input type="text" name="ipricegroup" class="form-control" required="" maxlength="02" onkeyup="gede(this)">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductname" class="form-control" maxlength="30" onkeyup="gede(this)">
                        </div>
                    </div> -->


                    <div class="form-group">
                        <label class="col-md-12">Kode Grade</label>
                        <div class="col-sm-12">
                            <select name="iproductgrade" class="form-control select2">
                            <?php foreach ($iproductgrade as $r):?>
                                <option value="<?php echo $r->i_product_grade;?>"><?php echo $r->i_product_grade." - ".$r->e_product_gradename;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <!-- <div class="form-group">
                        <label class="col-md-12">Kode Grade</label>
                        <div class="col-sm-12">
                            <input type="text" name="iproductgrade" class="form-control" maxlength="30" onkeyup="gede(this)">
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-md-12">Harga Pabrik</label>
                        <div class="col-sm-12">
                            <input type="number" name="vproductmill" class="form-control" maxlength="7" onkeyup="gede(this)">
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