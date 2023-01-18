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
                        <label class="col-md-12">Nama Barang Jadi</label>
                        <div class="col-sm-12">                            
                            <select name="ikodebrg" class="form-control select2">
                            <option value="">Pilih Barang</option>
                            <?php foreach ($wip_barang as $ikodebrg):?>
                                <option value="<?php echo $ikodebrg->i_kodebrg;?>">
                                    <?php echo $ikodebrg->i_kodebrg."-".$ikodebrg->e_namabrg;?></option>
                            <?php endforeach; ?>                            
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
                    <div class="form-group row">
                        <label class="col-md-4">Bulan</label>
                        <label class="col-md-4">Tahun</label>
                        <label class="col-md-4">Harga</label>
                        <div class="col-sm-4">
                            <select name="bulan" class="form-control select2">
                                <option value="">Pilih Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Febuari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="tahun" class="form-control" maxlength="4"  value="" >
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="harga" class="form-control" maxlength="30"  value="" >
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

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
