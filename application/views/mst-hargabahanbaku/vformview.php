<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal Berlaku</label>
                        <label class="col-md-6">Supplier</label> 
                        <div class="col-sm-6">
                            <input type="text" name="dberlaku" id="dberlaku" class="form-control" readonly value="<?= $data->d_berlaku; ?>">
                        </div>                        
                        <div class="col-sm-6">
                            <input type="hidden" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" name="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_supplier_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label>  
                        <div class="col-sm-6">
                            <input type="text" name="kodebrg" id="kodebrg" class="form-control" required="" value="<?= $data->i_material; ?>" readonly>
                        </div>                         
                        <div class="col-sm-6">
                            <input type="text" name="namabrg" id="namabrg" class="form-control" required="" value="<?= $data->e_material_name; ?>"readonly>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Harga dari Supplier</label>
                        <label class="col-md-6">Satuan dari Supplier</label>
                        <div class="col-sm-6">
                            <input type="text" name="harga" id="harga" class="form-control" required="" value="<?= $data->v_price; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <select name="isatuansupplier" id="isatuansupplier" class="form-control select2" onchange="konversi(this.value);" disabled="">
                               <option value="<?=$data->i_satuan_konversi;?>"><?=$data->e_satuan_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8"> 
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Kategori Barang</label>      
                        <label class="col-md-6">Jenis Barang</label>   
                        <div class="col-sm-6">
                            <input type="hidden" name="ikodekelompok" name="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>"readonly>
                            <input type="text" name="enamakelompok" name="enamakelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_nama_kelompok; ?>"readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                            <input type="text" name="ekodejenis" id="ekodejenis" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_type_name; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-6">Include PPN</label>
                        <label class="col-md-6">Min Order</label>      
                        <div class="col-sm-6">
                            <select name="itipe" class="form-control select2" disabled="">
                                <?php if($data->f_ppn == 't'){
                                        $fppn = 'Ya';
                                    }else{
                                        $fppn = 'Tidak';
                                    }
                                ?>
                                <option value="<?=$data->f_ppn;?>"><?=$fppn;?></option>
                            </select>  
                        </div>
                        <div class="col-sm-6">
                             <input type="text" name="norder" id="norder" class="form-control" required="" value="<?= $data->n_order; ?>" readonly>
                        </div>           
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-6">Harga Exclude</label>
                        <label class="col-md-6">Satuan Konversi</label> 
                        <div class="col-sm-6">
                            <input type="text" name="hargakonversi" id="hargakonversi" class="form-control" required="" value="<?= $data->v_harga_konversi; ?>" readonly>
                            <select name="konversiharga" id="konversiharga" style="display:none;"> 
                            </select>
                            <select name="angkafaktor" id="angkafaktor" style="display:none;"> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" name="isatuanperusahaan" id="isatuanperusahaan" class="form-control" required="" value="<?= $data->i_satuan_code; ?>"readonly>
                            <input type="hidden" name="satuanawal" name="satuanawal" class="form-control" required="" value="<?= $data->i_satuan_code; ?>"readonly>
                            <input type="text" name="esatuanperusahaan" name="esatuanperusahaan" class="form-control" required="" value="<?= $data->satuan_perusahaan; ?>"readonly>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-6">Kode Material Supplier</label>  
                        <label class="col-sm-6"></label>
                        <div class="col-sm-6">
                            <input type="text" readonly name="imaterialsupplier" id="imaterialsupplier" class="form-control" required="" value="<?= $data->i_material_supplier; ?>">
                        </div> 
                        <div class="col-sm-6"></div>
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