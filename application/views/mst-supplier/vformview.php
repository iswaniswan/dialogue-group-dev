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
            <div id="pesan"></div>
           <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Kode Supplier</label>
                        <label class="col-md-4">Nama Supplier</label>
                        <label class="col-md-4">Alamat</label>
                        <label class="col-md-2">Kota</label>
                        <div class="col-sm-2">
                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->id?>" >
                            <input type="text" name="isupplier" id="isupplier" class="form-control input-sm" autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this)" value="<?= $data->i_supplier?>" readonly>
                            <input type="hidden" name="isupplierold" id="isupplierold" class="form-control" autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this)" value="<?= $data->i_supplier?>">
                        </div>                        
                        <div class="col-sm-4">
                            <input type="text" name="isuppliername" class="form-control input-sm" value="<?= $data->e_supplier_name?>" onkeyup="gede(this);clearname(this);" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="isupplieraddres" class="form-control input-sm" required onkeyup="gede(this)" readonly><?= $data->e_supplier_address; ?></textarea>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isuppliercity" class="form-control input-sm" required value="<?= $data->e_supplier_city?>" onkeyup="gede(this)" readonly>
                        </div>
                    </div>
                    <div class="form-group row">       
                        <label class="col-md-1">Kode Pos</label>                 
                        <label class="col-md-2">Telepon</label>
                        <label class="col-md-2">FAX</label>     
                        <label class="col-md-1"></label>                  
                        <label class="col-md-3">Nama Pemilik </label>
                        <label class="col-md-1">Diskon %</label>
                        <label class="col-md-2">TOP</label>
                        <div class="col-sm-1">
                            <input type="text" name="isupplierpostalcode" id="isupplierpostalcode" class="form-control input-sm"  maxlength="5" onkeypress="return angka(event)" value="<?= $data->e_supplier_postalcode; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isupplierphone" id="isupplierphone" class="form-control input-sm" required value="<?= $data->e_supplier_phone; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isupplierfax" class="form-control input-sm" value="<?= $data->e_supplier_fax; ?>" readonly>
                        </div> <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                            <input type="text" name="esupplierownername" class="form-control input-sm" required value="<?= $data->e_supplier_ownername; ?>" onkeyup="gede(this)" readonly>
                        </div> 
                        <div class="col-sm-1">
                            <input type="text" name="isupplierdiskon" id="isupplierdiskon" class="form-control input-sm" onkeypress="return angkahungkul(this);" value="<?= $data->n_diskon; ?>" readonly>
                        </div>
                        <div class="col-sm-1"> 
                            <input type="text" name="isuppliertoplength" id="isuppliertoplength" class="form-control input-sm" onkeypress="return hanyaAngka(this);" required="" maxlength="3" value="<?= $data->n_supplier_toplength; ?>" readonly>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-1">PKP</label>   
                        <label class="col-md-2">NPWP</label>
                        <label class="col-md-3">Nama NPWP</label>
                        <label class="col-md-6">Alamat NPWP</label> 
                        <?php 
                            $check = '';
                            if($data->f_pkp == 't') { $check = 'checked';}
                        ?>                       
                        <div class="col-sm-1">
                             <input type="checkbox" class="form-check-input"  name="isupplierpkp" id="isupplierpkp" onclick="wajibnpwp(this.value);" <?php echo $check;?> disabled>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isuppliernpwp" id="isuppliernpwp" class="form-control input-sm" value="<?= $data->i_supplier_npwp; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="esuppliernpwpname" id="esuppliernpwpname" class="form-control input-sm" onkeyup="gede(this)"  value="<?= $data->e_npwp_name; ?>" readonly>
                        </div>    
                        <div class="col-sm-6">
                            <textarea type="text" name="isuppliernpwpaddress" id="isuppliernpwpaddress" class="form-control input-sm" onkeyup="gede(this)" readonly><?= $data->e_npwp_address; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Bank</label>
                        <label class="col-md-3">Nomor Rekening </label>
                        <label class="col-md-4">Nama Rekening</label>
                        <label class="col-md-2">Include/Exclude</label> 
                        <div class="col-sm-3">
                            <select name="ibank" id="ibank" class="form-control select2" disabled="">
                                <option value="">Pilih Bank</option>
                                <?php foreach($bank as $row){?>
                                        <!-- <option value="<?=$row->i_bank;?>"><?=$row->e_bank_name;?></option> -->
                                        <?php if ($row->i_bank == $data->i_bank) { ?>
                                                <option value="<?= $row->i_bank ;?>" selected><?=$row->e_bank_name;?></option>
                                        <?php } else { ?>
                                                <option value="<?= $row->i_bank ;?>"><?=$row->e_bank_name;?></option>
                                        <?php } ?>
                                <?php } ?>
                            </select>
                        </div> 
                        <div class="col-sm-3">
                            <input type="text" name="inorekening" class="form-control input-sm" value="<?= $data->i_no_rekening; ?>" readonly>
                        </div>                   
                        <div class="col-sm-4">
                            <input type="text" name="enamarekening" class="form-control input-sm" value="<?= $data->e_nama_rekening; ?>" onkeyup="gede(this)" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="ftipepajak" id="ftipepajak" class="form-control select2" disabled="">
                                <option value="">Pilih Include/Exclude</option>
                                <?php foreach($typepajak as $row){?>
                                        <?php if ($data->i_type_pajak == $row->i_type_pajak) { ?>
                                            <option value="<?=$row->i_type_pajak;?>" selected><?=$row->e_type_pajak_name;?></option>
                                        <?php } else { ?>
                                            <option value="<?=$row->i_type_pajak;?>"><?=$row->e_type_pajak_name;?></option>
                                        <?php } ?>
                                <?php } ?>
                            </select>                          
                        </div>
                    </div>
                    <div class="form-group row">    
                        <label class="col-md-3">Kategori Partner</label>
                        <label class="col-md-3">Jenis Partner</label>
                        <label class="col-md-3">Level Perusahaan</label>
                        <label class="col-md-3">Kepala Partner Group</label>
                        <div class="col-sm-3">
                            <select name="isuppliergroup" id="isuppliergroup" class="form-control select2 input-sm" disabled="">
                                <option value="">Pilih Kategori Partner</option>
                                <?php foreach ($suppliergroup as $row):?>
                                    <?php if ($row->i_supplier_group == $data->i_supplier_group) { ?>
                                            <option value="<?= $row->i_supplier_group ;?>" selected><?=$row->e_supplier_group_name;?></option>
                                    <?php } else { ?>
                                            <option value="<?= $row->i_supplier_group ;?>"><?=$row->e_supplier_group_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>                        
                        <div class="col-sm-3">
                            <select name="itypeindustry" id="itypeindustry" class="form-control select2 input-sm" disabled="">
                               <option value="">Pilih Jenis</option>
                                <?php foreach ($typeindustry as $row):?>
                                    <?php if ($row->i_type_industry == $data->i_type_industry) { ?>
                                            <option value="<?= $row->i_type_industry ;?>" selected><?=$row->e_type_industry_name;?></option>
                                    <?php } else { ?>
                                            <option value="<?= $row->i_type_industry ;?>"><?=$row->e_type_industry_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="ilevelcompany" id="ilevelcompany" class="form-control select2 input-sm" onchange="getpusat(this.value);" disabled="">
                               <!--  <option value="">Level Perusahaan</option> -->
                                <?php foreach ($levelcompany as $row):?> 
                                     <?php if ($data->i_level == $row->i_level) { ?>
                                       <option value="<?php echo $row->i_level;?>" selected><?php echo $row->e_level_name;?></option>
                                    <?php } else { ?>
                                       <option value="<?php echo $row->i_level;?>"><?php echo $row->e_level_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <?php if($data->i_level == 'PLV00'){?>
                                <select disabled name="ikepalapusat" id="ikepalapusat" class="form-control select2" disabled="">
                                </select>
                            <?php }else{?>
                                <select name="ikepalapusat" id="ikepalapusat" class="form-control select2" disabled="">
                                   <!--  <option value="<?=$data->i_kepala_pusat?>"><?=$data->i_pusat;?></option> -->
                                    <?php foreach($kepalapusat as $row): ?>
                                        <?php if ($row->i_kepala_pusat == $data->i_kepala_pusat) { ?>
                                                <option value="<?= $row->i_kepala_pusat ;?>" selected><?=$row->e_pusat;?></option>
                                        <?php } else { ?>
                                                <option value="<?= $row->i_kepala_pusat ;?>"><?=$row->e_pusat;?></option>
                                        <?php } ?>
                                    
                                    <?php endforeach; ?> 
                                </select>
                            <?php }?>
                        </div>                        
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Makloon</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Jenis Pembelian</label>
                        <label class="col-md-3">Internal/Eksternal</label>
                        <div class="col-sm-3">
                            <select name="ijenismakloon[]" id="ijenismakloon" class="form-control select2" multiple="multiple" disabled=""> 
                                <?php if ($makloon) {
                                    foreach ($makloon as $kuy) {?>
                                        <option value="<?= $kuy->i_type_makloon;?>" selected><?= $kuy->e_type_makloon_name;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="ikategoriproduk[]" id="ikategoriproduk"  multiple="multiple" class="form-control select2" disabled="">
                                <?php if ($kategori) {
                                    foreach ($kategori as $kuy) {?>
                                        <option value="<?= $kuy->i_kode_kelompok;?>" selected><?= $kuy->e_nama_kelompok;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>   
                        <div class="col-sm-3">                        
                            <select name="jenis_pembelian" id="jenis_pembelian" disabled class="form-control input-sm  select2">
                                <option value="credit" <?php if ($data->jenis_pembelian=='credit') { ?> selected <?php } ?>>CREDIT</option>
                                <option value="cash" <?php if ($data->jenis_pembelian=='cash') { ?> selected <?php } ?>>CASH</option>
                            </select>
                        </div>   
                        <div class="col-sm-3">                        
                            <select name="inter_exter" id="inter_exter" disabled class="form-control input-sm select2">
                                <option value=""></option>
                                <?php if ($jahit->num_rows()>0) {
                                    foreach ($jahit->result() as $key) {?>
                                        <option value="<?= $key->id;?>" <?php if ($key->id==$data->id_kategori_jahit) { ?> selected <?php } ?>><?= $key->e_nama_kategori;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>                  
                    </div>            
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>                               
                </div>  
            </div> <!-- end row -->
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
</script>
