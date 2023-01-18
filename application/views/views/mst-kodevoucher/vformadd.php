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
                    <div class="form-group">
                        <label class="col-md-12">Jenis Voucher</label>
                        <div class="col-sm-12">
                            <select name="ijenis" class="form-control select2">
                            <option value="">Pilih Jenis Voucher</option>
                            <?php foreach ($jenis_voucher as $ijenis):?>
                                <option value="<?php echo $ijenis->i_jenis;?>">
                                    <?php echo $ijenis->e_jenis_voucher;?></option>
                            <?php endforeach; ?>
                        </select>  
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Nama Voucher</label>
                        <div class="col-sm-12">
                        <select name="evouchername" class="form-control select2">
                            <option value="">Pilih Nama Voucher</option>
                            <option value="reciept">Reciept</option>
                            <option value="payment">Payment</option>
                        </select>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="edescription" class="form-control" maxlength="30"  value="" >
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
