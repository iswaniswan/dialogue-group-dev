<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>        
        <div class="panel-body table-responsive">
            <div id="pesan"></div>     
                <div class="col-md-12">
                  <div class="form-group row">
                        <label class="col-md-3">Kode</label>
                        <label class="col-md-3">Satuan Awal</label>
                        <label class="col-md-3">Satuan Konversi</label>
                        <label class="col-md-3">Angka Faktor</label>
                        <div class="col-sm-3">
                            <input type="text" name="kodekonversi" id="kodekonversi" class="form-control" maxlength="30" value="<?= $data->i_satuan_konversi; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="isatuanawal" class="form-control select2" disabled="true">
                                <option value="<?=$data->i_satuan_code;?>" selected="selected"><?=$data->e_satuan_awal;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                        <select name="isatuankonversi" class="form-control select2" disabled="true">
                            <option value="<?=$data->i_satuan_code_konversi;?>" selected="selected"><?=$data->e_satuan_konversi;?></option>
                        </select> 
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="eangkafaktorkonversi" class="form-control" maxlength="30"  value="<?= $data->n_angka_faktor_konversi;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Rumus Konversi</label>
                        <div class="col-sm-6">
                        <select name="irumuskonversi" id="irumuskonversi" class="form-control select2" disabled="true">
                            <option value="<?=$data->i_rumus_konversi;?>" selected="selected"><?=$data->e_rumus_konversi;?></option>
                        </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>   
                        </div>
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
