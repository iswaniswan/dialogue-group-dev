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
            <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Nama Brand</label>
                        <div class="col-sm-12">
                            <input type="text" name="ebrandname" class="form-control" maxlength="30"  value="" required="">
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Kode Brand</label>
                        <div class="col-sm-12">
                            <input type="text" name="ebrandcode" class="form-control" onkeyup="gede(this)"maxlength="30"  value="" required="">
                        </div>
                    </div>                
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
 

        </div>
    </div>
</div>

<script>
 $(document).ready(function () {
    $(".select2").select2();
 });
</script>
