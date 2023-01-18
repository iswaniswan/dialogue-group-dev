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
                        <label class="col-md-6">Username</label>
                        <label class="col-md-6">Nama Karyawan / User</label>
                        <div class="col-sm-6">
                            <input type="text" name="iuser" class="form-control" required="" readonly="" value="<?= $data->username; ?>">
                            <input type="hidden" name="iuserold" class="form-control" required=""  value="<?= $data->username; ?>">
                            <input type="hidden" name="idcompany" class="form-control" required=""  value="<?= $data->id_company; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="eusername" onkeyup="clearname(this);" class="form-control" value="<?= $data->e_name; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-sm-offset-3 col-sm-12">
                        <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Password Lama</label>
                        <label class="col-md-6">Password Baru</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="pasworold" class="form-control" value="<?= $data->e_password;?> ">
                            <input type="password" name="passwordoldd" class="form-control" value="" >
                        </div>
                        <div class="col-sm-6">
                            <input type="password" name="passwordnew" class="form-control" value="">
                        </div>
                        <p style = 'margin-top:20px' align="justify"><font face="Courier New" color="red" size="3">*Note : Isi Password Lama dan Password baru  </font></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
