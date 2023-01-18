<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Berlaku</label>
                        <label class="col-md-3">Kategori Barang</label>      
                        <label class="col-md-3">Jenis Barang</label> 
                        <label class="col-md-3">Kode Barang</label> 
                        <div class="col-sm-3">
                            <input type="hidden" name="dfrom" id="dfrom" class="form-control input-sm" readonly value="<?=$dfrom;?>">
                            <input type="text" name="dberlaku" id="dberlaku" class="form-control input-sm date" readonly value="<?= $data->d_berlaku; ?>">
                            <input type="hidden" name="dberlakusebelum" id="dberlakusebelum" class="form-control input-sm date" readonly value="<?= date("d-m-Y",strtotime($data->d_berlaku));?>">
                            <input type="hidden" name="dakhirsebelum" id="dakhirsebelum" class="form-control input-sm date" readonly value="<?= date('Y-m-d', strtotime('-1 days', strtotime( $dberlaku )));?>">
                            <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?=$data->id;?>">
                        </div>  
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodekelompok" name="ikodekelompok" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>"readonly>
                            <input type="text" name="enamakelompok" name="enamakelompok" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->e_nama_kelompok; ?>"readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control input-sm" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                            <input type="text" name="ekodejenis" id="ekodejenis" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->e_type_name; ?>" readonly>
                        </div>  
                        <div class="col-sm-3">
                            <input type="hidden" name="kodebrg" id="kodebrg" class="form-control input-sm" required="" value="<?= $data->id_product_base; ?>" readonly>
                            <input type="text" name="ikodebrg" id="ikodebrg" class="form-control input-sm" required="" value="<?= $data->i_product_base; ?>" readonly>
                        </div> 
                    </div>
                    <div class="form-group row">       
                        <label class="col-md-3">Nama Barang</label>
                        <label class="col-md-2">Warna</label>
                        <label class="col-md-2">Jenis Barang</label>
                        <label class="col-md-3">Kode Harga</label>
                        <label class="col-md-2">Harga</label>
                        <div class="col-sm-3">
                            <input type="text" name="namabrg" id="namabrg" class="form-control input-sm" required="" value="<?= $data->e_product_basename; ?>"readonly>
                        </div>  
                        <div class="col-sm-2">
                            <input type="text" name="ecolor" id="ecolor" class="form-control input-sm" required="" value="<?= $data->e_color_name; ?>"readonly>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_jenis_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ikodeharga" id="ikodeharga" class="form-control input-sm" required="" value="<?= $data->e_harga; ?>"readonly>
                        </div>  
                        <div class="col-sm-2">
                            <input type="text" name="harga" id="harga" class="form-control input-sm" required="" value="<?= number_format($data->v_price,2); ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"> 
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });
</script>