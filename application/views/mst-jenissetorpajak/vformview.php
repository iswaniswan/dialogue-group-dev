<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>        
        <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                     <div class="form-group">
                        <label class="col-md-12">ID Setoran Pajak</label>
                        <div class="col-sm-12">
                            <input type="text" name="isetoran" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_setoran; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Akun Pajak</label>
                        <div class="col-sm-12">                           
                        <select name="iakunpajak" class="form-control select2" readonly>
                            <option value="">Pilih Kode Akun Pajak</option>
                                <?php foreach($akun_pajak as $iakunpajak): ?>
                            <option value="<?php echo $iakunpajak->i_akun_pajak;?>" 
                                <?php if($iakunpajak->i_akun_pajak==$data->i_akun_pajak) { ?> selected="selected" <?php } ?>>
                                <?php echo $iakunpajak->i_akun_pajak;?></option>
                                <?php endforeach; ?> 
                        </select>
                        </div>
                    </div>                                
                    <div class="form-group">
                        <label class="col-md-12">Kode Jenis Setoran</label>
                        <div class="col-sm-12">
                            <input type="text" name="ijsetorpajak" class="form-control" maxlength="5"  value="<?= $data->i_jsetor_pajak; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Jenis Setoran/Uraian Pembayaran</label>
                        <div class="col-sm-12">
                            <input type="text" name="ejsetorpajak" class="form-control" maxlength="30"  value="<?= $data->e_jsetor_pajak; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eketerangan" class="form-control" maxlength="30"  value="<?= $data->keterangan; ?>" readonly>
                        </div>
                    </div>                                                      
                </div>
            </div>
        </div>
    </div>
</div>