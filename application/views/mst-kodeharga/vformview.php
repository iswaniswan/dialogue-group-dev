<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4">Kode Harga</label>
                            <label class="col-md-8">Nama Harga</label>
                            <div class="col-sm-4">
                                <input type="hidden" readonly="" name="id" value="<?= $data->id; ?>">
                                <input type="text" name="iharga" id="iharga" class="form-control" required="" maxlength="15" onkeyup="gede(this); clearcode(this);" value="<?= $data->i_harga; ?>" readonly>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="eharga" id="eharga" class="form-control" required="" onkeyup="gede(this); clearname(this);" value="<?= $data->e_harga; ?>" readonly>
                            </div>
                        </div>                       
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
</script>
