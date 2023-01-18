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
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Supplier</label>
                        <label class="col-md-6">Jenis Potong</label>
                        <div class="col-sm-6">                           
                            <select name="isupplier" class="form-control select2">
                            <option value="">Pilih Supplier</option>
                            <?php foreach ($supplier as $isupplier):?>
                                <option value="<?php echo $isupplier->i_supplier;?>">
                                    <?php echo $isupplier->i_supplier."-".$isupplier->e_supplier_name;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                        <select name="ejenispotong" class="form-control select2">
                            <option value="">Pilih Jenis Potong</option>
                            <option value="1">Potong Serong</option>
                            <option value="2">Potong Lurus</option> 
                            <option value="3">Potong Spiral</option>  
                        </select> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Harga</label>
                        <div class="col-sm-6">
                            <input type="text" name="eharga" class="form-control" value="" >
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

 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
