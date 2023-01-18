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
                        <label class="col-md-12">Kode Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomer" class="form-control" required="" maxlength="5" onkeyup="gede(this)" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerdiscount1" class="form-control" required="" maxlength="6" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerdiscount2" class="form-control" required="" maxlength="6" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount 3</label>
                        <div class="col-sm-12">
                            <input type="text" name="ncustomerdiscount3" class="form-control" required="" maxlength="6" >
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