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
                    <div class="form-group">
                        <label class="col-md-12">Kode Akun Pajak</label>
                        <div class="col-sm-12">
                           <select name="iakunpajak" class="form-control select2">
                            <option value="">Pilih Kode Akun Pajak</option>
                            <?php foreach ($akun_pajak as $iakunpajak):?>
                                <option value="<?php echo $iakunpajak->i_akun_pajak;?>">
                                    <?php echo $iakunpajak->i_akun_pajak;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>                                
                    <div class="form-group">
                        <label class="col-md-12">Kode Jenis Setoran</label>
                        <div class="col-sm-12">
                            <input type="text" name="ijsetorpajak" class="form-control" maxlength="5"  value="" >
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Jenis Setoran/Uraian Pembayaran</label>
                        <div class="col-sm-12">
                            <input type="text" name="ejsetorpajak" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eketerangan" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
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
