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
                        <label class="col-md-4">Kode Jenis</label>
                        <label class="col-md-8">Nama Jenis</label>
                        <div class="col-sm-4">
                            <input type="text" name="isuppliertype" class="form-control" required="" maxlength="2" onkeyup="gede(this)" value="<?= $data->i_type; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="isuppliertypename" class="form-control" required=""   maxlength="60" value="<?= $data->e_type_name; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit"  class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                         <label class="col-md-6">Kategori Supplier</label>
                         <div class="col-sm-6">
                            <select name="ikategorisupplier" class="form-control select2">
                                <option value="">Pilih Kategori Supplier</option>
                                <?php foreach($supplier_group as $ikategorisupplier): ?>
                                <option value="<?php echo $ikategorisupplier->i_supplier_group;?>" 
                                <?php if($ikategorisupplier->i_supplier_group==$data->i_supplier_group) { ?> selected="selected" <?php } ?>>
                                <?php echo $ikategorisupplier->e_supplier_groupname;?></option>
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
