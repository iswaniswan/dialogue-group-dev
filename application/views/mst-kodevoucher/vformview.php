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
                <div class="form-group">
                        <label class="col-md-12">ID Voucher</label>
                        <div class="col-sm-12">
                            <input type="text" name="ivoucher" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_voucher; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Voucher</label>
                        <div class="col-sm-12">
                            <input type="text" name="ikodevoucher" class="form-control" maxlength="5"  value="<?= $data->i_voucher_code; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Jenis Voucher</label>
                        <div class="col-sm-12">
                            <select name="ijenis" class="form-control select2" readonly>
                            <option value="">Pilih Jenis Voucher</option>
                                <?php foreach($jenis_voucher as $ijenis): ?>
                                <option value="<?php echo $ijenis->i_jenis;?>" 
                                <?php if($ijenis->i_jenis==$data->i_jenis) { ?> selected="selected" <?php } ?>>
                                <?php echo $ijenis->e_jenis_voucher;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Nama Voucher</label>
                        <div class="col-sm-12">
                        <select name="evouchername" class="form-control select2" readonly>
                            <option value="">Pilih Nama Voucher</option>
                            <option value="reciept" <?php if($data->e_voucher_name =='reciept') { ?> selected <?php } ?>>Reciept</option>
                            <option value="payment" <?php if($data->e_voucher_name =='payment') { ?> selected <?php } ?>>Payment</option>
                        </select>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="edescription" class="form-control" maxlength="30"  value="<?= $data->e_description; ?>" readonly>
                        </div>
                    </div>                                 
            </div>
        </div>

            </div>
        </div>
    </div>
</div>