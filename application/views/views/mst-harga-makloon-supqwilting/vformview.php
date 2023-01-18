<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/view2/<?=$dfrom;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-5">Supplier</label> 
                        <label class="col-md-6">Kategori Barang</label>  
                        <div class="col-sm-5">
                            <input type="hidden" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" name="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_supplier_name; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" name="ikodekelompok" name="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>"readonly>
                             <input type="text" name="enamakelompok" name="enamakelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_nama; ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-5">Jenis Barang</label>
                        <label class="col-md-3">Tanggal Berlaku</label>
                        <label class="col-md-4">Include PPN</label>
                        <div class="col-sm-5">
                            <input type="text" name="ikodejenis" id="ikodejenis" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                             <input type="text" name="dberlaku" id="dberlaku" class="form-control date" readonly value="<?= $data->d_berlaku; ?>">
                        </div>
                        <div class="col-sm-3">
                             <select name="itipe" class="form-control select2" disabled="">
                                <option value="">Pilih Include PPN</option>
                                <option value="I" <?php if($data->i_tipe =='I') { ?> selected <?php } ?> >Ya</option>
                                <option value="E" <?php if($data->i_tipe =='E') { ?> selected <?php } ?> >Tidak</option> 
                            </select>  
                        </div>
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-5">Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label> 
                        <div class="col-sm-5">
                            <input type="text" name="kodebrg" id="kodebrg" class="form-control" required="" value="<?= $data->i_material; ?>" readonly>
                        </div>                         
                        <div class="col-sm-6">
                            <input type="text" name="namabrg" id="namabrg" class="form-control" required="" value="<?= $data->e_material_name; ?>"readonly>
                        </div>    
                    </div>
                    <div class="form-group row">
                        <label class="col-md-5">Harga</label>
                        <label class="col-md-6">Satuan</label>
                        <div class="col-sm-5">
                            <input type="text" name="harga" id="harga" class="form-control" required="" value="<?= $data->v_price; ?>" readonly>
                        </div>
                         <div class="col-sm-3">
                             <select name="isatuan" id="isatuan" class="form-control select2" disabled="">
                                <option value="">Pilih Satuan</option>
                                <?php foreach($satuan as $satuan): ?>
                                <option value="<?php echo $satuan->i_satuan_code;?>" 
                                <?php if($satuan->i_satuan_code==$data->i_satuan_code) { ?> selected="selected" <?php } ?>>
                                <?php echo $satuan->e_satuan;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div>  
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

</script>