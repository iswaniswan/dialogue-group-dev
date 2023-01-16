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
                        <label class="col-md-12">Kode Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="istore" class="form-control" required="" maxlength="2" onkeyup="gede(this)" value="<?= $data->i_store; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Gudang</label>
                        <div class="col-sm-12">
                            <input type="text" name="estorename" class="form-control" required="" maxlength="30" value="<?= $data->e_store_name; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Register</label>
                        <div class="col-sm-12">
                            <input type="text" name="dstoreregister" class="form-control date" value="<?= date("d-m-Y", strtotime($data->d_store_register)); ?>" readonly>
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
    showCalendar('.date');
 });
</script>
