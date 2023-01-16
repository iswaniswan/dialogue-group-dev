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
                        <label class="col-md-12">Kode</label>
                        <div class="col-sm-12">
                            <input type="text" name="kode" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" name="nama" class="form-control" value="<?= $data->e_nama; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                          <select name="igudang" class="form-control select2">
                            <option value="">Pilih Gudang</option>
                            <?php foreach ($gudang as $igudang):?>
                                <option value="<?php echo $igudang->i_kode_master;?>"
                                    <?php if($igudang->i_kode_master==$data->i_kode_master) { ?> selected="selected" <?php } ?>>
                                    <?php echo $igudang->e_nama_master;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>                                         
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
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
