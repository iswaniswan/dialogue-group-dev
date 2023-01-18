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
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Nama Unit Jahit</label>
                            <label class="col-md-3">Nama Perusahaan</label>
                            <label class="col-md-6">Lokasi Perusahaan</label>
                            <div class="col-sm-3">
                                <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                <input type="text" name="eunitjahitname" class="form-control input-sm" placeholder="isi Nama Unit jahit" value="<?= $data->e_nama_unit; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="eperusahaanname" class="form-control input-sm" required="" placeholder="isi Nama Perusahaan Unit Jahit" value="<?= $data->e_perusahaan_name; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="eunitjahitaddress" class="form-control input-sm" maxlength="" placeholder="isi Alamat Lokasi Perusahaan Unit Jahit" value="<?= $data->e_unitjahit_address; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Penanggung Jawab</label>
                            <label class="col-md-3">Admin</label>
                            <label class="col-md-6">Kategori Unit</label>
                            <div class="col-sm-3">
                                <input type="text" name="epenanggungjawabname" class="form-control input-sm" required="" placeholder="isi Penanggung Jawab Unit Jahit" value="<?= $data->e_penanggung_jawab_name; ?>">
                            </div>
                            
                            <div class="col-sm-3">
                                <input type="text" name="eadminname" class="form-control input-sm" placeholder="isi Admin Unit Jahit" value="<?= $data->e_admin_name; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ikategori" class="form-control input-sm" placeholder="isi Admin Unit Jahit" value="<?= $data->e_nama_kategori; ?>">
                            </div>
                        </div>  
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-8 col-sm-5">
                                <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            </div>
        </div>
    </div>
</div>