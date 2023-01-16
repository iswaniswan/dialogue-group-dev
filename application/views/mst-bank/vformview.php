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
                        <label class="col-md-3">Kode Bank</label>
                        <label class="col-md-5">Nama Bank</label>
                        <label class="col-md-4">Jenis Bank</label>
                        <div class="col-sm-3">
                            <input type="text" name="ibank" id="ibank" class="form-control" required="" onkeyup="gede(this)" value="<?=$data->i_bank;?>" placeholder="Kode Bank (Exp : BCA01)" readonly>
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="ebankname" class="form-control" value="<?=$data->e_bank_name;?>" onkeyup="gede(this)" placeholder="Nama Bank (Exp: BCA BANDUNG)" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="jenis" id="jenis" class="form-control select2" disabled="true">
                               <option value="<?=$data->i_jenis;?>"><?=$data->e_jenis_name;?></option>
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
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