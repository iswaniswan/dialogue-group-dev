<?php if($sukses == TRUE && $kode != ''){ ?>
<div class="alert alert-success">
    <h4><i class="icon fa fa-check"></i> <b>Berhasil</b></h4>
    <h4>Kode : <b><?= $kode; ?></b></h4>
    <input type="hidden" id= "kode" name="kode" class="form-control"  value="<?=$kode?>">
</div>
<?php }elseif($sukses == TRUE){ ?>
<div class="alert alert-success">
    <h4><i class="icon fa fa-check"></i> <b>Berhasil</b></h4>
</div>
<?php }else{ ?>
<div class="alert alert-danger">
    <h4><i class="icon fa fa-ban"></i> Gagal!</h4>Maaf, Data Gagal Disimpan!
</div>
<?php } ?>
