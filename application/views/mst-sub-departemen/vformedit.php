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
                    <div class="form-group row">
                        <label class="col-md-4">Kode Sub Departemen</label>
                        <label class="col-md-8">Nama Sub Departemen</label>
                        <div class="col-sm-4">
                            <input type="text" name="isubbagian" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_sub_bagian; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="enama" class="form-control" value="<?= $data->e_sub_bagian; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Departemen</label>
                        <div class="col-sm-6">
                          <select name="idepartemen" class="form-control select2">
                            <option value="">Pilih Departemen</option>
                            <?php foreach ($dept as $idepartemen):?>
                                <option value="<?php echo $idepartemen->i_kode;?>"
                                    <?php if($idepartemen->i_kode==$data->i_kode) { ?> selected="selected" <?php } ?>>
                                    <?php echo $idepartemen->e_nama;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
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
