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
                        <label class="col-md-12">Kode Kelompok Unit Jahit dan Packing</label>
                        <div class="col-sm-12">
                            <input type="text" name="ijahitpacking" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_jahitpacking; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Kelompok Unit Jahit dan Packing</label>
                        <div class="col-sm-12">
                            <input type="text" name="enamajahitpacking" class="form-control" maxlength="30"  value="<?= $data->e_nama_jahitpacking; ?>" >
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Kode Unit Jahit</label>
                        <div class="col-sm-12">
                        <select name="iunitjahit" class="form-control select2">
                            <option value="">Pilih Kode Unit Packing</option>
                            <?php foreach ($unit_jahit as $iunitjahit):?>
                                <option value="<?php echo $iunitjahit->i_unit_jahit;?>"
                                    <?php if($iunitjahit->i_unit_jahit==$data->i_unit_jahit) { ?> selected="selected" <?php } ?>>
                                    <?php echo $iunitjahit->i_unit_jahit;?></option>
                            <?php endforeach; ?>
                        </select>                         
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Unit Jahit</label>
                        <div class="col-sm-12">
                        <select name="eunitjahitname" class="form-control select2">
                            <option value="">Pilih Nama Unit Jahit</option>
                            <?php foreach ($unit_jahit as $eunitjahitname):?>
                                <option value="<?php echo $eunitjahitname->e_unitjahit_name;?>"
                                    <?php if($eunitjahitname->e_unitjahit_name==$data->e_unitjahit_name) { ?> selected="selected" <?php } ?>>
                                    <?php echo $eunitjahitname->e_unitjahit_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">Kode Unit Packing</label>
                        <div class="col-sm-12">                            
                        <select name="iunitpacking" class="form-control select2">
                            <option value="">Pilih Kode Unit Packing</option>
                            <?php foreach ($unit_packing as $iunitpacking):?>
                                <option value="<?php echo $iunitpacking->i_unit_packing;?>"
                                    <?php if($iunitpacking->i_unit_packing==$data->i_unit_packing) { ?> selected="selected" <?php } ?>>
                                    <?php echo $iunitpacking->i_unit_packing;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Unit Packing</label>
                        <div class="col-sm-12">
                        <select name="enamapacking" class="form-control select2">
                            <option value="">Pilih Nama Unit Packing</option>
                            <?php foreach ($unit_packing as $enamapacking):?>
                                <option value="<?php echo $enamapacking->e_nama_packing;?>"  
                                    <?php if($enamapacking->e_nama_packing==$data->e_nama_packing) { ?> selected="selected" <?php } ?>>
                                    <?php echo $enamapacking->e_nama_packing;?></option>
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
