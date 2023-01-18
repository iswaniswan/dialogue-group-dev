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
                    <div class="form-group row">
                        <label class="col-md-4">Kode</label>
                        <label class="col-md-8">Nama Barang Jadi</label>
                        <div class="col-sm-4">
                            <input type="text" name="ikodebrgwip" class="form-control" maxlength="30"  value="<?= $data->kode_brg_wip;?>" readonly>
                        </div>
                        <div class="col-sm-8">                            
                            <select name="ikodebrg" class="form-control select2" disabled="">
                            <option value="">Pilih Barang</option>
                            <?php foreach ($wip_barang as $ikodebrg):?>
                                <option value="<?php echo $ikodebrg->i_kodebrg;?>"
                                     <?php if($ikodebrg->i_kodebrg==$data->i_kodebrg) { ?> selected="selected" <?php } ?>>
                                    <?php echo $ikodebrg->i_kodebrg."-".$ikodebrg->e_namabrg;?></option>
                            <?php endforeach; ?>                            
                        </select>                        
                        </div>
                    </div>
                    </div>  
                    <div class="col-md-6">                  
                    <div class="form-group row">
                        <label class="col-md-4">Bulan</label>
                        <label class="col-md-4">Tahun</label>
                        <label class="col-md-4">Harga</label>
                        <div class="col-sm-4">
                            <select name="bulan" class="form-control select2" disabled="">
                                <option value="">Pilih Bulan</option>
                                <option value="01" <?php if($data->bulan =='01') { ?> selected <?php } ?>>Januari</option>
                                <option value="02" <?php if($data->bulan =='02') { ?> selected <?php } ?>>Febuari</option>
                                <option value="03" <?php if($data->bulan =='03') { ?> selected <?php } ?>>Maret</option>
                                <option value="04" <?php if($data->bulan =='04') { ?> selected <?php } ?>>April</option>
                                <option value="05" <?php if($data->bulan =='05') { ?> selected <?php } ?>>Mei</option>
                                <option value="06" <?php if($data->bulan =='06') { ?> selected <?php } ?>>Juni</option>
                                <option value="07" <?php if($data->bulan =='07') { ?> selected <?php } ?>>Juli</option>
                                <option value="08" <?php if($data->bulan =='08') { ?> selected <?php } ?>>Agustus</option>
                                <option value="09" <?php if($data->bulan =='09') { ?> selected <?php } ?>>September</option>
                                <option value="10" <?php if($data->bulan =='10') { ?> selected <?php } ?>>Oktober</option>
                                <option value="11" <?php if($data->bulan =='11') { ?> selected <?php } ?>>November</option>
                                <option value="12" <?php if($data->bulan =='12') { ?> selected <?php } ?>>Desember</option>
                            </select>
                        </div>
                         <div class="col-sm-4">
                            <input type="text" name="tahun" class="form-control" maxlength="4"  value="<?= $data->tahun;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="harga" class="form-control" maxlength="30"  value="<?= $data->harga;?>" readonly>
                        </div>
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