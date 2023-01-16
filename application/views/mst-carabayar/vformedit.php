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
                        <label class="col-md-6">Kode Bank</label>
                        <label class="col-md-6">CoA</label>
                        <div class="col-sm-6">
                            <input type="text" name="id" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_jenis_bayar; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="name" class="form-control"  value="<?= $data->e_jenis_bayarname; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                        </div>
                    </div>
                </div>   
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        $(".select2").select2();
    });
</script>
