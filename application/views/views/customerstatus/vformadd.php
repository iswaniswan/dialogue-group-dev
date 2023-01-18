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
                <div class="form-group">
                        <label class="col-md-12">Kode Status Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomerstatus" class="form-control" required="" maxlength="5" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomerstatusname" class="form-control" required="" maxlength="20" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Batas Bawah</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerstatusdown" class="form-control" required="" maxlength="2" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Batas Atas</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerstatusup" class="form-control" required="" maxlength="2" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Index</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerstatusindex" class="form-control" required="" maxlength="3" >
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