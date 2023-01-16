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
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4">Kode Department</label>
                            <label class="col-md-8">Nama Department</label>
                            <div class="col-sm-4">
                                <input type="text" name="idept" class="form-control" required="" onkeyup="gede(this); clearcode(this);" value="<?= $data->i_departement; ?>" readonly>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="edept" class="form-control" onkeyup="clearname(this);" value="<?= $data->e_departement_name; ?>" maxlength="50">
                            </div>
                        </div>                                       
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-12">
                                <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>