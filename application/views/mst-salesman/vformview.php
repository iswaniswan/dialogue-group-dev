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
                            <label class="col-md-2">Kode Sales</label>
                            <label class="col-md-4">Nama Sales</label>
                            <label class="col-md-3">Area</label>
                            <label class="col-md-3">Kota</label>
                            <div class="col-sm-2">
                                <input type="hidden" readonly="" name="id" value="<?= $data->id; ?>">
                                <input type="text" name="isales" id="isales" class="form-control input-sm" required="" value="<?= $data->i_sales; ?>" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="esales" id="esales" class="form-control input-sm" value="<?= $data->e_sales; ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="iarea" id="iarea" class="form-control select2" disabled="">
                                    <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ekota" id="ekota" class="form-control input-sm" value="<?=$data->e_kota;?>" readonly>
                            </div>   
                        </div>  
                        <div class="form-group row">       
                            <label class="col-md-3">Telepon</label>      
                            <label class="col-md-6">Alamat</label>
                            <label class="col-md-3">Kode Pos</label>   
                            <div class="col-sm-3">
                                <input type="text" name="etelepon" id="etelepon" class="form-control input-sm" value="<?=$data->e_telepon;?>" readonly>
                            </div>                                                  
                            <div class="col-sm-6">
                                <textarea class="form-control input-sm" name="ealamat" readonly><?=$data->e_alamat;?></textarea>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ekodepos" id="ekodepos" class="form-control input-sm" value="<?=$data->e_kodepos;?>" readonly>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select2").select2();
    });
</script>
