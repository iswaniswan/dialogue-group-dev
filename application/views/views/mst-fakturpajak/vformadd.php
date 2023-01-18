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
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Segmen 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="segmen1" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="" placeholder="7 digit pertama, misal: 010.900">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No Urut Awal</label>
                        <div class="col-sm-12">
                            <input type="text" name="nourutawal" class="form-control" value="" placeholder="misal: 31928693">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">No Urut Akhir</label>
                        <div class="col-sm-12">
                            <input type="text" name="nourutakhir" class="form-control" value="" placeholder="misal: 31929876">
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
