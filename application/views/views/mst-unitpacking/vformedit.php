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
            <div class="col-md-6">
                <div id="pesan"></div>                   
                    <div class="form-group">
                        <label class="col-md-12">Kode Unit Packing</label>
                        <div class="col-sm-12">
                            <input type="text" name="iunitpacking" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_unit_packing; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Unit Packing</label>
                        <div class="col-sm-12">
                            <input type="text" name="eunitpackingname" class="form-control" maxlength="30"  value="<?= $data->e_nama_packing; ?>" >
                        </div>
                    </div>                     
                    <div class="form-group">
                        <label class="col-md-12">Lokasi</label>
                        <div class="col-sm-12">
                            <input type="text" name="epackinglocation" class="form-control" maxlength=""  value="<?= $data->e_lokasi_packing; ?>" >
                        </div>
                    </div>                                
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>-->
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
